import { Code2, FileCode, Webhook, Database, Shield } from "lucide-react";

interface PremiumFeature {
  feature: string;
  className: string;
  description: string;
  tier: string;
}

const premiumFeatures: PremiumFeature[] = [
  {
    feature: "Analytics Dashboard",
    className: "Premium_Analytics",
    description: "Dashboard widget, view tracking, unique visitors, time spent",
    tier: "Premium",
  },
  {
    feature: "Password Protection",
    className: "Premium_Password",
    description: "Per-PDF password with hashed storage, brute-force protection",
    tier: "Premium",
  },
  {
    feature: "Detailed View Tracking",
    className: "Premium_Tracking",
    description: "IP, user agent, referrer tracking",
    tier: "Premium",
  },
  {
    feature: "Reading Progress",
    className: "Premium_Progress",
    description: "Save/resume reading position, page, scroll, zoom",
    tier: "Premium",
  },
  {
    feature: "XML Sitemap",
    className: "Premium_Sitemap",
    description: "Dedicated sitemap at /pdf/sitemap.xml with XSL styling",
    tier: "Premium",
  },
  {
    feature: "PDF Categories & Tags",
    className: "Premium_Taxonomies",
    description: "Hierarchical categories, flat tags, archive filtering",
    tier: "Premium",
  },
  {
    feature: "CSV/JSON Export",
    className: "Premium_Export",
    description: "Export analytics data in CSV or JSON format",
    tier: "Premium",
  },
  {
    feature: "Role-Based Access",
    className: "Premium_Roles",
    description: "Require login, limit by user role",
    tier: "Premium",
  },
  {
    feature: "Bulk Import",
    className: "Premium_Bulk",
    description: "Import multiple PDFs via CSV/ZIP with category assignment",
    tier: "Premium",
  },
  {
    feature: "Full REST API",
    className: "Premium_API",
    description: "Complete API access for integrations",
    tier: "Premium",
  },
  {
    feature: "White-Label Options",
    className: "Premium_WhiteLabel",
    description: "Remove branding, customize viewer appearance",
    tier: "Premium",
  },
];

interface Hook {
  name: string;
  type: "action" | "filter";
  description: string;
  example: string;
}

const hooks: Hook[] = [
  {
    name: "pdf_embed_seo_pdf_viewed",
    type: "action",
    description: "Fires after a PDF is viewed with post ID and view count",
    example: `add_action( 'pdf_embed_seo_pdf_viewed', function( $post_id, $count ) {
    // Your code here
}, 10, 2 );`,
  },
  {
    name: "pdf_embed_seo_schema_data",
    type: "filter",
    description: "Modify Schema.org DigitalDocument data for a single PDF",
    example: `add_filter( 'pdf_embed_seo_schema_data', function( $schema, $post_id ) {
    $schema['author'] = [
        '@type' => 'Person',
        'name'  => get_post_meta( $post_id, '_author', true ),
    ];
    return $schema;
}, 10, 2 );`,
  },
  {
    name: "pdf_embed_seo_archive_schema_data",
    type: "filter",
    description: "Modify Schema.org data for the archive page",
    example: `add_filter( 'pdf_embed_seo_archive_schema_data', function( $schema ) {
    $schema['description'] = 'Custom archive description';
    return $schema;
} );`,
  },
  {
    name: "pdf_embed_seo_archive_query",
    type: "filter",
    description: "Modify the archive page posts per page setting",
    example: `add_filter( 'pdf_embed_seo_archive_query', function( $posts_per_page ) {
    return 24; // Show 24 PDFs per page
} );`,
  },
  {
    name: "pdf_embed_seo_sitemap_query_args",
    type: "filter",
    description: "Modify the sitemap shortcode query arguments",
    example: `add_filter( 'pdf_embed_seo_sitemap_query_args', function( $query_args, $atts ) {
    $query_args['orderby'] = 'modified';
    return $query_args;
}, 10, 2 );`,
  },
  {
    name: "pdf_embed_seo_archive_title",
    type: "filter",
    description: "Modify the archive page title",
    example: `add_filter( 'pdf_embed_seo_archive_title', function( $title ) {
    return 'Our PDF Library';
} );`,
  },
  {
    name: "pdf_embed_seo_archive_description",
    type: "filter",
    description: "Modify the archive page description",
    example: `add_filter( 'pdf_embed_seo_archive_description', function( $description ) {
    return 'Browse our collection of PDF documents.';
} );`,
  },
  {
    name: "pdf_embed_seo_viewer_options",
    type: "filter",
    description: "Modify PDF.js viewer options",
    example: `add_filter( 'pdf_embed_seo_viewer_options', function( $options, $post_id ) {
    $options['defaultZoom'] = 'page-fit';
    return $options;
}, 10, 2 );`,
  },
  {
    name: "pdf_embed_seo_allowed_types",
    type: "filter",
    description: "Modify allowed MIME types for upload",
    example: `add_filter( 'pdf_embed_seo_allowed_types', function( $types ) {
    $types[] = 'application/postscript';
    return $types;
} );`,
  },
  {
    name: "pdf_embed_seo_rest_document",
    type: "filter",
    description: "Modify REST API document response",
    example: `add_filter( 'pdf_embed_seo_rest_document', function( $data, $post, $detailed ) {
    $data['custom_field'] = get_post_meta( $post->ID, '_custom', true );
    return $data;
}, 10, 3 );`,
  },
  {
    name: "pdf_embed_seo_rest_document_data",
    type: "filter",
    description: "Modify REST API document data response",
    example: `add_filter( 'pdf_embed_seo_rest_document_data', function( $data, $post_id ) {
    $data['extra'] = 'value';
    return $data;
}, 10, 2 );`,
  },
  {
    name: "pdf_embed_seo_rest_settings",
    type: "filter",
    description: "Modify REST API settings response",
    example: `add_filter( 'pdf_embed_seo_rest_settings', function( $settings ) {
    $settings['custom_setting'] = true;
    return $settings;
} );`,
  },
];

