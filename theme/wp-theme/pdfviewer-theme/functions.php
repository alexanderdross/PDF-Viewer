<?php
/**
 * PDF Embed & SEO Optimize Theme Functions
 *
 * @package PDFViewer
 * @version 2.2.96
 */

if (!defined('ABSPATH')) {
    exit;
}

define('PDFVIEWER_THEME_VERSION', '2.2.96');
define('PDFVIEWER_THEME_DIR', get_template_directory());
define('PDFVIEWER_THEME_URI', get_template_directory_uri());

/**
 * Theme Setup
 */
function pdfviewer_theme_setup() {
    // Add support for block editor
    add_theme_support('wp-block-styles');
    add_theme_support('align-wide');
    add_theme_support('editor-styles');
    add_theme_support('responsive-embeds');

    // Add support for standard WordPress features
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'style',
        'script',
    ));

    // Add support for custom logo
    add_theme_support('custom-logo', array(
        'height'      => 40,
        'width'       => 40,
        'flex-height' => true,
        'flex-width'  => true,
    ));

    // Register navigation menus
    register_nav_menus(array(
        'primary'  => __('Primary Menu', 'pdfviewer'),
        'footer'   => __('Footer Menu', 'pdfviewer'),
        'legal'    => __('Legal Links', 'pdfviewer'),
    ));

    // Set content width
    $GLOBALS['content_width'] = 1400;
}
add_action('after_setup_theme', 'pdfviewer_theme_setup');

/**
 * Enqueue Scripts and Styles
 */
function pdfviewer_enqueue_assets() {
    // Main stylesheet (includes inlined @font-face declarations)
    wp_enqueue_style(
        'pdfviewer-main',
        PDFVIEWER_THEME_URI . '/assets/css/main.css',
        array(),
        PDFVIEWER_THEME_VERSION
    );

    // Main JavaScript (self-hosted, no external dependencies)
    wp_enqueue_script(
        'pdfviewer-main',
        PDFVIEWER_THEME_URI . '/assets/js/main.js',
        array(),
        PDFVIEWER_THEME_VERSION,
        true
    );

    // Pass data to JavaScript
    wp_localize_script('pdfviewer-main', 'pdfviewerData', array(
        'ajaxUrl' => admin_url('admin-ajax.php'),
        'nonce'   => wp_create_nonce('pdfviewer_nonce'),
        'siteUrl' => home_url('/'),
    ));
}
add_action('wp_enqueue_scripts', 'pdfviewer_enqueue_assets');

/**
 * Enqueue Block Editor Assets
 */
function pdfviewer_enqueue_editor_assets() {
    wp_enqueue_style(
        'pdfviewer-editor',
        PDFVIEWER_THEME_URI . '/assets/css/main.css',
        array(),
        PDFVIEWER_THEME_VERSION
    );
}
add_action('enqueue_block_editor_assets', 'pdfviewer_enqueue_editor_assets');

/**
 * Register Block Patterns Category
 */
function pdfviewer_register_pattern_category() {
    register_block_pattern_category('pdfviewer', array(
        'label' => __('PDF Viewer Theme', 'pdfviewer'),
    ));
}
add_action('init', 'pdfviewer_register_pattern_category');

/**
 * Register Block Patterns
 */
