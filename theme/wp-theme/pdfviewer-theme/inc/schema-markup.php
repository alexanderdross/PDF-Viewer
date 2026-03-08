<?php
/**
 * JSON-LD Schema Markup
 * Adds structured data for better SEO and rich snippets
 *
 * @package PDFViewer
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Vanity domain for dual-domain schema markup.
 * Used alongside home_url() to include both domains in schema URL arrays.
 */
if (!defined('PDFVIEWER_VANITY_DOMAIN')) {
    define('PDFVIEWER_VANITY_DOMAIN', 'https://pdfviewermodule.com');
}

/**
 * Helper: Build a canonical URL for schema markup.
 *
 * Returns the vanity domain URL as a single string. Schema.org properties
 * like url, @id, and item require a string, not an array.
 *
 * @param string $path The path to append (e.g. '/pro/').
 * @return string Canonical URL using the vanity domain.
 */
function pdfviewer_schema_urls($path = '/') {
    return PDFVIEWER_VANITY_DOMAIN . $path;
}

/**
 * Add FAQ Schema from Block Content
 * Automatically detects FAQ blocks/patterns and generates FAQ schema
 */
function pdfviewer_faq_schema() {
    if (!is_singular()) {
        return;
    }

    $post = get_post();
    if (!$post) {
        return;
    }

    $content = $post->post_content;

    // Check if page has FAQ content (accordion or details elements)
    if (strpos($content, 'faq') === false && strpos($content, 'accordion') === false) {
        return;
    }

    // Parse FAQ items from block content
    $faq_items = array();

    // Match accordion-style FAQs
    if (preg_match_all('/<button[^>]*class="[^"]*accordion-trigger[^"]*"[^>]*>([^<]+)<\/button>.*?<div[^>]*class="[^"]*accordion-content[^"]*"[^>]*>(.*?)<\/div>/is', $content, $matches, PREG_SET_ORDER)) {
        foreach ($matches as $match) {
            $question = wp_strip_all_tags(trim($match[1]));
            $answer = wp_strip_all_tags(trim($match[2]));

            if (!empty($question) && !empty($answer)) {
                $faq_items[] = array(
                    '@type'          => 'Question',
                    'name'           => $question,
                    'acceptedAnswer' => array(
                        '@type' => 'Answer',
                        'text'  => $answer,
                    ),
                );
            }
        }
    }

    // Match details/summary elements
    if (preg_match_all('/<summary[^>]*>(.*?)<\/summary>.*?<div[^>]*>(.*?)<\/div>/is', $content, $matches, PREG_SET_ORDER)) {
        foreach ($matches as $match) {
            $question = wp_strip_all_tags(trim($match[1]));
            $answer = wp_strip_all_tags(trim($match[2]));

            if (!empty($question) && !empty($answer)) {
                $faq_items[] = array(
                    '@type'          => 'Question',
                    'name'           => $question,
                    'acceptedAnswer' => array(
                        '@type' => 'Answer',
                        'text'  => $answer,
                    ),
                );
            }
        }
    }

    if (empty($faq_items)) {
        return;
    }

    $schema = array(
        '@context'   => 'https://schema.org',
        '@type'      => 'FAQPage',
        'mainEntity' => $faq_items,
    );

    echo '<script type="application/ld+json">' . wp_json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . '</script>' . "\n";
}
add_action('wp_head', 'pdfviewer_faq_schema', 20);

/**
 * Add HowTo Schema from Block Content
 * Detects step-by-step content and generates HowTo schema
 */
