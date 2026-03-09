# Drupal Module Comparison: Original vs Fixed

**Date:** 2026-03-09
**Original Version:** drupal-pdf-embed-seo (in-repo, v1.2.11)
**Fixed Version:** drupal-pdf-embed-seo_new.zip (v1.2.13 via v1.2.12)

This document compares the original (broken) Drupal module with the fixed version uploaded as `drupal-pdf-embed-seo_new.zip`. The fixed version includes two version bumps: v1.2.12 (critical bug fixes) and v1.2.13 (PHPStan/PHPCS compliance sweep).

---

## Summary of Changes

| Category | Count | Impact |
|----------|-------|--------|
| **Critical Bug Fixes** | 7 | Runtime fatal errors, 403 errors, broken HTML |
| **Security Fixes** | 1 | PHP object injection via `unserialize()` |
| **Dependency Injection Fixes** | 40+ | Drupal coding standards compliance, testability |
| **Type Safety Fixes** | 15+ | `instanceof` checks preventing crashes on NULL entities |
| **New Features** | 2 | REST API document endpoints, admin content tab |
| **Formatting/Standards** | 70 files | Drupal coding standards (indent, comments, naming) |

**Total files changed:** 67

---

## Critical Bug Fixes (v1.2.12)

These are the bugs that caused the module to not work properly:

### 1. Twig FieldItemList Crash — `pdf-viewer.html.twig`

**File:** `templates/pdf-viewer.html.twig:121`

| Original (broken) | Fixed |
|---|---|
| `data-document-id="{{ pdf_document.id }}"` | `data-document-id="{{ pdf_document.id.value }}"` |

**Problem:** In Drupal, `pdf_document.id` returns a `FieldItemList` object, not a scalar value. Twig throws "Object of type FieldItemList cannot be printed", causing a fatal error when rendering any PDF viewer page.

### 2. PDF Viewer 403 Error — `pdf_embed_seo.routing.yml`

**File:** `pdf_embed_seo.routing.yml:85`

| Original (broken) | Fixed |
|---|---|
| Route `pdf_embed_seo.pdf_data` has `_csrf_token: 'TRUE'` | CSRF token requirement **removed** |

**Problem:** The `pdf_data` route serves PDF file data via a GET request. CSRF tokens are for POST/mutation requests. Adding `_csrf_token: 'TRUE'` to a GET route causes Drupal to reject all requests with a 403 Forbidden error since the viewer's JavaScript `fetch()` call doesn't include a CSRF token. This made every PDF completely unviewable.

### 3. Broken HTML in Template Attributes — All Twig Templates

**Files:** `templates/pdf-document.html.twig`, `templates/pdf-archive.html.twig`, `templates/pdf-archive-item.html.twig`

| Original (broken) | Fixed |
|---|---|
| `{{ 'View %title PDF document'\|t({'%title': pdf_title}) }}` | `{{ 'View @title PDF document'\|t({'@title': pdf_title}) }}` |

**Problem:** In Drupal's `t()` function, `%variable` wraps the value in `<em>` tags (for emphasis in user-facing messages). Using `%variable` inside HTML `title` and `aria-label` attributes produces invalid HTML like `title="View <em>My PDF</em> document"`, breaking tooltips and accessibility attributes. `@variable` does plain text replacement without HTML wrapping.

**Occurrences fixed:** 20+ replacements across 3 template files.

### 4. REST API 500 Error — `PdfApiController::formatDocument()` (original missing entirely)

**File:** `src/Controller/PdfApiController.php`

**Problem:** The original controller only had `getSettings()` and `trackView()` methods. The `formatDocument()` method (needed by premium API) referenced a non-existent `slug` base field, causing 500 errors. The new version derives the slug from the entity's path alias via `$document->get('path')->alias`.

### 5. Missing Analytics Methods — `PdfAnalyticsTracker.php` (Premium)

**File:** `modules/pdf_embed_seo_premium/src/Service/PdfAnalyticsTracker.php`

**Problem:** `PdfPremiumApiController` called `$analytics->getTotalDownloads()`, `$analytics->getDocumentAnalytics()`, and `$analytics->trackDownload()` — but these methods didn't exist in the service class, causing fatal "Call to undefined method" errors.

**Fix:** Three new methods added: `getTotalDownloads()`, `getDocumentAnalytics()`, `trackDownload()`.

### 6. Wrong Argument Type — `PdfViewerEnhancer::getProgress()` (Premium)

**File:** `modules/pdf_embed_seo_premium/src/Service/PdfViewerEnhancer.php`

| Original (broken) | Fixed |
|---|---|
| `$this->progressTracker->getProgress($pdf_document->id())` | `$this->progressTracker->getProgress($pdf_document)` |

