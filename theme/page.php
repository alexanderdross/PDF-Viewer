<?php
/**
 * Page Template
 * Used for standard pages edited via block editor
 *
 * @package PDFViewer
 */

get_header();

while (have_posts()) :
    the_post();
    ?>
    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
        <?php
        // Get page content - fully supports block editor
        the_content();
        ?>
    </article>
    <?php
endwhile;

get_footer();
