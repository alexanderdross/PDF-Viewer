<?php
/**
 * PHPUnit Bootstrap File
 *
 * Sets up the testing environment for the PDF Viewer Theme
 *
 * @package PDFViewer
 * @subpackage Tests
 */

// Define WordPress constants for testing
if (!defined('ABSPATH')) {
    define('ABSPATH', dirname(__DIR__) . '/');
}

if (!defined('PDFVIEWER_THEME_VERSION')) {
    define('PDFVIEWER_THEME_VERSION', '2.2.96');
}

if (!defined('PDFVIEWER_THEME_DIR')) {
    define('PDFVIEWER_THEME_DIR', dirname(__DIR__));
}

if (!defined('PDFVIEWER_THEME_URI')) {
    define('PDFVIEWER_THEME_URI', 'https://example.com/wp-content/themes/pdfviewer-theme');
}

/**
 * WordPress Function Stubs
 * These mock WordPress core functions for unit testing without WordPress loaded
 */

if (!function_exists('wp_parse_args')) {
    /**
     * Merge user defined arguments into defaults array.
     */
    function wp_parse_args($args, $defaults = []) {
        if (is_object($args)) {
            $args = get_object_vars($args);
        } elseif (!is_array($args)) {
            parse_str($args, $args);
        }
        return array_merge($defaults, $args);
    }
}

if (!function_exists('esc_url')) {
    /**
     * Checks and cleans a URL.
     */
    function esc_url($url) {
        if (empty($url)) {
            return '';
        }
        return filter_var($url, FILTER_SANITIZE_URL);
    }
}

if (!function_exists('esc_attr')) {
    /**
     * Escapes for HTML attribute context.
     */
    function esc_attr($text) {
        return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
    }
}

if (!function_exists('esc_html')) {
    /**
     * Escapes for HTML context.
     */
    function esc_html($text) {
        return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
    }
}

if (!function_exists('esc_html_e')) {
    /**
     * Displays translated text that has been escaped for safe use in HTML output.
     */
    function esc_html_e($text, $domain = 'default') {
        echo esc_html($text);
    }
}

if (!function_exists('esc_attr_e')) {
    /**
     * Displays translated text that has been escaped for safe use in an attribute.
     */
    function esc_attr_e($text, $domain = 'default') {
        echo esc_attr($text);
    }
}

if (!function_exists('__')) {
    /**
     * Retrieves the translation of $text.
     */
    function __($text, $domain = 'default') {
        return $text;
    }
}

if (!function_exists('_e')) {
    /**
     * Displays translated text.
     */
    function _e($text, $domain = 'default') {
        echo $text;
    }
}

if (!function_exists('home_url')) {
    /**
     * Retrieves the URL for the current site.
     */
    function home_url($path = '', $scheme = null) {
        return 'https://example.com' . $path;
    }
}

if (!function_exists('get_template_directory')) {
    /**
     * Retrieves template directory path for the active theme.
     */
    function get_template_directory() {
        return PDFVIEWER_THEME_DIR;
    }
}

if (!function_exists('get_template_directory_uri')) {
    /**
     * Retrieves template directory URI for the active theme.
     */
    function get_template_directory_uri() {
        return PDFVIEWER_THEME_URI;
    }
}

if (!function_exists('wp_json_encode')) {
    /**
     * Encodes a variable into JSON.
     */
    function wp_json_encode($data, $options = 0, $depth = 512) {
        return json_encode($data, $options, $depth);
    }
}

if (!function_exists('sanitize_text_field')) {
    /**
     * Sanitizes a string from user input.
     */
    function sanitize_text_field($str) {
        return htmlspecialchars(strip_tags($str), ENT_QUOTES, 'UTF-8');
    }
}

if (!function_exists('wp_kses_post')) {
    /**
     * Sanitizes content for allowed HTML tags for post content.
     */
    function wp_kses_post($data) {
        return $data; // Simplified for testing
    }
}

if (!function_exists('absint')) {
    /**
     * Convert a value to non-negative integer.
     */
    function absint($value) {
        return abs((int) $value);
    }
}

if (!function_exists('wp_date')) {
    /**
     * Retrieves the date in localized format.
     */
    function wp_date($format, $timestamp = null, $timezone = null) {
        return date($format, $timestamp ?? time());
    }
}

if (!function_exists('add_action')) {
    function add_action($tag, $fn, $priority = 10, $accepted_args = 1) {}
}

if (!function_exists('add_filter')) {
    function add_filter($tag, $fn, $priority = 10, $accepted_args = 1) {}
}

if (!function_exists('remove_action')) {
    function remove_action($tag, $fn, $priority = 10) {}
}

if (!function_exists('get_page_by_path')) {
    function get_page_by_path($path) {
        return null;
    }
}

if (!function_exists('wp_insert_post')) {
    function wp_insert_post($args) {
        return 1;
    }
}

if (!function_exists('update_post_meta')) {
    function update_post_meta($id, $key, $value) {
        return true;
    }
}

if (!function_exists('get_the_title')) {
    function get_the_title($id = 0) {
        return 'Test Page';
    }
}

if (!function_exists('get_the_date')) {
    function get_the_date($fmt = '') {
        return '2026-02-25T12:00:00+00:00';
    }
}

if (!function_exists('get_the_modified_date')) {
    function get_the_modified_date($fmt = '') {
        return '2026-02-25T14:00:00+00:00';
    }
}

if (!function_exists('get_post_field')) {
    function get_post_field($field, $id = 0) {
        return 'test-page';
    }
}

if (!function_exists('get_the_ID')) {
    function get_the_ID() {
        return 1;
    }
}

if (!function_exists('is_front_page')) {
    function is_front_page() {
        return false;
    }
}

if (!function_exists('is_singular')) {
    function is_singular() {
        return true;
    }
}

if (!function_exists('defined')) {
    // built-in, no stub needed
}

if (!defined('PDFVIEWER_VANITY_DOMAIN')) {
    define('PDFVIEWER_VANITY_DOMAIN', 'https://pdfviewermodule.com');
}

// Stub WordPress Walker_Nav_Menu base class required by theme's custom walker.
if (!class_exists('Walker_Nav_Menu')) {
    class Walker_Nav_Menu {
        public function walk($elements, $max_depth, ...$args) { return ''; }
        public function start_lvl(&$output, $depth = 0, $args = null) {}
        public function end_lvl(&$output, $depth = 0, $args = null) {}
        public function start_el(&$output, $data_object, $depth = 0, $args = null, $current_object_id = 0) {}
        public function end_el(&$output, $data_object, $depth = 0, $args = null) {}
    }
}

// Autoload test classes
spl_autoload_register(function ($class) {
    $prefix = 'PDFViewer\\Tests\\';
    $base_dir = __DIR__ . '/';

    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    $relative_class = substr($class, $len);
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

    if (file_exists($file)) {
        require $file;
    }
});

echo "PDFViewer Theme Test Bootstrap Loaded\n";
echo "Theme Version: " . PDFVIEWER_THEME_VERSION . "\n";
echo "Theme Directory: " . PDFVIEWER_THEME_DIR . "\n\n";
