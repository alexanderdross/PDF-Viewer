import { NextRequest, NextResponse } from 'next/server';
import { prisma } from '@/lib/prisma';
import { checkRateLimit, rateLimitResponse } from '@/lib/rate-limit';
import { getDaysRemaining, computeStatus, normalizeSiteUrl, isLocalDomain } from '@/lib/license';
import { lookupIp, anonymizeIp } from '@/lib/geoip';
import { ActivateRequestSchema } from '@/types';
import type { ActivateResponse, ApiError } from '@/types';

export async function POST(request: NextRequest) {
  const ip = request.headers.get('x-forwarded-for')?.split(',')[0]?.trim() || 'unknown';

  // Rate limiting by IP (general) and by license key (activate-specific)
  const ipLimit = checkRateLimit(ip, 'api');
  if (!ipLimit.allowed) return rateLimitResponse(ipLimit);

  let body: unknown;
  try {
    body = await request.json();
  } catch {
    return NextResponse.json<ApiError>(
      { error: 'invalid_json', message: 'Request body must be valid JSON.' },
      { status: 400 },
    );
  }

  const parsed = ActivateRequestSchema.safeParse(body);
  if (!parsed.success) {
    return NextResponse.json<ApiError>(
      { error: 'validation_error', message: parsed.error.issues.map((i) => i.message).join(', ') },
      { status: 400 },
    );
  }

  const { license_key, site_url, platform, plugin_version, php_version, cms_version, node_version } = parsed.data;

  // Rate limiting by license key for activations
  const keyLimit = checkRateLimit(license_key, 'activate');
  if (!keyLimit.allowed) return rateLimitResponse(keyLimit);

  // Look up license
  const license = await prisma.license.findUnique({
    where: { licenseKey: license_key },
    include: {
      installations: {
        where: { isActive: true, isLocal: false },
      },
    },
  });

  if (!license) {
    return NextResponse.json<ApiError>(
      { error: 'not_found', message: 'License key not found.' },
      { status: 404 },
    );
  }

  // Check license status
  const status = computeStatus(license.expiresAt, license.status);
  if (status === 'expired' || status === 'revoked' || status === 'inactive') {
    return NextResponse.json<ActivateResponse>(
      { activated: false, message: `Cannot activate: license is ${status}.` },
      { status: 403 },
    );
  }

  const normalizedUrl = normalizeSiteUrl(site_url);
  const isLocal = isLocalDomain(site_url);

  // Check if already activated for this site
  const existingInstallation = await prisma.installation.findFirst({
    where: {
      licenseId: license.id,
      siteUrl: normalizedUrl,
      isActive: true,
    },
  });

  if (existingInstallation) {
    // Update existing installation
    await prisma.installation.update({
      where: { id: existingInstallation.id },
      data: {
        pluginVersion: plugin_version,
        phpVersion: php_version || null,
        cmsVersion: cms_version || null,
        nodeVersion: node_version || null,
        lastCheckedAt: new Date(),
      },
    });

    const activeSites = license.installations.filter((i) => !i.isLocal).length;
    return NextResponse.json<ActivateResponse>({
      activated: true,
      activation_id: existingInstallation.id,
      status: status as ActivateResponse['status'],
      site_limit: license.siteLimit,
      active_sites: activeSites,
      remaining_sites: license.siteLimit === 0 ? -1 : license.siteLimit - activeSites,
      expires_at: license.expiresAt?.toISOString() || null,
      message: `License already active for ${normalizedUrl}. Installation updated.`,
    });
  }

  // Check site limit (0 = unlimited, local domains don't count)
  const activeSites = license.installations.filter((i) => !i.isLocal).length;
  if (!isLocal && license.siteLimit > 0 && activeSites >= license.siteLimit) {
    const activeDomains = license.installations.filter((i) => !i.isLocal).map((i) => i.siteUrl);
    return NextResponse.json(
      {
        activated: false,
        error: 'site_limit_reached',
        site_limit: license.siteLimit,
        active_sites: activeSites,
        active_domains: activeDomains,
        message: 'Site limit reached. Deactivate an existing site or upgrade your plan.',
      },
      { status: 403 },
    );
  }

  // Create new installation
  const installation = await prisma.installation.create({
    data: {
      licenseId: license.id,
      siteUrl: normalizedUrl,
      platform,
      pluginVersion: plugin_version,
      phpVersion: php_version || null,
      cmsVersion: cms_version || null,
      nodeVersion: node_version || null,
      isLocal,
    },
  });

  // Update license activated_at if first activation
  if (!license.activatedAt) {
    await prisma.license.update({
      where: { id: license.id },
      data: { activatedAt: new Date(), status: 'active' },
    });
  }

  // Geo-IP lookup
  const geoResult = await lookupIp(ip);
  if (geoResult) {
    await prisma.geoData.create({
      data: {
        installationId: installation.id,
        ipAnonymized: anonymizeIp(ip),
        countryCode: geoResult.countryCode,
        countryName: geoResult.countryName,
        region: geoResult.region,
        city: geoResult.city,
        latitude: geoResult.latitude,
        longitude: geoResult.longitude,
        continent: geoResult.continent,
        timezone: geoResult.timezone,
      },
    });
  }

  // Audit log
  await prisma.auditLog.create({
    data: {
      licenseId: license.id,
      eventType: 'license.activated',
      details: { site_url: normalizedUrl, platform, plugin_version, is_local: isLocal },
      ipAddress: anonymizeIp(ip),
      userAgent: request.headers.get('user-agent') || null,
    },
  });

  const newActiveSites = isLocal ? activeSites : activeSites + 1;

  return NextResponse.json<ActivateResponse>({
    activated: true,
    activation_id: installation.id,
    status: status as ActivateResponse['status'],
    site_limit: license.siteLimit,
    active_sites: newActiveSites,
    remaining_sites: license.siteLimit === 0 ? -1 : license.siteLimit - newActiveSites,
    expires_at: license.expiresAt?.toISOString() || null,
    latest_version: '1.3.0',
    message: `License activated successfully for ${normalizedUrl}`,
  });
}
