<?php
/**
 * Front Page Template
 * Homepage with all sections
 *
 * @package PDFViewer
 */

get_header();

$steps = array(
    array(
        'step'        => '1',
        'icon'        => 'download',
        'title'       => 'Install the Plugin',
        'description' => 'Add it to your WordPress site in just a few clicks - it\'s completely free and takes less than a minute.',
    ),
    array(
        'step'        => '2',
        'icon'        => 'file-text',
        'title'       => 'Upload Your Documents',
        'description' => 'Add your PDFs and give them titles and descriptions. No technical knowledge needed.',
    ),
    array(
        'step'        => '3',
        'icon'        => 'zap',
        'title'       => 'Start Sharing',
        'description' => 'Your documents are now ready to be found on Google and shared on social media with professional previews.',
    ),
);

$faqs = array(
    array(
        'question' => 'Is this PDF plugin really free?',
        'answer'   => 'Yes! PDF Embed & SEO Optimize is completely free and open source for both WordPress and Drupal. You get all features—Mozilla PDF.js viewer, full SEO optimization, JSON-LD schema, social sharing cards, and view statistics—at no cost.',
    ),
    array(
        'question' => 'Is the plugin available for both WordPress and Drupal?',
        'answer'   => 'Yes! PDF Embed & SEO Optimize is available as a WordPress plugin and a Drupal module. Both versions include the same core features: Mozilla PDF.js viewer, SEO optimization, JSON-LD schema, and social sharing previews.',
    ),
    array(
        'question' => 'How does this plugin help my PDFs get discovered by AI tools like ChatGPT?',
        'answer'   => 'The plugin optimizes your PDFs for Generative Engine Optimization (GEO). Your content is structured with JSON-LD schema, semantic HTML, and rich metadata that AI language models can easily understand, index, and cite.',
    ),
    array(
        'question' => 'What is Generative Engine Optimization (GEO)?',
        'answer'   => 'GEO is an advanced SEO strategy focused on making your content discoverable by AI and Large Language Models (LLMs). It goes beyond traditional SEO by ensuring your PDFs have clear metadata, structured data, and semantic markup.',
    ),
    array(
        'question' => 'Will this plugin slow down my website?',
        'answer'   => 'Not at all. PDFs load via AJAX only when visitors view them, keeping your pages fast. The bundled PDF.js viewer is optimized for performance, and thumbnails are auto-generated and cached.',
    ),
    array(
        'question' => 'Does this PDF plugin work with my theme?',
        'answer'   => 'Yes! The plugin works with any WordPress or Drupal theme and includes light/dark theme options for the viewer. It\'s compatible with page builders like Elementor, Divi, Gutenberg, Layout Builder, and Paragraphs.',
    ),
    array(
        'question' => 'How does PDF SEO optimization work?',
        'answer'   => 'Each PDF becomes a proper webpage with its own URL, title tag, meta description, and JSON-LD DigitalDocument schema. The plugin integrates with Yoast SEO and automatically adds your PDFs to an XML sitemap.',
    ),
    array(
        'question' => 'Can I control PDF downloads?',
        'answer'   => 'Yes! You have per-PDF toggle controls for both print and download options. The plugin also hides direct file URLs via AJAX loading, making it harder for people to share or scrape your files.',
    ),
);
?>

<!-- Hero Section -->
<section class="hero-section relative pt-8 pb-10 md:pt-12 md:pb-12 lg:pt-16 lg:pb-14 overflow-hidden" aria-labelledby="hero-heading">
    <div class="absolute inset-0 gradient-hero opacity-5" aria-hidden="true"></div>
    <div class="hidden md:block absolute top-20 left-10 w-72 h-72 bg-primary/20 rounded-full blur-3xl" aria-hidden="true"></div>
    <div class="hidden md:block absolute bottom-20 right-10 w-96 h-96 bg-accent/20 rounded-full blur-3xl" aria-hidden="true"></div>

    <div class="container mx-auto px-4 lg:px-8 relative">
        <div class="max-w-4xl mx-auto text-center">
            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-primary/10 text-primary text-sm font-medium mb-8 animate-fade-in">
                <?php echo pdfviewer_icon('zap', 16); ?>
                <span><?php esc_html_e('Free for WordPress, Drupal & React / Next.js', 'pdfviewer'); ?></span>
            </div>

            <h1 id="hero-heading" class="text-4xl md:text-5xl lg:text-6xl font-extrabold tracking-tight leading-tight mb-6 animate-fade-in" style="animation-delay: 0.1s">
                <?php esc_html_e('Share PDFs That', 'pdfviewer'); ?> <span class="text-gradient"><?php esc_html_e('Get Found', 'pdfviewer'); ?></span>
            </h1>

            <p class="text-xl md:text-2xl text-muted-foreground max-w-2xl mx-auto mb-10 animate-fade-in" style="animation-delay: 0.2s">
                <?php esc_html_e('Help customers find your documents on Google and AI tools like ChatGPT. Display PDFs beautifully on any device and track who\'s reading them.', 'pdfviewer'); ?>
            </p>

            <div class="flex flex-col items-center gap-4 mb-16 animate-fade-in" style="animation-delay: 0.3s">
                <div class="flex flex-col items-center justify-center gap-4 w-full max-w-md">
                    <a href="<?php echo esc_url(home_url('/wordpress-pdf-viewer/')); ?>"
                       class="btn btn-primary btn-xl gap-2 gradient-hero shadow-glow w-full justify-center"
                       aria-label="<?php esc_attr_e('Download PDF Embed & SEO Optimize for WordPress', 'pdfviewer'); ?>"
                       title="<?php esc_attr_e('Free download from WordPress.org', 'pdfviewer'); ?>">
                        <?php pdfviewer_wordpress_icon(20); ?>
                        <?php esc_html_e('Download for WordPress', 'pdfviewer'); ?>
                    </a>
                    <a href="<?php echo esc_url(home_url('/drupal-pdf-viewer/')); ?>"
                       class="btn btn-outline btn-xl gap-2 w-full justify-center"
                       aria-label="<?php esc_attr_e('Get PDF Embed & SEO Optimize module for Drupal', 'pdfviewer'); ?>"
                       title="<?php esc_attr_e('Learn about the Drupal module', 'pdfviewer'); ?>">
                        <?php pdfviewer_drupal_icon(20); ?>
                        <?php esc_html_e('Get Drupal Module', 'pdfviewer'); ?>
                    </a>
                    <a href="<?php echo esc_url(home_url('/nextjs-pdf-viewer/')); ?>"
                       class="btn btn-outline btn-xl gap-2 w-full justify-center font-mono text-base"
                       aria-label="<?php esc_attr_e('Install via npm for React / Next.js', 'pdfviewer'); ?>"
                       title="<?php esc_attr_e('npm install for React / Next.js', 'pdfviewer'); ?>">
                        <?php pdfviewer_react_icon(18); ?>
                        <?php esc_html_e('npm install @pdf-embed-seo/react', 'pdfviewer'); ?>
                    </a>
                </div>
                <a href="<?php echo esc_url(home_url('/examples/')); ?>"
                   class="inline-flex items-center gap-2 text-muted-foreground hover:text-primary transition-colors"
                   aria-label="<?php esc_attr_e('View live examples of PDF Embed & SEO Optimize in action', 'pdfviewer'); ?>">
                    <?php esc_html_e('View Live Examples', 'pdfviewer'); ?>
                    <?php echo pdfviewer_icon('arrow-right', 16); ?>
                </a>
            </div>

            <ul class="flex flex-col sm:flex-row flex-wrap items-center justify-center gap-3 animate-fade-in list-none w-full" style="animation-delay: 0.4s">
                <li class="flex items-center gap-2 px-4 py-2 bg-card rounded-full shadow-soft">
                    <?php echo pdfviewer_icon('file-text', 16, 'text-primary'); ?>
                    <span class="text-sm font-medium"><?php esc_html_e('Beautiful Viewer', 'pdfviewer'); ?></span>
                </li>
                <li class="flex items-center gap-2 px-4 py-2 bg-card rounded-full shadow-soft">
                    <?php echo pdfviewer_icon('search', 16, 'text-primary'); ?>
                    <span class="text-sm font-medium"><?php esc_html_e('SEO & GEO Ready', 'pdfviewer'); ?></span>
                </li>
                <li class="flex items-center gap-2 px-4 py-2 bg-card rounded-full shadow-soft">
                    <?php echo pdfviewer_icon('zap', 16, 'text-accent'); ?>
                    <span class="text-sm font-medium"><?php esc_html_e('Found by AI & LLMs', 'pdfviewer'); ?></span>
                </li>
            </ul>
        </div>

        <!-- Browser Mockup -->
        <figure class="max-w-5xl mx-auto mt-16 animate-fade-in-up" style="animation-delay: 0.5s">
            <div class="relative rounded-xl overflow-hidden shadow-large bg-card border border-border/50">
                <!-- Browser Chrome -->
                <div class="flex items-center gap-2 px-3 py-2.5 bg-muted/80 border-b border-border" aria-hidden="true">
                    <div class="flex gap-1.5">
                        <div class="w-3 h-3 rounded-full bg-red-400"></div>
                        <div class="w-3 h-3 rounded-full bg-yellow-400"></div>
                        <div class="w-3 h-3 rounded-full bg-green-400"></div>
                    </div>
                    <!-- URL Input -->
                    <div class="flex-1 flex items-center gap-2 bg-background rounded-lg px-4 py-1.5 ml-2 mr-3">
                        <?php echo pdfviewer_icon('lock', 12, 'text-green-600'); ?>
                        <span class="text-xs sm:text-sm text-muted-foreground whitespace-nowrap">domain.com/pdf/brochure/</span>
                    </div>
                </div>

                <!-- Browser Content -->
                <div class="aspect-[16/9] bg-gradient-to-br from-primary/5 to-accent/5 flex items-center justify-center">
                    <div class="text-center">
                        <img
                            src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/pdf-icon.svg'); ?>"
                            alt="<?php esc_attr_e('PDF document icon', 'pdfviewer'); ?>"
                            class="w-20 h-auto mx-auto mb-6 drop-shadow-lg animate-float"
                            width="64"
                            height="80"
                        />
                        <p class="text-lg font-semibold text-foreground"><?php esc_html_e('PDF Viewer Preview', 'pdfviewer'); ?></p>
                        <p class="text-sm text-muted-foreground"><?php esc_html_e('Display PDFs beautifully on WordPress, Drupal, React & Next.js. Get discovered by search engines and AI.', 'pdfviewer'); ?></p>
                    </div>
                </div>
            </div>
        </figure>
    </div>