**Problem:** `getProgress()` expects a `PdfDocumentInterface` object, but was passed an integer ID, causing a fatal error or incorrect behavior.

### 7. DB API Method Name Error — `TwoFactorAuth::setupTotpSecret()` (Pro+)

**File:** `modules/pdf_embed_seo_pro_plus/src/Service/TwoFactorAuth.php`

| Original (broken) | Fixed |
|---|---|
| `->key(['user_id' => $user_id])` | `->keys(['user_id' => $user_id])` |

**Problem:** The Drupal Database `Merge` (upsert) query method is `keys()` (plural), not `key()`. This caused a fatal error when setting up TOTP secrets for 2FA.

---

## Security Fix

### Unsafe `unserialize()` — Premium Install

**File:** `modules/pdf_embed_seo_premium/pdf_embed_seo_premium.install:498`

| Original | Fixed |
|---|---|
| `unserialize($row->value)` | `unserialize($row->value, ['allowed_classes' => FALSE])` |

**Problem:** Unrestricted `unserialize()` can instantiate arbitrary PHP classes, potentially enabling remote code execution (PHP object injection). Adding `['allowed_classes' => FALSE]` restricts deserialization to scalars and arrays only.

---

## New Features (v1.2.12)

### 1. REST API Document Endpoints

**File:** `pdf_embed_seo.routing.yml` + `src/Controller/PdfApiController.php`

Three new controller-based API routes added:

| Route | Method | Path | Purpose |
|-------|--------|------|---------|
| `pdf_embed_seo.api.documents` | GET | `/api/pdf-embed-seo/v1/documents` | List all published PDFs |
| `pdf_embed_seo.api.document` | GET | `/api/pdf-embed-seo/v1/documents/{id}` | Get single PDF |
| `pdf_embed_seo.api.document_data` | GET | `/api/pdf-embed-seo/v1/documents/{id}/data` | Get secure PDF URL |

**Why needed:** The original module only had REST resource plugins (`PdfDocumentResource`, `PdfDataResource`) which require Drupal's `rest` module to be enabled and configured. These controller routes work without `rest` module, matching the WordPress plugin's behavior.

New methods added to `PdfApiController`: `getDocuments()`, `getDocument()`, `getDocumentData()`, `formatDocument()`.

### 2. Admin Content Tab

**New file:** `pdf_embed_seo.links.task.yml`

Adds a "PDF Documents" tab on Admin > Content page, making PDF documents discoverable from the standard content admin interface.

---

## Dependency Injection Overhaul (v1.2.13)

The original module used `\Drupal::` static service calls extensively, violating Drupal coding standards and making code untestable. The new version replaces all static calls with proper constructor dependency injection.

### Files with DI fixes (core module):

| File | Static calls removed |
|------|---------------------|
| `PdfApiController.php` | `\Drupal::service()`, `\Drupal::moduleHandler()`, `\Drupal::database()`, `\Drupal::time()`, `\Drupal::logger()` |
| `PdfDataController.php` | `\Drupal::service('file_system')`, `\Drupal::database()`, `\Drupal::request()`, `\Drupal::time()`, `\Drupal::logger()` |
| `PdfArchiveController.php` | `\Drupal::request()` |
| `PdfViewController.php` | `\Drupal::service('extension.list.module')`, `\Drupal::request()` |
| `PdfAnalyticsController.php` | `\Drupal::service()` |
| `PdfDocumentForm.php` | `\Drupal::moduleHandler()`, `\Drupal::service('password')` |
| `PdfEmbedSeoSettingsForm.php` | `\Drupal::moduleHandler()`, `\Drupal::service('file_url_generator')` |
| `PdfPasswordForm.php` | `\Drupal::entityTypeManager()`, `\Drupal::service('password')`, `\Drupal::request()` |
| `PdfDocumentListBuilder.php` | `\Drupal::service('date.formatter')` |
| `PdfViewerBlock.php` | `\Drupal::config()` |
| `PdfViewerFormatter.php` | `\Drupal::config()`, `\Drupal::service('file_url_generator')`, `\Drupal::service('extension.list.module')` |
| `PdfDocument.php` (MediaSource) | `\Drupal::service('extension.list.module')`, `\Drupal::hasService()` |
| `PdfAnalyticsResource.php` | `\Drupal::currentUser()`, `\Drupal::request()`, `\Drupal::time()` |
| `PdfDataResource.php` | `\Drupal::service('file_system')`, `\Drupal::request()` |
| `PdfDocumentResource.php` | `\Drupal::config()`, `\Drupal::moduleHandler()`, `\Drupal::service('file_url_generator')` |
| `PdfProgressResource.php` | `\Drupal::service('user.data')`, `\Drupal::service('tempstore.private')`, `\Drupal::request()` |

### Files with DI fixes (premium module):

