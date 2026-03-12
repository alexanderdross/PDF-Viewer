<?php
/**
 * Template Name: Drupal PDF Viewer
 * Drupal PDF Viewer landing page
 *
 * @package PDFViewer
 */

get_header();

$requirements = array(
    'Drupal 10 or 11',
    'PHP 8.1 or higher',
    'Optional: ImageMagick or Ghostscript (for auto thumbnails)',
);

$viewer_features = array(
    array(
        'icon'        => 'file-text',
        'title'       => 'Desktop Optimized',
        'description' => 'Full-featured viewer with zoom, search, page navigation, and document outline on desktop browsers.',
    ),
    array(
        'icon'        => 'file-text',
        'title'       => 'Mobile Responsive',
        'description' => 'Touch-friendly controls and responsive layout that works perfectly on phones.',
    ),
    array(
        'icon'        => 'file-text',
        'title'       => 'Tablet Ready',
        'description' => 'Optimized for iPad and Android tablets with gesture support.',
    ),
);
?>

<!-- Hero -->
<section class="py-16 lg:py-24 bg-card" aria-labelledby="viewer-hero-heading">
    <div class="container mx-auto px-4 lg:px-8">
        <div class="max-w-4xl mx-auto text-center">
            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-primary/20 text-foreground text-sm font-medium mb-6">
                <?php echo pdfviewer_icon('file-text', 16); ?>
                <span><?php esc_html_e('Drupal PDF Viewer', 'pdfviewer'); ?></span>
            </div>
            <h1 id="viewer-hero-heading" class="text-4xl md:text-5xl lg:text-6xl font-extrabold tracking-tight leading-tight mb-6">
                <?php esc_html_e('The Best', 'pdfviewer'); ?> <span class="text-gradient"><?php esc_html_e('Drupal PDF Viewer', 'pdfviewer'); ?></span> <?php esc_html_e('Module', 'pdfviewer'); ?>
            </h1>
            <p class="text-xl md:text-2xl text-muted-foreground max-w-3xl mx-auto mb-6">
                <?php esc_html_e('Display PDFs beautifully on any device with Mozilla\'s trusted PDF.js technology. Free, SEO-optimized, and works with every Drupal theme.', 'pdfviewer'); ?>
            </p>
            <div class="flex flex-wrap items-center justify-center gap-3 mb-10">
                <a href="https://www.acquia.com?ref=pdfviewer" target="_blank" rel="noopener" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-card border border-border text-sm hover:border-primary/30 transition-colors" title="<?php esc_attr_e('Visit Acquia — the leading Drupal enterprise platform', 'pdfviewer'); ?>">
                    <?php echo pdfviewer_icon('shield', 16, 'text-primary'); ?>
                    <span class="font-medium text-foreground"><?php esc_html_e('Reviewed by Acquia', 'pdfviewer'); ?></span>
                </a>
            </div>
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                <a href="#download-drupal"
                   class="btn btn-primary btn-lg gradient-hero shadow-glow gap-2 min-w-[240px] justify-center">
                    <?php echo pdfviewer_icon('download', 20); ?>
                    <?php esc_html_e('Download Free Module', 'pdfviewer'); ?>
                </a>
                <a href="<?php echo esc_url(home_url('/examples/')); ?>" class="btn btn-outline btn-lg gap-2 min-w-[240px] justify-center">
                    <?php esc_html_e('View Live Demo', 'pdfviewer'); ?>
                    <?php echo pdfviewer_icon('arrow-right', 20); ?>
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Requirements & Installation -->
<section class="py-16 lg:py-24" aria-labelledby="requirements-heading">
    <div class="container mx-auto px-4 lg:px-8">
        <div class="max-w-4xl mx-auto">
            <div class="grid md:grid-cols-2 gap-8">
                <!-- Requirements -->
                <div class="bg-card rounded-2xl p-6 border border-border">
                    <h2 id="requirements-heading" class="text-xl font-bold mb-4"><?php esc_html_e('Requirements', 'pdfviewer'); ?></h2>
                    <ul class="space-y-3 list-none">
                        <?php foreach ($requirements as $req) : ?>
                            <li class="flex items-start gap-2">
                                <?php echo pdfviewer_icon('check', 18, 'text-primary shrink-0 mt-0.5'); ?>
                                <span class="text-muted-foreground"><?php echo esc_html($req); ?></span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>

                <!-- Installation -->
                <div class="bg-card rounded-2xl p-6 border border-border">
                    <h2 class="text-xl font-bold mb-4"><?php esc_html_e('Installation', 'pdfviewer'); ?></h2>
                    <div class="space-y-4">
                        <div>
                            <p class="text-sm font-medium text-foreground mb-2 flex items-center gap-2">
                                <?php echo pdfviewer_icon('file-text', 16); ?>
                                <?php esc_html_e('Via Composer (recommended):', 'pdfviewer'); ?>
                            </p>
                            <div class="bg-muted rounded-lg p-3">
                                <code class="text-sm text-primary font-mono">composer require drossmedia/pdf_embed_seo</code>
                            </div>
                            <div class="bg-muted rounded-lg p-3 mt-2">
                                <code class="text-sm text-primary font-mono">drush en pdf_embed_seo</code>
                            </div>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-foreground mb-2"><?php esc_html_e('Manual Installation:', 'pdfviewer'); ?></p>
                            <ol class="text-sm text-muted-foreground space-y-2 list-none">
                                <li class="flex items-start gap-2">
                                    <span class="w-5 h-5 rounded-full gradient-hero text-primary-foreground text-xs font-bold flex items-center justify-center shrink-0">1</span>
                                    <span><?php esc_html_e('Download and extract to /modules/contrib/pdf_embed_seo', 'pdfviewer'); ?></span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <span class="w-5 h-5 rounded-full gradient-hero text-primary-foreground text-xs font-bold flex items-center justify-center shrink-0">2</span>
                                    <span><?php esc_html_e('Enable via Admin > Extend', 'pdfviewer'); ?></span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <span class="w-5 h-5 rounded-full gradient-hero text-primary-foreground text-xs font-bold flex items-center justify-center shrink-0">3</span>
                                    <span><?php esc_html_e('Configure at Admin > Configuration > Content > PDF Embed & SEO', 'pdfviewer'); ?></span>
                                </li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- PDF.js Section -->
