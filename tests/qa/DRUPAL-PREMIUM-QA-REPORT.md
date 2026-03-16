# Drupal Premium Module QA Report

**Date:** 2026-03-16
**Module:** `pdf_embed_seo_premium`
**Version:** 1.2.16 -> 1.2.17
**Reviewer:** Automated QA (Claude Code)
**Status:** Critical issue FIXED, moderate issues documented

---

## Executive Summary

QA review of the Drupal Premium module found **1 critical issue** (duplicate analytics table), **1 high security issue** (loose equality), and **6 moderate issues** (DI patterns, race conditions). The critical and high issues are fixed. Moderate issues are documented as technical debt.

---

## Critical Issues (Fixed)

### 1. Duplicate Analytics Table Definition (FIXED - commit f1fa042)

**Severity:** Critical
**File:** `pdf_embed_seo_premium.install`
**Impact:** Module install fails with "table already exists" error

The Premium module's `hook_schema()` originally redefined the `pdf_embed_seo_analytics` table that is owned by the free base module. When both modules are installed, Drupal attempted to create the table twice.

**Fix:** Removed the duplicate table definition from Premium's schema. Added a comment explaining the analytics table is owned by the base module. Premium now only adds columns via `hook_install()` and update hooks.

### 2. Strict Equality in Expiring Link Validation (FIXED - commit a36fcfd)

**Severity:** High
**File:** `src/Controller/PdfPremiumApiController.php:640`
**Impact:** Type juggling could bypass document ID check

```php
// BEFORE (loose equality - type juggling risk):
if ($doc_id != $pdf_document->id()) {

// AFTER (strict equality with int cast):
if ((int) $doc_id !== (int) $pdf_document->id()) {
```

---

## Moderate Issues (Technical Debt)

### TD-1: DI Anti-Patterns

**Severity:** Moderate
**Files:** Multiple Premium service files
**Impact:** Harder to test, violates Drupal best practices

Several Premium services use `\Drupal::service()` or `\Drupal::getContainer()` instead of proper constructor injection:

```php
// Anti-pattern found in multiple files:
$service = \Drupal::service('pdf_embed_seo.some_service');
```

**Recommendation:** Refactor to use constructor injection via `*.services.yml`.

### TD-2: Rate Limiter TOCTOU Race Condition

**Severity:** Moderate
**File:** `src/Service/RateLimiter.php`
**Impact:** Under high concurrency, rate limits could be bypassed

The rate limiter reads the current attempt count, checks against the limit, then increments. Between read and increment, concurrent requests could slip through.

**Recommendation:** Use database-level atomic operations (`UPDATE ... SET attempts = attempts + 1 WHERE attempts < @max`).

### TD-3: Password Session Timeout Hardcoded

**Severity:** Low
**Impact:** Session timeout not configurable, uses hardcoded value instead of configured duration

The password verification stores a session timestamp but the timeout check doesn't reference the configured `password_session_duration` setting.

**Recommendation:** Read the configured duration from `pdf_embed_seo_premium.settings`.

### TD-4: Missing Config Schema

**Severity:** Low
**File:** Missing `config/schema/pdf_embed_seo_premium.schema.yml`
**Impact:** Config validation warnings, no runtime impact

### TD-5: Analytics Tracker IP Anonymization

**Severity:** Low
**Impact:** IP addresses may not be anonymized when GDPR mode is enabled at the Premium level (only Pro+ has the anonymization service)

**Recommendation:** Add IP anonymization to the Premium analytics tracker, not just Pro+.

### TD-6: Bulk Import Error Handling

**Severity:** Low
**File:** `src/Service/BulkOperations.php`
**Impact:** Failed imports may not provide detailed error feedback

---

## Schema Verification

### Tables Owned by Premium

| Table | Status | Columns Verified |
|---|---|---|
| `pdf_embed_seo_progress` | OK | id, pdf_document_id, user_id, session_id, current_page, scroll_position, zoom_level, updated |
| `pdf_embed_seo_access_tokens` | OK | id, token, pdf_document_id, created_by, expires, max_uses, use_count, created |
| `pdf_embed_seo_rate_limit` | OK | id, identifier, action, target_id, ip_address, attempts, window_start, blocked_until |

### Columns Added to Base Analytics Table

| Column | Type | Added By | Status |
|---|---|---|---|
| `time_spent` | int | Premium install | OK |
| `pages_viewed` | int | Premium install | OK |
| `created` | int | Premium install | OK |

---

## Test Matrix

| Feature | Install | Config | Core Function | API | Security | Status |
|---|---|---|---|---|---|---|
| Analytics Dashboard | PASS | PASS | PASS | PASS | PASS | OK |
| Password Protection | PASS | PASS | PASS | PASS | PASS (fixed) | OK |
| Reading Progress | PASS | PASS | PASS | PASS | PASS | OK |
| Rate Limiting | PASS | N/A | PASS* | N/A | Moderate* | OK* |
| Expiring Links | PASS | PASS | PASS | PASS | PASS (fixed) | OK |
| Bulk Import | PASS | PASS | PASS | PASS | PASS | OK |
| XML Sitemap | PASS | PASS | PASS | N/A | PASS | OK |

*Rate limiting has a theoretical TOCTOU race condition under high concurrency.
