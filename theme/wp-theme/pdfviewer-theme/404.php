<?php
/**
 * 404 Error Page Template
 *
 * @package PDFViewer
 */

get_header();
?>

<section class="min-h-[60vh] flex items-center justify-center py-24" aria-labelledby="error-heading">
    <div class="container mx-auto px-4 lg:px-8 text-center">
        <div class="max-w-2xl mx-auto">
            <?php // Error Icon ?>
            <div class="w-24 h-24 mx-auto mb-8 gradient-hero rounded-2xl flex items-center justify-center shadow-glow animate-float">
                <?php echo pdfviewer_icon('file-text', 48, 'text-primary-foreground'); ?>
            </div>

            <h1 id="error-heading" class="text-6xl md:text-8xl font-extrabold text-primary mb-4">
                404
            </h1>

            <h2 class="text-2xl md:text-3xl font-bold mb-4">
                <?php esc_html_e('Page Not Found', 'pdfviewer'); ?>
            </h2>

            <p class="text-lg text-muted-foreground mb-8 max-w-md mx-auto">
                <?php esc_html_e('The page you\'re looking for doesn\'t exist or has been moved. Let\'s get you back on track.', 'pdfviewer'); ?>
            </p>

            <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                <a href="<?php echo esc_url(home_url('/')); ?>"
                   class="btn btn-primary btn-lg gap-2"
                   title="<?php esc_attr_e('Return to homepage', 'pdfviewer'); ?>">
                    <?php echo pdfviewer_icon('arrow-right', 20); ?>
                    <?php esc_html_e('Go to Homepage', 'pdfviewer'); ?>
                </a>

                <a href="<?php echo esc_url(home_url('/documentation/')); ?>"
                   class="btn btn-outline btn-lg gap-2"
                   title="<?php esc_attr_e('Browse documentation', 'pdfviewer'); ?>">
                    <?php echo pdfviewer_icon('book-open', 20); ?>
                    <?php esc_html_e('View Documentation', 'pdfviewer'); ?>
                </a>
            </div>

            <?php // Quick Links ?>
            <div class="mt-12 pt-8 border-t border-border">
                <p class="text-sm text-muted-foreground mb-4"><?php esc_html_e('Quick Links:', 'pdfviewer'); ?></p>
                <nav class="flex flex-wrap items-center justify-center gap-4" aria-label="<?php esc_attr_e('Quick links', 'pdfviewer'); ?>">
                    <a href="<?php echo esc_url(home_url('/examples/')); ?>"
                       class="text-primary hover:underline transition-colors"
                       title="<?php esc_attr_e('View examples', 'pdfviewer'); ?>">
                        <?php esc_html_e('Examples', 'pdfviewer'); ?>
                    </a>
                    <span class="text-muted-foreground/50" aria-hidden="true">&bull;</span>
                    <a href="<?php echo esc_url(home_url('/pro/')); ?>"
                       class="text-primary hover:underline transition-colors"
                       title="<?php esc_attr_e('View Pro features', 'pdfviewer'); ?>">
                        <?php esc_html_e('Pro Features', 'pdfviewer'); ?>
                    </a>
                    <span class="text-muted-foreground/50" aria-hidden="true">&bull;</span>
                    <a href="<?php echo esc_url(home_url('/changelog/')); ?>"
                       class="text-primary hover:underline transition-colors"
                       title="<?php esc_attr_e('View changelog', 'pdfviewer'); ?>">
                        <?php esc_html_e('Changelog', 'pdfviewer'); ?>
                    </a>
                </nav>
            </div>
        </div>
    </div>
</section>

<?php
get_footer();
