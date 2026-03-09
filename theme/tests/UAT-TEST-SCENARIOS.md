# User Acceptance Testing (UAT) Scenarios

## PDF Viewer WordPress Theme
## Version: 2.2.79

---

## User Personas

1. **Website Administrator** - Installs and configures the theme
2. **Content Editor** - Creates and manages pages
3. **End User** - Visits the marketing website
4. **Developer** - Reads documentation for plugin integration

---

## Scenario 1: Theme Installation (Administrator)

### UAT-001: Install Theme from ZIP

**Preconditions:**
- WordPress 6.0+ installed
- Admin access to WordPress

**Steps:**
1. Download theme ZIP from GitHub
2. Navigate to Appearance > Themes
3. Click "Add New" > "Upload Theme"
4. Select downloaded ZIP file
5. Click "Install Now"
6. Click "Activate"

**Expected Results:**
- [ ] Theme installs without errors
- [ ] Theme activates successfully
- [ ] Homepage displays with correct styling
- [ ] No PHP errors in debug log

**Actual Results:** _________________

**Status:** [ ] Pass [ ] Fail

---

### UAT-002: Configure Primary Navigation

**Preconditions:**
- Theme activated
- Admin access

**Steps:**
1. Navigate to Appearance > Menus
2. Create new menu "Main Navigation"
3. Add pages: Documentation, Examples, Pro, Changelog
4. Set menu location to "Primary Menu"
5. Save menu
6. View frontend

**Expected Results:**
- [ ] Menu items appear in header
- [ ] Links navigate to correct pages
- [ ] Mobile menu shows same items

**Actual Results:** _________________

**Status:** [ ] Pass [ ] Fail

---

## Scenario 2: Documentation Page (Developer)

### UAT-003: Read WordPress Installation Guide

**Preconditions:**
- Documentation page exists
- Theme active

**Steps:**
1. Navigate to /documentation/
2. Verify WordPress tab is selected
3. Read "Getting Started" section
4. Scroll to "Shortcodes & Blocks"
5. Copy shortcode example

**Expected Results:**
- [ ] WordPress tab active by default
- [ ] Installation steps visible (3 steps)
- [ ] WP-CLI command visible
- [ ] Shortcode table shows id, width, height attributes
- [ ] Example code: `[pdf_viewer id="123" width="100%" height="600px"]`

**Actual Results:** _________________

**Status:** [ ] Pass [ ] Fail

---

### UAT-004: Read Drupal Documentation

**Preconditions:**
- Documentation page exists

**Steps:**
1. Navigate to /documentation/
2. Click "Drupal" tab
3. Verify URL updates to #drupal
4. Read Drupal-specific content
5. Reload page with #drupal hash

**Expected Results:**
- [ ] Drupal tab becomes active (highlighted)
- [ ] URL shows /documentation/#drupal
- [ ] Installation shows Composer commands
- [ ] Drupal blocks section visible
- [ ] Twig template examples shown
- [ ] Direct URL /documentation/#drupal loads Drupal content

**Actual Results:** _________________

**Status:** [ ] Pass [ ] Fail

---

### UAT-005: Review REST API Documentation

**Preconditions:**
- Documentation page exists

**Steps:**
1. Navigate to Documentation page
2. Scroll to "REST API Reference" section
3. Review available endpoints
4. Note parameters for GET /documents

**Expected Results:**
- [ ] Base URL shown: /wp-json/pdf-embed-seo/v1/
- [ ] GET /documents endpoint documented with:
  - page (int)
  - per_page (int)
  - search (string)
  - orderby (string)
- [ ] Premium endpoints marked with "Pro" badge
- [ ] Example JSON response shown

**Actual Results:** _________________

**Status:** [ ] Pass [ ] Fail

---

### UAT-006: Review WordPress Hooks

**Preconditions:**
- Documentation page exists

**Steps:**
1. Navigate to Documentation
2. Scroll to "WordPress Hooks" section
3. Review Actions and Filters
4. Copy example code

**Expected Results:**
- [ ] Actions list shows 3 hooks:
  - pdf_embed_seo_pdf_viewed
  - pdf_embed_seo_premium_init
  - pdf_embed_seo_settings_saved
- [ ] Filters table shows 11 filters with parameters
- [ ] Code example shows add_filter usage
- [ ] Code is syntax highlighted

**Actual Results:** _________________

**Status:** [ ] Pass [ ] Fail

---

## Scenario 3: End User Experience

### UAT-007: Browse Homepage

**Preconditions:**
- Homepage configured as front page

**Steps:**
1. Navigate to homepage (/)
2. View hero section
3. Scroll through all sections
4. Click primary CTA button
5. Click secondary CTA button

**Expected Results:**
- [ ] Hero loads with gradient background
- [ ] "Get Started Free" CTA links to WordPress.org
- [ ] "View Examples" CTA links to /examples/
- [ ] Problem/Solution sections visible
- [ ] Features comparison table readable
- [ ] FAQ accordion works
- [ ] Animations trigger on scroll

**Actual Results:** _________________

**Status:** [ ] Pass [ ] Fail

---

### UAT-008: Mobile Navigation

**Preconditions:**
- Mobile device or responsive mode (< 1024px)

**Steps:**
1. Navigate to any page on mobile
2. Tap hamburger menu icon
3. Browse menu items
4. Tap a menu item
5. Tap outside menu or X to close

**Expected Results:**
- [ ] Hamburger icon visible in header
- [ ] Menu slides in from right
- [ ] All navigation items accessible
- [ ] Tapping item navigates to page
- [ ] Menu closes on navigation
- [ ] X button closes menu
- [ ] Tapping overlay closes menu

