# Release v1.3.2 - March 2026

## Platform Versions

| Platform | Version |
|----------|---------|
| Drupal Free/Premium | v1.2.17 |
| Drupal Pro+ Enterprise | v1.3.2 |
| WordPress Free/Premium | v1.2.13 |
| React Free/Premium | v1.2.12 |
| React Pro+ | v1.3.1 |
| Marketing Theme | v2.2.97 |
| License Manager | v1.0.1 |

---

## Drupal v1.2.17 / v1.3.2

### Fixed
- **Pro+ Module Activation** - Resolved critical installation failures preventing Pro+ Enterprise tier from activating; added 8 missing controllers, ProPlusSettingsForm, routing, and asset files
- **Database Schema Alignment** - Rewrote Pro+ `hook_schema()` for 6 tables and added 3 missing table schemas (heatmaps, 2fa_tokens, 2fa_secrets) to match service layer expectations
- **Table Name Prefix** - Fixed all 9 Pro+ services using short table names (e.g., `pdf_versions`) instead of required `pdf_embed_seo_` prefix (60+ query references)
- **Analytics Column Names** - Fixed `document_id` to `pdf_document_id` in free module analytics queries (PdfApiController, PdfDataController, ComputedViewCount)
- **Timestamp Type Alignment** - Fixed schema columns using int type where services insert datetime strings
- **AdvancedAnalytics SQL** - Fixed `DATE_FORMAT` to use `FROM_UNIXTIME()` for integer timestamp columns, fixed mixed cutoff types
- **Premium Duplicate Table** - Removed duplicate `pdf_embed_seo_analytics` definition from Premium schema that caused install conflicts
- **Null Safety** - Added null coalescing for PDF file URL access in PdfViewController and PdfDocumentForm

### Security
- **XSS in WhiteLabel** - Replaced `addslashes()` with `json_encode()` for JavaScript context output in white-label company name rendering
- **Annotation Permission Logic** - Fixed broken owner-based permission checks in AnnotationsApiController that allowed owners to bypass `edit own`/`delete own` permissions
- **Strict Equality** - Changed loose `!=` to strict `!==` with int cast in expiring link document ID validation

---

## WordPress v1.2.13 / Drupal v1.2.16

### Fixed
- **AcroForms Form Toolbar** - Form toolbar not appearing on PDFs with fillable form fields; added PDF.js AnnotationLayer rendering over canvas
- **Interactive Form Fields** - Text inputs, checkboxes, radio buttons, and dropdowns now render interactively on top of the PDF page

### Added
- **Fallback Form Field Rendering** - Manual DOM-based form field rendering for environments without full PDF.js AnnotationLayer support
- **GDPR & HIPAA Compliance Info** - Added compliance explanations to marketing website (Enterprise, Pro, and platform pages)

---

## All Platforms - AcroForms Release (v1.2.15/v1.2.12)

### Added
- **PDF AcroForms (Public Form Filling)** - Fill text fields, checkboxes, radio buttons, dropdowns, and date pickers directly in the browser
- **Form Validation** - Real-time validation for required fields, email, phone, and date formats
- **Download/Print Filled PDF** - Download or print with form data embedded
- **Clear Form Data** - Reset form fields with confirmation dialog
- **State Persistence** - Form data survives refresh, back/forward nav, and device rotation via sessionStorage
- **Data Loss Warning** - Browser prompt on unsaved form data
- **Online Submission (Pro+)** - Optional server-side form submission endpoint
- **Responsive Form Fields** - 44x44px min touch targets, mobile-optimized keyboards
- **Privacy-First** - Browser-only data storage, no server-side PII

### Fixed
- **License Validation (WordPress)** - Pro+ keys rejected by Premium local fallback validator

---

## Distribution Files
- `drupal-pdf-embed-seo-v1.2.17.zip` - Drupal module (Free + Premium + Pro+)
- `pdf-embed-seo-optimize-v1.2.13.zip` - WordPress plugin (Free + Premium + Pro+)
- `react-pdf-embed-seo-v1.2.12.zip` - React/Next.js packages
- `pdfviewer-theme-v2.2.97.zip` - Marketing website theme
- `pdf-license-manager-v1.0.1.zip` - License management plugin

---

## Create Release Command

After merging to main, run:

```bash
git tag -a v1.3.2 -m "Release v1.3.2 - March 2026"
git push origin v1.3.2
gh release create v1.3.2 \
  --title "v1.3.2 - Drupal Pro+ Fixes, AcroForms, Security" \
  --notes-file RELEASE-v1.3.2.md \
  dist/drupal-pdf-embed-seo-v1.2.17.zip \
  dist/pdf-embed-seo-optimize-v1.2.13.zip \
  dist/react-pdf-embed-seo-v1.2.12.zip \
  dist/pdfviewer-theme-v2.2.97.zip \
  dist/pdf-license-manager-v1.0.1.zip
```
