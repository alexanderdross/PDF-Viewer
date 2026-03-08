import { useState, useEffect, forwardRef } from "react";
import { Layout } from "@/components/layout/Layout";
import { SEOHead } from "@/components/SEOHead";
import { useLocation, useNavigate } from "react-router-dom";
import { Tabs, TabsList, TabsTrigger } from "@/components/ui/tabs";
import { WordPressIcon, DrupalIcon, ReactIcon } from "@/components/icons/PlatformIcons";

type Platform = "wordpress" | "drupal" | "react";

interface ChangelogEntry {
  version: string;
  date: string;
  type: "free" | "pro" | "proPlus" | "both";
  platforms: Platform[];
  title: string;
  changes: {
    type: "added" | "improved" | "fixed" | "changed";
    description: string;
  }[];
}

const changelog: ChangelogEntry[] = [
  {
    version: "1.2.12",
    date: "February 17, 2026",
    type: "both",
    title: "Drupal REST API Routes & Template Fixes",
    changes: [
      { type: "added", description: "REST API document endpoints — GET /documents, GET /documents/{id}, and GET /documents/{id}/data as standalone controller routes (no rest module dependency)" },
      { type: "added", description: "Pagination, sorting, and filtering support for the documents list endpoint" },
      { type: "added", description: "Admin content tab — PDF Documents tab now visible on the Admin > Content page" },
      { type: "fixed", description: "Twig FieldItemList crash — Fixed 'Object of type FieldItemList cannot be printed' error on single PDF view" },
      { type: "fixed", description: "PDF viewer 403 error — Removed unnecessary CSRF token requirement from PDF data GET route" },
      { type: "fixed", description: "Broken HTML in archive cards and breadcrumbs — Fixed placeholder format in Twig t() calls producing escaped HTML in attributes" },
      { type: "fixed", description: "REST API /documents 500 error — Slug now derived from path alias instead of non-existent base field" },
    ],
  },
  {
    version: "1.2.11",
    date: "February 10, 2026",
    type: "both",
    title: "Drupal Media Library & Security Hardening",
    changes: [
      { type: "added", description: "Media Library Integration — PDFs managed via Drupal's Media Library with PdfDocument MediaSource plugin" },
      { type: "added", description: "PdfViewerFormatter field formatter for displaying PDFs in Media entities" },
      { type: "added", description: "Rate Limiter service (pdf_embed_seo.rate_limiter) for brute force protection" },
      { type: "added", description: "Access Token Storage service with database backend and automatic cleanup" },
      { type: "fixed", description: "Security: CSRF Protection — Added _csrf_token to all POST API endpoints" },
      { type: "fixed", description: "Security: Rate Limiting — Brute force protection (5 attempts/5 min, 15 min block)" },
      { type: "fixed", description: "Security: Session Cache Context — Prevents cross-session cache leaks on password-protected routes" },
      { type: "fixed", description: "Performance: Computed View Count — Converted to computed field (no more entity saves)" },
      { type: "fixed", description: "Scalability: Token Storage Migration — Replaced State API with dedicated database table" },
      { type: "changed", description: "Database update hook creates new tables and migrates State API tokens" },
    ],
  },
  {
    version: "1.2.10",
    date: "February 5, 2026",
    type: "both",
    title: "iOS Print Support & Print CSS",
    changes: [
      { type: "added", description: "iOS Print Support — Enhanced print for Safari/iOS across all platforms" },
      { type: "added", description: "Comprehensive Print CSS — Professional print output for WordPress, Drupal, and React" },
      { type: "added", description: "@page rules for A4 portrait sizing and margins" },
      { type: "added", description: "print-color-adjust and page-break-inside for canvas elements" },
      { type: "added", description: "500ms Safari/iOS delay with fallback to canvas print if popup blocked" },
    ],
  },
  {
    version: "1.2.9",
    date: "February 5, 2026",
    type: "both",
    title: "Drupal Performance & GDPR Compliance",
    changes: [
      { type: "fixed", description: "Performance: Removed entity saves during page views — views tracked directly in analytics table" },
      { type: "fixed", description: "Performance: Added cache tag invalidation for pdf_document_list on insert/update/delete" },
      { type: "fixed", description: "Performance: Added cache metadata to PdfViewerBlock with tags, contexts, and max-age" },
      { type: "fixed", description: "Security: Fixed Pathauto service dependency — graceful fallback URL-safe string generator" },
      { type: "added", description: "GDPR IP Anonymization — Zeroes last octet (IPv4) or last 80 bits (IPv6), enabled by default" },
      { type: "added", description: "Cache Tag Invalidation Hooks for proper cache management" },
    ],
  },
  {
    version: "1.2.8",
    date: "February 4, 2026",
    type: "both",
    title: "Plugin Check Compliance & Archive Improvements",
    changes: [
      { type: "fixed", description: "WordPress Plugin Check Compliance — Added direct file access protection to all test files" },
      { type: "changed", description: "WordPress Premium Sitemap URL changed from /pdf-sitemap.xml to /pdf/sitemap.xml" },
      { type: "changed", description: "Archive Page styling improvements (Content Alignment renamed from Heading Alignment)" },
    ],
  },
  {
    version: "1.2.7",
    date: "February 2, 2026",
    type: "both",
    title: "Full-Width PDF Pages (Sidebar Removal)",
    changes: [
      { type: "fixed", description: "WordPress: Removed get_sidebar() calls from PDF archive and single templates" },
      { type: "fixed", description: "WordPress: Added CSS rules to hide sidebars on PDF archive pages" },
      { type: "fixed", description: "Drupal: Added theme suggestions and preprocess hooks for full-width PDF pages" },
      { type: "added", description: "Unit Tests for Sidebar Removal across WordPress and Drupal" },
    ],
  },
  {
    version: "1.2.6",
    date: "February 1, 2026",
    type: "both",
    title: "Plugin Check Compliance & Security Fixes",
    changes: [
      { type: "fixed", description: "WordPress: Fixed unescaped SQL in premium REST API and analytics" },
      { type: "fixed", description: "WordPress: Updated get_posts() to use post__not_in instead of deprecated exclude" },
      { type: "fixed", description: "Hook Naming: pdf_embed_seo_settings_saved → pdf_embed_seo_optimize_settings_saved" },
      { type: "fixed", description: "Drupal: Implemented proper password hashing using Drupal's password service" },
      { type: "fixed", description: "Drupal: Fixed XSS in PdfViewerBlock — document titles now escaped with Html::escape()" },
    ],
  },
  {
    version: "1.2.5",
    date: "January 28, 2026",
    type: "both",
    platforms: ["wordpress", "drupal"],
    title: "Download Tracking & Expiring Access Links",
    changes: [
      { type: "added", description: "Download Tracking (Pro) - Track PDF downloads separately from views" },
      { type: "added", description: "Separate download counter per document (_pdf_download_count)" },
      { type: "added", description: "Download analytics in dashboard" },
      { type: "added", description: "REST API endpoint: POST /documents/{id}/download" },
      { type: "added", description: "User attribution for authenticated downloads" },
      { type: "added", description: "Expiring Access Links (Pro+) - Generate time-limited URLs for PDFs" },
      { type: "added", description: "Configurable expiration time (5 min to 30 days)" },
      { type: "added", description: "Maximum usage limits per link" },
      { type: "added", description: "Secure token-based access" },
      { type: "added", description: "REST endpoints: POST /documents/{id}/expiring-link, GET /documents/{id}/expiring-link/{token}" },
      { type: "added", description: "Admin-only link generation" },
      { type: "added", description: "Drupal Premium Feature Parity - Complete WordPress/Drupal consistency" },
      { type: "added", description: "PdfSchemaEnhancer service for GEO/AEO/LLM optimization" },
      { type: "added", description: "PdfAccessManager service for role-based access control" },
      { type: "added", description: "PdfBulkOperations service for CSV import and bulk updates" },
      { type: "added", description: "PdfViewerEnhancer service for text search, bookmarks, reading progress UI" },
      { type: "added", description: "Extended REST API with 14+ endpoints matching WordPress" },
    ],
  },
  {
    version: "1.2.4",
    date: "January 28, 2025",
    type: "proPlus",
    platforms: ["wordpress", "drupal"],
    title: "Pro+ AI & Schema Optimization Meta Box",
    changes: [
      { type: "added", description: "AI Summary (TL;DR) meta box field with abstract schema support (Pro+)" },
      { type: "added", description: "Key Points field with Schema.org ItemList markup (Pro+)" },
      { type: "added", description: "FAQ Schema with question/answer pairs for rich snippets (Pro+)" },
      { type: "added", description: "Table of Contents with hasPart schema markup (Pro+)" },
      { type: "added", description: "Custom Speakable Content for voice search optimization (Pro+)" },
      { type: "added", description: "Premium Settings Preview for free users" },
      { type: "added", description: "Learning Outcomes field with teaches schema property (Pro+)" },
      { type: "added", description: "Reading Time estimate with timeRequired schema (Pro+)" },
      { type: "changed", description: "Enhanced meta box UI with collapsible sections" },
      { type: "changed", description: "Improved premium feature discovery for free users" },
    ],
  },
  {
    version: "1.2.3",
    date: "January 28, 2025",
    type: "both",
    platforms: ["wordpress", "drupal"],
    title: "GEO/AEO/LLM Schema Optimization",
    changes: [
      { type: "added", description: "GEO/AEO/LLM Schema for AI and voice assistant optimization" },
      { type: "added", description: "potentialAction schema (ReadAction, DownloadAction, ViewAction)" },
      { type: "added", description: "accessMode and accessibilityFeature schema properties" },
      { type: "added", description: "Standalone OpenGraph meta tags (works without Yoast SEO)" },
      { type: "added", description: "Standalone Twitter Card meta tags" },
      { type: "added", description: "Auto-generated og:image from PDF thumbnail" },
      { type: "added", description: "pdf_embed_seo_archive_schema_data filter hook" },
      { type: "added", description: "pdf_embed_seo_archive_query filter hook" },
      { type: "added", description: "pdf_embed_seo_rest_settings filter hook" },
      { type: "changed", description: "Schema.org markup optimized for AI/LLM consumption" },
      { type: "changed", description: "Improved social sharing preview for non-Yoast sites" },
    ],
  },
  {
    version: "1.2.2",
    date: "January 28, 2025",
    type: "both",
    platforms: ["wordpress", "drupal"],
    title: "Archive Display & Breadcrumb Schema",
    changes: [
      { type: "added", description: "Archive Display Options with configurable list/grid views for PDF archives" },
      { type: "added", description: "List view with condensed layout" },
      { type: "added", description: "Grid/card view with thumbnails" },
      { type: "added", description: "Toggle description and view count visibility" },
      { type: "added", description: "WordPress and Drupal support for archive views" },
      { type: "added", description: "Breadcrumb Schema with Schema.org BreadcrumbList JSON-LD markup" },
      { type: "added", description: "3-level breadcrumbs (Home > PDF Documents > Document Title)" },
      { type: "added", description: "Visible breadcrumb navigation with ARIA labels and proper focus states" },
      { type: "added", description: "Archive Page Redirect (Premium) - Redirect /pdf archive to custom URL" },
      { type: "added", description: "301 (permanent) or 302 (temporary) redirect options with license validation" },
      { type: "changed", description: "Improved archive templates with accessibility attributes" },
      { type: "changed", description: "Enhanced CSS with high contrast and reduced motion support" },
      { type: "changed", description: "Better mobile responsiveness for archive pages" },
    ],
  },
  {
    version: "1.2.1",
    date: "January 27, 2025",
    type: "both",
    platforms: ["wordpress", "drupal"],
    title: "Unit Tests & Plugin Branding",
    changes: [
      { type: "added", description: "Comprehensive unit tests for WordPress Free module (REST API, Schema, Post Type, Shortcodes)" },
      { type: "added", description: "Unit tests for WordPress Premium module (Password, Analytics, Progress, REST API)" },
      { type: "added", description: "Unit tests for Drupal Free module (API Controller, Entity, Storage)" },
      { type: "added", description: '"Get Premium" action link on free plugin page' },
      { type: "added", description: '"Visit Site", "Documentation", "Support" links on premium plugin page' },
      { type: "added", description: 'Plugin name differentiation: "(Free Version)" and "(Premium)"' },
      { type: "changed", description: "Plugin URI now points to https://pdfviewermodule.com" },
      { type: "changed", description: "Author URI updated to https://dross.net/media/" },
      { type: "improved", description: "Plugin action links with contextual options" },
      { type: "fixed", description: "Consistent version numbering across all modules" },
    ],
  },
  {
    version: "1.2.0",
    date: "January 2025",
    type: "both",
    platforms: ["wordpress", "drupal"],
    title: "Premium Features Release — Pro & Pro+ Tiers",
    changes: [
      { type: "added", description: "Three Pro tiers: Pro 1 Site (€49/yr), Pro 5 Sites (€99/yr), Pro Unlimited (€199/yr)" },
      { type: "added", description: "Pro Lifetime plan (€399 one-time) with all Pro features on unlimited sites" },
      { type: "added", description: "Complete REST API for WordPress and Drupal (Free + Premium endpoints)" },
      { type: "added", description: "Pro: Analytics Dashboard with view tracking, popular documents, and time filters" },
      { type: "added", description: "Pro: Password Protection with secure hashing and session-based access" },
      { type: "added", description: "Pro: Text Search in Viewer and Bookmark Navigation" },
      { type: "added", description: "Pro: Detailed View Tracking, Download Tracking, Brute-Force Protection" },
      { type: "added", description: "Pro: Reading Progress tracking with auto-save and resume functionality" },
      { type: "added", description: "Pro: XML Sitemap at /pdf/sitemap.xml with XSL stylesheet" },
      { type: "added", description: "Pro: Categories and Tags taxonomies for PDF organization" },
      { type: "added", description: "Pro: AI/GEO/AEO Schema Optimization fields" },
      { type: "added", description: "Pro: CSV/JSON Analytics Export" },
      { type: "added", description: "Pro: Expiring Access Links with time-limited URLs" },
      { type: "added", description: "Pro: Role-Based Access Control" },
      { type: "added", description: "Pro: Bulk Import via CSV/ZIP" },
      { type: "added", description: "Archive Display Options (List/Grid views, description and view count toggles)" },
      { type: "added", description: "Breadcrumb Schema (BreadcrumbList JSON-LD, visible navigation)" },
      { type: "added", description: "Archive Page Redirect for Pro users" },
      { type: "improved", description: "Drupal module structure with proper Entity API integration" },
      { type: "improved", description: "REST API authentication with nonce/CSRF protection" },
      { type: "improved", description: "Archive templates with accessibility attributes and mobile responsiveness" },
    ],
  },
  {
    version: "1.1.0",
    date: "January 2025",
    type: "free",
    platforms: ["drupal"],
    title: "Drupal Support & Platform Parity",
    changes: [
      { type: "added", description: "Drupal 10/11 module with PDF Document entity type" },
      { type: "added", description: "Drupal PDF Viewer block with configuration options" },
      { type: "added", description: "REST API endpoints for Drupal (/api/pdf-embed-seo/v1/)" },
      { type: "added", description: "Drush commands for PDF management" },
      { type: "added", description: "Twig templates for theming (pdf-document.html.twig, pdf-viewer.html.twig)" },
      { type: "improved", description: "Unified feature parity between WordPress and Drupal" },
      { type: "improved", description: "Schema.org output with CollectionPage for archives" },
    ],
  },
  {
    version: "1.0.0",
    date: "January 2025",
    type: "free",
    platforms: ["wordpress"],
    title: "Initial Release",
    changes: [
      { type: "added", description: "Custom post type for PDF documents with clean URL structure (/pdf/document-name/)" },
      { type: "added", description: "Mozilla PDF.js viewer integration (bundled locally - no external CDN)" },
      { type: "added", description: "Gutenberg block for embedding PDFs in the block editor" },
      { type: "added", description: "Auto-generate thumbnails from PDF first pages (requires ImageMagick or Ghostscript)" },
      { type: "added", description: "Yoast SEO compatibility with full control over meta tags and OpenGraph" },
      { type: "added", description: "Print/download permission controls on a per-PDF basis" },
      { type: "added", description: "View statistics tracking for each PDF document" },
      { type: "added", description: "Shortcode support ([pdf_viewer] and [pdf_viewer_sitemap])" },
      { type: "added", description: "Schema markup (DigitalDocument and CollectionPage) for rich search results" },
      { type: "added", description: "PDF archive page at /pdf/ listing all documents" },
      { type: "added", description: "Responsive design for desktop, tablet, and mobile devices" },
    ],
  },
];