function pdfviewer_howto_schema() {
    if (!is_singular()) {
        return;
    }

    $post = get_post();
    if (!$post) {
        return;
    }

    $content = $post->post_content;

    // Check if page has how-to content
    if (strpos($content, 'how-it-works') === false && strpos($content, 'howto') === false && strpos($content, 'step') === false) {
        return;
    }

    // Parse steps from numbered lists or step sections
    $steps = array();

    // Match ordered list items
    if (preg_match_all('/<li[^>]*>([^<]+(?:<[^>]+>[^<]+<\/[^>]+>)*[^<]*)<\/li>/is', $content, $matches)) {
        foreach ($matches[1] as $index => $step_text) {
            $step_text = wp_strip_all_tags(trim($step_text));
            if (!empty($step_text)) {
                $steps[] = array(
                    '@type' => 'HowToStep',
                    'name'  => 'Step ' . ($index + 1),
                    'text'  => $step_text,
                );
            }
        }
    }

    if (empty($steps) || count($steps) < 2) {
        return;
    }

    $schema = array(
        '@context'    => 'https://schema.org',
        '@type'       => 'HowTo',
        'name'        => get_the_title(),
        'description' => get_the_excerpt() ?: wp_trim_words(wp_strip_all_tags($content), 30),
        'step'        => $steps,
    );

    echo '<script type="application/ld+json">' . wp_json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . '</script>' . "\n";
}
add_action('wp_head', 'pdfviewer_howto_schema', 20);

/**
 * Add Organization Schema
 */
function pdfviewer_organization_schema() {
    if (!is_front_page()) {
        return;
    }

    $schema = array(
        '@context' => 'https://schema.org',
        '@type'    => 'Organization',
        'name'     => 'Dross:Media',
        'url'      => PDFVIEWER_VANITY_DOMAIN . '/',
        'sameAs'   => array(
            PDFVIEWER_VANITY_DOMAIN,
            'https://wordpress.org/plugins/pdf-embed-seo-optimize',
            'https://www.drupal.org/project/pdf-embed-seo-optimize',
        ),
        'logo'     => array(
            '@type' => 'ImageObject',
            'url'   => PDFVIEWER_THEME_URI . '/assets/images/logo.png',
        ),
    );

    echo '<script type="application/ld+json">' . wp_json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . '</script>' . "\n";
}
add_action('wp_head', 'pdfviewer_organization_schema', 20);

/**
 * Add Breadcrumb Schema
 * Schema starts with PDF Viewer (internal site navigation only)
 * Visual breadcrumb includes external links but schema is site-specific
 */
function pdfviewer_breadcrumb_schema() {
    if (is_front_page()) {
        return;
    }

    $breadcrumbs = array();
    $position = 1;

    // PDF Viewer (this site's home - first item in schema, uses vanity domain)
    $breadcrumbs[] = array(
        '@type'    => 'ListItem',
        'position' => $position++,
        'name'     => __('PDF Viewer', 'pdfviewer'),
        'item'     => PDFVIEWER_VANITY_DOMAIN . '/',
    );

    // If page
    if (is_page()) {
        // Get current page slug reliably
        global $post;
        $page_slug = '';
        if ($post) {
            $page_slug = $post->post_name;
        }

        // Custom titles for platform-specific pages
        $custom_titles = array(
            'wordpress-pdf-viewer' => 'PDF Viewer Plugin for WordPress',
            'drupal-pdf-viewer'    => 'PDF Viewer Module for Drupal',
            'nextjs-pdf-viewer'    => 'PDF Viewer Component for React / Next.js',
        );

        $page_title = isset($custom_titles[$page_slug]) ? $custom_titles[$page_slug] : get_the_title();

        $breadcrumbs[] = array(
            '@type'    => 'ListItem',
            'position' => $position,
            'name'     => $page_title,
            'item'     => PDFVIEWER_VANITY_DOMAIN . '/' . $page_slug . '/',
        );
    }

    if (count($breadcrumbs) < 2) {
        return;
    }

    $schema = array(
        '@context'        => 'https://schema.org',
        '@type'           => 'BreadcrumbList',
        'itemListElement' => $breadcrumbs,
    );

    echo '<script type="application/ld+json">' . wp_json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . '</script>' . "\n";
}
add_action('wp_head', 'pdfviewer_breadcrumb_schema', 20);

/**
 * Add WebSite Schema with Search Action
 */
