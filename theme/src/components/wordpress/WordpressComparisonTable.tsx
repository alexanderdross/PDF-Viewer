import { Check, X } from "lucide-react";

type FeatureValue = boolean | string;

interface Feature {
  name: string;
  free: FeatureValue;
  pro: FeatureValue;
  proPlus: FeatureValue;
}

interface FeatureCategory {
  category: string;
  features: Feature[];
}

const comparisonData: FeatureCategory[] = [
  {
    category: "Viewer & Display",
    features: [
      { name: "Mozilla PDF.js Viewer (v4.0)", free: true, pro: true, proPlus: true },
      { name: "Light Theme", free: true, pro: true, proPlus: true },
      { name: "Dark Theme", free: true, pro: true, proPlus: true },
      { name: "Responsive Design", free: true, pro: true, proPlus: true },
      { name: "Print Control (per PDF)", free: true, pro: true, proPlus: true },
      { name: "Download Control (per PDF)", free: true, pro: true, proPlus: true },
      { name: "Configurable Viewer Height", free: true, pro: true, proPlus: true },
      { name: "Gutenberg Block", free: true, pro: true, proPlus: true },
      { name: "Shortcodes", free: true, pro: true, proPlus: true },
      { name: "iOS/Safari Print Support", free: true, pro: true, proPlus: true },
      { name: "Text Search in Viewer", free: false, pro: true, proPlus: true },
      { name: "Bookmark Navigation", free: false, pro: true, proPlus: true },
      { name: "PDF Annotations", free: false, pro: false, proPlus: true },
      { name: "Digital Signatures", free: false, pro: false, proPlus: true },
    ],
  },
  {
    category: "Content Management",
    features: [
      { name: "PDF Document Post Type", free: true, pro: true, proPlus: true },
      { name: "Title, Description, Slug", free: true, pro: true, proPlus: true },
      { name: "File Upload & Management", free: true, pro: true, proPlus: true },
      { name: "Featured Image / Thumbnail", free: true, pro: true, proPlus: true },
      { name: "Auto-Generate Thumbnails", free: true, pro: true, proPlus: true },
      { name: "Published/Draft Status", free: true, pro: true, proPlus: true },
      { name: "Owner/Author Tracking", free: true, pro: true, proPlus: true },
      { name: "Admin List with Sortable Columns", free: true, pro: true, proPlus: true },
      { name: "Quick Edit Support", free: true, pro: true, proPlus: true },
      { name: "Multi-language Support", free: true, pro: true, proPlus: true },
      { name: "Categories Taxonomy", free: false, pro: true, proPlus: true },
      { name: "Tags Taxonomy", free: false, pro: true, proPlus: true },
      { name: "Role-Based Access Control", free: false, pro: true, proPlus: true },
      { name: "Bulk Edit Actions", free: false, pro: true, proPlus: true },
      { name: "Bulk Import (CSV/ZIP)", free: false, pro: true, proPlus: true },
      { name: "Document Versioning", free: false, pro: false, proPlus: true },
      { name: "Version History", free: false, pro: false, proPlus: true },
      { name: "Auto-Versioning", free: false, pro: false, proPlus: true },
    ],
  },
  {
    category: "SEO & URLs",
    features: [
      { name: "Clean URL Structure (/pdf/slug/)", free: true, pro: true, proPlus: true },
      { name: "Auto Path/Slug Generation", free: true, pro: true, proPlus: true },
      { name: "Schema.org DigitalDocument", free: true, pro: true, proPlus: true },
      { name: "Schema.org CollectionPage (Archive)", free: true, pro: true, proPlus: true },
      { name: "Schema.org BreadcrumbList", free: true, pro: true, proPlus: true },
      { name: "Yoast SEO Integration", free: true, pro: true, proPlus: true },
      { name: "OpenGraph Meta Tags", free: true, pro: true, proPlus: true },
      { name: "Twitter Card Support", free: true, pro: true, proPlus: true },
      { name: "GEO/AEO/LLM Schema Optimization", free: true, pro: true, proPlus: true },
      { name: "SpeakableSpecification (Voice Assistants)", free: true, pro: true, proPlus: true },
      { name: "potentialAction Schema (ReadAction, etc.)", free: true, pro: true, proPlus: true },
      { name: "Standalone Social Meta (without Yoast)", free: true, pro: true, proPlus: true },
      { name: "XML Sitemap (/pdf/sitemap.xml)", free: false, pro: true, proPlus: true },
      { name: "Sitemap XSL Stylesheet", free: false, pro: true, proPlus: true },
      { name: "Search Engine Ping", free: false, pro: true, proPlus: true },
    ],
  },
  {
    category: "AI & Voice Search Optimization",
    features: [
      { name: "Basic Speakable Schema (WebPage)", free: true, pro: true, proPlus: true },
      { name: "potentialAction (ReadAction, DownloadAction)", free: true, pro: true, proPlus: true },
      { name: "accessMode & accessibilityFeature", free: true, pro: true, proPlus: true },
      { name: "AI Summary / TL;DR Field", free: false, pro: true, proPlus: true },
      { name: "Key Points & Takeaways", free: false, pro: true, proPlus: true },
      { name: "FAQ Schema (FAQPage for Rich Results)", free: false, pro: true, proPlus: true },
      { name: "Table of Contents Schema (hasPart)", free: false, pro: true, proPlus: true },
      { name: "Reading Time Estimate (timeRequired)", free: false, pro: true, proPlus: true },
      { name: "Difficulty Level (educationalLevel)", free: false, pro: true, proPlus: true },
      { name: "Document Type Classification", free: false, pro: true, proPlus: true },
      { name: "Target Audience Schema", free: false, pro: true, proPlus: true },
      { name: "Custom Speakable Content", free: false, pro: true, proPlus: true },
      { name: "Related Documents (isRelatedTo)", free: false, pro: true, proPlus: true },
      { name: "Prerequisites (coursePrerequisites)", free: false, pro: true, proPlus: true },
      { name: "Learning Outcomes (teaches)", free: false, pro: true, proPlus: true },
    ],
  },
  {
    category: "Archive & Listing",
    features: [
      { name: "Archive Page (/pdf/)", free: true, pro: true, proPlus: true },
      { name: "Pagination Support", free: true, pro: true, proPlus: true },
      { name: "Grid/List Display Modes", free: true, pro: true, proPlus: true },
      { name: "Sorting Options", free: true, pro: true, proPlus: true },
      { name: "Search Filtering", free: true, pro: true, proPlus: true },
      { name: "Visible Breadcrumb Navigation", free: true, pro: true, proPlus: true },
      { name: "Full-Width Layout (No Sidebars)", free: true, pro: true, proPlus: true },
      { name: "Custom Archive Heading", free: true, pro: true, proPlus: true },
      { name: "Content Alignment Options", free: true, pro: true, proPlus: true },
      { name: "Custom Font/Background Colors", free: true, pro: true, proPlus: true },
      { name: "Category Filter", free: false, pro: true, proPlus: true },
      { name: "Tag Filter", free: false, pro: true, proPlus: true },
      { name: "Archive Page Redirect", free: false, pro: true, proPlus: true },
    ],
  },
  {
    category: "REST API",
    features: [
      { name: "GET /documents (list)", free: true, pro: true, proPlus: true },
      { name: "GET /documents/{id} (single)", free: true, pro: true, proPlus: true },
      { name: "GET /documents/{id}/data (secure URL)", free: true, pro: true, proPlus: true },
      { name: "POST /documents/{id}/view (track)", free: true, pro: true, proPlus: true },
      { name: "GET /settings", free: true, pro: true, proPlus: true },
      { name: "GET /analytics", free: false, pro: true, proPlus: true },
      { name: "GET /analytics/documents", free: false, pro: true, proPlus: true },
      { name: "GET /analytics/export", free: false, pro: true, proPlus: true },
      { name: "GET/POST /documents/{id}/progress", free: false, pro: true, proPlus: true },
      { name: "POST /documents/{id}/verify-password", free: false, pro: true, proPlus: true },
      { name: "POST /documents/{id}/download", free: false, pro: true, proPlus: true },
      { name: "POST /documents/{id}/expiring-link", free: false, pro: true, proPlus: true },
      { name: "GET /documents/{id}/expiring-link/{token}", free: false, pro: true, proPlus: true },
      { name: "GET /categories", free: false, pro: true, proPlus: true },
      { name: "GET /tags", free: false, pro: true, proPlus: true },
      { name: "POST /bulk/import", free: false, pro: true, proPlus: true },
      { name: "GET /bulk/import/status", free: false, pro: true, proPlus: true },
      { name: "Pro+ REST API Endpoints", free: false, pro: false, proPlus: true },
      { name: "Webhooks API", free: false, pro: false, proPlus: true },
    ],
  },
  {
    category: "Statistics & Analytics",
    features: [
      { name: "Basic View Counter", free: true, pro: true, proPlus: true },
      { name: "View Count Display", free: true, pro: true, proPlus: true },
      { name: "Analytics Dashboard", free: false, pro: true, proPlus: true },
      { name: "Detailed View Tracking", free: false, pro: true, proPlus: true },
      { name: "Download Tracking", free: false, pro: true, proPlus: true },
      { name: "IP Address Logging", free: false, pro: true, proPlus: true },
      { name: "User Agent Tracking", free: false, pro: true, proPlus: true },
      { name: "Referrer Tracking", free: false, pro: true, proPlus: true },
      { name: "Time Spent Tracking", free: false, pro: true, proPlus: true },
      { name: "Popular Documents Report", free: false, pro: true, proPlus: true },
      { name: "Recent Views Log", free: false, pro: true, proPlus: true },
      { name: "Analytics Export (CSV/JSON)", free: false, pro: true, proPlus: true },
      { name: "Time Period Filters", free: false, pro: true, proPlus: true },
      { name: "Heatmaps", free: false, pro: false, proPlus: true },
      { name: "Engagement Scoring", free: false, pro: false, proPlus: true },
      { name: "Geographic Analytics", free: false, pro: false, proPlus: true },
      { name: "Device Analytics", free: false, pro: false, proPlus: true },
    ],
  },
  {
    category: "Security & Access",
    features: [
      { name: "Nonce/CSRF Protection", free: true, pro: true, proPlus: true },
      { name: "Permission System", free: true, pro: true, proPlus: true },
      { name: "Capability/Access Checks", free: true, pro: true, proPlus: true },
      { name: "Secure PDF URL (no direct links)", free: true, pro: true, proPlus: true },
      { name: "Input Sanitization", free: true, pro: true, proPlus: true },
      { name: "Output Escaping", free: true, pro: true, proPlus: true },
      { name: "Password Protection", free: false, pro: true, proPlus: true },
      { name: "Secure Password Hashing (bcrypt)", free: false, pro: true, proPlus: true },
      { name: "Session-Based Access", free: false, pro: true, proPlus: true },
      { name: "Configurable Session Duration", free: false, pro: true, proPlus: true },
      { name: "Brute-Force Protection", free: false, pro: true, proPlus: true },
      { name: "Login Requirement Option", free: false, pro: true, proPlus: true },
      { name: "Role Restrictions", free: false, pro: true, proPlus: true },
      { name: "Expiring Access Links", free: false, pro: true, proPlus: true },
      { name: "Max Uses per Link", free: false, pro: true, proPlus: true },
      { name: "Two-Factor Authentication (2FA)", free: false, pro: false, proPlus: true },
      { name: "IP Whitelisting", free: false, pro: false, proPlus: true },
      { name: "Audit Logs", free: false, pro: false, proPlus: true },
    ],
  },
  {
    category: "Reading Experience",
    features: [
      { name: "Page Navigation", free: true, pro: true, proPlus: true },
      { name: "Zoom Controls", free: true, pro: true, proPlus: true },
      { name: "Full Screen Mode", free: true, pro: true, proPlus: true },
      { name: "Reading Progress Tracking", free: false, pro: true, proPlus: true },
      { name: "Resume Reading Feature", free: false, pro: true, proPlus: true },
      { name: "Page Position Save", free: false, pro: true, proPlus: true },
      { name: "Scroll Position Save", free: false, pro: true, proPlus: true },
      { name: "Zoom Level Save", free: false, pro: true, proPlus: true },
    ],
  },
  {
    category: "Developer Features",
    features: [
      { name: "WordPress Actions & Filters", free: true, pro: true, proPlus: true },
      { name: "Template Overrides", free: true, pro: true, proPlus: true },
      { name: "Coding Standards Compliant", free: true, pro: true, proPlus: true },
      { name: "Translation Ready", free: true, pro: true, proPlus: true },
    ],
  },
  {
    category: "Compliance & Privacy",
    features: [
      { name: "Basic GDPR (IP Anonymization)", free: true, pro: true, proPlus: true },
      { name: "Full GDPR Mode", free: false, pro: false, proPlus: true },
      { name: "HIPAA Compliance Mode", free: false, pro: false, proPlus: true },
      { name: "Data Retention Policies", free: false, pro: false, proPlus: true },
      { name: "Consent Management", free: false, pro: false, proPlus: true },
    ],
  },
  {
    category: "Integrations",
    features: [
      { name: "Yoast SEO", free: true, pro: true, proPlus: true },
      { name: "Google Analytics 4", free: false, pro: true, proPlus: true },
      { name: "WooCommerce (Sell PDFs)", free: false, pro: true, proPlus: true },
      { name: "Webhooks (Zapier, etc.)", free: false, pro: false, proPlus: true },
      { name: "Custom Webhook Events", free: false, pro: false, proPlus: true },
      { name: "Webhook Signatures (HMAC)", free: false, pro: false, proPlus: true },
    ],
  },
  {
    category: "Branding",
    features: [
      { name: "Default Branding", free: true, pro: true, proPlus: true },
      { name: "White Label Mode", free: false, pro: false, proPlus: true },
      { name: "Custom Logo", free: false, pro: false, proPlus: true },
      { name: 'Hide "Powered By"', free: false, pro: false, proPlus: true },
      { name: "Custom CSS Injection", free: false, pro: false, proPlus: true },
    ],
  },
  {
    category: "Support",
    features: [
      { name: "Community Support", free: true, pro: true, proPlus: true },
      { name: "Priority Email Support", free: false, pro: true, proPlus: true },
      { name: "1-on-1 Setup Assistance", free: false, pro: false, proPlus: true },
      { name: "Dedicated Account Manager", free: false, pro: false, proPlus: true },
      { name: "SLA Guarantee", free: false, pro: false, proPlus: true },
    ],
  },
];

