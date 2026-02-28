import { NextRequest, NextResponse } from 'next/server';
import { prisma } from '@/lib/prisma';
import { checkRateLimit, rateLimitResponse } from '@/lib/rate-limit';
import { getDaysRemaining, computeStatus, normalizeSiteUrl } from '@/lib/license';
import { lookupIp, anonymizeIp } from '@/lib/geoip';
import { CheckRequestSchema } from '@/types';
import type { CheckResponse, ApiError } from '@/types';

const LATEST_VERSION = '1.3.0';
const GEO_UPDATE_THRESHOLD_DAYS = 30;

export async function POST(request: NextRequest) {
  const ip = request.headers.get('x-forwarded-for')?.split(',')[0]?.trim() || 'unknown';

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

  const parsed = CheckRequestSchema.safeParse(body);
  if (!parsed.success) {
    return NextResponse.json<ApiError>(
      { error: 'validation_error', message: parsed.error.issues.map((i) => i.message).join(', ') },
      { status: 400 },
    );
  }

  const { license_key, site_url, plugin_version } = parsed.data;

  // Rate limit by license key for checks
  const keyLimit = checkRateLimit(license_key, 'check');
  if (!keyLimit.allowed) return rateLimitResponse(keyLimit);

  const normalizedUrl = normalizeSiteUrl(site_url);

  // Look up license
  const license = await prisma.license.findUnique({
    where: { licenseKey: license_key },
  });

  if (!license) {
    return NextResponse.json<ApiError>(
      { error: 'not_found', message: 'License key not found.' },
      { status: 404 },
    );
  }

  const status = computeStatus(license.expiresAt, license.status);
  const daysRemaining = getDaysRemaining(license.expiresAt);
  const updateAvailable = plugin_version !== LATEST_VERSION;

  // Update installation's last_checked_at and plugin_version
  const installation = await prisma.installation.findFirst({
    where: {
      licenseId: license.id,
      siteUrl: normalizedUrl,
      isActive: true,
    },
    include: { geoData: true },
  });

  if (installation) {
    await prisma.installation.update({
      where: { id: installation.id },
      data: {
        lastCheckedAt: new Date(),
        pluginVersion: plugin_version,
      },
    });

    // Update geo data if older than threshold
    if (installation.geoData) {
      const geoAge = Date.now() - installation.geoData.updatedAt.getTime();
      const thresholdMs = GEO_UPDATE_THRESHOLD_DAYS * 24 * 60 * 60 * 1000;

      if (geoAge > thresholdMs) {
        const geoResult = await lookupIp(ip);
        if (geoResult) {
          await prisma.geoData.update({
            where: { id: installation.geoData.id },
            data: {
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
      }
    }
  }

  // Build message
  let message: string | null = null;
  if (daysRemaining !== null && daysRemaining <= 14 && daysRemaining > 0) {
    message = `Your license expires in ${daysRemaining} day${daysRemaining === 1 ? '' : 's'}. Renew at https://pdfviewer.drossmedia.de`;
  } else if (status === 'grace_period') {
    const graceDaysLeft = 14 + (daysRemaining || 0);
    message = `Your license is in grace period. Features will be disabled in ${graceDaysLeft} day${graceDaysLeft === 1 ? '' : 's'}.`;
  } else if (updateAvailable) {
    message = `A new version (${LATEST_VERSION}) is available. Update recommended.`;
  }

  const response: CheckResponse = {
    valid: status === 'active' || status === 'grace_period',
    status: status as CheckResponse['status'],
    expires_at: license.expiresAt?.toISOString() || null,
    days_remaining: daysRemaining,
    latest_version: LATEST_VERSION,
    update_available: updateAvailable,
    update_url: updateAvailable && (status === 'active' || status === 'grace_period')
      ? `https://pdfviewer.drossmedia.de/download/${license.type}/${LATEST_VERSION}`
      : undefined,
    message,
    checked_at: new Date().toISOString(),
  };

  return NextResponse.json(response);
}
