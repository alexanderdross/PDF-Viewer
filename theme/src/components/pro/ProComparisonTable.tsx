import { Fragment } from "react";
import { Check, X } from "lucide-react";

type FeatureValue = boolean | string;

interface Feature {
  name: string;
  free: FeatureValue;
  pro: FeatureValue;
  proPlus: FeatureValue;
  enterprise: FeatureValue;
}

interface FeatureCategory {
  category: string;
  features: Feature[];
}

// Helper: feature available from a given tier upward
function fromTier(tier: "free" | "pro" | "proPlus" | "enterprise"): Pick<Feature, "free" | "pro" | "proPlus" | "enterprise"> {
  const levels = { free: 0, pro: 1, proPlus: 2, enterprise: 3 };
  const level = levels[tier];
  return {
    free: level <= 0,
    pro: level <= 1,
    proPlus: level <= 2,
    enterprise: level <= 3,
  };
}

const comparisonData: FeatureCategory[] = [
  {
    category: "Viewer & Display",
    features: [
      { name: "Mozilla PDF.js Viewer (v4.0)", ...fromTier("free") },
      { name: "Light & Dark Themes", ...fromTier("free") },
      { name: "Responsive Design", ...fromTier("free") },
      { name: "Print Control (per PDF)", ...fromTier("free") },
      { name: "Download Control (per PDF)", ...fromTier("free") },
      { name: "Configurable Viewer Height", ...fromTier("free") },
      { name: "Gutenberg Block (WP)", ...fromTier("free") },
      { name: "PDF Viewer Block (Drupal)", ...fromTier("free") },
      { name: "Shortcodes (WP)", ...fromTier("free") },
      { name: "Text Search in Viewer", ...fromTier("pro") },
      { name: "Bookmark Navigation", ...fromTier("pro") },
      { name: "Reading Progress (Resume)", ...fromTier("pro") },
      { name: "Mobile Swipe Gestures", ...fromTier("pro") },
      { name: "Presentation Mode", ...fromTier("pro") },
      { name: "Annotations & Highlighting", ...fromTier("proPlus") },
    ],
  },
  {
    category: "Content Management",
    features: [
      { name: "PDF Document Post Type / Entity", ...fromTier("free") },
      { name: "Title, Description, Slug", ...fromTier("free") },
      { name: "File Upload & Management", ...fromTier("free") },
      { name: "Featured Image / Thumbnail", ...fromTier("free") },
      { name: "Auto-Generate Thumbnails", ...fromTier("free") },
      { name: "Published/Draft Status", ...fromTier("free") },
      { name: "Owner/Author Tracking", ...fromTier("free") },
      { name: "Admin List with Sortable Columns", ...fromTier("free") },
      { name: "Quick Edit Support (WP)", ...fromTier("free") },
      { name: "Multi-language Support", ...fromTier("free") },
      { name: "Categories Taxonomy", ...fromTier("pro") },
      { name: "Tags Taxonomy", ...fromTier("pro") },
      { name: "Bulk Edit Actions", ...fromTier("proPlus") },
      { name: "Bulk Import (CSV/ZIP)", ...fromTier("proPlus") },
      { name: "Duplicate PDF Document", ...fromTier("proPlus") },
      { name: "Import/Export Settings", ...fromTier("proPlus") },
    ],
  },
  {
    category: "SEO & URLs",
    features: [
      { name: "Clean URL Structure (/pdf/slug/)", ...fromTier("free") },
      { name: "Auto Path/Slug Generation", ...fromTier("free") },
      { name: "Schema.org DigitalDocument", ...fromTier("free") },
      { name: "Schema.org CollectionPage (Archive)", ...fromTier("free") },
      { name: "Schema.org BreadcrumbList", ...fromTier("free") },
      { name: "Yoast SEO Integration (WP)", ...fromTier("free") },
      { name: "OpenGraph Meta Tags", ...fromTier("free") },
      { name: "Twitter Card Support", ...fromTier("free") },
      { name: "GEO/AEO/LLM Schema Optimization", ...fromTier("free") },
      { name: "SpeakableSpecification (Voice Assistants)", ...fromTier("free") },
      { name: "potentialAction Schema (ReadAction, etc.)", ...fromTier("free") },
      { name: "Standalone Social Meta (without Yoast)", ...fromTier("free") },
      { name: "XML Sitemap (/pdf/sitemap.xml)", ...fromTier("pro") },
      { name: "Sitemap XSL Stylesheet", ...fromTier("pro") },
      { name: "Search Engine Ping", ...fromTier("pro") },
    ],
  },
  {
    category: "AI & Voice Search Optimization",
    features: [
      { name: "Basic Speakable Schema (WebPage)", ...fromTier("free") },
      { name: "potentialAction (ReadAction, DownloadAction)", ...fromTier("free") },
      { name: "accessMode & accessibilityFeature", ...fromTier("free") },
      { name: "AI Summary / TL;DR Field", ...fromTier("pro") },
      { name: "Key Points & Takeaways", ...fromTier("pro") },
      { name: "FAQ Schema (FAQPage for Rich Results)", ...fromTier("pro") },
      { name: "Table of Contents Schema (hasPart)", ...fromTier("pro") },
      { name: "Reading Time Estimate (timeRequired)", ...fromTier("proPlus") },
      { name: "Difficulty Level (educationalLevel)", ...fromTier("proPlus") },
      { name: "Document Type Classification", ...fromTier("proPlus") },
      { name: "Target Audience Schema", ...fromTier("proPlus") },
      { name: "Custom Speakable Content", ...fromTier("proPlus") },
      { name: "Related Documents (isRelatedTo)", ...fromTier("proPlus") },
      { name: "Prerequisites (coursePrerequisites)", ...fromTier("proPlus") },
      { name: "Learning Outcomes (teaches)", ...fromTier("proPlus") },
    ],
  },
  {
    category: "Archive & Listing",
    features: [
      { name: "Archive Page (/pdf/)", ...fromTier("free") },
      { name: "Pagination Support", ...fromTier("free") },
      { name: "Grid/List Display Modes", ...fromTier("free") },
      { name: "Sorting Options", ...fromTier("free") },
      { name: "Search Filtering", ...fromTier("free") },
      { name: "Visible Breadcrumb Navigation", ...fromTier("free") },
      { name: "Category Filter", ...fromTier("pro") },
      { name: "Tag Filter", ...fromTier("pro") },
      { name: "Archive Page Redirect", ...fromTier("proPlus") },
    ],
  },
  {
    category: "Statistics & Analytics",
    features: [
      { name: "Basic View Counter", ...fromTier("free") },
      { name: "View Count Display", ...fromTier("free") },
      { name: "Analytics Dashboard", ...fromTier("pro") },
      { name: "Detailed View Tracking", ...fromTier("pro") },
      { name: "Download Tracking", ...fromTier("pro") },
      { name: "IP Address Logging", ...fromTier("pro") },
      { name: "User Agent Tracking", ...fromTier("pro") },
      { name: "Referrer Tracking", ...fromTier("pro") },
      { name: "Time Spent Tracking", ...fromTier("pro") },
      { name: "Popular Documents Report", ...fromTier("pro") },
      { name: "Recent Views Log", ...fromTier("pro") },
      { name: "Analytics Export (CSV/JSON)", ...fromTier("pro") },
      { name: "Time Period Filters", ...fromTier("pro") },
      { name: "Time-based Charts", ...fromTier("pro") },
    ],
  },
  {
    category: "Security & Access",
    features: [
      { name: "Nonce/CSRF Protection", ...fromTier("free") },
      { name: "Permission System", ...fromTier("free") },
      { name: "Capability/Access Checks", ...fromTier("free") },
      { name: "Secure PDF URL (no direct links)", ...fromTier("free") },
      { name: "Input Sanitization", ...fromTier("free") },
      { name: "Output Escaping", ...fromTier("free") },
      { name: "Password Protection", ...fromTier("pro") },
      { name: "Secure Password Hashing (bcrypt)", ...fromTier("pro") },
      { name: "Session-Based Access", ...fromTier("pro") },
      { name: "Configurable Session Duration", ...fromTier("pro") },
      { name: "Brute-Force Protection", ...fromTier("pro") },
      { name: "Login Requirement Option", ...fromTier("proPlus") },
      { name: "Role Restrictions", ...fromTier("proPlus") },
      { name: "Expiring Access Links", ...fromTier("proPlus") },
      { name: "Time-Limited URLs", ...fromTier("proPlus") },
      { name: "Max Uses per Link", ...fromTier("proPlus") },
      { name: "Dynamic Watermarks", ...fromTier("proPlus") },
      { name: "IP Anonymization (GDPR)", ...fromTier("proPlus") },
      { name: "Custom Branding / White Label", ...fromTier("proPlus") },
    ],
  },
  {
    category: "REST API",
    features: [
      { name: "GET /documents (list)", ...fromTier("free") },
      { name: "GET /documents/{id} (single)", ...fromTier("free") },
      { name: "GET /documents/{id}/data (secure URL)", ...fromTier("free") },
      { name: "POST /documents/{id}/view (track)", ...fromTier("free") },
      { name: "GET /settings", ...fromTier("free") },
      { name: "GET /analytics", ...fromTier("pro") },
      { name: "GET /analytics/documents", ...fromTier("pro") },
      { name: "GET /analytics/export", ...fromTier("pro") },
      { name: "GET/POST /documents/{id}/progress", ...fromTier("pro") },
      { name: "POST /documents/{id}/verify-password", ...fromTier("pro") },
      { name: "POST /documents/{id}/download", ...fromTier("pro") },
      { name: "POST /documents/{id}/expiring-link", ...fromTier("proPlus") },
      { name: "GET /documents/{id}/expiring-link/{token}", ...fromTier("proPlus") },
      { name: "GET /categories, /tags", ...fromTier("pro") },
      { name: "POST /bulk/import", ...fromTier("proPlus") },
      { name: "GET /bulk/import/status", ...fromTier("proPlus") },
    ],
  },
  {
    category: "Reading Experience",
    features: [
      { name: "Page Navigation", ...fromTier("free") },
      { name: "Zoom Controls", ...fromTier("free") },
      { name: "Full Screen Mode", ...fromTier("free") },
      { name: "Reading Progress Tracking", ...fromTier("pro") },
      { name: "Resume Reading Feature", ...fromTier("pro") },
      { name: "Page Position Save", ...fromTier("pro") },
      { name: "Scroll Position Save", ...fromTier("pro") },
      { name: "Zoom Level Save", ...fromTier("pro") },
    ],
  },
  {
    category: "Integrations",
    features: [
      { name: "Yoast SEO (WP)", ...fromTier("free") },
      { name: "Google Analytics 4", ...fromTier("pro") },
      { name: "Mailchimp (Email Gate)", ...fromTier("pro") },
      { name: "WooCommerce (Sell PDFs)", ...fromTier("proPlus") },
    ],
  },
  {
    category: "Developer Features",
    features: [
      { name: "WordPress Hooks", free: "Basic", pro: "Extended", proPlus: "Extended", enterprise: "Extended" },
      { name: "Drupal Alter Hooks", ...fromTier("free") },
      { name: "Template Overrides", ...fromTier("free") },
      { name: "Coding Standards Compliant", ...fromTier("free") },
      { name: "Translation Ready", ...fromTier("free") },
    ],
  },
  {
    category: "Support",
    features: [
      { name: "Community Support", ...fromTier("free") },
      { name: "Email Support", free: false, pro: true, proPlus: true, enterprise: true },
      { name: "Priority Email Support", free: false, pro: true, proPlus: true, enterprise: true },
      { name: "Priority + Chat Support", free: false, pro: false, proPlus: true, enterprise: true },
      { name: "Dedicated Account Manager", ...fromTier("enterprise") },
    ],
  },
  {
    category: "Enterprise",
    features: [
      { name: "SLA Agreement (99.5%+ uptime)", ...fromTier("enterprise") },
      { name: "SSO Integration Support", ...fromTier("enterprise") },
      { name: "Compliance Documentation Package", ...fromTier("enterprise") },
      { name: "Data Processing Agreement (DPA)", ...fromTier("enterprise") },
      { name: "Custom Development (scoped)", ...fromTier("enterprise") },
      { name: "Lifetime Updates", ...fromTier("enterprise") },
      { name: "Priority Escalation Path", ...fromTier("enterprise") },
],
  },
];

