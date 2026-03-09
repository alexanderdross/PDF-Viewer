<?php
/**
 * Performance Optimizations
 * Server-side rendering and caching optimizations
 *
 * @package PDFViewer
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Remove jQuery Migrate (if not needed)
 */
function pdfviewer_remove_jquery_migrate($scripts) {
    if (!is_admin() && isset($scripts->registered['jquery'])) {
        $script = $scripts->registered['jquery'];
        if ($script->deps) {
            $script->deps = array_diff($script->deps, array('jquery-migrate'));
        }
    }
}
add_action('wp_default_scripts', 'pdfviewer_remove_jquery_migrate');

/**
 * Defer Non-Critical JavaScript
 */
function pdfviewer_defer_scripts($tag, $handle, $src) {
    // Don't defer admin scripts
    if (is_admin()) {
        return $tag;
    }

    // Scripts to defer (theme + known non-critical plugin scripts)
    $defer_scripts = array(
        'pdfviewer-main',
        'wp-embed',
    );

    if (in_array($handle, $defer_scripts)) {
        return str_replace(' src', ' defer src', $tag);
    }

    // Defer performance measurement plugin scripts to reduce critical path
    if (
        strpos($handle, 'od-') === 0 ||
        strpos($handle, 'optimization-detective') !== false ||
        strpos($handle, 'image-prioritizer') !== false ||
        $handle === 'web-vitals'
    ) {
        // Only add defer if not already present
        if (strpos($tag, 'defer') === false && strpos($tag, 'async') === false) {
            return str_replace(' src', ' defer src', $tag);
        }
    }

    return $tag;
}
add_filter('script_loader_tag', 'pdfviewer_defer_scripts', 10, 3);

/**
 * Break performance plugin dependency chains
 * Optimization Detective → Web Vitals → Image Prioritizer creates a 739ms chain.
 * Removing deps lets the browser fetch them in parallel instead of sequentially.
 */
function pdfviewer_flatten_plugin_script_deps() {
    if (is_admin()) {
        return;
    }

    $handles = array('od-detection', 'web-vitals', 'image-prioritizer-detection');
    foreach ($handles as $handle) {
        if (wp_script_is($handle, 'registered')) {
            global $wp_scripts;
            if (isset($wp_scripts->registered[$handle])) {
                $wp_scripts->registered[$handle]->deps = array();
            }
        }
    }
}
add_action('wp_enqueue_scripts', 'pdfviewer_flatten_plugin_script_deps', 999);

/**
 * Remove WordPress Version from Scripts and Styles
 */
function pdfviewer_remove_version_strings($src) {
    if (strpos($src, 'ver=')) {
        // Keep version for theme assets for cache busting
        if (strpos($src, PDFVIEWER_THEME_URI) !== false) {
            return $src;
        }
        $src = remove_query_arg('ver', $src);
    }
    return $src;
}
add_filter('style_loader_src', 'pdfviewer_remove_version_strings', 999);
add_filter('script_loader_src', 'pdfviewer_remove_version_strings', 999);

/**
 * Optimize Images in Content
 * Add lazy loading and decoding attributes to content images
 */
function pdfviewer_optimize_content_images($content) {
    if (empty($content)) {
        return $content;
    }

    // Add loading="lazy" and decoding="async" to images without these attributes
    $content = preg_replace_callback(
        '/<img([^>]+)>/i',
        function ($matches) {
            $img = $matches[0];

            // Skip if already has loading attribute
            if (strpos($img, 'loading=') === false) {
                $img = str_replace('<img', '<img loading="lazy"', $img);
            }

            // Skip if already has decoding attribute
            if (strpos($img, 'decoding=') === false) {
                $img = str_replace('<img', '<img decoding="async"', $img);
            }

            return $img;
        },
        $content
    );

    return $content;
}
add_filter('the_content', 'pdfviewer_optimize_content_images', 99);

/**
 * Add Width and Height to Images
 * Helps prevent CLS by ensuring images have dimensions
 */