</section>

<!-- Problem Section -->
<section class="py-8 lg:py-10 bg-card" aria-labelledby="problem-heading">
    <div class="container mx-auto px-4 lg:px-8">
        <header class="max-w-3xl mx-auto text-center mb-16">
            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-destructive/10 text-destructive text-sm font-medium mb-6">
                <?php echo pdfviewer_icon('x-circle', 16); ?>
                <span><?php esc_html_e('The Problem', 'pdfviewer'); ?></span>
            </div>
            <h2 id="problem-heading" class="text-3xl md:text-4xl font-bold mb-6">
                <?php esc_html_e('Your PDFs Are Working Against You', 'pdfviewer'); ?>
            </h2>
            <p class="text-lg text-muted-foreground">
                <?php esc_html_e('Right now, uploading PDFs to WordPress means losing control over how customers find and experience your important documents.', 'pdfviewer'); ?>
            </p>
        </header>

        <ul class="grid md:grid-cols-2 lg:grid-cols-3 gap-6 list-none">
            <li class="relative p-6 rounded-2xl bg-background border border-destructive/20 shadow-soft animate-fade-in">
                <div class="w-10 h-10 rounded-xl bg-destructive/10 flex items-center justify-center mb-4">
                    <?php echo pdfviewer_icon('link', 20, 'text-destructive'); ?>
                </div>
                <h3 class="text-lg font-semibold mb-2"><?php esc_html_e('URL Exposes File Location', 'pdfviewer'); ?></h3>
                <p class="text-sm text-muted-foreground"><?php esc_html_e('Direct PDF links like /uploads/doc.pdf look unprofessional and expose your file structure to everyone.', 'pdfviewer'); ?></p>
            </li>
            <li class="relative p-6 rounded-2xl bg-background border border-destructive/20 shadow-soft animate-fade-in" style="animation-delay: 0.1s">
                <div class="w-10 h-10 rounded-xl bg-destructive/10 flex items-center justify-center mb-4">
                    <?php echo pdfviewer_icon('search', 20, 'text-destructive'); ?>
                </div>
                <h3 class="text-lg font-semibold mb-2"><?php esc_html_e('No SEO Value', 'pdfviewer'); ?></h3>
                <p class="text-sm text-muted-foreground"><?php esc_html_e('Search engines treat PDFs as files, not pages. No title tags, meta descriptions, or structured data means no rankings.', 'pdfviewer'); ?></p>
            </li>
            <li class="relative p-6 rounded-2xl bg-background border border-destructive/20 shadow-soft animate-fade-in" style="animation-delay: 0.2s">
                <div class="w-10 h-10 rounded-xl bg-destructive/10 flex items-center justify-center mb-4">
                    <?php echo pdfviewer_icon('external-link', 20, 'text-destructive'); ?>
                </div>
                <h3 class="text-lg font-semibold mb-2"><?php esc_html_e('No Social Sharing Preview', 'pdfviewer'); ?></h3>
                <p class="text-sm text-muted-foreground"><?php esc_html_e('When someone shares your PDF link on social media, it shows nothing—no image, no description, no engagement.', 'pdfviewer'); ?></p>
            </li>
            <li class="relative p-6 rounded-2xl bg-background border border-destructive/20 shadow-soft animate-fade-in" style="animation-delay: 0.3s">
                <div class="w-10 h-10 rounded-xl bg-destructive/10 flex items-center justify-center mb-4">
                    <?php echo pdfviewer_icon('eye', 20, 'text-destructive'); ?>
                </div>
                <h3 class="text-lg font-semibold mb-2"><?php esc_html_e('No Control or Analytics', 'pdfviewer'); ?></h3>
                <p class="text-sm text-muted-foreground"><?php esc_html_e('You can\'t track views, control print/download options, or know which documents are actually being read.', 'pdfviewer'); ?></p>
            </li>
            <li class="relative p-6 rounded-2xl bg-background border border-destructive/20 shadow-soft animate-fade-in" style="animation-delay: 0.4s">
                <div class="w-10 h-10 rounded-xl bg-destructive/10 flex items-center justify-center mb-4">
                    <?php echo pdfviewer_icon('external-link', 20, 'text-destructive'); ?>
                </div>
                <h3 class="text-lg font-semibold mb-2"><?php esc_html_e('Users Leave Your Site', 'pdfviewer'); ?></h3>
                <p class="text-sm text-muted-foreground"><?php esc_html_e('PDFs open in the browser\'s default viewer, taking visitors away from your brand and website experience.', 'pdfviewer'); ?></p>
            </li>
            <li class="relative p-6 rounded-2xl bg-background border border-destructive/20 shadow-soft animate-fade-in" style="animation-delay: 0.5s">
                <div class="w-10 h-10 rounded-xl bg-destructive/10 flex items-center justify-center mb-4">
                    <?php echo pdfviewer_icon('file-text', 20, 'text-destructive'); ?>
                </div>
                <h3 class="text-lg font-semibold mb-2"><?php esc_html_e('Inconsistent Mobile Experience', 'pdfviewer'); ?></h3>
                <p class="text-sm text-muted-foreground"><?php esc_html_e('Mobile viewing varies wildly by device and browser. Some visitors can\'t read your documents at all.', 'pdfviewer'); ?></p>
            </li>
        </ul>

        <div class="mt-16 max-w-3xl mx-auto">
            <div class="p-6 rounded-xl bg-destructive/5 border border-destructive/20">
                <p class="text-sm font-medium text-destructive mb-2">❌ <?php esc_html_e('What your customers see now:', 'pdfviewer'); ?></p>
                <code class="text-sm text-muted-foreground break-all"><a href="<?php echo esc_url(home_url('/wp-content/uploads/2025/03/example-1.pdf')); ?>" title="Example of unoptimized PDF URL in WordPress uploads folder" target="_blank" rel="noopener">yourdomain.com/wp-content/uploads/2025/03/example-document.pdf</a></code>
                <p class="text-xs text-muted-foreground mt-3"><?php esc_html_e('A messy link that doesn\'t tell anyone what the document is about', 'pdfviewer'); ?></p>
            </div>
        </div>
    </div>