function pdfviewer_register_block_patterns() {
    // Hero Section Pattern
    register_block_pattern('pdfviewer/hero', array(
        'title'       => __('Hero Section', 'pdfviewer'),
        'description' => __('Main hero section with headline, description, and CTAs', 'pdfviewer'),
        'categories'  => array('pdfviewer'),
        'content'     => pdfviewer_get_pattern_content('hero'),
    ));

    // Features Section Pattern
    register_block_pattern('pdfviewer/features', array(
        'title'       => __('Features Grid', 'pdfviewer'),
        'description' => __('Grid of feature cards with icons', 'pdfviewer'),
        'categories'  => array('pdfviewer'),
        'content'     => pdfviewer_get_pattern_content('features'),
    ));

    // CTA Section Pattern
    register_block_pattern('pdfviewer/cta', array(
        'title'       => __('Call to Action', 'pdfviewer'),
        'description' => __('Call to action section with buttons', 'pdfviewer'),
        'categories'  => array('pdfviewer'),
        'content'     => pdfviewer_get_pattern_content('cta'),
    ));

    // FAQ Section Pattern
    register_block_pattern('pdfviewer/faq', array(
        'title'       => __('FAQ Accordion', 'pdfviewer'),
        'description' => __('Frequently asked questions with accordion', 'pdfviewer'),
        'categories'  => array('pdfviewer'),
        'content'     => pdfviewer_get_pattern_content('faq'),
    ));

    // Comparison Table Pattern
    register_block_pattern('pdfviewer/comparison', array(
        'title'       => __('Comparison Table', 'pdfviewer'),
        'description' => __('Feature comparison table', 'pdfviewer'),
        'categories'  => array('pdfviewer'),
        'content'     => pdfviewer_get_pattern_content('comparison'),
    ));

    // Pricing Section Pattern
    register_block_pattern('pdfviewer/pricing', array(
        'title'       => __('Pricing Cards', 'pdfviewer'),
        'description' => __('Pricing cards with features', 'pdfviewer'),
        'categories'  => array('pdfviewer'),
        'content'     => pdfviewer_get_pattern_content('pricing'),
    ));

    // Problem/Solution Section Pattern
    register_block_pattern('pdfviewer/problem-solution', array(
        'title'       => __('Problem & Solution', 'pdfviewer'),
        'description' => __('Side-by-side problem and solution comparison', 'pdfviewer'),
        'categories'  => array('pdfviewer'),
        'content'     => pdfviewer_get_pattern_content('problem-solution'),
    ));

    // How It Works Section Pattern
    register_block_pattern('pdfviewer/how-it-works', array(
        'title'       => __('How It Works', 'pdfviewer'),
        'description' => __('Three-step process with numbered circles', 'pdfviewer'),
        'categories'  => array('pdfviewer'),
        'content'     => pdfviewer_get_pattern_content('how-it-works'),
    ));

    // Testimonials Section Pattern
    register_block_pattern('pdfviewer/testimonials', array(
        'title'       => __('Testimonials', 'pdfviewer'),
        'description' => __('Customer testimonials grid with ratings', 'pdfviewer'),
        'categories'  => array('pdfviewer'),
        'content'     => pdfviewer_get_pattern_content('testimonials'),
    ));

    // GEO (Generative Engine Optimization) Section Pattern
    register_block_pattern('pdfviewer/geo-section', array(
        'title'       => __('GEO Section', 'pdfviewer'),
        'description' => __('Generative Engine Optimization feature section', 'pdfviewer'),
        'categories'  => array('pdfviewer'),
        'content'     => pdfviewer_get_pattern_content('geo-section'),
    ));
}
add_action('init', 'pdfviewer_register_block_patterns');

/**
 * Get Block Pattern Content.
 *
 * Reads a block pattern HTML file from the patterns directory.
 * Uses a whitelist to prevent path traversal.
 *
 * @param string $pattern_name Pattern filename (without extension).
 * @return string Pattern HTML content, or empty string if not found.
 */
function pdfviewer_get_pattern_content($pattern_name) {
    $allowed_patterns = array(
        'hero', 'features', 'cta', 'faq', 'comparison',
        'pricing', 'problem-solution', 'how-it-works',
        'testimonials', 'geo-section',
    );

    if (!in_array($pattern_name, $allowed_patterns, true)) {
        return '';
    }

    $pattern_file = PDFVIEWER_THEME_DIR . '/patterns/' . $pattern_name . '.html';

    if (file_exists($pattern_file)) {
        return file_get_contents($pattern_file);
    }

    return '';
}

/**
 * Optimized Image Helper Function
 * Creates <picture> element with WebP and fallback, lazy loading, and proper dimensions
 *
 * @param array $args {
 *     @type string $src_webp    Path to WebP image
 *     @type string $src_fallback Path to fallback image (jpg/png)
 *     @type string $alt         Alt text for accessibility
 *     @type int    $width       Image width for CLS optimization
 *     @type int    $height      Image height for CLS optimization
 *     @type string $class       CSS classes
 *     @type bool   $lazy        Whether to lazy load (default: true for below-fold)
 *     @type string $title       Title attribute for SEO
 *     @type string $aria_label  ARIA label for accessibility
 *     @type string $sizes       Responsive sizes attribute
 * }
 * @return string HTML picture element
 */