function pdfviewer_add_image_dimensions($content) {
    if (empty($content)) {
        return $content;
    }

    // Match images without width/height
    $content = preg_replace_callback(
        '/<img([^>]+)src=["\']([^"\']+)["\']([^>]*)>/i',
        function ($matches) {
            $before = $matches[1];
            $src = $matches[2];
            $after = $matches[3];

            // Skip if already has width and height
            if (strpos($before . $after, 'width=') !== false && strpos($before . $after, 'height=') !== false) {
                return $matches[0];
            }

            // Try to get image dimensions
            $image_path = '';

            // Check if it's a local image
            if (strpos($src, home_url()) !== false) {
                $image_path = str_replace(home_url(), ABSPATH, $src);
            } elseif (strpos($src, '/wp-content/') !== false) {
                $image_path = ABSPATH . ltrim($src, '/');
            }

            if ($image_path && file_exists($image_path)) {
                $size = @getimagesize($image_path);
                if ($size) {
                    $width = $size[0];
                    $height = $size[1];

                    // Only add if not already present
                    $attrs = '';
                    if (strpos($before . $after, 'width=') === false) {
                        $attrs .= ' width="' . $width . '"';
                    }
                    if (strpos($before . $after, 'height=') === false) {
                        $attrs .= ' height="' . $height . '"';
                    }

                    return '<img' . $before . 'src="' . $src . '"' . $after . $attrs . '>';
                }
            }

            return $matches[0];
        },
        $content
    );

    return $content;
}
add_filter('the_content', 'pdfviewer_add_image_dimensions', 98);

/**
 * Remove Unnecessary Meta Tags
 */
function pdfviewer_remove_meta_tags() {
    // Remove Windows Live Writer manifest link
    remove_action('wp_head', 'wlwmanifest_link');

    // Remove RSD link
    remove_action('wp_head', 'rsd_link');

    // Remove WordPress version
    remove_action('wp_head', 'wp_generator');

    // Remove shortlink
    remove_action('wp_head', 'wp_shortlink_wp_head');

    // Remove REST API link
    remove_action('wp_head', 'rest_output_link_wp_head');

    // Remove oEmbed discovery links
    remove_action('wp_head', 'wp_oembed_add_discovery_links');
}
add_action('init', 'pdfviewer_remove_meta_tags');

/**
 * Disable XML-RPC
 */
add_filter('xmlrpc_enabled', '__return_false');

/**
 * Enable PHP-level gzip compression as fallback
 * Applies when mod_deflate / server-level compression is not configured.
 * Reduces HTML document size by ~70% (e.g., 23 KiB → ~7 KiB).
 */
function pdfviewer_enable_gzip() {
    if (is_admin() || wp_doing_ajax()) {
        return;
    }

    // Skip if compression is already active (server-level)
    if (ini_get('zlib.output_compression') || ini_get('output_handler') === 'ob_gzhandler') {
        return;
    }

    // Enable zlib output compression if available
    if (extension_loaded('zlib') && !headers_sent()) {
        ini_set('zlib.output_compression', 'On');
        ini_set('zlib.output_compression_level', '6');
    }
}
add_action('template_redirect', 'pdfviewer_enable_gzip', -2);

/**
 * Optimize Output Buffer
 * Minify HTML output
 */
function pdfviewer_minify_html($buffer) {
    // Don't minify admin pages
    if (is_admin()) {
        return $buffer;
    }

    // Don't minify if viewing page source for debugging
    if (filter_input(INPUT_GET, 'source', FILTER_SANITIZE_SPECIAL_CHARS) !== null) {
        return $buffer;
    }

    // Basic HTML minification
    $search = array(
        '/\>[^\S ]+/s',     // Strip whitespaces after tags, except space
        '/[^\S ]+\</s',     // Strip whitespaces before tags, except space
        '/(\s)+/s',         // Shorten multiple whitespace sequences
    );

    $replace = array(
        '>',
        '<',
        '\\1',
    );

    // Don't minify script and style content
    $buffer = preg_replace_callback(
        '/<(script|style)[^>]*>.*?<\/\1>/is',
        function ($matches) {
            return '<!--PDFVIEWER_PRESERVE-->' . base64_encode($matches[0]) . '<!--/PDFVIEWER_PRESERVE-->';
        },
        $buffer
    );

    // Minify HTML
    $buffer = preg_replace($search, $replace, $buffer);

    // Restore script and style content
    $buffer = preg_replace_callback(
        '/<!--PDFVIEWER_PRESERVE-->([^<]+)<!--\/PDFVIEWER_PRESERVE-->/s',
        function ($matches) {
            return base64_decode($matches[1]);
        },
        $buffer
    );

    return $buffer;
}

