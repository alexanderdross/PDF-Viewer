# Changelog - PDF Embed & SEO Optimize (Drupal)

All notable changes to the Drupal module will be documented in this file.

## [1.2.13] - 2026-02-17

### Code Quality & Static Analysis

- **PHPStan level 5 compliance**: Resolved 526 static analysis errors across all three module tiers (base, premium, pro_plus). Zero errors remaining.
- **PHPCS Drupal standards compliance**: Fixed 20,000+ coding standard violations. Zero errors remaining (22 acceptable warnings).
- **Added `phpstan.neon` configuration**: Module-level PHPStan config with proper core module scanning, Drupal-specific error suppression, and test exclusions.

### Dependency Injection

- **Replaced all `\Drupal::` static calls** in service classes, controllers, forms, plugins, and blocks with proper constructor dependency injection.
- **Fixed constructor property promotion** conflicts with `ControllerBase`, `FormBase`, and `EntityForm` parent properties that lack native type declarations.
- **Fixed `ConfigFormBase` constructor**: Added required `TypedConfigManagerInterface` parameter for Drupal 11 compatibility in `PdfEmbedSeoSettingsForm`.

### Type Safety

- **Entity type narrowing**: Added `instanceof PdfDocumentInterface` checks across 15+ files (REST resources, controllers, blocks, services) replacing unsafe null checks.
- **Fixed `TimeInterface` namespace**: Corrected `Drupal\Core\Datetime\TimeInterface` to `Drupal\Component\Datetime\TimeInterface` in 4 service files.
- **Added missing service methods**: `getTotalDownloads()`, `getDocumentAnalytics()`, `trackDownload()` on premium `PdfAnalyticsTracker`.
- **Fixed `Merge::key()` API**: Corrected to `Merge::keys()` (plural) in `TwoFactorAuth`.
- **Fixed `SimpleXMLElement::addAttribute()` type**: Cast page number to string in `AnnotationManager`.

### Security

- **Secured `unserialize()` call**: Added `['allowed_classes' => FALSE]` option in premium install file.

### Formatting

- **Drupal coding standards**: Converted all files from PSR-12 formatting (4-space indent, Allman braces) to Drupal standards (2-space indent, same-line braces).
- **Fixed PHPDoc**: Corrected parameter order, added missing parameter descriptions, fixed comment capitalization.
- **Fixed function naming**: Renamed `pdf_embed_seo_is_pro_plus()` to `pdf_embed_seo_pro_plus_is_valid()` to match module prefix requirement.

### Files Changed

**All Modules (70 files reformatted)**

Key files with logic changes:
- `phpstan.neon` — new PHPStan configuration
- `src/Controller/PdfDataController.php` — DI, TimeInterface fix, property override fix
- `src/Controller/PdfApiController.php` — DI, entity type narrowing
- `src/Controller/PdfArchiveController.php` — entity type narrowing
- `src/Controller/PdfAnalyticsController.php` — entity type narrowing, user type check
- `src/Controller/PdfViewController.php` — DI, property override fix
- `src/Form/PdfEmbedSeoSettingsForm.php` — TypedConfigManager DI, PHPDoc fix
- `src/Form/PdfPasswordForm.php` — property override fix, entity type narrowing
- `src/Form/PdfDocumentForm.php` — property override fix
- `src/Plugin/Block/PdfViewerBlock.php` — entity type narrowing
- `src/Plugin/rest/resource/*.php` — entity type narrowing (4 files)
- `src/Service/PdfAnalyticsTracker.php` — TimeInterface fix
- `modules/pdf_embed_seo_premium/src/Service/PdfAnalyticsTracker.php` — TimeInterface fix, 3 new methods
- `modules/pdf_embed_seo_premium/src/Service/PdfProgressTracker.php` — TimeInterface fix
- `modules/pdf_embed_seo_premium/src/Controller/PdfPremiumApiController.php` — property override fix, param order fix, entity type narrowing
- `modules/pdf_embed_seo_premium/src/Controller/PdfSitemapController.php` — property override fix, entity type narrowing
- `modules/pdf_embed_seo_premium/src/Service/PdfBulkOperations.php` — entity type narrowing
- `modules/pdf_embed_seo_premium/src/Service/PdfViewerEnhancer.php` — fixed method signature
- `modules/pdf_embed_seo_premium/pdf_embed_seo_premium.install` — secure unserialize
- `modules/pdf_embed_seo_pro_plus/pdf_embed_seo_pro_plus.module` — function rename
- `modules/pdf_embed_seo_pro_plus/src/Service/TwoFactorAuth.php` — Merge::keys fix, entity type narrowing
- `modules/pdf_embed_seo_pro_plus/src/Service/AnnotationManager.php` — string cast fix
- `modules/pdf_embed_seo_pro_plus/src/Service/VersionManager.php` — file type narrowing

