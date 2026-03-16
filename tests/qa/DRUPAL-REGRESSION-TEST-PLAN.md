# Drupal PDF Embed SEO - Regression Test Plan

**Date:** 2026-03-16
**Covers:** Free 1.2.17, Premium 1.2.17, Pro+ 1.3.2
**Purpose:** Manual regression tests after QA fixes

---

## Pre-requisites

- Drupal 10 or 11 installation
- PHP 8.1+
- MySQL/MariaDB
- ImageMagick or Ghostscript (optional, for thumbnails)

---

## 1. Fresh Installation Tests

### 1.1 Free Module Install
- [ ] Enable `pdf_embed_seo` module via Drush or admin UI
- [ ] Verify no errors in recent log messages (`/admin/reports/dblog`)
- [ ] Verify `pdf_embed_seo_analytics` table exists with correct columns
- [ ] Navigate to `/admin/config/content/pdf-embed-seo` - settings form loads
- [ ] Navigate to `/admin/content/pdf-documents` - empty list displays

### 1.2 Premium Module Install
- [ ] Enable `pdf_embed_seo_premium` module
- [ ] Verify no errors in recent log messages
- [ ] Verify these tables exist:
  - `pdf_embed_seo_progress`
  - `pdf_embed_seo_access_tokens`
  - `pdf_embed_seo_rate_limit`
- [ ] Verify analytics table has Premium columns: `time_spent`, `pages_viewed`, `created`
- [ ] Navigate to Premium settings - form loads
- [ ] Default config values set: `enable_analytics=TRUE`, `enable_password_protection=TRUE`

### 1.3 Pro+ Module Install
- [ ] Enable `pdf_embed_seo_pro_plus` module
- [ ] Verify no errors in recent log messages
- [ ] Verify these 9 tables exist:
  - `pdf_embed_seo_versions`
  - `pdf_embed_seo_annotations`
  - `pdf_embed_seo_audit_log`
  - `pdf_embed_seo_webhooks`
  - `pdf_embed_seo_webhook_deliveries`
  - `pdf_embed_seo_consents`
  - `pdf_embed_seo_heatmaps`
  - `pdf_embed_seo_2fa_tokens`
  - `pdf_embed_seo_2fa_secrets`
- [ ] Verify analytics table has Pro+ columns: `event_type`, `session_id`, `country`, `device_type`
- [ ] Navigate to `/admin/config/content/pdf-embed-seo/pro-plus` - settings form loads
- [ ] Installation message mentions license key configuration

---

## 2. PDF Document CRUD

### 2.1 Create
- [ ] Navigate to `/admin/content/pdf-documents/add`
- [ ] Upload a PDF file (< 50MB)
- [ ] Enter title and description
- [ ] Set allow_download = TRUE
- [ ] Save - no errors
- [ ] Redirects to document view or admin list

### 2.2 View
- [ ] Navigate to `/pdf/{slug}` - PDF viewer renders
- [ ] PDF.js viewer loads and displays first page
- [ ] Page navigation works (next/previous)
- [ ] Zoom controls work
- [ ] Download button visible (when allowed)
- [ ] Print button visible (when allowed)
- [ ] View count increments (check analytics table)

### 2.3 Edit
- [ ] Edit the PDF document - form loads with existing values
- [ ] Change title - saves correctly
- [ ] Upload new PDF file - replaces previous
- [ ] Toggle download/print settings - reflected on frontend

### 2.4 Delete
- [ ] Delete a PDF document - confirmation prompt
- [ ] After deletion, `/pdf/{slug}` returns 404
- [ ] Archive page no longer shows the document

---

## 3. View Count & Analytics (Free + Premium)

### 3.1 Computed View Count
- [ ] View a PDF document
- [ ] Check `pdf_embed_seo_analytics` table - new row inserted with correct `pdf_document_id`
- [ ] Entity view count reflects the count from analytics table
- [ ] REST API `/api/pdf-embed-seo/v1/documents/{id}` shows correct view count

### 3.2 Analytics API
- [ ] `GET /api/pdf-embed-seo/v1/documents` - lists documents with view counts
- [ ] `POST /api/pdf-embed-seo/v1/documents/{id}/view` - increments view
- [ ] Response includes `views` field matching analytics table count

---

## 4. Premium Features

### 4.1 Password Protection
- [ ] Set a password on a PDF document
- [ ] Visit the PDF page - password form displayed
- [ ] Enter wrong password - error message, rate limit check
- [ ] Enter correct password - PDF viewer loads
- [ ] After 5 wrong attempts in 5 minutes - blocked for 15 minutes

