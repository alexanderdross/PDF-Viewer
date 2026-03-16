# Drupal Free Module QA Report

**Date:** 2026-03-16
**Module:** `pdf_embed_seo`
**Version:** 1.2.16 -> 1.2.17
**Reviewer:** Automated QA (Claude Code)
**Status:** All critical issues FIXED

---

## Executive Summary

QA review of the Drupal free base module found **3 critical issues** related to analytics column naming and null safety. All have been fixed.

---

## Critical Issues (All Fixed)

### 1. Analytics Column Name Mismatch (FIXED - commit f1fa042)

**Severity:** Critical
**Files:** `PdfApiController.php`, `PdfDataController.php`, `ComputedViewCount.php`
**Impact:** View count tracking and display broken

The analytics table uses `pdf_document_id` as the column name, but three files referenced it as `document_id`:

| File | Line | Before | After |
|---|---|---|---|
| `PdfApiController.php` | ~120 | `->condition('document_id', ...)` | `->condition('pdf_document_id', ...)` |
| `PdfApiController.php` | ~135 | `->fields(['document_id' => ...])` | `->fields(['pdf_document_id' => ...])` |
| `PdfDataController.php` | ~85 | `->condition('document_id', ...)` | `->condition('pdf_document_id', ...)` |
| `ComputedViewCount.php` | ~30 | `->condition('document_id', ...)` | `->condition('pdf_document_id', ...)` |

### 2. Null Safety for PDF File URL (FIXED - commit f1fa042)

**Severity:** High
**Files:** `PdfViewController.php`, `PdfDocumentForm.php`
**Impact:** PHP fatal error when PDF entity has no file attached

The `pdf_file` field could be empty, causing `->entity->getFileUri()` to throw on null:

```php
// BEFORE:
$file_url = $pdf_document->get('pdf_file')->entity->getFileUri();

// AFTER:
$file_entity = $pdf_document->get('pdf_file')->entity;
$file_url = $file_entity ? $file_entity->getFileUri() : '';
```

### 3. Twig JSON-LD Output (VERIFIED SAFE)

**Severity:** Initially flagged as potential XSS
**File:** `templates/pdf-document.html.twig`
**Impact:** None - verified safe

The pattern `{{ schema_data|json_encode|raw }}` was flagged by QA. Investigation confirmed this is safe because Drupal's Twig `json_encode` filter uses PHP's `JSON_HEX_TAG` flag, which escapes `<` and `>` characters, preventing script injection.

---

## Architecture Notes

### Computed View Count

The `view_count` field on `PdfDocument` entity uses `ComputedItemListTrait` to read directly from the `pdf_embed_seo_analytics` table. This is a performance optimization - no entity saves occur on page views. The view count is always fresh from the analytics table.

### PDF Data Security

Direct PDF file URLs are not exposed. The `PdfDataController` serves PDF data via an AJAX endpoint that performs access checks before returning the file URL. This prevents direct linking/hotlinking.

### CSRF Protection

All POST API endpoints require CSRF tokens via Drupal's session token system. GET endpoints that serve PDF data also validate the session.

---

## Schema Verification

### Tables Owned by Free Module

| Table | Status | Columns |
|---|---|---|
| `pdf_embed_seo_analytics` | OK | id, pdf_document_id, user_id, ip_address, user_agent, referrer, timestamp, time_spent*, pages_viewed*, created*, event_type**, session_id**, country**, device_type** |

\* Added by Premium module install
\** Added by Pro+ module install

---

## Test Matrix

| Feature | Install | CRUD | Display | API | SEO | Status |
|---|---|---|---|---|---|---|
| PDF Entity | PASS | PASS | PASS | PASS | PASS | OK |
| Archive View | PASS | N/A | PASS | PASS | PASS | OK |
| Single PDF View | PASS | N/A | PASS (fixed) | PASS | PASS | OK |
| View Count | PASS | N/A | PASS (fixed) | PASS (fixed) | N/A | OK |
| REST API | PASS | PASS | N/A | PASS (fixed) | N/A | OK |
| Thumbnails | PASS | PASS | PASS | N/A | N/A | OK |
| Media Library | PASS | PASS | PASS | N/A | N/A | OK |
| Gutenberg/Block | PASS | PASS | PASS | N/A | N/A | OK |
