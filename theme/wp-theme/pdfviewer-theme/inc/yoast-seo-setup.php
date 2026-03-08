<?php
/**
 * Yoast SEO Pre-filled Meta Data Setup
 *
 * Creates all pages with pre-filled Yoast SEO meta data including:
 * - SEO titles
 * - Meta descriptions
 * - Open Graph titles & descriptions
 * - Twitter Card titles & descriptions
 * - Focus keywords
 * - Canonical URLs
 *
 * @package PDFViewer
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Canonical base URL for SEO meta tags.
 * All canonical, OG, and Twitter URLs must use pdfviewermodule.com.
 */
if (!defined('PDFVIEWER_VANITY_DOMAIN')) {
    define('PDFVIEWER_VANITY_DOMAIN', 'https://pdfviewermodule.com');
}

/**
 * Page SEO Configuration
 * All meta data from the original React site
 */
function pdfviewer_get_pages_seo_config() {
    $site_url = PDFVIEWER_VANITY_DOMAIN;
    $og_image = get_template_directory_uri() . '/assets/images/og-image.jpg';

    return array(
        // Homepage (Front Page)
        'home' => array(
            'title'            => 'Home',
            'slug'             => 'home',
            'template'         => '',
            'is_front_page'    => true,
            'seo_title'        => 'PDF Embed & SEO Optimize – Free Plugin for WP, Drupal & React',
            'meta_description' => 'The best free plugin for embedding PDFs on WP, Drupal & React. SEO-optimized pages with clean URLs, JSON-LD schema, OpenGraph cards, view analytics, and Mozilla PDF.js viewer.',
            'focus_keyword'    => 'PDF embed plugin',
            'og_title'         => 'PDF Embed & SEO Optimize – Free Plugin for WP, Drupal & React',
            'og_description'   => 'The best free plugin for embedding PDFs on WP, Drupal & React. SEO-optimized pages with clean URLs, JSON-LD schema, OpenGraph cards, view analytics, and Mozilla PDF.js viewer.',
            'og_image'         => $og_image,
            'twitter_title'    => 'PDF Embed & SEO Optimize – Free for WP, Drupal & React',
            'twitter_description' => 'Transform your PDFs into SEO-optimized pages with clean URLs, schema markup, and Mozilla PDF.js viewer. Available for WP, Drupal & React.',
            'canonical'        => $site_url . '/',
        ),

        // Documentation
        'documentation' => array(
            'title'            => 'Documentation',
            'slug'             => 'documentation',
            'template'         => '',
            'seo_title'        => 'Documentation – Getting Started with PDF Embed & SEO Optimize',
            'meta_description' => 'Complete guide to using PDF Embed & SEO Optimize for WordPress and Drupal. Learn about installation, configuration, REST API, hooks, shortcodes, and premium features.',
            'focus_keyword'    => 'PDF Embed documentation',
            'og_title'         => 'Documentation – Getting Started with PDF Embed & SEO Optimize',
            'og_description'   => 'Complete guide to using PDF Embed & SEO Optimize for WordPress and Drupal. Learn about installation, configuration, REST API, hooks, shortcodes, and premium features.',
            'og_image'         => $og_image,
            'twitter_title'    => 'Documentation – PDF Embed & SEO Optimize',
            'twitter_description' => 'Complete guide to installation, configuration, REST API, hooks, and shortcodes.',
            'canonical'        => $site_url . '/documentation/',
        ),

        // Examples
        'examples' => array(
            'title'            => 'Examples',
            'slug'             => 'examples',
            'template'         => '',
            'seo_title'        => 'Live Examples – See PDF Embed & SEO Optimize in Action',
            'meta_description' => 'View live examples of the PDF Embed & SEO Optimize WordPress plugin. See clean URLs, professional PDF display, and SEO-optimized document pages.',
            'focus_keyword'    => 'PDF embed examples',
            'og_title'         => 'Live Examples – See PDF Embed & SEO Optimize in Action',
            'og_description'   => 'View live examples of the PDF Embed & SEO Optimize WordPress plugin. See clean URLs, professional PDF display, and SEO-optimized document pages.',
            'og_image'         => $og_image,
            'twitter_title'    => 'Live Examples – PDF Embed & SEO Optimize',
            'twitter_description' => 'See clean URLs, professional PDF display, and SEO-optimized document pages.',
            'canonical'        => $site_url . '/examples/',
        ),

        // Pro
        'pro' => array(
            'title'            => 'Pro',
            'slug'             => 'pro',
            'template'         => '',
            'seo_title'        => 'PDF Embed Pro – Advanced PDF Features for WP, Drupal & React',
            'meta_description' => 'Upgrade to Pro for advanced analytics, password protection, WooCommerce integration, email gates, and priority support. Available for WP, Drupal & React. Starting at €49/year.',
            'focus_keyword'    => 'PDF Embed Pro',
            'og_title'         => 'PDF Embed Pro – Advanced PDF Features for WP, Drupal & React',
            'og_description'   => 'Upgrade to Pro for advanced analytics, password protection, WooCommerce integration, email gates, and priority support. Available for WP, Drupal & React. Starting at €49/year.',
            'og_image'         => $og_image,
            'twitter_title'    => 'PDF Embed Pro – Features for WP, Drupal & React',
            'twitter_description' => 'Advanced analytics, password protection, WooCommerce integration, and priority support. Available for WP, Drupal & React.',
            'canonical'        => $site_url . '/pro/',
        ),

        // Changelog
        'changelog' => array(
            'title'            => 'Changelog',
            'slug'             => 'changelog',
            'template'         => '',
            'seo_title'        => 'Changelog – PDF Embed & SEO Optimize Version History',
            'meta_description' => 'See what\'s new in PDF Embed & SEO Optimize. Complete version history with new features, improvements, and bug fixes for both Free and Pro versions.',
            'focus_keyword'    => 'PDF Embed changelog',
            'og_title'         => 'Changelog – PDF Embed & SEO Optimize Version History',
            'og_description'   => 'See what\'s new in PDF Embed & SEO Optimize. Complete version history with new features, improvements, and bug fixes for both Free and Pro versions.',
            'og_image'         => $og_image,
            'twitter_title'    => 'Changelog – PDF Embed & SEO Optimize',
            'twitter_description' => 'Complete version history with new features, improvements, and bug fixes.',
            'canonical'        => $site_url . '/changelog/',
        ),

        // Cart
        'cart' => array(
            'title'            => 'Cart',
            'slug'             => 'cart',
            'template'         => '',
            'seo_title'        => 'Shopping Cart – PDF Embed & SEO Optimize Pro',
            'meta_description' => 'Review your PDF Embed Pro license selection and proceed to secure checkout.',
            'focus_keyword'    => 'PDF Embed Pro purchase',
            'og_title'         => 'Shopping Cart – PDF Embed & SEO Optimize Pro',
            'og_description'   => 'Review your PDF Embed Pro license selection and proceed to secure checkout.',
            'og_image'         => $og_image,
            'twitter_title'    => 'Shopping Cart – PDF Embed Pro',
            'twitter_description' => 'Review your license selection and proceed to secure checkout.',
            'canonical'        => $site_url . '/cart/',
            'noindex'          => true, // Cart pages should not be indexed
        ),

        // WordPress PDF Viewer
        'wordpress-pdf-viewer' => array(
            'title'            => 'WordPress PDF Viewer',
            'slug'             => 'wordpress-pdf-viewer',
            'template'         => '',
            'seo_title'        => 'WordPress PDF Viewer & Embed Plugin – Free, SEO-Optimized, Mobile Ready',
            'meta_description' => 'The best free WordPress PDF plugin. Embed and display PDFs beautifully with Mozilla PDF.js, SEO optimization, clean URLs, Gutenberg blocks, shortcodes, and view analytics.',
            'focus_keyword'    => 'WordPress PDF viewer',
            'og_title'         => 'WordPress PDF Viewer & Embed Plugin – Free, SEO-Optimized, Mobile Ready',
            'og_description'   => 'The best free WordPress PDF plugin. Embed and display PDFs beautifully with Mozilla PDF.js, SEO optimization, clean URLs, Gutenberg blocks, shortcodes, and view analytics.',
            'og_image'         => $og_image,
            'twitter_title'    => 'WordPress PDF Viewer Plugin – Free & SEO-Optimized',
            'twitter_description' => 'Embed and display PDFs beautifully with Mozilla PDF.js, clean URLs, and Gutenberg blocks.',
            'canonical'        => $site_url . '/wordpress-pdf-viewer/',
        ),

        // Drupal PDF Viewer
        'drupal-pdf-viewer' => array(
            'title'            => 'Drupal PDF Viewer',
            'slug'             => 'drupal-pdf-viewer',
            'template'         => '',
            'seo_title'        => 'Best Drupal PDF Viewer Module – Free, SEO-Optimized, Mobile Ready',
            'meta_description' => 'Display PDFs beautifully on your Drupal site with the best free PDF viewer module. Mozilla PDF.js integration, mobile responsive, SEO optimized with clean URLs and schema markup.',
            'focus_keyword'    => 'Drupal PDF viewer',
            'og_title'         => 'Best Drupal PDF Viewer Module – Free, SEO-Optimized, Mobile Ready',
            'og_description'   => 'Display PDFs beautifully on your Drupal site with the best free PDF viewer module. Mozilla PDF.js integration, mobile responsive, SEO optimized with clean URLs and schema markup.',
            'og_image'         => $og_image,
            'twitter_title'    => 'Drupal PDF Viewer Module – Free & SEO-Optimized',
            'twitter_description' => 'Display PDFs beautifully with Mozilla PDF.js, mobile responsive, and SEO optimized.',
            'canonical'        => $site_url . '/drupal-pdf-viewer/',
        ),

        // Compare Plugins
        'compare' => array(
            'title'            => 'Plugin Comparison',
            'slug'             => 'compare',
            'template'         => '',
            'seo_title'        => 'Best WordPress PDF Plugin Comparison 2025 – Feature Comparison',
            'meta_description' => 'Compare WordPress PDF plugins side-by-side. See how PDF Embed & SEO Optimize stacks up against other PDF viewer plugins for SEO, features, and pricing.',
            'focus_keyword'    => 'WordPress PDF plugin comparison',
            'og_title'         => 'Best WordPress PDF Plugin Comparison 2025 – Feature Comparison',
            'og_description'   => 'Compare WordPress PDF plugins side-by-side. See how PDF Embed & SEO Optimize stacks up against other PDF viewer plugins for SEO, features, and pricing.',
            'og_image'         => $og_image,
            'twitter_title'    => 'WordPress PDF Plugin Comparison 2025',
            'twitter_description' => 'Compare PDF plugins side-by-side for SEO, features, and pricing.',
            'canonical'        => $site_url . '/compare/',
        ),

        // React / Next.js PDF Viewer
        'nextjs-pdf-viewer' => array(
            'title'            => 'React / Next.js PDF Viewer',
            'slug'             => 'nextjs-pdf-viewer',
            'template'         => '',
            'seo_title'        => 'React / Next.js PDF Viewer Component – TypeScript, SSR Compatible, Tree-shakeable',
            'meta_description' => 'Modern PDF viewer React component with TypeScript support, SSR compatible, tree-shakeable. Perfect for React 18+ and Next.js 13+ applications with Schema.org markup and analytics.',
            'focus_keyword'    => 'React PDF viewer',
            'og_title'         => 'React / Next.js PDF Viewer Component – TypeScript, SSR Compatible',
            'og_description'   => 'Modern PDF viewer React component with TypeScript support, SSR compatible, optimized for React 18+ and Next.js 13+ applications.',
            'og_image'         => $og_image,
            'twitter_title'    => 'React / Next.js PDF Viewer Component',
            'twitter_description' => 'Modern PDF viewer React component with TypeScript support and SSR compatibility for React 18+ and Next.js 13+.',
            'canonical'        => $site_url . '/nextjs-pdf-viewer/',
        ),

        // PDF Grid
        'pdf-grid' => array(
            'title'            => 'PDF Grid',
            'slug'             => 'pdf-grid',
            'template'         => '',
            'seo_title'        => 'PDF Gallery Grid View – Browse All PDF Documents',
            'meta_description' => 'Browse all SEO-optimized PDF documents in a visual grid layout with thumbnails. Powered by PDF Embed & SEO Optimize plugin.',
            'focus_keyword'    => 'PDF grid view',
            'og_title'         => 'PDF Gallery Grid View – Browse All PDF Documents',
            'og_description'   => 'Browse all SEO-optimized PDF documents in a visual grid layout with thumbnails.',
            'og_image'         => $og_image,
            'twitter_title'    => 'PDF Gallery Grid View',
            'twitter_description' => 'Browse all PDF documents in a visual grid layout with thumbnails.',
            'canonical'        => $site_url . '/pdf-grid/',
        ),

        // Enterprise
        'enterprise' => array(
            'title'            => 'Enterprise',
            'slug'             => 'enterprise',
            'template'         => 'page-enterprise.php',
            'seo_title'        => 'Enterprise PDF Management for WP, Drupal & React | PDF Embed Pro',
            'meta_description' => 'GDPR-compliant, audit-ready PDF delivery for Pharma, Life Sciences, and Healthcare. Available for WP, Drupal & React. Role-based access, expiring links, full REST API.',
            'focus_keyword'    => 'enterprise PDF management',
            'og_title'         => 'Enterprise PDF Management for WP, Drupal & React',
            'og_description'   => 'GDPR-compliant, audit-ready PDF delivery for Pharma, Life Sciences, and Healthcare. Available for WP, Drupal & React.',
            'og_image'         => $og_image,
            'twitter_title'    => 'Enterprise PDF Management for WP, Drupal & React',
            'twitter_description' => 'GDPR-compliant, audit-ready PDF delivery with role-based access and full REST API. For WP, Drupal & React.',
            'canonical'        => $site_url . '/enterprise/',
        ),

    );
}