| File | Static calls removed |
|------|---------------------|
| `PdfPremiumApiController.php` | 20+ static calls replaced with 8 injected services |
| `PdfAnalyticsController.php` | `\Drupal::service()`, `\Drupal::request()` |
| `PdfSitemapController.php` | `\Drupal::service('file_url_generator')` |
| `PdfAccessManager.php` | `Role::loadMultiple()` static call |
| `PdfAnalyticsTracker.php` | `\Drupal::logger()` |
| `PdfProgressTracker.php` | `\Drupal::logger()` |
| `PdfBulkOperations.php` | Global `t()` calls |
| `PdfSchemaEnhancer.php` | Global `t()` calls |
| `PdfViewerEnhancer.php` | 10 global `t()` calls |

### Files with DI fixes (pro+ module):

| File | Static calls removed |
|------|---------------------|
| `AdvancedAnalytics.php` | `\Drupal::logger()` |
| `AnnotationManager.php` | `\Drupal::logger()` |
| `AuditLogger.php` | `\Drupal::logger()`, `\Drupal::config()` |
| `ComplianceManager.php` | `\Drupal::logger()`, `\Drupal::request()` |
| `TwoFactorAuth.php` | `\Drupal::logger()`, `\Drupal::entityTypeManager()`, `\Drupal::service()`, `\Drupal::config()`, `\Drupal::request()` |
| `VersionManager.php` | `\Drupal::logger()` |

### Services YAML changes:

| File | Services updated |
|------|-----------------|
| `pdf_embed_seo_premium.services.yml` | Added `@logger.factory`, `@entity_type.manager` arguments |
| `pdf_embed_seo_pro_plus.services.yml` | Added `@logger.factory`, `@config.factory`, `@request_stack`, `@entity_type.manager`, `@plugin.manager.mail` arguments |

---

## Type Safety Fixes

Added `instanceof` checks before accessing entity methods (prevents crashes when entities are NULL or wrong type):

| File | Check added |
|------|------------|
| `PdfApiController.php` | `$document instanceof PdfDocumentInterface` |
| `PdfArchiveController.php` | `$document instanceof PdfDocumentInterface` |
| `PdfAnalyticsController.php` | `$document instanceof PdfDocumentInterface`, `UserInterface` |
| `PdfPasswordForm.php` | `$pdf_document instanceof PdfDocumentInterface` |
| `PdfViewerBlock.php` | `$pdf_document instanceof PdfDocumentInterface` |
| `PdfSitemapController.php` | `$document instanceof PdfDocumentInterface` |
| `PdfBulkOperations.php` | `$document instanceof PdfDocumentInterface` |
| `PdfPremiumApiController.php` | `$term instanceof TermInterface` (categories/tags) |
| `AnnotationManager.php` | Type cast for `SimpleXMLElement::addAttribute()` |
| `VersionManager.php` | `$file instanceof FileInterface` |
| `TwoFactorAuth.php` | `$user instanceof UserInterface`, `$document instanceof PdfDocumentInterface` |

### Null-Safe Session Handling (5 files)

Added `$request->hasSession()` checks before accessing sessions:

- `PdfViewController.php`
- `PdfDataController.php`
- `PdfPasswordForm.php`
- `PdfDataResource.php`
- `PdfProgressResource.php`

### TimeInterface Deprecation Fix

| File | Change |
|------|--------|
| `PdfAnalyticsTracker.php` (core) | `Drupal\Core\Datetime\TimeInterface` -> `Drupal\Component\Datetime\TimeInterface` |
| `PdfAnalyticsTracker.php` (premium) | Same namespace fix |
| `PdfProgressTracker.php` (premium) | Same namespace fix |

---

## Architectural Changes

### Removed Remote License Validation (Premium + Pro+)

The original module contained a `LicenseValidator` service in the premium module that made HTTP requests to the central license dashboard API (`pdfviewer.drossmedia.de`). This was removed:

- **Deleted:** `modules/pdf_embed_seo_premium/src/Service/LicenseValidator.php` (439 lines)
- **Simplified:** `modules/pdf_embed_seo_pro_plus/src/Service/LicenseValidator.php` (removed HTTP client, remote validation, heartbeat)
- **Removed:** Cron hooks for daily heartbeat in both premium and pro+ `.module` files
- **Simplified:** `PdfPremiumSettingsForm.php` license activation now uses local-only validation

License validation is now purely local (pattern-matching on key format + expiration check).

### Removed Thumbnail Auto-Generation from Form Save

- `PdfDocumentForm.php` — Removed automatic thumbnail generation on `SAVED_NEW`
- `PdfDocument.php` (MediaSource) — Removed dynamic thumbnail generation from `getThumbnailUri()`

---

## Module Naming Convention Fix (Pro+)