**Actual Results:** _________________

**Status:** [ ] Pass [ ] Fail

---

### UAT-009: View Examples Page

**Preconditions:**
- Examples page exists

**Steps:**
1. Navigate to /examples/
2. View different PDF viewer examples
3. Note configuration differences
4. Scroll to password-protected example

**Expected Results:**
- [ ] Multiple example cards displayed
- [ ] Each example shows different configuration
- [ ] In-Page vs Standalone types clear
- [ ] Download/Print permissions indicated
- [ ] Password-protected example marked

**Actual Results:** _________________

**Status:** [ ] Pass [ ] Fail

---

### UAT-010: Review Pricing (Pro Page)

**Preconditions:**
- Pro page exists

**Steps:**
1. Navigate to /pro/
2. Review pricing tiers
3. Compare Free vs Pro features
4. Click purchase/upgrade button

**Expected Results:**
- [ ] Free tier shows $0 with basic features
- [ ] Pro tier shows price with premium features
- [ ] Feature comparison table clear
- [ ] CTA buttons functional
- [ ] Money-back guarantee mentioned

**Actual Results:** _________________

**Status:** [ ] Pass [ ] Fail

---

## Scenario 4: Accessibility Testing

### UAT-011: Keyboard Navigation

**Preconditions:**
- Desktop browser
- No mouse

**Steps:**
1. Load homepage
2. Press Tab to navigate
3. Use Enter to activate links
4. Navigate mobile menu with keyboard
5. Expand FAQ accordion with Enter/Space

**Expected Results:**
- [ ] Skip link appears on first Tab
- [ ] Focus visible on all interactive elements
- [ ] Tab order follows visual order
- [ ] Enter activates links/buttons
- [ ] Escape closes modals/menus
- [ ] ARIA roles announced by screen reader

**Actual Results:** _________________

**Status:** [ ] Pass [ ] Fail

---

### UAT-012: Screen Reader Compatibility

**Preconditions:**
- Screen reader active (NVDA/VoiceOver)

**Steps:**
1. Navigate to homepage
2. Listen to page structure
3. Navigate through headings
4. Read documentation page
5. Interact with tabs

**Expected Results:**
- [ ] Page title announced
- [ ] Heading hierarchy correct (h1 > h2 > h3)
- [ ] Images have descriptive alt text
- [ ] Links describe destination
- [ ] Buttons have accessible names
- [ ] Tab panels announce state

**Actual Results:** _________________

**Status:** [ ] Pass [ ] Fail

---

## Scenario 5: SEO Validation

### UAT-013: Verify Meta Tags

**Preconditions:**
- Browser developer tools

**Steps:**
1. Navigate to homepage
2. View page source
3. Check meta tags
4. Navigate to Documentation
5. Compare meta tags

**Expected Results:**
- [ ] Unique title tag per page
- [ ] Meta description < 160 characters
- [ ] og:title present
- [ ] og:description present
- [ ] og:image present (1200x630)
- [ ] twitter:card present
- [ ] Canonical URL set

**Actual Results:** _________________

**Status:** [ ] Pass [ ] Fail

---

### UAT-014: Validate Structured Data

**Preconditions:**
- Access to Google Rich Results Test

**Steps:**
1. Test homepage URL in Rich Results Test
2. Test documentation page
3. Review detected schemas
4. Check for errors/warnings

**Expected Results:**
- [ ] SoftwareApplication schema on homepage
- [ ] No critical errors
- [ ] AggregateRating renders
- [ ] Organization data present
- [ ] BreadcrumbList on subpages

**Actual Results:** _________________

**Status:** [ ] Pass [ ] Fail

---

## Scenario 6: Performance

### UAT-015: Page Load Speed

**Preconditions:**
- Access to Google PageSpeed Insights

**Steps:**
1. Test homepage on PageSpeed Insights
2. Review mobile score
3. Review desktop score
4. Check Core Web Vitals
5. Test documentation page

**Expected Results:**
- [ ] Mobile score > 80
- [ ] Desktop score > 90
- [ ] LCP < 2.5s
- [ ] FID < 100ms
- [ ] CLS < 0.1
- [ ] No render-blocking resources

**Actual Results:** _________________

**Status:** [ ] Pass [ ] Fail

---

## UAT Sign-off

### Test Summary

| Scenario | Total Tests | Passed | Failed | Blocked |
|----------|-------------|--------|--------|---------|
| Installation | 2 | | | |
| Documentation | 4 | | | |
| End User | 4 | | | |
| Accessibility | 2 | | | |
| SEO | 2 | | | |
| Performance | 1 | | | |
| **Total** | **15** | | | |

### Approval

| Role | Name | Signature | Date |
|------|------|-----------|------|
| Product Owner | | | |
| QA Lead | | | |
| Developer | | | |

### Issues Found

| ID | Scenario | Description | Severity | Status |
|----|----------|-------------|----------|--------|
| | | | | |

### Notes

_Additional observations or recommendations:_

---

## Appendix: Test Data

### Sample Shortcodes for Testing

```php
// Basic viewer
[pdf_viewer id="123"]

// Custom dimensions
[pdf_viewer id="123" width="100%" height="600px"]

// Sitemap list
[pdf_viewer_sitemap orderby="date" order="DESC" limit="10"]
```

### Sample API Requests

```bash
# List documents
curl https://example.com/wp-json/pdf-embed-seo/v1/documents

# Get single document
curl https://example.com/wp-json/pdf-embed-seo/v1/documents/123

# Search documents
curl https://example.com/wp-json/pdf-embed-seo/v1/documents?search=report
```