/**
 * Start Output Buffer for Minification
 */
function pdfviewer_start_buffer() {
    if (!is_admin() && !wp_doing_ajax()) {
        ob_start('pdfviewer_minify_html');
    }
}
add_action('template_redirect', 'pdfviewer_start_buffer', -1);

/**
 * Add Cache Headers
 * Extends cache lifetime for static marketing pages to reduce TTFB on repeat visits.
 */
function pdfviewer_cache_headers() {
    if (is_admin() || is_user_logged_in() || headers_sent()) {
        return;
    }

    // Static marketing pages: cache for 24 hours (86400s)
    // Dynamic pages with comments: shorter cache
    if (!is_singular() || !comments_open()) {
        header('Cache-Control: public, max-age=86400, stale-while-revalidate=3600');
    } else {
        header('Cache-Control: public, max-age=3600');
    }
}
add_action('send_headers', 'pdfviewer_cache_headers');

/**
 * Optimize Post Thumbnail Output
 */
function pdfviewer_post_thumbnail($html, $post_id, $post_thumbnail_id, $size, $attr) {
    // Add loading="lazy" if not set
    if (strpos($html, 'loading=') === false) {
        $html = str_replace('<img', '<img loading="lazy"', $html);
    }

    // Add decoding="async" if not set
    if (strpos($html, 'decoding=') === false) {
        $html = str_replace('<img', '<img decoding="async"', $html);
    }

    return $html;
}
add_filter('post_thumbnail_html', 'pdfviewer_post_thumbnail', 10, 5);

/**
 * Add WebP Support Detection
 */
function pdfviewer_webp_detection_script() {
    ?>
    <script>
    (function(){
        var webp=new Image();
        webp.onload=webp.onerror=function(){
            var s=webp.height===1;
            document.documentElement.classList.add(s?'webp':'no-webp');
        };
        webp.src='data:image/webp;base64,UklGRjoAAABXRUJQVlA4IC4AAACyAgCdASoCAAIALmk0mk0iIiIiIgBoSygABc6WWgAA/veff/0PP8bA//LwYAAA';
    })();
    </script>
    <?php
}
add_action('wp_head', 'pdfviewer_webp_detection_script', 1);

/**
 * Register Additional Image Sizes
 */
function pdfviewer_register_image_sizes() {
    // Responsive image sizes
    add_image_size('pdfviewer-mobile', 480, 0, false);
    add_image_size('pdfviewer-tablet', 768, 0, false);
    add_image_size('pdfviewer-desktop', 1200, 0, false);
    add_image_size('pdfviewer-large', 1600, 0, false);

    // Hero image sizes
    add_image_size('pdfviewer-hero-mobile', 640, 360, true);
    add_image_size('pdfviewer-hero-tablet', 1024, 576, true);
    add_image_size('pdfviewer-hero-desktop', 1920, 1080, true);

    // Card image sizes
    add_image_size('pdfviewer-card', 400, 300, true);
    add_image_size('pdfviewer-card-2x', 800, 600, true);
}
add_action('after_setup_theme', 'pdfviewer_register_image_sizes');

/**
 * Add Image Size Names to Media Library
 */
function pdfviewer_image_size_names($sizes) {
    return array_merge($sizes, array(
        'pdfviewer-mobile'  => __('PDF Viewer Mobile', 'pdfviewer'),
        'pdfviewer-tablet'  => __('PDF Viewer Tablet', 'pdfviewer'),
        'pdfviewer-desktop' => __('PDF Viewer Desktop', 'pdfviewer'),
        'pdfviewer-card'    => __('PDF Viewer Card', 'pdfviewer'),
    ));
}
add_filter('image_size_names_choose', 'pdfviewer_image_size_names');