const tierColumns = [
  { key: "free" as const, label: "Free", className: "text-foreground" },
  { key: "pro" as const, label: "Pro", className: "text-primary" },
  { key: "proPlus" as const, label: "Pro+", className: "text-foreground" },
  { key: "enterprise" as const, label: "Enterprise", className: "text-accent" },
];

function FeatureCell({ value }: { value: FeatureValue }) {
  if (typeof value === "string") {
    return <span className="text-foreground font-medium text-xs">{value}</span>;
  }
  if (value === true) {
    return <Check className="w-4 h-4 sm:w-5 sm:h-5 text-primary mx-auto" aria-label="Included" />;
  }
  return <X className="w-4 h-4 sm:w-5 sm:h-5 text-destructive mx-auto" aria-label="Not included" />;
}

export function ProComparisonTable() {
  return (
    <section id="compare" className="py-20 lg:py-32 bg-card" aria-labelledby="comparison-heading">
      <div className="container mx-auto px-4 lg:px-8">
        <header className="max-w-3xl mx-auto text-center mb-12">
          <h2 id="comparison-heading" className="text-3xl md:text-4xl font-bold mb-6">
            Feature Comparison
          </h2>
          <p className="text-lg text-muted-foreground">
            Complete feature comparison across {comparisonData.reduce((sum, cat) => sum + cat.features.length, 0)}+ features and all plan tiers
          </p>
        </header>

        <div className="max-w-5xl mx-auto overflow-hidden rounded-2xl border border-border shadow-soft">
          <div className="overflow-x-auto">
            <table className="w-full min-w-[480px]" role="table" aria-label="Complete feature comparison across Free, Pro, Pro+ and Enterprise plans">
              <thead>
                <tr className="bg-muted">
                  <th className="text-left py-3 sm:py-4 px-3 sm:px-4 font-semibold text-foreground text-xs sm:text-sm sticky left-0 bg-muted z-10 min-w-[160px] sm:min-w-[220px]" scope="col">
                    Feature
                  </th>
                  {tierColumns.map((tier) => (
                    <th key={tier.key} className={`text-center py-3 sm:py-4 px-1.5 sm:px-3 font-semibold ${tier.className} text-[10px] sm:text-sm w-16 sm:w-24`} scope="col">
                      {tier.label}
                    </th>
                  ))}
                </tr>
              </thead>
              <tbody>
                {comparisonData.map((category) => (
                  <Fragment key={category.category}>
                    <tr className="bg-muted">
                      <td colSpan={5} className="py-2 sm:py-3 px-3 sm:px-4 font-semibold text-foreground text-xs sm:text-sm uppercase tracking-wider">
                        {category.category}
                      </td>
                    </tr>
                    {category.features.map((feature, featIndex) => (
                      <tr
                        key={`${category.category}-${feature.name}`}
                        className={featIndex % 2 === 0 ? "bg-background" : "bg-muted/30"}
                      >
                        <td className="py-2 sm:py-3 px-3 sm:px-4 text-foreground text-xs sm:text-sm sticky left-0 bg-inherit z-10">
                          {feature.name}
                        </td>
                        {tierColumns.map((tier) => (
                          <td key={tier.key} className={`py-2 sm:py-3 px-1.5 sm:px-3 text-center ${tier.key === "enterprise" ? "bg-accent/5" : tier.key === "pro" ? "bg-primary/5" : ""}`}>
                            <FeatureCell value={feature[tier.key]} />
                          </td>
                        ))}
                      </tr>
                    ))}
                  </Fragment>
                ))}
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </section>
  );
}