function pdfviewer_picture($args) {
    $defaults = array(
        'src_webp'     => '',
        'src_fallback' => '',
        'alt'          => '',
        'width'        => 0,
        'height'       => 0,
        'class'        => '',
        'lazy'         => true,
        'title'        => '',
        'aria_label'   => '',
        'sizes'        => '100vw',
        'srcset'       => '',
    );

    $args = wp_parse_args($args, $defaults);

    // Build attributes
    $loading = $args['lazy'] ? 'lazy' : 'eager';
    $decoding = $args['lazy'] ? 'async' : 'sync';
    $fetchpriority = $args['lazy'] ? '' : ' fetchpriority="high"';

    $img_attrs = array();
    $img_attrs[] = 'src="' . esc_url($args['src_fallback']) . '"';
    $img_attrs[] = 'alt="' . esc_attr($args['alt']) . '"';

    if ($args['width'] > 0) {
        $img_attrs[] = 'width="' . intval($args['width']) . '"';
    }

    if ($args['height'] > 0) {
        $img_attrs[] = 'height="' . intval($args['height']) . '"';
    }

    if ($args['class']) {
        $img_attrs[] = 'class="' . esc_attr($args['class']) . '"';
    }

    $img_attrs[] = 'loading="' . $loading . '"';
    $img_attrs[] = 'decoding="' . $decoding . '"';

    if ($args['title']) {
        $img_attrs[] = 'title="' . esc_attr($args['title']) . '"';
    }

    if ($args['aria_label']) {
        $img_attrs[] = 'aria-label="' . esc_attr($args['aria_label']) . '"';
    }

    $img_attrs_str = implode(' ', $img_attrs);

    // Build picture element
    $html = '<picture>';

    if ($args['src_webp']) {
        $html .= '<source type="image/webp" srcset="' . esc_url($args['src_webp']) . '"';
        if ($args['sizes']) {
            $html .= ' sizes="' . esc_attr($args['sizes']) . '"';
        }
        $html .= '>';
    }

    $html .= '<img ' . $img_attrs_str . $fetchpriority . '>';
    $html .= '</picture>';

    return $html;
}

/**
 * Get SVG Icon
 * Returns inline SVG for icons (self-hosted, no external libraries)
 *
 * @param string $icon Icon name
 * @param int $size Icon size in pixels
 * @param string $class Additional CSS classes
 * @return string SVG markup
 */
