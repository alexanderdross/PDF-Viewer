<?php
/**
 * Template Name: Documentation
 * Complete documentation page with platform tabs
 *
 * @package PDFViewer
 */

get_header();

// Theme version marker for debugging
echo '<!-- PdfViewer Theme v' . PDFVIEWER_THEME_VERSION . ' - Documentation Template -->' . "\n";

// Configuration settings data
$config_settings = array(
    array('setting' => 'Allow Download by Default', 'default' => 'Yes', 'description' => 'New PDFs allow downloads'),
    array('setting' => 'Allow Print by Default', 'default' => 'Yes', 'description' => 'New PDFs allow printing'),
    array('setting' => 'Auto-generate Thumbnails', 'default' => 'Yes', 'description' => 'Create thumbnails from PDF'),
    array('setting' => 'Viewer Theme', 'default' => 'Light', 'description' => 'Light or Dark theme'),
    array('setting' => 'Viewer Height', 'default' => '800px', 'description' => 'Default viewer height'),
    array('setting' => 'Archive Posts per Page', 'default' => '12', 'description' => 'PDFs per archive page'),
);

// WordPress hooks data
$wp_actions = array(
    array('hook' => 'pdf_embed_seo_pdf_viewed', 'description' => 'Fired when PDF is viewed ($post_id, $count)'),
    array('hook' => 'pdf_embed_seo_premium_init', 'description' => 'Premium features initialized'),
    array('hook' => 'pdf_embed_seo_settings_saved', 'description' => 'Settings saved'),
);

$wp_filters = array(
    array('hook' => 'pdf_embed_seo_schema_data', 'params' => '$schema, $post_id', 'description' => 'Modify Schema.org data for a single PDF'),
    array('hook' => 'pdf_embed_seo_archive_schema_data', 'params' => '$schema', 'description' => 'Modify Schema.org data for archive page'),
    array('hook' => 'pdf_embed_seo_archive_query', 'params' => '$posts_per_page', 'description' => 'Modify archive page posts per page'),
    array('hook' => 'pdf_embed_seo_sitemap_query_args', 'params' => '$query_args, $atts', 'description' => 'Modify sitemap shortcode query args'),
    array('hook' => 'pdf_embed_seo_archive_title', 'params' => '$title', 'description' => 'Modify the archive page title'),
    array('hook' => 'pdf_embed_seo_archive_description', 'params' => '$description', 'description' => 'Modify the archive page description'),
    array('hook' => 'pdf_embed_seo_viewer_options', 'params' => '$options, $post_id', 'description' => 'Modify PDF.js viewer options'),
    array('hook' => 'pdf_embed_seo_allowed_types', 'params' => '$types', 'description' => 'Modify allowed MIME types for upload'),
    array('hook' => 'pdf_embed_seo_rest_document', 'params' => '$data, $post, $detailed', 'description' => 'Modify REST API document response'),
    array('hook' => 'pdf_embed_seo_rest_document_data', 'params' => '$data, $post_id', 'description' => 'Modify REST API document data'),
    array('hook' => 'pdf_embed_seo_rest_settings', 'params' => '$settings', 'description' => 'Modify REST API settings response'),
);

// Premium features
$premium_features = array(
    array(
        'icon'  => 'bar-chart-3',
        'title' => 'Analytics Dashboard',
        'items' => array('Total views and unique visitors', 'Popular documents ranking', 'Time period filters (7d, 30d, 90d, 12m)', 'CSV/JSON export'),
    ),
    array(
        'icon'  => 'lock',
        'title' => 'Password Protection',
        'items' => array('Per-PDF password settings', 'Secure password hashing', 'Brute-force protection', 'AJAX-based verification'),
    ),
    array(
        'icon'  => 'clock',
        'title' => 'Reading Progress',
        'items' => array('Auto-save reading position', 'Resume from last page', 'Track scroll and zoom', 'Works for all users'),
    ),
    array(
        'icon'  => 'globe',
        'title' => 'XML Sitemap',
        'items' => array('Available at /pdf/sitemap.xml', 'XSL-styled browser view', 'Auto-updates on changes', 'Submit to Google Search Console'),
    ),
    array(
        'icon'  => 'database',
        'title' => 'Download Tracking',
        'items' => array('Track downloads separately from views', 'Separate download counter per PDF', 'User attribution for logged-in users', 'REST API endpoint for tracking'),
    ),
    array(
        'icon'  => 'shield',
        'title' => 'Expiring Access Links',
        'items' => array('Generate time-limited URLs', 'Configurable expiration (5min-30 days)', 'Max usage limits per link', 'Secure token-based access'),
    ),
);
?>

<!-- Hero -->
<section class="py-8 lg:py-12 bg-card" aria-labelledby="docs-heading">
    <div class="container mx-auto px-4 lg:px-8">
        <div class="max-w-3xl">
            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-primary/10 text-primary text-sm font-medium mb-6">
                <?php echo pdfviewer_icon('book-open', 16); ?>
                <span><?php esc_html_e('Documentation', 'pdfviewer'); ?></span>
            </div>
            <h1 id="docs-heading" class="text-4xl md:text-5xl font-bold leading-tight mb-6">
                <?php esc_html_e('Getting Started Guide', 'pdfviewer'); ?>
            </h1>
            <p class="text-xl text-muted-foreground">
                <?php esc_html_e('Everything you need to know to start sharing your PDFs professionally. Complete documentation for WordPress, Drupal and React / Next.js.', 'pdfviewer'); ?>
            </p>
        </div>
    </div>
</section>

<!-- Platform Tabs -->
<section class="py-6 border-b border-border bg-background">
    <div class="container mx-auto px-4 lg:px-8">
        <div class="flex gap-2 max-w-xl" role="tablist" aria-label="<?php esc_attr_e('Select platform documentation', 'pdfviewer'); ?>">
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
</section>

<!-- Content -->
<section class="pt-4 pb-8 lg:pt-6 lg:pb-12">
    <div class="container mx-auto px-4 lg:px-8">
        <div class="max-w-4xl mx-auto space-y-16">

            <!-- Getting Started -->
            <div class="animate-fade-in" id="getting-started">
                <div class="flex items-start gap-4 mb-6">
                    <div class="w-12 h-12 rounded-xl gradient-hero flex items-center justify-center shrink-0">
                        <?php echo pdfviewer_icon('terminal', 24, 'text-primary-foreground'); ?>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold mb-2"><?php esc_html_e('Getting Started', 'pdfviewer'); ?></h2>
                        <p class="text-muted-foreground"><?php esc_html_e('Install and configure the plugin in minutes', 'pdfviewer'); ?></p>
                    </div>
                </div>

                <!-- WordPress Installation -->
                <div id="panel-wordpress" role="tabpanel" aria-labelledby="wordpress" class="platform-panel space-y-4">
                    <div class="bg-card rounded-2xl border border-border p-6">
                        <div class="flex items-center gap-2 mb-4">
                            <span class="flex items-center justify-center w-6 h-6 rounded-full bg-primary text-primary-foreground text-xs font-bold">1</span>
                            <p class="text-sm font-medium text-foreground"><?php esc_html_e('Install the Plugin', 'pdfviewer'); ?></p>
                        </div>
                        <p class="text-sm text-muted-foreground mb-3"><?php esc_html_e('Via WordPress Admin:', 'pdfviewer'); ?></p>
                        <ol class="list-decimal list-inside space-y-2 text-muted-foreground ml-4">
                            <li><?php esc_html_e('Go to Plugins > Add New', 'pdfviewer'); ?></li>
                            <li><?php esc_html_e('Search for "PDF Embed SEO Optimize"', 'pdfviewer'); ?></li>
                            <li><?php esc_html_e('Click Install, then Activate', 'pdfviewer'); ?></li>
                        </ol>
                        <p class="text-sm text-muted-foreground mt-4 mb-2"><?php esc_html_e('Or via WP-CLI:', 'pdfviewer'); ?></p>
                        <code class="block bg-muted px-4 py-3 rounded-lg font-mono text-primary text-sm">wp plugin install pdf-embed-seo-optimize --activate</code>
                    </div>
                    <div class="bg-card rounded-2xl border border-border p-6">
                        <div class="flex items-center gap-2 mb-4">
                            <span class="flex items-center justify-center w-6 h-6 rounded-full bg-primary text-primary-foreground text-xs font-bold">2</span>
                            <p class="text-sm font-medium text-foreground"><?php esc_html_e('Upload Your First PDF', 'pdfviewer'); ?></p>
                        </div>
                        <ol class="list-decimal list-inside space-y-2 text-muted-foreground ml-4">
                            <li><?php esc_html_e('Go to PDF Documents > Add New', 'pdfviewer'); ?></li>
                            <li><?php esc_html_e('Enter a title for your PDF', 'pdfviewer'); ?></li>
                            <li><?php esc_html_e('Click "Upload PDF" and select your file', 'pdfviewer'); ?></li>
                            <li><?php esc_html_e('Add a description (optional but recommended for SEO)', 'pdfviewer'); ?></li>
                            <li><?php esc_html_e('Click "Publish" to make it live', 'pdfviewer'); ?></li>
                        </ol>
                    </div>
                    <div class="bg-card rounded-2xl border border-border p-6">
                        <div class="flex items-center gap-2 mb-4">
                            <span class="flex items-center justify-center w-6 h-6 rounded-full bg-primary text-primary-foreground text-xs font-bold">3</span>
                            <p class="text-sm font-medium text-foreground"><?php esc_html_e('Configure Display Options', 'pdfviewer'); ?></p>
                        </div>
                        <ol class="list-decimal list-inside space-y-2 text-muted-foreground ml-4">
                            <li><?php esc_html_e('Choose viewer type: In-Page or Standalone', 'pdfviewer'); ?></li>
                            <li><?php esc_html_e('Toggle Download and Print permissions', 'pdfviewer'); ?></li>
                            <li><?php esc_html_e('Set viewer height (default: 800px)', 'pdfviewer'); ?></li>
                            <li><?php esc_html_e('Add password protection (Pro feature)', 'pdfviewer'); ?></li>
                        </ol>
                    </div>
                    <div class="bg-card rounded-2xl border border-border p-6">
                        <div class="flex items-center gap-2 mb-4">
                            <span class="flex items-center justify-center w-6 h-6 rounded-full bg-primary text-primary-foreground text-xs font-bold">4</span>
                            <p class="text-sm font-medium text-foreground"><?php esc_html_e('View Your PDF', 'pdfviewer'); ?></p>
                        </div>
                        <ol class="list-decimal list-inside space-y-2 text-muted-foreground ml-4">
                            <li><?php esc_html_e('Click "View PDF" to see the SEO-optimized page', 'pdfviewer'); ?></li>
                            <li><?php esc_html_e('Check /pdf/ to see all your documents', 'pdfviewer'); ?></li>
                            <li><?php esc_html_e('Share the URL - it\'s search engine ready!', 'pdfviewer'); ?></li>
                        </ol>
                    </div>
                </div>

                <!-- Drupal Installation -->
                <div id="panel-drupal" role="tabpanel" aria-labelledby="drupal" class="platform-panel space-y-4 hidden">
                    <div class="bg-card rounded-2xl border border-border p-6">
                        <div class="flex items-center gap-2 mb-4">
                            <span class="flex items-center justify-center w-6 h-6 rounded-full bg-primary text-primary-foreground text-xs font-bold">1</span>
                            <p class="text-sm font-medium text-foreground"><?php esc_html_e('Install the Module', 'pdfviewer'); ?></p>
                        </div>
                        <p class="text-sm text-muted-foreground mb-3"><?php esc_html_e('Manual Installation:', 'pdfviewer'); ?></p>
                        <ol class="list-decimal list-inside space-y-2 text-muted-foreground ml-4 text-sm">
                            <li><?php esc_html_e('Download and extract to modules/contrib/pdf_embed_seo/', 'pdfviewer'); ?></li>
                            <li><?php esc_html_e('Enable via Drush: drush en pdf_embed_seo', 'pdfviewer'); ?></li>
                            <li><?php esc_html_e('Or enable via UI: Admin > Extend > PDF Embed & SEO Optimize', 'pdfviewer'); ?></li>
                        </ol>
                        <p class="text-sm text-muted-foreground mt-4 mb-2"><?php esc_html_e('For Premium features:', 'pdfviewer'); ?></p>
                        <code class="block bg-muted px-4 py-3 rounded-lg font-mono text-primary text-sm ml-4">drush en pdf_embed_seo_premium</code>
                    </div>
                    <div class="bg-card rounded-2xl border border-border p-6">
                        <div class="flex items-center gap-2 mb-4">
                            <span class="flex items-center justify-center w-6 h-6 rounded-full bg-primary text-primary-foreground text-xs font-bold">2</span>
                            <p class="text-sm font-medium text-foreground"><?php esc_html_e('Requirements', 'pdfviewer'); ?></p>
                        </div>
                        <ul class="space-y-2 text-sm text-muted-foreground ml-4">
                            <li><strong class="text-foreground">Drupal:</strong> <?php esc_html_e('10 or 11', 'pdfviewer'); ?></li>
                            <li><strong class="text-foreground">PHP:</strong> <?php esc_html_e('8.1 or higher', 'pdfviewer'); ?></li>
                            <li><strong class="text-foreground"><?php esc_html_e('Dependencies:', 'pdfviewer'); ?></strong> <?php esc_html_e('Node, File, Taxonomy, Path, Path Alias modules', 'pdfviewer'); ?></li>
                            <li><strong class="text-foreground"><?php esc_html_e('Optional:', 'pdfviewer'); ?></strong> <?php esc_html_e('ImageMagick or Ghostscript (for automatic thumbnail generation)', 'pdfviewer'); ?></li>
                        </ul>
                    </div>
                    <div class="bg-card rounded-2xl border border-border p-6">
                        <div class="flex items-center gap-2 mb-4">
                            <span class="flex items-center justify-center w-6 h-6 rounded-full bg-primary text-primary-foreground text-xs font-bold">3</span>
                            <p class="text-sm font-medium text-foreground"><?php esc_html_e('Upload Your First PDF', 'pdfviewer'); ?></p>
                        </div>
                        <ol class="list-decimal list-inside space-y-2 text-muted-foreground ml-4">
                            <li><?php esc_html_e('Go to Content > Add content > PDF Document', 'pdfviewer'); ?></li>
                            <li><?php esc_html_e('Enter a title for your PDF', 'pdfviewer'); ?></li>
                            <li><?php esc_html_e('Upload your PDF file', 'pdfviewer'); ?></li>
                            <li><?php esc_html_e('Add a description for SEO', 'pdfviewer'); ?></li>
                            <li><?php esc_html_e('Save to publish', 'pdfviewer'); ?></li>
                        </ol>
                    </div>
                    <div class="bg-card rounded-2xl border border-border p-6">
                        <div class="flex items-center gap-2 mb-4">
                            <span class="flex items-center justify-center w-6 h-6 rounded-full bg-primary text-primary-foreground text-xs font-bold">4</span>
                            <p class="text-sm font-medium text-foreground"><?php esc_html_e('Configure Module Settings', 'pdfviewer'); ?></p>
                        </div>
                        <ol class="list-decimal list-inside space-y-2 text-muted-foreground ml-4">
                            <li><?php esc_html_e('Go to Configuration > Content > PDF Embed & SEO', 'pdfviewer'); ?></li>
                            <li><?php esc_html_e('Set default viewer options and theme', 'pdfviewer'); ?></li>
                            <li><?php esc_html_e('Configure download/print permissions', 'pdfviewer'); ?></li>
                            <li><?php esc_html_e('Set archive page display style (grid/list)', 'pdfviewer'); ?></li>
                            <li><?php esc_html_e('Enable GDPR IP anonymization (IPv4: zeros last octet, IPv6: zeros last 80 bits)', 'pdfviewer'); ?></li>
                        </ol>
                    </div>
                    <div class="bg-card rounded-2xl border border-border p-6">
                        <div class="flex items-center gap-2 mb-4">
                            <span class="flex items-center justify-center w-6 h-6 rounded-full bg-primary text-primary-foreground text-xs font-bold">5</span>
                            <p class="text-sm font-medium text-foreground"><?php esc_html_e('View Your PDF', 'pdfviewer'); ?></p>
                        </div>
                        <ol class="list-decimal list-inside space-y-2 text-muted-foreground ml-4">
                            <li><?php esc_html_e('Visit /pdf to see the archive', 'pdfviewer'); ?></li>
                            <li><?php esc_html_e('Each PDF gets its own SEO-friendly URL at /pdf/{slug}', 'pdfviewer'); ?></li>
                            <li><?php esc_html_e('Schema.org markup is automatically added', 'pdfviewer'); ?></li>
                            <li><?php esc_html_e('XML Sitemap available at /pdf/sitemap.xml (Premium)', 'pdfviewer'); ?></li>
                        </ol>
                    </div>
                </div>

                <!-- React / Next.js Installation -->
                <div id="panel-react" role="tabpanel" aria-labelledby="react" class="platform-panel space-y-4 hidden">
                    <div class="bg-card rounded-2xl border border-border p-6">
                        <div class="flex items-center gap-2 mb-4">
                            <span class="flex items-center justify-center w-6 h-6 rounded-full bg-primary text-primary-foreground text-xs font-bold">1</span>
                            <p class="text-sm font-medium text-foreground"><?php esc_html_e('Install the Package', 'pdfviewer'); ?></p>
                        </div>
                        <p class="text-sm text-muted-foreground mb-3"><?php esc_html_e('Via npm or yarn:', 'pdfviewer'); ?></p>
                        <div class="space-y-2 ml-4">
                            <code class="block bg-muted px-4 py-3 rounded-lg font-mono text-primary text-sm">npm install @pdf-embed-seo/react</code>
                            <p class="text-xs text-muted-foreground"><?php esc_html_e('or with yarn:', 'pdfviewer'); ?></p>
                            <code class="block bg-muted px-4 py-3 rounded-lg font-mono text-primary text-sm">yarn add @pdf-embed-seo/react</code>
                        </div>
                    </div>
                    <div class="bg-card rounded-2xl border border-border p-6">
                        <div class="flex items-center gap-2 mb-4">
                            <span class="flex items-center justify-center w-6 h-6 rounded-full bg-primary text-primary-foreground text-xs font-bold">2</span>
                            <p class="text-sm font-medium text-foreground"><?php esc_html_e('Import the Component', 'pdfviewer'); ?></p>
                        </div>
                        <p class="text-sm text-muted-foreground mb-3"><?php esc_html_e('In your React component:', 'pdfviewer'); ?></p>
                        <code class="block bg-muted px-4 py-3 rounded-lg font-mono text-primary text-sm">import { PdfViewer } from '@pdf-embed-seo/react';</code>
                    </div>
                    <div class="bg-card rounded-2xl border border-border p-6">
                        <div class="flex items-center gap-2 mb-4">
                            <span class="flex items-center justify-center w-6 h-6 rounded-full bg-primary text-primary-foreground text-xs font-bold">3</span>
                            <p class="text-sm font-medium text-foreground"><?php esc_html_e('Use the Component', 'pdfviewer'); ?></p>
                        </div>
                        <p class="text-sm text-muted-foreground mb-3"><?php esc_html_e('Basic usage example:', 'pdfviewer'); ?></p>
                        <pre class="bg-muted px-4 py-3 rounded-lg font-mono text-primary text-sm overflow-x-auto"><code>&lt;PdfViewer
  src="/documents/annual-report.pdf"
  title="Annual Report 2024"
  height="600px"
  allowDownload={true}
  allowPrint={true}
