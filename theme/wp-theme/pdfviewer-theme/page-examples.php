<?php
/**
 * Template Name: Examples
 * Examples page showing live PDF demos
 *
 * @package PDFViewer
 */

get_header();

$examples = array(
    array(
        'title'       => 'In-Page PDF with Download & Print',
        'description' => 'PDF renders directly on the page with download and print buttons enabled for user convenience.',
        'url'         => esc_url(home_url('/pdf/example-1/')),
        'features'    => array('Embedded viewer', 'Download button', 'Print button'),
        'icon'        => 'file-text',
    ),
    array(
        'title'       => 'In-Page PDF without Download/Print',
        'description' => 'PDF renders on the page with download and print functionality disabled for protected content.',
        'url'         => esc_url(home_url('/pdf/example-2/')),
        'features'    => array('Embedded viewer', 'No download', 'View-only mode'),
        'icon'        => 'file-text',
    ),
    array(
        'title'       => 'Standalone PDF with Download & Print',
        'description' => 'PDF opens in a new tab as a standalone viewer with full download and print capabilities.',
        'url'         => esc_url(home_url('/pdf/example-3/')),
        'features'    => array('Opens in new tab', 'Download button', 'Print button'),
        'icon'        => 'file-text',
    ),
    array(
        'title'       => 'Standalone PDF without Download/Print',
        'description' => 'PDF opens in a new tab as a standalone viewer with download and print disabled.',
        'url'         => esc_url(home_url('/pdf/example-4/')),
        'features'    => array('Opens in new tab', 'No download', 'View-only mode'),
        'icon'        => 'file-text',
    ),
    array(
        'title'       => 'In-Page PDF with Password Protection',
        'description' => 'PDF renders directly on the page but requires a password to view. Password: PDF-Reader-Test',
        'url'         => esc_url(home_url('/pdf/example-5/')),
        'features'    => array('Embedded viewer', 'Password required', 'Secure access'),
        'icon'        => 'lock',
    ),
    array(
        'title'       => 'Standalone PDF with Password Protection',
        'description' => 'PDF opens in a new tab as a standalone viewer with password protection. Password: PDF-Reader-Test',
        'url'         => esc_url(home_url('/pdf/example-6/')),
        'features'    => array('Opens in new tab', 'Password required', 'Secure access'),
        'icon'        => 'lock',
    ),
    array(
        'title'       => 'HTML Sitemap - List View',
        'description' => 'Auto-generated archive page displaying all PDFs in a clean, scannable list format.',
        'url'         => esc_url(home_url('/pdf/')),
        'features'    => array('List layout', 'Auto-generated', 'SEO-friendly'),
        'icon'        => 'file-text',
    ),
    array(
        'title'       => 'HTML Sitemap - Grid View',
        'description' => 'Visual archive page with PDF thumbnails arranged in a responsive grid layout.',
        'url'         => esc_url(home_url('/pdf-grid/')),
        'features'    => array('Grid layout', 'Thumbnails', 'Visual browsing'),
        'icon'        => 'file-text',
    ),
);
?>

<!-- Hero -->
<section class="py-16 lg:py-24 bg-card" aria-labelledby="examples-heading">
    <div class="container mx-auto px-4 lg:px-8">
        <div class="max-w-3xl">
            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-300 text-sm font-medium mb-6">
                <?php echo pdfviewer_icon('file-text', 16); ?>
                <span><?php esc_html_e('Examples', 'pdfviewer'); ?></span>
            </div>
            <h1 id="examples-heading" class="text-4xl md:text-5xl font-bold leading-tight mb-6">
                <?php esc_html_e('See It In Action', 'pdfviewer'); ?>
            </h1>
            <p class="text-xl text-muted-foreground">
                <?php esc_html_e('See how your documents look with clean, professional links that customers can easily find and share.', 'pdfviewer'); ?>
            </p>
        </div>
    </div>
</section>

