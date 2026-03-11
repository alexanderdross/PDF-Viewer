<?php
/**
 * Template Name: Changelog
 * Changelog page showing version history with platform tabs
 *
 * @package PDFViewer
 */

get_header();

// ─── WordPress Changelog ───────────────────────────────────────────────
$changelog_wordpress = array(
    array(
        'version' => '1.2.12',
        'date'    => 'March 11, 2026',
        'type'    => 'both',
        'title'   => 'PDF AcroForms Support & License Key Fix',
        'changes' => array(
            array('type' => 'added', 'description' => 'PDF AcroForms (Public Form Filling) - Fill text fields, checkboxes, radio buttons, dropdowns, and date pickers directly in the browser'),
            array('type' => 'added', 'description' => 'Form Validation - Real-time validation with visual indicators for required fields, email, phone, and date formats'),
            array('type' => 'added', 'description' => 'Download Filled PDF - Users can download the PDF with their filled-in form data embedded'),
            array('type' => 'added', 'description' => 'Print Filled PDF - Print the PDF including all form data with optimized print layout'),
            array('type' => 'added', 'description' => 'Clear Form Data - Reset all form fields to their default/empty state with confirmation dialog'),
            array('type' => 'added', 'description' => 'State Persistence - Form data survives page refresh, back/forward navigation, and device rotation via sessionStorage'),
            array('type' => 'added', 'description' => 'Data Loss Warning - Browser prompt warns users about unsaved form data when navigating away'),
            array('type' => 'added', 'description' => 'Online Submission (Pro+) - Optional server-side form submission endpoint for collecting completed forms'),
            array('type' => 'added', 'description' => 'Responsive Form Fields - Minimum 44x44px touch targets, mobile-optimized keyboards for email/phone/number fields'),
            array('type' => 'added', 'description' => 'Privacy-First Architecture - All form data stored in browser memory only, no server-side PII storage'),
            array('type' => 'fixed', 'description' => 'License Validation: Pro+ keys (PDF$PRO+#...) were rejected by the Premium module\'s local fallback validator'),
            array('type' => 'changed', 'description' => 'Version bump to 1.2.12 (Free/Premium), 1.3.1 (Pro+)'),
        ),
    ),
    array(
        'version' => '1.2.11',
        'date'    => 'February 10, 2026',
        'type'    => 'both',
        'title'   => 'Sync Release',
        'changes' => array(
            array('type' => 'changed', 'description' => 'No WordPress-specific changes in this release'),
        ),
    ),
    array(
        'version' => '1.2.10',
        'date'    => 'February 5, 2026',
        'type'    => 'both',
        'title'   => 'iOS Print Support Improvements',
        'changes' => array(
            array('type' => 'added', 'description' => 'iOS Print Support - Enhanced existing print CSS with canvas optimization and page-break handling'),
            array('type' => 'changed', 'description' => 'Version bump to 1.2.10'),
        ),
    ),
    array(
        'version' => '1.2.9',
        'date'    => 'February 5, 2026',
        'type'    => 'both',
        'title'   => 'Archive & Layout Fixes',
        'changes' => array(
            array('type' => 'fixed', 'description' => 'Archive page list view - Icon alignment fix (changed from inline-flex to flex)'),
            array('type' => 'fixed', 'description' => 'Boxed layout fix - Added explicit width and box-sizing to content wrapper, grid, and list nav'),
            array('type' => 'changed', 'description' => 'Version bump to 1.2.9'),
        ),
    ),
    array(
        'version' => '1.2.8',
        'date'    => 'February 4, 2026',
        'type'    => 'both',
        'title'   => 'Plugin Check, Sitemap URL & Content Alignment',
        'changes' => array(
            array('type' => 'fixed', 'description' => 'Plugin Check Compliance - Added direct file access protection to all test files (ABSPATH check)'),
            array('type' => 'changed', 'description' => 'Premium Sitemap URL changed from /pdf-sitemap.xml to /pdf/sitemap.xml with 301 redirect for backward compatibility'),
            array('type' => 'added', 'description' => 'Yoast SEO Integration: Automatic redirect to Yoast\'s native sitemap when Yoast is active'),
            array('type' => 'added', 'description' => 'Automatic fallback to custom rendering when Yoast is inactive, disabled, or PDFs marked noindex'),
            array('type' => 'changed', 'description' => '"Heading Alignment" renamed to "Content Alignment" - now applies to archive headers, list navigation, and grid layouts'),
            array('type' => 'added', 'description' => 'Font color setting now applies to grid card titles, excerpts, and metadata'),
            array('type' => 'added', 'description' => 'Item background color setting now applies to individual grid cards'),
            array('type' => 'changed', 'description' => 'Version bump to 1.2.8'),
        ),
    ),
    array(
        'version' => '1.2.7',
        'date'    => 'February 2, 2026',
        'type'    => 'both',
        'title'   => 'Sidebar Removal, Cache Fix & Archive Styling',
        'changes' => array(
            array('type' => 'fixed', 'description' => 'Sidebar/Widget Area Removal - Removed get_sidebar() calls from archive and single templates'),
            array('type' => 'fixed', 'description' => 'Added CSS rules to hide sidebars on archive pages (.post-type-archive-pdf_document)'),
            array('type' => 'fixed', 'description' => '"Security check failed" error on cached pages - Switched PDF viewer from AJAX to REST API endpoint'),
            array('type' => 'fixed', 'description' => 'REST API doesn\'t require nonces for public read operations, fixing cache compatibility'),
            array('type' => 'added', 'description' => 'Archive Page Styling Settings - Custom H1 heading for archive page'),
            array('type' => 'added', 'description' => 'Heading alignment options: left, center (default), right'),
            array('type' => 'added', 'description' => 'Custom font color and background color for archive header'),
            array('type' => 'added', 'description' => 'Custom heading also updates 2nd breadcrumb item (HTML and Schema.org BreadcrumbList)'),
            array('type' => 'added', 'description' => 'Unit tests for sidebar removal (test-template-sidebar.php)'),
            array('type' => 'changed', 'description' => 'Version bump to 1.2.7'),
        ),
    ),
    array(
        'version' => '1.2.6',
        'date'    => 'February 1, 2026',
        'type'    => 'both',
        'title'   => 'Plugin Check Compliance & Security',
        'changes' => array(
            array('type' => 'fixed', 'description' => 'Unescaped SQL table name parameters in premium REST API'),
            array('type' => 'fixed', 'description' => 'Interpolated SQL variables in premium analytics'),
            array('type' => 'fixed', 'description' => 'Updated get_posts() to use post__not_in instead of deprecated exclude parameter'),
            array('type' => 'added', 'description' => 'Proper esc_sql() sanitization for all database table names'),
            array('type' => 'changed', 'description' => 'Hook renamed: pdf_embed_seo_settings_saved → pdf_embed_seo_optimize_settings_saved (breaking change)'),
            array('type' => 'changed', 'description' => 'Version bump to 1.2.6'),
        ),
    ),
    array(
        'version' => '1.2.5',
        'date'    => 'January 28, 2026',
        'type'    => 'both',
        'title'   => 'Download Tracking & Expiring Access Links',
        'changes' => array(
            array('type' => 'added', 'description' => 'Download Tracking (Pro) - Separate download counter per document (_pdf_download_count)'),
            array('type' => 'added', 'description' => 'Download analytics in dashboard'),
            array('type' => 'added', 'description' => 'REST API endpoint: POST /documents/{id}/download'),
            array('type' => 'added', 'description' => 'Expiring Access Links (Pro+) - Configurable expiration time (5 min to 30 days)'),
            array('type' => 'added', 'description' => 'Maximum usage limits per link with secure token-based access'),
            array('type' => 'added', 'description' => 'REST endpoints: POST /documents/{id}/expiring-link, GET /documents/{id}/expiring-link/{token}'),
            array('type' => 'changed', 'description' => 'Version bump to 1.2.5'),
        ),
    ),
    array(
        'version' => '1.2.4',
        'date'    => 'January 28, 2025',
        'type'    => 'proPlus',
        'title'   => 'Pro+ AI & Schema Optimization Meta Box',
        'changes' => array(
            array('type' => 'added', 'description' => 'AI Summary (TL;DR) field with abstract schema support (Pro+)'),
            array('type' => 'added', 'description' => 'Key Points & Takeaways with Schema.org ItemList markup (Pro+)'),
            array('type' => 'added', 'description' => 'FAQ Schema (FAQPage) for Google rich results (Pro+)'),
            array('type' => 'added', 'description' => 'Table of Contents Schema (hasPart) with deep links (Pro+)'),
            array('type' => 'added', 'description' => 'Reading Time estimate with timeRequired schema (Pro+)'),
            array('type' => 'added', 'description' => 'Difficulty Level, Document Type, Target Audience, Speakable Content (Pro+)'),
            array('type' => 'added', 'description' => 'Related Documents, Prerequisites, Learning Outcomes schema (Pro+)'),
            array('type' => 'added', 'description' => 'AI Optimization Preview Meta Box for free users with Get Premium CTA'),
            array('type' => 'added', 'description' => 'Premium Settings Preview on free settings page'),
            array('type' => 'changed', 'description' => 'Version bump to 1.2.4'),
        ),
    ),
    array(
        'version' => '1.2.3',
        'date'    => 'January 28, 2025',
        'type'    => 'both',
        'title'   => 'GEO/AEO/LLM Schema & Social Meta',
        'changes' => array(
            array('type' => 'added', 'description' => 'GEO/AEO/LLM Schema - SpeakableSpecification with CSS selectors for voice assistants'),
            array('type' => 'added', 'description' => 'accessMode, accessModeSufficient, accessibilityFeature properties'),
            array('type' => 'added', 'description' => 'potentialAction (ReadAction, DownloadAction, SearchAction, ViewAction)'),
            array('type' => 'added', 'description' => 'Standalone Open Graph and Twitter Card meta tags (without Yoast)'),
            array('type' => 'added', 'description' => 'Enhanced DigitalDocument Schema with identifier, fileFormat, inLanguage, publisher'),
            array('type' => 'fixed', 'description' => 'Plugin Check compliance: Fixed escaping issues in premium license notices'),
            array('type' => 'changed', 'description' => 'Version bump to 1.2.3'),
        ),
    ),
    array(
        'version' => '1.2.2',
        'date'    => 'January 28, 2025',
        'type'    => 'both',
        'title'   => 'Archive Display Options & Breadcrumb Schema',
        'changes' => array(
            array('type' => 'added', 'description' => 'Archive Display Options - List/grid views for PDF archives'),
            array('type' => 'added', 'description' => 'Breadcrumb Schema - Schema.org BreadcrumbList JSON-LD with visible navigation'),
            array('type' => 'added', 'description' => 'Archive Page Redirect (Premium) - Redirect /pdf archive to custom URL'),
            array('type' => 'changed', 'description' => 'Version bump to 1.2.2'),
        ),
    ),
    array(
        'version' => '1.2.1',
        'date'    => 'January 27, 2025',
        'type'    => 'both',
        'title'   => 'Unit Tests & Plugin Branding',
        'changes' => array(
            array('type' => 'added', 'description' => 'Unit tests for WordPress Free module (REST API, Schema, Post Type, Shortcodes)'),
            array('type' => 'added', 'description' => 'Unit tests for WordPress Premium module (Password, Analytics, Progress, REST API)'),
            array('type' => 'added', 'description' => '"Get Premium" action link on free plugin page'),
            array('type' => 'added', 'description' => 'Plugin name differentiation: "(Free Version)" and "(Premium)"'),
            array('type' => 'changed', 'description' => 'Plugin URI now points to https://pdfviewermodule.com'),
            array('type' => 'changed', 'description' => 'Version bump to 1.2.1'),
        ),
    ),
    array(
        'version' => '1.2.0',
        'date'    => 'January 25, 2025',
        'type'    => 'both',
        'title'   => 'Premium Features Release — Pro & Pro+ Tiers',
        'changes' => array(
            array('type' => 'added', 'description' => 'Three Pro tiers: Pro 1 Site (€49/yr), Pro 5 Sites (€99/yr), Pro Unlimited (€199/yr)'),
            array('type' => 'added', 'description' => 'Pro Lifetime plan (€399 one-time) with all Pro features on unlimited sites'),
            array('type' => 'added', 'description' => 'Complete REST API - GET /documents, GET /documents/{id}, GET /documents/{id}/data'),
            array('type' => 'added', 'description' => 'Pro: Analytics Dashboard with view tracking, popular documents, and time filters'),
            array('type' => 'added', 'description' => 'Pro: Password Protection with bcrypt hashing and session-based access'),
            array('type' => 'added', 'description' => 'Pro: Text Search in Viewer and Bookmark Navigation'),
            array('type' => 'added', 'description' => 'Pro: Detailed View Tracking, Download Tracking, Brute-Force Protection'),
            array('type' => 'added', 'description' => 'Pro: Reading Progress tracking with auto-save and resume functionality'),
            array('type' => 'added', 'description' => 'Pro: XML Sitemap at /pdf/sitemap.xml with XSL stylesheet'),
            array('type' => 'added', 'description' => 'Pro: Categories and Tags taxonomies for PDF organization'),
            array('type' => 'added', 'description' => 'Pro: AI/GEO/AEO Schema Optimization fields'),
            array('type' => 'added', 'description' => 'Pro: CSV/JSON Analytics Export'),
            array('type' => 'added', 'description' => 'Pro: Expiring Access Links with time-limited URLs'),
            array('type' => 'added', 'description' => 'Pro: Role-Based Access Control'),
            array('type' => 'added', 'description' => 'Pro: Bulk Import via CSV/ZIP'),
            array('type' => 'changed', 'description' => 'Version bump to 1.2.0'),
        ),
    ),
    array(
        'version' => '1.1.0',
        'date'    => 'January 2025',
        'type'    => 'free',
        'title'   => 'Improved Structure & Yoast Integration',
        'changes' => array(
            array('type' => 'added', 'description' => 'UAT/QA test documentation'),
            array('type' => 'improved', 'description' => 'Improved WordPress plugin structure'),
            array('type' => 'improved', 'description' => 'Enhanced Yoast SEO integration'),
        ),
    ),
    array(
        'version' => '1.0.0',
        'date'    => 'January 2025',
        'type'    => 'free',
        'title'   => 'Initial Release',
        'changes' => array(
            array('type' => 'added', 'description' => 'Mozilla PDF.js viewer integration (v4.0, bundled locally)'),
            array('type' => 'added', 'description' => 'Custom post type for PDF documents with clean URL structure (/pdf/document-name/)'),
            array('type' => 'added', 'description' => 'Gutenberg block for embedding PDFs in the block editor'),
            array('type' => 'added', 'description' => 'Schema.org DigitalDocument markup for rich search results'),
            array('type' => 'added', 'description' => 'Yoast SEO integration with full control over meta tags and OpenGraph'),
            array('type' => 'added', 'description' => 'Print/download permission controls on a per-PDF basis'),
            array('type' => 'added', 'description' => 'View statistics tracking, archive page at /pdf/, and shortcode support'),
            array('type' => 'added', 'description' => 'Auto-generate thumbnails from PDF first pages (requires ImageMagick or Ghostscript)'),
            array('type' => 'added', 'description' => 'Responsive design for desktop, tablet, and mobile devices'),
        ),
    ),
);