</section>

<!-- Solution Section -->
<section id="features" class="py-8 lg:py-10" aria-labelledby="solution-heading">
    <div class="container mx-auto px-4 lg:px-8">
        <header class="max-w-3xl mx-auto text-center mb-16">
            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-primary/10 text-primary text-sm font-medium mb-6">
                <?php echo pdfviewer_icon('check', 16); ?>
                <span><?php esc_html_e('The Solution', 'pdfviewer'); ?></span>
            </div>
            <h2 id="solution-heading" class="text-3xl md:text-4xl font-bold mb-6">
                <span class="text-gradient"><?php esc_html_e('Make Your PDFs Work Harder', 'pdfviewer'); ?></span>
            </h2>
            <p class="text-lg text-muted-foreground">
                <?php esc_html_e('Turn your documents into professional web pages that customers can find, read, and share easily.', 'pdfviewer'); ?>
            </p>
        </header>

        <div class="max-w-3xl mx-auto mb-16">
            <div class="p-6 rounded-xl bg-primary/5 border border-primary/20">
                <p class="text-sm font-medium text-primary mb-2">✅ <?php esc_html_e('What your customers see with our plugin:', 'pdfviewer'); ?></p>
                <code class="text-sm text-primary font-semibold break-all"><a href="<?php echo esc_url(home_url('/pdf/example-1/')); ?>" title="SEO-optimized PDF page with clean URL structure" target="_blank" rel="noopener">yourdomain.com/pdf/product-brochure/</a></code>
                <p class="text-xs text-muted-foreground mt-3"><?php esc_html_e('A clean, professional link that tells everyone exactly what they\'ll find', 'pdfviewer'); ?></p>
            </div>
        </div>

        <ul class="grid md:grid-cols-2 lg:grid-cols-3 gap-6 list-none">
            <li class="group p-6 rounded-2xl bg-card border border-border shadow-soft hover:shadow-medium hover:border-primary/30 transition-all duration-300 animate-fade-in">
                <div class="w-10 h-10 rounded-xl gradient-hero flex items-center justify-center mb-4 group-hover:shadow-glow transition-shadow">
                    <?php echo pdfviewer_icon('link', 20, 'text-primary-foreground'); ?>
                </div>
                <h3 class="text-lg font-semibold mb-2"><?php esc_html_e('Clean, Branded URLs', 'pdfviewer'); ?></h3>
                <p class="text-sm text-muted-foreground"><?php esc_html_e('Each PDF gets a professional URL like /pdf/annual-report/ instead of messy upload paths.', 'pdfviewer'); ?></p>
            </li>
            <li class="group p-6 rounded-2xl bg-card border border-border shadow-soft hover:shadow-medium hover:border-primary/30 transition-all duration-300 animate-fade-in" style="animation-delay: 0.05s">
                <div class="w-10 h-10 rounded-xl gradient-hero flex items-center justify-center mb-4 group-hover:shadow-glow transition-shadow">
                    <?php echo pdfviewer_icon('search', 20, 'text-primary-foreground'); ?>
                </div>
                <h3 class="text-lg font-semibold mb-2"><?php esc_html_e('Full SEO Optimization', 'pdfviewer'); ?></h3>
                <p class="text-sm text-muted-foreground"><?php esc_html_e('Yoast SEO integration with title tags, meta descriptions, JSON-LD schema markup, and XML sitemap inclusion.', 'pdfviewer'); ?></p>
            </li>
            <li class="group p-6 rounded-2xl bg-card border border-border shadow-soft hover:shadow-medium hover:border-primary/30 transition-all duration-300 animate-fade-in" style="animation-delay: 0.1s">
                <div class="w-10 h-10 rounded-xl gradient-hero flex items-center justify-center mb-4 group-hover:shadow-glow transition-shadow">
                    <?php echo pdfviewer_icon('external-link', 20, 'text-primary-foreground'); ?>
                </div>
                <h3 class="text-lg font-semibold mb-2"><?php esc_html_e('OpenGraph & Twitter Cards', 'pdfviewer'); ?></h3>
                <p class="text-sm text-muted-foreground"><?php esc_html_e('Auto-generated thumbnails from your PDF\'s first page create beautiful social media previews.', 'pdfviewer'); ?></p>
            </li>
            <li class="group p-6 rounded-2xl bg-card border border-border shadow-soft hover:shadow-medium hover:border-primary/30 transition-all duration-300 animate-fade-in" style="animation-delay: 0.15s">
                <div class="w-10 h-10 rounded-xl gradient-hero flex items-center justify-center mb-4 group-hover:shadow-glow transition-shadow">
                    <?php echo pdfviewer_icon('lock', 20, 'text-primary-foreground'); ?>
                </div>
                <h3 class="text-lg font-semibold mb-2"><?php esc_html_e('Content Protection', 'pdfviewer'); ?></h3>
                <p class="text-sm text-muted-foreground"><?php esc_html_e('Hide direct file URLs and control print/download permissions per document via AJAX loading.', 'pdfviewer'); ?></p>
            </li>
            <li class="group p-6 rounded-2xl bg-card border border-border shadow-soft hover:shadow-medium hover:border-primary/30 transition-all duration-300 animate-fade-in" style="animation-delay: 0.2s">
                <div class="w-10 h-10 rounded-xl gradient-hero flex items-center justify-center mb-4 group-hover:shadow-glow transition-shadow">
                    <?php echo pdfviewer_icon('eye', 20, 'text-primary-foreground'); ?>
                </div>
                <h3 class="text-lg font-semibold mb-2"><?php esc_html_e('Built-in View Statistics', 'pdfviewer'); ?></h3>
                <p class="text-sm text-muted-foreground"><?php esc_html_e('Track how often each PDF is viewed and know which documents are most popular.', 'pdfviewer'); ?></p>
            </li>
            <li class="group p-6 rounded-2xl bg-card border border-border shadow-soft hover:shadow-medium hover:border-primary/30 transition-all duration-300 animate-fade-in" style="animation-delay: 0.25s">
                <div class="w-10 h-10 rounded-xl gradient-hero flex items-center justify-center mb-4 group-hover:shadow-glow transition-shadow">
                    <?php echo pdfviewer_icon('file-text', 20, 'text-primary-foreground'); ?>
                </div>
                <h3 class="text-lg font-semibold mb-2"><?php esc_html_e('Mozilla PDF.js Viewer', 'pdfviewer'); ?></h3>
                <p class="text-sm text-muted-foreground"><?php esc_html_e('Industry-standard rendering that works consistently across all browsers and devices.', 'pdfviewer'); ?></p>
            </li>
        </ul>
    </div>
