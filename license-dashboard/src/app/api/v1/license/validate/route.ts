import { NextRequest, NextResponse } from 'next/server';
import { prisma } from '@/lib/prisma';
import { checkRateLimit, rateLimitResponse } from '@/lib/rate-limit';
import { getDaysRemaining, computeStatus } from '@/lib/license';
import { ValidateRequestSchema } from '@/types';
import type { ValidateResponse, ApiError } from '@/types';

export async function POST(request: NextRequest) {
  // Rate limiting by IP
  const ip = request.headers.get('x-forwarded-for')?.split(',')[0]?.trim() || 'unknown';
  const rateLimit = checkRateLimit(ip, 'api');
  if (!rateLimit.allowed) return rateLimitResponse(rateLimit);

  // Parse and validate input
  let body: unknown;
  try {
    body = await request.json();
  } catch {
    return NextResponse.json<ApiError>(
      { error: 'invalid_json', message: 'Request body must be valid JSON.' },
      { status: 400 },
    );
  }

  const parsed = ValidateRequestSchema.safeParse(body);
  if (!parsed.success) {
    return NextResponse.json<ApiError>(
      { error: 'validation_error', message: parsed.error.issues.map((i) => i.message).join(', ') },
      { status: 400 },
    );
  }

  const { license_key } = parsed.data;

  // Look up license
  const license = await prisma.license.findUnique({
    where: { licenseKey: license_key },
    include: {
      installations: {
        where: { isActive: true, isLocal: false },
        select: { id: true },
      },
    },
  });

  if (!license) {
    return NextResponse.json<ApiError>(
      { error: 'not_found', message: 'License key not found.' },
      { status: 404 },
    );
  }

  // Compute current status
  const status = computeStatus(license.expiresAt, license.status);
  const daysRemaining = getDaysRemaining(license.expiresAt);
  const activeSites = license.installations.length;

  let message = 'License is valid.';
  if (status === 'expired') {
    message = 'License has expired. Please renew at https://pdfviewer.drossmedia.de';
  } else if (status === 'grace_period') {
    message = `License is in grace period. Features will be disabled in ${14 + (daysRemaining || 0)} days.`;
  } else if (status === 'revoked') {
    message = 'License has been revoked.';
  }

  const response: ValidateResponse = {
    valid: status === 'active' || status === 'grace_period',
    status: status as ValidateResponse['status'],
    type: license.type as ValidateResponse['type'],
    plan: license.plan as ValidateResponse['plan'],
    expires_at: license.expiresAt?.toISOString() || null,
    days_remaining: daysRemaining,
    site_limit: license.siteLimit,
    active_sites: activeSites,
    message,
  };

  return NextResponse.json(response);
}