// ─── Drupal Changelog ──────────────────────────────────────────────────
$changelog_drupal = array(
    array(
        'version' => '1.2.15',
        'date'    => 'March 11, 2026',
        'type'    => 'both',
        'title'   => 'PDF AcroForms Support (Public Form Filling)',
        'changes' => array(
            array('type' => 'added', 'description' => 'PDF AcroForms (Public Form Filling) - Fill text fields, checkboxes, radio buttons, dropdowns, and date pickers directly in the browser'),
            array('type' => 'added', 'description' => 'Form Validation - Real-time validation with visual indicators for required fields, email, phone, and date formats'),
            array('type' => 'added', 'description' => 'Download Filled PDF - Users can download the PDF with their filled-in form data embedded'),
            array('type' => 'added', 'description' => 'Print Filled PDF - Print the PDF including all form data with optimized print layout'),
            array('type' => 'added', 'description' => 'Clear Form Data - Reset all form fields to their default/empty state with confirmation dialog'),
            array('type' => 'added', 'description' => 'State Persistence - Form data survives page refresh, back/forward navigation, and device rotation via sessionStorage'),
            array('type' => 'added', 'description' => 'Data Loss Warning - Browser prompt warns users about unsaved form data when navigating away'),
            array('type' => 'added', 'description' => 'Online Submission (Pro+) - Optional server-side form submission endpoint for collecting completed forms'),
            array('type' => 'added', 'description' => 'Responsive Form Fields - Minimum 44x44px touch targets, mobile-optimized keyboards for email/phone/number fields'),
            array('type' => 'added', 'description' => 'Privacy-First Architecture - All form data stored in browser memory only, no server-side PII storage'),
            array('type' => 'changed', 'description' => 'Version bump to 1.2.15 (Free/Premium), 1.3.1 (Pro+)'),
        ),
    ),
    array(
        'version' => '1.2.14',
        'date'    => 'March 11, 2026',
        'type'    => 'both',
        'title'   => 'Pro+ License Key Fix & Cross-Platform Parity',
        'changes' => array(
            array('type' => 'fixed', 'description' => 'License Validation: Pro+ keys (PDF$PRO+#...) were rejected by the Premium module\'s local fallback validator'),
            array('type' => 'fixed', 'description' => 'Local fallback regex now accepts both Premium (PDF$PRO#) and Pro+ (PDF$PRO+#) key formats'),
            array('type' => 'fixed', 'description' => 'Same fix applied to WordPress Premium plugin for cross-platform parity'),
            array('type' => 'changed', 'description' => 'Version bump to 1.2.14'),
        ),
    ),
    array(
        'version' => '1.2.13',
        'date'    => 'February 17, 2026',
        'type'    => 'both',
        'title'   => 'Code Quality & Static Analysis Compliance',
        'changes' => array(
            array('type' => 'improved', 'description' => 'PHPStan level 5 compliance — Resolved 526 static analysis errors across all module tiers'),
            array('type' => 'improved', 'description' => 'PHPCS Drupal standards compliance — Fixed 20,000+ coding standard violations'),
            array('type' => 'changed', 'description' => 'Replaced all \\Drupal:: static calls in service classes with proper constructor dependency injection (40+ files)'),
            array('type' => 'fixed', 'description' => 'Entity type narrowing — Added instanceof PdfDocumentInterface checks across 15+ files'),
            array('type' => 'fixed', 'description' => 'Secured unserialize() call with allowed_classes restriction in premium install file'),
            array('type' => 'changed', 'description' => 'Version bump to 1.2.13'),
        ),
    ),
    array(
        'version' => '1.2.12',
        'date'    => 'February 17, 2026',
        'type'    => 'both',
        'title'   => 'REST API Routes & Template Fixes',
        'changes' => array(
            array('type' => 'added', 'description' => 'REST API document endpoints - GET /documents, GET /documents/{id}, and GET /documents/{id}/data as standalone controller routes (no rest module dependency)'),
            array('type' => 'added', 'description' => 'Pagination, sorting, and filtering support for the documents list endpoint'),
            array('type' => 'added', 'description' => 'Admin content tab - PDF Documents tab now visible on the Admin > Content page'),
            array('type' => 'fixed', 'description' => 'Twig FieldItemList crash - Fixed error on single PDF view when printing entity field objects'),
            array('type' => 'fixed', 'description' => 'PDF viewer 403 error - Removed unnecessary CSRF token requirement from PDF data GET route'),
            array('type' => 'fixed', 'description' => 'Broken HTML in archive cards and breadcrumbs - Fixed placeholder format in Twig t() calls'),
            array('type' => 'fixed', 'description' => 'REST API /documents 500 error - Slug now derived from path alias instead of non-existent base field'),
        ),
    ),
    array(
        'version' => '1.2.11',
        'date'    => 'February 10, 2026',
        'type'    => 'both',
        'title'   => 'Media Library Integration & Security Hardening',
        'changes' => array(
            array('type' => 'added', 'description' => 'Media Library Integration - PDFs can now be managed via Drupal\'s Media Library'),
            array('type' => 'added', 'description' => 'Created PdfDocument MediaSource plugin for PDF files'),
            array('type' => 'added', 'description' => 'Created PdfViewerFormatter field formatter for displaying PDFs in Media entities'),
            array('type' => 'added', 'description' => 'Rate Limiter service - Brute force protection for password verification (5 attempts per 5 min)'),
            array('type' => 'added', 'description' => 'Access Token Storage service - Database backend with automatic cleanup'),
            array('type' => 'fixed', 'description' => 'Security: CSRF Protection - Added _csrf_token to all POST API endpoints'),
            array('type' => 'fixed', 'description' => 'Security: Session Cache Context - Prevents cross-session cache leaks for password-protected PDFs'),
            array('type' => 'fixed', 'description' => 'Performance: Computed View Count - Converted view_count to computed field (no more entity saves)'),
            array('type' => 'fixed', 'description' => 'Scalability: Token Storage Migration - Replaced State API with dedicated database table'),
            array('type' => 'changed', 'description' => 'Architecture: Backwards-compatible with fallback to State API if new tables don\'t exist'),
            array('type' => 'changed', 'description' => 'Version bump to 1.2.11'),
        ),
    ),
    array(
        'version' => '1.2.10',
        'date'    => 'February 5, 2026',
        'type'    => 'both',
        'title'   => 'Print Implementation Overhaul',
        'changes' => array(
            array('type' => 'changed', 'description' => 'Print implementation changed to open PDF in new window for native browser printing (matches WordPress approach)'),
            array('type' => 'added', 'description' => '500ms delay for Safari/iOS compatibility before triggering print dialog'),
            array('type' => 'added', 'description' => 'Fallback to canvas print if popup is blocked'),
            array('type' => 'added', 'description' => 'Comprehensive print media queries (previously missing in Drupal)'),
            array('type' => 'added', 'description' => '@page rules for proper A4 portrait sizing and margins'),
            array('type' => 'added', 'description' => '-webkit-print-color-adjust and print-color-adjust for proper color printing'),
            array('type' => 'added', 'description' => 'Hide all toolbar, control, loading, and error elements in print output'),
            array('type' => 'changed', 'description' => 'Version bump to 1.2.10'),
        ),
    ),
    array(
        'version' => '1.2.9',
        'date'    => 'February 5, 2026',
        'type'    => 'both',
        'title'   => 'Performance, Security & GDPR Compliance',
        'changes' => array(
            array('type' => 'fixed', 'description' => 'Performance: Removed entity saves during page views - prevents cache invalidation on every view'),
            array('type' => 'fixed', 'description' => 'Performance: Added cache tag invalidation for lists (hook_ENTITY_TYPE_insert/update/delete)'),
            array('type' => 'fixed', 'description' => 'Performance: Added cache metadata to PdfViewerBlock (tags, contexts, max-age)'),
            array('type' => 'fixed', 'description' => 'Security: Fixed Pathauto service dependency - fallback URL-safe string generator prevents fatal errors'),
            array('type' => 'fixed', 'description' => 'Privacy: Added IP anonymization for GDPR compliance (zeros last octet IPv4 / last 80 bits IPv6)'),
            array('type' => 'fixed', 'description' => 'CSS: Fixed list view icon alignment and boxed layout width constraint'),
            array('type' => 'added', 'description' => 'GDPR IP Anonymization Setting - New checkbox in settings form'),
            array('type' => 'added', 'description' => 'Cache Tag Invalidation Hooks for proper cache management'),
            array('type' => 'changed', 'description' => 'Version bump to 1.2.9'),
        ),
    ),
    array(
        'version' => '1.2.8',
        'date'    => 'February 4, 2026',
        'type'    => 'both',
        'title'   => 'Archive Settings & Color Customization',
        'changes' => array(
            array('type' => 'added', 'description' => 'Content Alignment setting (left, center, right) for archive pages'),
            array('type' => 'added', 'description' => 'Font Color setting for content items in grid and list views'),
            array('type' => 'added', 'description' => 'Background Color setting for individual grid cards'),
            array('type' => 'changed', 'description' => 'CSS inheritance for custom colors on child elements'),
            array('type' => 'changed', 'description' => 'Version bump to 1.2.8'),
        ),
    ),
    array(
        'version' => '1.2.7',
        'date'    => 'February 2, 2026',
        'type'    => 'both',
        'title'   => 'Sidebar Removal & Full-Width Templates',
        'changes' => array(
            array('type' => 'fixed', 'description' => 'Sidebar/Widget Area Removal - PDF pages now display full-width'),
            array('type' => 'added', 'description' => 'hook_theme_suggestions_page_alter() for full-width page templates'),
            array('type' => 'added', 'description' => 'hook_preprocess_page() to programmatically clear sidebar regions on PDF routes'),
            array('type' => 'added', 'description' => 'hook_preprocess_html() to add .page-pdf body classes for CSS targeting'),
            array('type' => 'added', 'description' => 'CSS rules to hide common Drupal sidebar selectors'),
            array('type' => 'added', 'description' => 'Unit tests for sidebar removal (PdfSidebarRemovalTest.php)'),
            array('type' => 'changed', 'description' => 'Version bump to 1.2.7'),
        ),
    ),
    array(
        'version' => '1.2.6',
        'date'    => 'February 1, 2026',
        'type'    => 'both',
        'title'   => 'Security: Password Hashing & XSS Prevention',
        'changes' => array(
            array('type' => 'fixed', 'description' => 'Security: Implemented proper password hashing using Drupal password service'),
            array('type' => 'fixed', 'description' => 'Security: Fixed potential XSS in PdfViewerBlock with proper Html::escape()'),
            array('type' => 'changed', 'description' => 'Version bump to 1.2.6'),
        ),
    ),
    array(
        'version' => '1.2.5',
        'date'    => 'January 28, 2026',
        'type'    => 'both',
        'title'   => 'Premium Feature Parity & Download Tracking',
        'changes' => array(
            array('type' => 'added', 'description' => 'Download Tracking (Pro) - REST API endpoint: POST /documents/{id}/download'),
            array('type' => 'added', 'description' => 'Expiring Access Links (Pro+) - Configurable expiration time with secure token-based access'),
            array('type' => 'added', 'description' => 'PdfSchemaEnhancer service for GEO/AEO/LLM optimization'),
            array('type' => 'added', 'description' => 'PdfAccessManager service for role-based access control'),
            array('type' => 'added', 'description' => 'PdfBulkOperations service for CSV import and bulk updates'),
            array('type' => 'added', 'description' => 'PdfViewerEnhancer service for text search, bookmarks, reading progress UI'),
            array('type' => 'added', 'description' => 'Extended REST API with 14+ endpoints matching WordPress'),
            array('type' => 'fixed', 'description' => 'PDF.js Assets - Library files now included in module (pdf.min.js, pdf.worker.min.js)'),
            array('type' => 'fixed', 'description' => 'workerSrc Configuration - Fixed PDF.js worker not loading in PdfViewController'),
            array('type' => 'fixed', 'description' => 'Cross-Platform License Validation - Accepts WordPress-style license keys'),
            array('type' => 'changed', 'description' => 'Version bump to 1.2.5'),
        ),
    ),
    array(
        'version' => '1.2.4',
        'date'    => 'January 28, 2025',
        'type'    => 'proPlus',
        'title'   => 'Pro+ GEO/AEO/LLM Schema Fields',
        'changes' => array(
            array('type' => 'added', 'description' => 'Pro+ GEO/AEO/LLM schema optimization fields (matching WordPress)'),
            array('type' => 'changed', 'description' => 'Version bump to 1.2.4'),
        ),
    ),
    array(
        'version' => '1.2.3',
        'date'    => 'January 28, 2025',
        'type'    => 'both',
        'title'   => 'GEO/AEO/LLM Schema & Social Meta',
        'changes' => array(
            array('type' => 'added', 'description' => 'GEO/AEO/LLM Schema - SpeakableSpecification with CSS selectors'),
            array('type' => 'added', 'description' => 'accessMode, accessModeSufficient, accessibilityFeature properties'),
            array('type' => 'added', 'description' => 'potentialAction (ReadAction, DownloadAction)'),
            array('type' => 'added', 'description' => 'Standalone Open Graph and Twitter Card meta tags'),
            array('type' => 'changed', 'description' => 'Version bump to 1.2.3'),
        ),
    ),
    array(
        'version' => '1.2.2',
        'date'    => 'January 28, 2025',
        'type'    => 'both',
        'title'   => 'Archive Display Options & Breadcrumb Schema',
        'changes' => array(
            array('type' => 'added', 'description' => 'Archive Display Options - List/grid views for PDF archives'),
            array('type' => 'added', 'description' => 'Breadcrumb Schema - Schema.org BreadcrumbList markup'),
            array('type' => 'changed', 'description' => 'Version bump to 1.2.2'),
        ),
    ),
    array(
        'version' => '1.2.1',
        'date'    => 'January 27, 2025',
        'type'    => 'both',
        'title'   => 'Unit Tests',
        'changes' => array(
            array('type' => 'added', 'description' => 'Unit tests for Drupal Free module (API Controller, Entity, Storage)'),
            array('type' => 'changed', 'description' => 'Version bump to 1.2.1'),
        ),
    ),
    array(
        'version' => '1.2.0',
        'date'    => 'January 25, 2025',
        'type'    => 'both',
        'title'   => 'REST API & Premium Submodule — Pro & Pro+ Tiers',
        'changes' => array(
            array('type' => 'added', 'description' => 'Three Pro tiers: Pro 1 Site (€49/yr), Pro 5 Sites (€99/yr), Pro Unlimited (€199/yr)'),
            array('type' => 'added', 'description' => 'Complete REST API - GET /documents, GET /documents/{id}, GET /documents/{id}/data'),
            array('type' => 'added', 'description' => 'Premium submodule (pdf_embed_seo_premium) separated from free base module'),
            array('type' => 'added', 'description' => 'Pro: Analytics Dashboard, Password Protection, Detailed View Tracking'),
            array('type' => 'added', 'description' => 'Pro: Reading Progress, XML Sitemap, Categories & Tags, AI/GEO/AEO Schema'),
            array('type' => 'added', 'description' => 'Pro: Role-Based Access, Bulk Import'),
            array('type' => 'added', 'description' => 'Premium REST API endpoints matching WordPress'),
            array('type' => 'changed', 'description' => 'Version bump to 1.2.0'),
        ),
    ),
    array(
        'version' => '1.1.0',
        'date'    => 'January 2025',
        'type'    => 'free',
        'title'   => 'Initial Drupal Module Release',
        'changes' => array(
            array('type' => 'added', 'description' => 'Drupal 10/11 module with PDF Document entity type'),
            array('type' => 'added', 'description' => 'PDF Viewer block with configuration options'),
            array('type' => 'added', 'description' => 'REST API endpoints for Drupal (/api/pdf-embed-seo/v1/)'),
            array('type' => 'added', 'description' => 'Drush commands for PDF management'),
            array('type' => 'added', 'description' => 'Twig templates for theming (pdf-document.html.twig, pdf-viewer.html.twig)'),
            array('type' => 'improved', 'description' => 'Unified feature parity between WordPress and Drupal'),
        ),
    ),
);