function pdfviewer_icon($icon, $size = 24, $class = '') {
    $icons = array(
        'menu' => '<path d="M4 6h16M4 12h16M4 18h16" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>',
        'x' => '<path d="M18 6L6 18M6 6l12 12" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>',
        'download' => '<path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4M7 10l5 5 5-5M12 15V3" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>',
        'arrow-right' => '<path d="M5 12h14M12 5l7 7-7 7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>',
        'external-link' => '<path d="M18 13v6a2 2 0 01-2 2H5a2 2 0 01-2-2V8a2 2 0 012-2h6M15 3h6v6M10 14L21 3" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>',
        'check' => '<path d="M20 6L9 17l-5-5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>',
        'x-circle' => '<circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2"/><path d="M15 9l-6 6M9 9l6 6" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>',
        'zap' => '<path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>',
        'file-text' => '<path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z" stroke="currentColor" stroke-width="2"/><path d="M14 2v6h6M16 13H8M16 17H8M10 9H8" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>',
        'search' => '<circle cx="11" cy="11" r="8" stroke="currentColor" stroke-width="2"/><path d="M21 21l-4.35-4.35" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>',
        'chevron-down' => '<path d="M6 9l6 6 6-6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>',
        'sparkles' => '<path d="M12 2l2.4 7.2L22 12l-7.6 2.8L12 22l-2.4-7.2L2 12l7.6-2.8L12 2z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>',
        'book-open' => '<path d="M2 3h6a4 4 0 014 4v14a3 3 0 00-3-3H2zM22 3h-6a4 4 0 00-4 4v14a3 3 0 013-3h7z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>',
        'eye' => '<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" stroke="currentColor" stroke-width="2"/><circle cx="12" cy="12" r="3" stroke="currentColor" stroke-width="2"/>',
        'lock' => '<rect x="3" y="11" width="18" height="11" rx="2" stroke="currentColor" stroke-width="2" fill="none"/><path d="M7 11V7a5 5 0 0 1 10 0v4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>',
        'shopping-cart' => '<circle cx="9" cy="21" r="1" stroke="currentColor" stroke-width="2"/><circle cx="20" cy="21" r="1" stroke="currentColor" stroke-width="2"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>',
        'link' => '<path d="M10 13a5 5 0 007.54.54l3-3a5 5 0 00-7.07-7.07l-1.72 1.71" stroke="currentColor" stroke-width="2" stroke-linecap="round"/><path d="M14 11a5 5 0 00-7.54-.54l-3 3a5 5 0 007.07 7.07l1.71-1.71" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>',
        'terminal' => '<path d="M4 17l6-6-6-6M12 19h8" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>',
        'settings' => '<circle cx="12" cy="12" r="3" stroke="currentColor" stroke-width="2"/><path d="M19.4 15a1.65 1.65 0 00.33 1.82l.06.06a2 2 0 010 2.83 2 2 0 01-2.83 0l-.06-.06a1.65 1.65 0 00-1.82-.33 1.65 1.65 0 00-1 1.51V21a2 2 0 01-2 2 2 2 0 01-2-2v-.09A1.65 1.65 0 009 19.4a1.65 1.65 0 00-1.82.33l-.06.06a2 2 0 01-2.83 0 2 2 0 010-2.83l.06-.06a1.65 1.65 0 00.33-1.82 1.65 1.65 0 00-1.51-1H3a2 2 0 01-2-2 2 2 0 012-2h.09A1.65 1.65 0 004.6 9a1.65 1.65 0 00-.33-1.82l-.06-.06a2 2 0 010-2.83 2 2 0 012.83 0l.06.06a1.65 1.65 0 001.82.33H9a1.65 1.65 0 001-1.51V3a2 2 0 012-2 2 2 0 012 2v.09a1.65 1.65 0 001 1.51 1.65 1.65 0 001.82-.33l.06-.06a2 2 0 012.83 0 2 2 0 010 2.83l-.06.06a1.65 1.65 0 00-.33 1.82V9a1.65 1.65 0 001.51 1H21a2 2 0 012 2 2 2 0 01-2 2h-.09a1.65 1.65 0 00-1.51 1z" stroke="currentColor" stroke-width="2"/>',
        'code-2' => '<path d="M18 16l4-4-4-4M6 8l-4 4 4 4M14.5 4l-5 16" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>',
        'puzzle' => '<path d="M19.439 7.85c-.049.322.059.648.289.878l1.568 1.568c.47.47.706 1.087.706 1.704s-.235 1.233-.706 1.704l-1.611 1.611a.98.98 0 01-.837.276c-.47-.07-.802-.48-.968-.925a2.501 2.501 0 10-3.214 3.214c.446.166.855.497.925.968a.979.979 0 01-.276.837l-1.61 1.61a2.404 2.404 0 01-1.705.707 2.402 2.402 0 01-1.704-.706l-1.568-1.568a1.026 1.026 0 00-.877-.29c-.493.074-.84.504-1.02.968a2.5 2.5 0 11-3.237-3.237c.464-.18.894-.527.967-1.02a1.026 1.026 0 00-.289-.877l-1.568-1.568A2.402 2.402 0 011.998 12c0-.617.236-1.234.706-1.704L4.315 8.69c.236-.236.572-.344.896-.285.47.086.812.507.982.965a2.5 2.5 0 103.22-3.22c-.458-.17-.879-.512-.965-.982a1.025 1.025 0 01.285-.896l1.61-1.611A2.401 2.401 0 0112.047 2c.618 0 1.235.236 1.705.706l1.568 1.568c.23.23.556.338.877.29.493-.074.84-.504 1.02-.968a2.5 2.5 0 113.237 3.237c-.464.18-.894.527-.967 1.02z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>',
        'database' => '<ellipse cx="12" cy="5" rx="9" ry="3" stroke="currentColor" stroke-width="2"/><path d="M21 12c0 1.66-4 3-9 3s-9-1.34-9-3" stroke="currentColor" stroke-width="2"/><path d="M3 5v14c0 1.66 4 3 9 3s9-1.34 9-3V5" stroke="currentColor" stroke-width="2"/>',
        'globe' => '<circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2"/><path d="M2 12h20M12 2a15.3 15.3 0 014 10 15.3 15.3 0 01-4 10 15.3 15.3 0 01-4-10 15.3 15.3 0 014-10z" stroke="currentColor" stroke-width="2"/>',
        'quote' => '<path d="M3 21c3 0 7-1 7-8V5c0-1.25-.756-2.017-2-2H4c-1.25 0-2 .75-2 1.972V11c0 1.25.75 2 2 2 1 0 1 0 1 1v1c0 1-1 2-2 2s-1 .008-1 1.031V21c0 1 0 1 1 1z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M15 21c3 0 7-1 7-8V5c0-1.25-.757-2.017-2-2h-4c-1.25 0-2 .75-2 1.972V11c0 1.25.75 2 2 2h.75c0 2.25.25 4-2.75 4v3c0 1 0 1 1 1z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>',
        'message-square' => '<path d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>',
        'star-filled' => '<path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z" fill="currentColor" stroke="currentColor" stroke-width="2"/>',
        'file-code' => '<path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" stroke="currentColor" stroke-width="2"/><path d="M14 2v6h6M10 13l-2 2 2 2M14 13l2 2-2 2" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>',
        'shield' => '<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>',
        'building-2' => '<path d="M6 22V4a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v18ZM6 12H4a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h2M18 9h2a2 2 0 0 1 2 2v9a2 2 0 0 1-2 2h-2M10 6h4M10 10h4M10 14h4M10 18h4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>',
    );

    $svg_content = isset($icons[$icon]) ? $icons[$icon] : '';

    if (!$svg_content) {
        return '';
    }

    $class_attr = $class ? ' class="' . esc_attr($class) . '"' : '';

    return sprintf(
        '<svg xmlns="http://www.w3.org/2000/svg" width="%d" height="%d" viewBox="0 0 24 24" fill="none"%s aria-hidden="true">%s</svg>',
        intval($size),
        intval($size),
        $class_attr,
        $svg_content
    );
}