<!-- URL Structure Comparison -->
<section id="url-comparison" class="py-16 lg:py-24 scroll-mt-24">
    <div class="container mx-auto px-4 lg:px-8">
        <div class="max-w-4xl mx-auto">
            <h2 class="text-2xl font-bold mb-4 text-center">
                <a href="#url-comparison" class="hover:text-primary transition-colors hover:underline"><?php esc_html_e('Before & After Comparison', 'pdfviewer'); ?></a>
            </h2>
            <p class="text-muted-foreground text-center mb-8 max-w-2xl mx-auto">
                <?php esc_html_e('Compare how your PDF links look with standard WordPress versus our plugin. Clean, professional URLs make a big difference for both users and search engines.', 'pdfviewer'); ?>
            </p>

            <div class="grid md:grid-cols-2 gap-6 mb-12">
                <!-- Without Plugin -->
                <article class="bg-card rounded-2xl border border-destructive/20 p-6 animate-fade-in">
                    <div class="flex items-center gap-2 mb-4">
                        <div class="w-8 h-8 rounded-lg bg-destructive/10 flex items-center justify-center">
                            <?php echo pdfviewer_icon('x-circle', 16, 'text-destructive'); ?>
                        </div>
                        <span class="font-semibold"><?php esc_html_e('Standard WordPress', 'pdfviewer'); ?></span>
                    </div>
                    <div class="bg-destructive/5 rounded-lg p-4 mb-4">
                        <code class="text-sm text-muted-foreground break-all"><a href="<?php echo esc_url(home_url('/wp-content/uploads/2025/03/example-1.pdf')); ?>" target="_blank" rel="noopener" title="<?php esc_attr_e('Example of unoptimized PDF URL', 'pdfviewer'); ?>">domain.com/wp-content/uploads/2025/03/example-1.pdf</a></code>
                    </div>
                    <ul class="space-y-2 text-sm text-muted-foreground list-none">
                        <li class="flex items-start gap-2">
                            <?php echo pdfviewer_icon('x-circle', 16, 'text-destructive shrink-0 mt-0.5'); ?>
                            <span><?php esc_html_e('Messy, confusing link', 'pdfviewer'); ?></span>
                        </li>
                        <li class="flex items-start gap-2">
                            <?php echo pdfviewer_icon('x-circle', 16, 'text-destructive shrink-0 mt-0.5'); ?>
                            <span><?php esc_html_e('Hard to find on Google', 'pdfviewer'); ?></span>
                        </li>
                        <li class="flex items-start gap-2">
                            <?php echo pdfviewer_icon('x-circle', 16, 'text-destructive shrink-0 mt-0.5'); ?>
                            <span><?php esc_html_e('No way to track who reads it', 'pdfviewer'); ?></span>
                        </li>
                    </ul>
                </article>

                <!-- With Plugin -->
                <article class="bg-card rounded-2xl border border-primary/20 p-6 animate-fade-in" style="animation-delay: 0.1s">
                    <div class="flex items-center gap-2 mb-4">
                        <div class="w-8 h-8 rounded-lg bg-primary/10 flex items-center justify-center">
                            <?php echo pdfviewer_icon('check', 16, 'text-primary'); ?>
                        </div>
                        <span class="font-semibold"><?php esc_html_e('With Our Plugin', 'pdfviewer'); ?></span>
                    </div>
                    <div class="bg-primary/5 rounded-lg p-4 mb-4">
                        <code class="text-sm text-primary font-semibold break-all"><a href="' . esc_url(home_url('/pdf/example-1/')) . '" target="_blank" rel="noopener" title="<?php esc_attr_e('SEO-optimized PDF page with clean URL', 'pdfviewer'); ?>">domain.com/pdf/product-brochure/</a></code>
                    </div>
                    <ul class="space-y-2 text-sm text-muted-foreground list-none">
                        <li class="flex items-start gap-2">
                            <?php echo pdfviewer_icon('check', 16, 'text-primary shrink-0 mt-0.5'); ?>
                            <span><?php esc_html_e('Clean, professional link', 'pdfviewer'); ?></span>
                        </li>
                        <li class="flex items-start gap-2">
                            <?php echo pdfviewer_icon('check', 16, 'text-primary shrink-0 mt-0.5'); ?>
                            <span><?php esc_html_e('Shows up in Google searches', 'pdfviewer'); ?></span>
                        </li>
                        <li class="flex items-start gap-2">
                            <?php echo pdfviewer_icon('check', 16, 'text-primary shrink-0 mt-0.5'); ?>
                            <span><?php esc_html_e('Track views and sharing', 'pdfviewer'); ?></span>
                        </li>
                    </ul>
                </article>
            </div>

            <!-- URL Structure -->
            <article id="url-structure" class="bg-card rounded-2xl border border-border p-8 mb-12 animate-fade-in scroll-mt-24" style="animation-delay: 0.2s">
                <div class="flex items-center gap-3 mb-4">
                    <?php echo pdfviewer_icon('link', 20, 'text-primary'); ?>
                    <h3 class="text-lg font-semibold">
                        <a href="#url-structure" class="hover:text-primary transition-colors hover:underline"><?php esc_html_e('How Your Links Will Look', 'pdfviewer'); ?></a>
                    </h3>
                </div>
                <p class="text-muted-foreground mb-6">
                    <?php esc_html_e('Your PDFs get clean, memorable URLs that are easy to share and look professional in emails, social media, and print materials.', 'pdfviewer'); ?>
                </p>
                <div class="space-y-4">
                    <div class="flex flex-col sm:flex-row sm:items-center gap-2 sm:gap-4">
                        <code class="bg-muted px-4 py-2 rounded-lg font-mono text-sm">yourdomain.com/pdf/</code>
                        <?php echo pdfviewer_icon('arrow-right', 16, 'text-muted-foreground hidden sm:block'); ?>
                        <span class="text-muted-foreground text-sm"><?php esc_html_e('A page listing all your PDF documents', 'pdfviewer'); ?></span>
                    </div>
                    <div class="flex flex-col sm:flex-row sm:items-center gap-2 sm:gap-4">
                        <code class="bg-muted px-4 py-2 rounded-lg font-mono text-sm">yourdomain.com/pdf/your-document-name/</code>
                        <?php echo pdfviewer_icon('arrow-right', 16, 'text-muted-foreground hidden sm:block'); ?>
                        <span class="text-muted-foreground text-sm"><?php esc_html_e('Each PDF gets its own professional page', 'pdfviewer'); ?></span>
                    </div>
                    <div class="flex flex-col sm:flex-row sm:items-center gap-2 sm:gap-4">
                        <code class="bg-muted px-4 py-2 rounded-lg font-mono text-sm text-foreground font-semibold">yourdomain.com/pdf/sitemap.xml</code>
                        <?php echo pdfviewer_icon('arrow-right', 16, 'text-muted-foreground hidden sm:block'); ?>
                        <span class="text-muted-foreground text-sm"><?php esc_html_e('Automatic sitemap for Google to find all your PDFs', 'pdfviewer'); ?></span>
                    </div>
                </div>
                <div class="mt-6 pt-6 border-t border-border">
                    <p class="text-sm text-muted-foreground">
                        <strong class="text-foreground"><?php esc_html_e('Bonus:', 'pdfviewer'); ?></strong>
                        <?php esc_html_e('The plugin automatically creates an XML sitemap just for your PDFs, helping search engines discover and index all your documents.', 'pdfviewer'); ?>
                    </p>
                </div>
            </article>
        </div>
    </div>
