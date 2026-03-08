/**
 * License validation utilities
 */

import type { ProPlusLicenseStatus } from '../types';

interface LicenseValidationResult {
  isValid: boolean;
  status: ProPlusLicenseStatus;
  type: 'pro_plus' | 'unlimited' | 'dev' | 'invalid';
  message: string;
}

/**
 * Validate Pro+ license key format
 * Formats:
 * - Pro+: PDF$PRO+#XXXX-XXXX@XXXX-XXXX!XXXX
 * - Unlimited: PDF$UNLIMITED#XXXX@XXXX!XXXX
 * - Dev: PDF$DEV#XXXX-XXXX@XXXX!XXXX
 */
export function validateProPlusLicense(licenseKey: string): LicenseValidationResult {
  if (!licenseKey || typeof licenseKey !== 'string') {
    return {
      isValid: false,
      status: 'inactive',
      type: 'invalid',
      message: 'No license key provided',
    };
  }

  const key = licenseKey.trim().toUpperCase();

  // Pro+ Enterprise license format
  const proPlusPattern = /^PDF\$PRO\+#[A-Z0-9]{4}-[A-Z0-9]{4}@[A-Z0-9]{4}-[A-Z0-9]{4}![A-Z0-9]{4}$/;
  if (proPlusPattern.test(key)) {
    return {
      isValid: true,
      status: 'valid',
      type: 'pro_plus',
      message: 'Valid Pro+ Enterprise license',
    };
  }

  // Unlimited license format
  const unlimitedPattern = /^PDF\$UNLIMITED#[A-Z0-9]{4}@[A-Z0-9]{4}![A-Z0-9]{4}$/;
  if (unlimitedPattern.test(key)) {
    return {
      isValid: true,
      status: 'valid',
      type: 'unlimited',
      message: 'Valid unlimited license',
    };
  }

  // Development license format
  const devPattern = /^PDF\$DEV#[A-Z0-9]{4}-[A-Z0-9]{4}@[A-Z0-9]{4}![A-Z0-9]{4}$/;
  if (devPattern.test(key)) {
    return {
      isValid: true,
      status: 'valid',
      type: 'dev',
      message: 'Valid development license',
    };
  }

  return {
    isValid: false,
    status: 'invalid',
    type: 'invalid',
    message: 'Invalid license key format',
  };
}

/**
 * Mask a license key for display
 */
export function maskLicenseKey(licenseKey: string): string {
  if (!licenseKey || licenseKey.length <= 10) {
    return '*'.repeat(licenseKey?.length || 0);
  }

  const prefix = licenseKey.slice(0, 8);
  const suffix = licenseKey.slice(-4);
  const middle = '*'.repeat(Math.max(0, licenseKey.length - 12));

  return `${prefix}${middle}${suffix}`;
}

/**
 * Check if license is expired
 */
export function isLicenseExpired(expiresAt: string | undefined): boolean {
  if (!expiresAt) {
    return false;
  }

  const expiryDate = new Date(expiresAt);
  return expiryDate < new Date();
}

/**
 * Check if license is in grace period (14 days after expiry)
 */
export function isInGracePeriod(expiresAt: string | undefined): boolean {
  if (!expiresAt) {
    return false;
  }

  const expiryDate = new Date(expiresAt);
  const now = new Date();
  const graceEndDate = new Date(expiryDate);
  graceEndDate.setDate(graceEndDate.getDate() + 14);

  return expiryDate < now && now < graceEndDate;
}

/**
 * Get days until license expires
 */
export function getDaysUntilExpiry(expiresAt: string | undefined): number | null {
  if (!expiresAt) {
    return null;
  }

  const expiryDate = new Date(expiresAt);
  const now = new Date();
  const diffTime = expiryDate.getTime() - now.getTime();
  const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));

  return diffDays;
}

/**
 * License API base URL for remote validation.
 */
const LICENSE_API_URL = 'https://pdfviewer.drossmedia.de/wp-json/plm/v1';

/**
 * Remote license validation response.
 */
export interface RemoteLicenseResponse {
  valid: boolean;
  status: string;
  type: string;
  plan: string;
  expires_at: string | null;
  days_remaining: number | null;
  site_limit: number;
  active_sites: number;
  message: string;
}

/**
 * Validate a license key remotely against the License Dashboard API.
 * Should be called server-side (e.g., Next.js API route) to avoid exposing keys.
 */
export async function validateLicenseRemote(
  licenseKey: string,
  apiUrl: string = LICENSE_API_URL
): Promise<RemoteLicenseResponse> {
  const response = await fetch(`${apiUrl}/license/validate`, {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({
      license_key: licenseKey,
      platform: 'react',
    }),
  });

  if (!response.ok) {
    throw new Error(`License validation failed: ${response.status}`);
  }

  return response.json();
}

/**
 * Activate a license on the License Dashboard API.
 * Should be called server-side.
 */
export async function activateLicenseRemote(
  licenseKey: string,
  siteUrl: string,
  pluginVersion: string = '1.3.0',
  apiUrl: string = LICENSE_API_URL
): Promise<Record<string, unknown>> {
  const response = await fetch(`${apiUrl}/license/activate`, {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({
      license_key: licenseKey,
      site_url: siteUrl,
      platform: 'react',
      plugin_version: pluginVersion,
      node_version: typeof process !== 'undefined' ? process.version : 'unknown',
    }),
  });

  if (!response.ok) {
    throw new Error(`License activation failed: ${response.status}`);
  }

  return response.json();
}

/**
 * Deactivate a license on the License Dashboard API.
 * Should be called server-side.
 */
export async function deactivateLicenseRemote(
  licenseKey: string,
  siteUrl: string,
  apiUrl: string = LICENSE_API_URL
): Promise<Record<string, unknown>> {
  const response = await fetch(`${apiUrl}/license/deactivate`, {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({
      license_key: licenseKey,
      site_url: siteUrl,
    }),
  });

  if (!response.ok) {
    throw new Error(`License deactivation failed: ${response.status}`);
  }

  return response.json();
}

/**
 * Send a heartbeat check to the License Dashboard API.
 * Should be called server-side, typically from a cron job or API route.
 */
export async function heartbeatCheck(
  licenseKey: string,
  siteUrl: string,
  pluginVersion: string = '1.3.0',
  apiUrl: string = LICENSE_API_URL
): Promise<RemoteLicenseResponse> {
  const response = await fetch(`${apiUrl}/license/check`, {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({
      license_key: licenseKey,
      site_url: siteUrl,
      plugin_version: pluginVersion,
      platform: 'react',
    }),
  });

  if (!response.ok) {
    throw new Error(`License heartbeat failed: ${response.status}`);
  }

  return response.json();
}

export default {
  validateProPlusLicense,
  maskLicenseKey,
  isLicenseExpired,
  isInGracePeriod,
  getDaysUntilExpiry,
  validateLicenseRemote,
  activateLicenseRemote,
  deactivateLicenseRemote,
  heartbeatCheck,
};