| Original | Fixed |
|---|---|
| `pdf_embed_seo_is_pro_plus()` | `pdf_embed_seo_pro_plus_is_valid()` |

Drupal module functions must be prefixed with the module machine name. The original function used `pdf_embed_seo_` prefix instead of `pdf_embed_seo_pro_plus_`.

---

## Translation Function Fix

Multiple services replaced bare `t()` calls with `$this->t()` via `StringTranslationTrait`:

- `PdfAccessManager.php` (5 calls)
- `PdfViewerEnhancer.php` (10 calls)
- `PdfBulkOperations.php` (5 calls)
- `PdfSchemaEnhancer.php` (1 call)

---

## Return Type Declarations

Added PHP return type declarations across all controllers and services:
- `: array`, `: JsonResponse`, `: StreamedResponse`, `: static`, `: string`, `: int`, `: bool`, `: int|false`, `: string|false`

---

## JavaScript Formatting

All 7 JavaScript files reformatted from 2-space to 4-space indentation to match Drupal coding standards. **No functional changes** in any JS file:

- `assets/js/pdf-viewer.js`
- `assets/js/pdf-admin.js`
- `assets/js/premium/pdf-analytics.js`
- `assets/js/premium/pdf-bookmarks.js`
- `assets/js/premium/pdf-password.js`
- `assets/js/premium/pdf-progress.js`
- `assets/js/premium/pdf-search.js`

---

## New Configuration Files

| File | Purpose |
|------|---------|
| `pdf_embed_seo.links.task.yml` | Admin content tab + settings tab local tasks |
| `phpcs.xml` | PHPCS configuration for Drupal coding standards |
| `phpstan.neon` | PHPStan level 5 configuration |

---

## Files Changed Summary

### Core Module (26 files)
- `pdf_embed_seo.install` — Formatting
- `pdf_embed_seo.module` — Formatting
- `pdf_embed_seo.routing.yml` — Removed CSRF from GET route + added 3 API routes
- `pdf_embed_seo.links.task.yml` — **New file** (admin tabs)
- `phpcs.xml` — **New file**
- `phpstan.neon` — **New file**
- `src/Controller/PdfApiController.php` — DI overhaul + 4 new methods
- `src/Controller/PdfArchiveController.php` — DI + null safety
- `src/Controller/PdfDataController.php` — Full DI overhaul
- `src/Controller/PdfViewController.php` — DI + session safety
- `src/Controller/PdfAnalyticsController.php` — DI + type safety
- `src/Entity/PdfDocument.php` — Formatting
- `src/Field/ComputedViewCount.php` — Formatting
- `src/Form/PdfDocumentForm.php` — DI + removed auto-thumbnail
- `src/Form/PdfEmbedSeoSettingsForm.php` — DI
- `src/Form/PdfPasswordForm.php` — DI + session safety
- `src/PdfDocumentAccessControlHandler.php` — Formatting
- `src/PdfDocumentListBuilder.php` — DI
- `src/Plugin/Block/PdfViewerBlock.php` — DI + type safety
- `src/Plugin/Field/FieldFormatter/PdfViewerFormatter.php` — DI (ContainerFactoryPluginInterface)
- `src/Plugin/media/Source/PdfDocument.php` — DI + removed dynamic thumbnail
- `src/Plugin/rest/resource/*.php` — DI + type safety (4 files)
- `src/Service/PdfAnalyticsTracker.php` — TimeInterface namespace fix
- `src/Service/PdfThumbnailGenerator.php` — Formatting
- `templates/*.html.twig` — `%var` -> `@var` fix + `.id.value` fix (4 files)
- `CHANGELOG.md` — v1.2.12 + v1.2.13 entries
- `tests/` — Formatting (5 files)

### Premium Module (18 files)
- `pdf_embed_seo_premium.install` — `unserialize()` security fix
- `pdf_embed_seo_premium.module` — Removed cron heartbeat
- `pdf_embed_seo_premium.services.yml` — Added DI arguments
- `src/Controller/*.php` — DI overhaul (3 files)
- `src/Form/PdfPremiumSettingsForm.php` — Simplified license activation
- `src/Service/LicenseValidator.php` — **Deleted** (remote validation removed)
- `src/Service/*.php` — DI overhaul, missing methods, bug fixes (7 files)
- `tests/` — Formatting (3 files)

### Pro+ Module (14 files)
- `pdf_embed_seo_pro_plus.module` — Function rename + removed cron
- `pdf_embed_seo_pro_plus.services.yml` — Added DI arguments
- `src/Controller/` — **New empty directory**
- `src/Service/*.php` — DI overhaul, bug fixes (9 files)
- `tests/` — Formatting (5 files)

### JavaScript (7 files)
- All reformatted (2-space -> 4-space indent), no functional changes