</section>

<!-- Comparison Table -->
<section class="py-8 lg:py-10 bg-card" aria-labelledby="comparison-heading">
    <div class="container mx-auto px-4 lg:px-8">
        <header class="max-w-3xl mx-auto text-center mb-12">
            <h2 id="comparison-heading" class="text-3xl md:text-4xl font-bold mb-6">
                <?php esc_html_e('Why Use This Plugin Instead of Direct PDF Links?', 'pdfviewer'); ?>
            </h2>
            <p class="text-lg text-muted-foreground">
                <?php esc_html_e('See exactly what you gain by switching to PDF Embed & SEO Optimize', 'pdfviewer'); ?>
            </p>
        </header>

        <div class="max-w-5xl mx-auto">
            <?php
            $comparisons = array(
                array('feature' => 'URL Structure', 'without' => 'Exposes file location (/uploads/doc.pdf)', 'with' => 'Clean, branded URLs (/pdf/document-name/)'),
                array('feature' => 'SEO Value', 'without' => 'Search engines treat as file, no rankings', 'with' => 'Full SEO with Yoast integration, meta tags, schema markup'),
                array('feature' => 'Social Sharing', 'without' => 'No preview image or description', 'with' => 'OpenGraph & Twitter Cards with auto-generated thumbnails'),
                array('feature' => 'User Control', 'without' => 'No control over print/download', 'with' => 'Per-document print/download toggles'),
                array('feature' => 'Analytics', 'without' => 'No way to track views', 'with' => 'Built-in view statistics tracking'),
                array('feature' => 'Viewing Experience', 'without' => 'Users leave site to browser viewer', 'with' => 'Mozilla PDF.js viewer renders within your site'),
                array('feature' => 'Mobile Experience', 'without' => 'Varies wildly by device/browser', 'with' => 'Responsive design works on all devices'),
                array('feature' => 'Content Protection', 'without' => 'File URL easily shared/scraped', 'with' => 'Direct URLs hidden, harder to copy'),
                array('feature' => 'Schema Markup', 'without' => 'No structured data for search engines', 'with' => 'DigitalDocument and CollectionPage schemas'),
            );
            ?>

            <!-- Desktop Table -->
            <div class="hidden md:block overflow-hidden rounded-2xl border border-border shadow-soft">
                <table class="w-full" role="table" aria-label="<?php esc_attr_e('Feature comparison between direct PDF links and PDF Embed plugin', 'pdfviewer'); ?>">
                    <thead>
                        <tr class="bg-muted">
                            <th class="text-left py-4 px-6 font-semibold text-foreground" scope="col"><?php esc_html_e('Feature', 'pdfviewer'); ?></th>
                            <th class="text-left py-4 px-6 font-semibold text-destructive" scope="col">
                                <div class="flex items-center gap-2">
                                    <?php echo pdfviewer_icon('x-circle', 20); ?>
                                    <?php esc_html_e('Direct PDF Link', 'pdfviewer'); ?>
                                </div>
                            </th>
                            <th class="text-left py-4 px-6 font-semibold text-primary" scope="col">
                                <div class="flex items-center gap-2">
                                    <?php echo pdfviewer_icon('check', 20); ?>
                                    <?php esc_html_e('PDF Embed & SEO Optimize', 'pdfviewer'); ?>
                                </div>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($comparisons as $index => $row) : ?>
                            <tr class="<?php echo $index % 2 === 0 ? 'bg-background' : 'bg-muted/30'; ?>">
                                <td class="py-4 px-6 font-medium text-foreground"><?php echo esc_html($row['feature']); ?></td>
                                <td class="py-4 px-6 text-muted-foreground">
                                    <div class="flex items-start gap-2">
                                        <?php echo pdfviewer_icon('x-circle', 16, 'text-destructive shrink-0 mt-0.5'); ?>
                                        <span><?php echo esc_html($row['without']); ?></span>
                                    </div>
                                </td>
                                <td class="py-4 px-6 text-foreground">
                                    <div class="flex items-start gap-2">
                                        <?php echo pdfviewer_icon('check', 16, 'text-primary shrink-0 mt-0.5'); ?>
                                        <span><?php echo esc_html($row['with']); ?></span>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Mobile Cards -->
            <div class="md:hidden space-y-4">
                <?php foreach ($comparisons as $index => $row) : ?>
                    <div class="bg-background rounded-xl border border-border p-4 animate-fade-in" style="animation-delay: <?php echo esc_attr($index * 0.05); ?>s">
                        <h3 class="font-semibold text-foreground mb-3"><?php echo esc_html($row['feature']); ?></h3>
                        <div class="space-y-2">
                            <div class="flex items-start gap-2 text-sm">
                                <?php echo pdfviewer_icon('x-circle', 16, 'text-destructive shrink-0 mt-0.5'); ?>
                                <span class="text-muted-foreground"><?php echo esc_html($row['without']); ?></span>
                            </div>
                            <div class="flex items-start gap-2 text-sm">
                                <?php echo pdfviewer_icon('check', 16, 'text-primary shrink-0 mt-0.5'); ?>
                                <span class="text-foreground font-medium"><?php echo esc_html($row['with']); ?></span>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Summary -->
            <div class="mt-12 p-6 rounded-2xl bg-primary/5 border border-primary/20 text-center">
                <p class="text-lg font-medium text-foreground">
                    <?php esc_html_e('Each PDF becomes a proper webpage that Google can rank, with title tags, meta descriptions, and structured data.', 'pdfviewer'); ?>
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Key Benefits -->
<section class="py-8 lg:py-10 bg-background" aria-labelledby="benefits-heading">
    <div class="container mx-auto px-4 lg:px-8">
        <header class="max-w-3xl mx-auto text-center mb-12">
            <h2 id="benefits-heading" class="text-3xl md:text-4xl font-bold mb-6">
                <?php esc_html_e('6 Key Benefits at a Glance', 'pdfviewer'); ?>
            </h2>
            <p class="text-lg text-muted-foreground">
                <?php esc_html_e('Everything you need to make your PDFs work harder for your WordPress website', 'pdfviewer'); ?>
            </p>
        </header>

        <?php
        $benefits = array(
            array(
                'icon'        => 'search',
                'title'       => 'SEO & GEO Optimized',
                'description' => 'Full SEO with meta tags, Yoast integration, JSON-LD schema, and XML sitemap. Plus GEO optimization so AI tools and LLMs can discover your content.',
                'link'        => '/wordpress-pdf-viewer/',
                'link_text'   => 'Learn about WordPress PDF SEO',
            ),
            array(
                'icon'        => 'bot',
                'title'       => 'Found by AI & LLMs',
                'description' => 'Structured data and semantic markup help ChatGPT, Google AI, and other language models understand and surface your PDF content.',
                'link'        => null,
                'link_text'   => null,
            ),
            array(
                'icon'        => 'palette',
                'title'       => 'Brand Control',
                'description' => 'Clean branded URLs like /pdf/annual-report/ and consistent viewer experience matching your site design.',
                'link'        => '/wordpress-pdf-viewer/',
                'link_text'   => 'See the WordPress PDF viewer',
            ),
            array(
                'icon'        => 'shield',
                'title'       => 'Content Protection',
                'description' => 'AJAX loading hides direct file URLs, with per-document print and download toggles for complete control.',
                'link'        => null,
                'link_text'   => null,
            ),
            array(
                'icon'        => 'bar-chart-3',
                'title'       => 'Analytics',
                'description' => 'Built-in view statistics track document engagement so you know which PDFs resonate with your audience.',
                'link'        => '/pro/',
                'link_text'   => 'Get advanced analytics with Pro',
            ),
            array(
                'icon'        => 'share-2',
                'title'       => 'Social Sharing',
                'description' => 'Auto-generated OpenGraph and Twitter Cards with PDF thumbnails create compelling social previews.',
                'link'        => null,
                'link_text'   => null,
            ),
        );
        ?>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 max-w-6xl mx-auto">
            <?php foreach ($benefits as $index => $benefit) : ?>
                <article class="group p-6 rounded-2xl bg-card border border-border hover:border-primary/30 hover:shadow-soft transition-all duration-300 animate-fade-in" style="animation-delay: <?php echo esc_attr($index * 0.1); ?>s">
                    <div class="w-12 h-12 rounded-xl bg-primary/10 flex items-center justify-center mb-4 group-hover:bg-primary/20 transition-colors">
                        <?php echo pdfviewer_icon($benefit['icon'], 24, 'text-primary'); ?>
                    </div>
                    <h3 class="text-xl font-semibold text-foreground mb-2"><?php echo esc_html($benefit['title']); ?></h3>
                    <p class="text-muted-foreground leading-relaxed mb-3"><?php echo esc_html($benefit['description']); ?></p>
                    <?php if ($benefit['link']) : ?>
                        <a href="<?php echo esc_url(home_url($benefit['link'])); ?>"
                           class="inline-flex items-center gap-1 text-sm text-primary hover:text-primary/80 font-medium transition-colors">
                            <?php echo esc_html($benefit['link_text']); ?>
                            <?php echo pdfviewer_icon('arrow-right', 12); ?>
                        </a>
                    <?php endif; ?>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- GEO Section -->