<section class="py-16 lg:py-24 bg-card" aria-labelledby="pdfjs-heading">
    <div class="container mx-auto px-4 lg:px-8">
        <div class="max-w-4xl mx-auto">
            <div class="text-center mb-12">
                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-300 text-sm font-medium mb-6">
                    <?php echo pdfviewer_icon('zap', 16); ?>
                    <span><?php esc_html_e('Powered by Mozilla', 'pdfviewer'); ?></span>
                </div>
                <h2 id="pdfjs-heading" class="text-3xl font-bold mb-4">
                    <?php esc_html_e('Built on Mozilla\'s PDF.js (v4.0)', 'pdfviewer'); ?>
                </h2>
                <p class="text-lg text-muted-foreground max-w-2xl mx-auto">
                    <?php esc_html_e('The same technology that powers Firefox\'s PDF viewer—trusted by millions worldwide. Consistent, reliable rendering on every browser and device.', 'pdfviewer'); ?>
                </p>
            </div>
            <div class="grid md:grid-cols-3 gap-6">
                <?php foreach ($viewer_features as $index => $feature) : ?>
                    <div class="bg-card rounded-2xl p-6 border border-border text-center shadow-sm animate-fade-in" style="animation-delay: <?php echo esc_attr($index * 0.1); ?>s">
                        <div class="w-14 h-14 rounded-xl gradient-hero flex items-center justify-center mx-auto mb-4">
                            <?php echo pdfviewer_icon($feature['icon'], 28, 'text-primary-foreground'); ?>
                        </div>
                        <h3 class="font-semibold text-lg mb-2"><?php echo esc_html($feature['title']); ?></h3>
                        <p class="text-muted-foreground text-sm"><?php echo esc_html($feature['description']); ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>

