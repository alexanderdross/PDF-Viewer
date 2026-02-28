import { z } from 'zod';

// === Enums ===

export const LicenseTypeEnum = z.enum(['premium', 'pro_plus']);
export const LicensePlanEnum = z.enum(['starter', 'professional', 'agency', 'enterprise', 'lifetime', 'dev']);
export const LicenseStatusEnum = z.enum(['active', 'expired', 'grace_period', 'revoked', 'inactive']);
export const PlatformEnum = z.enum(['wordpress', 'drupal', 'react']);

export type LicenseType = z.infer<typeof LicenseTypeEnum>;
export type LicensePlan = z.infer<typeof LicensePlanEnum>;
export type LicenseStatus = z.infer<typeof LicenseStatusEnum>;
export type Platform = z.infer<typeof PlatformEnum>;

// === API Request Schemas ===

export const ValidateRequestSchema = z.object({
  license_key: z.string().min(1),
  site_url: z.string().url(),
  platform: PlatformEnum,
});

export const ActivateRequestSchema = z.object({
  license_key: z.string().min(1),
  site_url: z.string().url(),
  platform: PlatformEnum,
  plugin_version: z.string().min(1),
  php_version: z.string().optional(),
  cms_version: z.string().optional(),
  node_version: z.string().optional(),
});

export const DeactivateRequestSchema = z.object({
  license_key: z.string().min(1),
  site_url: z.string().url(),
});

export const CheckRequestSchema = z.object({
  license_key: z.string().min(1),
  site_url: z.string().url(),
  plugin_version: z.string().min(1),
  platform: PlatformEnum,
});

export type ValidateRequest = z.infer<typeof ValidateRequestSchema>;
export type ActivateRequest = z.infer<typeof ActivateRequestSchema>;
export type DeactivateRequest = z.infer<typeof DeactivateRequestSchema>;
export type CheckRequest = z.infer<typeof CheckRequestSchema>;

// === API Response Types ===

export interface ValidateResponse {
  valid: boolean;
  status: LicenseStatus;
  type: LicenseType;
  plan: LicensePlan;
  expires_at: string | null;
  days_remaining: number | null;
  site_limit: number;
  active_sites: number;
  message: string;
}

export interface ActivateResponse {
  activated: boolean;
  activation_id?: string;
  status?: LicenseStatus;
  site_limit?: number;
  active_sites?: number;
  remaining_sites?: number;
  expires_at?: string | null;
  latest_version?: string;
  message: string;
  error?: string;
}

export interface DeactivateResponse {
  deactivated: boolean;
  active_sites: number;
  remaining_sites: number;
  message: string;
}

export interface CheckResponse {
  valid: boolean;
  status: LicenseStatus;
  expires_at: string | null;
  days_remaining: number | null;
  latest_version: string;
  update_available: boolean;
  update_url?: string;
  message: string | null;
  checked_at: string;
}

export interface HealthResponse {
  status: 'ok' | 'error';
  version: string;
  database: 'connected' | 'disconnected';
  timestamp: string;
}

export interface ApiError {
  error: string;
  message: string;
}

// === Dashboard Types ===

export interface DashboardStats {
  activeLicenses: number;
  activeInstallations: number;
  monthlyRevenue: number;
  expiringLicenses: number;
}

export interface GeoDistribution {
  countryCode: string;
  countryName: string;
  count: number;
}

export interface PlatformDistribution {
  platform: Platform;
  count: number;
}

export interface VersionDistribution {
  version: string;
  count: number;
}