// ─── React / Next.js Changelog ─────────────────────────────────────────
$changelog_react = array(
    array(
        'version' => '1.2.12',
        'date'    => 'March 11, 2026',
        'type'    => 'both',
        'title'   => 'PDF AcroForms Support (Public Form Filling)',
        'changes' => array(
            array('type' => 'added', 'description' => 'PDF AcroForms (Public Form Filling) - Fill text fields, checkboxes, radio buttons, dropdowns, and date pickers directly in the browser'),
            array('type' => 'added', 'description' => 'Form Validation - Real-time validation with visual indicators for required fields, email, phone, and date formats'),
            array('type' => 'added', 'description' => 'Download Filled PDF - Users can download the PDF with their filled-in form data embedded'),
            array('type' => 'added', 'description' => 'Print Filled PDF - Print the PDF including all form data with optimized print layout'),
            array('type' => 'added', 'description' => 'Clear Form Data - Reset all form fields to their default/empty state with confirmation dialog'),
            array('type' => 'added', 'description' => 'State Persistence - Form data survives page refresh, back/forward navigation, and device rotation via sessionStorage'),
            array('type' => 'added', 'description' => 'Data Loss Warning - Browser prompt warns users about unsaved form data when navigating away'),
            array('type' => 'added', 'description' => 'Online Submission (Pro+) - Optional server-side form submission endpoint for collecting completed forms'),
            array('type' => 'added', 'description' => 'Responsive Form Fields - Minimum 44x44px touch targets, mobile-optimized keyboards for email/phone/number fields'),
            array('type' => 'added', 'description' => 'Privacy-First Architecture - All form data stored in browser memory only, no server-side PII storage'),
            array('type' => 'added', 'description' => 'usePdfForm hook - React hook for managing form state, validation, and submission'),
            array('type' => 'added', 'description' => 'PdfFormViewer component - Drop-in form-aware PDF viewer with built-in form toolbar'),
            array('type' => 'changed', 'description' => 'Version bump to 1.2.12 (Free/Premium), 1.3.1 (Pro+)'),
        ),
    ),
    array(
        'version' => '1.2.11',
        'date'    => 'February 10, 2026',
        'type'    => 'both',
        'title'   => 'Sync Release',
        'changes' => array(
            array('type' => 'changed', 'description' => 'No React-specific changes in this release'),
        ),
    ),
    array(
        'version' => '1.2.10',
        'date'    => 'February 5, 2026',
        'type'    => 'both',
        'title'   => 'iOS Print Support & Print CSS',
        'changes' => array(
            array('type' => 'added', 'description' => 'iOS Print Support - Changed print implementation to open PDF in new window'),
            array('type' => 'added', 'description' => 'Native browser printing for better Safari/iOS compatibility'),
            array('type' => 'added', 'description' => '500ms delay for Safari/iOS before triggering print dialog'),
            array('type' => 'added', 'description' => 'Fallback to canvas print if popup is blocked'),
            array('type' => 'added', 'description' => 'Comprehensive print CSS with @page rules for A4 portrait sizing'),
            array('type' => 'changed', 'description' => 'Version bump to 1.2.10'),
        ),
    ),
    array(
        'version' => '1.2.9',
        'date'    => 'February 5, 2026',
        'type'    => 'both',
        'title'   => 'Archive & Layout Fixes',
        'changes' => array(
            array('type' => 'fixed', 'description' => 'Archive page list view - Icon alignment fix (changed from inline-flex to flex)'),
            array('type' => 'fixed', 'description' => 'Boxed layout fix - Added explicit width and box-sizing to content wrapper'),
            array('type' => 'changed', 'description' => 'Version bump to 1.2.9'),
        ),
    ),
    array(
        'version' => '1.2.8',
        'date'    => 'February 4, 2026',
        'type'    => 'both',
        'title'   => 'Grid/List View Styling',
        'changes' => array(
            array('type' => 'added', 'description' => 'Custom font color support for grid cards and list items'),
            array('type' => 'added', 'description' => 'Custom background color for individual grid cards'),
            array('type' => 'added', 'description' => 'CSS inheritance for custom colors on child elements'),
            array('type' => 'changed', 'description' => 'Version bump to 1.2.8'),
        ),
    ),
    array(
        'version' => '1.2.5',
        'date'    => 'January 28, 2026',
        'type'    => 'both',
        'title'   => 'Download Tracking & Expiring Links',
        'changes' => array(
            array('type' => 'added', 'description' => 'useDownloadTracking hook for tracking downloads via API (Pro)'),
            array('type' => 'added', 'description' => 'Expiring Links Support (Pro+) - API client methods for generating/validating expiring links'),
            array('type' => 'changed', 'description' => 'Version bump to 1.2.5'),
        ),
    ),
    array(
        'version' => '1.2.3',
        'date'    => 'January 28, 2025',
        'type'    => 'both',
        'title'   => 'PdfSeo Component Enhancements',
        'changes' => array(
            array('type' => 'added', 'description' => 'GEO/AEO/LLM schema optimization in PdfSeo component'),
            array('type' => 'added', 'description' => 'SpeakableSpecification support'),
            array('type' => 'added', 'description' => 'accessMode and accessibilityFeature properties'),
            array('type' => 'added', 'description' => 'potentialAction (ReadAction, DownloadAction)'),
            array('type' => 'changed', 'description' => 'Version bump to 1.2.3'),
        ),
    ),
    array(
        'version' => '1.2.2',
        'date'    => 'January 28, 2025',
        'type'    => 'both',
        'title'   => 'PdfArchive Component & Breadcrumb Schema',
        'changes' => array(
            array('type' => 'added', 'description' => 'PdfArchive Component - Grid/list display mode toggle'),
            array('type' => 'added', 'description' => 'Sorting options and search filtering'),
            array('type' => 'added', 'description' => 'Breadcrumb Schema added to PdfSeo component'),
            array('type' => 'changed', 'description' => 'Version bump to 1.2.2'),
        ),
    ),
    array(
        'version' => '1.2.0',
        'date'    => 'January 25, 2025',
        'type'    => 'both',
        'title'   => 'Initial React/Next.js Package Release — Pro & Pro+ Tiers',
        'changes' => array(
            array('type' => 'added', 'description' => 'Three Pro tiers: Pro 1 Site (€49/yr), Pro 5 Sites (€99/yr), Pro Unlimited (€199/yr)'),
            array('type' => 'added', 'description' => '@pdf-embed-seo/core - TypeScript types, API client, PDF.js integration, Schema.org generators'),
            array('type' => 'added', 'description' => '@pdf-embed-seo/react - PdfViewer, PdfArchive, PdfSeo, PdfProvider components'),
            array('type' => 'added', 'description' => 'Hooks: usePdf, usePdfList, usePdfViewer, useProgress'),
            array('type' => 'added', 'description' => 'Next.js optimized exports at @pdf-embed-seo/react/nextjs'),
            array('type' => 'added', 'description' => 'Pro: @pdf-embed-seo/react-premium - PdfAnalytics, PdfPasswordModal, PdfSearch, PdfBookmarks'),
            array('type' => 'added', 'description' => 'Pro: Premium hooks - useAnalytics, usePassword, useSearch, useBookmarks'),
            array('type' => 'added', 'description' => 'Pro: PdfProgressBar component and AI/GEO/AEO Schema support'),
            array('type' => 'changed', 'description' => 'Version 1.2.0 (synced with WordPress/Drupal)'),
        ),
    ),
);