function pdfviewer_website_schema() {
    if (!is_front_page()) {
        return;
    }

    $schema = array(
        '@context'        => 'https://schema.org',
        '@type'           => 'WebSite',
        'name'            => get_bloginfo('name'),
        'url'             => pdfviewer_schema_urls('/'),
        'potentialAction' => array(
            '@type'       => 'SearchAction',
            'target'      => array(
                '@type'       => 'EntryPoint',
                'urlTemplate' => PDFVIEWER_VANITY_DOMAIN . '/?s={search_term_string}',
            ),
            'query-input' => 'required name=search_term_string',
        ),
    );

    echo '<script type="application/ld+json">' . wp_json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . '</script>' . "\n";
}
add_action('wp_head', 'pdfviewer_website_schema', 20);

/**
 * Add Product/Software Schema for Plugin
 */
function pdfviewer_software_schema() {
    // Only on pages about the plugin
    $plugin_pages = array('wordpress-pdf-viewer', 'drupal-pdf-viewer', 'nextjs-pdf-viewer', 'pro');

    if (!is_page($plugin_pages)) {
        return;
    }

    $schema = array(
        '@context'            => 'https://schema.org',
        '@type'               => 'SoftwareApplication',
        'name'                => 'PDF Embed & SEO Optimize',
        'applicationCategory' => 'WebApplication',
        'operatingSystem'     => 'WordPress, Drupal, React, Next.js',
        'url'                 => pdfviewer_schema_urls('/'),
        'offers'              => array(
            '@type'         => 'Offer',
            'price'         => '0',
            'priceCurrency' => 'EUR',
            'availability'  => 'https://schema.org/InStock',
        ),
        'featureList'         => array(
            'SEO-optimized PDF embedding',
            'Clean URLs for PDFs',
            'Automatic XML sitemap',
            'Open Graph and Twitter Cards',
            'Responsive PDF viewer',
            'View analytics',
        ),
        'screenshot'          => array(
            PDFVIEWER_THEME_URI . '/assets/images/og-image.jpg',
            PDFVIEWER_THEME_URI . '/assets/images/pwa-512x512.png',
        ),
        'downloadUrl'         => 'https://wordpress.org/plugins/pdf-embed-seo-optimize',
        'aggregateRating'     => array(
            '@type'       => 'AggregateRating',
            'ratingValue' => '4.8',
            'ratingCount' => '487',
            'bestRating'  => '5',
            'worstRating' => '1',
        ),
    );

    echo '<script type="application/ld+json">' . wp_json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . '</script>' . "\n";
}
add_action('wp_head', 'pdfviewer_software_schema', 20);

/**
 * Add SoftwareSourceCode Schema for React/Next.js Component
 */
function pdfviewer_react_component_schema() {
    // Only on the Next.js PDF Viewer page
    if (!is_page('nextjs-pdf-viewer')) {
        return;
    }

    $schema = array(
        '@context'            => 'https://schema.org',
        '@type'               => 'SoftwareSourceCode',
        'name'                => '@pdf-embed-seo/react',
        'description'         => 'Modern PDF viewer component for React and Next.js applications. TypeScript support, SSR compatible, and optimized for performance.',
        'codeRepository'      => 'https://www.npmjs.com/package/@pdf-embed-seo/react',
        'programmingLanguage' => array(
            '@type' => 'ComputerLanguage',
            'name'  => 'TypeScript',
        ),
        'runtimePlatform'     => 'React, Next.js',
        'license'             => 'https://opensource.org/licenses/MIT',
        'author'              => array(
            '@type' => 'Organization',
            'name'  => 'Dross:Media',
            'url'   => 'https://dross.net',
        ),
        'offers'              => array(
            '@type'         => 'Offer',
            'price'         => '0',
            'priceCurrency' => 'EUR',
            'availability'  => 'https://schema.org/InStock',
        ),
    );

    echo '<script type="application/ld+json">' . wp_json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . '</script>' . "\n";
}
add_action('wp_head', 'pdfviewer_react_component_schema', 20);

/**
 * Testimonials schema removed - reviews are now included in pdfviewer_pro_page_schema()
 * to avoid duplicate Product schemas on the /pro/ page.
 */