<section class="py-8 lg:py-10" aria-labelledby="geo-heading">
    <div class="container mx-auto px-4 lg:px-8">
        <header class="max-w-3xl mx-auto text-center mb-16">
            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-primary/10 text-primary text-sm font-medium mb-6">
                <?php echo pdfviewer_icon('sparkles', 16); ?>
                <span><?php esc_html_e('Generative Engine Optimization', 'pdfviewer'); ?></span>
            </div>
            <h2 id="geo-heading" class="text-3xl md:text-4xl font-bold mb-6">
                <?php esc_html_e('Make Your PDFs AI-Ready', 'pdfviewer'); ?>
            </h2>
            <p class="text-lg text-muted-foreground">
                <?php esc_html_e('Go beyond traditional SEO. GEO ensures your PDF content is discovered, understood, and accurately cited by AI tools like ChatGPT, Perplexity, and Google AI Overview.', 'pdfviewer'); ?>
            </p>
        </header>

        <div class="max-w-5xl mx-auto mb-16">
            <p class="text-xl font-semibold text-center mb-8"><?php esc_html_e('How GEO Works', 'pdfviewer'); ?></p>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <article class="p-6 rounded-2xl bg-background border border-border hover:border-primary/30 transition-all duration-300 animate-fade-in">
                    <div class="w-12 h-12 rounded-xl bg-primary/10 flex items-center justify-center mb-4">
                        <?php echo pdfviewer_icon('file-text', 24, 'text-primary'); ?>
                    </div>
                    <h3 class="text-lg font-semibold text-foreground mb-2"><?php esc_html_e('Semantic Markup', 'pdfviewer'); ?></h3>
                    <p class="text-muted-foreground text-sm leading-relaxed"><?php esc_html_e('Clear HTML structure and schema.org vocabulary help AI understand document context and relationships.', 'pdfviewer'); ?></p>
                </article>
                <article class="p-6 rounded-2xl bg-background border border-border hover:border-primary/30 transition-all duration-300 animate-fade-in" style="animation-delay: 0.1s">
                    <div class="w-12 h-12 rounded-xl bg-primary/10 flex items-center justify-center mb-4">
                        <?php echo pdfviewer_icon('sparkles', 24, 'text-primary'); ?>
                    </div>
                    <h3 class="text-lg font-semibold text-foreground mb-2"><?php esc_html_e('JSON-LD Schema', 'pdfviewer'); ?></h3>
                    <p class="text-muted-foreground text-sm leading-relaxed"><?php esc_html_e('DigitalDocument structured data provides machine-readable metadata about title, author, and content.', 'pdfviewer'); ?></p>
                </article>
                <article class="p-6 rounded-2xl bg-background border border-border hover:border-primary/30 transition-all duration-300 animate-fade-in" style="animation-delay: 0.2s">
                    <div class="w-12 h-12 rounded-xl bg-primary/10 flex items-center justify-center mb-4">
                        <?php echo pdfviewer_icon('zap', 24, 'text-primary'); ?>
                    </div>
                    <h3 class="text-lg font-semibold text-foreground mb-2"><?php esc_html_e('Optimized Metadata', 'pdfviewer'); ?></h3>
                    <p class="text-muted-foreground text-sm leading-relaxed"><?php esc_html_e('Keywords, descriptions, and taxonomies embedded in a format AI models can easily parse and index.', 'pdfviewer'); ?></p>
                </article>
            </div>
        </div>

        <!-- How Your PDFs Appear in AI Responses -->
        <div class="max-w-5xl mx-auto mt-16">
            <p class="text-xl font-semibold text-center mb-8"><?php esc_html_e('How Your PDFs Appear in AI Responses', 'pdfviewer'); ?></p>
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <article class="group p-6 rounded-2xl bg-background border border-border hover:border-primary/30 hover:shadow-soft transition-all duration-300 animate-fade-in">
                    <div class="w-10 h-10 rounded-lg gradient-hero flex items-center justify-center mb-4 group-hover:opacity-90 transition-colors">
                        <?php echo pdfviewer_icon('quote', 20, 'text-primary-foreground'); ?>
                    </div>
                    <h3 class="text-lg font-semibold text-foreground mb-3"><?php esc_html_e('Direct Citations', 'pdfviewer'); ?></h3>
                    <blockquote class="text-sm italic text-foreground bg-muted rounded-lg p-3 mb-3 border-l-4 border-primary m-0">
                        <?php esc_html_e('"According to the 2024 Annual Report from YourCompany..."', 'pdfviewer'); ?>
                    </blockquote>
                    <p class="text-muted-foreground text-sm leading-relaxed m-0"><?php esc_html_e('AI tools cite your PDF as an authoritative source when answering user questions.', 'pdfviewer'); ?></p>
                </article>
                <article class="group p-6 rounded-2xl bg-background border border-border hover:border-primary/30 hover:shadow-soft transition-all duration-300 animate-fade-in" style="animation-delay: 0.15s">
                    <div class="w-10 h-10 rounded-lg gradient-hero flex items-center justify-center mb-4 group-hover:opacity-90 transition-colors">
                        <?php echo pdfviewer_icon('message-square', 20, 'text-primary-foreground'); ?>
                    </div>
                    <h3 class="text-lg font-semibold text-foreground mb-3"><?php esc_html_e('Content Summarization', 'pdfviewer'); ?></h3>
                    <blockquote class="text-sm italic text-foreground bg-muted rounded-lg p-3 mb-3 border-l-4 border-primary m-0">
                        <?php esc_html_e('"The whitepaper highlights three key findings: improved efficiency, cost reduction, and..."', 'pdfviewer'); ?>
                    </blockquote>
                    <p class="text-muted-foreground text-sm leading-relaxed m-0"><?php esc_html_e('LLMs accurately summarize your PDF\'s key points in conversational responses.', 'pdfviewer'); ?></p>
                </article>
                <article class="group p-6 rounded-2xl bg-background border border-border hover:border-primary/30 hover:shadow-soft transition-all duration-300 animate-fade-in" style="animation-delay: 0.3s">
                    <div class="w-10 h-10 rounded-lg gradient-hero flex items-center justify-center mb-4 group-hover:opacity-90 transition-colors">
                        <?php echo pdfviewer_icon('search', 20, 'text-primary-foreground'); ?>
                    </div>
                    <h3 class="text-lg font-semibold text-foreground mb-3"><?php esc_html_e('Thematic Discovery', 'pdfviewer'); ?></h3>
                    <blockquote class="text-sm italic text-foreground bg-muted rounded-lg p-3 mb-3 border-l-4 border-primary m-0">
                        <?php esc_html_e('"For more details, see the comprehensive guide available at yourdomain.com/pdf/..."', 'pdfviewer'); ?>
                    </blockquote>
                    <p class="text-muted-foreground text-sm leading-relaxed m-0"><?php esc_html_e('Your PDFs appear as recommended resources when users explore related topics.', 'pdfviewer'); ?></p>
                </article>
            </div>
        </div>

        <p class="text-center text-muted-foreground mt-12 max-w-2xl mx-auto">
            <?php esc_html_e('With GEO optimization, your valuable PDF content stays at the forefront of information discovery—even as AI technologies continue to advance.', 'pdfviewer'); ?>
        </p>
    </div>
