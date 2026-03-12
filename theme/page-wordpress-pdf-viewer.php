<?php
/**
 * Template Name: WordPress PDF Viewer
 * WordPress PDF Viewer landing page
 *
 * @package PDFViewer
 */

get_header();

$requirements = array(
    'WordPress 5.8 or higher',
    'PHP 7.4 or higher',
    'Optional: Yoast SEO (for enhanced SEO)',
    'Optional: ImageMagick or Ghostscript (for auto thumbnails)',
);

$install_steps = array(
    'Download the plugin ZIP file',
    'Go to Plugins > Add New > Upload Plugin',
    'Upload and activate the plugin',
    'Go to PDF Documents to start adding PDFs',
);

$embed_methods = array(
    array(
        'icon'        => 'file-text',
        'title'       => 'Gutenberg Block',
        'description' => 'Simply add the PDF Viewer block in the WordPress editor, select your PDF, and publish. No coding required.',
        'code'        => null,
    ),
    array(
        'icon'        => 'file-text',
        'title'       => 'Shortcode',
        'description' => 'Use a simple shortcode anywhere on your WordPress site-posts, pages, widgets, or theme files.',
        'code'        => '[pdf_viewer id="123"]',
    ),
    array(
        'icon'        => 'file-text',
        'title'       => 'Theme Integration',
        'description' => 'For developers, integrate directly into your WordPress theme using PHP.',
        'code'        => '<?php echo do_shortcode(\'[pdf_viewer id="123"]\'); ?>',
    ),
);