<!-- Theme Options -->
<section class="py-16 lg:py-24" aria-labelledby="themes-heading">
    <div class="container mx-auto px-4 lg:px-8">
        <div class="max-w-4xl mx-auto">
            <h2 id="themes-heading" class="text-3xl font-bold mb-8 text-center">
                <?php esc_html_e('Light & Dark Viewer Themes', 'pdfviewer'); ?>
            </h2>
            <div class="grid md:grid-cols-2 gap-6">
                <div class="bg-card rounded-2xl p-8 border border-border animate-fade-in">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="w-12 h-12 rounded-xl gradient-hero flex items-center justify-center">
                            <?php echo pdfviewer_icon('zap', 24, 'text-primary-foreground'); ?>
                        </div>
                        <h3 class="text-xl font-semibold"><?php esc_html_e('Light Theme', 'pdfviewer'); ?></h3>
                    </div>
                    <p class="text-muted-foreground"><?php esc_html_e('Clean, bright interface that matches light Drupal themes.', 'pdfviewer'); ?></p>
                </div>
                <div class="bg-card rounded-2xl p-8 border border-border animate-fade-in" style="animation-delay: 0.1s">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="w-12 h-12 rounded-xl gradient-hero flex items-center justify-center">
                            <?php echo pdfviewer_icon('zap', 24, 'text-primary-foreground'); ?>
                        </div>
                        <h3 class="text-xl font-semibold"><?php esc_html_e('Dark Theme', 'pdfviewer'); ?></h3>
                    </div>
                    <p class="text-muted-foreground"><?php esc_html_e('Easy on the eyes for sites with dark mode enabled.', 'pdfviewer'); ?></p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Feature Comparison Table -->