/&gt;</code></pre>
                    </div>
                    <div class="bg-card rounded-2xl border border-border p-6">
                        <div class="flex items-center gap-2 mb-4">
                            <span class="flex items-center justify-center w-6 h-6 rounded-full bg-primary text-primary-foreground text-xs font-bold">4</span>
                            <p class="text-sm font-medium text-foreground"><?php esc_html_e('Next.js App Router (Optional)', 'pdfviewer'); ?></p>
                        </div>
                        <p class="text-sm text-muted-foreground mb-3"><?php esc_html_e('For Next.js with App Router, use dynamic import:', 'pdfviewer'); ?></p>
                        <pre class="bg-muted px-4 py-3 rounded-lg font-mono text-primary text-sm overflow-x-auto"><code>'use client';
import dynamic from 'next/dynamic';

const PdfViewer = dynamic(
  () => import('@pdf-embed-seo/react').then(mod => mod.PdfViewer),
  { ssr: false }
);</code></pre>
                    </div>
                </div>
            </div>

            <!-- Configuration -->
            <div class="animate-fade-in pt-16" style="animation-delay: 0.1s">
                <div class="flex items-start gap-4 mb-6">
                    <div class="w-12 h-12 rounded-xl gradient-accent flex items-center justify-center shrink-0">
                        <?php echo pdfviewer_icon('settings', 24, 'text-accent-foreground'); ?>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold mb-2"><?php esc_html_e('Configuration', 'pdfviewer'); ?></h2>
                        <p class="text-muted-foreground config-path">
                            <span class="wp-config"><?php esc_html_e('Navigate to: PDF Documents > Settings', 'pdfviewer'); ?></span>
                            <span class="drupal-config hidden"><?php esc_html_e('Navigate to: Admin > Configuration > Content > PDF Embed & SEO', 'pdfviewer'); ?></span>
                        </p>
                    </div>
                </div>

                <div class="overflow-x-auto rounded-2xl border border-border">
                    <table class="w-full min-w-[400px]">
                        <thead>
                            <tr class="bg-muted">
                                <th class="text-left py-3 px-4 font-semibold text-foreground"><?php esc_html_e('Setting', 'pdfviewer'); ?></th>
                                <th class="text-left py-3 px-4 font-semibold text-foreground"><?php esc_html_e('Default', 'pdfviewer'); ?></th>
                                <th class="text-left py-3 px-4 font-semibold text-foreground hidden md:table-cell"><?php esc_html_e('Description', 'pdfviewer'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($config_settings as $index => $row) : ?>
                                <tr class="<?php echo $index % 2 === 0 ? 'bg-background' : 'bg-muted/30'; ?>">
                                    <td class="py-3 px-4 text-foreground font-medium whitespace-nowrap"><?php echo esc_html($row['setting']); ?></td>
                                    <td class="py-3 px-4 text-muted-foreground"><?php echo esc_html($row['default']); ?></td>
                                    <td class="py-3 px-4 text-muted-foreground hidden md:table-cell"><?php echo esc_html($row['description']); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- XML Sitemap -->
            <div class="animate-fade-in pt-16" style="animation-delay: 0.2s">
                <div class="flex items-start gap-4 mb-6">
                    <div class="w-12 h-12 rounded-xl gradient-accent flex items-center justify-center shrink-0">
                        <?php echo pdfviewer_icon('globe', 24, 'text-accent-foreground'); ?>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold mb-2"><?php esc_html_e('Automatic Google Sitemap', 'pdfviewer'); ?></h2>
                        <p class="text-muted-foreground"><?php esc_html_e('Help search engines find all your PDF documents automatically', 'pdfviewer'); ?></p>
                    </div>
                </div>

                <div class="bg-card rounded-2xl border border-border p-6 mb-6">
                    <p class="text-sm font-medium text-muted-foreground mb-3"><?php esc_html_e('Your PDF sitemap is available at:', 'pdfviewer'); ?></p>
                    <code class="block bg-primary/5 px-4 py-3 rounded-lg font-mono text-primary border border-primary/20">yourdomain.com/pdf/sitemap.xml</code>
                </div>

                <p class="text-muted-foreground mb-4">
                    <?php esc_html_e('The plugin automatically creates a special sitemap just for your PDFs. This helps Google and other search engines discover and index all your documents, making them easier for customers to find.', 'pdfviewer'); ?>
                </p>

                <div class="bg-muted/50 rounded-xl p-4 border-l-4 border-accent">
                    <p class="text-sm text-muted-foreground">
                        <strong><?php esc_html_e('Good to know:', 'pdfviewer'); ?></strong>
                        <?php esc_html_e('You can submit this sitemap to Google Search Console to help your documents appear in search results faster.', 'pdfviewer'); ?>
                    </p>
                </div>
            </div>

            <!-- Shortcodes & Blocks -->
            <div class="animate-fade-in pt-16" style="animation-delay: 0.3s">
                <div class="flex items-start gap-4 mb-6">
                    <div class="w-12 h-12 rounded-xl gradient-hero flex items-center justify-center shrink-0">
                        <?php echo pdfviewer_icon('code-2', 24, 'text-primary-foreground'); ?>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold mb-2"><?php esc_html_e('Shortcodes & Blocks', 'pdfviewer'); ?></h2>
                        <p class="text-muted-foreground"><?php esc_html_e('Embed PDFs anywhere on your site', 'pdfviewer'); ?></p>
                    </div>
                </div>

                <!-- WordPress Shortcodes -->
                <div class="wp-shortcodes space-y-6">
                    <!-- Quick Embed Steps -->
                    <div class="bg-primary/5 rounded-2xl border border-primary/20 p-6">
                        <h3 class="font-semibold text-foreground mb-4"><?php esc_html_e('Quick Embed Steps', 'pdfviewer'); ?></h3>
                        <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-4">
                            <div class="flex items-start gap-3">
                                <span class="flex items-center justify-center w-6 h-6 rounded-full bg-primary text-primary-foreground text-xs font-bold shrink-0">1</span>
                                <div>
                                    <p class="text-sm font-medium text-foreground"><?php esc_html_e('Get PDF ID', 'pdfviewer'); ?></p>
                                    <p class="text-xs text-muted-foreground"><?php esc_html_e('Find it in PDF Documents list', 'pdfviewer'); ?></p>
                                </div>
                            </div>
                            <div class="flex items-start gap-3">
                                <span class="flex items-center justify-center w-6 h-6 rounded-full bg-primary text-primary-foreground text-xs font-bold shrink-0">2</span>
                                <div>
                                    <p class="text-sm font-medium text-foreground"><?php esc_html_e('Add Shortcode', 'pdfviewer'); ?></p>
                                    <p class="text-xs text-muted-foreground"><?php esc_html_e('Use [pdf_viewer id="X"]', 'pdfviewer'); ?></p>
                                </div>
                            </div>
                            <div class="flex items-start gap-3">
                                <span class="flex items-center justify-center w-6 h-6 rounded-full bg-primary text-primary-foreground text-xs font-bold shrink-0">3</span>
                                <div>
                                    <p class="text-sm font-medium text-foreground"><?php esc_html_e('Customize Size', 'pdfviewer'); ?></p>
                                    <p class="text-xs text-muted-foreground"><?php esc_html_e('Add width/height attributes', 'pdfviewer'); ?></p>
                                </div>
                            </div>
                            <div class="flex items-start gap-3">
                                <span class="flex items-center justify-center w-6 h-6 rounded-full bg-primary text-primary-foreground text-xs font-bold shrink-0">4</span>
                                <div>
                                    <p class="text-sm font-medium text-foreground"><?php esc_html_e('Publish', 'pdfviewer'); ?></p>
                                    <p class="text-xs text-muted-foreground"><?php esc_html_e('Save and preview your page', 'pdfviewer'); ?></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-card rounded-2xl border border-border p-6">
                        <h3 class="font-semibold text-foreground mb-3">[pdf_viewer]</h3>
                        <p class="text-sm text-muted-foreground mb-4"><?php esc_html_e('Embed a single PDF viewer.', 'pdfviewer'); ?></p>
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead>
                                    <tr class="bg-muted">
                                        <th class="text-left py-2 px-3 font-medium"><?php esc_html_e('Attribute', 'pdfviewer'); ?></th>
                                        <th class="text-left py-2 px-3 font-medium"><?php esc_html_e('Default', 'pdfviewer'); ?></th>
                                        <th class="text-left py-2 px-3 font-medium"><?php esc_html_e('Description', 'pdfviewer'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="border-t border-border">
                                        <td class="py-2 px-3 font-mono text-primary">id</td>
                                        <td class="py-2 px-3 text-muted-foreground"><?php esc_html_e('Current post', 'pdfviewer'); ?></td>
                                        <td class="py-2 px-3 text-muted-foreground"><?php esc_html_e('PDF document ID', 'pdfviewer'); ?></td>
                                    </tr>
                                    <tr class="border-t border-border">
                                        <td class="py-2 px-3 font-mono text-primary">width</td>
                                        <td class="py-2 px-3 text-muted-foreground">100%</td>
                                        <td class="py-2 px-3 text-muted-foreground"><?php esc_html_e('Viewer width', 'pdfviewer'); ?></td>
                                    </tr>
                                    <tr class="border-t border-border">
                                        <td class="py-2 px-3 font-mono text-primary">height</td>
                                        <td class="py-2 px-3 text-muted-foreground">800px</td>
                                        <td class="py-2 px-3 text-muted-foreground"><?php esc_html_e('Viewer height', 'pdfviewer'); ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <code class="block bg-muted px-4 py-3 rounded-lg font-mono text-primary text-sm mt-4">[pdf_viewer id="123" width="100%" height="600px"]</code>
                    </div>

                    <div class="bg-card rounded-2xl border border-border p-6">
                        <h3 class="font-semibold text-foreground mb-3">[pdf_viewer_sitemap]</h3>
                        <p class="text-sm text-muted-foreground mb-4"><?php esc_html_e('Display a list of all PDFs.', 'pdfviewer'); ?></p>
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead>
                                    <tr class="bg-muted">
                                        <th class="text-left py-2 px-3 font-medium"><?php esc_html_e('Attribute', 'pdfviewer'); ?></th>
                                        <th class="text-left py-2 px-3 font-medium"><?php esc_html_e('Default', 'pdfviewer'); ?></th>
                                        <th class="text-left py-2 px-3 font-medium"><?php esc_html_e('Description', 'pdfviewer'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="border-t border-border">
                                        <td class="py-2 px-3 font-mono text-primary">orderby</td>
                                        <td class="py-2 px-3 text-muted-foreground">title</td>
                                        <td class="py-2 px-3 text-muted-foreground"><?php esc_html_e('Sort: title, date, menu_order', 'pdfviewer'); ?></td>
                                    </tr>
                                    <tr class="border-t border-border">
                                        <td class="py-2 px-3 font-mono text-primary">order</td>
                                        <td class="py-2 px-3 text-muted-foreground">ASC</td>
                                        <td class="py-2 px-3 text-muted-foreground"><?php esc_html_e('Direction: ASC, DESC', 'pdfviewer'); ?></td>
                                    </tr>
                                    <tr class="border-t border-border">
                                        <td class="py-2 px-3 font-mono text-primary">limit</td>
                                        <td class="py-2 px-3 text-muted-foreground">-1</td>
                                        <td class="py-2 px-3 text-muted-foreground"><?php esc_html_e('Number of PDFs (-1 for all)', 'pdfviewer'); ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <code class="block bg-muted px-4 py-3 rounded-lg font-mono text-primary text-sm mt-4">[pdf_viewer_sitemap orderby="date" order="DESC" limit="10"]</code>
                    </div>

                    <div class="bg-card rounded-2xl border border-border p-6">
                        <h3 class="font-semibold text-foreground mb-3"><?php esc_html_e('Gutenberg Block', 'pdfviewer'); ?></h3>
                        <ol class="list-decimal list-inside space-y-2 text-muted-foreground">
                            <li><?php esc_html_e('In the block editor, click "+" to add a block', 'pdfviewer'); ?></li>
                            <li><?php esc_html_e('Search for "PDF Viewer"', 'pdfviewer'); ?></li>
                            <li><?php esc_html_e('Select the PDF document from the dropdown', 'pdfviewer'); ?></li>
                            <li><?php esc_html_e('Adjust width/height in block settings', 'pdfviewer'); ?></li>
                        </ol>
                    </div>
                </div>

                <!-- Drupal Blocks & Twig -->
                <div class="drupal-shortcodes space-y-6 hidden">
                    <!-- Quick Embed Steps for Drupal -->
                    <div class="bg-primary/5 rounded-2xl border border-primary/20 p-6">
                        <h3 class="font-semibold text-foreground mb-4"><?php esc_html_e('Quick Embed Steps', 'pdfviewer'); ?></h3>
                        <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-4">
                            <div class="flex items-start gap-3">
                                <span class="flex items-center justify-center w-6 h-6 rounded-full bg-primary text-primary-foreground text-xs font-bold shrink-0">1</span>
                                <div>
                                    <p class="text-sm font-medium text-foreground"><?php esc_html_e('Create PDF', 'pdfviewer'); ?></p>
                                    <p class="text-xs text-muted-foreground"><?php esc_html_e('Add PDF Document content', 'pdfviewer'); ?></p>
                                </div>
                            </div>
                            <div class="flex items-start gap-3">
                                <span class="flex items-center justify-center w-6 h-6 rounded-full bg-primary text-primary-foreground text-xs font-bold shrink-0">2</span>
                                <div>
                                    <p class="text-sm font-medium text-foreground"><?php esc_html_e('Place Block', 'pdfviewer'); ?></p>
                                    <p class="text-xs text-muted-foreground"><?php esc_html_e('Add PDF Viewer block', 'pdfviewer'); ?></p>
                                </div>
                            </div>
                            <div class="flex items-start gap-3">
                                <span class="flex items-center justify-center w-6 h-6 rounded-full bg-primary text-primary-foreground text-xs font-bold shrink-0">3</span>
                                <div>
                                    <p class="text-sm font-medium text-foreground"><?php esc_html_e('Configure', 'pdfviewer'); ?></p>
                                    <p class="text-xs text-muted-foreground"><?php esc_html_e('Select PDF and set options', 'pdfviewer'); ?></p>
                                </div>
                            </div>
                            <div class="flex items-start gap-3">
                                <span class="flex items-center justify-center w-6 h-6 rounded-full bg-primary text-primary-foreground text-xs font-bold shrink-0">4</span>
                                <div>
                                    <p class="text-sm font-medium text-foreground"><?php esc_html_e('Clear Cache', 'pdfviewer'); ?></p>
                                    <p class="text-xs text-muted-foreground"><?php esc_html_e('Run drush cr to apply', 'pdfviewer'); ?></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-card rounded-2xl border border-border p-6">
                        <h3 class="font-semibold text-foreground mb-3"><?php esc_html_e('PDF Viewer Block', 'pdfviewer'); ?></h3>
                        <p class="text-sm text-muted-foreground mb-4"><?php esc_html_e('Add the PDF Viewer block to any page or layout.', 'pdfviewer'); ?></p>
                        <ol class="list-decimal list-inside space-y-2 text-muted-foreground">
                            <li><?php esc_html_e('Go to Structure > Block Layout', 'pdfviewer'); ?></li>
                            <li><?php esc_html_e('Click "Place block" in the desired region', 'pdfviewer'); ?></li>
                            <li><?php esc_html_e('Search for "PDF Viewer"', 'pdfviewer'); ?></li>
                            <li><?php esc_html_e('Configure the block settings and save', 'pdfviewer'); ?></li>
                        </ol>
                    </div>

                    <div class="bg-card rounded-2xl border border-border p-6">
                        <h3 class="font-semibold text-foreground mb-3"><?php esc_html_e('Twig Templates', 'pdfviewer'); ?></h3>
                        <p class="text-sm text-muted-foreground mb-4"><?php esc_html_e('Override templates in your theme for custom styling.', 'pdfviewer'); ?></p>
                        <div class="space-y-3">
                            <div>
                                <code class="block bg-muted px-4 py-3 rounded-lg font-mono text-primary text-sm">pdf-document.html.twig</code>
                                <p class="text-xs text-muted-foreground mt-1"><?php esc_html_e('Single PDF document page template', 'pdfviewer'); ?></p>
                            </div>
                            <div>
                                <code class="block bg-muted px-4 py-3 rounded-lg font-mono text-primary text-sm">pdf-viewer.html.twig</code>
                                <p class="text-xs text-muted-foreground mt-1"><?php esc_html_e('PDF viewer component template', 'pdfviewer'); ?></p>
                            </div>
                            <div>
                                <code class="block bg-muted px-4 py-3 rounded-lg font-mono text-primary text-sm">pdf-archive.html.twig</code>
                                <p class="text-xs text-muted-foreground mt-1"><?php esc_html_e('PDF archive listing template', 'pdfviewer'); ?></p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-card rounded-2xl border border-border p-6">
                        <h3 class="font-semibold text-foreground mb-3"><?php esc_html_e('Render in Twig', 'pdfviewer'); ?></h3>
                        <p class="text-sm text-muted-foreground mb-4"><?php esc_html_e('Embed a PDF viewer programmatically using a preprocess function:', 'pdfviewer'); ?></p>
                        <pre class="bg-muted px-4 py-3 rounded-lg font-mono text-sm overflow-x-auto"><code class="text-primary">// In mytheme.theme or custom module
$build = \Drupal::service('plugin.manager.block')
  ->createInstance('pdf_viewer_block', [
    'pdf_id' => $node->field_pdf->target_id,
  ])->build();

// Then in Twig template:
{{ pdf_viewer }}</code></pre>
                    </div>

                    <div class="bg-card rounded-2xl border border-border p-6">
                        <h3 class="font-semibold text-foreground mb-3"><?php esc_html_e('Permissions', 'pdfviewer'); ?></h3>
                        <p class="text-sm text-muted-foreground mb-4"><?php esc_html_e('Configure at: Admin > People > Permissions', 'pdfviewer'); ?></p>
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead>
                                    <tr class="bg-muted">
                                        <th class="text-left py-2 px-3 font-medium"><?php esc_html_e('Permission', 'pdfviewer'); ?></th>
                                        <th class="text-left py-2 px-3 font-medium"><?php esc_html_e('Description', 'pdfviewer'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="border-t border-border">
                                        <td class="py-2 px-3 font-mono text-primary text-xs">administer pdf embed seo</td>
                                        <td class="py-2 px-3 text-muted-foreground"><?php esc_html_e('Configure global module settings', 'pdfviewer'); ?></td>
                                    </tr>
                                    <tr class="border-t border-border">
                                        <td class="py-2 px-3 font-mono text-primary text-xs">view pdf document</td>
                                        <td class="py-2 px-3 text-muted-foreground"><?php esc_html_e('View published PDFs (public)', 'pdfviewer'); ?></td>
                                    </tr>
                                    <tr class="border-t border-border">
                                        <td class="py-2 px-3 font-mono text-primary text-xs">create pdf document</td>
                                        <td class="py-2 px-3 text-muted-foreground"><?php esc_html_e('Create new documents', 'pdfviewer'); ?></td>
                                    </tr>
                                    <tr class="border-t border-border">
                                        <td class="py-2 px-3 font-mono text-primary text-xs">edit own pdf document</td>
                                        <td class="py-2 px-3 text-muted-foreground"><?php esc_html_e('Edit only own documents', 'pdfviewer'); ?></td>
                                    </tr>
                                    <tr class="border-t border-border">
                                        <td class="py-2 px-3 font-mono text-primary text-xs">view pdf analytics</td>
                                        <td class="py-2 px-3 text-muted-foreground"><?php esc_html_e('Access analytics dashboard (Premium)', 'pdfviewer'); ?></td>
                                    </tr>
                                    <tr class="border-t border-border">
                                        <td class="py-2 px-3 font-mono text-primary text-xs">bypass pdf password</td>
                                        <td class="py-2 px-3 text-muted-foreground"><?php esc_html_e('View protected PDFs without password (Premium)', 'pdfviewer'); ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="bg-card rounded-2xl border border-border p-6">
                        <h3 class="font-semibold text-foreground mb-3"><?php esc_html_e('Services', 'pdfviewer'); ?></h3>
                        <p class="text-sm text-muted-foreground mb-4"><?php esc_html_e('Available Drupal services for custom development:', 'pdfviewer'); ?></p>
                        <ul class="space-y-3 text-sm">
                            <li>
                                <code class="text-primary bg-muted px-2 py-0.5 rounded text-xs">pdf_embed_seo.thumbnail_generator</code>
                                <p class="text-muted-foreground mt-1 ml-4"><?php esc_html_e('Generate thumbnail images from PDF first pages', 'pdfviewer'); ?></p>
                            </li>
                            <li>
                                <code class="text-primary bg-muted px-2 py-0.5 rounded text-xs">pdf_embed_seo.analytics_tracker</code>
                                <p class="text-muted-foreground mt-1 ml-4"><?php esc_html_e('Track and query view/download statistics (Premium)', 'pdfviewer'); ?></p>
                            </li>
                            <li>
                                <code class="text-primary bg-muted px-2 py-0.5 rounded text-xs">pdf_embed_seo.progress_tracker</code>
                                <p class="text-muted-foreground mt-1 ml-4"><?php esc_html_e('Save and retrieve reading progress (Premium)', 'pdfviewer'); ?></p>
                            </li>
                            <li>
                                <code class="text-primary bg-muted px-2 py-0.5 rounded text-xs">pdf_embed_seo.schema_enhancer</code>
                                <p class="text-muted-foreground mt-1 ml-4"><?php esc_html_e('Enhance Schema.org with GEO/AEO/LLM optimizations (Premium)', 'pdfviewer'); ?></p>
                            </li>
                            <li>
                                <code class="text-primary bg-muted px-2 py-0.5 rounded text-xs">pdf_embed_seo.bulk_operations</code>
                                <p class="text-muted-foreground mt-1 ml-4"><?php esc_html_e('Perform bulk import/update operations (Premium)', 'pdfviewer'); ?></p>
                            </li>
                        </ul>
                    </div>

                    <div class="bg-card rounded-2xl border border-border p-6">
                        <h3 class="font-semibold text-foreground mb-3"><?php esc_html_e('JavaScript Events', 'pdfviewer'); ?></h3>
                        <p class="text-sm text-muted-foreground mb-4"><?php esc_html_e('Listen for PDF viewer events in your custom JavaScript:', 'pdfviewer'); ?></p>
                        <pre class="bg-muted px-4 py-3 rounded-lg font-mono text-sm overflow-x-auto"><code class="text-primary">document.addEventListener('pdfLoaded', (e) => {
  console.log('Pages:', e.detail.numPages);
});

document.addEventListener('pageRendered', (e) => {
  console.log('Rendered:', e.detail.pageNumber);
});

document.addEventListener('pageChanged', (e) => {
  console.log('Page:', e.detail.pageNumber);
});

document.addEventListener('zoomChanged', (e) => {
  console.log('Zoom:', e.detail.scale);
});</code></pre>
                    </div>

                    <div class="bg-card rounded-2xl border border-border p-6">
                        <h3 class="font-semibold text-foreground mb-3"><?php esc_html_e('URL Structure', 'pdfviewer'); ?></h3>
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead>
                                    <tr class="bg-muted">
                                        <th class="text-left py-2 px-3 font-medium"><?php esc_html_e('Page', 'pdfviewer'); ?></th>
                                        <th class="text-left py-2 px-3 font-medium"><?php esc_html_e('Path', 'pdfviewer'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="border-t border-border">
                                        <td class="py-2 px-3 text-muted-foreground"><?php esc_html_e('Archive', 'pdfviewer'); ?></td>
                                        <td class="py-2 px-3 font-mono text-primary">/pdf</td>
                                    </tr>
                                    <tr class="border-t border-border">
                                        <td class="py-2 px-3 text-muted-foreground"><?php esc_html_e('Single PDF', 'pdfviewer'); ?></td>
                                        <td class="py-2 px-3 font-mono text-primary">/pdf/{slug}</td>
                                    </tr>
                                    <tr class="border-t border-border">
                                        <td class="py-2 px-3 text-muted-foreground"><?php esc_html_e('XML Sitemap (Premium)', 'pdfviewer'); ?></td>
                                        <td class="py-2 px-3 font-mono text-primary">/pdf/sitemap.xml</td>
                                    </tr>
                                    <tr class="border-t border-border">
                                        <td class="py-2 px-3 text-muted-foreground"><?php esc_html_e('Admin List', 'pdfviewer'); ?></td>
                                        <td class="py-2 px-3 font-mono text-primary">/admin/content/pdf-documents</td>
                                    </tr>
                                    <tr class="border-t border-border">
                                        <td class="py-2 px-3 text-muted-foreground"><?php esc_html_e('Settings', 'pdfviewer'); ?></td>
                                        <td class="py-2 px-3 font-mono text-primary">/admin/config/content/pdf-embed-seo</td>
                                    </tr>
                                    <tr class="border-t border-border">
                                        <td class="py-2 px-3 text-muted-foreground"><?php esc_html_e('Analytics (Premium)', 'pdfviewer'); ?></td>
                                        <td class="py-2 px-3 font-mono text-primary">/admin/reports/pdf-analytics</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- React Component Props -->
                <div class="react-shortcodes space-y-6 hidden">
                    <div class="bg-primary/5 rounded-2xl border border-primary/20 p-6">
                        <h3 class="font-semibold text-foreground mb-4"><?php esc_html_e('Quick Start', 'pdfviewer'); ?></h3>
                        <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-4">
                            <div class="flex items-start gap-3">
                                <span class="flex items-center justify-center w-6 h-6 rounded-full bg-primary text-primary-foreground text-xs font-bold shrink-0">1</span>
                                <div>
                                    <p class="text-sm font-medium text-foreground"><?php esc_html_e('Install Package', 'pdfviewer'); ?></p>
                                    <p class="text-xs text-muted-foreground"><?php esc_html_e('npm install @pdf-embed-seo/react', 'pdfviewer'); ?></p>
                                </div>
                            </div>
                            <div class="flex items-start gap-3">
                                <span class="flex items-center justify-center w-6 h-6 rounded-full bg-primary text-primary-foreground text-xs font-bold shrink-0">2</span>
                                <div>
                                    <p class="text-sm font-medium text-foreground"><?php esc_html_e('Import Component', 'pdfviewer'); ?></p>
                                    <p class="text-xs text-muted-foreground"><?php esc_html_e('import { PdfViewer }', 'pdfviewer'); ?></p>
                                </div>
                            </div>
                            <div class="flex items-start gap-3">
                                <span class="flex items-center justify-center w-6 h-6 rounded-full bg-primary text-primary-foreground text-xs font-bold shrink-0">3</span>
                                <div>
                                    <p class="text-sm font-medium text-foreground"><?php esc_html_e('Add Props', 'pdfviewer'); ?></p>
                                    <p class="text-xs text-muted-foreground"><?php esc_html_e('src, title, height, etc.', 'pdfviewer'); ?></p>
                                </div>
                            </div>
                            <div class="flex items-start gap-3">
                                <span class="flex items-center justify-center w-6 h-6 rounded-full bg-primary text-primary-foreground text-xs font-bold shrink-0">4</span>
                                <div>
                                    <p class="text-sm font-medium text-foreground"><?php esc_html_e('Render', 'pdfviewer'); ?></p>
                                    <p class="text-xs text-muted-foreground"><?php esc_html_e('Component renders PDF viewer', 'pdfviewer'); ?></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-card rounded-2xl border border-border p-6">
                        <h3 class="font-semibold text-foreground mb-3">&lt;PdfViewer /&gt;</h3>
                        <p class="text-sm text-muted-foreground mb-4"><?php esc_html_e('Main component for embedding PDFs in React applications.', 'pdfviewer'); ?></p>
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead>
                                    <tr class="bg-muted">
                                        <th class="text-left py-2 px-3 font-medium"><?php esc_html_e('Prop', 'pdfviewer'); ?></th>
                                        <th class="text-left py-2 px-3 font-medium"><?php esc_html_e('Type', 'pdfviewer'); ?></th>
                                        <th class="text-left py-2 px-3 font-medium"><?php esc_html_e('Default', 'pdfviewer'); ?></th>
                                        <th class="text-left py-2 px-3 font-medium"><?php esc_html_e('Description', 'pdfviewer'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="border-t border-border">
                                        <td class="py-2 px-3 font-mono text-primary">src</td>
                                        <td class="py-2 px-3 text-muted-foreground">string</td>
                                        <td class="py-2 px-3 text-muted-foreground">—</td>
                                        <td class="py-2 px-3 text-muted-foreground"><?php esc_html_e('PDF file URL (required)', 'pdfviewer'); ?></td>
                                    </tr>
                                    <tr class="border-t border-border">
                                        <td class="py-2 px-3 font-mono text-primary">title</td>
                                        <td class="py-2 px-3 text-muted-foreground">string</td>
                                        <td class="py-2 px-3 text-muted-foreground">—</td>
                                        <td class="py-2 px-3 text-muted-foreground"><?php esc_html_e('Document title for accessibility', 'pdfviewer'); ?></td>
                                    </tr>
                                    <tr class="border-t border-border">
                                        <td class="py-2 px-3 font-mono text-primary">width</td>
                                        <td class="py-2 px-3 text-muted-foreground">string</td>
                                        <td class="py-2 px-3 text-muted-foreground">'100%'</td>
                                        <td class="py-2 px-3 text-muted-foreground"><?php esc_html_e('Viewer width', 'pdfviewer'); ?></td>
                                    </tr>
                                    <tr class="border-t border-border">
                                        <td class="py-2 px-3 font-mono text-primary">height</td>
                                        <td class="py-2 px-3 text-muted-foreground">string</td>
                                        <td class="py-2 px-3 text-muted-foreground">'600px'</td>
                                        <td class="py-2 px-3 text-muted-foreground"><?php esc_html_e('Viewer height', 'pdfviewer'); ?></td>
                                    </tr>
                                    <tr class="border-t border-border">
                                        <td class="py-2 px-3 font-mono text-primary">allowDownload</td>
                                        <td class="py-2 px-3 text-muted-foreground">boolean</td>
                                        <td class="py-2 px-3 text-muted-foreground">true</td>
                                        <td class="py-2 px-3 text-muted-foreground"><?php esc_html_e('Show download button', 'pdfviewer'); ?></td>
                                    </tr>
                                    <tr class="border-t border-border">
                                        <td class="py-2 px-3 font-mono text-primary">allowPrint</td>
                                        <td class="py-2 px-3 text-muted-foreground">boolean</td>
                                        <td class="py-2 px-3 text-muted-foreground">true</td>
                                        <td class="py-2 px-3 text-muted-foreground"><?php esc_html_e('Show print button', 'pdfviewer'); ?></td>
                                    </tr>
                                    <tr class="border-t border-border">
                                        <td class="py-2 px-3 font-mono text-primary">initialPage</td>
                                        <td class="py-2 px-3 text-muted-foreground">number</td>
                                        <td class="py-2 px-3 text-muted-foreground">1</td>
                                        <td class="py-2 px-3 text-muted-foreground"><?php esc_html_e('Starting page number', 'pdfviewer'); ?></td>
                                    </tr>
                                    <tr class="border-t border-border">
                                        <td class="py-2 px-3 font-mono text-primary">initialZoom</td>
                                        <td class="py-2 px-3 text-muted-foreground">string</td>
                                        <td class="py-2 px-3 text-muted-foreground">'auto'</td>
                                        <td class="py-2 px-3 text-muted-foreground"><?php esc_html_e("'auto', 'page-fit', 'page-width', or percentage", 'pdfviewer'); ?></td>
                                    </tr>
                                    <tr class="border-t border-border">
                                        <td class="py-2 px-3 font-mono text-primary">className</td>
                                        <td class="py-2 px-3 text-muted-foreground">string</td>
                                        <td class="py-2 px-3 text-muted-foreground">—</td>
                                        <td class="py-2 px-3 text-muted-foreground"><?php esc_html_e('Custom CSS class', 'pdfviewer'); ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <pre class="bg-muted px-4 py-3 rounded-lg font-mono text-primary text-sm mt-4 overflow-x-auto"><code>&lt;PdfViewer
  src="/documents/report.pdf"
  title="Annual Report"
  height="700px"
  allowDownload={true}
  allowPrint={false}
/&gt;</code></pre>
                    </div>
                </div>

            </div>

            <!-- PDF AcroForms / Form Toolbar -->
            <div class="animate-fade-in pt-16" style="animation-delay: 0.35s">
                <div class="flex items-start gap-4 mb-6">
                    <div class="w-12 h-12 rounded-xl gradient-hero flex items-center justify-center shrink-0">
                        <?php echo pdfviewer_icon('file-text', 24, 'text-primary-foreground'); ?>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold mb-2"><?php esc_html_e('PDF AcroForms (Fillable Forms)', 'pdfviewer'); ?></h2>
                        <p class="text-muted-foreground"><?php esc_html_e('Interactive form fields in your PDF viewer', 'pdfviewer'); ?></p>
                    </div>
                </div>

                <!-- What are AcroForms -->
                <div class="bg-card rounded-2xl border border-border p-6 mb-6">
                    <h3 class="font-semibold text-foreground mb-3"><?php esc_html_e('What are AcroForms?', 'pdfviewer'); ?></h3>
                    <p class="text-sm text-muted-foreground mb-4">
                        <?php esc_html_e('AcroForms are interactive form fields embedded in PDF documents. When a PDF contains fillable fields (text inputs, checkboxes, radio buttons, dropdowns), the viewer automatically detects them and displays an interactive form toolbar.', 'pdfviewer'); ?>
                    </p>
                    <p class="text-sm text-muted-foreground">
                        <?php esc_html_e('This feature works with any PDF that contains standard AcroForm fields — no additional configuration is required. Simply upload your fillable PDF and the form toolbar appears automatically.', 'pdfviewer'); ?>
                    </p>
                </div>

                <!-- How It Works -->
                <div class="bg-primary/5 rounded-2xl border border-primary/20 p-6 mb-6">
                    <h3 class="font-semibold text-foreground mb-4"><?php esc_html_e('How It Works', 'pdfviewer'); ?></h3>
                    <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-4">
                        <div class="flex items-start gap-3">
                            <span class="flex items-center justify-center w-6 h-6 rounded-full bg-primary text-primary-foreground text-xs font-bold shrink-0">1</span>
                            <div>
                                <p class="text-sm font-medium text-foreground"><?php esc_html_e('Upload PDF', 'pdfviewer'); ?></p>
                                <p class="text-xs text-muted-foreground"><?php esc_html_e('Upload a PDF with AcroForm fields', 'pdfviewer'); ?></p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <span class="flex items-center justify-center w-6 h-6 rounded-full bg-primary text-primary-foreground text-xs font-bold shrink-0">2</span>
                            <div>
                                <p class="text-sm font-medium text-foreground"><?php esc_html_e('Auto-Detection', 'pdfviewer'); ?></p>
                                <p class="text-xs text-muted-foreground"><?php esc_html_e('Viewer detects form fields automatically', 'pdfviewer'); ?></p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <span class="flex items-center justify-center w-6 h-6 rounded-full bg-primary text-primary-foreground text-xs font-bold shrink-0">3</span>
                            <div>
                                <p class="text-sm font-medium text-foreground"><?php esc_html_e('Fill Fields', 'pdfviewer'); ?></p>
                                <p class="text-xs text-muted-foreground"><?php esc_html_e('Users fill in text, checkboxes, and dropdowns', 'pdfviewer'); ?></p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <span class="flex items-center justify-center w-6 h-6 rounded-full bg-primary text-primary-foreground text-xs font-bold shrink-0">4</span>
                            <div>
                                <p class="text-sm font-medium text-foreground"><?php esc_html_e('Download / Print', 'pdfviewer'); ?></p>
                                <p class="text-xs text-muted-foreground"><?php esc_html_e('Download or print the filled PDF', 'pdfviewer'); ?></p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Toolbar Features -->
                <div class="bg-card rounded-2xl border border-border p-6 mb-6">
                    <h3 class="font-semibold text-foreground mb-4"><?php esc_html_e('Form Toolbar', 'pdfviewer'); ?></h3>
                    <p class="text-sm text-muted-foreground mb-4">
                        <?php esc_html_e('When a PDF with form fields is loaded, a blue toolbar appears below the main viewer toolbar with these controls:', 'pdfviewer'); ?>
                    </p>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="bg-muted">
                                    <th class="text-left py-2 px-3 font-medium"><?php esc_html_e('Button', 'pdfviewer'); ?></th>
                                    <th class="text-left py-2 px-3 font-medium"><?php esc_html_e('Description', 'pdfviewer'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="border-t border-border">
                                    <td class="py-2 px-3 font-medium text-foreground"><?php esc_html_e('Form Fields Detected', 'pdfviewer'); ?></td>
                                    <td class="py-2 px-3 text-muted-foreground"><?php esc_html_e('Label indicating the PDF contains fillable form fields. Toolbar only appears when form fields are found.', 'pdfviewer'); ?></td>
                                </tr>
                                <tr class="border-t border-border">
                                    <td class="py-2 px-3 font-medium text-foreground"><?php esc_html_e('Clear Form', 'pdfviewer'); ?></td>
                                    <td class="py-2 px-3 text-muted-foreground"><?php esc_html_e('Resets all form fields to their default empty state. Useful when users want to start over.', 'pdfviewer'); ?></td>
                                </tr>
                                <tr class="border-t border-border">
                                    <td class="py-2 px-3 font-medium text-foreground"><?php esc_html_e('Download Filled PDF', 'pdfviewer'); ?></td>
                                    <td class="py-2 px-3 text-muted-foreground"><?php esc_html_e('Downloads the PDF with all filled-in form data embedded. Only shown when downloads are enabled for the document.', 'pdfviewer'); ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Supported Field Types -->
                <div class="bg-card rounded-2xl border border-border p-6 mb-6">
                    <h3 class="font-semibold text-foreground mb-4"><?php esc_html_e('Supported Field Types', 'pdfviewer'); ?></h3>
                    <div class="grid sm:grid-cols-2 gap-4">
                        <div class="flex items-start gap-3">
                            <?php echo pdfviewer_icon('type', 16, 'text-primary mt-0.5'); ?>
                            <div>
                                <p class="text-sm font-medium text-foreground"><?php esc_html_e('Text Fields', 'pdfviewer'); ?></p>
                                <p class="text-xs text-muted-foreground"><?php esc_html_e('Single-line and multi-line text inputs for names, addresses, comments, etc.', 'pdfviewer'); ?></p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <?php echo pdfviewer_icon('check-square', 16, 'text-primary mt-0.5'); ?>
                            <div>
                                <p class="text-sm font-medium text-foreground"><?php esc_html_e('Checkboxes', 'pdfviewer'); ?></p>
                                <p class="text-xs text-muted-foreground"><?php esc_html_e('Toggle checkboxes for yes/no options, agreements, and multi-select choices.', 'pdfviewer'); ?></p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <?php echo pdfviewer_icon('circle', 16, 'text-primary mt-0.5'); ?>
                            <div>
                                <p class="text-sm font-medium text-foreground"><?php esc_html_e('Radio Buttons', 'pdfviewer'); ?></p>
                                <p class="text-xs text-muted-foreground"><?php esc_html_e('Single-select option groups where only one choice can be selected.', 'pdfviewer'); ?></p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <?php echo pdfviewer_icon('chevron-down', 16, 'text-primary mt-0.5'); ?>
                            <div>
                                <p class="text-sm font-medium text-foreground"><?php esc_html_e('Dropdown Menus', 'pdfviewer'); ?></p>
                                <p class="text-xs text-muted-foreground"><?php esc_html_e('Select menus with predefined options for structured data entry.', 'pdfviewer'); ?></p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Platform Notes -->
                <div class="grid sm:grid-cols-3 gap-4">
                    <div class="bg-card rounded-2xl border border-border p-5">
                        <div class="flex items-center gap-2 mb-3">
                            <?php echo pdfviewer_icon('wordpress', 20, 'text-primary'); ?>
                            <h4 class="font-semibold text-foreground text-sm"><?php esc_html_e('WordPress', 'pdfviewer'); ?></h4>
                        </div>
                        <p class="text-xs text-muted-foreground"><?php esc_html_e('AcroForms work automatically with any PDF uploaded via the PDF Documents admin. No shortcode changes needed — forms are detected on page load.', 'pdfviewer'); ?></p>
                    </div>
                    <div class="bg-card rounded-2xl border border-border p-5">
                        <div class="flex items-center gap-2 mb-3">
                            <?php echo pdfviewer_icon('droplet', 20, 'text-primary'); ?>
                            <h4 class="font-semibold text-foreground text-sm"><?php esc_html_e('Drupal', 'pdfviewer'); ?></h4>
                        </div>
                        <p class="text-xs text-muted-foreground"><?php esc_html_e('Upload a fillable PDF via the PDF Document entity form. The viewer template includes the annotation layer and form toolbar out of the box.', 'pdfviewer'); ?></p>
                    </div>
                    <div class="bg-card rounded-2xl border border-border p-5">
                        <div class="flex items-center gap-2 mb-3">
                            <?php echo pdfviewer_icon('hexagon', 20, 'text-primary'); ?>
                            <h4 class="font-semibold text-foreground text-sm"><?php esc_html_e('React / Next.js', 'pdfviewer'); ?></h4>
                        </div>
                        <p class="text-xs text-muted-foreground"><?php esc_html_e('The PdfViewer component handles AcroForms automatically. Form fields render as interactive overlays when detected in the loaded PDF.', 'pdfviewer'); ?></p>
                    </div>
                </div>
            </div>

            <!-- REST API Reference -->
            <div class="animate-fade-in pt-16" style="animation-delay: 0.4s">
                <div class="flex items-start gap-4 mb-6">
                    <div class="w-12 h-12 rounded-xl gradient-hero flex items-center justify-center shrink-0">
                        <?php echo pdfviewer_icon('database', 24, 'text-primary-foreground'); ?>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold mb-2"><?php esc_html_e('REST API Reference', 'pdfviewer'); ?></h2>
                        <p class="text-muted-foreground api-base">
                            <span class="wp-api"><?php esc_html_e('Base URL: /wp-json/pdf-embed-seo/v1/', 'pdfviewer'); ?></span>
                            <span class="drupal-api hidden"><?php esc_html_e('Base URL: /api/pdf-embed-seo/v1/', 'pdfviewer'); ?></span>
                        </p>
                    </div>
                </div>

                <div class="space-y-4">
                    <div class="bg-card rounded-2xl border border-border p-6">
                        <h3 class="font-semibold text-foreground mb-3">GET /documents</h3>
                        <p class="text-sm text-muted-foreground mb-4"><?php esc_html_e('List all published PDF documents.', 'pdfviewer'); ?></p>
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead>
                                    <tr class="bg-muted">
                                        <th class="text-left py-2 px-3 font-medium"><?php esc_html_e('Parameter', 'pdfviewer'); ?></th>
                                        <th class="text-left py-2 px-3 font-medium"><?php esc_html_e('Type', 'pdfviewer'); ?></th>
                                        <th class="text-left py-2 px-3 font-medium"><?php esc_html_e('Description', 'pdfviewer'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="border-t border-border">
                                        <td class="py-2 px-3 font-mono text-primary">page</td>
                                        <td class="py-2 px-3 text-muted-foreground">int</td>
                                        <td class="py-2 px-3 text-muted-foreground"><?php esc_html_e('Page number (default: 1)', 'pdfviewer'); ?></td>
                                    </tr>
                                    <tr class="border-t border-border">
                                        <td class="py-2 px-3 font-mono text-primary">per_page</td>
                                        <td class="py-2 px-3 text-muted-foreground">int</td>
                                        <td class="py-2 px-3 text-muted-foreground"><?php esc_html_e('Items per page (max 100)', 'pdfviewer'); ?></td>
                                    </tr>
                                    <tr class="border-t border-border">
                                        <td class="py-2 px-3 font-mono text-primary">search</td>
                                        <td class="py-2 px-3 text-muted-foreground">string</td>
                                        <td class="py-2 px-3 text-muted-foreground"><?php esc_html_e('Search term', 'pdfviewer'); ?></td>
                                    </tr>
                                    <tr class="border-t border-border">
                                        <td class="py-2 px-3 font-mono text-primary">orderby</td>
                                        <td class="py-2 px-3 text-muted-foreground">string</td>
                                        <td class="py-2 px-3 text-muted-foreground"><?php esc_html_e('date, title, modified, views', 'pdfviewer'); ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="bg-card rounded-2xl border border-border p-6">
                        <h3 class="font-semibold text-foreground mb-3">GET /documents/{id}</h3>
                        <p class="text-sm text-muted-foreground mb-4"><?php esc_html_e('Get single PDF document details.', 'pdfviewer'); ?></p>
                        <p class="text-xs text-muted-foreground"><?php esc_html_e('Returns: id, title, slug, url, description, thumbnail, file_size, page_count, view_count, dates', 'pdfviewer'); ?></p>
                    </div>

                    <div class="bg-card rounded-2xl border border-border p-6">
                        <h3 class="font-semibold text-foreground mb-3">GET /documents/{id}/data</h3>
                        <p class="text-sm text-muted-foreground mb-4"><?php esc_html_e('Get secure PDF URL for viewer (AJAX-based).', 'pdfviewer'); ?></p>
                        <p class="text-xs text-muted-foreground"><?php esc_html_e('Returns: success, url (temporary secure URL), expires', 'pdfviewer'); ?></p>
                    </div>

                    <div class="bg-card rounded-2xl border border-border p-6">
                        <h3 class="font-semibold text-foreground mb-3">POST /documents/{id}/view</h3>
                        <p class="text-sm text-muted-foreground"><?php esc_html_e('Track a PDF view (increment view counter).', 'pdfviewer'); ?></p>
                    </div>

                    <div class="bg-card rounded-2xl border border-border p-6">
                        <h3 class="font-semibold text-foreground mb-3">GET /settings</h3>
                        <p class="text-sm text-muted-foreground"><?php esc_html_e('Get public plugin settings (viewer theme, height, permissions).', 'pdfviewer'); ?></p>
                    </div>

                    <div class="bg-card rounded-2xl border border-border p-6">
                        <div class="flex items-center gap-2 mb-3">
                            <h3 class="font-semibold text-foreground"><?php esc_html_e('Premium Endpoints', 'pdfviewer'); ?></h3>
                            <span class="text-xs font-medium px-2 py-1 rounded bg-primary/10 text-primary"><?php esc_html_e('Pro', 'pdfviewer'); ?></span>
                        </div>
                        <ul class="list-disc list-inside space-y-2 text-sm text-muted-foreground">
                            <li><code class="text-primary bg-muted px-2 py-0.5 rounded">GET /analytics</code> <span class="ml-1"><?php esc_html_e('Dashboard summary (total views, unique visitors, popular docs)', 'pdfviewer'); ?></span></li>
                            <li><code class="text-primary bg-muted px-2 py-0.5 rounded">GET /analytics/documents</code> <span class="ml-1"><?php esc_html_e('Per-document analytics', 'pdfviewer'); ?></span></li>
                            <li><code class="text-primary bg-muted px-2 py-0.5 rounded">GET /analytics/export</code> <span class="ml-1"><?php esc_html_e('Export data (CSV/JSON)', 'pdfviewer'); ?></span></li>
                            <li><code class="text-primary bg-muted px-2 py-0.5 rounded">GET /documents/{id}/progress</code> <span class="ml-1"><?php esc_html_e('Get reading progress', 'pdfviewer'); ?></span></li>
                            <li><code class="text-primary bg-muted px-2 py-0.5 rounded">POST /documents/{id}/progress</code> <span class="ml-1"><?php esc_html_e('Save reading progress (page, scroll, zoom)', 'pdfviewer'); ?></span></li>
                            <li><code class="text-primary bg-muted px-2 py-0.5 rounded">POST /documents/{id}/verify-password</code> <span class="ml-1"><?php esc_html_e('Verify password for protected PDFs', 'pdfviewer'); ?></span></li>
                            <li><code class="text-primary bg-muted px-2 py-0.5 rounded">POST /documents/{id}/download</code> <span class="ml-1"><?php esc_html_e('Track PDF download', 'pdfviewer'); ?></span></li>
                            <li><code class="text-primary bg-muted px-2 py-0.5 rounded">POST /documents/{id}/expiring-link</code> <span class="ml-1"><?php esc_html_e('Generate expiring access link', 'pdfviewer'); ?></span></li>
                        </ul>
                    </div>

                    <div class="bg-card rounded-2xl border border-border p-6">
                        <h3 class="font-semibold text-foreground mb-3"><?php esc_html_e('Example Response', 'pdfviewer'); ?></h3>
                        <pre class="bg-muted px-4 py-3 rounded-lg font-mono text-xs overflow-x-auto"><code class="text-primary">{
  "id": 123,
  "title": "Annual Report 2024",
  "slug": "annual-report-2024",
  "url": "https://example.com/pdf/annual-report-2024/",
  "excerpt": "Company annual report...",
  "date": "2024-01-15T10:30:00+00:00",
  "views": 1542,
  "thumbnail": "https://example.com/wp-content/uploads/thumb.jpg"
}</code></pre>
                    </div>
                </div>
            </div>

            <!-- Hooks -->
            <div class="animate-fade-in pt-16" style="animation-delay: 0.5s">
                <div class="flex items-start gap-4 mb-6">
                    <div class="w-12 h-12 rounded-xl gradient-accent flex items-center justify-center shrink-0">
                        <?php echo pdfviewer_icon('puzzle', 24, 'text-accent-foreground'); ?>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold mb-2 hooks-title">
                            <span class="wp-hooks-title"><?php esc_html_e('WordPress Hooks', 'pdfviewer'); ?></span>
                            <span class="drupal-hooks-title hidden"><?php esc_html_e('Drupal Hooks', 'pdfviewer'); ?></span>
                        </h2>
                        <p class="text-muted-foreground"><?php esc_html_e('Extend and customize the plugin functionality', 'pdfviewer'); ?></p>
                    </div>
                </div>

                <!-- WordPress Hooks -->
                <div class="wp-hooks space-y-4">
                    <div class="bg-card rounded-2xl border border-border p-6">
                        <h3 class="font-semibold text-foreground mb-3"><?php esc_html_e('Actions', 'pdfviewer'); ?></h3>
                        <ul class="list-disc list-inside space-y-2 text-sm">
                            <?php foreach ($wp_actions as $action) : ?>
                                <?php if ( ! empty( $action['hook'] ) ) : ?>
                                    <li>
                                        <code class="text-primary bg-muted px-2 py-0.5 rounded"><?php echo esc_html($action['hook']); ?></code>
                                        <span class="text-muted-foreground ml-2"><?php echo esc_html($action['description']); ?></span>
                                    </li>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </ul>
                    </div>

                    <div class="bg-card rounded-2xl border border-border p-6">
                        <h3 class="font-semibold text-foreground mb-3"><?php esc_html_e('Filters', 'pdfviewer'); ?></h3>
                        <ul class="list-disc list-inside space-y-2 text-sm">
                            <?php foreach ($wp_filters as $filter) : ?>
                                <?php if ( ! empty( $filter['hook'] ) ) : ?>
                                    <li>
                                        <code class="text-primary bg-muted px-2 py-0.5 rounded"><?php echo esc_html($filter['hook']); ?></code>
                                        <span class="text-muted-foreground ml-2"><?php echo esc_html($filter['description']); ?></span>
                                    </li>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </ul>
                    </div>

                    <div class="bg-card rounded-2xl border border-border p-6">
                        <h3 class="font-semibold text-foreground mb-3"><?php esc_html_e('Example: Add custom schema data', 'pdfviewer'); ?></h3>
                        <pre class="bg-muted px-4 py-3 rounded-lg font-mono text-sm overflow-x-auto"><code class="text-primary">add_filter( 'pdf_embed_seo_schema_data', function( $schema, $post_id ) {
    $schema['author'] = [
        '@type' => 'Person',
        'name'  => get_post_meta( $post_id, '_pdf_author', true ),
    ];
    return $schema;
}, 10, 2 );</code></pre>
                    </div>
                </div>

                <!-- Drupal Hooks -->
                <div class="drupal-hooks space-y-4 hidden">
                    <div class="bg-card rounded-2xl border border-border p-6">
                        <h3 class="font-semibold text-foreground mb-3"><?php esc_html_e('Alter Hooks', 'pdfviewer'); ?></h3>
                        <ul class="list-disc list-inside space-y-2 text-sm">
                            <li>
                                <code class="text-primary bg-muted px-2 py-0.5 rounded">hook_pdf_embed_seo_document_data_alter</code>
                                <span class="text-muted-foreground ml-2"><?php esc_html_e('Modify API document data before response', 'pdfviewer'); ?></span>
                            </li>
                            <li>
                                <code class="text-primary bg-muted px-2 py-0.5 rounded">hook_pdf_embed_seo_schema_alter</code>
                                <span class="text-muted-foreground ml-2"><?php esc_html_e('Customize Schema.org structured data', 'pdfviewer'); ?></span>
                            </li>
                            <li>
                                <code class="text-primary bg-muted px-2 py-0.5 rounded">hook_pdf_embed_seo_viewer_options_alter</code>
                                <span class="text-muted-foreground ml-2"><?php esc_html_e('Modify PDF.js viewer configuration', 'pdfviewer'); ?></span>
                            </li>
                            <li>
                                <code class="text-primary bg-muted px-2 py-0.5 rounded">hook_pdf_embed_seo_api_settings_alter</code>
                                <span class="text-muted-foreground ml-2"><?php esc_html_e('Modify REST API settings response', 'pdfviewer'); ?></span>
                            </li>
                            <li>
                                <code class="text-primary bg-muted px-2 py-0.5 rounded">hook_pdf_embed_seo_verify_password_alter</code>
                                <span class="text-muted-foreground ml-2"><?php esc_html_e('Override password verification logic (Premium)', 'pdfviewer'); ?></span>
                            </li>
                        </ul>
                    </div>

                    <div class="bg-card rounded-2xl border border-border p-6">
                        <h3 class="font-semibold text-foreground mb-3"><?php esc_html_e('Event & Entity Hooks', 'pdfviewer'); ?></h3>
                        <ul class="list-disc list-inside space-y-2 text-sm">
                            <li>
                                <code class="text-primary bg-muted px-2 py-0.5 rounded">hook_pdf_embed_seo_view_tracked</code>
                                <span class="text-muted-foreground ml-2"><?php esc_html_e('Triggered when a PDF view is tracked', 'pdfviewer'); ?></span>
                            </li>
                            <li>
                                <code class="text-primary bg-muted px-2 py-0.5 rounded">hook_pdf_embed_seo_document_saved</code>
                                <span class="text-muted-foreground ml-2"><?php esc_html_e('Triggered when a PDF document is saved', 'pdfviewer'); ?></span>
                            </li>
                            <li>
                                <code class="text-primary bg-muted px-2 py-0.5 rounded">hook_pdf_document_insert</code>
                                <span class="text-muted-foreground ml-2"><?php esc_html_e('Document created', 'pdfviewer'); ?></span>
                            </li>
                            <li>
                                <code class="text-primary bg-muted px-2 py-0.5 rounded">hook_pdf_document_update</code>
                                <span class="text-muted-foreground ml-2"><?php esc_html_e('Document updated', 'pdfviewer'); ?></span>
                            </li>
                            <li>
                                <code class="text-primary bg-muted px-2 py-0.5 rounded">hook_pdf_document_delete</code>
                                <span class="text-muted-foreground ml-2"><?php esc_html_e('Document deleted', 'pdfviewer'); ?></span>
                            </li>
                        </ul>
                    </div>

                    <div class="bg-card rounded-2xl border border-border p-6">
                        <h3 class="font-semibold text-foreground mb-3"><?php esc_html_e('Preprocessing Hooks', 'pdfviewer'); ?></h3>
                        <ul class="list-disc list-inside space-y-2 text-sm">
                            <li>
                                <code class="text-primary bg-muted px-2 py-0.5 rounded">template_preprocess_pdf_document</code>
                                <span class="text-muted-foreground ml-2"><?php esc_html_e('Preprocess single document variables', 'pdfviewer'); ?></span>
                            </li>
                            <li>
                                <code class="text-primary bg-muted px-2 py-0.5 rounded">template_preprocess_pdf_viewer</code>
                                <span class="text-muted-foreground ml-2"><?php esc_html_e('Preprocess viewer variables', 'pdfviewer'); ?></span>
                            </li>
                            <li>
                                <code class="text-primary bg-muted px-2 py-0.5 rounded">template_preprocess_pdf_archive</code>
                                <span class="text-muted-foreground ml-2"><?php esc_html_e('Preprocess archive listing variables', 'pdfviewer'); ?></span>
                            </li>
                            <li>
                                <code class="text-primary bg-muted px-2 py-0.5 rounded">template_preprocess_pdf_archive_item</code>
                                <span class="text-muted-foreground ml-2"><?php esc_html_e('Preprocess archive item variables', 'pdfviewer'); ?></span>
                            </li>
                        </ul>
                    </div>

                    <div class="bg-card rounded-2xl border border-border p-6">
                        <h3 class="font-semibold text-foreground mb-3"><?php esc_html_e('Example: Custom Schema', 'pdfviewer'); ?></h3>
                        <pre class="bg-muted px-4 py-3 rounded-lg font-mono text-sm overflow-x-auto"><code class="text-primary">/**
 * Implements hook_pdf_embed_seo_schema_alter().
 */
function mymodule_pdf_embed_seo_schema_alter(array &$schema, $document) {
  $schema['author'] = [
    '@type' => 'Person',
    'name' => $document->get('field_author')->value,
  ];
}</code></pre>
                    </div>

                    <div class="bg-card rounded-2xl border border-border p-6">
                        <h3 class="font-semibold text-foreground mb-3"><?php esc_html_e('Example: View Tracking', 'pdfviewer'); ?></h3>
                        <pre class="bg-muted px-4 py-3 rounded-lg font-mono text-sm overflow-x-auto"><code class="text-primary">/**
 * Implements hook_pdf_embed_seo_view_tracked().
 */
function mymodule_pdf_embed_seo_view_tracked($document_id, $analytics_data) {
  \Drupal::logger('mymodule')->info('PDF @id viewed', ['@id' => $document_id]);
}</code></pre>
                    </div>
                </div>

                <!-- React Event Handlers -->
                <div class="react-hooks space-y-4 hidden">
                    <div class="bg-card rounded-2xl border border-border p-6">
                        <h3 class="font-semibold text-foreground mb-3"><?php esc_html_e('Event Callbacks', 'pdfviewer'); ?></h3>
                        <p class="text-sm text-muted-foreground mb-4"><?php esc_html_e('React props for handling PDF viewer events.', 'pdfviewer'); ?></p>
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead>
                                    <tr class="bg-muted">
                                        <th class="text-left py-2 px-3 font-medium"><?php esc_html_e('Prop', 'pdfviewer'); ?></th>
                                        <th class="text-left py-2 px-3 font-medium"><?php esc_html_e('Parameters', 'pdfviewer'); ?></th>
                                        <th class="text-left py-2 px-3 font-medium"><?php esc_html_e('Description', 'pdfviewer'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="border-t border-border">
                                        <td class="py-2 px-3 font-mono text-primary">onLoad</td>
                                        <td class="py-2 px-3 text-muted-foreground">(pdf: PDFDocument) => void</td>
                                        <td class="py-2 px-3 text-muted-foreground"><?php esc_html_e('Called when PDF finishes loading', 'pdfviewer'); ?></td>
                                    </tr>
                                    <tr class="border-t border-border">
                                        <td class="py-2 px-3 font-mono text-primary">onError</td>
                                        <td class="py-2 px-3 text-muted-foreground">(error: Error) => void</td>
                                        <td class="py-2 px-3 text-muted-foreground"><?php esc_html_e('Called on loading or rendering error', 'pdfviewer'); ?></td>
                                    </tr>
                                    <tr class="border-t border-border">
                                        <td class="py-2 px-3 font-mono text-primary">onPageChange</td>
                                        <td class="py-2 px-3 text-muted-foreground">(page: number) => void</td>
                                        <td class="py-2 px-3 text-muted-foreground"><?php esc_html_e('Called when page changes', 'pdfviewer'); ?></td>
                                    </tr>
                                    <tr class="border-t border-border">
                                        <td class="py-2 px-3 font-mono text-primary">onZoomChange</td>
                                        <td class="py-2 px-3 text-muted-foreground">(zoom: number) => void</td>
                                        <td class="py-2 px-3 text-muted-foreground"><?php esc_html_e('Called when zoom level changes', 'pdfviewer'); ?></td>
                                    </tr>
                                    <tr class="border-t border-border">
                                        <td class="py-2 px-3 font-mono text-primary">onDownload</td>
                                        <td class="py-2 px-3 text-muted-foreground">() => void</td>
                                        <td class="py-2 px-3 text-muted-foreground"><?php esc_html_e('Called when download button clicked', 'pdfviewer'); ?></td>
                                    </tr>
                                    <tr class="border-t border-border">
                                        <td class="py-2 px-3 font-mono text-primary">onPrint</td>
                                        <td class="py-2 px-3 text-muted-foreground">() => void</td>
                                        <td class="py-2 px-3 text-muted-foreground"><?php esc_html_e('Called when print button clicked', 'pdfviewer'); ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="bg-card rounded-2xl border border-border p-6">
                        <h3 class="font-semibold text-foreground mb-3"><?php esc_html_e('Example: Analytics Tracking', 'pdfviewer'); ?></h3>
                        <pre class="bg-muted px-4 py-3 rounded-lg font-mono text-sm overflow-x-auto"><code class="text-primary">import { PdfViewer } from '@pdf-embed-seo/react';

export default function TrackedDocument({ src, title }) {
  const handleLoad = (pdf) => {
    analytics.track('pdf_viewed', {
      title,
      pages: pdf.numPages
    });
  };

  const handlePageChange = (page) => {
    analytics.track('pdf_page_viewed', { page, title });
  };

  return (
    &lt;PdfViewer
      src={src}
      title={title}
      onLoad={handleLoad}
      onPageChange={handlePageChange}
      onError={(err) => console.error('PDF Error:', err)}
    /&gt;
  );
}</code></pre>
                    </div>
                </div>
            </div>

            <!-- Theming & Templates -->
            <div class="animate-fade-in pt-16" style="animation-delay: 0.6s">
                <div class="flex items-start gap-4 mb-6">
                    <div class="w-12 h-12 rounded-xl gradient-hero flex items-center justify-center shrink-0">
                        <?php echo pdfviewer_icon('file-text', 24, 'text-primary-foreground'); ?>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold mb-2"><?php esc_html_e('Theming & Templates', 'pdfviewer'); ?></h2>
                        <p class="text-muted-foreground"><?php esc_html_e('Customize the look and feel', 'pdfviewer'); ?></p>
                    </div>
                </div>

                <!-- WordPress Templates -->
                <div class="wp-templates space-y-4">
                    <div class="bg-card rounded-2xl border border-border p-6">
                        <div class="flex items-center gap-2 mb-4">
                            <span class="flex items-center justify-center w-6 h-6 rounded-full bg-primary text-primary-foreground text-xs font-bold">1</span>
                            <p class="text-sm font-medium text-foreground"><?php esc_html_e('Copy Template Files', 'pdfviewer'); ?></p>
                        </div>
                        <ol class="list-decimal list-inside space-y-2 text-muted-foreground ml-4">
                            <li><?php esc_html_e('Navigate to wp-content/plugins/pdf-embed-seo-optimize/templates/', 'pdfviewer'); ?></li>
                            <li><?php esc_html_e('Copy the template file you want to customize', 'pdfviewer'); ?></li>
                            <li><?php esc_html_e('Paste it into your theme folder (e.g., wp-content/themes/your-theme/)', 'pdfviewer'); ?></li>
                        </ol>
                    </div>
                    <div class="bg-card rounded-2xl border border-border p-6">
                        <div class="flex items-center gap-2 mb-4">
                            <span class="flex items-center justify-center w-6 h-6 rounded-full bg-primary text-primary-foreground text-xs font-bold">2</span>
                            <p class="text-sm font-medium text-foreground"><?php esc_html_e('Available Template Files', 'pdfviewer'); ?></p>
                        </div>
                        <ul class="space-y-3 text-sm ml-4">
                            <li class="flex items-start gap-2">
                                <code class="text-primary bg-muted px-2 py-0.5 rounded shrink-0">single-pdf_document.php</code>
                                <span class="text-muted-foreground"><?php esc_html_e('Controls single PDF page layout, viewer placement, and metadata display', 'pdfviewer'); ?></span>
                            </li>
                            <li class="flex items-start gap-2">
                                <code class="text-primary bg-muted px-2 py-0.5 rounded shrink-0">archive-pdf_document.php</code>
                                <span class="text-muted-foreground"><?php esc_html_e('Controls PDF archive/listing page with grid or list view', 'pdfviewer'); ?></span>
                            </li>
                            <li class="flex items-start gap-2">
                                <code class="text-primary bg-muted px-2 py-0.5 rounded shrink-0">content-pdf_document.php</code>
                                <span class="text-muted-foreground"><?php esc_html_e('Individual PDF card in archive listings', 'pdfviewer'); ?></span>
                            </li>
                        </ul>
                    </div>
                    <div class="bg-card rounded-2xl border border-border p-6">
                        <div class="flex items-center gap-2 mb-4">
                            <span class="flex items-center justify-center w-6 h-6 rounded-full bg-primary text-primary-foreground text-xs font-bold">3</span>
                            <p class="text-sm font-medium text-foreground"><?php esc_html_e('Customize with CSS', 'pdfviewer'); ?></p>
                        </div>
                        <p class="text-sm text-muted-foreground mb-3 ml-4"><?php esc_html_e('Add custom styles to your theme\'s style.css:', 'pdfviewer'); ?></p>
                        <pre class="bg-muted px-4 py-3 rounded-lg font-mono text-sm overflow-x-auto"><code class="text-primary">/* Custom PDF viewer styling */
.pdf-viewer-container {
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

/* PDF archive grid */
.pdf-archive-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 1.5rem;
}</code></pre>
                    </div>
                    <div class="bg-card rounded-2xl border border-border p-6">
                        <div class="flex items-center gap-2 mb-4">
                            <span class="flex items-center justify-center w-6 h-6 rounded-full bg-primary text-primary-foreground text-xs font-bold">4</span>
                            <p class="text-sm font-medium text-foreground"><?php esc_html_e('Use Template Tags', 'pdfviewer'); ?></p>
                        </div>
                        <p class="text-sm text-muted-foreground mb-3 ml-4"><?php esc_html_e('Available template tags for your custom templates:', 'pdfviewer'); ?></p>
                        <ul class="space-y-2 text-sm ml-4">
                            <li><code class="text-primary bg-muted px-2 py-0.5 rounded">pdf_embed_seo_get_viewer()</code> <span class="text-muted-foreground">- Output the PDF viewer</span></li>
                            <li><code class="text-primary bg-muted px-2 py-0.5 rounded">pdf_embed_seo_get_thumbnail()</code> <span class="text-muted-foreground">- Get PDF thumbnail URL</span></li>
                            <li><code class="text-primary bg-muted px-2 py-0.5 rounded">pdf_embed_seo_get_download_url()</code> <span class="text-muted-foreground">- Get secure download URL</span></li>
                            <li><code class="text-primary bg-muted px-2 py-0.5 rounded">pdf_embed_seo_get_page_count()</code> <span class="text-muted-foreground">- Get PDF page count</span></li>
                            <li><code class="text-primary bg-muted px-2 py-0.5 rounded">pdf_embed_seo_get_view_count()</code> <span class="text-muted-foreground">- Get view count</span></li>
                        </ul>
                    </div>
                </div>

                <!-- Drupal Templates -->
                <div class="drupal-templates space-y-4 hidden">
                    <div class="bg-card rounded-2xl border border-border p-6">
                        <div class="flex items-center gap-2 mb-4">
                            <span class="flex items-center justify-center w-6 h-6 rounded-full bg-primary text-primary-foreground text-xs font-bold">1</span>
                            <p class="text-sm font-medium text-foreground"><?php esc_html_e('Copy Twig Templates', 'pdfviewer'); ?></p>
                        </div>
                        <ol class="list-decimal list-inside space-y-2 text-muted-foreground ml-4">
                            <li><?php esc_html_e('Navigate to modules/pdf_embed_seo/templates/', 'pdfviewer'); ?></li>
                            <li><?php esc_html_e('Copy the .html.twig file you want to customize', 'pdfviewer'); ?></li>
                            <li><?php esc_html_e('Paste it into your theme\'s templates/ folder', 'pdfviewer'); ?></li>
                            <li><?php esc_html_e('Clear Drupal cache: drush cr', 'pdfviewer'); ?></li>
                        </ol>
                    </div>
                    <div class="bg-card rounded-2xl border border-border p-6">
                        <div class="flex items-center gap-2 mb-4">
                            <span class="flex items-center justify-center w-6 h-6 rounded-full bg-primary text-primary-foreground text-xs font-bold">2</span>
                            <p class="text-sm font-medium text-foreground"><?php esc_html_e('Available Twig Templates', 'pdfviewer'); ?></p>
                        </div>
                        <ul class="space-y-3 text-sm ml-4">
                            <li class="flex items-start gap-2">
                                <code class="text-primary bg-muted px-2 py-0.5 rounded shrink-0">pdf-document.html.twig</code>
                                <span class="text-muted-foreground"><?php esc_html_e('Single PDF document page layout', 'pdfviewer'); ?></span>
                            </li>
                            <li class="flex items-start gap-2">
                                <code class="text-primary bg-muted px-2 py-0.5 rounded shrink-0">pdf-viewer.html.twig</code>
                                <span class="text-muted-foreground"><?php esc_html_e('PDF.js viewer component', 'pdfviewer'); ?></span>
                            </li>
                            <li class="flex items-start gap-2">
                                <code class="text-primary bg-muted px-2 py-0.5 rounded shrink-0">pdf-archive.html.twig</code>
                                <span class="text-muted-foreground"><?php esc_html_e('Archive listing page', 'pdfviewer'); ?></span>
                            </li>
                            <li class="flex items-start gap-2">
                                <code class="text-primary bg-muted px-2 py-0.5 rounded shrink-0">pdf-archive-item.html.twig</code>
                                <span class="text-muted-foreground"><?php esc_html_e('Individual archive item (grid card or list row)', 'pdfviewer'); ?></span>
                            </li>
                            <li class="flex items-start gap-2">
                                <code class="text-primary bg-muted px-2 py-0.5 rounded shrink-0">pdf-password-form.html.twig</code>
                                <span class="text-muted-foreground"><?php esc_html_e('Password protection form (Premium)', 'pdfviewer'); ?></span>
                            </li>
                        </ul>
                    </div>
                    <div class="bg-card rounded-2xl border border-border p-6">
                        <div class="flex items-center gap-2 mb-4">
                            <span class="flex items-center justify-center w-6 h-6 rounded-full bg-primary text-primary-foreground text-xs font-bold">3</span>
                            <p class="text-sm font-medium text-foreground"><?php esc_html_e('Template Variables', 'pdfviewer'); ?></p>
                        </div>
                        <p class="text-sm text-muted-foreground mb-3"><?php esc_html_e('pdf-viewer.html.twig:', 'pdfviewer'); ?></p>
                        <ul class="space-y-1 text-sm ml-4 mb-4">
                            <li><code class="text-primary bg-muted px-1 py-0.5 rounded text-xs">pdf_document</code> <span class="text-muted-foreground">- <?php esc_html_e('The PDF document entity', 'pdfviewer'); ?></span></li>
                            <li><code class="text-primary bg-muted px-1 py-0.5 rounded text-xs">pdf_url</code> <span class="text-muted-foreground">- <?php esc_html_e('Secure URL to the PDF file', 'pdfviewer'); ?></span></li>
                            <li><code class="text-primary bg-muted px-1 py-0.5 rounded text-xs">allow_download</code> <span class="text-muted-foreground">- <?php esc_html_e('Boolean for download permission', 'pdfviewer'); ?></span></li>
                            <li><code class="text-primary bg-muted px-1 py-0.5 rounded text-xs">allow_print</code> <span class="text-muted-foreground">- <?php esc_html_e('Boolean for print permission', 'pdfviewer'); ?></span></li>
                            <li><code class="text-primary bg-muted px-1 py-0.5 rounded text-xs">viewer_theme</code> <span class="text-muted-foreground">- <?php esc_html_e("'light' or 'dark'", 'pdfviewer'); ?></span></li>
                        </ul>
                        <p class="text-sm text-muted-foreground mb-3"><?php esc_html_e('pdf-archive.html.twig:', 'pdfviewer'); ?></p>
                        <ul class="space-y-1 text-sm ml-4">
                            <li><code class="text-primary bg-muted px-1 py-0.5 rounded text-xs">documents</code> <span class="text-muted-foreground">- <?php esc_html_e('Array of PDF document entities', 'pdfviewer'); ?></span></li>
                            <li><code class="text-primary bg-muted px-1 py-0.5 rounded text-xs">pager</code> <span class="text-muted-foreground">- <?php esc_html_e('Pagination render array', 'pdfviewer'); ?></span></li>
                            <li><code class="text-primary bg-muted px-1 py-0.5 rounded text-xs">display_style</code> <span class="text-muted-foreground">- <?php esc_html_e("'grid' or 'list'", 'pdfviewer'); ?></span></li>
                            <li><code class="text-primary bg-muted px-1 py-0.5 rounded text-xs">archive_title</code> <span class="text-muted-foreground">- <?php esc_html_e('Page heading', 'pdfviewer'); ?></span></li>
                            <li><code class="text-primary bg-muted px-1 py-0.5 rounded text-xs">content_alignment</code> <span class="text-muted-foreground">- <?php esc_html_e('Alignment setting', 'pdfviewer'); ?></span></li>
                        </ul>
                    </div>
                    <div class="bg-card rounded-2xl border border-border p-6">
                        <div class="flex items-center gap-2 mb-4">
                            <span class="flex items-center justify-center w-6 h-6 rounded-full bg-primary text-primary-foreground text-xs font-bold">4</span>
                            <p class="text-sm font-medium text-foreground"><?php esc_html_e('Add Custom Styles', 'pdfviewer'); ?></p>
                        </div>
                        <p class="text-sm text-muted-foreground mb-3 ml-4"><?php esc_html_e('Add styles to your theme\'s CSS or create a custom library:', 'pdfviewer'); ?></p>
                        <pre class="bg-muted px-4 py-3 rounded-lg font-mono text-sm overflow-x-auto"><code class="text-primary">/* In your-theme.libraries.yml */
pdf-custom:
  css:
    theme:
      css/pdf-custom.css: {}</code></pre>
                    </div>
                    <div class="bg-card rounded-2xl border border-border p-6">
                        <div class="flex items-center gap-2 mb-4">
                            <span class="flex items-center justify-center w-6 h-6 rounded-full bg-primary text-primary-foreground text-xs font-bold">5</span>
                            <p class="text-sm font-medium text-foreground"><?php esc_html_e('Body CSS Classes', 'pdfviewer'); ?></p>
                        </div>
                        <p class="text-sm text-muted-foreground mb-3 ml-4"><?php esc_html_e('The module adds CSS classes to the body element for page-specific styling:', 'pdfviewer'); ?></p>
                        <ul class="space-y-2 text-sm ml-4">
                            <li><code class="text-primary bg-muted px-2 py-0.5 rounded">.page-pdf</code> <span class="text-muted-foreground">- <?php esc_html_e('Applied to all PDF-related pages', 'pdfviewer'); ?></span></li>
                            <li><code class="text-primary bg-muted px-2 py-0.5 rounded">.page-pdf-document</code> <span class="text-muted-foreground">- <?php esc_html_e('Applied to single PDF document pages', 'pdfviewer'); ?></span></li>
                            <li><code class="text-primary bg-muted px-2 py-0.5 rounded">.page-pdf-archive</code> <span class="text-muted-foreground">- <?php esc_html_e('Applied to PDF archive/listing pages', 'pdfviewer'); ?></span></li>
                        </ul>
                        <p class="text-sm text-muted-foreground mt-4 mb-3 ml-4"><?php esc_html_e('Example usage:', 'pdfviewer'); ?></p>
                        <pre class="bg-muted px-4 py-3 rounded-lg font-mono text-sm overflow-x-auto"><code class="text-primary">/* Hide sidebar on PDF pages */
body.page-pdf .sidebar {
  display: none;
}

/* Full-width viewer on document pages */
body.page-pdf-document .content-wrapper {
  max-width: 100%;
  padding: 0;
}</code></pre>
                    </div>
                </div>

                <!-- React Styling -->
                <div class="react-templates space-y-4 hidden">
                    <div class="bg-card rounded-2xl border border-border p-6">
                        <h3 class="font-semibold text-foreground mb-3"><?php esc_html_e('CSS Customization', 'pdfviewer'); ?></h3>
                        <p class="text-sm text-muted-foreground mb-4"><?php esc_html_e('Style the PDF viewer using CSS classes or the className prop.', 'pdfviewer'); ?></p>
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead>
                                    <tr class="bg-muted">
                                        <th class="text-left py-2 px-3 font-medium"><?php esc_html_e('CSS Class', 'pdfviewer'); ?></th>
                                        <th class="text-left py-2 px-3 font-medium"><?php esc_html_e('Description', 'pdfviewer'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="border-t border-border">
                                        <td class="py-2 px-3 font-mono text-primary">.pdf-viewer</td>
                                        <td class="py-2 px-3 text-muted-foreground"><?php esc_html_e('Main container element', 'pdfviewer'); ?></td>
                                    </tr>
                                    <tr class="border-t border-border">
                                        <td class="py-2 px-3 font-mono text-primary">.pdf-viewer__toolbar</td>
                                        <td class="py-2 px-3 text-muted-foreground"><?php esc_html_e('Top toolbar with controls', 'pdfviewer'); ?></td>
                                    </tr>
                                    <tr class="border-t border-border">
                                        <td class="py-2 px-3 font-mono text-primary">.pdf-viewer__canvas</td>
                                        <td class="py-2 px-3 text-muted-foreground"><?php esc_html_e('PDF rendering canvas', 'pdfviewer'); ?></td>
                                    </tr>
                                    <tr class="border-t border-border">
                                        <td class="py-2 px-3 font-mono text-primary">.pdf-viewer__page</td>
                                        <td class="py-2 px-3 text-muted-foreground"><?php esc_html_e('Individual page container', 'pdfviewer'); ?></td>
                                    </tr>
                                    <tr class="border-t border-border">
                                        <td class="py-2 px-3 font-mono text-primary">.pdf-viewer__loading</td>
                                        <td class="py-2 px-3 text-muted-foreground"><?php esc_html_e('Loading spinner overlay', 'pdfviewer'); ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="bg-card rounded-2xl border border-border p-6">
                        <h3 class="font-semibold text-foreground mb-3"><?php esc_html_e('Custom Styling Example', 'pdfviewer'); ?></h3>
                        <pre class="bg-muted px-4 py-3 rounded-lg font-mono text-sm overflow-x-auto"><code class="text-primary">/* styles.css */
.my-pdf-viewer {
  border-radius: 12px;
  overflow: hidden;
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
}

.my-pdf-viewer .pdf-viewer__toolbar {
  background: linear-gradient(to right, #3b82f6, #1d4ed8);
  color: white;
}

.my-pdf-viewer .pdf-viewer__canvas {
  background: #f8fafc;
}</code></pre>
                        <pre class="bg-muted px-4 py-3 rounded-lg font-mono text-sm overflow-x-auto mt-4"><code class="text-primary">// Component usage
&lt;PdfViewer
  src="/document.pdf"
  className="my-pdf-viewer"
/&gt;</code></pre>
                    </div>

                    <div class="bg-card rounded-2xl border border-border p-6">
                        <h3 class="font-semibold text-foreground mb-3"><?php esc_html_e('CSS Variables', 'pdfviewer'); ?></h3>
                        <p class="text-sm text-muted-foreground mb-4"><?php esc_html_e('Override these CSS custom properties to customize colors globally:', 'pdfviewer'); ?></p>
                        <pre class="bg-muted px-4 py-3 rounded-lg font-mono text-sm overflow-x-auto"><code class="text-primary">:root {
  --pdf-viewer-primary: #3b82f6;
  --pdf-viewer-background: #ffffff;
  --pdf-viewer-toolbar-bg: #f1f5f9;
  --pdf-viewer-border-color: #e2e8f0;
  --pdf-viewer-text-color: #1e293b;
}</code></pre>
                    </div>
                </div>

            </div>

            <!-- Developer Documentation -->
            <div id="developer-docs" class="animate-fade-in pt-16" style="animation-delay: 0.65s">
                <div class="flex items-start gap-4 mb-6">
                    <div class="w-12 h-12 rounded-xl bg-purple-500/20 flex items-center justify-center shrink-0">
                        <?php echo pdfviewer_icon('code-2', 24, 'text-purple-600'); ?>
                    </div>
                    <div>
                        <div class="flex items-center gap-3">
                            <h2 class="text-2xl font-bold"><?php esc_html_e('Developer Documentation', 'pdfviewer'); ?></h2>
                            <span class="text-xs font-medium px-2 py-1 rounded bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-300"><?php esc_html_e('For Developers', 'pdfviewer'); ?></span>
                        </div>
                        <p class="text-muted-foreground"><?php esc_html_e('Extend and integrate PDF Embed with hooks, REST API, and webhooks', 'pdfviewer'); ?></p>
                    </div>
                </div>

                <div class="space-y-12">
                    <!-- Premium Feature Classes -->
                    <div class="wp-hooks">
                        <h3 class="text-xl font-bold text-foreground mb-4 flex items-center gap-2">
                            <?php echo pdfviewer_icon('file-code', 20, 'text-purple-600'); ?>
                            <?php esc_html_e('Premium Feature Classes', 'pdfviewer'); ?>
                        </h3>
                        <?php
                        $premium_classes = array(
                            array('feature' => 'Analytics Dashboard', 'class' => 'Premium_Analytics', 'description' => 'Dashboard widget, view tracking, unique visitors, time spent'),
                            array('feature' => 'Password Protection', 'class' => 'Premium_Password', 'description' => 'Per-PDF password with hashed storage, brute-force protection'),
                            array('feature' => 'Detailed View Tracking', 'class' => 'Premium_Tracking', 'description' => 'IP, user agent, referrer tracking'),
                            array('feature' => 'Reading Progress', 'class' => 'Premium_Progress', 'description' => 'Save/resume reading position, page, scroll, zoom'),
                            array('feature' => 'XML Sitemap', 'class' => 'Premium_Sitemap', 'description' => 'Dedicated sitemap at /pdf/sitemap.xml with XSL styling'),
                            array('feature' => 'PDF Categories & Tags', 'class' => 'Premium_Taxonomies', 'description' => 'Hierarchical categories, flat tags, archive filtering'),
                            array('feature' => 'CSV/JSON Export', 'class' => 'Premium_Export', 'description' => 'Export analytics data in CSV or JSON format'),
                            array('feature' => 'Role-Based Access', 'class' => 'Premium_Roles', 'description' => 'Require login, limit by user role'),
                            array('feature' => 'Bulk Import', 'class' => 'Premium_Bulk', 'description' => 'Import multiple PDFs via CSV/ZIP with category assignment'),
                            array('feature' => 'Full REST API', 'class' => 'Premium_API', 'description' => 'Complete API access for integrations'),
                            array('feature' => 'White-Label Options', 'class' => 'Premium_WhiteLabel', 'description' => 'Remove branding, customize viewer appearance'),
                        );
                        ?>
                        <div class="overflow-x-auto rounded-2xl border border-border">
                            <table class="w-full min-w-[500px]" role="table">
                                <thead>
                                    <tr class="bg-muted">
                                        <th class="text-left py-3 px-4 font-semibold text-foreground" scope="col"><?php esc_html_e('Feature', 'pdfviewer'); ?></th>
                                        <th class="text-left py-3 px-4 font-semibold text-foreground" scope="col"><?php esc_html_e('Class', 'pdfviewer'); ?></th>
                                        <th class="text-left py-3 px-4 font-semibold text-foreground hidden md:table-cell" scope="col"><?php esc_html_e('Description', 'pdfviewer'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($premium_classes as $index => $item) : ?>
                                        <tr class="<?php echo $index % 2 === 0 ? 'bg-background' : 'bg-muted/30'; ?>">
                                            <td class="py-3 px-4 text-foreground font-medium"><?php echo esc_html($item['feature']); ?></td>
                                            <td class="py-3 px-4"><code class="text-primary font-mono text-sm"><?php echo esc_html($item['class']); ?></code></td>
                                            <td class="py-3 px-4 text-muted-foreground hidden md:table-cell"><?php echo esc_html($item['description']); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Extended WordPress Hooks with Examples -->
                    <div class="wp-hooks mt-10">
                        <h3 class="text-xl font-bold text-foreground mb-4 flex items-center gap-2">
                            <?php echo pdfviewer_icon('code-2', 20, 'text-blue-600'); ?>
                            <?php esc_html_e('WordPress Hooks (Extended)', 'pdfviewer'); ?>
                        </h3>
                        <?php
                        $extended_hooks = array(
                            array(
                                'name' => 'pdf_embed_seo_pdf_viewed',
                                'type' => 'action',
                                'description' => 'Fires after a PDF is viewed with post ID and view count',
                                'example' => "add_action( 'pdf_embed_seo_pdf_viewed', function( \$post_id, \$count ) {\n    // Your code here\n}, 10, 2 );",
                            ),
                            array(
                                'name' => 'pdf_embed_seo_schema_data',
                                'type' => 'filter',
                                'description' => 'Modify Schema.org DigitalDocument data for a single PDF',
                                'example' => "add_filter( 'pdf_embed_seo_schema_data', function( \$schema, \$post_id ) {\n    \$schema['author'] = [\n        '@type' => 'Person',\n        'name'  => get_post_meta( \$post_id, '_author', true ),\n    ];\n    return \$schema;\n}, 10, 2 );",
                            ),
                            array(
                                'name' => 'pdf_embed_seo_viewer_options',
                                'type' => 'filter',
                                'description' => 'Modify PDF.js viewer options',
                                'example' => "add_filter( 'pdf_embed_seo_viewer_options', function( \$options, \$post_id ) {\n    \$options['defaultZoom'] = 'page-fit';\n    return \$options;\n}, 10, 2 );",
                            ),
                            array(
                                'name' => 'pdf_embed_seo_rest_document',
                                'type' => 'filter',
                                'description' => 'Modify REST API document response',
                                'example' => "add_filter( 'pdf_embed_seo_rest_document', function( \$data, \$post, \$detailed ) {\n    \$data['custom_field'] = get_post_meta( \$post->ID, '_custom', true );\n    return \$data;\n}, 10, 3 );",
                            ),
                        );
                        ?>
                        <div class="space-y-4">
                            <?php foreach ($extended_hooks as $hook) : ?>
                                <div class="bg-card rounded-xl border border-border p-4">
                                    <div class="flex flex-wrap items-center gap-2 mb-2">
                                        <code class="text-foreground font-mono font-semibold text-sm break-all"><?php echo esc_html($hook['name']); ?></code>
                                        <span class="text-xs font-medium px-2 py-0.5 rounded shrink-0 <?php echo $hook['type'] === 'action' ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300' : 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300'; ?>">
                                            <?php echo esc_html($hook['type']); ?>
                                        </span>
                                    </div>
                                    <p class="text-muted-foreground text-sm mb-3"><?php echo esc_html($hook['description']); ?></p>
                                    <pre class="bg-muted px-3 py-3 rounded-lg font-mono text-xs overflow-x-auto"><code class="text-foreground"><?php echo esc_html($hook['example']); ?></code></pre>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- REST API Reference -->
                    <div class="mt-10">
                        <h3 class="text-xl font-bold text-foreground mb-4 flex items-center gap-2">
                            <?php echo pdfviewer_icon('database', 20, 'text-amber-600'); ?>
                            <?php esc_html_e('REST API Reference', 'pdfviewer'); ?>
                            <span class="text-sm font-normal text-muted-foreground">/wp-json/pdf-embed-seo/v1/</span>
                        </h3>
                        <?php
                        $api_endpoints = array(
                            array('method' => 'GET', 'endpoint' => '/documents', 'description' => 'List all published PDF documents', 'tier' => ''),
                            array('method' => 'GET', 'endpoint' => '/documents/{id}', 'description' => 'Get single PDF document details', 'tier' => ''),
                            array('method' => 'GET', 'endpoint' => '/documents/{id}/data', 'description' => 'Get PDF file URL securely (for viewer)', 'tier' => ''),
                            array('method' => 'GET', 'endpoint' => '/analytics', 'description' => 'Get analytics overview (requires admin)', 'tier' => 'Premium'),
                            array('method' => 'POST', 'endpoint' => '/documents/{id}/expiring-link', 'description' => 'Generate expiring access link', 'tier' => 'Premium'),
                            array('method' => 'POST', 'endpoint' => '/bulk/import', 'description' => 'Bulk import PDFs via CSV/ZIP', 'tier' => 'Premium'),
                        );
                        ?>
                        <div class="overflow-x-auto rounded-2xl border border-border">
                            <table class="w-full min-w-[500px]" role="table">
                                <thead>
                                    <tr class="bg-muted">
                                        <th class="text-left py-3 px-4 font-semibold text-foreground" scope="col"><?php esc_html_e('Method', 'pdfviewer'); ?></th>
                                        <th class="text-left py-3 px-4 font-semibold text-foreground" scope="col"><?php esc_html_e('Endpoint', 'pdfviewer'); ?></th>
                                        <th class="text-left py-3 px-4 font-semibold text-foreground hidden md:table-cell" scope="col"><?php esc_html_e('Description', 'pdfviewer'); ?></th>
                                        <th class="text-left py-3 px-4 font-semibold text-foreground" scope="col"><?php esc_html_e('Tier', 'pdfviewer'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($api_endpoints as $index => $endpoint) : ?>
                                        <tr class="<?php echo $index % 2 === 0 ? 'bg-background' : 'bg-muted/30'; ?>">
                                            <td class="py-3 px-4">
                                                <span class="text-xs font-bold px-2 py-1 rounded <?php echo $endpoint['method'] === 'GET' ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300' : 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300'; ?>">
                                                    <?php echo esc_html($endpoint['method']); ?>
                                                </span>
                                            </td>
                                            <td class="py-3 px-4"><code class="text-foreground font-mono text-xs"><?php echo esc_html($endpoint['endpoint']); ?></code></td>
                                            <td class="py-3 px-4 text-muted-foreground hidden md:table-cell"><?php echo esc_html($endpoint['description']); ?></td>
                                            <td class="py-3 px-4">
                                                <?php if ($endpoint['tier']) : ?>
                                                    <span class="text-xs font-medium px-2 py-0.5 rounded bg-primary/10 text-primary"><?php echo esc_html($endpoint['tier']); ?></span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- JavaScript Events -->
                    <div class="mt-10">
                        <h3 class="text-xl font-bold text-foreground mb-4 flex items-center gap-2">
                            <?php echo pdfviewer_icon('zap', 20, 'text-emerald-600'); ?>
                            <?php esc_html_e('JavaScript Events', 'pdfviewer'); ?>
                        </h3>
                        <p class="text-muted-foreground mb-4"><?php esc_html_e('Listen for viewer events to build custom integrations.', 'pdfviewer'); ?></p>
                        <?php
                        $js_events = array(
                            array('event' => 'pdfLoaded', 'description' => 'PDF document loaded successfully'),
                            array('event' => 'pageRendered', 'description' => 'A page has been rendered'),
                            array('event' => 'pageChanged', 'description' => 'User navigated to a different page'),
                            array('event' => 'zoomChanged', 'description' => 'Zoom level was changed'),
                        );
                        ?>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <?php foreach ($js_events as $event) : ?>
                                <article class="bg-card rounded-xl border border-border p-4">
                                    <code class="text-emerald-600 dark:text-emerald-400 font-mono font-semibold text-sm"><?php echo esc_html($event['event']); ?></code>
                                    <p class="text-muted-foreground text-sm mt-1"><?php echo esc_html($event['description']); ?></p>
                                </article>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Premium Features -->
            <div class="animate-fade-in pt-16" style="animation-delay: 0.7s">
                <div class="flex items-start gap-4 mb-6">
                    <div class="w-12 h-12 rounded-xl bg-purple-500/20 flex items-center justify-center shrink-0">
                        <?php echo pdfviewer_icon('zap', 24, 'text-purple-600'); ?>
                    </div>
                    <div>
                        <div class="flex items-center gap-3">
                            <h2 class="text-2xl font-bold"><?php esc_html_e('Premium Features', 'pdfviewer'); ?></h2>
                            <span class="text-xs font-medium px-2 py-1 rounded bg-primary/10 text-primary"><?php esc_html_e('Pro', 'pdfviewer'); ?></span>
                        </div>
                        <p class="text-muted-foreground"><?php esc_html_e('Advanced functionality for Pro users', 'pdfviewer'); ?></p>
                    </div>
                </div>

                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <?php foreach ($premium_features as $feature) : ?>
                        <div class="bg-card rounded-2xl border border-border p-6">
                            <div class="flex items-center gap-3 mb-3">
                                <?php echo pdfviewer_icon($feature['icon'], 20, 'text-primary'); ?>
                                <h3 class="font-semibold text-foreground"><?php echo esc_html($feature['title']); ?></h3>
                            </div>
                            <ul class="list-disc list-inside text-sm text-muted-foreground space-y-1">
                                <?php foreach ($feature['items'] as $item) : ?>
                                    <li><?php echo esc_html($item); ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="flex flex-wrap justify-center gap-4 mt-8">
                    <a href="<?php echo esc_url(home_url('/pro/#comparison')); ?>" class="inline-flex items-center justify-center gap-2 px-6 py-3 rounded-xl font-semibold text-sm bg-primary text-primary-foreground hover:bg-primary/90 transition-colors shadow-md min-w-[220px]">
                        <?php echo pdfviewer_icon('zap', 18); ?>
                        <?php esc_html_e('View All Pro Features', 'pdfviewer'); ?>
                    </a>
                    <a href="#developer-docs" class="inline-flex items-center justify-center gap-2 px-6 py-3 rounded-xl font-semibold text-sm border-2 border-border bg-background text-foreground hover:bg-muted transition-colors min-w-[220px]">
                        <?php echo pdfviewer_icon('code-2', 18); ?>
                        <?php esc_html_e('Developer Docs', 'pdfviewer'); ?>
                    </a>
                </div>
            </div>

        </div>
    </div>
</section>

<script>
// Platform tabs functionality
document.addEventListener('DOMContentLoaded', function() {
    const tabs = document.querySelectorAll('.platform-tab');
    const wpElements = document.querySelectorAll('.wp-config, .wp-api, .wp-hooks-title, .wp-hooks, .wp-templates, .wp-shortcodes, #panel-wordpress');
    const drupalElements = document.querySelectorAll('.drupal-config, .drupal-api, .drupal-hooks-title, .drupal-hooks, .drupal-templates, .drupal-shortcodes, #panel-drupal');
    const reactElements = document.querySelectorAll('#panel-react, .react-shortcodes, .react-hooks, .react-templates');
    const wpDrupalOnlySections = document.querySelectorAll('.config-path, .wp-shortcodes, .drupal-shortcodes, .wp-hooks, .drupal-hooks, .wp-templates, .drupal-templates, .react-shortcodes, .react-hooks, .react-templates');

    // Check URL hash on load and initialize content visibility
    const hash = window.location.hash.replace('#', '');
    const initialPlatform = hash || 'wordpress';

    if (initialPlatform === 'drupal') {
        switchToDrupal();
    } else if (initialPlatform === 'react') {
        switchToReact();
    } else {
        // Ensure WordPress content is visible on initial load
        switchToWordPress();
    }

    tabs.forEach(function(tab) {
        tab.addEventListener('click', function() {
            const platform = this.getAttribute('data-platform');

            // Update tab styles
            tabs.forEach(function(t) {
                t.classList.remove('bg-primary', 'text-primary-foreground');
                t.classList.add('bg-muted', 'text-muted-foreground');
                t.setAttribute('aria-selected', 'false');
            });
            this.classList.remove('bg-muted', 'text-muted-foreground');
            this.classList.add('bg-primary', 'text-primary-foreground');
            this.setAttribute('aria-selected', 'true');

            // Show/hide content
            if (platform === 'drupal') {
                switchToDrupal();
            } else if (platform === 'react') {
                switchToReact();
            } else {
                switchToWordPress();
            }

            // Update URL hash
            history.replaceState(null, null, '#' + platform);
        });
    });

    function switchToWordPress() {
        wpElements.forEach(function(el) {
            el.classList.remove('hidden');
            el.style.setProperty('display', 'block', 'important');
        });
        drupalElements.forEach(function(el) {
            el.classList.add('hidden');
            el.style.setProperty('display', 'none', 'important');
        });
        reactElements.forEach(function(el) {
            el.classList.add('hidden');
            el.style.setProperty('display', 'none', 'important');
        });
        wpDrupalOnlySections.forEach(function(el) {
            el.style.removeProperty('display');
        });
        updateTabState('wordpress');
    }

    function switchToDrupal() {
        wpElements.forEach(function(el) {
            el.classList.add('hidden');
            el.style.setProperty('display', 'none', 'important');
        });
        drupalElements.forEach(function(el) {
            el.classList.remove('hidden');
            el.style.setProperty('display', 'block', 'important');
        });
        reactElements.forEach(function(el) {
            el.classList.add('hidden');
            el.style.setProperty('display', 'none', 'important');
        });
        wpDrupalOnlySections.forEach(function(el) {
            el.style.removeProperty('display');
        });
        updateTabState('drupal');
    }

    function switchToReact() {
        wpElements.forEach(function(el) {
            el.classList.add('hidden');
            el.style.setProperty('display', 'none', 'important');
        });
        drupalElements.forEach(function(el) {
            el.classList.add('hidden');
            el.style.setProperty('display', 'none', 'important');
        });
        reactElements.forEach(function(el) {
            el.classList.remove('hidden');
            el.style.setProperty('display', 'block', 'important');
        });
        updateTabState('react');
    }

    function updateTabState(platform) {
        tabs.forEach(function(t) {
            if (t.getAttribute('data-platform') === platform) {
                t.classList.remove('bg-muted', 'text-muted-foreground');
                t.classList.add('bg-primary', 'text-primary-foreground');
                t.setAttribute('aria-selected', 'true');
            } else {
                t.classList.remove('bg-primary', 'text-primary-foreground');
                t.classList.add('bg-muted', 'text-muted-foreground');
                t.setAttribute('aria-selected', 'false');
            }
        });
    }
});
</script>

<?php get_footer(); ?>