/**
 * Get Product Schema with AggregateRating array
 * Centralized function to ensure consistent schema across all pages
 *
 * @return array Product schema data
 */
function pdfviewer_get_product_schema() {
    return array(
        '@context'        => 'https://schema.org',
        '@type'           => 'Product',
        'name'            => 'PDF Embed & SEO Optimize',
        'description'     => 'Display PDFs beautifully with SEO-optimized pages. Schema.org markup, AI-ready structured data, and analytics for WordPress and Drupal.',
        'brand'           => array(
            '@type' => 'Brand',
            'name'  => 'Dross:Media',
        ),
        'image'           => array(
            PDFVIEWER_THEME_URI . '/assets/images/og-image.jpg',
            PDFVIEWER_THEME_URI . '/assets/images/pwa-512x512.png',
        ),
        'url'             => pdfviewer_schema_urls('/'),
        'offers'          => array(
            '@type'         => 'AggregateOffer',
            'lowPrice'      => '0',
            'highPrice'     => '199',
            'priceCurrency' => 'EUR',
            'availability'  => 'https://schema.org/InStock',
            'offerCount'    => '4',
        ),
        'aggregateRating' => array(
            '@type'       => 'AggregateRating',
            'ratingValue' => '4.8',
            'ratingCount' => '487',
            'bestRating'  => '5',
            'worstRating' => '1',
        ),
    );
}

/**
 * Add Product Schema with AggregateRating to most pages (via wp_head)
 * This provides consistent product rating visibility across the site
 * Skips pages that have their own dedicated Product schema (pro page)
 */
function pdfviewer_global_product_schema() {
    global $pdfviewer_product_schema_output;

    // Prevent duplicate output
    if (!empty($pdfviewer_product_schema_output)) {
        return;
    }

    // Skip pages that have their own dedicated Product schema
    if (is_page('pro')) {
        return;
    }

    $pdfviewer_product_schema_output = true;
    $schema = pdfviewer_get_product_schema();

    echo '<script type="application/ld+json">' . wp_json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . '</script>' . "\n";
}
add_action('wp_head', 'pdfviewer_global_product_schema', 15);

/**
 * Fallback: Add Product Schema via wp_footer for plugin-generated pages
 * This catches pages that might not call wp_head() (rare but possible with some plugins)
 */
function pdfviewer_global_product_schema_fallback() {
    global $pdfviewer_product_schema_output;

    // Only output if wp_head didn't already output it
    if (!empty($pdfviewer_product_schema_output)) {
        return;
    }

    // Skip pages that have their own dedicated Product schema
    if (is_page('pro')) {
        return;
    }

    $pdfviewer_product_schema_output = true;
    $schema = pdfviewer_get_product_schema();

    echo '<script type="application/ld+json">' . wp_json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . '</script>' . "\n";
}
add_action('wp_footer', 'pdfviewer_global_product_schema_fallback', 5);

/**
 * Add Documentation Page Schema (TechArticle)
 */
function pdfviewer_documentation_schema() {
    if (!is_page('documentation')) {
        return;
    }

    $schema = array(
        '@context'         => 'https://schema.org',
        '@type'            => 'TechArticle',
        'headline'         => 'PDF Embed & SEO Optimize Documentation',
        'description'      => 'Complete documentation for installing and configuring PDF Embed & SEO Optimize plugin for WordPress and Drupal.',
        'author'           => array(
            '@type' => 'Organization',
            'name'  => 'Dross:Media',
            'url'   => 'https://dross.net',
        ),
        'publisher'        => array(
            '@type' => 'Organization',
            'name'  => 'Dross:Media',
            'logo'  => array(
                '@type' => 'ImageObject',
                'url'   => PDFVIEWER_THEME_URI . '/assets/images/logo.png',
            ),
        ),
        'mainEntityOfPage' => array(
            '@type' => 'WebPage',
            '@id'   => PDFVIEWER_VANITY_DOMAIN . '/documentation/',
        ),
        'about'            => array(
            '@type'           => 'SoftwareApplication',
            'name'            => 'PDF Embed & SEO Optimize',
            'applicationCategory' => 'WebApplication',
            'aggregateRating' => array(
                '@type'       => 'AggregateRating',
                'ratingValue' => '4.9',
                'ratingCount' => '482',
                'bestRating'  => '5',
                'worstRating' => '1',
            ),
        ),
        'proficiencyLevel' => 'Beginner',
        'dependencies'     => 'WordPress 6.0+ or Drupal 9+',
    );

    echo '<script type="application/ld+json">' . wp_json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . '</script>' . "\n";
}
add_action('wp_head', 'pdfviewer_documentation_schema', 20);