/**
 * Get change type badge HTML
 */
function pdfviewer_change_badge($type) {
    $styles = array(
        'added'    => 'bg-green-50 text-green-700 shadow-[0_1px_3px_rgba(34,197,94,0.2)]',
        'improved' => 'bg-blue-50 text-blue-700 shadow-[0_1px_3px_rgba(59,130,246,0.2)]',
        'fixed'    => 'bg-amber-50 text-amber-700 shadow-[0_1px_3px_rgba(245,158,11,0.2)]',
        'changed'  => 'bg-purple-50 text-purple-700 shadow-[0_1px_3px_rgba(168,85,247,0.2)]',
    );
    $style = isset($styles[$type]) ? $styles[$type] : $styles['changed'];
    $label = ucfirst($type);
    return '<span class="inline-flex items-center text-[10px] md:text-xs font-semibold px-2.5 md:px-3 py-1 rounded-full ' . esc_attr($style) . '">' . esc_html($label) . '</span>';
}

/**
 * Get version badge HTML
 */
function pdfviewer_version_badge($type) {
    if ($type === 'both') {
        return '<div class="flex gap-1.5 md:gap-2">
            <span class="text-[10px] md:text-xs font-medium px-2 py-0.5 md:py-1 rounded-md bg-muted text-muted-foreground border border-border">Free</span>
            <span class="text-[10px] md:text-xs font-medium px-2 py-0.5 md:py-1 rounded-md bg-primary/10 text-primary border border-primary/20">Pro</span>
        </div>';
    }
    if ($type === 'proPlus') {
        return '<span class="text-[10px] md:text-xs font-medium px-2 py-0.5 md:py-1 rounded-md bg-accent/10 text-accent border border-accent/20">Pro+</span>';
    }
    if ($type === 'pro') {
        return '<span class="text-[10px] md:text-xs font-medium px-2 py-0.5 md:py-1 rounded-md bg-primary/10 text-primary border border-primary/20">Pro Only</span>';
    }
    return '<span class="text-[10px] md:text-xs font-medium px-2 py-0.5 md:py-1 rounded-md bg-muted text-muted-foreground border border-border">Free</span>';
}

