<?php
/**
 * Theme Customizer Settings
 *
 * @package PDFViewer
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Register Customizer Settings
 */
function pdfviewer_customize_register($wp_customize) {
    // Theme Options Section
    $wp_customize->add_section('pdfviewer_options', array(
        'title'       => __('Theme Options', 'pdfviewer'),
        'description' => __('Configure theme-specific options', 'pdfviewer'),
        'priority'    => 30,
    ));

    // WordPress Download URL
    $wp_customize->add_setting('pdfviewer_wordpress_url', array(
        'default'           => 'https://wordpress.org/plugins/pdf-embed-seo-optimize',
        'sanitize_callback' => 'esc_url_raw',
    ));

    $wp_customize->add_control('pdfviewer_wordpress_url', array(
        'label'       => __('WordPress Plugin URL', 'pdfviewer'),
        'description' => __('URL to the WordPress.org plugin page', 'pdfviewer'),
        'section'     => 'pdfviewer_options',
        'type'        => 'url',
    ));

    // Drupal Download URL
    $wp_customize->add_setting('pdfviewer_drupal_url', array(
        'default'           => 'https://www.drupal.org/project/pdf-embed-seo-optimize',
        'sanitize_callback' => 'esc_url_raw',
    ));

    $wp_customize->add_control('pdfviewer_drupal_url', array(
        'label'       => __('Drupal Module URL', 'pdfviewer'),
        'description' => __('URL to the Drupal.org module page', 'pdfviewer'),
        'section'     => 'pdfviewer_options',
        'type'        => 'url',
    ));

    // Social Links Section
    $wp_customize->add_section('pdfviewer_social', array(
        'title'       => __('Social Links', 'pdfviewer'),
        'description' => __('Configure social media links', 'pdfviewer'),
        'priority'    => 35,
    ));

    // Imprint URL
    $wp_customize->add_setting('pdfviewer_imprint_url', array(
        'default'           => 'https://dross.net/imprint',
        'sanitize_callback' => 'esc_url_raw',
    ));

    $wp_customize->add_control('pdfviewer_imprint_url', array(
        'label'   => __('Imprint URL', 'pdfviewer'),
        'section' => 'pdfviewer_social',
        'type'    => 'url',
    ));

    // Privacy Policy URL
    $wp_customize->add_setting('pdfviewer_privacy_url', array(
        'default'           => 'https://dross.net/privacy-policy',
        'sanitize_callback' => 'esc_url_raw',
    ));

    $wp_customize->add_control('pdfviewer_privacy_url', array(
        'label'   => __('Privacy Policy URL', 'pdfviewer'),
        'section' => 'pdfviewer_social',
        'type'    => 'url',
    ));

    // Company Website URL
    $wp_customize->add_setting('pdfviewer_company_url', array(
        'default'           => 'https://dross.net/#media',
        'sanitize_callback' => 'esc_url_raw',
    ));

    $wp_customize->add_control('pdfviewer_company_url', array(
        'label'   => __('Company Website URL', 'pdfviewer'),
        'section' => 'pdfviewer_social',
        'type'    => 'url',
    ));
}
add_action('customize_register', 'pdfviewer_customize_register');

/**
 * Get Theme Option
 */
function pdfviewer_get_option($option, $default = '') {
    return get_theme_mod('pdfviewer_' . $option, $default);
}