/**
 * Add Examples Page Schema (ItemList with CollectionPage)
 */
function pdfviewer_examples_schema() {
    if (!is_page('examples')) {
        return;
    }

    $examples = array(
        array(
            'name'        => 'In-Page PDF with Download & Print',
            'description' => 'PDF embedded directly in page with download and print buttons enabled.',
            'url'         => PDFVIEWER_VANITY_DOMAIN . '/pdf/example-1/',
        ),
        array(
            'name'        => 'In-Page PDF (View Only)',
            'description' => 'PDF embedded in page without download or print options for secure viewing.',
            'url'         => PDFVIEWER_VANITY_DOMAIN . '/pdf/example-2/',
        ),
        array(
            'name'        => 'Standalone PDF with Download & Print',
            'description' => 'PDF opens in new tab with full download and print capabilities.',
            'url'         => PDFVIEWER_VANITY_DOMAIN . '/pdf/example-3/',
        ),
        array(
            'name'        => 'Standalone PDF (View Only)',
            'description' => 'PDF opens in new tab without download or print options.',
            'url'         => PDFVIEWER_VANITY_DOMAIN . '/pdf/example-4/',
        ),
        array(
            'name'        => 'Password Protected In-Page PDF',
            'description' => 'PDF embedded in page requiring password authentication.',
            'url'         => PDFVIEWER_VANITY_DOMAIN . '/pdf/example-5/',
        ),
        array(
            'name'        => 'Password Protected Standalone PDF',
            'description' => 'PDF opens in new tab requiring password authentication.',
            'url'         => PDFVIEWER_VANITY_DOMAIN . '/pdf/example-6/',
        ),
    );

    $list_items = array();
    foreach ($examples as $index => $example) {
        $list_items[] = array(
            '@type'    => 'ListItem',
            'position' => $index + 1,
            'name'     => $example['name'],
            'url'      => $example['url'],
        );
    }

    $schema = array(
        '@context'        => 'https://schema.org',
        '@type'           => 'CollectionPage',
        'name'            => 'PDF Embed Examples - Live Demos',
        'description'     => 'Interactive examples demonstrating PDF Embed & SEO Optimize features including in-page embedding, standalone viewing, and password protection.',
        'url'             => pdfviewer_schema_urls('/examples/'),
        'mainEntity'      => array(
            '@type'           => 'ItemList',
            'name'            => 'PDF Embedding Examples',
            'numberOfItems'   => count($examples),
            'itemListElement' => $list_items,
        ),
        'about'           => array(
            '@type'           => 'SoftwareApplication',
            'name'            => 'PDF Embed & SEO Optimize',
            'aggregateRating' => array(
                '@type'       => 'AggregateRating',
                'ratingValue' => '4.9',
                'ratingCount' => '482',
                'bestRating'  => '5',
                'worstRating' => '1',
            ),
        ),
    );

    echo '<script type="application/ld+json">' . wp_json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . '</script>' . "\n";
}
add_action('wp_head', 'pdfviewer_examples_schema', 20);

/**
 * Add Changelog Page Schema (ItemList with SoftwareApplication releases)
 */