interface Endpoint {
  method: string;
  endpoint: string;
  description: string;
  tier?: string;
}

const restEndpoints: Endpoint[] = [
  // Public Endpoints
  { method: "GET", endpoint: "/wp-json/pdf-embed-seo/v1/documents", description: "List all published PDF documents" },
  { method: "GET", endpoint: "/wp-json/pdf-embed-seo/v1/documents/{id}", description: "Get single PDF document details" },
  { method: "GET", endpoint: "/wp-json/pdf-embed-seo/v1/documents/{id}/data", description: "Get PDF file URL securely (for viewer)" },
  { method: "POST", endpoint: "/wp-json/pdf-embed-seo/v1/documents/{id}/view", description: "Track a PDF view (increment counter)" },
  { method: "GET", endpoint: "/wp-json/pdf-embed-seo/v1/settings", description: "Get public plugin settings" },
  // Premium Endpoints
  { method: "GET", endpoint: "/wp-json/pdf-embed-seo/v1/analytics", description: "Get analytics overview (requires admin)", tier: "Premium" },
  { method: "GET", endpoint: "/wp-json/pdf-embed-seo/v1/analytics/documents", description: "Get per-document analytics data", tier: "Premium" },
  { method: "GET", endpoint: "/wp-json/pdf-embed-seo/v1/analytics/export", description: "Export analytics as CSV/JSON", tier: "Premium" },
  { method: "GET", endpoint: "/wp-json/pdf-embed-seo/v1/documents/{id}/progress", description: "Get reading progress", tier: "Premium" },
  { method: "POST", endpoint: "/wp-json/pdf-embed-seo/v1/documents/{id}/progress", description: "Save reading progress", tier: "Premium" },
  { method: "POST", endpoint: "/wp-json/pdf-embed-seo/v1/documents/{id}/verify-password", description: "Verify password for protected PDFs", tier: "Premium" },
  { method: "POST", endpoint: "/wp-json/pdf-embed-seo/v1/documents/{id}/download", description: "Track PDF download (separate from views)", tier: "Premium" },
  { method: "POST", endpoint: "/wp-json/pdf-embed-seo/v1/documents/{id}/expiring-link", description: "Generate expiring access link (admin only)", tier: "Premium" },
  { method: "GET", endpoint: "/wp-json/pdf-embed-seo/v1/documents/{id}/expiring-link/{token}", description: "Access PDF via expiring link", tier: "Premium" },
  { method: "GET", endpoint: "/wp-json/pdf-embed-seo/v1/categories", description: "List PDF categories", tier: "Premium" },
  { method: "GET", endpoint: "/wp-json/pdf-embed-seo/v1/tags", description: "List PDF tags", tier: "Premium" },
  { method: "POST", endpoint: "/wp-json/pdf-embed-seo/v1/bulk/import", description: "Bulk import PDFs via CSV/ZIP", tier: "Premium" },
  { method: "GET", endpoint: "/wp-json/pdf-embed-seo/v1/bulk/import/status", description: "Get bulk import status (admin only)", tier: "Premium" },
];