const ChangeTypeBadge = forwardRef<HTMLSpanElement, { type: "added" | "improved" | "fixed" | "changed" }>(
  ({ type }, ref) => {
    const styles = {
      added: "bg-green-500/10 text-green-600 border-green-500/20",
      improved: "bg-blue-500/10 text-blue-600 border-blue-500/20",
      fixed: "bg-amber-500/10 text-amber-600 border-amber-500/20",
      changed: "bg-purple-500/10 text-purple-600 border-purple-500/20",
    };

    return (
      <span ref={ref} className={`text-xs font-medium px-2 py-0.5 rounded border ${styles[type]}`}>
        {type.charAt(0).toUpperCase() + type.slice(1)}
      </span>
    );
  }
);
ChangeTypeBadge.displayName = "ChangeTypeBadge";

const VersionBadge = forwardRef<HTMLDivElement, { type: "free" | "pro" | "proPlus" | "both" }>(
  ({ type }, ref) => {
    if (type === "both") {
      return (
        <div ref={ref} className="flex gap-2">
          <span className="text-xs font-medium px-2 py-1 rounded bg-muted text-muted-foreground">Free</span>
          <span className="text-xs font-medium px-2 py-1 rounded bg-primary/10 text-primary">Pro</span>
        </div>
      );
    }
    if (type === "proPlus") {
      return <span className="text-xs font-medium px-2 py-1 rounded bg-accent/10 text-accent">Pro+</span>;
    }
    if (type === "pro") {
      return <span className="text-xs font-medium px-2 py-1 rounded bg-primary/10 text-primary">Pro Only</span>;
    }
    return <span className="text-xs font-medium px-2 py-1 rounded bg-muted text-muted-foreground">Free</span>;
  }
);
VersionBadge.displayName = "VersionBadge";

