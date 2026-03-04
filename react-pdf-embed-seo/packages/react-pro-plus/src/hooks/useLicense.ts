/**
 * useLicense hook for remote license validation.
 *
 * Validates a license key against the License Dashboard API.
 * For security, prefer calling this from a server-side API route
 * rather than directly from the client.
 */

import { useState, useEffect, useCallback } from 'react';
import type { ProPlusLicenseStatus } from '../types';

interface LicenseState {
  valid: boolean;
  status: ProPlusLicenseStatus;
  type: string;
  plan: string;
  expiresAt: string | null;
  daysRemaining: number | null;
  siteLimit: number;
  activeSites: number;
  message: string;
}

interface UseLicenseOptions {
  /**
   * URL to your local validation API route (e.g., '/api/validate-license').
   * This avoids exposing the license key in client bundles.
   * If not provided, falls back to direct API call (not recommended for production).
   */
  validationEndpoint?: string;

  /**
   * License key to validate. Only used if validationEndpoint is not set.
   * For security, prefer using validationEndpoint with server-side key storage.
   */
  licenseKey?: string;

  /**
   * Whether to auto-validate on mount. Defaults to true.
   */
  autoValidate?: boolean;
}

interface UseLicenseReturn {
  license: LicenseState | null;
  loading: boolean;
  error: string | null;
  revalidate: () => Promise<void>;
}

/**
 * Hook for validating a license key against the License Dashboard API.
 *
 * Usage with server-side validation (recommended):
 * ```tsx
 * const { license, loading } = useLicense({
 *   validationEndpoint: '/api/validate-license',
 * });
 * ```
 *
 * Usage with direct validation (development only):
 * ```tsx
 * const { license, loading } = useLicense({
 *   licenseKey: process.env.NEXT_PUBLIC_PDF_LICENSE_KEY,
 * });
 * ```
 */
export function useLicense(options: UseLicenseOptions = {}): UseLicenseReturn {
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
        // Recommended: call your own API route that validates server-side.
        const res = await fetch(validationEndpoint, { method: 'POST' });
        if (!res.ok) {
          throw new Error(`Validation endpoint returned ${res.status}`);
        }
        data = await res.json();
      } else if (licenseKey) {
        // Fallback: direct API call (not recommended for production).
        const res = await fetch(
          'https://pdfviewer.drossmedia.de/wp-json/plm/v1/license/validate',
          {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
              license_key: licenseKey,
              platform: 'react',
            }),
          }
        );
        if (!res.ok) {
          throw new Error(`License API returned ${res.status}`);
        }
        data = await res.json();
      } else {
        setLicense(null);
        setLoading(false);
        return;
      }

      setLicense({
        valid: !!data.valid,
        status: (data.status as ProPlusLicenseStatus) || 'inactive',
        type: (data.type as string) || '',
        plan: (data.plan as string) || '',
        expiresAt: (data.expires_at as string) || null,
        daysRemaining: (data.days_remaining as number) ?? null,
        siteLimit: (data.site_limit as number) || 0,
        activeSites: (data.active_sites as number) || 0,
        message: (data.message as string) || '',
      });
    } catch (err) {
      const message = err instanceof Error ? err.message : 'License validation failed';
      setError(message);
      // Keep last known state on error (fail open).
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

export default useLicense;
