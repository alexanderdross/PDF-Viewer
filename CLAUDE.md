# PDF Embed & SEO Optimize - Complete Repository Documentation

A comprehensive PDF management solution available for WordPress, Drupal, and React/Next.js that uses Mozilla's PDF.js library to securely display PDFs with SEO optimization. This repository also contains the marketing website (WordPress theme) and a centralized license management system.

**Current Version:** 1.3.0 (Pro+ Enterprise), 1.2.11 (Free & Premium)
**Platforms:** WordPress (Free, Premium & Pro+), Drupal 10/11 (Free, Premium & Pro+), React/Next.js
**License:** GPL v2 or later (WordPress/Drupal), MIT (React Free), Commercial (React Pro/Pro+)
**Website:** https://pdfviewer.drossmedia.de
**Author:** [Dross:Media](https://dross.net/media/)

---

## Table of Contents

1. [Repository Overview](#repository-overview)
2. [Repository Structure](#repository-structure)
3. [WordPress Theme (Marketing Website)](#wordpress-theme-marketing-website)
4. [WordPress Plugin (PDF Embed & SEO)](#wordpress-plugin-pdf-embed--seo)
5. [Drupal Module (PDF Embed & SEO)](#drupal-module-pdf-embed--seo)
6. [React/Next.js Packages](#reactnextjs-packages)
7. [License Manager (PDF License Manager)](#license-manager-pdf-license-manager)
8. [REST API Reference](#rest-api-reference)
9. [WordPress Hooks Reference](#wordpress-hooks-reference)
10. [Drupal Hooks Reference](#drupal-hooks-reference)
11. [React Hooks Reference](#react-hooks-reference)
12. [Data Models](#data-models)
13. [URL Structure](#url-structure)
14. [Feature Matrix](#feature-matrix)
15. [Security Measures](#security-measures)
16. [Dependencies](#dependencies)
17. [Testing & QA](#testing--qa)
18. [Distribution & Releases](#distribution--releases)
19. [Changelog Summary](#changelog-summary)

---

## Repository Overview

This monorepo contains **five major components**:

| Component | Directory | Technology | Purpose |
|-----------|-----------|------------|---------|
| Marketing Website | `theme/wp-theme/pdfviewer-theme/` | WordPress Theme (Tailwind CSS) | Product website at pdfviewer.drossmedia.de |
| WordPress Plugin | `pdf-embed-seo-optimize/` | PHP (WordPress 5.8+) | PDF viewer plugin (Free + Premium + Pro+) |
| Drupal Module | `drupal-pdf-embed-seo/` | PHP (Drupal 10/11) | PDF viewer module (Free + Premium + Pro+) |
| React Packages | `react-pdf-embed-seo/` | TypeScript/React 18+ | PDF viewer components (Free + Pro + Pro+) |
| License Manager | `license-dashboard/` | PHP (WordPress Plugin) | Central license management system |

### Module Matrix

| Module | Directory | Platform | Version | Features |
|--------|-----------|----------|---------|----------|
| WP Free | `pdf-embed-seo-optimize/` | WordPress 5.8+ | 1.2.10 | Core PDF viewer, SEO, REST API |
| WP Premium | `pdf-embed-seo-optimize/premium/` | WordPress 5.8+ | 1.2.10 | Analytics, passwords, progress, sitemap |
| WP Pro+ | `pdf-embed-seo-optimize/pro-plus/` | WordPress 5.8+ | 1.3.0 | Advanced analytics, annotations, versioning, webhooks, 2FA, compliance |
| Drupal Free | `drupal-pdf-embed-seo/` | Drupal 10/11 | 1.2.11 | Core PDF viewer, SEO, REST API |
| Drupal Premium | `drupal-pdf-embed-seo/modules/pdf_embed_seo_premium/` | Drupal 10/11 | 1.2.11 | Analytics, passwords, progress, sitemap |
| Drupal Pro+ | `drupal-pdf-embed-seo/modules/pdf_embed_seo_pro_plus/` | Drupal 10/11 | 1.3.0 | Advanced analytics, annotations, versioning, webhooks, 2FA, compliance |
| React Free | `react-pdf-embed-seo/packages/react/` | React 18+/Next.js 13+ | 1.2.10 | Components, hooks, SEO, API client |
| React Pro | `react-pdf-embed-seo/packages/react-premium/` | React 18+/Next.js 13+ | 1.2.10 | Analytics, passwords, progress, search |
| React Pro+ | `react-pdf-embed-seo/packages/react-pro-plus/` | React 18+/Next.js 13+ | 1.3.0 | Heatmaps, annotations, versioning, webhooks, 2FA, compliance |

### NPM Packages (React/Next.js)

| Package | Version | License | Description |
|---------|---------|---------|-------------|
| `@pdf-embed-seo/core` | 1.2.10 | MIT | Core types, utilities, API client |
| `@pdf-embed-seo/react` | 1.2.10 | MIT | Free React components |
| `@pdf-embed-seo/react-premium` | 1.2.10 | Commercial | Pro React components |
| `@pdf-embed-seo/react-pro-plus` | 1.3.0 | Commercial | Pro+ Enterprise components |

---

## Repository Structure

```
PDF-Viewer/
├── CLAUDE.md                            # This file - complete project documentation
├── CLAUDE-REACT.md                      # React/Next.js module documentation
├── README.md                            # Quick start guide
├── CHANGELOG.md                         # Complete version history (all platforms)
├── CHANGELOG-WORDPRESS.md               # WordPress-specific changes
├── CHANGELOG-DRUPAL.md                  # Drupal-specific changes
├── CHANGELOG-REACT.md                   # React-specific changes
├── CHANGELOG-v1.3.0.md                  # Pro+ Enterprise v1.3.0 changelog
├── DOCUMENTATION.md                     # Extended developer documentation
├── FEATURES.md                          # Complete feature matrix
├── COMPARISON.md                        # Free vs Premium vs Pro+ comparison
├── PRO.md                               # Pro tier features and pricing
├── LASTENHEFT-LICENSE-CHECKER.md        # License checker system specification
├── DRUPAL-DOCUMENTATION-UPDATES.md      # Drupal technical documentation
├── DRUPAL-PREMIUM-FEATURES.md           # Drupal premium features overview
├── REACT-MODULE-PLAN.md                 # React module implementation plan
├── WEBSITE-UPDATE-GUIDE.md              # Website documentation update guide
│
├── theme/                               # Marketing website
│   └── wp-theme/pdfviewer-theme/        # WordPress theme (Tailwind CSS + vanilla JS)
│
├── pdf-embed-seo-optimize/              # WordPress plugin (Free + Premium + Pro+)
│   ├── includes/                        # Core PHP classes (8 files)
│   ├── admin/                           # Admin UI (views, CSS, JS)
│   ├── public/                          # Frontend (templates, CSS, JS)
│   ├── blocks/                          # Gutenberg block
│   ├── assets/pdfjs/                    # PDF.js library (bundled)
│   ├── tests/                           # PHPUnit tests
│   ├── premium/                         # Premium add-on
│   │   └── includes/                    # 10 premium PHP classes
│   └── pro-plus/                        # Pro+ Enterprise add-on
│       └── includes/                    # 9 enterprise PHP classes
│
├── drupal-pdf-embed-seo/                # Drupal module (Free + Premium + Pro+)
│   ├── src/                             # PHP source (Entity, Controller, Form, Plugin, Service)
│   ├── config/                          # Configuration schemas
│   ├── templates/                       # Twig templates
│   ├── assets/                          # CSS/JS assets
│   └── modules/
│       ├── pdf_embed_seo_premium/       # Premium submodule
│       └── pdf_embed_seo_pro_plus/      # Pro+ Enterprise submodule
│
├── react-pdf-embed-seo/                 # React/Next.js monorepo (pnpm + Turborepo)
│   ├── packages/
│   │   ├── core/                        # @pdf-embed-seo/core (types, API client)
│   │   ├── react/                       # @pdf-embed-seo/react (components, hooks)
│   │   ├── react-premium/               # @pdf-embed-seo/react-premium (pro components)
│   │   └── react-pro-plus/              # @pdf-embed-seo/react-pro-plus (enterprise)
│   └── tests/                           # Shared test files
│
├── license-dashboard/                   # PDF License Manager WordPress plugin (v1.0.1)
│   ├── includes/                        # PHP classes (7 files)
│   └── admin/                           # Admin views, CSS, JS
│
├── dist/                                # Pre-built distribution ZIPs
├── releases/                            # Release artifacts
├── tests/qa/                            # QA/UAT test plans and reports
├── docs/                                # API and technical documentation
├── pages/                               # Product page content
└── website-markup/                      # HTML markup templates
```

---

## WordPress Theme (Marketing Website)

### Overview

The marketing website theme for **pdfviewer.drossmedia.de** (also accessible via vanity domain **pdfviewermodule.com**).

**Location:** `theme/wp-theme/pdfviewer-theme/`
**Version:** 2.2.94
**Requires:** WordPress 6.0+, PHP 8.0+
**Tech Stack:** Tailwind CSS (compiled), vanilla JavaScript (no dependencies), self-hosted fonts (Inter + Outfit)

### Theme Structure

```
theme/wp-theme/pdfviewer-theme/
├── style.css                          # Theme metadata + imports
├── functions.php                      # Theme setup (35.9 KB) - enqueues, hooks, patterns
├── theme.json                         # Block editor configuration (colors, fonts, layout)
├── index.php                          # Fallback template
├── header.php                         # Site header with navigation (34.8 KB)
├── footer.php                         # Site footer (15.1 KB)
├── front-page.php                     # Homepage (63.7 KB)
├── page.php                           # Generic page template
├── 404.php                            # Error page
│
├── page-documentation.php             # Documentation/guides page (1,810 lines)
├── page-pro.php                       # Pro/pricing page (886 lines)
├── page-changelog.php                 # Changelog page (670 lines)
├── page-wordpress-pdf-viewer.php      # WordPress product page (621 lines)
├── page-nextjs-pdf-viewer.php         # Next.js product page (499 lines)
├── page-drupal-pdf-viewer.php         # Drupal product page (472 lines)
├── page-enterprise.php                # Enterprise/Pro+ page (354 lines)
├── page-examples.php                  # PDF examples showcase (249 lines)
├── page-pdf-grid.php                  # PDF gallery grid view (228 lines)
│
├── inc/
│   ├── critical-css.php               # Critical CSS inlining
│   ├── customizer.php                 # WP Customizer options
│   ├── performance.php                # Performance optimizations
│   ├── schema-markup.php              # JSON-LD structured data (FAQPage, vanity URLs)
│   ├── template-tags.php              # Helper functions (picture elements, WebP)
│   └── yoast-seo-setup.php            # Yoast SEO integration
│
├── patterns/                          # 10 Block Editor patterns
│   ├── hero.html                      # Hero section with gradient
│   ├── features.html                  # Feature cards with icons
│   ├── cta.html                       # Call to action
│   ├── faq.html                       # Expandable FAQ items
│   ├── comparison.html                # Feature comparison table
│   ├── pricing.html                   # Pricing tier cards
│   ├── problem-solution.html          # Problem & solution layout
│   ├── how-it-works.html              # Step-by-step process
│   ├── testimonials.html              # Customer reviews with ratings
│   └── geo-section.html               # GEO optimization features
│
├── assets/
│   ├── css/main.css                   # Compiled Tailwind CSS (58.5 KB)
│   ├── js/main.js                     # Vanilla JS (26.9 KB) - scroll, menu, accordion, ARIA
│   ├── fonts/                         # Self-hosted Inter + Outfit (woff2)
│   └── images/                        # Logos (WebP), favicons, OG images
│
├── sitemaps/
│   ├── vanity-sitemap.xml             # Dual-domain sitemap
│   └── pdf-vanity-sitemap.xml         # PDF-specific sitemap
│
├── tests/Unit/                        # PHPUnit tests
│   ├── IconFunctionTest.php
│   ├── PictureFunctionTest.php
│   ├── SocialMetaTest.php
│   └── TemplateFunctionsTest.php
│
└── phpunit.xml                        # Test configuration
```

### Theme Key Features

- **Block Editor Ready** - Full Gutenberg support with 10 custom block patterns
- **Accessibility** - WCAG 2.1 AA compliant (skip links, ARIA labels, focus states)
- **SEO** - Yoast integration, JSON-LD schemas (FAQPage), OpenGraph meta tags
- **Performance** - Self-hosted fonts, lazy loading, minimal vanilla JS, WebP images with PNG fallback
- **Responsive** - Mobile-first design
- **Dual-Domain** - Supports pdfviewer.drossmedia.de and pdfviewermodule.com
- **3 Navigation Menus** - Primary, footer, legal
- **Customizer** - Theme options for branding, colors, download URLs, social links

### Theme Configuration (`theme.json`)

- **Color Palette:** 9 colors (primary, accent, background, foreground, muted, destructive, etc.)
- **Gradients:** hero, accent, dark
- **Font Families:** Inter (body), Outfit (headings), Monospace
- **Font Sizes:** xs through 4xl with fluid sizing
- **Layout:** contentSize: 900px, wideSize: 1400px

### Theme Helper Functions (`inc/template-tags.php`)

- `pdfviewer_simple_picture()` - Picture element with WebP support and lazy loading
- `pdfviewer_schema_urls()` - Vanity domain URL generation
- `pdfviewer_faq_schema()` - Generates FAQPage schema from accordion/details elements

---

## WordPress Plugin (PDF Embed & SEO)

### Overview

**Location:** `pdf-embed-seo-optimize/`
**Main File:** `pdf-embed-seo-optimize.php` (v1.2.10)
**Requires:** WordPress 5.8+, PHP 7.4+
**Architecture:** Singleton pattern with lazy-loaded components

### Architecture

```
PDF_Embed_SEO (Main Class)
├── Post Type Handler    → Registers pdf_document CPT with /pdf/{slug}/ URLs
├── Frontend Renderer    → Template loading, PDF.js integration, toolbar generation
├── Admin Interface      → Meta boxes, settings page, dashboard widgets
├── REST API             → 5 public endpoints for API access
├── Yoast Integration    → JSON-LD schema, Open Graph, Twitter Cards
├── Shortcodes           → [pdf_viewer] and [pdf_viewer_sitemap]
├── Gutenberg Block      → pdf-embed-seo/pdf-viewer block
├── Thumbnail Generator  → ImageMagick/Ghostscript support
├── Premium Loader       → 9 additional feature classes (optional)
└── Pro+ Loader          → 9 enterprise feature classes (optional)
```

### Core Class Files

| File | Lines | Purpose |
|------|-------|---------|
| `pdf-embed-seo-optimize.php` | 365 | Main plugin bootstrap, singleton |
| `includes/class-pdf-embed-seo-optimize-post-type.php` | 275 | CPT registration, meta fields, view count |
| `includes/class-pdf-embed-seo-optimize-frontend.php` | 361 | Frontend rendering, PDF.js, toolbar, themes |
| `includes/class-pdf-embed-seo-optimize-admin.php` | 1,094 | Admin UI, meta boxes, settings page |
| `includes/class-pdf-embed-seo-optimize-rest-api.php` | 497 | REST API (5 endpoints) |
| `includes/class-pdf-embed-seo-optimize-yoast.php` | 1,094 | SEO: JSON-LD, Open Graph, Twitter Cards |
| `includes/class-pdf-embed-seo-optimize-shortcodes.php` | 161 | [pdf_viewer] and [pdf_viewer_sitemap] |
| `includes/class-pdf-embed-seo-optimize-block.php` | 191 | Gutenberg block registration |
| `includes/class-pdf-embed-seo-optimize-thumbnail.php` | 361 | Thumbnail generation (ImageMagick/Ghostscript) |

### Frontend Assets

| File | Size | Purpose |
|------|------|---------|
| `public/css/viewer-styles.css` | 1,051 lines | Viewer container, toolbar, canvas, themes, responsive, print CSS |
| `public/js/viewer-scripts.js` | 442 lines | PDF.js init, page nav, zoom, download, print, fullscreen |
| `public/views/single-pdf-document.php` | 286 lines | Single PDF template with breadcrumbs |
| `public/views/archive-pdf-document.php` | 200+ lines | Archive with grid/list modes |

### Admin Settings

Configurable via Settings page (`/wp-admin/edit.php?post_type=pdf_document&page=pdf-embed-seo-optimize-settings`):

- Viewer theme (light/dark), default height
- Archive: display style (grid/list), posts per page (default: 12), heading text
- Colors: font color, background color, item background color
- Layout: content alignment, width (boxed/full-width), breadcrumb visibility
- Thumbnails: auto-generate toggle
- Defaults: download/print permissions
- Custom favicon URL

### Shortcodes

**`[pdf_viewer id="123"]`** - Embed a PDF viewer
- Attributes: `id` (required), `width` (default: 100%), `height` (default: 800px)

**`[pdf_viewer_sitemap]`** - List all PDF documents
- Attributes: `orderby` (title/date/modified/menu_order), `order` (ASC/DESC), `limit` (-1 for all)

### Gutenberg Block

- **Block Name:** `pdf-embed-seo/pdf-viewer`
- **Attributes:** `pdfId`, `width`, `height`, `align`
- **Editor:** Preview with PDF thumbnail, live settings sidebar

### Premium Features (`premium/`)

**Loader:** `premium/class-pdf-embed-seo-premium.php` (420+ lines)

10 premium classes in `premium/includes/`:
1. **Taxonomies** - Categories and tags for PDFs
2. **Password Protection** - Document-level password security (bcrypt hashing)
3. **Role-Based Access** - User capability restrictions
4. **Analytics Dashboard** - View/download tracking with reports
5. **Viewer Enhancements** - Text search, bookmarks in viewer
6. **Bulk Operations** - CSV/ZIP import
7. **XML Sitemap** - `/pdf/sitemap.xml` with XSL stylesheet
8. **Premium REST API** - 14+ additional endpoints
9. **Schema Optimization** - AI summary, FAQ schema, TOC, reading time, difficulty level
10. **Premium Admin** - Enhanced admin UI

### Pro+ Enterprise Features (`pro-plus/`)

**Loader:** `pro-plus/class-pdf-embed-seo-pro-plus.php` (419+ lines)
**Requires:** Premium 1.2.0+

9 enterprise classes in `pro-plus/includes/`:
1. **Advanced Analytics** - Heatmaps, engagement scoring
2. **Security** - 2FA, IP whitelisting, audit logs
3. **Webhooks** - Third-party integrations
4. **White Label** - Branding customization
5. **Versioning** - Document version control/history
6. **Annotations** - Highlight tools, note-taking
7. **Compliance** - GDPR/HIPAA features
8. **Pro+ REST API** - Enterprise-only endpoints
9. **Pro+ Admin** - Enterprise settings/dashboards

### WordPress Plugin Tests

11+ PHPUnit test files covering:
- Post type registration and URLs
- Shortcode rendering and attributes
- REST endpoint responses
- JSON-LD schema output
- Sidebar removal on PDF pages
- Hook naming convention compliance
- Archive display customization
- Analytics tracking, password verification, SQL escaping (Premium)
- Expiring links, download tracking (Premium)

---

## Drupal Module (PDF Embed & SEO)

### Overview

**Location:** `drupal-pdf-embed-seo/`
**Version:** 1.2.11 (Free/Premium), 1.3.0 (Pro+)
**Requires:** Drupal 10 or 11, PHP 8.1+
**Dependencies:** node, file, taxonomy, path, path_alias, media

### Architecture

Three-tier modular system:
- **Free Module** (`pdf_embed_seo`) - Core PDF management and viewing
- **Premium Module** (`pdf_embed_seo_premium`) - Analytics, security, SEO enhancements
- **Pro+ Module** (`pdf_embed_seo_pro_plus`) - Enterprise features

### Entity: PdfDocument

Content entity with translations and ownership support.

**Base Fields:**

| Field | Type | Notes |
|-------|------|-------|
| `id` | serial | Primary key |
| `uuid` | uuid | Universal identifier |
| `title` | string(255) | Required, translatable |
| `description` | text_long | Translatable, optional |
| `pdf_file` | file | Required, max 50MB |
| `thumbnail` | image | Auto-generated |
| `allow_download` | boolean | Default FALSE |
| `allow_print` | boolean | Default FALSE |
| `view_count` | integer | **Computed field** (reads from analytics table) |
| `password` | string(255) | Premium: hashed password |
| `status` | boolean | Published/unpublished |
| `uid` | user_id | Author/owner |
| `created` | created | Creation timestamp |
| `changed` | changed | Last modified |

### Controllers

| Controller | Route | Purpose |
|-----------|-------|---------|
| `PdfViewController` | `/pdf/{pdf_document}` | Display single PDF |
| `PdfArchiveController` | `/pdf` | Archive listing (grid/list) |
| `PdfDataController` | `/pdf-data/{pdf_document}` | Secure PDF data (AJAX) |
| `PdfApiController` | `/api/pdf-embed-seo/v1/*` | REST API endpoints |

### Services (Free)

- `pdf_embed_seo.thumbnail_generator` - PDF thumbnail generation via ImageMagick/Ghostscript
- `pdf_embed_seo.analytics_tracker` - Basic view counting

### Premium Services (8 services)

1. `pdf_embed_seo.analytics_tracker` - Full analytics (IP, user agent, referrer tracking)
2. `pdf_embed_seo.progress_tracker` - Reading progress per user/document
3. `pdf_embed_seo.schema_enhancer` - GEO/AEO/LLM schema optimization
4. `pdf_embed_seo.access_manager` - Role-based access control
5. `pdf_embed_seo.viewer_enhancer` - Search, bookmarks, progress bar UI
6. `pdf_embed_seo.bulk_operations` - CSV/ZIP bulk import
7. `pdf_embed_seo.rate_limiter` - Brute force protection (5 attempts/5 min, 15 min block)
8. `pdf_embed_seo.access_token_storage` - Expiring link tokens with DB backend

### Pro+ Services (9 services)

1. `pdf_embed_seo_pro_plus.version_manager` - Document versioning
2. `pdf_embed_seo_pro_plus.annotation_manager` - User annotations
3. `pdf_embed_seo_pro_plus.audit_logger` - Compliance audit logs
4. `pdf_embed_seo_pro_plus.webhook_dispatcher` - External system webhooks
5. `pdf_embed_seo_pro_plus.advanced_analytics` - Heatmaps, engagement metrics
6. `pdf_embed_seo_pro_plus.compliance_manager` - GDPR/HIPAA tracking
7. `pdf_embed_seo_pro_plus.two_factor_auth` - 2FA for sensitive PDFs
8. `pdf_embed_seo_pro_plus.white_label` - Custom branding
9. `pdf_embed_seo_pro_plus.license_validator` - License validation

### Templates (Twig)

- `pdf-document.html.twig` - Single PDF page wrapper
- `pdf-viewer.html.twig` - PDF.js viewer with toolbar (page nav, zoom, search, print, download, fullscreen)
- `pdf-archive.html.twig` - Archive listing with breadcrumbs, styling options
- `pdf-archive-item.html.twig` - Individual archive item (thumbnail, title, excerpt, metadata)
- `pdf-password-form.html.twig` - Password entry form (Premium)
- `pdf-analytics-dashboard.html.twig` - Analytics dashboard (Premium)

### Drupal Permissions (8)

1. `administer pdf embed seo` - Admin settings
2. `access pdf document overview` - View admin list
3. `view pdf document` - View published PDFs (frontend)
4. `create pdf document` - Create new PDFs
5. `edit pdf document` / `edit own pdf document` - Edit PDFs
6. `delete pdf document` / `delete own pdf document` - Delete PDFs

### Database Tables

**Free:** `pdf_embed_seo_analytics` (view tracking)
**Premium:** `pdf_embed_seo_rate_limit` (brute force), `pdf_embed_seo_access_tokens` (expiring links)

### Key Architecture Decisions

1. **Computed View Count** - No entity saves on page views; reads from analytics table for performance
2. **REST API for PDF Data** - Direct PDF URLs hidden; served via secure endpoint
3. **Session Cache Context** - Prevents cross-session cache leaks on password-protected PDFs
4. **Rate Limiting with DB** - Dedicated table persists across requests, works in multi-server environments
5. **Access Token Storage** - Replaced State API with dedicated DB table for scalability

### Plugins

- **PdfViewerBlock** - Block plugin for embedding PDF viewers in any block region
- **PdfViewerFormatter** - Field formatter to display any file field as PDF viewer
- **PdfDocument MediaSource** - Media Library integration for PDFs (v1.2.11+)

---

## React/Next.js Packages

### Overview

**Location:** `react-pdf-embed-seo/`
**Monorepo:** pnpm workspaces + Turborepo
**Build:** tsup (ESM + CJS + DTS)
**Tests:** Vitest + @testing-library/react
**Node:** 18+, React 18+/19+, Next.js 13+/14+/15+ (optional)

### Package Architecture

```
react-pdf-embed-seo/
├── package.json                    # Root: pnpm workspaces + Turborepo
├── turbo.json                      # Build pipeline configuration
├── tsconfig.base.json              # Shared TypeScript config
├── packages/
│   ├── core/                       # @pdf-embed-seo/core (types, API client, utils)
│   ├── react/                      # @pdf-embed-seo/react (components, hooks, styles)
│   ├── react-premium/              # @pdf-embed-seo/react-premium (pro components)
│   └── react-pro-plus/             # @pdf-embed-seo/react-pro-plus (enterprise)
└── tests/
    └── unit/                       # Shared unit tests
```

### Core Package (`@pdf-embed-seo/core`)

**Version:** 1.2.10 | **License:** MIT

**Exports:** `.`, `./types`, `./utils`, `./api`

```
packages/core/src/
├── index.ts                        # Main exports
├── types/
│   ├── index.ts                    # Re-exports
│   ├── document.ts                 # PdfDocument, PdfDocumentInfo interfaces
│   ├── settings.ts                 # PdfSettings, ViewerOptions interfaces
│   └── api.ts                      # API response types
├── api/
│   ├── index.ts                    # API client exports
│   ├── wordpress.ts                # WordPress REST API client
│   └── standalone.ts               # Standalone API client
├── constants/
│   ├── index.ts
│   └── defaults.ts                 # Default configuration values
└── utils/                          # Utility functions
```

### React Package (`@pdf-embed-seo/react`)

**Version:** 1.2.10 | **License:** MIT

**Exports:** `.`, `./nextjs`, `./styles`, `./styles/viewer`, `./styles/archive`, `./styles/dark`

```
packages/react/src/
├── index.ts                        # Main exports
├── components/
│   ├── PdfViewer/
│   │   ├── PdfViewer.tsx           # Main viewer component
│   │   ├── PdfToolbar.tsx          # Toolbar with controls
│   │   ├── PdfPageNav.tsx          # Page navigation
│   │   └── PdfZoomControls.tsx     # Zoom in/out/fit
│   ├── PdfArchive/
│   │   ├── PdfArchive.tsx          # Archive listing component
│   │   ├── PdfCard.tsx             # Grid card component
│   │   ├── PdfGrid.tsx             # Grid layout
│   │   └── PdfList.tsx             # List layout
│   ├── PdfSeo/
│   │   ├── PdfJsonLd.tsx           # JSON-LD structured data
│   │   ├── PdfMeta.tsx             # Meta tags (OG, Twitter)
│   │   └── PdfBreadcrumbs.tsx      # Breadcrumb navigation
│   └── PdfProvider/
│       ├── PdfProvider.tsx         # Context provider
│       └── PdfContext.ts           # React context
├── hooks/
│   ├── usePdfDocument.ts           # Fetch single PDF document
│   ├── usePdfDocuments.ts          # Fetch paginated document list
│   ├── usePdfViewer.ts             # Viewer state management
│   ├── usePdfSeo.ts                # SEO metadata hook
│   └── usePdfTheme.ts              # Theme management hook
├── nextjs/
│   ├── index.ts                    # Next.js exports
│   ├── route-handlers.ts           # API route handlers
│   ├── metadata.ts                 # Next.js metadata generation
│   └── static-params.ts            # Static params for SSG
└── styles/
    ├── index.css                   # Combined styles
    ├── viewer.css                  # Viewer styles
    ├── viewer-dark.css             # Dark theme
    └── archive.css                 # Archive styles
```

### React Premium Package (`@pdf-embed-seo/react-premium`)

**Version:** 1.2.10 | **License:** Commercial

```
packages/react-premium/src/
├── index.ts
├── components/
│   ├── PdfProgressBar/             # Reading progress bar
│   ├── PdfBookmarks/               # Bookmark navigation
│   ├── PdfSearch/                   # In-document text search
│   ├── PdfAnalytics/                # Analytics dashboard
│   └── PdfPasswordModal/           # Password protection modal
├── hooks/
│   ├── useReadingProgress.ts       # Reading progress tracking
│   ├── useAnalytics.ts             # Analytics tracking
│   └── usePasswordProtection.ts    # Password verification
└── nextjs/
    ├── index.ts                    # Next.js exports
    ├── middleware.ts               # Auth/license middleware
    └── sitemap.ts                  # XML sitemap generation
```

### React Pro+ Package (`@pdf-embed-seo/react-pro-plus`)

**Version:** 1.3.0 | **License:** Commercial

```
packages/react-pro-plus/src/
├── index.ts
├── types/index.ts                  # Pro+ TypeScript types
├── context/ProPlusContext.tsx       # Pro+ context provider
├── components/
│   ├── PdfHeatmap.tsx              # Page heatmap visualization
│   ├── PdfAdvancedAnalytics.tsx    # Advanced analytics dashboard
│   ├── PdfAnnotations.tsx          # Annotation display/management
│   ├── PdfAnnotationToolbar.tsx    # Annotation creation toolbar
│   ├── PdfVersionHistory.tsx       # Document version history
│   ├── PdfWebhookConfig.tsx        # Webhook configuration UI
│   ├── PdfAuditLog.tsx             # Audit log viewer
│   ├── PdfTwoFactorAuth.tsx        # 2FA component
│   ├── PdfComplianceConsent.tsx    # GDPR/HIPAA consent
│   └── PdfWhiteLabel.tsx           # White-label branding
├── hooks/
│   ├── useHeatmap.ts               # Heatmap data hook
│   ├── useAdvancedAnalytics.ts     # Advanced analytics hook
│   ├── useAnnotations.ts           # Annotations CRUD hook
│   ├── useVersions.ts              # Version management hook
│   ├── useWebhooks.ts              # Webhook management hook
│   ├── useAuditLog.ts              # Audit log hook
│   ├── useTwoFactorAuth.ts         # 2FA verification hook
│   ├── useCompliance.ts            # Compliance tracking hook
│   ├── useWhiteLabel.ts            # White-label configuration hook
│   └── useProPlusLicense.ts        # License validation hook
├── utils/
│   ├── license.ts                  # License key validation
│   ├── webhook.ts                  # Webhook utilities
│   └── compliance.ts               # Compliance helpers
└── nextjs/index.ts                 # Next.js exports
```

### React Usage Examples

```tsx
// Provider setup (app/layout.tsx)
import { PdfProvider } from '@pdf-embed-seo/react';
import '@pdf-embed-seo/react/styles';

<PdfProvider config={{
  apiBaseUrl: 'https://your-site.com/wp-json/pdf-embed-seo/v1',
  backendType: 'wordpress', // or 'drupal'
}}>
  {children}
</PdfProvider>

// Display viewer
import { PdfViewer } from '@pdf-embed-seo/react';
<PdfViewer documentId={id} height="800px" theme="light" allowDownload allowPrint />

// Archive listing
import { PdfArchive } from '@pdf-embed-seo/react';
<PdfArchive displayMode="grid" perPage={12} showSearch showPagination />

// SEO
import { PdfSeo } from '@pdf-embed-seo/react';
<PdfSeo document={document} />

// Hooks
const { document, loading, error } = usePdfDocument(id);
const { documents, pagination, fetchPage } = usePdfDocuments({ perPage: 10 });

// Premium components
import { PdfPasswordModal, PdfProgressBar, PdfSearch } from '@pdf-embed-seo/react-premium';

// Next.js optimized imports
import { PdfViewer, PdfArchive } from '@pdf-embed-seo/react/nextjs';
```

### React Test Files

```
tests/unit/core.test.ts              # Core package tests
tests/unit/react-components.test.tsx # Component tests
tests/unit/react-hooks.test.ts       # Hook tests
tests/unit/premium.test.tsx          # Premium component tests
packages/react-pro-plus/tests/       # Pro+ tests (annotations, compliance, versions, webhooks, license)
```

---

## License Manager (PDF License Manager)

### Overview

**Location:** `license-dashboard/`
**Version:** 1.0.1
**Type:** Standalone WordPress plugin
**Deployed on:** pdfviewer.drossmedia.de

Central license management system that validates, activates, and manages licenses for all three platforms (WordPress, Drupal, React/Next.js).

### Architecture

```
license-dashboard/
├── pdf-license-manager.php              # Main plugin bootstrap
├── README.md                            # Documentation
├── includes/
│   ├── class-plm-database.php           # 6-table schema via dbDelta()
│   ├── class-plm-license.php            # Key generation, validation, IP anonymization
│   ├── class-plm-api.php                # REST API (5 endpoints + rate limiting)
│   ├── class-plm-geoip.php              # MaxMind GeoLite2 integration
│   ├── class-plm-stripe.php             # Stripe webhook handler (native, no SDK)
│   ├── class-plm-admin.php              # 7 admin pages
│   └── class-plm-dashboard-widget.php   # Dashboard KPI widget
└── admin/
    ├── views/                           # 7 admin view files
    │   ├── dashboard.php                # KPIs + quick actions + API docs
    │   ├── licenses.php                 # License list + create form
    │   ├── license-detail.php           # Single license + installations + actions
    │   ├── installations.php            # 9-column installations table
    │   ├── stats.php                    # Geo, platform, version distribution charts
    │   ├── audit-log.php                # Paginated event log
    │   └── settings.php                 # Stripe product mapping
    ├── css/admin.css                    # Admin styles
    └── js/admin.js                      # Copy-to-clipboard, plan auto-update
```

### Database Schema (6 Tables)

| Table | Purpose |
|-------|---------|
| `wp_plm_licenses` | Master license records (key, type, plan, status, expiration, Stripe info) |
| `wp_plm_installations` | Where licenses are activated (site_url, platform, versions, heartbeat) |
| `wp_plm_geo_data` | GeoIP data per installation (country, region, city, coordinates) |
| `wp_plm_audit_logs` | Complete audit trail (event types, details, anonymized IP) |
| `wp_plm_stripe_events` | Stripe webhook event log (idempotency) |
| `wp_plm_stripe_product_map` | Stripe Product ID to license type/plan mapping |

### License Key Formats

| Type | Format | Example |
|------|--------|---------|
| Premium | `PDF$PRO#XXXX-XXXX@XXXX-XXXX!XXXX` | `PDF$PRO#A1B2-C3D4@E5F6-G7H8!I9J0` |
| Pro+ | `PDF$PRO+#XXXX-XXXX@XXXX-XXXX!XXXX` | `PDF$PRO+#A1B2-C3D4@E5F6-G7H8!I9J0` |
| Unlimited | `PDF$UNLIMITED#XXXX@XXXX!XXXX` | `PDF$UNLIMITED#A1B2@C3D4!E5F6` |
| Dev | `PDF$DEV#XXXX-XXXX@XXXX!XXXX` | `PDF$DEV#A1B2-C3D4@E5F6!G7H8` |

### License Plans & Site Limits

| Plan | Site Limit | Description |
|------|-----------|-------------|
| Starter | 1 | Single site |
| Professional | 5 | Up to 5 sites |
| Agency/Enterprise | Unlimited | No limit |
| Dev | Free | Development only |

**Local domains** (localhost, .test, .dev, .staging, private IPs) do not count toward site limits.

### REST API Endpoints

**Base URL:** `/wp-json/plm/v1/`

| Method | Endpoint | Purpose | Rate Limit |
|--------|----------|---------|------------|
| `GET` | `/health` | Health check | 60/min |
| `POST` | `/license/validate` | Validate license key | 60/min |
| `POST` | `/license/activate` | Activate for a site | 10/hour per key |
| `POST` | `/license/deactivate` | Deactivate for a site | 60/min |
| `POST` | `/license/check` | Heartbeat/status check (24h interval) | 1000/day per key |
| `POST` | `/webhook/stripe` | Stripe webhook receiver | N/A |

### License Lifecycle

```
inactive (created) → active (first activation) → grace_period (14 days post-expiry) → expired
                                                ↓
                                            revoked (manual)
```

### Stripe Integration

- **Native PHP** implementation (no Stripe SDK required)
- **HMAC-SHA256** signature verification with 5-minute timestamp tolerance
- **Handled Events:**
  - `checkout.session.completed` - Creates new license from product mapping
  - `invoice.paid` - Extends license by 365 days (renewal)
  - `customer.subscription.deleted` - Logs cancellation
  - `invoice.payment_failed` - Logs failure
- **Idempotency:** Duplicate events rejected via unique `stripe_event_id`

### GeoIP (MaxMind GeoLite2)

- Stores anonymized IP (last octet zeroed for IPv4, last 80 bits for IPv6)
- Looks up: country, region, city, timezone, coordinates
- Refreshes automatically every 30 days on heartbeat
- GDPR-compliant: IP anonymized after lookup

### Security

- SQL injection prevention: `$wpdb->prepare()` throughout
- Rate limiting via WordPress Transients API
- Admin access: `manage_options` capability required
- License keys: cryptographically secure `random_bytes()`
- Masked key display in admin UI
- Audit trail for all operations
- Input: `sanitize_text_field()`, `esc_url_raw()`, `absint()`, `sanitize_email()`
- Output: `esc_html()`, `esc_attr()`, `esc_url()`

### Configuration Constants

```php
define( 'PLM_VERSION', '1.0.1' );
define( 'PLM_GRACE_PERIOD_DAYS', 14 );
define( 'PLM_LATEST_PLUGIN_VERSION', '1.3.0' );
define( 'PLM_STRIPE_WEBHOOK_SECRET', 'whsec_...' );  // wp-config.php
```

---

## REST API Reference

### API Base URLs

| Platform | Base URL |
|----------|----------|
| WordPress | `/wp-json/pdf-embed-seo/v1/` |
| Drupal | `/api/pdf-embed-seo/v1/` |
| License Manager | `/wp-json/plm/v1/` |
| React/Next.js | Configurable via `PdfProvider` (connects to WP or Drupal backend) |

### Public Endpoints (Free)

| Method | Endpoint | Description | Auth |
|--------|----------|-------------|------|
| `GET` | `/documents` | List all published PDFs | None |
| `GET` | `/documents/{id}` | Get single PDF details | None |
| `GET` | `/documents/{id}/data` | Get PDF file URL securely | None |
| `POST` | `/documents/{id}/view` | Track PDF view | None (WP) / CSRF (Drupal) |
| `GET` | `/settings` | Get public settings | None |

### Premium Endpoints

| Method | Endpoint | Description | Auth |
|--------|----------|-------------|------|
| `GET` | `/analytics` | Analytics overview | Admin |
| `GET` | `/analytics/documents` | Per-document analytics | Admin |
| `GET` | `/analytics/export` | Export analytics CSV/JSON | Admin |
| `GET` | `/documents/{id}/progress` | Get reading progress | None |
| `POST` | `/documents/{id}/progress` | Save reading progress | None |
| `POST` | `/documents/{id}/verify-password` | Verify PDF password | None |
| `POST` | `/documents/{id}/download` | Track PDF download | None |
| `POST` | `/documents/{id}/expiring-link` | Generate expiring access link | Admin |
| `GET` | `/documents/{id}/expiring-link/{token}` | Validate expiring link | None |
| `GET` | `/categories` | List PDF categories | None |
| `GET` | `/tags` | List PDF tags | None |
| `POST` | `/bulk/import` | Start bulk import | Admin |
| `GET` | `/bulk/import/status` | Get import status | Admin |

### Query Parameters for `/documents`

| Parameter | Default | Description |
|-----------|---------|-------------|
| `page` | 1 | Page number |
| `per_page` | 10 | Items per page (max 100) |
| `search` | - | Search term |
| `orderby` | date | Sort: date, title, modified, views |
| `order` | desc | Sort direction: asc, desc |

### Response Format

```json
{
  "id": 123,
  "title": "Document Title",
  "slug": "document-slug",
  "url": "https://site.com/pdf/document-slug/",
  "excerpt": "Description...",
  "date": "2024-01-15T10:30:00+00:00",
  "modified": "2024-06-20T14:45:00+00:00",
  "views": 1542,
  "thumbnail": "https://site.com/uploads/thumb.jpg",
  "allow_download": true,
  "allow_print": false
}
```

---

## WordPress Hooks Reference

### Actions

| Hook | Parameters | Description |
|------|------------|-------------|
| `pdf_embed_seo_pdf_viewed` | `$post_id, $count` | PDF was viewed |
| `pdf_embed_seo_premium_init` | - | Premium features initialized |
| `pdf_embed_seo_optimize_settings_saved` | `$post_id, $settings` | Settings saved |
| `pdf_embed_seo_pro_plus_init` | - | Pro+ features initialized |

### Filters

| Hook | Parameters | Description |
|------|------------|-------------|
| `pdf_embed_seo_post_type_args` | `$args` | Modify CPT registration |
| `pdf_embed_seo_schema_data` | `$schema, $post_id` | Modify Schema.org data |
| `pdf_embed_seo_archive_schema_data` | `$schema` | Modify archive schema |
| `pdf_embed_seo_archive_query` | `$posts_per_page` | Modify archive query |
| `pdf_embed_seo_archive_title` | `$title` | Modify archive title |
| `pdf_embed_seo_archive_description` | `$description` | Modify archive description |
| `pdf_embed_seo_sitemap_query_args` | `$query_args, $atts` | Modify sitemap query |
| `pdf_embed_seo_viewer_options` | `$options, $post_id` | Modify viewer options |
| `pdf_embed_seo_allowed_types` | `$types` | Modify allowed MIME types |
| `pdf_embed_seo_rest_document` | `$data, $post, $detailed` | Modify REST response |
| `pdf_embed_seo_rest_document_data` | `$data, $post_id` | Modify REST data response |
| `pdf_embed_seo_rest_settings` | `$settings` | Modify REST settings |
| `pdf_embed_seo_can_access_pdf` | `$can_access, $post_id` | Override PDF access check |

### Premium Filters

| Hook | Parameters | Description |
|------|------------|-------------|
| `pdf_embed_seo_password_error` | `$error` | Custom password error |
| `pdf_embed_seo_verify_password` | `$is_valid, $post_id, $password` | Override password check |
| `pdf_embed_seo_rest_analytics` | `$data, $period` | Modify analytics response |

---

## Drupal Hooks Reference

### Alter Hooks

| Hook | Description |
|------|-------------|
| `hook_pdf_embed_seo_document_data_alter` | Modify API document data |
| `hook_pdf_embed_seo_api_settings_alter` | Modify API settings |
| `hook_pdf_embed_seo_verify_password_alter` | Override password verification |
| `hook_pdf_embed_seo_viewer_options_alter` | Modify viewer options |
| `hook_pdf_embed_seo_schema_alter` | Modify Schema.org output |

### Event Hooks

| Hook | Description |
|------|-------------|
| `hook_pdf_embed_seo_view_tracked` | PDF view was tracked |
| `hook_pdf_embed_seo_document_saved` | PDF document saved |

---

## React Hooks Reference

### Core Hooks (`@pdf-embed-seo/react`)

| Hook | Parameters | Returns | Description |
|------|------------|---------|-------------|
| `usePdfDocument` | `documentId` | `{ document, loading, error, refetch }` | Fetch single PDF |
| `usePdfDocuments` | `options?` | `{ documents, pagination, loading, error, fetchPage }` | Fetch paginated list |
| `usePdfViewer` | `documentId` | `{ viewerState, setPage, setZoom, setTheme }` | Viewer state management |
| `usePdfSeo` | `document` | `{ jsonLd, meta }` | SEO metadata |
| `usePdfTheme` | - | `{ theme, setTheme }` | Theme management |

### Pro Hooks (`@pdf-embed-seo/react-premium`)

| Hook | Returns | Description |
|------|---------|-------------|
| `useAnalytics` | `{ analytics, trackView, trackDownload }` | Analytics tracking |
| `usePasswordProtection` | `{ isProtected, isUnlocked, verify, error }` | Password verification |
| `useReadingProgress` | `{ progress, saveProgress, loading }` | Reading progress |

### Pro+ Hooks (`@pdf-embed-seo/react-pro-plus`)

| Hook | Description |
|------|-------------|
| `useHeatmap` | Page heatmap data |
| `useAdvancedAnalytics` | Advanced analytics and engagement |
| `useAnnotations` | Annotation CRUD operations |
| `useVersions` | Document version management |
| `useWebhooks` | Webhook configuration |
| `useAuditLog` | Audit log access |
| `useTwoFactorAuth` | 2FA verification |
| `useCompliance` | Compliance tracking |
| `useWhiteLabel` | White-label configuration |
| `useProPlusLicense` | License validation |

---

## Data Models

### WordPress Post Meta

| Meta Key | Type | Tier | Description |
|----------|------|------|-------------|
| `_pdf_file_id` | int | Free | Attachment ID |
| `_pdf_file_url` | string | Free | Direct PDF URL (internal) |
| `_pdf_allow_download` | bool | Free | Allow download |
| `_pdf_allow_print` | bool | Free | Allow print |
| `_pdf_standalone_mode` | bool | Free | Standalone fullscreen mode |
| `_pdf_view_count` | int | Free | View count |
| `_pdf_download_count` | int | Premium | Download count |
| `_pdf_password_protected` | bool | Premium | Password enabled |
| `_pdf_password` | string | Premium | Hashed password |
| `_pdf_ai_summary` | string | Premium | AI summary/TL;DR |
| `_pdf_key_points` | string | Premium | Key takeaways |
| `_pdf_reading_time` | int | Premium | Reading time in minutes |
| `_pdf_difficulty_level` | string | Premium | Difficulty level |
| `_pdf_document_type` | string | Premium | Document type |
| `_pdf_target_audience` | string | Premium | Target audience |
| `_pdf_faq_items` | array | Premium | FAQ Q&A pairs |
| `_pdf_toc_items` | array | Premium | Table of contents |
| `_pdf_custom_speakable` | string | Premium | Custom speakable content |
| `_pdf_related_documents` | array | Premium | Related PDF IDs |
| `_pdf_prerequisites` | string | Premium | Prerequisites |
| `_pdf_learning_outcomes` | string | Premium | Learning outcomes |

### WordPress Options

| Option | Description |
|--------|-------------|
| `pdf_embed_seo_settings` | Serialized plugin settings |
| `pdf_embed_seo_version` | Current version |
| `pdf_embed_seo_premium_license_status` | License status (Premium) |
| `pdf_embed_seo_premium_settings` | Premium settings (Premium) |

---

## URL Structure

| Page | WordPress | Drupal |
|------|-----------|--------|
| Archive | `/pdf/` | `/pdf` |
| Single PDF | `/pdf/{slug}/` | `/pdf/{slug}` |
| XML Sitemap (Premium) | `/pdf/sitemap.xml` | `/pdf/sitemap.xml` |
| Admin List | `/wp-admin/edit.php?post_type=pdf_document` | `/admin/content/pdf-documents` |
| Settings | `/wp-admin/edit.php?post_type=pdf_document&page=pdf-embed-seo-settings` | `/admin/config/content/pdf-embed-seo` |
| Analytics | `/wp-admin/edit.php?post_type=pdf_document&page=pdf-analytics` | `/admin/reports/pdf-analytics` |

---

## Feature Matrix

### Viewer & Display

| Feature | Free | Premium | Pro+ |
|---------|:----:|:-------:|:----:|
| Mozilla PDF.js Viewer (v4.0) | X | X | X |
| Light/Dark Theme | X | X | X |
| Responsive Design | X | X | X |
| Print/Download Control (per PDF) | X | X | X |
| Configurable Viewer Height | X | X | X |
| Gutenberg Block (WP) / Drupal Block / React Component | X | X | X |
| Shortcodes (WP) | X | X | X |
| Text Search in Viewer | - | X | X |
| Bookmark Navigation | - | X | X |
| Heatmaps | - | - | X |
| Annotations | - | - | X |

### Content Management

| Feature | Free | Premium | Pro+ |
|---------|:----:|:-------:|:----:|
| Custom Post Type / Entity | X | X | X |
| File Upload & Management | X | X | X |
| Featured Image / Auto-Thumbnails | X | X | X |
| Categories & Tags Taxonomy | - | X | X |
| Role-Based Access Control | - | X | X |
| Bulk Import (CSV/ZIP) | - | X | X |
| Document Versioning | - | - | X |

### SEO & URLs

| Feature | Free | Premium | Pro+ |
|---------|:----:|:-------:|:----:|
| Clean URL Structure (`/pdf/slug/`) | X | X | X |
| Schema.org DigitalDocument | X | X | X |
| Yoast SEO Integration (WP) | X | X | X |
| OpenGraph + Twitter Cards | X | X | X |
| GEO/AEO/LLM Basic (speakable, potentialAction) | X | X | X |
| XML Sitemap | - | X | X |
| AI Summary, FAQ Schema, TOC | - | X | X |

### Statistics & Analytics

| Feature | Free | Premium | Pro+ |
|---------|:----:|:-------:|:----:|
| Basic View Counter | X | X | X |
| Analytics Dashboard | - | X | X |
| Download Tracking | - | X | X |
| Analytics Export (CSV/JSON) | - | X | X |
| Advanced Heatmaps & Engagement | - | - | X |

### Security & Access

| Feature | Free | Premium | Pro+ |
|---------|:----:|:-------:|:----:|
| Nonce/CSRF Protection | X | X | X |
| Secure PDF URL (no direct links) | X | X | X |
| Password Protection | - | X | X |
| Expiring Access Links | - | X | X |
| Two-Factor Authentication | - | - | X |
| Audit Logs | - | - | X |

### Enterprise (Pro+ Only)

| Feature | Description |
|---------|-------------|
| Webhooks | Third-party integration with signed payloads |
| White Label | Custom branding, remove attribution |
| Compliance | GDPR/HIPAA tracking and data deletion |
| Versioning | Document revision history, rollback |
| Annotations | Highlight, comment, bookmark |

---

## Security Measures

### All Platforms

1. **PDF URL Protection** - Direct URLs hidden via AJAX/API endpoint
2. **Nonce/CSRF Verification** - All POST requests verified
3. **Capability/Permission Checks** - Admin functions require proper permissions
4. **Input Sanitization** - All inputs sanitized
5. **Output Escaping** - All outputs escaped
6. **Password Hashing** - bcrypt hashing (Premium)

### Drupal-Specific (v1.2.11)

7. **CSRF Token** on all POST API endpoints
8. **Rate Limiting** - 5 password attempts per 5 minutes, 15-minute block
9. **Session Cache Context** - Prevents cross-session cache leaks on password-protected PDFs
10. **Computed View Count** - No entity saves on page views (performance + security)
11. **IP Anonymization** - GDPR-compliant (enabled by default)

### License Manager

12. **HMAC-SHA256** Stripe webhook signature verification
13. **Cryptographically secure** key generation via `random_bytes()`
14. **Rate limiting** per IP and per license key
15. **Anonymized IP** in audit logs
16. **Masked key display** in admin UI

---

## Dependencies

### WordPress Plugin
- WordPress 5.8+, PHP 7.4+
- Mozilla PDF.js (bundled)
- Optional: Yoast SEO, ImageMagick/Ghostscript (thumbnails)

### Drupal Module
- Drupal 10 or 11, PHP 8.1+
- Core modules: node, file, taxonomy, path, path_alias, media
- Optional: ImageMagick/Ghostscript (thumbnails)

### React/Next.js
- Node.js 18+, React 18+/19+
- Optional: Next.js 13+/14+/15+
- pdfjs-dist ^4.0.0
- TypeScript 5+ (recommended)
- Build: pnpm 8.15+, Turborepo 2.0+, tsup 8.0+
- Test: Vitest 2.0+, @testing-library/react 14+

### WordPress Theme
- WordPress 6.0+, PHP 8.0+
- Tailwind CSS (compiled, no runtime dependency)
- Optional: Yoast SEO

### License Manager
- WordPress (separate installation on pdfviewer.drossmedia.de)
- MaxMind GeoLite2-City.mmdb (GeoIP)
- Stripe webhook (no SDK, native PHP)

---

## Testing & QA

### Test Files

| Location | Framework | Coverage |
|----------|-----------|----------|
| `pdf-embed-seo-optimize/tests/` | PHPUnit | WordPress plugin (11+ test files) |
| `theme/wp-theme/pdfviewer-theme/tests/` | PHPUnit | Theme functions (4 test files) |
| `react-pdf-embed-seo/tests/unit/` | Vitest | React components/hooks (4 test files) |
| `react-pdf-embed-seo/packages/react-pro-plus/tests/` | Vitest | Pro+ features (5 test files) |
| `tests/qa/` | Manual | QA/UAT test plans and reports |

### QA Documentation

| File | Description |
|------|-------------|
| `tests/qa/UAT-TEST-PLAN-COMPLETE.md` | Complete user acceptance testing scenarios |
| `tests/qa/QA-TEST-PLAN-COMPLETE.md` | Comprehensive QA test plan |
| `tests/qa/QA-TEST-PLAN.md` | Full QA testing procedures |
| `tests/qa/UAT-TEST-PLAN.md` | UAT scenarios and test cases |
| `tests/qa/TEST-REPORT-v1.3.0.md` | v1.3.0 test results (200+ test cases) |
| `tests/qa/TEST-REPORT-v1.2.10.md` | v1.2.10 test results |
| `tests/qa/TEST-REPORT-v1.2.8.md` | v1.2.8 test results |

### Running Tests

```bash
# WordPress plugin (requires WordPress test suite)
cd pdf-embed-seo-optimize && phpunit

# WordPress theme
cd theme/wp-theme/pdfviewer-theme && phpunit

# React packages
cd react-pdf-embed-seo && pnpm test        # Run all tests
cd react-pdf-embed-seo && pnpm typecheck   # TypeScript check
cd react-pdf-embed-seo && pnpm lint        # ESLint
cd react-pdf-embed-seo && pnpm build       # Build all packages
```

---

## Distribution & Releases

### Distribution Packages (`dist/`)

| Package | Version | Contents |
|---------|---------|----------|
| `pdf-embed-seo-complete-v1.3.0.zip` | 1.3.0 | Complete (WP+Drupal+React) Pro+ |
| `pdf-embed-seo-pro-plus-v1.3.0.zip` | 1.3.0 | Pro+ Enterprise only |
| `pdf-embed-seo-complete-v1.2.11.zip` | 1.2.11 | Complete (WP+Drupal+React) |
| `pdf-embed-seo-all-modules-v1.2.11.zip` | 1.2.11 | WordPress Free+Premium |
| `drupal-pdf-embed-seo-v1.2.11.zip` | 1.2.11 | Drupal module only |

### Premium Purchase

**https://pdfviewer.drossmedia.de**

---

## Changelog Summary

### Current Versions
- **Free/Premium:** v1.2.11 (2026-02-10)
- **Pro+ Enterprise:** v1.3.0 (2026-02-11)
- **License Manager:** v1.0.1

### Key Version History

| Version | Highlights |
|---------|-----------|
| **1.3.0** | Pro+ Enterprise: advanced analytics, annotations, versioning, webhooks, 2FA, compliance |
| **1.2.11** | Drupal: CSRF protection, rate limiting, session cache, computed view count, Media Library, token storage migration |
| **1.2.10** | iOS print support, comprehensive print CSS across all platforms |
| **1.2.9** | Drupal: performance fixes (no entity saves on views, cache optimization), IP anonymization |
| **1.2.8** | Sitemap URL change (`/pdf/sitemap.xml`), archive styling settings, Drupal parity |
| **1.2.7** | Sidebar removal (full-width), REST API caching fix, archive heading/colors |
| **1.2.6** | WordPress Plugin Check compliance, hook rename, Drupal security fixes |
| **1.2.5** | Download tracking, expiring access links, Drupal premium feature parity |
| **1.2.4** | AI & schema optimization meta box (GEO/AEO/LLM) |
| **1.2.3** | GEO/AEO/LLM schema, standalone social meta tags |
| **1.2.2** | Archive display options (grid/list), breadcrumb schema |
| **1.2.0** | REST API, reading progress, password endpoint, XML sitemap, Drupal free/premium split |
| **1.0.0** | Initial release: WordPress plugin with PDF.js, Yoast, print/download controls |

---

## Credits

Made with love by [Dross:Media](https://dross.net/media/)

**License:** GPL v2 or later - https://www.gnu.org/licenses/gpl-2.0.html