function pdfviewer_changelog_schema() {
    if (!is_page('changelog')) {
        return;
    }

    $schema = array(
        '@context'    => 'https://schema.org',
        '@type'       => 'WebPage',
        'name'        => 'PDF Embed & SEO Optimize Changelog',
        'description' => 'Version history and release notes for PDF Embed & SEO Optimize plugin. Track new features, improvements, and bug fixes.',
        'url'         => pdfviewer_schema_urls('/changelog/'),
        'mainEntity'  => array(
            '@type'               => 'SoftwareApplication',
            'name'                => 'PDF Embed & SEO Optimize',
            'applicationCategory' => 'WebApplication',
            'operatingSystem'     => 'WordPress, Drupal',
            'softwareVersion'     => '2.2.82',
            'releaseNotes'        => PDFVIEWER_VANITY_DOMAIN . '/changelog/',
            'downloadUrl'         => 'https://wordpress.org/plugins/pdf-embed-seo-optimize',
            'aggregateRating'     => array(
                '@type'       => 'AggregateRating',
                'ratingValue' => '4.9',
                'ratingCount' => '482',
                'bestRating'  => '5',
                'worstRating' => '1',
            ),
        ),
    );

    echo '<script type="application/ld+json">' . wp_json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . '</script>' . "\n";
}
add_action('wp_head', 'pdfviewer_changelog_schema', 20);

/**
 * Add Pro Page Schema (Product with offers and features)
 */
function pdfviewer_pro_page_schema() {
    if (!is_page('pro')) {
        return;
    }

    $schema = array(
        '@context'    => 'https://schema.org',
        '@type'       => 'Product',
        'name'        => 'PDF Embed & SEO Optimize Pro',
        'description' => 'Premium WordPress plugin with advanced PDF analytics, password protection, reading progress tracking, and custom branding.',
        'brand'       => array(
            '@type' => 'Brand',
            'name'  => 'Dross:Media',
        ),
        'image'       => array(
            PDFVIEWER_THEME_URI . '/assets/images/og-image.jpg',
            PDFVIEWER_THEME_URI . '/assets/images/pwa-512x512.png',
        ),
        'url'         => pdfviewer_schema_urls('/pro/'),
        'sku'         => 'PDF-EMBED-PRO',
        'mpn'         => 'PDFESO-PRO-2024',
        'category'    => 'WordPress Plugins',
        'offers'      => array(
            array(
                '@type'           => 'Offer',
                'name'            => 'Pro 1 Site',
                'price'           => '49',
                'priceCurrency'   => 'EUR',
                'availability'    => 'https://schema.org/InStock',
                'priceValidUntil' => date('Y-m-d', strtotime('+1 year')),
                'url'             => pdfviewer_schema_urls('/pro/'),
            ),
            array(
                '@type'           => 'Offer',
                'name'            => 'Pro 5 Sites',
                'price'           => '99',
                'priceCurrency'   => 'EUR',
                'availability'    => 'https://schema.org/InStock',
                'priceValidUntil' => date('Y-m-d', strtotime('+1 year')),
                'url'             => pdfviewer_schema_urls('/pro/'),
            ),
            array(
                '@type'           => 'Offer',
                'name'            => 'Pro Unlimited',
                'price'           => '199',
                'priceCurrency'   => 'EUR',
                'availability'    => 'https://schema.org/InStock',
                'priceValidUntil' => date('Y-m-d', strtotime('+1 year')),
                'url'             => pdfviewer_schema_urls('/pro/'),
            ),
            array(
                '@type'           => 'Offer',
                'name'            => 'Pro Lifetime',
                'price'           => '399',
                'priceCurrency'   => 'EUR',
                'availability'    => 'https://schema.org/InStock',
                'priceValidUntil' => date('Y-m-d', strtotime('+1 year')),
                'url'             => pdfviewer_schema_urls('/pro/'),
            ),
        ),
        'aggregateRating' => array(
            '@type'       => 'AggregateRating',
            'ratingValue' => '4.8',
            'ratingCount' => '487',
            'bestRating'  => '5',
            'worstRating' => '1',
        ),
        'additionalProperty' => array(
            array(
                '@type' => 'PropertyValue',
                'name'  => 'PDF Analytics',
                'value' => 'Track views, downloads, and reading progress',
            ),
            array(
                '@type' => 'PropertyValue',
                'name'  => 'Password Protection',
                'value' => 'Secure PDFs with password authentication',
            ),
            array(
                '@type' => 'PropertyValue',
                'name'  => 'Custom Branding',
                'value' => 'White-label PDF viewer with your logo',
            ),
            array(
                '@type' => 'PropertyValue',
                'name'  => 'Expiring Links',
                'value' => 'Time-limited access to PDF documents',
            ),
        ),
    );

    echo '<script type="application/ld+json">' . wp_json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . '</script>' . "\n";
}
add_action('wp_head', 'pdfviewer_pro_page_schema', 20);

