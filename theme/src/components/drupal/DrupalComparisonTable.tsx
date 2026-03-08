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
      { name: "Light & Dark Themes", free: true, pro: true, proPlus: true },
      { name: "Responsive Design", free: true, pro: true, proPlus: true },
      { name: "Print Control (per PDF)", free: true, pro: true, proPlus: true },
      { name: "Download Control (per PDF)", free: true, pro: true, proPlus: true },
      { name: "Configurable Viewer Height", free: true, pro: true, proPlus: true },
      { name: "PDF Viewer Block", free: true, pro: true, proPlus: true },
      { name: "iOS/Safari Print Support", free: true, pro: true, proPlus: true },
      { name: "PDF Annotations", free: false, pro: false, proPlus: true },
      { name: "Digital Signatures", free: false, pro: false, proPlus: true },
    ],
  },
  {
    category: "Content Management",
    features: [
      { name: "PDF Document Entity", free: true, pro: true, proPlus: true },
      { name: "Title, Description, Slug", free: true, pro: true, proPlus: true },
      { name: "File Upload & Management", free: true, pro: true, proPlus: true },
      { name: "Thumbnail Support", free: true, pro: true, proPlus: true },
      { name: "Auto-Generate Thumbnails", free: true, pro: true, proPlus: true },
      { name: "Multi-language Support", free: true, pro: true, proPlus: true },
      { name: "Owner/User Tracking", free: true, pro: true, proPlus: true },
      { name: "Media Library Integration", free: true, pro: true, proPlus: true },
      { name: "Document Versioning", free: false, pro: false, proPlus: true },
      { name: "Version History", free: false, pro: false, proPlus: true },
      { name: "Auto-Versioning", free: false, pro: false, proPlus: true },
    ],
  },
  {
    category: "SEO & URLs",
    features: [
      { name: "Clean URLs (/pdf/slug)", free: true, pro: true, proPlus: true },
      { name: "Auto Path Alias", free: true, pro: true, proPlus: true },
      { name: "Schema.org DigitalDocument", free: true, pro: true, proPlus: true },
      { name: "Schema.org CollectionPage", free: true, pro: true, proPlus: true },
      { name: "Schema.org BreadcrumbList", free: true, pro: true, proPlus: true },
      { name: "OpenGraph Meta Tags", free: true, pro: true, proPlus: true },
      { name: "Twitter Card Support", free: true, pro: true, proPlus: true },
      { name: "GEO/AEO/LLM Schema Optimization", free: true, pro: true, proPlus: true },
      { name: "SpeakableSpecification (Voice Assistants)", free: true, pro: true, proPlus: true },
      { name: "potentialAction Schema (ReadAction, etc.)", free: true, pro: true, proPlus: true },
      { name: "Standalone Social Meta (without Metatag)", free: true, pro: true, proPlus: true },
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
      { name: "Archive Page (/pdf)", free: true, pro: true, proPlus: true },
      { name: "Pagination", free: true, pro: true, proPlus: true },
      { name: "Grid/List Display", free: true, pro: true, proPlus: true },
      { name: "Search & Sorting", free: true, pro: true, proPlus: true },
      { name: "Visible Breadcrumb Navigation", free: true, pro: true, proPlus: true },
      { name: "Full-Width Layout (No Sidebars)", free: true, pro: true, proPlus: true },
      { name: "Custom Archive Heading", free: true, pro: true, proPlus: true },
      { name: "Content Alignment Options", free: true, pro: true, proPlus: true },
      { name: "Custom Font/Background Colors", free: true, pro: true, proPlus: true },
      { name: "Category/Tag Filters", free: false, pro: true, proPlus: true },
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
      { name: "Analytics Dashboard", free: false, pro: true, proPlus: true },
      { name: "Detailed Tracking (IP, UA, referrer)", free: false, pro: true, proPlus: true },
      { name: "Download Tracking", free: false, pro: true, proPlus: true },
      { name: "IP Anonymization (GDPR)", free: false, pro: true, proPlus: true },
      { name: "Time Spent Tracking", free: false, pro: true, proPlus: true },
      { name: "Popular Documents Report", free: false, pro: true, proPlus: true },
      { name: "CSV/JSON Export", free: false, pro: true, proPlus: true },
      { name: "Heatmaps", free: false, pro: false, proPlus: true },
      { name: "Engagement Scoring", free: false, pro: false, proPlus: true },
      { name: "Geographic Analytics", free: false, pro: false, proPlus: true },
      { name: "Device Analytics", free: false, pro: false, proPlus: true },
    ],
  },
  {
    category: "Security",
    features: [
      { name: "Secure PDF URLs", free: true, pro: true, proPlus: true },
      { name: "CSRF Protection", free: true, pro: true, proPlus: true },
      { name: "Permission System", free: true, pro: true, proPlus: true },
      { name: "Entity Access Control", free: true, pro: true, proPlus: true },
      { name: "Password Protection", free: false, pro: true, proPlus: true },
      { name: "Password Hashing (Drupal service)", free: false, pro: true, proPlus: true },
      { name: "Session Cache Context", free: false, pro: true, proPlus: true },
      { name: "Brute-Force Protection", free: false, pro: true, proPlus: true },
      { name: "Rate Limiting", free: false, pro: true, proPlus: true },
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
      { name: "Resume Reading", free: false, pro: true, proPlus: true },
    ],
  },
  {
    category: "Developer",
    features: [
      { name: "Drupal Hooks (alter, events)", free: true, pro: true, proPlus: true },
      { name: "Twig Template Overrides", free: true, pro: true, proPlus: true },
      { name: "Drupal Services", free: true, pro: true, proPlus: true },
      { name: "Cache Tags & Contexts", free: true, pro: true, proPlus: true },
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
      { name: "Google Analytics 4", free: false, pro: true, proPlus: true },
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

export function DrupalComparisonTable() {
  return (
    <section className="py-16 lg:py-24" aria-labelledby="drupal-comparison-heading">
      <div className="container mx-auto px-4 lg:px-8">
        <div className="max-w-4xl mx-auto">
          <h2 id="drupal-comparison-heading" className="text-3xl font-bold mb-8 text-center">
            Drupal Feature Comparison: Free vs Pro vs Pro+
          </h2>

          {/* Desktop Table */}
          <div className="hidden md:block overflow-hidden rounded-2xl border border-border shadow-soft">
            <table className="w-full" role="table" aria-label="Drupal module feature comparison between Free, Pro, and Pro+ versions">
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
                      <div className="flex items-center gap-6 text-sm">
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