function FeatureCell({ value }: { value: FeatureValue }) {
  if (typeof value === "string") {
    return <span className="text-foreground font-medium">{value}</span>;
  }
  if (value === true) {
    return <Check className="w-5 h-5 text-primary mx-auto" aria-label="Included" />;
  }
  return <X className="w-5 h-5 text-destructive mx-auto" aria-label="Not included" />;
}

export function WordpressComparisonTable() {
  return (
    <section className="py-16 lg:py-24" aria-labelledby="wp-comparison-heading">
      <div className="container mx-auto px-4 lg:px-8">
        <div className="max-w-4xl mx-auto">
          <h2 id="wp-comparison-heading" className="text-3xl font-bold mb-8 text-center">
            WordPress Feature Comparison: Free vs Pro vs Pro+
          </h2>

          {/* Desktop Table */}
          <div className="hidden md:block overflow-hidden rounded-2xl border border-border shadow-soft">
            <table className="w-full" role="table" aria-label="WordPress plugin feature comparison between Free, Pro, and Pro+ versions">
              <thead>
                <tr className="bg-muted">
                  <th className="text-left py-4 px-6 font-semibold text-foreground" scope="col">
                    Feature
                  </th>
                  <th className="text-center py-4 px-6 font-semibold text-foreground w-24" scope="col">
                    Free
                  </th>
                  <th className="text-center py-4 px-6 font-semibold text-primary w-24" scope="col">
                    Pro
                  </th>
                  <th className="text-center py-4 px-6 font-semibold text-accent w-24" scope="col">
                    Pro+
                  </th>
                </tr>
              </thead>
              <tbody>
                {comparisonData.map((category) => (
                  <>
                    <tr key={category.category} className="bg-muted">
                      <td colSpan={4} className="py-3 px-6 font-semibold text-foreground text-sm uppercase tracking-wider">
                        {category.category}
                      </td>
                    </tr>
                    {category.features.map((feature, featIndex) => (
                      <tr
                        key={`${category.category}-${feature.name}`}
                        className={featIndex % 2 === 0 ? "bg-background" : "bg-muted/50"}
                      >
                        <td className="py-3 px-6 text-foreground">
                          {feature.name}
                        </td>
                        <td className="py-3 px-6 text-center">
                          <FeatureCell value={feature.free} />
                        </td>
                        <td className="py-3 px-6 text-center bg-primary/10">
                          <FeatureCell value={feature.pro} />
                        </td>
                        <td className="py-3 px-6 text-center bg-accent/10">
                          <FeatureCell value={feature.proPlus} />
                        </td>
                      </tr>
                    ))}
                  </>
                ))}
              </tbody>
            </table>
          </div>

          {/* Mobile Cards */}
          <div className="md:hidden space-y-6">
            {comparisonData.map((category) => (
              <div key={category.category} className="bg-background rounded-xl border border-border overflow-hidden">
                <div className="bg-muted px-4 py-3">
                  <h3 className="font-semibold text-foreground text-sm uppercase tracking-wider">
                    {category.category}
                  </h3>
                </div>
                <div className="divide-y divide-border">
                  {category.features.map((feature) => (
                    <div key={feature.name} className="p-4">
                      <div className="font-medium text-foreground mb-2">{feature.name}</div>
                      <div className="flex items-center gap-4 text-sm">
                        <div className="flex items-center gap-2">
                          <span className="text-muted-foreground">Free:</span>
                          <FeatureCell value={feature.free} />
                        </div>
                        <div className="flex items-center gap-2">
                          <span className="text-primary font-medium">Pro:</span>
                          <FeatureCell value={feature.pro} />
                        </div>
                        <div className="flex items-center gap-2">
                          <span className="text-accent font-medium">Pro+:</span>
                          <FeatureCell value={feature.proPlus} />
                        </div>
                      </div>
                    </div>
                  ))}
                </div>
              </div>
            ))}
          </div>
        </div>
      </div>
    </section>
  );
}