/**
 * Add Custom Block Supports
 */
function pdfviewer_block_supports() {
    // Add custom spacing to core blocks
    add_theme_support('custom-spacing');

    // Add border support
    add_theme_support('appearance-tools');
}
add_action('after_setup_theme', 'pdfviewer_block_supports');

/**
 * Register Custom Block Styles
 */
function pdfviewer_register_block_styles() {
    // Button styles
    register_block_style('core/button', array(
        'name'  => 'gradient-primary',
        'label' => __('Gradient Primary', 'pdfviewer'),
    ));

    register_block_style('core/button', array(
        'name'  => 'gradient-accent',
        'label' => __('Gradient Accent', 'pdfviewer'),
    ));

    // Group styles
    register_block_style('core/group', array(
        'name'  => 'glass',
        'label' => __('Glass Effect', 'pdfviewer'),
    ));

    register_block_style('core/group', array(
        'name'  => 'card',
        'label' => __('Card', 'pdfviewer'),
    ));
}
add_action('init', 'pdfviewer_register_block_styles');

/**
 * Add Body Classes
 */
function pdfviewer_body_classes($classes) {
    // Add class for front page
    if (is_front_page()) {
        $classes[] = 'front-page';
    }

    return $classes;
}
add_filter('body_class', 'pdfviewer_body_classes');

/**
 * Disable WordPress Emoji Scripts (performance optimization)
 */
function pdfviewer_disable_emojis() {
    remove_action('wp_head', 'print_emoji_detection_script', 7);
    remove_action('admin_print_scripts', 'print_emoji_detection_script');
    remove_action('wp_print_styles', 'print_emoji_styles');
    remove_action('admin_print_styles', 'print_emoji_styles');
    remove_filter('the_content_feed', 'wp_staticize_emoji');
    remove_filter('comment_text_rss', 'wp_staticize_emoji');
    remove_filter('wp_mail', 'wp_staticize_emoji_for_email');
}
add_action('init', 'pdfviewer_disable_emojis');

/**
 * Add fallback meta description if Yoast SEO is not active
 */
function pdfviewer_fallback_meta_description() {
    // Skip if Yoast SEO is active
    if (defined('WPSEO_VERSION')) {
        return;
    }

    $description = '';

    // Page-specific descriptions
    $page_descriptions = array(
        'home'                    => 'PDF Embed & SEO Optimize – Free plugin for WP, Drupal & React. SEO-friendly PDFs with Schema.org markup, analytics, and beautiful viewer.',
        'documentation'           => 'Complete documentation for PDF Embed & SEO Optimize. Installation, configuration, and developer guides.',
        'examples'                => 'Live examples of PDF Embed & SEO Optimize in action. See viewer modes, password protection, and analytics.',
        'pro'                     => 'Upgrade to PDF Embed Pro for advanced analytics, password protection, and reading progress tracking. Available for WP, Drupal & React.',
        'changelog'               => 'PDF Embed & SEO Optimize version history. See all features, improvements, and fixes.',
        'compare'                 => 'Compare PDF Embed & SEO Optimize with other PDF plugins for WordPress and Drupal.',
        'wordpress-pdf-viewer'    => 'PDF Embed & SEO Optimize for WordPress. Beautiful PDF viewer with full SEO optimization.',
        'drupal-pdf-viewer'       => 'PDF Embed & SEO Optimize for Drupal 10/11. Entity-based PDF management with REST API.',
        'nextjs-pdf-viewer'       => 'Modern PDF viewer React component with TypeScript support, SSR compatible, tree-shakeable. For React 18+ and Next.js 13+.',
        'cart'                    => 'Review your PDF Embed Pro license selection and proceed to secure checkout.',
        'pdf-grid'                => 'Browse all SEO-optimized PDF documents in a visual grid layout with thumbnails.',
        'enterprise'              => 'Enterprise PDF management for regulated industries. GDPR-compliant, audit-ready PDF delivery for WP, Drupal & React with role-based access, expiring links, and full REST API.',
    );

    if (is_front_page()) {
        $description = $page_descriptions['home'];
    } elseif (is_singular()) {
        $slug = get_post_field('post_name', get_the_ID());
        if (isset($page_descriptions[$slug])) {
            $description = $page_descriptions[$slug];
        } else {
            $description = get_the_excerpt() ?: wp_trim_words(get_the_content(), 25, '...');
        }
    }

    if ($description) {
        echo '<meta name="description" content="' . esc_attr($description) . '">' . "\n";
    }
}
add_action('wp_head', 'pdfviewer_fallback_meta_description', 1);