<section class="py-16 lg:py-24 bg-card" aria-labelledby="drupal-comparison-heading">
    <div class="container mx-auto px-4 lg:px-8">
        <div class="max-w-4xl mx-auto">
            <h2 id="drupal-comparison-heading" class="text-3xl font-bold mb-8 text-center">
                <?php esc_html_e('Drupal Feature Comparison: Free vs Pro vs Pro+', 'pdfviewer'); ?>
            </h2>

            <?php
            // Feature comparison data for Drupal
            $drupal_comparison_data = array(
                array(
                    'category' => 'Viewer & Display',
                    'features' => array(
                        array('name' => 'Mozilla PDF.js Viewer (v4.0)', 'free' => true, 'pro' => true, 'proPlus' => true),
                        array('name' => 'Light & Dark Themes', 'free' => true, 'pro' => true, 'proPlus' => true),
                        array('name' => 'Responsive Design', 'free' => true, 'pro' => true, 'proPlus' => true),
                        array('name' => 'Print Control (per PDF)', 'free' => true, 'pro' => true, 'proPlus' => true),
                        array('name' => 'Download Control (per PDF)', 'free' => true, 'pro' => true, 'proPlus' => true),
                        array('name' => 'PDF Viewer Block', 'free' => true, 'pro' => true, 'proPlus' => true),
                        array('name' => 'iOS/Safari Print Support', 'free' => true, 'pro' => true, 'proPlus' => true),
                        array('name' => 'PDF Annotations', 'free' => false, 'pro' => false, 'proPlus' => true),
                        array('name' => 'Digital Signatures', 'free' => false, 'pro' => false, 'proPlus' => true),
                    ),
                ),
                array(
                    'category' => 'Content Management',
                    'features' => array(
                        array('name' => 'PDF Document Entity', 'free' => true, 'pro' => true, 'proPlus' => true),
                        array('name' => 'Title, Description, Slug', 'free' => true, 'pro' => true, 'proPlus' => true),
                        array('name' => 'Auto-Generate Thumbnails', 'free' => true, 'pro' => true, 'proPlus' => true),
                        array('name' => 'Multi-language Support', 'free' => true, 'pro' => true, 'proPlus' => true),
                        array('name' => 'Media Library Integration', 'free' => true, 'pro' => true, 'proPlus' => true),
                        array('name' => 'Document Versioning', 'free' => false, 'pro' => false, 'proPlus' => true),
                        array('name' => 'Version History', 'free' => false, 'pro' => false, 'proPlus' => true),
                        array('name' => 'Auto-Versioning', 'free' => false, 'pro' => false, 'proPlus' => true),
                    ),
                ),
                array(
                    'category' => 'SEO & URLs',
                    'features' => array(
                        array('name' => 'Clean URLs (/pdf/slug)', 'free' => true, 'pro' => true, 'proPlus' => true),
                        array('name' => 'Schema.org DigitalDocument', 'free' => true, 'pro' => true, 'proPlus' => true),
                        array('name' => 'OpenGraph Meta Tags', 'free' => true, 'pro' => true, 'proPlus' => true),
                        array('name' => 'Twitter Card Support', 'free' => true, 'pro' => true, 'proPlus' => true),
                        array('name' => 'GEO/AEO/LLM Schema Optimization', 'free' => true, 'pro' => true, 'proPlus' => true),
                        array('name' => 'XML Sitemap (/pdf/sitemap.xml)', 'free' => false, 'pro' => true, 'proPlus' => true),
                    ),
                ),
                array(
                    'category' => 'Statistics & Analytics',
                    'features' => array(
                        array('name' => 'Basic View Counter', 'free' => true, 'pro' => true, 'proPlus' => true),
                        array('name' => 'Analytics Dashboard', 'free' => false, 'pro' => true, 'proPlus' => true),
                        array('name' => 'Download Tracking', 'free' => false, 'pro' => true, 'proPlus' => true),
                        array('name' => 'IP Anonymization (GDPR)', 'free' => false, 'pro' => true, 'proPlus' => true),
                        array('name' => 'CSV/JSON Export', 'free' => false, 'pro' => true, 'proPlus' => true),
                        array('name' => 'Heatmaps', 'free' => false, 'pro' => false, 'proPlus' => true),
                        array('name' => 'Engagement Scoring', 'free' => false, 'pro' => false, 'proPlus' => true),
                        array('name' => 'Geographic Analytics', 'free' => false, 'pro' => false, 'proPlus' => true),
                        array('name' => 'Device Analytics', 'free' => false, 'pro' => false, 'proPlus' => true),
                    ),
                ),
                array(
                    'category' => 'Security',
                    'features' => array(
                        array('name' => 'Secure PDF URLs', 'free' => true, 'pro' => true, 'proPlus' => true),
                        array('name' => 'Permission System', 'free' => true, 'pro' => true, 'proPlus' => true),
                        array('name' => 'Password Protection', 'free' => false, 'pro' => true, 'proPlus' => true),
                        array('name' => 'Password Hashing (Drupal service)', 'free' => false, 'pro' => true, 'proPlus' => true),
                        array('name' => 'Session Cache Context', 'free' => false, 'pro' => true, 'proPlus' => true),
                        array('name' => 'Brute-Force Protection', 'free' => false, 'pro' => true, 'proPlus' => true),
                        array('name' => 'Rate Limiting', 'free' => false, 'pro' => true, 'proPlus' => true),
                        array('name' => 'Expiring Access Links', 'free' => false, 'pro' => true, 'proPlus' => true),
                        array('name' => 'Max Uses per Link', 'free' => false, 'pro' => true, 'proPlus' => true),
                        array('name' => 'Two-Factor Authentication (2FA)', 'free' => false, 'pro' => false, 'proPlus' => true),
                        array('name' => 'IP Whitelisting', 'free' => false, 'pro' => false, 'proPlus' => true),
                        array('name' => 'Audit Logs', 'free' => false, 'pro' => false, 'proPlus' => true),
                    ),
                ),
                array(
                    'category' => 'Archive & Listing',
                    'features' => array(
                        array('name' => 'Archive Page (/pdf)', 'free' => true, 'pro' => true, 'proPlus' => true),
                        array('name' => 'Pagination', 'free' => true, 'pro' => true, 'proPlus' => true),
                        array('name' => 'Grid/List Display', 'free' => true, 'pro' => true, 'proPlus' => true),
                        array('name' => 'Search & Sorting', 'free' => true, 'pro' => true, 'proPlus' => true),
                        array('name' => 'Visible Breadcrumb Navigation', 'free' => true, 'pro' => true, 'proPlus' => true),
                        array('name' => 'Full-Width Layout (No Sidebars)', 'free' => true, 'pro' => true, 'proPlus' => true),
                        array('name' => 'Custom Archive Heading', 'free' => true, 'pro' => true, 'proPlus' => true),
                        array('name' => 'Content Alignment Options', 'free' => true, 'pro' => true, 'proPlus' => true),
                        array('name' => 'Custom Font/Background Colors', 'free' => true, 'pro' => true, 'proPlus' => true),
                        array('name' => 'Category/Tag Filters', 'free' => false, 'pro' => true, 'proPlus' => true),
                        array('name' => 'Archive Page Redirect', 'free' => false, 'pro' => true, 'proPlus' => true),
                    ),
                ),
                array(
                    'category' => 'Reading Experience',
                    'features' => array(
                        array('name' => 'Page Navigation', 'free' => true, 'pro' => true, 'proPlus' => true),
                        array('name' => 'Zoom Controls', 'free' => true, 'pro' => true, 'proPlus' => true),
                        array('name' => 'Full Screen Mode', 'free' => true, 'pro' => true, 'proPlus' => true),
                        array('name' => 'Reading Progress Tracking', 'free' => false, 'pro' => true, 'proPlus' => true),
                        array('name' => 'Resume Reading', 'free' => false, 'pro' => true, 'proPlus' => true),
                    ),
                ),
                array(
                    'category' => 'Compliance & Privacy',
                    'features' => array(
                        array('name' => 'Basic GDPR (IP Anonymization)', 'free' => true, 'pro' => true, 'proPlus' => true),
                        array('name' => 'Full GDPR Mode', 'free' => false, 'pro' => false, 'proPlus' => true),
                        array('name' => 'HIPAA Compliance Mode', 'free' => false, 'pro' => false, 'proPlus' => true),
                        array('name' => 'Data Retention Policies', 'free' => false, 'pro' => false, 'proPlus' => true),
                        array('name' => 'Consent Management', 'free' => false, 'pro' => false, 'proPlus' => true),
                    ),
                ),
                array(
                    'category' => 'Integrations',
                    'features' => array(
                        array('name' => 'Google Analytics 4', 'free' => false, 'pro' => true, 'proPlus' => true),
                        array('name' => 'Webhooks (Zapier, etc.)', 'free' => false, 'pro' => false, 'proPlus' => true),
                        array('name' => 'Custom Webhook Events', 'free' => false, 'pro' => false, 'proPlus' => true),
                        array('name' => 'Webhook Signatures (HMAC)', 'free' => false, 'pro' => false, 'proPlus' => true),
                    ),
                ),
                array(
                    'category' => 'Branding',
                    'features' => array(
                        array('name' => 'Default Branding', 'free' => true, 'pro' => true, 'proPlus' => true),
                        array('name' => 'White Label Mode', 'free' => false, 'pro' => false, 'proPlus' => true),
                        array('name' => 'Custom Logo', 'free' => false, 'pro' => false, 'proPlus' => true),
                        array('name' => 'Hide "Powered By"', 'free' => false, 'pro' => false, 'proPlus' => true),
                        array('name' => 'Custom CSS Injection', 'free' => false, 'pro' => false, 'proPlus' => true),
                    ),
                ),
                array(
                    'category' => 'Support',
                    'features' => array(
                        array('name' => 'Community Support', 'free' => true, 'pro' => true, 'proPlus' => true),
                        array('name' => 'Priority Email Support', 'free' => false, 'pro' => true, 'proPlus' => true),
                        array('name' => '1-on-1 Setup Assistance', 'free' => false, 'pro' => false, 'proPlus' => true),
                        array('name' => 'Dedicated Account Manager', 'free' => false, 'pro' => false, 'proPlus' => true),
                        array('name' => 'SLA Guarantee', 'free' => false, 'pro' => false, 'proPlus' => true),
                    ),
                ),
            );

            // Helper function for feature cell (avoid redeclaration)
            if (!function_exists('pdfviewer_drupal_feature_cell')) {
                function pdfviewer_drupal_feature_cell($value) {
                    if ($value === true) {
                        return '<span class="text-primary">' . pdfviewer_icon('check', 20) . '</span>';
                    }
                    return '<span class="text-destructive">' . pdfviewer_icon('x', 20) . '</span>';
                }
            }
            ?>

            <div class="bg-background rounded-2xl border border-border overflow-hidden">
                <!-- Table Header - Desktop -->
                <div class="hidden md:grid grid-cols-4 bg-muted p-4 font-semibold text-sm sticky top-0 text-foreground">
                    <div><?php esc_html_e('Feature', 'pdfviewer'); ?></div>
                    <div class="text-center"><?php esc_html_e('Free', 'pdfviewer'); ?></div>
                    <div class="text-center text-primary"><?php esc_html_e('Pro', 'pdfviewer'); ?></div>
                    <div class="text-center text-accent"><?php esc_html_e('Pro+', 'pdfviewer'); ?></div>
                </div>

                <!-- Table Header - Mobile -->
                <div class="md:hidden grid grid-cols-4 bg-muted p-3 font-semibold text-xs sticky top-0 text-foreground">
                    <div><?php esc_html_e('Feature', 'pdfviewer'); ?></div>
                    <div class="text-center"><?php esc_html_e('Free', 'pdfviewer'); ?></div>
                    <div class="text-center text-primary"><?php esc_html_e('Pro', 'pdfviewer'); ?></div>
                    <div class="text-center text-accent"><?php esc_html_e('Pro+', 'pdfviewer'); ?></div>
                </div>

                <!-- Table Body -->
                <?php foreach ($drupal_comparison_data as $category) : ?>
                    <!-- Category Header -->
                    <div class="bg-muted/50 px-4 py-2 md:py-3 border-t border-border">
                        <h3 class="font-semibold text-xs md:text-sm uppercase tracking-wide text-primary"><?php echo esc_html($category['category']); ?></h3>
                    </div>
                    <!-- Features -->
                    <?php foreach ($category['features'] as $index => $feature) : ?>
                        <!-- Desktop Row -->
                        <div class="hidden md:grid grid-cols-4 px-4 py-3 text-sm <?php echo $index % 2 === 0 ? 'bg-background' : 'bg-muted/50'; ?>">
                            <div class="text-foreground"><?php echo esc_html($feature['name']); ?></div>
                            <div class="text-center"><?php echo pdfviewer_drupal_feature_cell($feature['free']); ?></div>
                            <div class="text-center"><?php echo pdfviewer_drupal_feature_cell($feature['pro']); ?></div>
                            <div class="text-center"><?php echo pdfviewer_drupal_feature_cell($feature['proPlus']); ?></div>
                        </div>
                        <!-- Mobile Row -->
                        <div class="md:hidden grid grid-cols-4 px-3 py-2 text-xs <?php echo $index % 2 === 0 ? 'bg-background' : 'bg-muted/50'; ?> border-t border-border/50">
                            <div class="text-foreground font-medium"><?php echo esc_html($feature['name']); ?></div>
                            <div class="text-center"><?php echo pdfviewer_drupal_feature_cell($feature['free']); ?></div>
                            <div class="text-center"><?php echo pdfviewer_drupal_feature_cell($feature['pro']); ?></div>
                            <div class="text-center"><?php echo pdfviewer_drupal_feature_cell($feature['proPlus']); ?></div>
                        </div>
                    <?php endforeach; ?>
                <?php endforeach; ?>
            </div>
            <!-- GDPR & HIPAA Explanation -->
            <div class="mt-10 grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="p-6 rounded-2xl bg-card border border-border">
                    <div class="flex items-center gap-3 mb-3">
                        <?php echo pdfviewer_icon('shield', 20, 'text-accent'); ?>
                        <h3 class="text-base font-semibold text-foreground"><?php esc_html_e('What is GDPR?', 'pdfviewer'); ?></h3>
                    </div>
                    <p class="text-sm text-muted-foreground leading-relaxed"><?php esc_html_e('The General Data Protection Regulation (GDPR) is a European Union regulation that protects the personal data and privacy of individuals in the EU/EEA. It requires organizations to minimize data collection, provide transparency, and give individuals control over their data. Our module supports GDPR with IP anonymization (enabled by default), no third-party data transfers, consent management, and audit logging.', 'pdfviewer'); ?></p>
                </div>
                <div class="p-6 rounded-2xl bg-card border border-border">
                    <div class="flex items-center gap-3 mb-3">
                        <?php echo pdfviewer_icon('heart-pulse', 20, 'text-primary'); ?>
                        <h3 class="text-base font-semibold text-foreground"><?php esc_html_e('What is HIPAA?', 'pdfviewer'); ?></h3>
                    </div>
                    <p class="text-sm text-muted-foreground leading-relaxed"><?php esc_html_e('The Health Insurance Portability and Accountability Act (HIPAA) is a US federal law that protects sensitive patient health information (PHI). It requires access controls, audit trails, data integrity, and transmission security. Our module supports HIPAA with role-based access control, two-factor authentication, complete audit logs, and secure document delivery.', 'pdfviewer'); ?></p>
                </div>
            </div>
            <p class="mt-4 text-xs text-muted-foreground text-center italic"><?php esc_html_e('PDF Embed & SEO Optimize provides technical controls that support GDPR and HIPAA compliance. Compliance responsibility remains with your organization.', 'pdfviewer'); ?></p>
        </div>
    </div>