</section>

<!-- XML Sitemap Section -->
<section class="py-8 lg:py-10" aria-labelledby="sitemap-heading">
    <div class="container mx-auto px-4 lg:px-8">
        <div class="max-w-4xl mx-auto">
            <div class="grid lg:grid-cols-2 gap-12 items-center">
                <!-- Content -->
                <div class="animate-fade-in">
                    <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-300 text-sm font-medium mb-6">
                        <?php echo pdfviewer_icon('globe', 16); ?>
                        <span><?php esc_html_e('Automatic Sitemap', 'pdfviewer'); ?></span>
                    </div>
                    <h2 id="sitemap-heading" class="text-3xl md:text-4xl font-bold mb-6">
                        <?php esc_html_e('Let Google Find Your Documents', 'pdfviewer'); ?>
                    </h2>
                    <p class="text-lg text-muted-foreground mb-6">
                        <?php esc_html_e('The plugin automatically creates a special sitemap just for your PDFs. Submit it to Google Search Console and watch your documents appear in search results.', 'pdfviewer'); ?>
                    </p>

                    <ul class="space-y-4 list-none" role="list">
                        <li class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded-lg bg-primary/10 flex items-center justify-center shrink-0 mt-0.5">
                                <?php echo pdfviewer_icon('search', 16, 'text-primary'); ?>
                            </div>
                            <div>
                                <h3 class="font-semibold mb-1"><?php esc_html_e('Faster Indexing', 'pdfviewer'); ?></h3>
                                <p class="text-sm text-muted-foreground">
                                    <?php esc_html_e('Google discovers your new PDFs automatically, without you having to do anything.', 'pdfviewer'); ?>
                                </p>
                            </div>
                        </li>
                        <li class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded-lg bg-primary/10 flex items-center justify-center shrink-0 mt-0.5">
                                <?php echo pdfviewer_icon('file-text', 16, 'text-primary'); ?>
                            </div>
                            <div>
                                <h3 class="font-semibold mb-1"><?php esc_html_e('All Documents Included', 'pdfviewer'); ?></h3>
                                <p class="text-sm text-muted-foreground">
                                    <?php esc_html_e('Every PDF you publish is automatically added to the sitemap.', 'pdfviewer'); ?>
                                </p>
                            </div>
                        </li>
                    </ul>
                </div>

                <!-- Visual -->
                <figure class="animate-fade-in" style="animation-delay: 0.2s" aria-label="<?php esc_attr_e('XML Sitemap example', 'pdfviewer'); ?>">
                    <div class="bg-card rounded-2xl border border-border p-6 shadow-soft">
                        <div class="flex items-center gap-2 mb-4" aria-hidden="true">
                            <div class="flex gap-1.5">
                                <div class="w-3 h-3 rounded-full bg-destructive/60"></div>
                                <div class="w-3 h-3 rounded-full bg-accent/60"></div>
                                <div class="w-3 h-3 rounded-full bg-primary/60"></div>
                            </div>
                            <span class="text-xs text-muted-foreground ml-2">sitemap.xml</span>
                        </div>

                        <div class="bg-muted rounded-lg p-4 mb-4">
                            <p class="text-sm font-medium text-muted-foreground mb-2"><?php esc_html_e('Your PDF sitemap:', 'pdfviewer'); ?></p>
                            <code class="text-sm text-primary font-semibold break-all">
                                yourdomain.com/pdf/sitemap.xml
                            </code>
                        </div>

                        <ul class="space-y-2 text-sm list-none" role="list">
                            <li class="flex items-center gap-2 text-muted-foreground">
                                <?php echo pdfviewer_icon('arrow-right', 16, 'text-primary'); ?>
                                <span><?php esc_html_e('Contains all your PDF pages', 'pdfviewer'); ?></span>
                            </li>
                            <li class="flex items-center gap-2 text-muted-foreground">
                                <?php echo pdfviewer_icon('arrow-right', 16, 'text-primary'); ?>
                                <span><?php esc_html_e('Updates automatically', 'pdfviewer'); ?></span>
                            </li>
                            <li class="flex items-center gap-2 text-muted-foreground">
                                <?php echo pdfviewer_icon('arrow-right', 16, 'text-primary'); ?>
                                <span><?php esc_html_e('Ready for Google Search Console', 'pdfviewer'); ?></span>
                            </li>
                        </ul>
                    </div>
                </figure>
            </div>
        </div>
    </div>