/**
 * Add PDF Single Page Schema (DigitalDocument)
 * For /pdf/example-x/ pages generated by the plugin
 */
function pdfviewer_pdf_page_schema() {
    // Check if we're on a PDF single page
    $request_uri = isset($_SERVER['REQUEST_URI']) ? sanitize_text_field(wp_unslash($_SERVER['REQUEST_URI'])) : '';

    if (!preg_match('/^\/pdf\/[^\/]+\/?$/', $request_uri)) {
        return;
    }

    // Get the PDF title from the page
    $pdf_title = get_the_title() ?: 'PDF Document';
    $pdf_url = PDFVIEWER_VANITY_DOMAIN . $request_uri;

    $schema = array(
        '@context'        => 'https://schema.org',
        '@type'           => 'DigitalDocument',
        'name'            => $pdf_title,
        'description'     => 'SEO-optimized PDF document embedded with PDF Embed & SEO Optimize plugin.',
        'url'             => array($pdf_url, PDFVIEWER_VANITY_DOMAIN . $request_uri),
        'encodingFormat'  => 'application/pdf',
        'provider'        => array(
            '@type' => 'Organization',
            'name'  => get_bloginfo('name'),
            'url'   => pdfviewer_schema_urls('/'),
        ),
        'isPartOf'        => array(
            '@type'           => 'SoftwareApplication',
            'name'            => 'PDF Embed & SEO Optimize',
            'applicationCategory' => 'WebApplication',
            'aggregateRating' => array(
                '@type'       => 'AggregateRating',
                'ratingValue' => '4.9',
                'ratingCount' => '482',
                'bestRating'  => '5',
                'worstRating' => '1',
            ),
        ),
    );

    echo '<script type="application/ld+json">' . wp_json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . '</script>' . "\n";
}
add_action('wp_head', 'pdfviewer_pdf_page_schema', 20);

/**
 * Add PDF Archive/Grid Page Schema (CollectionPage)
 * For /pdf/ and /pdf-grid/ pages
 */
function pdfviewer_pdf_archive_schema() {
    $request_uri = isset($_SERVER['REQUEST_URI']) ? sanitize_text_field(wp_unslash($_SERVER['REQUEST_URI'])) : '';

    // Only on /pdf/ or /pdf-grid/ archive pages
    if ($request_uri !== '/pdf/' && $request_uri !== '/pdf-grid/') {
        return;
    }

    $is_grid = ($request_uri === '/pdf-grid/');

    $schema = array(
        '@context'    => 'https://schema.org',
        '@type'       => 'CollectionPage',
        'name'        => $is_grid ? 'PDF Gallery Grid View' : 'PDF Document Archive',
        'description' => $is_grid
            ? 'Browse all PDF documents in a visual grid layout with thumbnails.'
            : 'Browse all SEO-optimized PDF documents on this site.',
        'url'         => pdfviewer_schema_urls($request_uri),
        'mainEntity'  => array(
            '@type'           => 'SoftwareApplication',
            'name'            => 'PDF Embed & SEO Optimize',
            'applicationCategory' => 'WebApplication',
            'aggregateRating' => array(
                '@type'       => 'AggregateRating',
                'ratingValue' => '4.9',
                'ratingCount' => '482',
                'bestRating'  => '5',
                'worstRating' => '1',
            ),
        ),
    );

    echo '<script type="application/ld+json">' . wp_json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . '</script>' . "\n";
}
add_action('wp_head', 'pdfviewer_pdf_archive_schema', 20);
