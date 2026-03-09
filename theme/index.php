<?php
/**
 * Main Template File
 *
 * @package PDFViewer
 */

get_header();

if (have_posts()) :
    while (have_posts()) :
        the_post();
        ?>
        <article id="post-<?php the_ID(); ?>" <?php post_class('container mx-auto px-4 py-16 lg:px-8'); ?>>
            <header class="mb-8">
                <h1 class="text-4xl md:text-5xl font-bold mb-4"><?php the_title(); ?></h1>
            </header>

            <div class="prose prose-lg max-w-none">
                <?php the_content(); ?>
            </div>
        </article>
        <?php
    endwhile;
else :
    ?>
    <div class="container mx-auto px-4 py-16 lg:px-8 text-center">
        <h1 class="text-4xl font-bold mb-4"><?php esc_html_e('Nothing Found', 'pdfviewer'); ?></h1>
        <p class="text-muted-foreground"><?php esc_html_e('It seems we can\'t find what you\'re looking for.', 'pdfviewer'); ?></p>
    </div>
    <?php
endif;

get_footer();