/**
 * Create or Update Page with Yoast SEO Meta Data
 *
 * @param array $page_config Page configuration array
 * @return int|WP_Error Page ID or error
 */
function pdfviewer_create_page_with_seo($page_config) {
    // Check if page exists
    $existing_page = get_page_by_path($page_config['slug']);

    if ($existing_page) {
        $page_id = $existing_page->ID;
    } else {
        // Create new page
        $page_data = array(
            'post_title'   => $page_config['title'],
            'post_name'    => $page_config['slug'],
            'post_status'  => 'publish',
            'post_type'    => 'page',
            'post_content' => '',
        );

        // Add page template if specified
        if (!empty($page_config['template'])) {
            $page_data['page_template'] = $page_config['template'];
        }

        $page_id = wp_insert_post($page_data);

        if (is_wp_error($page_id)) {
            return $page_id;
        }
    }

    // Update Yoast SEO meta data
    pdfviewer_update_yoast_meta($page_id, $page_config);

    // Set as front page if configured
    if (!empty($page_config['is_front_page'])) {
        update_option('show_on_front', 'page');
        update_option('page_on_front', $page_id);
    }

    return $page_id;
}

/**
 * Update Yoast SEO Meta Data for a Page
 *
 * @param int   $page_id     Page ID
 * @param array $page_config SEO configuration
 */