const PlatformBadges = ({ platforms }: { platforms: Platform[] }) => {
  const icons: Record<Platform, { icon: React.ReactNode; label: string }> = {
    wordpress: { icon: <WordPressIcon size={12} aria-hidden="true" />, label: "WordPress" },
    drupal: { icon: <DrupalIcon size={12} aria-hidden="true" />, label: "Drupal" },
    react: { icon: <ReactIcon size={12} aria-hidden="true" />, label: "React" },
  };

  return (
    <div className="flex gap-1.5">
      {platforms.map((p) => (
        <span
          key={p}
          className="inline-flex items-center gap-1 text-xs px-2 py-0.5 rounded bg-muted text-muted-foreground"
          title={icons[p].label}
        >
          {icons[p].icon}
          {icons[p].label}
        </span>
      ))}
    </div>
  );
};

const Changelog = () => {
  const location = useLocation();
  const navigate = useNavigate();

  const getInitialPlatform = (): Platform | "all" => {
    const hash = location.hash.replace("#", "");
    if (hash === "wordpress" || hash === "drupal" || hash === "react") return hash;
    return "all";
  };

  const [platform, setPlatform] = useState<Platform | "all">(getInitialPlatform);

  useEffect(() => {
    const hash = location.hash.replace("#", "");
    if (hash === "wordpress" || hash === "drupal" || hash === "react" || hash === "all") {
      setPlatform(hash);
    }
  }, [location.hash]);

  const handlePlatformChange = (value: string) => {
    const newPlatform = value as Platform | "all";
    setPlatform(newPlatform);
    navigate(newPlatform === "all" ? "#" : `#${newPlatform}`, { replace: true });
  };

  const filteredChangelog = platform === "all"
    ? changelog
    : changelog.filter((entry) => entry.platforms.includes(platform));

  return (
    <Layout>
      <SEOHead
        title="Changelog – PDF Embed & SEO Optimize Version History"
        description="See what's new in PDF Embed & SEO Optimize. Complete version history with new features, improvements, and bug fixes for both Free and Pro versions."
        canonicalPath="/changelog/"
      />

      <section className="pt-32 pb-20 lg:pt-40 lg:pb-32">
        <div className="container mx-auto px-4 lg:px-8">
          <header className="max-w-3xl mx-auto text-center mb-10">
            <h1 className="text-4xl md:text-5xl font-bold text-foreground mb-6">
              Changelog
            </h1>
            <p className="text-lg text-muted-foreground">
              Complete version history for PDF Embed & SEO Optimize. See all new features, improvements, and bug fixes.
            </p>
          </header>

          {/* Platform Tabs */}
          <div className="max-w-3xl mx-auto mb-12">
            <Tabs value={platform} onValueChange={handlePlatformChange} className="w-full" aria-label="Filter changelog by platform">
              <TabsList className="grid w-full max-w-xl mx-auto grid-cols-4">
                <TabsTrigger value="all" title="Show all changelog entries">
                  All
                </TabsTrigger>
                <TabsTrigger value="wordpress" className="gap-1.5" title="Show WordPress changelog entries">
                  <WordPressIcon size={16} aria-hidden="true" />
                  WordPress
                </TabsTrigger>
                <TabsTrigger value="drupal" className="gap-1.5" title="Show Drupal changelog entries">
                  <DrupalIcon size={16} aria-hidden="true" />
                  Drupal
                </TabsTrigger>
                <TabsTrigger value="react" className="gap-1.5" title="Show React/Next.js changelog entries">
                  <ReactIcon size={16} aria-hidden="true" />
                  React
                </TabsTrigger>
              </TabsList>
            </Tabs>
          </div>

          <div className="max-w-3xl mx-auto">
            {filteredChangelog.length === 0 ? (
              <div className="text-center py-16">
                <p className="text-muted-foreground text-lg">No changelog entries for this platform yet.</p>
                <p className="text-muted-foreground text-sm mt-2">Check back soon or view all platforms.</p>
              </div>
            ) : (
              <div className="relative">
                {/* Timeline line */}
                <div className="absolute left-0 md:left-8 top-0 bottom-0 w-px bg-border" aria-hidden="true" />

                {/* Entries */}
                <div className="space-y-12">
                  {filteredChangelog.map((entry, index) => (
                    <article
                      key={entry.version}
                      className="relative pl-8 md:pl-20 animate-fade-in"
                      style={{ animationDelay: `${index * 0.05}s` }}
                    >
                      {/* Timeline dot */}
                      <div className="absolute left-0 md:left-8 -translate-x-1/2 w-4 h-4 rounded-full bg-primary border-4 border-background" aria-hidden="true" />

                      {/* Version header */}
                      <div className="flex flex-wrap items-center gap-3 mb-3">
                        <h2 className="text-2xl font-bold text-foreground">v{entry.version}</h2>
                        <VersionBadge type={entry.type} />
                        <span className="text-sm text-muted-foreground">{entry.date}</span>
                      </div>

                      {/* Platform badges */}
                      <div className="mb-4">
                        <PlatformBadges platforms={entry.platforms} />
                      </div>

                      <h3 className="text-lg font-semibold text-foreground mb-4">{entry.title}</h3>

                      {/* Changes */}
                      <ul className="space-y-3">
                        {entry.changes.map((change, changeIndex) => (
                          <li key={changeIndex} className="flex items-start gap-3">
                            <ChangeTypeBadge type={change.type} />
                            <span className="text-muted-foreground">{change.description}</span>
                          </li>
                        ))}
                      </ul>
                    </article>
                  ))}
                </div>
              </div>
            )}
          </div>
        </div>
      </section>
    </Layout>
  );
};

export default Changelog;
