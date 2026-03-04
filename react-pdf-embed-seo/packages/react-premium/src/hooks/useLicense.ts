/**
 * useLicense hook for remote license validation (Premium).
 *
 * Validates a license key against the License Dashboard API.
 * For security, prefer calling this from a server-side API route.
 */

import { useState, useEffect, useCallback } from 'react';

export type PremiumLicenseStatus = 'valid' | 'expired' | 'grace_period' | 'invalid' | 'inactive';

export interface LicenseState {
  valid: boolean;
  status: PremiumLicenseStatus;
  type: string;
  plan: string;
  expiresAt: string | null;
  daysRemaining: number | null;
  message: string;
}

export interface UseLicenseOptions {
  /**
   * URL to your local validation API route (e.g., '/api/validate-license').
   * Recommended to avoid exposing the license key in client bundles.
   */
  validationEndpoint?: string;

  /**
   * License key to validate directly. Only use in development.
   */
  licenseKey?: string;

  /**
   * Whether to auto-validate on mount. Defaults to true.
   */
  autoValidate?: boolean;
}

export interface UseLicenseResult {
  license: LicenseState | null;
  loading: boolean;
  error: string | null;
  revalidate: () => Promise<void>;
}

const LICENSE_API_URL = 'https://pdfviewer.drossmedia.de/wp-json/plm/v1';

/**
 * Hook for validating a Premium license key.
 *
 * Usage:
 * ```tsx
 * const { license, loading } = useLicense({
 *   validationEndpoint: '/api/validate-license',
 * });
 * ```
 */
export function useLicense(options: UseLicenseOptions = {}): UseLicenseResult {
  const {
    validationEndpoint,
    licenseKey,
    autoValidate = true,
  } = options;

  const [license, setLicense] = useState<LicenseState | null>(null);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState<string | null>(null);

  const validate = useCallback(async () => {
    setLoading(true);
    setError(null);

    try {
      let data: Record<string, unknown>;

      if (validationEndpoint) {
        const res = await fetch(validationEndpoint, { method: 'POST' });
        if (!res.ok) throw new Error(`Validation endpoint returned ${res.status}`);
        data = await res.json();
      } else if (licenseKey) {
        const res = await fetch(`${LICENSE_API_URL}/license/validate`, {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ license_key: licenseKey, platform: 'react' }),
        });
        if (!res.ok) throw new Error(`License API returned ${res.status}`);
        data = await res.json();
      } else {
        setLicense(null);
        setLoading(false);
        return;
      }

      setLicense({
        valid: !!data.valid,
        status: (data.status as PremiumLicenseStatus) || 'inactive',
        type: (data.type as string) || '',
        plan: (data.plan as string) || '',
        expiresAt: (data.expires_at as string) || null,
        daysRemaining: (data.days_remaining as number) ?? null,
        message: (data.message as string) || '',
      });
    } catch (err) {
      setError(err instanceof Error ? err.message : 'License validation failed');
    } finally {
      setLoading(false);
    }
  }, [validationEndpoint, licenseKey]);

  useEffect(() => {
    if (autoValidate) {
      validate();
    }
  }, [autoValidate, validate]);

  return { license, loading, error, revalidate: validate };
}

/**
 * Server-side license validation (for Next.js API routes).
 */
export async function validateLicenseServer(
  licenseKey: string,
  apiUrl: string = LICENSE_API_URL
): Promise<Record<string, unknown>> {
  const response = await fetch(`${apiUrl}/license/validate`, {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ license_key: licenseKey, platform: 'react' }),
  });

  if (!response.ok) {
    throw new Error(`License validation failed: ${response.status}`);
  }

  return response.json();
}

export default useLicense;