/**
 * Override PDF Grid page template
 * Forces the /pdf-grid/ URL to use our static template instead of plugin template
 */
function pdfviewer_override_pdf_grid_template($template) {
    // Check if we're on the pdf-grid page (by URL path)
    $request_uri = isset($_SERVER['REQUEST_URI']) ? sanitize_text_field(wp_unslash($_SERVER['REQUEST_URI'])) : '';

    if (preg_match('#^/pdf-grid/?$#', $request_uri)) {
        $custom_template = PDFVIEWER_THEME_DIR . '/page-pdf-grid.php';
        if (file_exists($custom_template)) {
            return $custom_template;
        }
    }

    return $template;
}
add_filter('template_include', 'pdfviewer_override_pdf_grid_template', 99);

/**
 * Serve vanity domain XML sitemaps from theme directory.
 * Maps /vanity-sitemap.xml and /pdf/vanity-sitemap.xml to theme files.
 * Uses init hook (earliest reliable hook) to intercept before WordPress
 * processes the request and sets a 404 status.
 */
function pdfviewer_serve_vanity_sitemaps() {
    $request_uri = trim(wp_parse_url(sanitize_text_field(wp_unslash($_SERVER['REQUEST_URI'])), PHP_URL_PATH), '/');

    $sitemap_map = array(
        'vanity-sitemap.xml'     => PDFVIEWER_THEME_DIR . '/sitemaps/vanity-sitemap.xml',
        'pdf/vanity-sitemap.xml' => PDFVIEWER_THEME_DIR . '/sitemaps/pdf-vanity-sitemap.xml',
    );

    if (isset($sitemap_map[$request_uri]) && file_exists($sitemap_map[$request_uri])) {
        http_response_code(200);
        status_header(200);
        header('HTTP/1.1 200 OK', true, 200);
        header('Content-Type: application/xml; charset=UTF-8');
        header('X-Robots-Tag: noindex');
        header('Cache-Control: public, max-age=86400');
        readfile($sitemap_map[$request_uri]);
        exit;
    }
}
add_action('init', 'pdfviewer_serve_vanity_sitemaps', 0);

/**
 * Add vanity sitemap reference to robots.txt.
 */
function pdfviewer_add_vanity_sitemap_to_robots($output, $public) {
    $output .= "\n# Vanity Domain Sitemap\n";
    $output .= "Sitemap: " . home_url('/vanity-sitemap.xml') . "\n";
    return $output;
}
add_filter('robots_txt', 'pdfviewer_add_vanity_sitemap_to_robots', 100, 2);

/**
 * Include additional theme files
 */
require_once PDFVIEWER_THEME_DIR . '/inc/template-tags.php';
require_once PDFVIEWER_THEME_DIR . '/inc/customizer.php';
require_once PDFVIEWER_THEME_DIR . '/inc/yoast-seo-setup.php';
require_once PDFVIEWER_THEME_DIR . '/inc/critical-css.php';
require_once PDFVIEWER_THEME_DIR . '/inc/performance.php';
require_once PDFVIEWER_THEME_DIR . '/inc/schema-markup.php';

/**
 * Create required pages on theme activation
 */
function pdfviewer_create_required_pages() {
    $pages = array(
        'nextjs-pdf-viewer' => array(
            'title'   => 'React / Next.js PDF Viewer',
            'content' => '',
        ),
        'documentation' => array(
            'title'   => 'Documentation',
            'content' => '',
        ),
        'examples' => array(
            'title'   => 'Examples',
            'content' => '',
        ),
        'pro' => array(
            'title'   => 'Pro Features',
            'content' => '',
        ),
        'changelog' => array(
            'title'   => 'Changelog',
            'content' => '',
        ),
        'wordpress-pdf-viewer' => array(
            'title'   => 'WordPress PDF Viewer',
            'content' => '',
        ),
        'drupal-pdf-viewer' => array(
            'title'   => 'Drupal PDF Viewer',
            'content' => '',
        ),
        'compare' => array(
            'title'   => 'Compare PDF Plugins',
            'content' => '',
        ),
        'enterprise' => array(
            'title'   => 'Enterprise',
            'content' => '',
        ),
        'cart' => array(
            'title'   => 'Cart',
            'content' => '',
        ),
        'pdf-grid' => array(
            'title'   => 'PDF Documents',
            'content' => '',
        ),
    );

    foreach ($pages as $slug => $page_data) {
        // Check if page already exists
        $existing = get_page_by_path($slug);
        if (!$existing) {
            wp_insert_post(array(
                'post_title'   => $page_data['title'],
                'post_name'    => $slug,
                'post_content' => $page_data['content'],
                'post_status'  => 'publish',
                'post_type'    => 'page',
            ));
        }
    }
}
add_action('after_switch_theme', 'pdfviewer_create_required_pages');