</section>

<!-- Additional Features -->
<section class="py-16 lg:py-24" aria-labelledby="drupal-extra-features-heading">
    <div class="container mx-auto px-4 lg:px-8">
        <div class="max-w-4xl mx-auto">
            <h2 id="drupal-extra-features-heading" class="text-3xl font-bold mb-12 text-center">
                <?php esc_html_e('More Than Just a PDF Viewer', 'pdfviewer'); ?>
            </h2>
            <div class="grid sm:grid-cols-3 gap-6">
                <article class="bg-card rounded-2xl p-6 border border-border text-center shadow-sm">
                    <div class="w-12 h-12 rounded-xl gradient-hero flex items-center justify-center mx-auto mb-4">
                        <?php echo pdfviewer_icon('lock', 24, 'text-primary-foreground'); ?>
                    </div>
                    <h3 class="font-semibold mb-2"><?php esc_html_e('Content Protection', 'pdfviewer'); ?></h3>
                    <p class="text-muted-foreground text-sm">
                        <?php esc_html_e('Control print and download options per PDF. Hide direct file URLs.', 'pdfviewer'); ?>
                    </p>
                </article>
                <article class="bg-card rounded-2xl p-6 border border-border text-center shadow-sm">
                    <div class="w-12 h-12 rounded-xl gradient-hero flex items-center justify-center mx-auto mb-4">
                        <?php echo pdfviewer_icon('search', 24, 'text-primary-foreground'); ?>
                    </div>
                    <h3 class="font-semibold mb-2"><?php esc_html_e('SEO & GEO Ready', 'pdfviewer'); ?></h3>
                    <p class="text-muted-foreground text-sm">
                        <?php esc_html_e('Optimized for Google and AI tools like ChatGPT with structured data.', 'pdfviewer'); ?>
                    </p>
                </article>
                <article class="bg-card rounded-2xl p-6 border border-border text-center shadow-sm">
                    <div class="w-12 h-12 rounded-xl gradient-hero flex items-center justify-center mx-auto mb-4">
                        <?php echo pdfviewer_icon('zap', 24, 'text-primary-foreground'); ?>
                    </div>
                    <h3 class="font-semibold mb-2"><?php esc_html_e('Fast Loading', 'pdfviewer'); ?></h3>
                    <p class="text-muted-foreground text-sm">
                        <?php esc_html_e('AJAX loading keeps your pages fast. PDFs load only when viewed.', 'pdfviewer'); ?>
                    </p>
                </article>
            </div>
        </div>
    </div>