</section>

<!-- How It Works -->
<section class="py-8 lg:py-10" aria-labelledby="how-it-works-heading">
    <div class="container mx-auto px-4 lg:px-8">
        <header class="max-w-3xl mx-auto text-center mb-16">
            <h2 id="how-it-works-heading" class="text-3xl md:text-4xl font-bold mb-6">
                <?php esc_html_e('Up and Running in 3 Easy Steps', 'pdfviewer'); ?>
            </h2>
            <p class="text-lg text-muted-foreground">
                <?php esc_html_e('No technical skills required. If you can use WordPress, you can use this plugin.', 'pdfviewer'); ?>
            </p>
        </header>

        <div class="max-w-4xl mx-auto">
            <div class="relative">
                <div class="hidden md:block absolute top-24 left-1/2 -translate-x-1/2 w-2/3 h-0.5 bg-gradient-to-r from-primary via-accent to-primary" aria-hidden="true"></div>

                <ol class="grid md:grid-cols-3 gap-8 list-none pt-8">
                    <?php foreach ($steps as $index => $item) : ?>
                        <li class="relative text-center animate-fade-in pt-4" style="animation-delay: <?php echo esc_attr($index * 0.2); ?>s">
                            <div class="relative z-10 w-20 h-20 mx-auto mb-6 gradient-hero rounded-2xl flex items-center justify-center shadow-glow">
                                <?php echo pdfviewer_icon($item['icon'], 32, 'text-primary-foreground'); ?>
                            </div>
                            <div class="hidden md:block absolute top-0 left-1/2 -translate-x-1/2 text-6xl font-bold text-muted/30" aria-hidden="true">
                                <?php echo esc_html($item['step']); ?>
                            </div>
                            <h3 class="text-xl font-semibold mb-3"><?php echo esc_html($item['title']); ?></h3>
                            <p class="text-muted-foreground"><?php echo esc_html($item['description']); ?></p>
                        </li>
                    <?php endforeach; ?>
                </ol>
            </div>
        </div>
    </div>
</section>

<!-- Key Features -->
<section class="py-8 lg:py-10" aria-labelledby="features-heading">
    <div class="container mx-auto px-4 lg:px-8">
        <header class="max-w-3xl mx-auto text-center mb-16">
            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-primary/10 text-primary text-sm font-medium mb-6">
                <?php echo pdfviewer_icon('zap', 16); ?>
                <span><?php esc_html_e('Core Features', 'pdfviewer'); ?></span>
            </div>
            <h2 id="features-heading" class="text-3xl md:text-4xl font-bold mb-6">
                <?php esc_html_e('Everything You Need for PDFs', 'pdfviewer'); ?>
            </h2>
            <p class="text-lg text-muted-foreground">
                <?php esc_html_e('A complete solution for embedding, optimizing, and tracking your PDF documents.', 'pdfviewer'); ?>
            </p>
        </header>

        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6 max-w-6xl mx-auto">
            <div class="bg-card rounded-2xl p-6 border border-border animate-fade-in">
                <div class="w-12 h-12 rounded-xl gradient-hero flex items-center justify-center mb-4">
                    <?php echo pdfviewer_icon('file-text', 24, 'text-primary-foreground'); ?>
                </div>
                <h3 class="text-xl font-semibold mb-2"><?php esc_html_e('Beautiful PDF Viewer', 'pdfviewer'); ?></h3>
                <p class="text-muted-foreground"><?php esc_html_e('Mozilla PDF.js provides consistent, beautiful rendering across all browsers and devices.', 'pdfviewer'); ?></p>
            </div>

            <div class="bg-card rounded-2xl p-6 border border-border animate-fade-in" style="animation-delay: 0.1s">
                <div class="w-12 h-12 rounded-xl gradient-hero flex items-center justify-center mb-4">
                    <?php echo pdfviewer_icon('search', 24, 'text-primary-foreground'); ?>
                </div>
                <h3 class="text-xl font-semibold mb-2"><?php esc_html_e('SEO Optimized', 'pdfviewer'); ?></h3>
                <p class="text-muted-foreground"><?php esc_html_e('Clean URLs, JSON-LD schema, XML sitemaps, and Yoast SEO integration for maximum visibility.', 'pdfviewer'); ?></p>
            </div>

            <div class="bg-card rounded-2xl p-6 border border-border animate-fade-in" style="animation-delay: 0.2s">
                <div class="w-12 h-12 rounded-xl gradient-hero flex items-center justify-center mb-4">
                    <?php echo pdfviewer_icon('sparkles', 24, 'text-primary-foreground'); ?>
                </div>
                <h3 class="text-xl font-semibold mb-2"><?php esc_html_e('AI & GEO Ready', 'pdfviewer'); ?></h3>
                <p class="text-muted-foreground"><?php esc_html_e('Optimized for ChatGPT, Perplexity, and other AI tools with structured data.', 'pdfviewer'); ?></p>
            </div>

            <div class="bg-card rounded-2xl p-6 border border-border animate-fade-in" style="animation-delay: 0.3s">
                <div class="w-12 h-12 rounded-xl gradient-hero flex items-center justify-center mb-4">
                    <?php echo pdfviewer_icon('external-link', 24, 'text-primary-foreground'); ?>
                </div>
                <h3 class="text-xl font-semibold mb-2"><?php esc_html_e('Social Sharing', 'pdfviewer'); ?></h3>
                <p class="text-muted-foreground"><?php esc_html_e('Auto-generated OpenGraph and Twitter cards with PDF thumbnail previews.', 'pdfviewer'); ?></p>
            </div>

            <div class="bg-card rounded-2xl p-6 border border-border animate-fade-in" style="animation-delay: 0.4s">
                <div class="w-12 h-12 rounded-xl gradient-hero flex items-center justify-center mb-4">
                    <?php echo pdfviewer_icon('eye', 24, 'text-primary-foreground'); ?>
                </div>
                <h3 class="text-xl font-semibold mb-2"><?php esc_html_e('View Analytics', 'pdfviewer'); ?></h3>
                <p class="text-muted-foreground"><?php esc_html_e('Track how many people view each PDF with built-in statistics.', 'pdfviewer'); ?></p>
            </div>

            <div class="bg-card rounded-2xl p-6 border border-border animate-fade-in" style="animation-delay: 0.5s">
                <div class="w-12 h-12 rounded-xl gradient-hero flex items-center justify-center mb-4">
                    <?php echo pdfviewer_icon('lock', 24, 'text-primary-foreground'); ?>
                </div>
                <h3 class="text-xl font-semibold mb-2"><?php esc_html_e('Content Control', 'pdfviewer'); ?></h3>
                <p class="text-muted-foreground"><?php esc_html_e('Control print and download permissions per PDF. Hide direct file URLs.', 'pdfviewer'); ?></p>
            </div>
        </div>
    </div>
</section>

