import crypto from 'crypto';

const GRACE_PERIOD_DAYS = 14;

const LICENSE_PATTERNS = {
  premium: /^PDF\$PRO#[A-Z0-9]{4}-[A-Z0-9]{4}@[A-Z0-9]{4}-[A-Z0-9]{4}![A-Z0-9]{4}$/,
  pro_plus: /^PDF\$PRO\+#[A-Z0-9]{4}-[A-Z0-9]{4}@[A-Z0-9]{4}-[A-Z0-9]{4}![A-Z0-9]{4}$/,
  unlimited: /^PDF\$UNLIMITED#[A-Z0-9]{4}@[A-Z0-9]{4}![A-Z0-9]{4}$/,
  dev: /^PDF\$DEV#[A-Z0-9]{4}-[A-Z0-9]{4}@[A-Z0-9]{4}![A-Z0-9]{4}$/,
};

const LOCAL_PATTERNS = [
  /^localhost(:\d+)?$/,
  /^127\.0\.0\.1(:\d+)?$/,
  /\.local$/,
  /\.test$/,
  /\.dev$/,
  /\.staging\./,
  /\.stage\./,
  /^10\.\d+\.\d+\.\d+/,
  /^192\.168\.\d+\.\d+/,
];

function randomSegment(length: number): string {
  const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
  const bytes = crypto.randomBytes(length);
  let result = '';
  for (let i = 0; i < length; i++) {
    result += chars[bytes[i] % chars.length];
  }
  return result;
}

export function generateLicenseKey(type: 'premium' | 'pro_plus' | 'unlimited' | 'dev'): string {
  const s = (n: number) => randomSegment(n);

  switch (type) {
    case 'premium':
      return `PDF$PRO#${s(4)}-${s(4)}@${s(4)}-${s(4)}!${s(4)}`;
    case 'pro_plus':
      return `PDF$PRO+#${s(4)}-${s(4)}@${s(4)}-${s(4)}!${s(4)}`;
    case 'unlimited':
      return `PDF$UNLIMITED#${s(4)}@${s(4)}!${s(4)}`;
    case 'dev':
      return `PDF$DEV#${s(4)}-${s(4)}@${s(4)}!${s(4)}`;
  }
}

export function detectLicenseType(key: string): 'premium' | 'pro_plus' | 'unlimited' | 'dev' | null {
  if (LICENSE_PATTERNS.pro_plus.test(key)) return 'pro_plus';
  if (LICENSE_PATTERNS.premium.test(key)) return 'premium';
  if (LICENSE_PATTERNS.unlimited.test(key)) return 'unlimited';
  if (LICENSE_PATTERNS.dev.test(key)) return 'dev';
  return null;
}

export function isValidLicenseFormat(key: string): boolean {
  return detectLicenseType(key) !== null;
}

export function isLocalDomain(siteUrl: string): boolean {
  try {
    const url = new URL(siteUrl);
    const hostname = url.hostname;
    return LOCAL_PATTERNS.some((pattern) => pattern.test(hostname));
  } catch {
    return false;
  }
}

export function normalizeSiteUrl(siteUrl: string): string {
  try {
    const url = new URL(siteUrl);
    return url.hostname.toLowerCase();
  } catch {
    return siteUrl.toLowerCase().replace(/^https?:\/\//, '').replace(/\/+$/, '');
  }
}

export function getDaysRemaining(expiresAt: Date | null): number | null {
  if (!expiresAt) return null;
  const now = new Date();
  const diff = expiresAt.getTime() - now.getTime();
  return Math.ceil(diff / (1000 * 60 * 60 * 24));
}

export function computeStatus(expiresAt: Date | null, currentStatus: string): string {
  if (currentStatus === 'revoked') return 'revoked';
  if (currentStatus === 'inactive') return 'inactive';
  if (!expiresAt) return 'active'; // Lifetime license

  const daysRemaining = getDaysRemaining(expiresAt);
  if (daysRemaining === null) return 'active';

  if (daysRemaining > 0) return 'active';
  if (daysRemaining >= -GRACE_PERIOD_DAYS) return 'grace_period';
  return 'expired';
}

export function maskLicenseKey(key: string): string {
  if (key.length < 10) return '****';
  const prefix = key.substring(0, key.indexOf('#') + 1);
  const suffix = key.substring(key.length - 4);
  return `${prefix}****${suffix}`;
}