$seo_benefits = array(
    array(
        'icon'        => 'search',
        'title'       => 'SEO-Optimized URLs',
        'description' => 'Each embedded PDF gets a clean URL like /pdf/document-name/ instead of ugly upload paths.',
    ),
    array(
        'icon'        => 'external-link',
        'title'       => 'Social Sharing Ready',
        'description' => 'Auto-generated OpenGraph and Twitter cards with PDF thumbnail previews.',
    ),
    array(
        'icon'        => 'eye',
        'title'       => 'View Analytics',
        'description' => 'Track how many people view each embedded PDF on your WordPress site.',
    ),
    array(
        'icon'        => 'file-text',
        'title'       => 'Beautiful Viewer',
        'description' => 'Mozilla PDF.js provides consistent rendering across all browsers and devices.',
    ),
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
                <span><?php esc_html_e('WordPress PDF Plugin', 'pdfviewer'); ?></span>
            </div>
            <h1 id="viewer-hero-heading" class="text-4xl md:text-5xl lg:text-6xl font-extrabold tracking-tight leading-tight mb-6">
                <?php esc_html_e('The Best', 'pdfviewer'); ?> <span class="text-gradient"><?php esc_html_e('WordPress PDF Viewer', 'pdfviewer'); ?></span> <?php esc_html_e('& Embed Plugin', 'pdfviewer'); ?>
            </h1>
            <p class="text-xl md:text-2xl text-muted-foreground max-w-3xl mx-auto mb-10">
                <?php esc_html_e('Embed and display PDFs beautifully on any device. SEO-optimized with clean URLs, Gutenberg blocks, shortcodes, and view analytics-all for free.', 'pdfviewer'); ?>
            </p>
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                <a href="#download-wordpress"
                   class="btn btn-primary btn-lg gradient-hero shadow-glow gap-2 min-w-[240px] justify-center">
                    <?php echo pdfviewer_icon('download', 20); ?>
                    <?php esc_html_e('Download Free Plugin', 'pdfviewer'); ?>
                </a>
                <a href="<?php echo esc_url(home_url('/examples/')); ?>" class="btn btn-outline btn-lg gap-2 min-w-[240px] justify-center">
                    <?php esc_html_e('View Live Demo', 'pdfviewer'); ?>
                    <?php echo pdfviewer_icon('arrow-right', 20); ?>
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Problem vs Solution -->
<section class="py-16 lg:py-24" aria-labelledby="problem-heading">
    <div class="container mx-auto px-4 lg:px-8">
        <div class="max-w-4xl mx-auto">
            <h2 id="problem-heading" class="text-3xl font-bold mb-8 text-center">
                <?php esc_html_e('Why Use Our WordPress PDF Plugin?', 'pdfviewer'); ?>
            </h2>
            <div class="grid md:grid-cols-2 gap-8">
                <div class="bg-destructive/5 rounded-2xl p-6 border border-destructive/20">
                    <h3 class="font-semibold text-lg mb-4 text-destructive"><?php esc_html_e('Default WordPress Approach', 'pdfviewer'); ?></h3>
                    <ul class="space-y-3 text-muted-foreground list-none">
                        <li class="flex items-start gap-2">
                            <span class="text-destructive mt-1 shrink-0">&#x2717;</span>
                            <span><?php esc_html_e('Ugly URLs: /wp-content/uploads/2025/01/file.pdf', 'pdfviewer'); ?></span>
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="text-destructive mt-1 shrink-0">&#x2717;</span>
                            <span><?php esc_html_e('No SEO value-PDFs are invisible to Google', 'pdfviewer'); ?></span>
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="text-destructive mt-1 shrink-0">&#x2717;</span>
                            <span><?php esc_html_e('No social sharing previews', 'pdfviewer'); ?></span>
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="text-destructive mt-1 shrink-0">&#x2717;</span>
                            <span><?php esc_html_e('No way to track views', 'pdfviewer'); ?></span>
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="text-destructive mt-1 shrink-0">&#x2717;</span>
                            <span><?php esc_html_e('Inconsistent display across devices', 'pdfviewer'); ?></span>
                        </li>
                    </ul>
                </div>
                <div class="bg-primary/5 rounded-2xl p-6 border border-primary/20">
                    <h3 class="font-semibold text-lg mb-4 text-primary"><?php esc_html_e('With PDF Embed & SEO Optimize', 'pdfviewer'); ?></h3>
                    <ul class="space-y-3 text-muted-foreground list-none">
                        <li class="flex items-start gap-2">
                            <?php echo pdfviewer_icon('check', 20, 'text-primary shrink-0 mt-0.5'); ?>
                            <span><?php esc_html_e('Clean URLs: /pdf/document-name/', 'pdfviewer'); ?></span>
                        </li>
                        <li class="flex items-start gap-2">
                            <?php echo pdfviewer_icon('check', 20, 'text-primary shrink-0 mt-0.5'); ?>
                            <span><?php esc_html_e('Full SEO with schema markup & sitemaps', 'pdfviewer'); ?></span>
                        </li>
                        <li class="flex items-start gap-2">
                            <?php echo pdfviewer_icon('check', 20, 'text-primary shrink-0 mt-0.5'); ?>
                            <span><?php esc_html_e('OpenGraph & Twitter Card previews', 'pdfviewer'); ?></span>
                        </li>
                        <li class="flex items-start gap-2">
                            <?php echo pdfviewer_icon('check', 20, 'text-primary shrink-0 mt-0.5'); ?>
                            <span><?php esc_html_e('Built-in view analytics', 'pdfviewer'); ?></span>
                        </li>
                        <li class="flex items-start gap-2">
                            <?php echo pdfviewer_icon('check', 20, 'text-primary shrink-0 mt-0.5'); ?>
                            <span><?php esc_html_e('Mozilla PDF.js for perfect rendering', 'pdfviewer'); ?></span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Requirements & Installation -->
<section class="py-16 lg:py-24 bg-card" aria-labelledby="requirements-heading">
    <div class="container mx-auto px-4 lg:px-8">
        <div class="max-w-4xl mx-auto">
            <div class="grid md:grid-cols-2 gap-8">
                <!-- Requirements -->
                <div class="bg-background rounded-2xl p-6 border border-border">
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
                <div class="bg-background rounded-2xl p-6 border border-border">
                    <h2 class="text-xl font-bold mb-4"><?php esc_html_e('Installation', 'pdfviewer'); ?></h2>
                    <ol class="space-y-3 list-none">
                        <?php foreach ($install_steps as $index => $step) : ?>
                            <li class="flex items-start gap-3">
                                <span class="w-6 h-6 rounded-full gradient-hero text-primary-foreground text-sm font-bold flex items-center justify-center shrink-0">
                                    <?php echo esc_html($index + 1); ?>
                                </span>
                                <span class="text-muted-foreground"><?php echo esc_html($step); ?></span>
                            </li>
                        <?php endforeach; ?>
                    </ol>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- How to Embed PDFs -->
<section class="py-16 lg:py-24" aria-labelledby="embed-methods-heading">
    <div class="container mx-auto px-4 lg:px-8">
        <div class="max-w-4xl mx-auto">
            <div class="text-center mb-12">
                <h2 id="embed-methods-heading" class="text-3xl font-bold mb-4">
                    <?php esc_html_e('3 Ways to Embed PDFs in WordPress', 'pdfviewer'); ?>
                </h2>
                <p class="text-lg text-muted-foreground">
                    <?php esc_html_e('Choose the method that works best for your workflow', 'pdfviewer'); ?>
                </p>
            </div>
            <div class="grid md:grid-cols-3 gap-6">
                <?php foreach ($embed_methods as $index => $method) : ?>
                    <div class="bg-card rounded-2xl p-6 border border-border animate-fade-in" style="animation-delay: <?php echo esc_attr($index * 0.1); ?>s">
                        <div class="w-12 h-12 rounded-xl gradient-hero flex items-center justify-center mb-4">
                            <?php echo pdfviewer_icon($method['icon'], 24, 'text-primary-foreground'); ?>
                        </div>
                        <h3 class="text-xl font-semibold mb-2"><?php echo esc_html($method['title']); ?></h3>
                        <p class="text-muted-foreground mb-4"><?php echo esc_html($method['description']); ?></p>
                        <?php if ($method['code']) : ?>
                            <code class="block bg-muted px-4 py-3 rounded-lg text-sm font-mono overflow-x-auto">
                                <?php echo esc_html($method['code']); ?>
                            </code>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>

<!-- SEO Benefits -->
<section class="py-16 lg:py-24 bg-card" aria-labelledby="seo-benefits-heading">
    <div class="container mx-auto px-4 lg:px-8">
        <div class="max-w-4xl mx-auto">
            <h2 id="seo-benefits-heading" class="text-3xl font-bold mb-12 text-center">
                <?php esc_html_e('SEO & Analytics Built-In', 'pdfviewer'); ?>
            </h2>
            <div class="grid sm:grid-cols-2 gap-6">
                <?php foreach ($seo_benefits as $index => $benefit) : ?>
                    <div class="bg-background rounded-2xl p-6 border border-border animate-fade-in" style="animation-delay: <?php echo esc_attr($index * 0.1); ?>s">
                        <div class="w-10 h-10 rounded-lg gradient-hero flex items-center justify-center mb-4">
                            <?php echo pdfviewer_icon($benefit['icon'], 20, 'text-primary-foreground'); ?>
                        </div>
                        <h3 class="font-semibold mb-2"><?php echo esc_html($benefit['title']); ?></h3>
                        <p class="text-muted-foreground text-sm"><?php echo esc_html($benefit['description']); ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>

<!-- PDF.js Section -->
<section class="py-16 lg:py-24" aria-labelledby="pdfjs-heading">
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
                    <?php esc_html_e('The same technology that powers Firefox\'s PDF viewer-trusted by millions worldwide. Consistent, reliable rendering on every browser and device.', 'pdfviewer'); ?>
                </p>
            </div>
            <div class="grid md:grid-cols-3 gap-6">
                <?php foreach ($viewer_features as $index => $feature) : ?>
                    <div class="bg-card rounded-2xl p-6 border border-border text-center animate-fade-in" style="animation-delay: <?php echo esc_attr($index * 0.1); ?>s">
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
<section class="py-16 lg:py-24 bg-card" aria-labelledby="themes-heading">
    <div class="container mx-auto px-4 lg:px-8">
        <div class="max-w-4xl mx-auto">
            <h2 id="themes-heading" class="text-3xl font-bold mb-8 text-center">
                <?php esc_html_e('Light & Dark Viewer Themes', 'pdfviewer'); ?>
            </h2>
            <div class="grid md:grid-cols-2 gap-6">
                <div class="bg-background rounded-2xl p-8 border border-border animate-fade-in">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="w-12 h-12 rounded-xl gradient-hero flex items-center justify-center">
                            <?php echo pdfviewer_icon('zap', 24, 'text-primary-foreground'); ?>
                        </div>
                        <h3 class="text-xl font-semibold"><?php esc_html_e('Light Theme', 'pdfviewer'); ?></h3>
                    </div>
                    <p class="text-muted-foreground"><?php esc_html_e('Clean, bright interface that matches light WordPress themes.', 'pdfviewer'); ?></p>
                </div>
                <div class="bg-background rounded-2xl p-8 border border-border animate-fade-in" style="animation-delay: 0.1s">
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
<section class="py-16 lg:py-24" aria-labelledby="wp-comparison-heading">
    <div class="container mx-auto px-4 lg:px-8">
        <div class="max-w-4xl mx-auto">
            <h2 id="wp-comparison-heading" class="text-3xl font-bold mb-8 text-center">
                <?php esc_html_e('WordPress Feature Comparison: Free vs Pro vs Pro+', 'pdfviewer'); ?>
            </h2>

            <?php
            // Feature comparison data
            $comparison_data = array(
                array(
                    'category' => 'Viewer & Display',
                    'features' => array(
                        array('name' => 'Mozilla PDF.js Viewer (v4.0)', 'free' => true, 'pro' => true, 'proPlus' => true),
                        array('name' => 'Light Theme', 'free' => true, 'pro' => true, 'proPlus' => true),
                        array('name' => 'Dark Theme', 'free' => true, 'pro' => true, 'proPlus' => true),
                        array('name' => 'Responsive Design', 'free' => true, 'pro' => true, 'proPlus' => true),
                        array('name' => 'Print Control (per PDF)', 'free' => true, 'pro' => true, 'proPlus' => true),
                        array('name' => 'Download Control (per PDF)', 'free' => true, 'pro' => true, 'proPlus' => true),
                        array('name' => 'Configurable Viewer Height', 'free' => true, 'pro' => true, 'proPlus' => true),
                        array('name' => 'Gutenberg Block', 'free' => true, 'pro' => true, 'proPlus' => true),
                        array('name' => 'Shortcodes', 'free' => true, 'pro' => true, 'proPlus' => true),
                        array('name' => 'iOS/Safari Print Support', 'free' => true, 'pro' => true, 'proPlus' => true),
                        array('name' => 'Text Search in Viewer', 'free' => false, 'pro' => true, 'proPlus' => true),
                        array('name' => 'Bookmark Navigation', 'free' => false, 'pro' => true, 'proPlus' => true),
                        array('name' => 'PDF Annotations', 'free' => false, 'pro' => false, 'proPlus' => true),
                        array('name' => 'Digital Signatures', 'free' => false, 'pro' => false, 'proPlus' => true),
                    ),
                ),
                array(
                    'category' => 'Content Management',
                    'features' => array(
                        array('name' => 'PDF Document Post Type', 'free' => true, 'pro' => true, 'proPlus' => true),
                        array('name' => 'Title, Description, Slug', 'free' => true, 'pro' => true, 'proPlus' => true),
                        array('name' => 'File Upload & Management', 'free' => true, 'pro' => true, 'proPlus' => true),
                        array('name' => 'Featured Image / Thumbnail', 'free' => true, 'pro' => true, 'proPlus' => true),
                        array('name' => 'Auto-Generate Thumbnails', 'free' => true, 'pro' => true, 'proPlus' => true),
                        array('name' => 'Categories Taxonomy', 'free' => false, 'pro' => true, 'proPlus' => true),
                        array('name' => 'Tags Taxonomy', 'free' => false, 'pro' => true, 'proPlus' => true),
                        array('name' => 'Bulk Import (CSV/ZIP)', 'free' => false, 'pro' => true, 'proPlus' => true),
                        array('name' => 'Document Versioning', 'free' => false, 'pro' => false, 'proPlus' => true),
                        array('name' => 'Version History', 'free' => false, 'pro' => false, 'proPlus' => true),
                        array('name' => 'Auto-Versioning', 'free' => false, 'pro' => false, 'proPlus' => true),
                    ),
                ),
                array(
                    'category' => 'SEO & URLs',
                    'features' => array(
                        array('name' => 'Clean URL Structure (/pdf/slug/)', 'free' => true, 'pro' => true, 'proPlus' => true),
                        array('name' => 'Schema.org DigitalDocument', 'free' => true, 'pro' => true, 'proPlus' => true),
                        array('name' => 'Yoast SEO Integration', 'free' => true, 'pro' => true, 'proPlus' => true),
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
                        array('name' => 'View Count Display', 'free' => true, 'pro' => true, 'proPlus' => true),
                        array('name' => 'Analytics Dashboard', 'free' => false, 'pro' => true, 'proPlus' => true),
                        array('name' => 'Download Tracking', 'free' => false, 'pro' => true, 'proPlus' => true),
                        array('name' => 'Analytics Export (CSV/JSON)', 'free' => false, 'pro' => true, 'proPlus' => true),
                        array('name' => 'Heatmaps', 'free' => false, 'pro' => false, 'proPlus' => true),
                        array('name' => 'Engagement Scoring', 'free' => false, 'pro' => false, 'proPlus' => true),
                        array('name' => 'Geographic Analytics', 'free' => false, 'pro' => false, 'proPlus' => true),
                        array('name' => 'Device Analytics', 'free' => false, 'pro' => false, 'proPlus' => true),
                    ),
                ),
                array(
                    'category' => 'Security & Access',
                    'features' => array(
                        array('name' => 'Nonce/CSRF Protection', 'free' => true, 'pro' => true, 'proPlus' => true),
                        array('name' => 'Secure PDF URL (no direct links)', 'free' => true, 'pro' => true, 'proPlus' => true),
                        array('name' => 'Password Protection', 'free' => false, 'pro' => true, 'proPlus' => true),
                        array('name' => 'Expiring Access Links', 'free' => false, 'pro' => true, 'proPlus' => true),
                        array('name' => 'Role-Based Access Control', 'free' => false, 'pro' => true, 'proPlus' => true),
                        array('name' => 'Two-Factor Authentication (2FA)', 'free' => false, 'pro' => false, 'proPlus' => true),
                        array('name' => 'IP Whitelisting', 'free' => false, 'pro' => false, 'proPlus' => true),
                        array('name' => 'Audit Logs', 'free' => false, 'pro' => false, 'proPlus' => true),
                    ),
                ),
                array(
                    'category' => 'Archive & Listing',
                    'features' => array(
                        array('name' => 'Archive Page (/pdf/)', 'free' => true, 'pro' => true, 'proPlus' => true),
                        array('name' => 'Pagination Support', 'free' => true, 'pro' => true, 'proPlus' => true),
                        array('name' => 'Grid/List Display Modes', 'free' => true, 'pro' => true, 'proPlus' => true),
                        array('name' => 'Sorting Options', 'free' => true, 'pro' => true, 'proPlus' => true),
                        array('name' => 'Search Filtering', 'free' => true, 'pro' => true, 'proPlus' => true),
                        array('name' => 'Visible Breadcrumb Navigation', 'free' => true, 'pro' => true, 'proPlus' => true),
                        array('name' => 'Full-Width Layout (No Sidebars)', 'free' => true, 'pro' => true, 'proPlus' => true),
                        array('name' => 'Custom Archive Heading', 'free' => true, 'pro' => true, 'proPlus' => true),
                        array('name' => 'Content Alignment Options', 'free' => true, 'pro' => true, 'proPlus' => true),
                        array('name' => 'Custom Font/Background Colors', 'free' => true, 'pro' => true, 'proPlus' => true),
                        array('name' => 'Category Filter', 'free' => false, 'pro' => true, 'proPlus' => true),
                        array('name' => 'Tag Filter', 'free' => false, 'pro' => true, 'proPlus' => true),
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
                        array('name' => 'Resume Reading Feature', 'free' => false, 'pro' => true, 'proPlus' => true),
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
                        array('name' => 'Yoast SEO', 'free' => true, 'pro' => true, 'proPlus' => true),
                        array('name' => 'Google Analytics 4', 'free' => false, 'pro' => true, 'proPlus' => true),
                        array('name' => 'WooCommerce (Sell PDFs)', 'free' => false, 'pro' => true, 'proPlus' => true),
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

            // Helper function for feature cell
            function pdfviewer_feature_cell($value) {
                if ($value === true) {
                    return '<span class="text-primary">' . pdfviewer_icon('check', 20) . '</span>';
                }
                return '<span class="text-destructive">' . pdfviewer_icon('x', 20) . '</span>';
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
                <?php foreach ($comparison_data as $category) : ?>
                    <!-- Category Header -->
                    <div class="bg-muted/50 px-4 py-2 md:py-3 border-t border-border">
                        <h3 class="font-semibold text-xs md:text-sm uppercase tracking-wide text-primary"><?php echo esc_html($category['category']); ?></h3>
                    </div>
                    <!-- Features -->
                    <?php foreach ($category['features'] as $index => $feature) : ?>
                        <!-- Desktop Row -->
                        <div class="hidden md:grid grid-cols-4 px-4 py-3 text-sm <?php echo $index % 2 === 0 ? 'bg-background' : 'bg-muted/50'; ?>">
                            <div class="text-foreground"><?php echo esc_html($feature['name']); ?></div>
                            <div class="text-center"><?php echo pdfviewer_feature_cell($feature['free']); ?></div>
                            <div class="text-center"><?php echo pdfviewer_feature_cell($feature['pro']); ?></div>
                            <div class="text-center"><?php echo pdfviewer_feature_cell($feature['proPlus']); ?></div>
                        </div>
                        <!-- Mobile Row -->
                        <div class="md:hidden grid grid-cols-4 px-3 py-2 text-xs <?php echo $index % 2 === 0 ? 'bg-background' : 'bg-muted/50'; ?> border-t border-border/50">
                            <div class="text-foreground font-medium"><?php echo esc_html($feature['name']); ?></div>
                            <div class="text-center"><?php echo pdfviewer_feature_cell($feature['free']); ?></div>
                            <div class="text-center"><?php echo pdfviewer_feature_cell($feature['pro']); ?></div>
                            <div class="text-center"><?php echo pdfviewer_feature_cell($feature['proPlus']); ?></div>
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
                    <p class="text-sm text-muted-foreground leading-relaxed"><?php esc_html_e('The General Data Protection Regulation (GDPR) is a European Union regulation that protects the personal data and privacy of individuals in the EU/EEA. It requires organizations to minimize data collection, provide transparency, and give individuals control over their data. Our plugin supports GDPR with IP anonymization (enabled by default), no third-party data transfers, consent management, and audit logging.', 'pdfviewer'); ?></p>
                </div>
                <div class="p-6 rounded-2xl bg-card border border-border">
                    <div class="flex items-center gap-3 mb-3">
                        <?php echo pdfviewer_icon('heart-pulse', 20, 'text-primary'); ?>
                        <h3 class="text-base font-semibold text-foreground"><?php esc_html_e('What is HIPAA?', 'pdfviewer'); ?></h3>
                    </div>
                    <p class="text-sm text-muted-foreground leading-relaxed"><?php esc_html_e('The Health Insurance Portability and Accountability Act (HIPAA) is a US federal law that protects sensitive patient health information (PHI). It requires access controls, audit trails, data integrity, and transmission security. Our plugin supports HIPAA with role-based access control, two-factor authentication, complete audit logs, and secure document delivery.', 'pdfviewer'); ?></p>
                </div>
            </div>
            <p class="mt-4 text-xs text-muted-foreground text-center italic"><?php esc_html_e('PDF Embed & SEO Optimize provides technical controls that support GDPR and HIPAA compliance. Compliance responsibility remains with your organization.', 'pdfviewer'); ?></p>
        </div>
    </div>
</section>

<!-- Additional Features -->
<section class="py-16 lg:py-24" aria-labelledby="extra-features-heading">
    <div class="container mx-auto px-4 lg:px-8">
        <div class="max-w-4xl mx-auto">
            <h2 id="extra-features-heading" class="text-3xl font-bold mb-12 text-center">
                <?php esc_html_e('More Than Just a PDF Viewer', 'pdfviewer'); ?>
            </h2>
            <div class="grid sm:grid-cols-3 gap-6">
                <div class="bg-card rounded-2xl p-6 border border-border text-center">
                    <div class="w-12 h-12 rounded-xl gradient-hero flex items-center justify-center mx-auto mb-4">
                        <?php echo pdfviewer_icon('lock', 24, 'text-primary-foreground'); ?>
                    </div>
                    <h3 class="font-semibold mb-2"><?php esc_html_e('Content Protection', 'pdfviewer'); ?></h3>
                    <p class="text-muted-foreground text-sm">
                        <?php esc_html_e('Control print and download options per PDF. Hide direct file URLs.', 'pdfviewer'); ?>
                    </p>
                </div>
                <div class="bg-card rounded-2xl p-6 border border-border text-center">
                    <div class="w-12 h-12 rounded-xl gradient-hero flex items-center justify-center mx-auto mb-4">
                        <?php echo pdfviewer_icon('search', 24, 'text-primary-foreground'); ?>
                    </div>
                    <h3 class="font-semibold mb-2"><?php esc_html_e('SEO & GEO Ready', 'pdfviewer'); ?></h3>
                    <p class="text-muted-foreground text-sm">
                        <?php esc_html_e('Optimized for Google and AI tools like ChatGPT with structured data.', 'pdfviewer'); ?>
                    </p>
                </div>
                <div class="bg-card rounded-2xl p-6 border border-border text-center">
                    <div class="w-12 h-12 rounded-xl gradient-hero flex items-center justify-center mx-auto mb-4">
                        <?php echo pdfviewer_icon('zap', 24, 'text-primary-foreground'); ?>
                    </div>
                    <h3 class="font-semibold mb-2"><?php esc_html_e('Fast Loading', 'pdfviewer'); ?></h3>
                    <p class="text-muted-foreground text-sm">
                        <?php esc_html_e('AJAX loading keeps your pages fast. PDFs load only when viewed.', 'pdfviewer'); ?>
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Download Free WordPress Plugin -->
<section id="download-wordpress" class="py-16 lg:py-24" aria-labelledby="download-wordpress-heading">
    <div class="container mx-auto px-4 lg:px-8">
        <div class="max-w-3xl mx-auto text-center">
            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-primary/20 text-foreground text-sm font-medium mb-6">
                <?php echo pdfviewer_icon('download', 16); ?>
                <span><?php esc_html_e('Free Download', 'pdfviewer'); ?></span>
            </div>
            <h2 id="download-wordpress-heading" class="text-3xl font-bold mb-6">
                <?php esc_html_e('Download Free WordPress PDF Viewer Plugin', 'pdfviewer'); ?>
            </h2>
            <p class="text-lg text-muted-foreground mb-8 max-w-2xl mx-auto">
                <?php esc_html_e('Get the most complete PDF embedding solution for WordPress. SEO-optimized, responsive, and fully customizable. No registration or credit card required.', 'pdfviewer'); ?>
            </p>
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                <a href="https://wordpress.org/plugins/pdf-embed-seo-optimize?ref=pdfviewer"
                   target="_blank"
                   rel="noopener"
                   class="btn btn-primary btn-lg gradient-hero shadow-glow gap-2 inline-flex items-center focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2"
                   aria-label="<?php esc_attr_e('Download PDF Embed & SEO Optimize from WordPress.org (opens in new tab)', 'pdfviewer'); ?>"
                   title="<?php esc_attr_e('Download the free WordPress PDF viewer plugin from WordPress.org', 'pdfviewer'); ?>">
                    <?php pdfviewer_wordpress_icon(20); ?>
                    <?php esc_html_e('Download from WordPress.org', 'pdfviewer'); ?>
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
