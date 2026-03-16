# Drupal PDF Embed SEO - Known Issues & Technical Debt

**Date:** 2026-03-16
**Version:** Free 1.2.17, Premium 1.2.17, Pro+ 1.3.2

---

## Known Issues

### KI-1: Rate Limiter TOCTOU Race Condition

**Module:** Premium
**File:** `src/Service/RateLimiter.php`
**Severity:** Low (theoretical)
**Impact:** Under extreme concurrency, rate limits could be bypassed by 1-2 requests

The rate limiter reads the attempt count, checks against the limit, then writes the incremented count. Between read and write, concurrent requests could pass the check.

**Workaround:** The window is extremely small and requires high concurrency on the same IP+action+target. For most deployments, this is not a practical concern.

**Proper fix:** Use atomic database operations:
```sql
UPDATE pdf_embed_seo_rate_limit
SET attempts = attempts + 1
WHERE identifier = :id AND attempts < :max
```

---

### KI-2: Missing Config Schema Files

**Module:** Premium, Pro+
**Impact:** Drupal config validation warnings in logs, no runtime impact

Neither `pdf_embed_seo_premium.schema.yml` nor `pdf_embed_seo_pro_plus.schema.yml` exist. Drupal uses these for config import/export validation.

**Impact when missing:**
- `drush config:export` / `config:import` may produce warnings
- Config inspector tools will show "unknown" for these configs
- No functional impact

---

### KI-3: Password Session Timeout Not Configurable

**Module:** Premium
**Impact:** Low - hardcoded timeout instead of using configured value

The `password_session_duration` setting exists in config but the session verification check uses a hardcoded value instead of reading the config.

---

### KI-4: No Automatic Cron for Data Retention

**Module:** Pro+
**Impact:** Without cron configuration, data retention policies are never applied

`ComplianceManager::applyRetentionPolicy()` exists but is not automatically called. Sites must configure cron to invoke it.

**Recommendation:** Add a `hook_cron()` implementation that calls `applyRetentionPolicy()` on a daily schedule.

---

## Technical Debt

### TD-1: DI Anti-Patterns in Premium Services

**Priority:** Medium
**Effort:** 2-3 hours

Several Premium services use static service resolution instead of constructor injection:

```php
// Found in multiple files:
$service = \Drupal::service('some.service');
$container = \Drupal::getContainer();
```

**Should be:**
```php
public function __construct(
  protected SomeService $someService,
) {}
```

**Files affected:**
- `PdfPremiumApiController.php` (some methods)
- Various Premium service files

### TD-2: Missing PHPUnit Tests for Pro+ Services

**Priority:** Medium
**Effort:** 4-6 hours

No automated tests exist for Pro+ services. Recommended test coverage:

| Service | Critical Methods to Test |
|---|---|
| `VersionManager` | `createVersion`, `restoreVersion`, `deleteVersion` |
| `AnnotationManager` | `create`, `update`, `delete`, permission checks |
| `WebhookDispatcher` | `dispatch`, `send`, signature generation/verification |
| `ComplianceManager` | `recordConsent`, `exportUserData`, `deleteUserData` |
| `TwoFactorAuth` | Token generation, verification, expiry |
| `AdvancedAnalytics` | `getDocumentMetrics`, `getTimeSeries`, `getHeatmapData` |

### TD-3: No Input Validation on JSON API Payloads

**Priority:** Medium
**Effort:** 2-3 hours

API controllers accept JSON payloads via `json_decode($request->getContent(), TRUE)` without validating the structure. Malformed or unexpected keys are silently ignored.

**Recommendation:** Add validation for required fields, types, and value ranges.

### TD-4: Missing Return Type Declarations

**Priority:** Low
**Effort:** 1 hour

Some older service methods lack PHP 8.1+ return type declarations. Not a functional issue but reduces IDE support and static analysis effectiveness.

### TD-5: Webhook Payload Size Limits

**Priority:** Low
**Effort:** 30 minutes

Webhook deliveries store full payloads without size limits. Very large payloads (e.g., full document data) could bloat the database.

**Recommendation:** Limit stored payload to 10KB and truncate response bodies.

### TD-6: No Database Transaction Wrapping

**Priority:** Low
**Effort:** 2 hours

Multi-step operations like `VersionManager::restoreVersion()` (unset all current, set new current) are not wrapped in database transactions. A failure between steps could leave the data in an inconsistent state.

**Recommendation:** Wrap multi-step operations in `$database->startTransaction()`.

---

## Resolved Issues (for reference)

| ID | Issue | Resolution | Commit |
|---|---|---|---|
| FIXED-001 | Pro+ module missing 9 controllers | Created all controllers | `6e690a4` |
| FIXED-002 | Pro+ missing settings form | Created ProPlusSettingsForm | `6e690a4` |
| FIXED-003 | 9 services using wrong table names | Fixed 60+ references | `f1fa042` |
| FIXED-004 | Schema column name mismatches | Rewrote 6 table schemas | `f1fa042` |
| FIXED-005 | Timestamp type mismatches | Changed to varchar(20) | `f1fa042` |
| FIXED-006 | 3 missing table schemas | Added heatmaps, 2fa_tokens, 2fa_secrets | `f1fa042` |
| FIXED-007 | Analytics column name errors | Fixed document_id -> pdf_document_id | `f1fa042` |
| FIXED-008 | AdvancedAnalytics SQL errors | Fixed FROM_UNIXTIME, cutoff types | `f1fa042` |
| FIXED-009 | Premium duplicate analytics table | Removed from Premium schema | `f1fa042` |
| FIXED-010 | WhiteLabel XSS vulnerability | addslashes -> json_encode | `817d66e` |
| FIXED-011 | Annotation permission bypass | Rewrote owner/non-owner checks | `817d66e` |
| FIXED-012 | Expiring link type juggling | Strict equality with int cast | `a36fcfd` |
| FIXED-013 | Null safety on PDF file access | Added null coalescing | `f1fa042` |