</section>

<!-- Download Free Drupal Module -->
<section id="download-drupal" class="py-16 lg:py-24 bg-card" aria-labelledby="download-drupal-heading">
    <div class="container mx-auto px-4 lg:px-8">
        <div class="max-w-3xl mx-auto text-center">
            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-primary/20 text-foreground text-sm font-medium mb-6">
                <?php echo pdfviewer_icon('download', 16); ?>
                <span><?php esc_html_e('Free Download', 'pdfviewer'); ?></span>
            </div>
            <h2 id="download-drupal-heading" class="text-3xl font-bold mb-6">
                <?php esc_html_e('Download Free Drupal PDF Viewer Module', 'pdfviewer'); ?>
            </h2>
            <p class="text-lg text-muted-foreground mb-8 max-w-2xl mx-auto">
                <?php esc_html_e('Get the most complete PDF embedding solution for Drupal. SEO-optimized, responsive, and fully customizable. No registration or credit card required.', 'pdfviewer'); ?>
            </p>
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                <a href="https://www.drupal.org/project/pdf-embed-seo-optimize?ref=pdfviewer"
                   target="_blank"
                   rel="noopener"
                   class="btn btn-primary btn-lg gradient-hero shadow-glow gap-2 inline-flex items-center focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2"
                   aria-label="<?php esc_attr_e('Download PDF Embed & SEO Optimize from Drupal.org (opens in new tab)', 'pdfviewer'); ?>"
                   title="<?php esc_attr_e('Download the free Drupal PDF viewer module from Drupal.org', 'pdfviewer'); ?>">
                    <?php pdfviewer_drupal_icon(20); ?>
                    <?php esc_html_e('Download from Drupal.org', 'pdfviewer'); ?>
                    <?php echo pdfviewer_icon('external-link', 18); ?>
                </a>
                <a href="<?php echo esc_url(home_url('/pro/#pricing')); ?>"
                   class="btn btn-outline btn-lg gap-2 inline-flex items-center focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2"
                   aria-label="<?php esc_attr_e('View Pro version pricing and features', 'pdfviewer'); ?>"
                   title="<?php esc_attr_e('Upgrade to Pro for advanced features like password protection, analytics, and more', 'pdfviewer'); ?>">
                    <?php echo pdfviewer_icon('zap', 20); ?>
                    <?php esc_html_e('Get Pro', 'pdfviewer'); ?>
                </a>
            </div>
        </div>
    </div>
</section>

<?php get_footer(); ?>