/**
 * Admin notice to create pages if they don't exist
 */
function pdfviewer_admin_notice_missing_pages() {
    if (!current_user_can('edit_pages')) {
        return;
    }

    $required_pages = array('nextjs-pdf-viewer', 'documentation', 'examples', 'pro', 'enterprise', 'cart', 'changelog', 'wordpress-pdf-viewer', 'drupal-pdf-viewer', 'compare', 'pdf-grid');
    $missing = array();

    foreach ($required_pages as $slug) {
        if (!get_page_by_path($slug)) {
            $missing[] = $slug;
        }
    }

    if (!empty($missing) && isset($_GET['page']) && sanitize_text_field(wp_unslash($_GET['page'])) !== 'pdfviewer-setup') {
        echo '<div class="notice notice-warning is-dismissible">';
        echo '<p><strong>PDF Embed & SEO Theme:</strong> Some required pages are missing. ';
        echo '<a href="' . esc_url(wp_nonce_url(admin_url('admin.php?action=pdfviewer_create_pages'), 'pdfviewer_create_pages_nonce')) . '">Click here to create them automatically</a>.</p>';
        echo '</div>';
    }
}
add_action('admin_notices', 'pdfviewer_admin_notice_missing_pages');

/**
 * Admin action to create missing pages
 */
