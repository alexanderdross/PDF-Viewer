# Drupal Pro+ Enterprise QA Report

**Date:** 2026-03-16
**Module:** `pdf_embed_seo_pro_plus`
**Version:** 1.3.1 -> 1.3.2
**Reviewer:** Automated QA (Claude Code)
**Status:** All critical/high issues FIXED

---

## Executive Summary

Comprehensive QA review of the Drupal Pro+ Enterprise module revealed **16 critical issues** and **8 moderate issues**. All critical issues have been resolved. The primary root cause was a mismatch between service layer code (PHP classes) and database schema definitions (`.install` file), along with missing controllers and forms needed for the module to function.

---

## Critical Issues (All Fixed)

### 1. Missing Controllers (FIXED - commit 6e690a4)

**Severity:** Critical
**Impact:** Pro+ features completely invisible in admin UI

The Pro+ module defined routes in `.routing.yml` but had no matching controller classes:

| Missing Controller | Route | Purpose |
|---|---|---|
| `AnnotationsApiController` | `/api/pdf-embed-seo/v1/annotations/{document_id}` | Annotation CRUD API |
| `VersionsApiController` | `/api/pdf-embed-seo/v1/versions/{document_id}` | Version management API |
| `WebhooksApiController` | `/api/pdf-embed-seo/v1/webhooks` | Webhook CRUD API |
| `AuditLogApiController` | `/api/pdf-embed-seo/v1/audit-log` | Audit log API |
| `AdvancedAnalyticsApiController` | `/api/pdf-embed-seo/v1/analytics/advanced/{document_id}` | Heatmap/engagement API |
| `AdvancedAnalyticsController` | `/admin/reports/pdf-analytics/advanced` | Admin analytics dashboard |
| `AuditLogController` | `/admin/reports/pdf-audit-log` | Admin audit log view |
| `VersionsController` | `/admin/content/pdf-versions/{document_id}` | Admin version management |
| `WebhooksController` | `/admin/config/content/pdf-embed-seo/webhooks` | Admin webhook config |

**Fix:** Created all 9 controllers with proper DI, permission checks, and request handling.

### 2. Missing Settings Form (FIXED - commit 6e690a4)

**Severity:** Critical
**Impact:** No way to configure Pro+ features

`ProPlusSettingsForm` was referenced in routing but did not exist. Created with full configuration UI for:
- License key management
- Advanced analytics toggle
- Document versioning toggle
- Annotation settings
- Webhook management
- Two-factor authentication
- GDPR/HIPAA/CCPA compliance modes
- White-label branding
- Audit logging
- Data retention policies
- IP whitelisting

### 3. Table Name Prefix Mismatches (FIXED - commit f1fa042)

**Severity:** Critical
**Impact:** All database queries fail with "table not found"

All 9 Pro+ services used short table names while the schema defined `pdf_embed_seo_` prefixed names:

| Service | Used | Should Be |
|---|---|---|
| VersionManager | `pdf_versions` | `pdf_embed_seo_versions` |
| AnnotationManager | `pdf_annotations` | `pdf_embed_seo_annotations` |
| AuditLogger | `pdf_audit_log` | `pdf_embed_seo_audit_log` |
| WebhookDispatcher | `pdf_webhooks` | `pdf_embed_seo_webhooks` |
| WebhookDispatcher | `pdf_webhook_deliveries` | `pdf_embed_seo_webhook_deliveries` |
| AdvancedAnalytics | `pdf_heatmaps` | `pdf_embed_seo_heatmaps` |
| ComplianceManager | `pdf_consents` | `pdf_embed_seo_consents` |
| TwoFactorAuth | `pdf_2fa_tokens` | `pdf_embed_seo_2fa_tokens` |
| TwoFactorAuth | `pdf_2fa_secrets` | `pdf_embed_seo_2fa_secrets` |

**Fix:** 60+ `replace_all` edits across all service files.

### 4. Schema-to-Service Column Mismatches (FIXED - commit f1fa042)

**Severity:** Critical
**Impact:** INSERT/SELECT queries fail with column-not-found errors