---

## [1.2.12] - 2026-02-17

### Bug Fixes
- **Twig FieldItemList crash**: Fixed `Object of type FieldItemList cannot be printed` error on the single PDF view page. The `pdf-viewer.html.twig` template was using `pdf_document.id` instead of `pdf_document.id.value` for the `data-document-id` attribute.
- **PDF viewer 403 error**: Removed `_csrf_token: 'TRUE'` from the `pdf_embed_seo.pdf_data` GET route. The CSRF token generated server-side didn't match during PDF.js fetch requests, causing a 403 for all users. CSRF protection is not needed on GET endpoints — the `_permission: 'view pdf document'` requirement provides sufficient access control.
- **Broken HTML in archive cards and breadcrumbs**: Changed all `%variable` placeholders to `@variable` in `t()` calls across three Twig templates (`pdf-archive-item.html.twig`, `pdf-archive.html.twig`, `pdf-document.html.twig`). The `%` prefix wraps values in `<em class="placeholder">` tags, which produced broken escaped HTML inside `title` and `aria-label` attributes.
- **REST API `/documents` endpoint returning 500**: The `formatDocument()` method referenced a non-existent `slug` base field (`Field slug is unknown`). The slug is now derived from the entity's path alias instead.

### New Features
- **REST API document endpoints**: Added `GET /documents`, `GET /documents/{id}`, and `GET /documents/{id}/data` as regular controller routes in `PdfApiController`. These were previously implemented as REST resource plugins (`PdfDocumentResource`) requiring the `rest` core module, which was not enabled. The new routes work without any additional module dependencies.
  - `GET /api/pdf-embed-seo/v1/documents` — list all published documents with pagination (`page`, `limit`, `sort`, `direction` query parameters)
  - `GET /api/pdf-embed-seo/v1/documents/{id}` — get a single document with detailed info including `data_url`
  - `GET /api/pdf-embed-seo/v1/documents/{id}/data` — get the secure PDF file URL (requires `view pdf document` permission)
- **Admin content tab**: Added `pdf_embed_seo.links.task.yml` with a "PDF Documents" local task tab on the Admin > Content page, making it visible alongside Content, Blocks, Media, and Files.

### Files Changed
- `templates/pdf-viewer.html.twig` — `pdf_document.id` → `pdf_document.id.value`
- `templates/pdf-archive-item.html.twig` — all `%` placeholders → `@` placeholders
- `templates/pdf-archive.html.twig` — `%site` → `@site`
- `templates/pdf-document.html.twig` — `%site`, `%date` → `@site`, `@date`
- `pdf_embed_seo.routing.yml` — removed CSRF from pdf_data route, added 3 API routes
- `src/Controller/PdfApiController.php` — added `getDocuments()`, `getDocument()`, `getDocumentData()`, `formatDocument()`
- `pdf_embed_seo.links.task.yml` — new file for local task tabs

## [1.2.11] - 2026-02-10

### Security
- **CSRF Protection**: Added CSRF token validation to all POST API endpoints:
  - `/api/pdf-embed-seo/v1/documents/{id}/view` (track view)
  - `/api/pdf-embed-seo/v1/documents/{id}/download` (track download)
  - `/api/pdf-embed-seo/v1/documents/{id}/progress` (save progress)
  - `/api/pdf-embed-seo/v1/documents/{id}/verify-password` (password verification)
- **Brute Force Protection**: Added rate limiting for password verification
  - Maximum 5 attempts per document per IP within 5 minutes
  - 15 minute block after exceeding limit
  - Returns HTTP 429 with retry-after information