function pdfviewer_admin_create_pages() {
    if (!current_user_can('edit_pages')) {
        wp_die(esc_html__('Unauthorized', 'pdfviewer'));
    }

    // Verify nonce to prevent CSRF.
    if (!isset($_GET['_wpnonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_GET['_wpnonce'])), 'pdfviewer_create_pages_nonce')) {
        wp_die(esc_html__('Security check failed.', 'pdfviewer'));
    }

    pdfviewer_create_required_pages();
    wp_safe_redirect(admin_url('edit.php?post_type=page&pdfviewer_pages_created=1'));
    exit;
}
add_action('admin_action_pdfviewer_create_pages', 'pdfviewer_admin_create_pages');

/**
 * Generate breadcrumb navigation bar
 * Matches React VisualBreadcrumbs component exactly:
 * - Full-width bar with bg-muted/60 background and bottom border
 * - Horizontally scrollable on narrow viewports (no wrapping)
 * - Compact spacing (gap-1, py-2.5)
 * - Muted link colors (not primary blue)
 * - Current page in foreground color
 *
 * @return string HTML breadcrumb markup
 */
function pdfviewer_breadcrumb() {
    // Don't show on front page (matches React: if pathname === "/" return null)
    if (is_front_page()) {
        return '';
    }

    $breadcrumbs = array();

    // Dross:Media (external link - first crumb)
    $breadcrumbs[] = array(
        'label'    => 'Dross:Media',
        'url'      => 'https://dross.net/media/',
        'external' => true,
    );

    // PDF Viewer (this site's home)
    $breadcrumbs[] = array(
        'label' => __('PDF Viewer', 'pdfviewer'),
        'url'   => home_url('/'),
    );

    // Build breadcrumb trail
    if (is_page()) {
        // Get current page slug reliably
        global $post;
        $page_slug = '';
        if ($post) {
            $page_slug = $post->post_name;
        }

        // Custom titles for specific pages (matches React routeNames)
        $custom_titles = array(
            'wordpress-pdf-viewer' => 'WordPress PDF Viewer',
            'drupal-pdf-viewer'    => 'Drupal PDF Viewer',
            'nextjs-pdf-viewer'    => 'React/Next.js PDF Viewer',
            'documentation'        => 'Documentation',
            'examples'             => 'Examples',
            'changelog'            => 'Changelog',
            'pro'                  => 'Pro',
            'cart'                 => 'Cart',
            'compare'              => 'Compare Plugins',
            'pdf-grid'             => 'PDF Grid',
        );

        // Use custom title if available, otherwise use page title
        $page_title = isset($custom_titles[$page_slug]) ? $custom_titles[$page_slug] : get_the_title();

        $breadcrumbs[] = array(
            'label'   => $page_title,
            'url'     => null,
            'current' => true,
        );
    } elseif (is_single()) {
        $categories = get_the_category();
        if (!empty($categories)) {
            $breadcrumbs[] = array(
                'label' => $categories[0]->name,
                'url'   => get_category_link($categories[0]->term_id),
            );
        }
        $breadcrumbs[] = array(
            'label'   => get_the_title(),
            'url'     => null,
            'current' => true,
        );
    } elseif (is_category()) {
        $breadcrumbs[] = array(
            'label'   => single_cat_title('', false),
            'url'     => null,
            'current' => true,
        );
    } elseif (is_archive()) {
        $breadcrumbs[] = array(
            'label'   => post_type_archive_title('', false) ?: __('Archive', 'pdfviewer'),
            'url'     => null,
            'current' => true,
        );
    } elseif (is_search()) {
        $breadcrumbs[] = array(
            'label'   => sprintf(__('Search: %s', 'pdfviewer'), get_search_query()),
            'url'     => null,
            'current' => true,
        );
    } elseif (is_404()) {
        $breadcrumbs[] = array(
            'label'   => __('Page Not Found', 'pdfviewer'),
            'url'     => null,
            'current' => true,
        );
    }

    // Chevron separator SVG - responsive size
    $chevron = '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="breadcrumb-chevron text-muted-foreground/60 shrink-0" aria-hidden="true"><polyline points="9 18 15 12 9 6"></polyline></svg>';

    // Build HTML - matches React VisualBreadcrumbs component
    // Full-width bar with muted background, compact layout, horizontal scroll
    ob_start();
    ?>
    <nav
        class="breadcrumb-nav bg-muted/60 border-b border-border"
        aria-label="<?php esc_attr_e('Breadcrumb navigation', 'pdfviewer'); ?>"
        role="navigation"
    >
        <div class="container mx-auto px-4 lg:px-8">
            <ol
                class="flex items-center gap-1 py-2.5 text-sm overflow-x-auto whitespace-nowrap scrollbar-breadcrumb"
                itemscope
                itemtype="https://schema.org/BreadcrumbList"
            >
                <?php foreach ($breadcrumbs as $index => $crumb) :
                    $is_first = ($index === 0);
                    $is_last = ($index === count($breadcrumbs) - 1);
                    $is_current = !empty($crumb['current']);
                    $is_external = !empty($crumb['external']);
                ?>
                    <li
                        class="inline-flex items-center gap-1 shrink-0"
                        itemscope
                        itemprop="itemListElement"
                        itemtype="https://schema.org/ListItem"
                    >
                        <?php // Separator (not for first item) ?>
                        <?php if (!$is_first) : ?>
                            <?php echo $chevron; ?>
                        <?php endif; ?>

                        <?php if ($is_last || $is_current) : ?>
                            <?php // Current page - not a link, foreground color, truncate on mobile ?>
                            <span
                                class="text-foreground font-medium breadcrumb-current"
                                aria-current="page"
                                itemprop="name"
                            ><?php echo esc_html($crumb['label']); ?></span>
                        <?php elseif ($is_external) : ?>
                            <?php // External link (Dross:Media) ?>
                            <a
                                href="<?php echo esc_url($crumb['url']); ?>"
                                target="_blank"
                                rel="noopener"
                                class="text-muted-foreground hover:text-primary transition-colors"
                                title="<?php echo esc_attr(sprintf(__('Go to %s (opens in new window)', 'pdfviewer'), $crumb['label'])); ?>"
                                aria-label="<?php echo esc_attr(sprintf(__('Go to %s (opens in new window)', 'pdfviewer'), $crumb['label'])); ?>"
                                itemprop="item"
                            >
                                <span itemprop="name"><?php echo esc_html($crumb['label']); ?></span>
                            </a>
                        <?php else : ?>
                            <?php // Internal link ?>
                            <a
                                href="<?php echo esc_url($crumb['url']); ?>"
                                class="text-muted-foreground hover:text-primary transition-colors"
                                title="<?php echo esc_attr(sprintf(__('Go to %s', 'pdfviewer'), $crumb['label'])); ?>"
                                aria-label="<?php echo esc_attr(sprintf(__('Go to %s', 'pdfviewer'), $crumb['label'])); ?>"
                                itemprop="item"
                            >
                                <span itemprop="name"><?php echo esc_html($crumb['label']); ?></span>
                            </a>
                        <?php endif; ?>

                        <?php // Hidden position meta for schema ?>
                        <meta itemprop="position" content="<?php echo esc_attr($index + 1); ?>" />
                    </li>
                <?php endforeach; ?>
            </ol>
        </div>
    </nav>
    <?php
    return ob_get_clean();
}

