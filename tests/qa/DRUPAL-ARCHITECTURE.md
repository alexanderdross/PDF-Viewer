# Drupal PDF Embed SEO - Architecture Knowledge Base

**Date:** 2026-03-16
**Covers:** Free (1.2.17), Premium (1.2.17), Pro+ Enterprise (1.3.2)

---

## Module Hierarchy

```
pdf_embed_seo (Free Base Module)
  Required by all tiers. Owns the PdfDocument entity and analytics table.
  |
  +-- pdf_embed_seo_premium (Premium Add-on)
  |     Depends on: pdf_embed_seo
  |     Adds: analytics, passwords, progress, rate limiting, expiring links
  |
  +-- pdf_embed_seo_pro_plus (Pro+ Enterprise)
        Depends on: pdf_embed_seo, pdf_embed_seo_premium
        Adds: annotations, versioning, webhooks, 2FA, compliance, heatmaps, white-label
```

**Install order:** Free -> Premium -> Pro+ (enforced by `dependencies` in `.info.yml`)
**Uninstall order:** Pro+ -> Premium -> Free

---

## Entity: PdfDocument

**Type:** Content entity with translations and ownership
**Provider:** `pdf_embed_seo` (free module)
**Path:** `src/Entity/PdfDocument.php`

### Base Fields

| Field | Type | Required | Notes |
|---|---|---|---|
| `id` | serial | Auto | Primary key |
| `uuid` | uuid | Auto | Universal identifier |
| `title` | string(255) | Yes | Translatable |
| `description` | text_long | No | Translatable |
| `pdf_file` | file | Yes | Max 50MB, `application/pdf` |
| `thumbnail` | image | No | Auto-generated via ImageMagick/Ghostscript |
| `allow_download` | boolean | No | Default FALSE |
| `allow_print` | boolean | No | Default FALSE |
| `view_count` | integer | Computed | Uses `ComputedItemListTrait`, reads from analytics table |
| `password` | string(255) | No | Premium: bcrypt hashed |
| `status` | boolean | No | Published/unpublished |
| `uid` | user_id | Auto | Author/owner |
| `created` | created | Auto | Creation timestamp |
| `changed` | changed | Auto | Last modified |

### Computed View Count (Important Architecture Decision)

The `view_count` field does NOT store a value in the entity table. Instead, it reads the count directly from `pdf_embed_seo_analytics` on every access:

```php
// ComputedViewCount.php
$count = $database->select('pdf_embed_seo_analytics', 'a')
  ->condition('pdf_document_id', $entity_id)
  ->countQuery()
  ->execute()
  ->fetchField();
```

**Why:** Prevents entity saves on every page view (Drupal entity saves are expensive operations that clear caches, fire hooks, and write to multiple tables).

---

## Database Tables

### Ownership Model

Tables are owned by the module that defines them in `hook_schema()`. Higher-tier modules add columns to lower-tier tables via `hook_install()`.

```
Free module owns:
  pdf_embed_seo_analytics       -- base analytics (view tracking)

Premium module owns:
  pdf_embed_seo_progress        -- reading progress per user/document
  pdf_embed_seo_access_tokens   -- expiring link tokens
  pdf_embed_seo_rate_limit      -- brute force protection

Pro+ module owns:
  pdf_embed_seo_versions        -- document version history
  pdf_embed_seo_annotations     -- user annotations
  pdf_embed_seo_audit_log       -- compliance audit trail
  pdf_embed_seo_webhooks        -- webhook configurations
  pdf_embed_seo_webhook_deliveries -- webhook delivery history
  pdf_embed_seo_consents        -- GDPR consent records
  pdf_embed_seo_heatmaps        -- page interaction heatmaps
  pdf_embed_seo_2fa_tokens      -- 2FA verification tokens
  pdf_embed_seo_2fa_secrets     -- TOTP secrets
```

### Column Extension Pattern

Premium and Pro+ add columns to the base analytics table:

```php
// Premium adds (via hook_install):
// - time_spent (int) - seconds spent viewing
// - pages_viewed (int) - pages viewed in session
// - created (int) - unix timestamp

// Pro+ adds (via hook_install):
// - event_type (varchar 32) - view/download/print
// - session_id (varchar 64) - visitor session tracking
// - country (varchar 2) - ISO country code
// - device_type (varchar 20) - desktop/mobile/tablet
```

### Timestamp Convention

**IMPORTANT:** The codebase uses TWO different timestamp formats:

| Table | Column | Type | Format | Used By |
|---|---|---|---|---|
| `pdf_embed_seo_analytics` | `timestamp` | `int` | Unix timestamp | All tiers |
| `pdf_embed_seo_analytics` | `created` | `int` | Unix timestamp | Premium |
| All Pro+ tables | `created_at` | `varchar(20)` | `Y-m-d H:i:s` | Pro+ services |
| All Pro+ tables | `updated_at` | `varchar(20)` | `Y-m-d H:i:s` | Pro+ services |

When writing queries that span both analytics and Pro+ tables, use appropriate cutoff formats:
- Analytics: `$cutoff = strtotime("-30 days");` (integer)
- Pro+ tables: `$cutoff = date('Y-m-d H:i:s', strtotime("-30 days"));` (string)
- For SQL date functions on analytics: `FROM_UNIXTIME(timestamp)` to convert

---

## Service Architecture

### Free Services

| Service ID | Class | Purpose |
|---|---|---|
| `pdf_embed_seo.thumbnail_generator` | `ThumbnailGenerator` | PDF thumbnail via ImageMagick/Ghostscript |
| `pdf_embed_seo.analytics_tracker` | `AnalyticsTracker` | Basic view counting |

### Premium Services

| Service ID | Class | Purpose |
|---|---|---|
| `pdf_embed_seo.analytics_tracker` | `AnalyticsTracker` | Full analytics (overrides free) |
| `pdf_embed_seo.progress_tracker` | `ProgressTracker` | Reading progress per user |
| `pdf_embed_seo.schema_enhancer` | `SchemaEnhancer` | GEO/AEO/LLM schema optimization |
| `pdf_embed_seo.access_manager` | `AccessManager` | Role-based access control |
| `pdf_embed_seo.viewer_enhancer` | `ViewerEnhancer` | Search, bookmarks, progress bar |
| `pdf_embed_seo.bulk_operations` | `BulkOperations` | CSV/ZIP import |
| `pdf_embed_seo.rate_limiter` | `RateLimiter` | Brute force protection |
| `pdf_embed_seo.access_token_storage` | `AccessTokenStorage` | Expiring link management |

### Pro+ Services

| Service ID | Class | Purpose |
|---|---|---|
| `pdf_embed_seo_pro_plus.version_manager` | `VersionManager` | Document versioning |
| `pdf_embed_seo_pro_plus.annotation_manager` | `AnnotationManager` | Annotation CRUD |
| `pdf_embed_seo_pro_plus.audit_logger` | `AuditLogger` | Compliance audit logs |
| `pdf_embed_seo_pro_plus.webhook_dispatcher` | `WebhookDispatcher` | Webhook management & delivery |
| `pdf_embed_seo_pro_plus.advanced_analytics` | `AdvancedAnalytics` | Heatmaps, engagement scoring |
| `pdf_embed_seo_pro_plus.compliance_manager` | `ComplianceManager` | GDPR/HIPAA/CCPA compliance |
| `pdf_embed_seo_pro_plus.two_factor_auth` | `TwoFactorAuth` | 2FA token management |
| `pdf_embed_seo_pro_plus.white_label` | `WhiteLabel` | Custom branding |
| `pdf_embed_seo_pro_plus.license_validator` | `LicenseValidator` | License key validation |

---

## Controller Architecture

### Free Controllers

| Controller | Route Pattern | Method | Purpose |
|---|---|---|---|
| `PdfViewController` | `/pdf/{pdf_document}` | GET | Display single PDF |
| `PdfArchiveController` | `/pdf` | GET | Archive listing |
| `PdfDataController` | `/pdf-data/{pdf_document}` | GET | Secure PDF data (AJAX) |
| `PdfApiController` | `/api/pdf-embed-seo/v1/*` | Various | REST API |

### Premium Controllers

| Controller | Route Pattern | Method | Purpose |
|---|---|---|---|
| `PdfPremiumApiController` | `/api/pdf-embed-seo/v1/analytics*` | GET | Analytics endpoints |
| `PdfPremiumApiController` | `/api/pdf-embed-seo/v1/documents/{id}/progress` | GET/POST | Reading progress |
| `PdfPremiumApiController` | `/api/pdf-embed-seo/v1/documents/{id}/verify-password` | POST | Password verification |
| `PdfPremiumApiController` | `/api/pdf-embed-seo/v1/documents/{id}/expiring-link` | POST/GET | Expiring links |

### Pro+ Controllers

