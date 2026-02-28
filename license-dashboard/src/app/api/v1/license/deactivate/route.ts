import { NextRequest, NextResponse } from 'next/server';
import { prisma } from '@/lib/prisma';
import { checkRateLimit, rateLimitResponse } from '@/lib/rate-limit';
import { normalizeSiteUrl } from '@/lib/license';
import { anonymizeIp } from '@/lib/geoip';
import { DeactivateRequestSchema } from '@/types';
import type { DeactivateResponse, ApiError } from '@/types';

export async function POST(request: NextRequest) {
  const ip = request.headers.get('x-forwarded-for')?.split(',')[0]?.trim() || 'unknown';
  const rateLimit = checkRateLimit(ip, 'api');
  if (!rateLimit.allowed) return rateLimitResponse(rateLimit);

  let body: unknown;
  try {
    body = await request.json();
  } catch {
    return NextResponse.json<ApiError>(
      { error: 'invalid_json', message: 'Request body must be valid JSON.' },
      { status: 400 },
    );
  }

  const parsed = DeactivateRequestSchema.safeParse(body);
  if (!parsed.success) {
    return NextResponse.json<ApiError>(
      { error: 'validation_error', message: parsed.error.issues.map((i) => i.message).join(', ') },
      { status: 400 },
    );
  }

  const { license_key, site_url } = parsed.data;
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

  // Find active installation
  const installation = await prisma.installation.findFirst({
    where: {
      licenseId: license.id,
      siteUrl: normalizedUrl,
      isActive: true,
    },
  });

  if (!installation) {
    return NextResponse.json<ApiError>(
      { error: 'not_found', message: `No active installation found for ${normalizedUrl}.` },
      { status: 404 },
    );
  }

  // Deactivate
  await prisma.installation.update({
    where: { id: installation.id },
    data: { isActive: false, deactivatedAt: new Date() },
  });

  // Audit log
  await prisma.auditLog.create({
    data: {
      licenseId: license.id,
      eventType: 'license.deactivated',
      details: { site_url: normalizedUrl },
      ipAddress: anonymizeIp(ip),
      userAgent: request.headers.get('user-agent') || null,
    },
  });

  // Count remaining active sites
  const activeSites = await prisma.installation.count({
    where: { licenseId: license.id, isActive: true, isLocal: false },
  });

  const response: DeactivateResponse = {
    deactivated: true,
    active_sites: activeSites,
    remaining_sites: license.siteLimit === 0 ? -1 : license.siteLimit - activeSites,
    message: `License deactivated for ${normalizedUrl}`,
  };

  return NextResponse.json(response);
}