### 4.2 Reading Progress
- [ ] View a PDF, navigate to page 5
- [ ] Reload the page - progress restored to page 5
- [ ] API endpoint returns correct progress data

### 4.3 Expiring Links
- [ ] Generate expiring link via API (`POST /api/.../documents/{id}/expiring-link`)
- [ ] Access the link - PDF loads
- [ ] Wait for expiry - link returns error
- [ ] Verify document ID validation is strict (cannot swap IDs)

### 4.4 Analytics Dashboard
- [ ] Navigate to `/admin/reports/pdf-analytics`
- [ ] Dashboard displays view/download stats
- [ ] Export CSV/JSON works

---

## 5. Pro+ Enterprise Features

### 5.1 Annotations
- [ ] View a PDF with annotations enabled
- [ ] Create a highlight annotation - saved to database
- [ ] Edit the annotation - updates in database
- [ ] Delete the annotation - removed from database
- [ ] Permission check: user without `create pdf annotations` gets 403
- [ ] Permission check: non-owner without `edit any pdf annotations` gets 403
- [ ] Permission check: owner without `edit own pdf annotations` gets 403

### 5.2 Document Versioning
- [ ] Create a new version of a document
- [ ] Version list shows all versions with timestamps
- [ ] Current version marked correctly
- [ ] Restore a previous version - becomes current
- [ ] Delete a non-current version
- [ ] Cannot delete the current version

### 5.3 Webhooks
- [ ] Create a webhook with URL and events
- [ ] Secret auto-generated if not provided
- [ ] Webhook appears in admin list
- [ ] Test webhook - sends test payload
- [ ] Trigger an event (e.g., view document) - webhook fired
- [ ] Check delivery history - shows status, response code, duration
- [ ] After 10 consecutive failures - webhook auto-disabled

### 5.4 White Label
- [ ] Enable white label in settings
- [ ] Set company name, colors, logo URL
- [ ] PDF viewer shows custom branding
- [ ] CSS variables applied for theme colors
- [ ] Company name rendered safely (no XSS)
- [ ] Hide branding option removes default attribution

### 5.5 Compliance (GDPR)
- [ ] Enable GDPR mode
- [ ] Record consent - entry created in consents table
- [ ] IP addresses anonymized (last octet zeroed)
- [ ] Export user data - returns all user-related records
- [ ] Delete user data - removes all user records
- [ ] Consent withdrawal - `withdrawn_at` set

### 5.6 Two-Factor Authentication
- [ ] Enable 2FA for a document
- [ ] Access document - 2FA prompt appears
- [ ] Enter valid token - access granted
- [ ] Enter expired token - access denied
- [ ] Token marked as used after verification

### 5.7 Advanced Analytics
- [ ] Track heatmap interaction - data saved to heatmaps table
- [ ] Retrieve heatmap data for a page
- [ ] Aggregated heatmap returns grid data
- [ ] Engagement score calculated correctly
- [ ] Time series data returns correct date groupings
- [ ] Geographic data returns country breakdown
- [ ] Device data returns device type breakdown

### 5.8 Audit Log
- [ ] Admin actions logged to audit log table
- [ ] Audit log accessible at `/admin/reports/pdf-audit-log`
- [ ] Entries include user, action, timestamp, IP, details

---

## 6. Uninstallation Tests

### 6.1 Pro+ Uninstall
- [ ] Uninstall `pdf_embed_seo_pro_plus`
- [ ] All 9 Pro+ tables dropped
- [ ] Pro+ config deleted
- [ ] Premium and Free continue to work

### 6.2 Premium Uninstall
- [ ] Uninstall `pdf_embed_seo_premium`
- [ ] Premium tables dropped (progress, access_tokens, rate_limit)
- [ ] Premium config deleted
- [ ] Legacy State API tokens cleaned up
- [ ] Free module continues to work

### 6.3 Free Uninstall
- [ ] Uninstall `pdf_embed_seo`
- [ ] Analytics table dropped
- [ ] All entities removed
- [ ] No orphaned data

---

## 7. Edge Cases

- [ ] PDF with no file attached - no PHP fatal error
- [ ] Very large PDF (>20MB) - viewer loads progressively
- [ ] PDF with AcroForms - form fields render
- [ ] Concurrent annotation edits - no data loss
- [ ] Unicode in document titles - handled correctly
- [ ] Special characters in webhook URLs - validated
- [ ] Expired 2FA tokens cleaned up properly