- **Session Cache Context**: Password-protected PDFs now properly vary cache by session
  - Prevents unlocked PDF content from being served to unauthenticated sessions

### Performance
- **Computed View Count**: The `view_count` entity field is now a computed field
  - Reads count directly from analytics table on demand
  - No longer triggers entity saves during page views
  - Eliminates cache invalidation issues from view tracking

### New Features
- **Media Library Integration**: Full integration with Drupal's Media system
  - New `PdfDocument` MediaSource plugin for the Media Library
  - New `PdfViewerFormatter` field formatter for embedding PDFs
  - PDFs can now be managed alongside other media types
  - Added `drupal:media` as a module dependency
- **Access Token Storage**: New database-backed token storage
  - Replaces State API for better scalability
  - Automatic cleanup of expired tokens via cron
  - New `pdf_embed_seo_access_tokens` database table
- **Rate Limiting Service**: New dedicated service for brute force protection
  - New `pdf_embed_seo_rate_limit` database table
  - Configurable limits per action type
  - Automatic cleanup of old records via cron

### Premium Module Changes
- New services registered:
  - `pdf_embed_seo.rate_limiter` - Rate limiting service
  - `pdf_embed_seo.access_token_storage` - Token storage service
- Update hook `pdf_embed_seo_premium_update_9001()`:
  - Creates new database tables
  - Migrates existing State API tokens to database
- Cron hook additions:
  - Cleans up expired access tokens
  - Cleans up old rate limit records (>24 hours)
  - Cleans up analytics data based on retention setting

### Technical Details
- All changes are backwards-compatible
- Services check for table existence before operations
- Graceful fallback to State API if new tables unavailable
- No breaking changes to existing API contracts

### Files Changed
**Free Module:**
- `pdf_embed_seo.info.yml` - Added media dependency, version bump
- `pdf_embed_seo.routing.yml` - Added CSRF tokens
- `src/Entity/PdfDocument.php` - Computed view_count field
- `src/Field/ComputedViewCount.php` - New computed field class
- `src/Controller/PdfViewController.php` - Session cache context
- `src/Plugin/media/Source/PdfDocument.php` - New Media Source
- `src/Plugin/Field/FieldFormatter/PdfViewerFormatter.php` - New formatter

**Premium Module:**
- `pdf_embed_seo_premium.info.yml` - Version bump
- `pdf_embed_seo_premium.install` - New tables and update hooks
- `pdf_embed_seo_premium.module` - Cron cleanup
- `pdf_embed_seo_premium.routing.yml` - CSRF tokens
- `pdf_embed_seo_premium.services.yml` - New services
- `src/Service/RateLimiter.php` - New service
- `src/Service/AccessTokenStorage.php` - New service
- `src/Controller/PdfPremiumApiController.php` - Rate limiting integration

---

## [1.2.10] - 2026-02-08

### iOS Print Support
- Changed print implementation to open PDF in new window for native browser printing
- Added 500ms delay for Safari/iOS compatibility
- Added fallback to canvas print if popup is blocked

### Print CSS
- Added `@page` rules for proper A4 portrait sizing
- Added `-webkit-print-color-adjust` for proper color printing
- Hide toolbar elements in print output

---

## [1.2.9] - 2026-01-30

### Code Review Fixes (Phase 1)
- **Performance**: Removed entity saves during page views
- **Performance**: Added cache tag invalidation for lists
- **Performance**: Added cache metadata to PdfViewerBlock
- **Security**: Fixed Pathauto service dependency with graceful fallback
- **Privacy**: Added IP anonymization setting for GDPR compliance

---

## Upgrade Notes

### From 1.2.10 to 1.2.11

1. **Database Update Required**: Run `drush updb` or visit `/admin/reports/updates` to execute update hook 9001
2. **Media Module**: The Media module is now required - ensure it's enabled before updating
3. **Cron**: Ensure cron is running for automatic cleanup of expired tokens and rate limits
4. **Cache Clear**: Clear all caches after update: `drush cr`

### Breaking Changes
None. All changes are backwards-compatible.

### Deprecations
- State API token storage is deprecated in favor of database storage
- The `view_count` field no longer stores data (reads from analytics table)