<!-- FAQ -->
<section id="faq" class="py-8 lg:py-10 bg-card" aria-labelledby="faq-heading">
    <div class="container mx-auto px-4 lg:px-8">
        <div class="max-w-3xl mx-auto">
            <header class="text-center mb-12 animate-fade-in">
                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-primary/10 text-primary text-sm font-medium mb-6">
                    <?php echo pdfviewer_icon('file-text', 16); ?>
                    <span><?php esc_html_e('Common Questions', 'pdfviewer'); ?></span>
                </div>
                <h2 id="faq-heading" class="text-3xl md:text-4xl font-bold mb-4">
                    <?php esc_html_e('Frequently Asked Questions', 'pdfviewer'); ?>
                </h2>
                <p class="text-lg text-muted-foreground">
                    <?php esc_html_e('Everything you need to know before getting started', 'pdfviewer'); ?>
                </p>
            </header>

            <div class="space-y-3" role="list" aria-label="<?php esc_attr_e('Frequently asked questions about PDF Embed & SEO Optimize', 'pdfviewer'); ?>">
                <?php foreach ($faqs as $index => $faq) :
                    // Generate SEO-friendly ID from question
                    $faq_id = 'faq-' . sanitize_title(substr($faq['question'], 0, 50));
                ?>
                    <details
                        id="<?php echo esc_attr($faq_id); ?>"
                        class="group bg-background border border-border rounded-xl px-6 py-4 transition-colors open:shadow-soft open:border-primary/30"
                        role="listitem"
                    >
                        <summary
                            class="flex items-center justify-between cursor-pointer text-left font-semibold text-foreground hover:text-primary list-none"
                            aria-label="<?php echo esc_attr($faq['question'] . ' - Click to expand answer'); ?>"
                            title="<?php echo esc_attr($faq['question']); ?>"
                        >
                            <?php echo esc_html($faq['question']); ?>
                            <span class="ml-4 shrink-0 transition-transform group-open:rotate-180" aria-hidden="true">
                                <?php echo pdfviewer_icon('chevron-down', 20); ?>
                            </span>
                        </summary>
                        <div class="mt-4 text-muted-foreground" role="region" aria-label="<?php echo esc_attr('Answer to: ' . $faq['question']); ?>">
                            <?php echo esc_html($faq['answer']); ?>
                        </div>
                    </details>
                <?php endforeach; ?>
            </div>

            <div class="mt-12 text-center animate-fade-in">
                <p class="text-muted-foreground">
                    <?php esc_html_e('Still have questions?', 'pdfviewer'); ?>
                    <a href="https://wordpress.org/support/plugin/pdf-embed-seo-optimize/?ref=pdfviewer"
                       target="_blank"
                       rel="noopener"
                       class="text-primary hover:underline font-medium">
                        <?php esc_html_e('Visit our support forum', 'pdfviewer'); ?>
                    </a>
                </p>
            </div>
        </div>
    </div>
</section>

<!-- CTA -->
<section class="py-8 lg:py-10 relative z-10">
    <div class="absolute inset-0 gradient-hero opacity-5" aria-hidden="true"></div>
    <div class="container mx-auto px-4 lg:px-8 relative">
        <div class="max-w-3xl mx-auto text-center">
            <h2 class="text-3xl md:text-4xl font-bold mb-6">
                <?php esc_html_e('Ready to Share PDFs That Get Found?', 'pdfviewer'); ?>
            </h2>
            <p class="text-lg text-muted-foreground mb-10">
                <?php esc_html_e('Join thousands of websites using PDF Embed & SEO Optimize to help customers find their documents.', 'pdfviewer'); ?>
            </p>
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                <div class="relative inline-block" data-dropdown="download-free">
                    <button type="button"
                            data-dropdown-trigger
                            class="btn btn-primary btn-lg gradient-hero shadow-glow gap-2"
                            aria-haspopup="true"
                            aria-expanded="false"
                            aria-controls="download-free-menu"
                            aria-label="<?php esc_attr_e('Select platform to download free version', 'pdfviewer'); ?>"
                            title="<?php esc_attr_e('Choose your platform: WordPress, Drupal, or React', 'pdfviewer'); ?>">
                        <?php echo pdfviewer_icon('download', 20); ?>
                        <?php esc_html_e('Download Free Plugin', 'pdfviewer'); ?>
                        <?php echo pdfviewer_icon('chevron-down', 16); ?>
                    </button>
                    <div id="download-free-menu"
                         data-dropdown-menu
                         class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 w-56 bg-card rounded-xl border border-border shadow-lg z-50"
                         role="menu"
                         aria-label="<?php esc_attr_e('Platform download options', 'pdfviewer'); ?>">
                        <nav aria-label="<?php esc_attr_e('Free version download links', 'pdfviewer'); ?>">
                            <ul class="py-2" role="list">
                                <li role="none">
                                    <a href="<?php echo esc_url(home_url('/wordpress-pdf-viewer/#download-wordpress')); ?>"
                                       class="flex items-center gap-3 px-4 py-3 text-sm text-foreground hover:bg-muted transition-colors rounded-lg mx-2"
                                       role="menuitem"
                                       aria-label="<?php esc_attr_e('Download free PDF Embed plugin for WordPress', 'pdfviewer'); ?>"
                                       title="<?php esc_attr_e('Get the free WordPress plugin from wordpress.org', 'pdfviewer'); ?>">
                                        <?php pdfviewer_wordpress_icon(20); ?>
                                        <span class="flex-1"><?php esc_html_e('WordPress', 'pdfviewer'); ?></span>
                                        <?php echo pdfviewer_icon('arrow-right', 14, 'text-muted-foreground'); ?>
                                    </a>
                                </li>
                                <li role="none">
                                    <a href="<?php echo esc_url(home_url('/drupal-pdf-viewer/#download-drupal')); ?>"
                                       class="flex items-center gap-3 px-4 py-3 text-sm text-foreground hover:bg-muted transition-colors rounded-lg mx-2"
                                       role="menuitem"
                                       aria-label="<?php esc_attr_e('Download free PDF Embed module for Drupal', 'pdfviewer'); ?>"
                                       title="<?php esc_attr_e('Get the free Drupal module from drupal.org', 'pdfviewer'); ?>">
                                        <?php pdfviewer_drupal_icon(20); ?>
                                        <span class="flex-1"><?php esc_html_e('Drupal', 'pdfviewer'); ?></span>
                                        <?php echo pdfviewer_icon('arrow-right', 14, 'text-muted-foreground'); ?>
                                    </a>
                                </li>
                                <li role="none">
                                    <a href="<?php echo esc_url(home_url('/nextjs-pdf-viewer/#download-react')); ?>"
                                       class="flex items-center gap-3 px-4 py-3 text-sm text-foreground hover:bg-muted transition-colors rounded-lg mx-2"
                                       role="menuitem"
                                       aria-label="<?php esc_attr_e('Download free PDF component for React and Next.js', 'pdfviewer'); ?>"
                                       title="<?php esc_attr_e('Get the free React / Next.js component via npm', 'pdfviewer'); ?>">
                                        <?php pdfviewer_react_icon(20); ?>
                                        <span class="flex-1"><?php esc_html_e('React', 'pdfviewer'); ?></span>
                                        <?php echo pdfviewer_icon('arrow-right', 14, 'text-muted-foreground'); ?>
                                    </a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
                <a href="<?php echo esc_url(home_url('/examples/')); ?>" class="btn btn-outline btn-lg gap-2">
                    <?php esc_html_e('View Examples', 'pdfviewer'); ?>
                    <?php echo pdfviewer_icon('arrow-right', 20); ?>
                </a>
            </div>
        </div>
    </div>
</section>

<?php get_footer(); ?>