function pdfviewer_update_yoast_meta($page_id, $page_config) {
    // Yoast SEO meta keys
    $yoast_meta = array(
        // SEO Title
        '_yoast_wpseo_title'              => $page_config['seo_title'] ?? '',

        // Meta Description
        '_yoast_wpseo_metadesc'           => $page_config['meta_description'] ?? '',

        // Focus Keyword
        '_yoast_wpseo_focuskw'            => $page_config['focus_keyword'] ?? '',

        // Canonical URL
        '_yoast_wpseo_canonical'          => $page_config['canonical'] ?? '',

        // Open Graph Title
        '_yoast_wpseo_opengraph-title'    => $page_config['og_title'] ?? '',

        // Open Graph Description
        '_yoast_wpseo_opengraph-description' => $page_config['og_description'] ?? '',

        // Open Graph Image
        '_yoast_wpseo_opengraph-image'    => $page_config['og_image'] ?? '',

        // Twitter Title
        '_yoast_wpseo_twitter-title'      => $page_config['twitter_title'] ?? '',

        // Twitter Description
        '_yoast_wpseo_twitter-description' => $page_config['twitter_description'] ?? '',

        // Twitter Image (same as OG image)
        '_yoast_wpseo_twitter-image'      => $page_config['og_image'] ?? '',
    );

    // Handle noindex setting
    if (!empty($page_config['noindex'])) {
        $yoast_meta['_yoast_wpseo_meta-robots-noindex'] = '1';
        $yoast_meta['_yoast_wpseo_meta-robots-nofollow'] = '0';
    }

    // Update all meta fields
    foreach ($yoast_meta as $meta_key => $meta_value) {
        if (!empty($meta_value)) {
            update_post_meta($page_id, $meta_key, $meta_value);
        }
    }

    // Set redirect if configured (using Yoast redirect if available, otherwise WP redirect)
    if (!empty($page_config['redirect_to'])) {
        // Yoast Premium redirect
        update_post_meta($page_id, '_yoast_wpseo_redirect', home_url($page_config['redirect_to']));
    }
}