</section>

<!-- Example PDFs -->
<section id="live-examples" class="py-16 lg:py-24 bg-card scroll-mt-24" aria-labelledby="live-examples-heading">
    <div class="container mx-auto px-4 lg:px-8">
        <div class="max-w-4xl mx-auto">
            <h2 id="live-examples-heading" class="text-2xl font-bold mb-4 text-center">
                <a href="#live-examples" class="hover:text-primary transition-colors hover:underline"><?php esc_html_e('Try These Live Examples', 'pdfviewer'); ?></a>
            </h2>
            <p class="text-muted-foreground text-center mb-8 max-w-2xl mx-auto">
                <?php esc_html_e('Click on any example below to see exactly how your PDFs will look and function. Each demo showcases different configuration options available in the plugin.', 'pdfviewer'); ?>
            </p>

            <div class="grid gap-6 md:grid-cols-2">
                <?php foreach ($examples as $index => $example) : ?>
                    <article class="group bg-background rounded-xl border border-border hover:border-primary/30 hover:shadow-medium transition-all duration-300 animate-fade-in overflow-hidden flex flex-col" style="animation-delay: <?php echo esc_attr($index * 0.1); ?>s">
                        <div class="p-6 flex flex-col flex-1">
                            <div class="flex items-start gap-4 mb-4">
                                <div class="w-12 h-12 rounded-xl flex items-center justify-center group-hover:shadow-glow transition-shadow flex-shrink-0 <?php echo $example['icon'] === 'lock' ? 'bg-amber-500' : 'gradient-hero'; ?>">
                                    <?php echo pdfviewer_icon($example['icon'], 24, 'text-primary-foreground'); ?>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h3 class="font-semibold text-lg mb-1 group-hover:text-primary transition-colors">
                                        <?php echo esc_html($example['title']); ?>
                                    </h3>
                                    <p class="text-sm text-muted-foreground">
                                        <?php echo esc_html($example['description']); ?>
                                    </p>
                                </div>
                            </div>

                            <div class="flex flex-wrap gap-2 mb-4">
                                <?php foreach ($example['features'] as $feature) : ?>
                                    <span class="inline-flex items-center px-3 py-1.5 rounded-lg bg-muted border border-border text-xs font-medium text-foreground">
                                        <?php echo esc_html($feature); ?>
                                    </span>
                                <?php endforeach; ?>
                            </div>

                            <a href="<?php echo esc_url($example['url']); ?>"
                               target="_blank"
                               rel="noopener"
                               class="btn btn-outline w-full mt-auto gap-2 justify-center"
                               aria-label="<?php echo esc_attr(sprintf(__('View %s example (opens in new tab)', 'pdfviewer'), $example['title'])); ?>"
                               title="<?php echo esc_attr(sprintf(__('View %s (opens in new tab)', 'pdfviewer'), $example['title'])); ?>">
                                <span><?php esc_html_e('View Example', 'pdfviewer'); ?></span>
                                <?php echo pdfviewer_icon('external-link', 16); ?>
                            </a>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>

<?php get_footer(); ?>