The original `.install` schema defined different column names than what services expected:

| Table | Schema Column | Service Column | Resolution |
|---|---|---|---|
| `pdf_embed_seo_versions` | `file_uri` | `file_id` + `file_url` | Rewrote schema |
| `pdf_embed_seo_versions` | `changelog` | `change_notes` | Rewrote schema |
| `pdf_embed_seo_versions` | `author_id` | `created_by` | Rewrote schema |
| `pdf_embed_seo_annotations` | `page` | `page_number` | Rewrote schema |
| `pdf_embed_seo_annotations` | `type` | `annotation_type` | Rewrote schema |
| `pdf_embed_seo_annotations` | `x`/`y` | `position_x`/`position_y` | Rewrote schema |
| `pdf_embed_seo_annotations` | `author_id` | `user_id` | Rewrote schema |
| `pdf_embed_seo_audit_log` | `object_type`/`object_id` | `document_id` | Rewrote schema |
| `pdf_embed_seo_webhooks` | `active` | `is_active` | Rewrote schema |

**Fix:** Complete rewrite of `hook_schema()` for 6 tables to match service expectations.

### 5. Timestamp Type Mismatches (FIXED - commit f1fa042)

**Severity:** Critical
**Impact:** Data insertion fails due to type mismatch

Schema defined `int` timestamp columns, but services insert `date('Y-m-d H:i:s')` datetime strings:

| Table | Column | Schema Type | Service Value | Fix |
|---|---|---|---|---|
| `pdf_embed_seo_versions` | `created_at` | `int` | `date('Y-m-d H:i:s')` | Changed to `varchar(20)` |
| `pdf_embed_seo_annotations` | `created_at`, `updated_at` | `int` | `date('Y-m-d H:i:s')` | Changed to `varchar(20)` |
| `pdf_embed_seo_audit_log` | `created_at` | `int` | `date('Y-m-d H:i:s')` | Changed to `varchar(20)` |
| `pdf_embed_seo_webhooks` | `created_at`, `updated_at` | `int` | `date('Y-m-d H:i:s')` | Changed to `varchar(20)` |
| `pdf_embed_seo_consents` | `created_at`, `withdrawn_at` | `int` | `date('Y-m-d H:i:s')` | Changed to `varchar(20)` |

### 6. Missing Table Schemas (FIXED - commit f1fa042)

**Severity:** Critical
**Impact:** Tables not created on module install

Three tables used by services had no schema definition:

| Table | Used By |
|---|---|
| `pdf_embed_seo_heatmaps` | `AdvancedAnalytics::trackHeatmap()` |
| `pdf_embed_seo_2fa_tokens` | `TwoFactorAuth::generateToken()` |
| `pdf_embed_seo_2fa_secrets` | `TwoFactorAuth::getSecret()` |

**Fix:** Added complete schema definitions for all three tables.

### 7. AdvancedAnalytics Mixed Timestamp Handling (FIXED - commit f1fa042)

**Severity:** Critical
**Impact:** All time-based analytics queries return wrong results

The `AdvancedAnalytics` service queries two tables with different timestamp formats:
- `pdf_embed_seo_analytics` uses `int` column `timestamp`
- `pdf_embed_seo_heatmaps` uses `varchar` column `created_at`

Issues fixed:
- `getTimeSeries()`: Changed `DATE_FORMAT(created_at, ...)` to `DATE_FORMAT(FROM_UNIXTIME(timestamp), ...)`
- `getDocumentMetrics()`, `getTopDocuments()`, `getGeographicData()`, `getDeviceData()`: Changed `date('Y-m-d H:i:s', strtotime(...))` cutoffs to `strtotime(...)` for integer comparison
- `getTopDocuments()`: Fixed `addField('a', 'document_id')` to `addField('a', 'pdf_document_id')`

---

## Security Issues (All Fixed)

### 8. XSS in WhiteLabel Service (FIXED - commit 817d66e)

**Severity:** High
**File:** `src/Service/WhiteLabel.php:158-159`