/**
 * Render changelog entries
 */
function pdfviewer_render_changelog_entries($changelog) {
    if (empty($changelog)) {
        echo '<p class="text-center text-muted-foreground py-8">' . esc_html__('No changelog entries for this platform yet.', 'pdfviewer') . '</p>';
        return;
    }
    $index = 0;
    foreach ($changelog as $entry) : ?>
        <article class="relative animate-fade-in" style="animation-delay: <?php echo esc_attr($index * 0.03); ?>s">
            <div class="bg-card rounded-lg md:rounded-xl border border-border shadow-soft overflow-hidden">
                <div class="bg-muted/30 border-b border-border px-4 md:px-6 py-3 md:py-4">
                    <div class="flex flex-wrap items-center gap-2 md:gap-4">
                        <h2 class="text-xl md:text-2xl font-bold text-foreground">v<?php echo esc_html($entry['version']); ?></h2>
                        <?php echo pdfviewer_version_badge($entry['type']); ?>
                        <span class="text-xs md:text-sm text-muted-foreground ml-auto"><?php echo esc_html($entry['date']); ?></span>
                    </div>
                    <h3 class="text-sm md:text-base font-medium text-muted-foreground mt-1.5 md:mt-2"><?php echo esc_html($entry['title']); ?></h3>
                </div>
                <div class="px-4 md:px-6 py-4 md:py-5">
                    <ul class="list-none pl-0 m-0 space-y-3 md:space-y-2">
                        <?php foreach ($entry['changes'] as $change) : ?>
                            <li class="text-[13px] md:text-sm text-foreground/80 leading-relaxed">
                                <?php echo pdfviewer_change_badge($change['type']); ?>
                                <span class="ml-2"><?php echo esc_html($change['description']); ?></span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </article>
    <?php
        $index++;
    endforeach;
}
?>