| Controller | Route Pattern | Purpose |
|---|---|---|
| `AnnotationsApiController` | `/api/.../annotations/{document_id}` | Annotation CRUD |
| `VersionsApiController` | `/api/.../versions/{document_id}` | Version management |
| `WebhooksApiController` | `/api/.../webhooks` | Webhook CRUD |
| `AuditLogApiController` | `/api/.../audit-log` | Audit log access |
| `AdvancedAnalyticsApiController` | `/api/.../analytics/advanced/{document_id}` | Heatmap/engagement API |
| `AdvancedAnalyticsController` | `/admin/reports/pdf-analytics/advanced` | Admin dashboard |
| `AuditLogController` | `/admin/reports/pdf-audit-log` | Admin audit log |
| `VersionsController` | `/admin/content/pdf-versions/{document_id}` | Admin version history |
| `WebhooksController` | `/admin/config/.../webhooks` | Admin webhook config |

---

## Permissions

### Free Permissions

| Permission | Description |
|---|---|
| `administer pdf embed seo` | Full admin access |
| `access pdf document overview` | View admin list |
| `view pdf document` | View published PDFs |
| `create pdf document` | Create new PDFs |
| `edit pdf document` | Edit any PDF |
| `edit own pdf document` | Edit own PDFs |
| `delete pdf document` | Delete any PDF |
| `delete own pdf document` | Delete own PDFs |

### Pro+ Permissions

| Permission | Description |
|---|---|
| `create pdf annotations` | Create annotations |
| `edit own pdf annotations` | Edit own annotations |
| `edit any pdf annotations` | Edit any annotation |
| `delete own pdf annotations` | Delete own annotations |
| `delete any pdf annotations` | Delete any annotation |
| `view pdf audit log` | View audit log |
| `manage pdf webhooks` | Configure webhooks |
| `manage pdf versions` | Manage document versions |

---

## Configuration

### Config Objects

| Config Name | Module | Purpose |
|---|---|---|
| `pdf_embed_seo.settings` | Free | Theme, height, archive settings |
| `pdf_embed_seo_premium.settings` | Premium | Analytics, password, progress settings |
| `pdf_embed_seo_pro_plus.settings` | Pro+ | All enterprise feature toggles |

### Key Pro+ Settings

```
enable_advanced_analytics: bool
enable_versioning: bool
enable_annotations: bool
enable_webhooks: bool
two_factor_enabled: bool
gdpr_mode: bool
hipaa_mode: bool
ccpa_mode: bool
enable_white_label: bool
enable_audit_log: bool
data_retention_days: int (365)
audit_log_retention: int (730)
consent_retention: int (1825)
heatmap_retention: int (90)
ip_whitelist: string (comma-separated IPs)
```

---

## Naming Conventions

### Table Names
All tables MUST use the `pdf_embed_seo_` prefix. This is a Drupal convention to prevent namespace collisions.

### Service IDs
- Free/Premium: `pdf_embed_seo.{service_name}`
- Pro+: `pdf_embed_seo_pro_plus.{service_name}`

### Config Keys
- Free: `pdf_embed_seo.settings`
- Premium: `pdf_embed_seo_premium.settings`
- Pro+: `pdf_embed_seo_pro_plus.settings`

### Hook Naming
- Alter hooks: `hook_pdf_embed_seo_{feature}_alter`
- Event hooks: `hook_pdf_embed_seo_{event}`

---

## Webhook System

### Event Types

| Event | Trigger |
|---|---|
| `document.created` | New PDF document saved |
| `document.updated` | PDF document edited |
| `document.deleted` | PDF document deleted |
| `document.viewed` | PDF viewed by user |
| `document.downloaded` | PDF downloaded |
| `password.attempt` | Password verification attempt |
| `annotation.created` | New annotation added |
| `version.created` | New version created |
| `consent.given` | User consent recorded |
| `data.exported` | User data exported (GDPR) |

### Delivery Flow

1. Event occurs -> `WebhookDispatcher::dispatch($event, $payload)` called
2. Gets all active webhooks subscribed to event
3. For each webhook: creates delivery record, sends HTTP POST
4. Updates delivery with response code, body, duration
5. On success: resets failure count
6. On failure: increments failure count
7. After 10 consecutive failures: auto-disables webhook

### Signature Verification

Webhooks use HMAC-SHA256 signatures:
```
X-Webhook-Signature: sha256=<hmac_hex_digest>
X-Webhook-Event: <event_type>
X-Webhook-Timestamp: <unix_timestamp>
```