export function ProDeveloperDocs() {
  return (
    <section className="py-20 lg:py-32 bg-background" aria-labelledby="developer-docs-heading">
      <div className="container mx-auto px-4 lg:px-8">
        <header className="max-w-3xl mx-auto text-center mb-12">
          <div className="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-300 text-sm font-medium mb-6">
            <Code2 className="w-4 h-4" aria-hidden="true" />
            For Developers
          </div>
          <h2 id="developer-docs-heading" className="text-3xl md:text-4xl font-bold mb-6">
            Developer Documentation
          </h2>
          <p className="text-lg text-muted-foreground">
            Extend and integrate PDF Embed Premium with hooks, REST API, and webhooks
          </p>
        </header>

        <div className="max-w-5xl mx-auto space-y-16">
          {/* Premium Features Classes */}
          <div>
            <div className="flex items-center gap-3 mb-6">
              <div className="w-10 h-10 rounded-lg bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center">
                <FileCode className="w-5 h-5 text-purple-800 dark:text-purple-300" aria-hidden="true" />
              </div>
              <h3 className="text-2xl font-bold text-foreground">Premium Feature Classes</h3>
            </div>

            <div className="overflow-x-auto rounded-2xl border border-border">
              <table className="w-full min-w-[500px]" role="table" aria-label="Premium feature classes and their tiers">
                <thead>
                  <tr className="bg-muted">
                    <th className="text-left py-3 px-4 font-semibold text-foreground" scope="col">Feature</th>
                    <th className="text-left py-3 px-4 font-semibold text-foreground" scope="col">Class</th>
                    <th className="text-left py-3 px-4 font-semibold text-foreground hidden lg:table-cell" scope="col">Description</th>
                    <th className="text-left py-3 px-4 font-semibold text-foreground" scope="col">Tier</th>
                  </tr>
                </thead>
                <tbody>
                  {premiumFeatures.map((feature, index) => (
                    <tr key={feature.feature} className={index % 2 === 0 ? "bg-background" : "bg-muted/30"}>
                      <td className="py-3 px-4 text-foreground font-medium whitespace-nowrap">{feature.feature}</td>
                      <td className="py-3 px-4">
                        <code className="text-foreground bg-muted px-2 py-1 rounded text-xs sm:text-sm font-mono whitespace-nowrap">
                          {feature.className}
                        </code>
                      </td>
                      <td className="py-3 px-4 text-muted-foreground hidden lg:table-cell">{feature.description}</td>
                      <td className="py-3 px-4">
                        <span className="text-xs font-medium px-2 py-1 rounded bg-primary/10 text-primary whitespace-nowrap">
                          {feature.tier}
                        </span>
                      </td>
                    </tr>
                  ))}
                </tbody>
              </table>
            </div>
          </div>

          {/* WordPress Hooks */}
          <div>
            <div className="flex items-center gap-3 mb-6">
              <div className="w-10 h-10 rounded-lg bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center">
                <Code2 className="w-5 h-5 text-blue-800 dark:text-blue-300" aria-hidden="true" />
              </div>
              <h3 className="text-2xl font-bold text-foreground">WordPress Hooks</h3>
            </div>

            <div className="space-y-4">
              {hooks.map((hook) => (
                <div key={hook.name} className="bg-card rounded-xl border border-border p-4">
                  <div className="flex flex-wrap items-center gap-2 mb-2">
                    <code className="text-foreground font-mono font-semibold text-sm sm:text-base break-all">{hook.name}</code>
                    <span className={`text-xs font-medium px-2 py-0.5 rounded shrink-0 ${
                      hook.type === "action" 
                        ? "bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300 border border-green-300 dark:border-green-700" 
                        : "bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300 border border-blue-300 dark:border-blue-700"
                    }`}>
                      {hook.type}
                    </span>
                  </div>
                  <p className="text-muted-foreground text-sm mb-3">{hook.description}</p>
                  <pre className="bg-muted px-3 sm:px-4 py-3 rounded-lg font-mono text-[10px] sm:text-xs overflow-x-auto">
                    <code className="text-foreground">{hook.example}</code>
                  </pre>
                </div>
              ))}
            </div>
          </div>

          {/* REST API */}
          <div>
            <div className="flex items-center gap-3 mb-6">
              <div className="w-10 h-10 rounded-lg bg-amber-100 dark:bg-amber-900/30 flex items-center justify-center">
                <Database className="w-5 h-5 text-amber-800 dark:text-amber-300" aria-hidden="true" />
              </div>
              <div>
                <h3 className="text-2xl font-bold text-foreground">REST API</h3>
                <p className="text-sm text-muted-foreground">Base URL: /wp-json/pdf-embed-seo/v1/</p>
              </div>
            </div>

            <div className="overflow-x-auto rounded-2xl border border-border mb-6">
              <table className="w-full min-w-[500px]" role="table" aria-label="REST API endpoints reference">
                <thead>
                  <tr className="bg-muted">
                    <th className="text-left py-3 px-4 font-semibold text-foreground" scope="col">Method</th>
                    <th className="text-left py-3 px-4 font-semibold text-foreground" scope="col">Endpoint</th>
                    <th className="text-left py-3 px-4 font-semibold text-foreground hidden md:table-cell" scope="col">Description</th>
                    <th className="text-left py-3 px-4 font-semibold text-foreground" scope="col">Tier</th>
                  </tr>
                </thead>
                <tbody>
                  {restEndpoints.map((endpoint, index) => (
                    <tr key={endpoint.endpoint} className={index % 2 === 0 ? "bg-background" : "bg-muted/30"}>
                      <td className="py-3 px-4">
                        <span className={`text-xs font-bold px-2 py-1 rounded whitespace-nowrap ${
                          endpoint.method === "GET" ? "bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300" :
                          endpoint.method === "POST" ? "bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300" :
                          endpoint.method === "PUT" ? "bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-300" :
                          "bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300"
                        }`} aria-label={`${endpoint.method} request`}>
                          {endpoint.method}
                        </span>
                      </td>
                      <td className="py-3 px-4">
                        <code className="text-foreground font-mono text-xs sm:text-sm break-all">{endpoint.endpoint}</code>
                      </td>
                      <td className="py-3 px-4 text-muted-foreground hidden md:table-cell">{endpoint.description}</td>
                      <td className="py-3 px-4">
                        {endpoint.tier && (
                          <span className="text-xs font-medium px-2 py-0.5 rounded bg-primary/10 text-primary whitespace-nowrap" aria-label={`Available in ${endpoint.tier} tier`}>
                            {endpoint.tier}
                          </span>
                        )}
                      </td>
                    </tr>
                  ))}
                </tbody>
              </table>
            </div>

            {/* Authentication */}
            <div className="bg-card rounded-xl border border-border p-4 mb-6">
              <div className="flex items-center gap-2 mb-3">
                <Shield className="w-4 h-4 text-primary" aria-hidden="true" />
                <p className="text-sm font-medium text-foreground">Authentication (Premium Endpoints)</p>
              </div>
              <p className="text-sm text-muted-foreground mb-4">
                Premium endpoints require WordPress cookie authentication with a valid nonce. 
                Include the <code className="text-primary bg-muted px-1.5 py-0.5 rounded text-xs">X-WP-Nonce</code> header 
                with a nonce generated via <code className="text-primary bg-muted px-1.5 py-0.5 rounded text-xs">wp_create_nonce('wp_rest')</code>.
              </p>
              <pre className="bg-muted px-3 sm:px-4 py-3 rounded-lg font-mono text-[10px] sm:text-xs overflow-x-auto mb-4">
                <code className="text-foreground">{`// JavaScript (logged-in users)
fetch('/wp-json/pdf-embed-seo/v1/analytics', {
  headers: {
    'X-WP-Nonce': wpApiSettings.nonce,
    'Content-Type': 'application/json'
  }
});`}</code>
              </pre>
              <pre className="bg-muted px-3 sm:px-4 py-3 rounded-lg font-mono text-[10px] sm:text-xs overflow-x-auto">
                <code className="text-foreground">{`// PHP (server-side)
$nonce = wp_create_nonce('wp_rest');
// Pass $nonce to frontend via wp_localize_script()`}</code>
              </pre>
            </div>

            <div className="bg-card rounded-xl border border-border p-4">
              <p className="text-sm font-medium text-foreground mb-3">Example Public Request:</p>
              <pre className="bg-muted px-4 py-3 rounded-lg font-mono text-xs overflow-x-auto">
                <code className="text-foreground">{`curl -X GET "https://example.com/wp-json/pdf-embed-seo/v1/documents?per_page=5&orderby=views" \\
  -H "Content-Type: application/json"`}</code>
              </pre>
            </div>
          </div>

          {/* Webhooks */}
          <div>
            <div className="flex items-center gap-3 mb-6">
              <div className="w-10 h-10 rounded-lg bg-emerald-100 dark:bg-emerald-900/30 flex items-center justify-center">
                <Webhook className="w-5 h-5 text-emerald-800 dark:text-emerald-300" aria-hidden="true" />
              </div>
              <div>
                <h3 className="text-2xl font-bold text-foreground">JavaScript Events</h3>
              </div>
            </div>

            <p className="text-muted-foreground mb-6">
              Listen for viewer events to build custom integrations.
            </p>

            <div className="grid grid-cols-1 md:grid-cols-2 gap-4" role="list" aria-label="Available JavaScript events">
              {[
                { event: "pdfLoaded", description: "PDF document loaded successfully" },
                { event: "pageRendered", description: "A page has been rendered" },
                { event: "pageChanged", description: "User navigated to a different page" },
                { event: "zoomChanged", description: "Zoom level was changed" },
              ].map((item) => (
                <article key={item.event} className="bg-card rounded-xl border border-border p-4" role="listitem">
                  <code className="text-emerald-800 dark:text-emerald-300 font-mono font-semibold text-sm">{item.event}</code>
                  <p className="text-muted-foreground text-sm mt-1">{item.description}</p>
                </article>
              ))}
            </div>
          </div>
        </div>
      </div>
    </section>
  );
}