<section class="pt-20 pb-20 md:pt-28 md:pb-24 lg:pt-32 lg:pb-32 overflow-x-hidden" aria-labelledby="changelog-heading">
    <div class="container mx-auto px-4 lg:px-8 overflow-hidden">
        <header class="max-w-3xl mx-auto text-center mb-10 md:mb-16">
            <h1 id="changelog-heading" class="text-3xl md:text-4xl lg:text-5xl font-bold text-foreground leading-tight mb-4 md:mb-6">
                <?php esc_html_e('Changelog', 'pdfviewer'); ?>
            </h1>
            <p class="text-base md:text-lg text-muted-foreground px-2">
                <?php esc_html_e('Complete version history for PDF Embed & SEO Optimize. See all new features, improvements, and bug fixes.', 'pdfviewer'); ?>
            </p>
        </header>

        <!-- Platform Tabs -->
        <div class="max-w-4xl mx-auto mb-8 md:mb-12">
            <div class="flex gap-2 max-w-xl mx-auto" role="tablist" aria-label="<?php esc_attr_e('Filter changelog by platform', 'pdfviewer'); ?>">
                <button
                    id="wordpress"
                    role="tab"
                    aria-selected="true"
                    aria-controls="panel-wordpress"
                    class="platform-tab flex-1 flex items-center justify-center gap-2 px-4 py-2 rounded-lg font-medium transition-colors"
                    data-platform="wordpress"
                >
                    <?php pdfviewer_wordpress_icon(18); ?>
                    <?php esc_html_e('WordPress', 'pdfviewer'); ?>
                </button>
                <button
                    id="drupal"
                    role="tab"
                    aria-selected="false"
                    aria-controls="panel-drupal"
                    class="platform-tab flex-1 flex items-center justify-center gap-2 px-4 py-2 rounded-lg font-medium transition-colors"
                    data-platform="drupal"
                >
                    <?php pdfviewer_drupal_icon(18); ?>
                    <?php esc_html_e('Drupal', 'pdfviewer'); ?>
                </button>
                <button
                    id="react"
                    role="tab"
                    aria-selected="false"
                    aria-controls="panel-react"
                    class="platform-tab flex-1 flex items-center justify-center gap-2 px-4 py-2 rounded-lg font-medium transition-colors"
                    data-platform="react"
                >
                    <?php pdfviewer_react_icon(18); ?>
                    <?php esc_html_e('React', 'pdfviewer'); ?>
                </button>
            </div>
        </div>

        <!-- Tab Panels (use hidden attribute for JS compatibility with initTabs) -->
        <div class="max-w-4xl mx-auto">
            <div id="panel-wordpress" role="tabpanel" aria-labelledby="wordpress" class="platform-panel">
                <div class="space-y-6 md:space-y-10">
                    <?php pdfviewer_render_changelog_entries($changelog_wordpress); ?>
                </div>
            </div>

            <div id="panel-drupal" role="tabpanel" aria-labelledby="drupal" class="platform-panel" hidden>
                <div class="space-y-6 md:space-y-10">
                    <?php pdfviewer_render_changelog_entries($changelog_drupal); ?>
                </div>
            </div>

            <div id="panel-react" role="tabpanel" aria-labelledby="react" class="platform-panel" hidden>
                <div class="space-y-6 md:space-y-10">
                    <?php pdfviewer_render_changelog_entries($changelog_react); ?>
                </div>
            </div>
        </div>
    </div>
</section>

<?php get_footer(); ?>