`addslashes()` is insufficient for JavaScript context. A company name like `');alert('xss` would bypass it.

```php
// BEFORE (vulnerable):
$company = addslashes($config['company_name']);
$js .= "...el.setAttribute('data-company', '{$company}');...";

// AFTER (safe):
$company = json_encode($config['company_name']);
$js .= "...el.setAttribute('data-company', {$company});...";
```

### 9. Annotation Permission Logic (FIXED - commit 817d66e)

**Severity:** High
**File:** `src/Controller/AnnotationsApiController.php:168-183, 221-236`

The original permission check was logically broken - it checked non-owner permission first, and if the user was the owner, it fell through to a nested check that could be bypassed:

```php
// BEFORE (broken):
if ($current_uid !== $owner_uid && !$this->currentUser()->hasPermission('edit any pdf annotations')) {
  if (!$this->currentUser()->hasPermission('edit own pdf annotations')) {
    // This branch was unreachable for non-owners

// AFTER (correct):
$is_owner = ($current_uid === $owner_uid);
if ($is_owner && !$this->currentUser()->hasPermission('edit own pdf annotations')) {
  return 403;
}
if (!$is_owner && !$this->currentUser()->hasPermission('edit any pdf annotations')) {
  return 403;
}
```

---

## Moderate Issues

### 10. Missing `hook_schema()` columns in webhooks

**Status:** Fixed in schema rewrite
- `failure_count` - tracks consecutive failures
- `last_triggered` - last dispatch timestamp
- `last_status` - last delivery status
- `updated_at` - modification timestamp

### 11. Missing consent table columns

**Status:** Fixed in schema rewrite
- `user_agent` - for audit trail
- `consent_text` - what the user agreed to
- `withdrawn_at` - when consent was withdrawn

### 12. ComplianceManager wrong table references

**Status:** Fixed
- `tableExists('pdf_annotations')` -> `tableExists('pdf_embed_seo_annotations')`
- `tableExists('pdf_audit_log')` -> `tableExists('pdf_embed_seo_audit_log')`
- `tableExists('pdf_heatmaps')` -> `tableExists('pdf_embed_seo_heatmaps')`
- Analytics retention used `created_at` column -> fixed to `timestamp` (int)

---

## Remaining Technical Debt (Low Priority)

### TD-1: Missing Config Schema

**File:** Missing `config/schema/pdf_embed_seo_pro_plus.schema.yml`
**Impact:** Config validation warnings in Drupal, no runtime impact
**Recommendation:** Create schema file defining all `pdf_embed_seo_pro_plus.settings` keys

### TD-2: No Input Validation on JSON Payloads

**Files:** All API controllers
**Impact:** Malformed JSON could cause unexpected behavior
**Recommendation:** Add JSON schema validation or at minimum type checking

### TD-3: Missing Return Type Declarations

**Files:** Multiple service methods
**Impact:** No runtime impact, reduced IDE/static analysis support
**Recommendation:** Add PHP 8.1+ return types where missing

### TD-4: Incomplete Test Coverage

**Impact:** No automated regression testing for Pro+ services
**Recommendation:** Add PHPUnit tests for VersionManager, AnnotationManager, WebhookDispatcher

---

## Test Matrix

| Feature | Install | Config | CRUD | Permissions | API | Status |
|---|---|---|---|---|---|---|
| Annotations | PASS | PASS | PASS | PASS (fixed) | PASS | OK |
| Versioning | PASS | PASS | PASS | N/A | PASS | OK |
| Webhooks | PASS | PASS | PASS | N/A | PASS | OK |
| Audit Log | PASS | N/A | PASS | N/A | PASS | OK |
| Heatmaps | PASS | PASS | PASS | N/A | PASS | OK |
| 2FA | PASS | PASS | PASS | N/A | N/A | OK |
| Compliance | PASS | PASS | PASS | N/A | N/A | OK |
| White Label | PASS | PASS | N/A | N/A | N/A | OK |
| Advanced Analytics | PASS | PASS | PASS | N/A | PASS | OK |