/**
 * Setup All Pages with Yoast SEO Meta Data
 * Run this function to create/update all pages
 */
function pdfviewer_setup_all_pages_seo() {
    $pages_config = pdfviewer_get_pages_seo_config();
    $results = array();

    foreach ($pages_config as $key => $page_config) {
        $result = pdfviewer_create_page_with_seo($page_config);

        if (is_wp_error($result)) {
            $results[$key] = array(
                'status' => 'error',
                'message' => $result->get_error_message(),
            );
        } else {
            $results[$key] = array(
                'status' => 'success',
                'page_id' => $result,
                'url' => get_permalink($result),
            );
        }
    }

    return $results;
}

/**
 * Admin Page for SEO Setup
 */
function pdfviewer_add_seo_setup_menu() {
    add_theme_page(
        __('SEO Setup', 'pdfviewer'),
        __('SEO Setup', 'pdfviewer'),
        'manage_options',
        'pdfviewer-seo-setup',
        'pdfviewer_seo_setup_page'
    );
}
add_action('admin_menu', 'pdfviewer_add_seo_setup_menu');

/**
 * SEO Setup Admin Page
 */
function pdfviewer_seo_setup_page() {
    // Check if Yoast SEO is active
    $yoast_active = defined('WPSEO_VERSION');

    // Handle form submission
    if (filter_input(INPUT_POST, 'pdfviewer_setup_seo', FILTER_SANITIZE_SPECIAL_CHARS) && check_admin_referer('pdfviewer_seo_setup')) {
        $results = pdfviewer_setup_all_pages_seo();
        $success_count = count(array_filter($results, function($r) { return $r['status'] === 'success'; }));
        $error_count = count($results) - $success_count;

        echo '<div class="notice notice-success"><p>';
        printf(
            __('SEO setup complete! %d pages created/updated successfully.', 'pdfviewer'),
            $success_count
        );
        if ($error_count > 0) {
            printf(' ' . __('%d errors occurred.', 'pdfviewer'), $error_count);
        }
        echo '</p></div>';
    }
    ?>
    <div class="wrap">
        <h1><?php esc_html_e('PDF Viewer Theme - SEO Setup', 'pdfviewer'); ?></h1>

        <?php if (!$yoast_active) : ?>
            <div class="notice notice-warning">
                <p>
                    <strong><?php esc_html_e('Yoast SEO not detected!', 'pdfviewer'); ?></strong>
                    <?php esc_html_e('Please install and activate Yoast SEO for full meta data support.', 'pdfviewer'); ?>
                </p>
            </div>
        <?php else : ?>
            <div class="notice notice-info">
                <p>
                    <strong><?php esc_html_e('Yoast SEO detected!', 'pdfviewer'); ?></strong>
                    <?php esc_html_e('All meta data will be saved to Yoast SEO fields.', 'pdfviewer'); ?>
                </p>
            </div>
        <?php endif; ?>

        <div class="card" style="max-width: 800px; padding: 20px;">
            <h2><?php esc_html_e('Create Pages with Pre-filled SEO Meta Data', 'pdfviewer'); ?></h2>
            <p><?php esc_html_e('This will create all theme pages and pre-fill Yoast SEO meta data including:', 'pdfviewer'); ?></p>
            <ul style="list-style: disc; margin-left: 20px;">
                <li><?php esc_html_e('SEO titles', 'pdfviewer'); ?></li>
                <li><?php esc_html_e('Meta descriptions', 'pdfviewer'); ?></li>
                <li><?php esc_html_e('Focus keywords', 'pdfviewer'); ?></li>
                <li><?php esc_html_e('Open Graph titles, descriptions, and images', 'pdfviewer'); ?></li>
                <li><?php esc_html_e('Twitter Card titles, descriptions, and images', 'pdfviewer'); ?></li>
                <li><?php esc_html_e('Canonical URLs', 'pdfviewer'); ?></li>
            </ul>

            <h3><?php esc_html_e('Pages to be created:', 'pdfviewer'); ?></h3>
            <table class="widefat" style="margin: 20px 0;">
                <thead>
                    <tr>
                        <th><?php esc_html_e('Page', 'pdfviewer'); ?></th>
                        <th><?php esc_html_e('URL', 'pdfviewer'); ?></th>
                        <th><?php esc_html_e('SEO Title', 'pdfviewer'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $pages_config = pdfviewer_get_pages_seo_config();
                    foreach ($pages_config as $key => $config) :
                        $existing = get_page_by_path($config['slug']);
                        $status = $existing ? '✓ Exists' : '○ New';
                    ?>
                        <tr>
                            <td>
                                <strong><?php echo esc_html($config['title']); ?></strong>
                                <br><small><?php echo esc_html($status); ?></small>
                            </td>
                            <td><code>/<?php echo esc_html($config['slug']); ?>/</code></td>
                            <td><small><?php echo esc_html(substr($config['seo_title'], 0, 50)); ?>...</small></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <form method="post">
                <?php wp_nonce_field('pdfviewer_seo_setup'); ?>
                <p>
                    <button type="submit" name="pdfviewer_setup_seo" class="button button-primary button-hero">
                        <?php esc_html_e('Create/Update Pages with SEO Data', 'pdfviewer'); ?>
                    </button>
                </p>
            </form>
        </div>

        <div class="card" style="max-width: 800px; padding: 20px; margin-top: 20px;">
            <h2><?php esc_html_e('After Setup', 'pdfviewer'); ?></h2>
            <ol>
                <li><?php esc_html_e('Edit each page in the block editor to add content', 'pdfviewer'); ?></li>
                <li><?php esc_html_e('Use block patterns from "PDF Viewer Theme" category', 'pdfviewer'); ?></li>
                <li><?php esc_html_e('Review SEO settings in Yoast SEO meta box', 'pdfviewer'); ?></li>
                <li><?php esc_html_e('Set up navigation menus in Appearance > Menus', 'pdfviewer'); ?></li>
            </ol>
        </div>
    </div>
    <?php
}

/**
 * Update Yoast SEO Meta on Page Save (for new pages)
 * Applies default SEO meta if page matches a known slug
 */
function pdfviewer_maybe_apply_default_seo($post_id, $post, $update) {
    // Only for pages
    if ($post->post_type !== 'page') {
        return;
    }

    // Skip auto-drafts
    if ($post->post_status === 'auto-draft') {
        return;
    }

    // Check if already has Yoast meta
    $existing_title = get_post_meta($post_id, '_yoast_wpseo_title', true);
    if (!empty($existing_title)) {
        return;
    }

    // Check if slug matches a known page
    $pages_config = pdfviewer_get_pages_seo_config();
    $slug = $post->post_name;

    foreach ($pages_config as $key => $config) {
        if ($config['slug'] === $slug) {
            pdfviewer_update_yoast_meta($post_id, $config);
            break;
        }
    }
}
add_action('wp_insert_post', 'pdfviewer_maybe_apply_default_seo', 10, 3);

/**
 * Programmatic Yoast SEO Fallbacks
 *
 * When Yoast is active but its post meta is empty (e.g. the SEO Setup admin
 * page was never run), Yoast outputs nothing and the theme fallback is
 * suppressed.  These filters close the gap by returning the configured
 * description from pdfviewer_get_pages_seo_config() whenever Yoast would
 * otherwise output an empty string.
 */

/**
 * Helper: get SEO config value for the current page
 *
 * @param string $field Config key (e.g. 'meta_description', 'og_description')
 * @return string Value or empty string
 */
function pdfviewer_get_current_page_seo_field($field) {
    if (is_admin()) {
        return '';
    }

    $slug = '';

    if (is_front_page()) {
        $slug = 'home';
    } elseif (is_singular()) {
        $slug = get_post_field('post_name', get_the_ID());
    }

    if (empty($slug)) {
        return '';
    }

    $pages_config = pdfviewer_get_pages_seo_config();

    foreach ($pages_config as $config) {
        if ($config['slug'] === $slug && !empty($config[$field])) {
            return $config[$field];
        }
    }

    return '';
}

/**
 * Supply meta description when Yoast's stored value is empty
 */
function pdfviewer_yoast_fallback_metadesc($description) {
    if (!empty($description)) {
        return $description;
    }
    return pdfviewer_get_current_page_seo_field('meta_description');
}
add_filter('wpseo_metadesc', 'pdfviewer_yoast_fallback_metadesc');

/**
 * Supply OG description when Yoast's stored value is empty
 */
function pdfviewer_yoast_fallback_og_desc($description) {
    if (!empty($description)) {
        return $description;
    }
    return pdfviewer_get_current_page_seo_field('og_description');
}
add_filter('wpseo_opengraph_desc', 'pdfviewer_yoast_fallback_og_desc');

/**
 * Supply Twitter description when Yoast's stored value is empty
 */
function pdfviewer_yoast_fallback_twitter_desc($description) {
    if (!empty($description)) {
        return $description;
    }
    return pdfviewer_get_current_page_seo_field('twitter_description');
}
add_filter('wpseo_twitter_description', 'pdfviewer_yoast_fallback_twitter_desc');

/**
 * Supply OG title when Yoast's stored value is empty
 */
function pdfviewer_yoast_fallback_og_title($title) {
    if (!empty($title)) {
        return $title;
    }
    return pdfviewer_get_current_page_seo_field('og_title');
}
add_filter('wpseo_opengraph_title', 'pdfviewer_yoast_fallback_og_title');

/**
 * Supply Twitter title when Yoast's stored value is empty
 */
function pdfviewer_yoast_fallback_twitter_title($title) {
    if (!empty($title)) {
        return $title;
    }
    return pdfviewer_get_current_page_seo_field('twitter_title');
}
add_filter('wpseo_twitter_title', 'pdfviewer_yoast_fallback_twitter_title');

/**
 * Ensure og:image and twitter:image tags are present on every page.
 *
 * Fires at wp_head priority 30 (after Yoast at 1, non-Yoast fallback at 2).
 * Outputs both landscape (1200x630) and square (512x512) images so platforms
 * like LinkedIn, Facebook, and WhatsApp can pick the format they prefer.
 *
 * This replaces the unreliable wpseo_add_opengraph_images hook which does
 * not work in all Yoast versions.
 */
function pdfviewer_ensure_og_images() {
    $base = get_template_directory_uri() . '/assets/images/';
    // Landscape (1200x630)
    echo '<meta property="og:image" content="' . esc_url($base . 'og-image.jpg') . '">' . "\n";
    echo '<meta property="og:image:width" content="1200">' . "\n";
    echo '<meta property="og:image:height" content="630">' . "\n";
    // Square (512x512)
    echo '<meta property="og:image" content="' . esc_url($base . 'pwa-512x512.png') . '">' . "\n";
    echo '<meta property="og:image:width" content="512">' . "\n";
    echo '<meta property="og:image:height" content="512">' . "\n";
    // Twitter image
    echo '<meta name="twitter:image" content="' . esc_url($base . 'og-image.jpg') . '">' . "\n";
}
add_action('wp_head', 'pdfviewer_ensure_og_images', 30);

/**
 * Force canonical URL to use pdfviewermodule.com domain.
 * Replaces the WordPress home_url() domain with the vanity domain.
 */
function pdfviewer_force_vanity_canonical($canonical) {
    if (empty($canonical)) {
        return $canonical;
    }
    return str_replace(home_url(), PDFVIEWER_VANITY_DOMAIN, $canonical);
}
add_filter('wpseo_canonical', 'pdfviewer_force_vanity_canonical');

/**
 * Force Open Graph URL to use pdfviewermodule.com domain.
 */
function pdfviewer_force_vanity_og_url($url) {
    if (empty($url)) {
        return $url;
    }
    return str_replace(home_url(), PDFVIEWER_VANITY_DOMAIN, $url);
}
add_filter('wpseo_opengraph_url', 'pdfviewer_force_vanity_og_url');

/**
 * Force Twitter URL to use pdfviewermodule.com domain.
 */
function pdfviewer_force_vanity_twitter_url($url) {
    if (empty($url)) {
        return $url;
    }
    return str_replace(home_url(), PDFVIEWER_VANITY_DOMAIN, $url);
}
add_filter('wpseo_twitter_url', 'pdfviewer_force_vanity_twitter_url');

/**
 * Force WordPress core canonical URL to use pdfviewermodule.com domain.
 * This filter fires when Yoast SEO is NOT active and WordPress outputs
 * its own <link rel="canonical"> via rel_canonical().
 */
function pdfviewer_force_core_canonical($canonical_url) {
    if (empty($canonical_url)) {
        return $canonical_url;
    }
    return str_replace(home_url(), PDFVIEWER_VANITY_DOMAIN, $canonical_url);
}
add_filter('get_canonical_url', 'pdfviewer_force_core_canonical');

/**
 * Force OEmbed canonical URL to use pdfviewermodule.com domain.
 */
function pdfviewer_force_oembed_canonical($url) {
    if (empty($url)) {
        return $url;
    }
    return str_replace(home_url(), PDFVIEWER_VANITY_DOMAIN, $url);
}
add_filter('oembed_request_post_id', '__return_zero');

/**
 * Output canonical, Open Graph and Twitter meta tags when Yoast SEO is NOT active.
 * Ensures pdfviewermodule.com canonical and OG URLs regardless of SEO plugin.
 */
function pdfviewer_fallback_canonical_og_tags() {
    // Skip if Yoast SEO is active (it handles its own canonical/OG output)
    if (defined('WPSEO_VERSION')) {
        return;
    }

    // Build the vanity canonical URL for the current page
    $path = '/';
    if (is_front_page()) {
        $path = '/';
    } elseif (is_singular()) {
        $slug = get_post_field('post_name', get_the_ID());
        $path = '/' . $slug . '/';
    }

    $canonical = PDFVIEWER_VANITY_DOMAIN . $path;

    // Output canonical link tag (we remove WP core's via pdfviewer_remove_core_canonical)
    echo '<link rel="canonical" href="' . esc_url($canonical) . '">' . "\n";

    // Get page-specific OG data from SEO config
    $pages_config = pdfviewer_get_pages_seo_config();
    $og_title = get_the_title() . ' – PDF Embed & SEO Optimize';
    $og_description = '';
    $slug_key = is_front_page() ? 'home' : (is_singular() ? get_post_field('post_name', get_the_ID()) : '');
    foreach ($pages_config as $config) {
        if ($config['slug'] === $slug_key) {
            $og_title = $config['og_title'] ?? $og_title;
            $og_description = $config['og_description'] ?? '';
            break;
        }
    }

    // Output Open Graph tags (images handled by pdfviewer_ensure_og_images)
    echo '<meta property="og:url" content="' . esc_url($canonical) . '">' . "\n";
    echo '<meta property="og:type" content="article">' . "\n";
    echo '<meta property="og:title" content="' . esc_attr($og_title) . '">' . "\n";
    if ($og_description) {
        echo '<meta property="og:description" content="' . esc_attr($og_description) . '">' . "\n";
    }
    echo '<meta property="og:site_name" content="PDF Embed &amp; SEO Optimize">' . "\n";

    // Output Twitter Card tags (twitter:image handled by pdfviewer_ensure_og_images)
    echo '<meta name="twitter:card" content="summary_large_image">' . "\n";
    echo '<meta name="twitter:url" content="' . esc_url($canonical) . '">' . "\n";
    echo '<meta name="twitter:title" content="' . esc_attr($og_title) . '">' . "\n";
    if ($og_description) {
        echo '<meta name="twitter:description" content="' . esc_attr($og_description) . '">' . "\n";
    }
}
add_action('wp_head', 'pdfviewer_fallback_canonical_og_tags', 2);

/**
 * Remove WordPress core's canonical tag when our fallback outputs its own.
 * Prevents duplicate <link rel="canonical"> tags with conflicting domains.
 */
function pdfviewer_remove_core_canonical() {
    if (!defined('WPSEO_VERSION')) {
        remove_action('wp_head', 'rel_canonical');
    }
}
add_action('init', 'pdfviewer_remove_core_canonical');

/**
 * Output author and date meta tags on ALL pages, regardless of Yoast status.
 * LinkedIn requires <meta name="author"> (standard HTML meta tag) in addition
 * to the OG article:author property. Also outputs article:published_time and
 * article:modified_time for social platforms.
 */
function pdfviewer_article_author_meta() {
    // Standard HTML meta tag - required by LinkedIn Post Inspector (plain-text name)
    echo '<meta name="author" content="Dross:Media">' . "\n";
    // Open Graph property - must be a URL per OG spec; LinkedIn rejects plain-text names
    echo '<meta property="article:author" content="https://drossmedia.de/">' . "\n";
    if (is_singular()) {
        $published = get_the_date('c');
        $modified  = get_the_modified_date('c');
        if ($published) {
            echo '<meta property="article:published_time" content="' . esc_attr($published) . '">' . "\n";
        }
        if ($modified) {
            echo '<meta property="article:modified_time" content="' . esc_attr($modified) . '">' . "\n";
        }
    }
}
add_action('wp_head', 'pdfviewer_article_author_meta', 25);

/**
 * Catch-all: rewrite any remaining pdfviewer.drossmedia.de references in <head>
 * to pdfviewermodule.com. This handles edge cases where Yoast, other plugins,
 * or WordPress core output canonical/og:url with the wrong domain.
 */
function pdfviewer_start_head_buffer() {
    ob_start();
}
add_action('wp_head', 'pdfviewer_start_head_buffer', 0);

function pdfviewer_flush_head_buffer() {
    $output = ob_get_clean();
    if ($output === false) {
        return;
    }
    $wp_domain = parse_url(home_url(), PHP_URL_HOST);
    $vanity_domain = parse_url(PDFVIEWER_VANITY_DOMAIN, PHP_URL_HOST);
    if ($wp_domain && $vanity_domain && $wp_domain !== $vanity_domain) {
        $wp_scheme = parse_url(home_url(), PHP_URL_SCHEME) ?: 'https';
        $vanity_scheme = parse_url(PDFVIEWER_VANITY_DOMAIN, PHP_URL_SCHEME) ?: 'https';
        // Rewrite canonical link tags
        $output = preg_replace(
            '/(rel=["\']canonical["\']\\s+href=["\'])' . preg_quote($wp_scheme . '://' . $wp_domain, '/') . '/i',
            '${1}' . $vanity_scheme . '://' . $vanity_domain,
            $output
        );
        // Also handle href before rel ordering
        $output = preg_replace(
            '/(href=["\'])' . preg_quote($wp_scheme . '://' . $wp_domain, '/') . '([^"\']*["\']\\s+rel=["\']canonical["\'])/i',
            '${1}' . $vanity_scheme . '://' . $vanity_domain . '${2}',
            $output
        );
        // Rewrite og:url content
        $output = preg_replace(
            '/(property=["\']og:url["\']\\s+content=["\'])' . preg_quote($wp_scheme . '://' . $wp_domain, '/') . '/i',
            '${1}' . $vanity_scheme . '://' . $vanity_domain,
            $output
        );
        // Rewrite twitter:url content
        $output = preg_replace(
            '/(name=["\']twitter:url["\']\\s+content=["\'])' . preg_quote($wp_scheme . '://' . $wp_domain, '/') . '/i',
            '${1}' . $vanity_scheme . '://' . $vanity_domain,
            $output
        );
    }
    echo $output;
}
add_action('wp_head', 'pdfviewer_flush_head_buffer', 9999);
