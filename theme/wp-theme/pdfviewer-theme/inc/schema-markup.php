<?php
/**
 * JSON-LD Schema Markup (Consolidated @graph)
 *
 * Outputs a single JSON-LD block with all structured data merged into one @graph.
 * Schemas: Organization, WebSite, WebPage, Product, SoftwareApplication,
 *          SiteNavigationElement, CollectionPage, FAQPage, Article.
 *
 * AggregateRating is present on every page via both Product and SoftwareApplication.
 *
 * @package PDFViewer
 * @since 2.2.95
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Vanity domain for dual-domain schema markup.
 */
if (!defined('PDFVIEWER_VANITY_DOMAIN')) {
    define('PDFVIEWER_VANITY_DOMAIN', 'https://pdfviewermodule.com');
}

/**
 * Helper: Build a canonical URL for schema markup.
 *
 * @param string $path The path to append (e.g. '/pro/').
 * @return string Canonical URL using the vanity domain.
 */
function pdfviewer_schema_urls($path = '/') {
    return PDFVIEWER_VANITY_DOMAIN . $path;
}

/**
 * Get the shared AggregateRating data.
 *
 * Centralized to ensure consistent rating values across Product
 * and SoftwareApplication schemas on every page.
 *
 * @return array Schema.org AggregateRating data.
 */
function pdfviewer_schema_aggregate_rating() {
    return array(
        '@type'       => 'AggregateRating',
        'ratingValue' => '4.8',
        'ratingCount' => '487',
        'bestRating'  => '5',
        'worstRating' => '1',
    );
}

/**
 * Output the consolidated JSON-LD @graph schema.
 *
 * All schema types are merged into a single script tag for clean,
 * non-redundant structured data output. Uses wp_json_encode() which
 * is the WordPress-approved method for safe JSON output in script tags.
 */
function pdfviewer_consolidated_schema() {
    // Cache blog info once for all schema functions.
    $site_name = get_bloginfo('name');
    $site_description = get_bloginfo('description');

    $graph = array();

    // 1. Organization (all pages)
    $graph[] = pdfviewer_schema_organization();

    // 2. WebSite (all pages)
    $graph[] = pdfviewer_schema_website($site_name);

    // 3. WebPage (all pages)
    $graph[] = pdfviewer_schema_webpage($site_name, $site_description);

    // 4. Product with AggregateRating (all pages)
    $graph[] = pdfviewer_schema_product();

    // 5. SiteNavigationElement (all pages)
    $graph = array_merge($graph, pdfviewer_schema_site_navigation());

    // 6. SoftwareApplication with AggregateRating (all pages)
    $graph[] = pdfviewer_schema_software_application();

    // 7. CollectionPage (examples, /pdf/, /pdf-grid/)
    $collection = pdfviewer_schema_collection_page();
    if ($collection) {
        $graph[] = $collection;
    }

    // 8. FAQPage (pages with FAQ content)
    $faq = pdfviewer_schema_faq_page();
    if ($faq) {
        $graph[] = $faq;
    }

    // 9. Article (front page highlight/purpose text)
    $article = pdfviewer_schema_article();
    if ($article) {
        $graph[] = $article;
    }

    $schema = array(
        '@context' => 'https://schema.org',
        '@graph'   => $graph,
    );

    // wp_json_encode is the WordPress-approved method for JSON output in script tags.
    // It handles all necessary encoding to prevent injection.
    echo '<script type="application/ld+json">' . wp_json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . '</script>' . "\n";
}
add_action('wp_head', 'pdfviewer_consolidated_schema', 15);

/**
 * 1. Organization schema.
 *
 * @return array Schema.org Organization data.
 */
function pdfviewer_schema_organization() {
    return array(
        '@type'  => 'Organization',
        '@id'    => pdfviewer_schema_urls('/#organization'),
        'name'   => 'Dross:Media',
        'url'    => pdfviewer_schema_urls('/'),
        'sameAs' => array(
            PDFVIEWER_VANITY_DOMAIN,
            'https://wordpress.org/plugins/pdf-embed-seo-optimize',
            'https://www.drupal.org/project/pdf-embed-seo-optimize',
        ),
        'logo'   => array(
            '@type'      => 'ImageObject',
            '@id'        => pdfviewer_schema_urls('/#logo'),
            'url'        => PDFVIEWER_THEME_URI . '/assets/images/logo.png',
            'contentUrl' => PDFVIEWER_THEME_URI . '/assets/images/logo.png',
            'caption'    => 'Dross:Media',
        ),
    );
}

/**
 * 2. WebSite schema with SearchAction.
 *
 * @param string $site_name Cached site name from get_bloginfo().
 * @return array Schema.org WebSite data.
 */
function pdfviewer_schema_website($site_name) {
    return array(
        '@type'           => 'WebSite',
        '@id'             => pdfviewer_schema_urls('/#website'),
        'name'            => $site_name,
        'url'             => pdfviewer_schema_urls('/'),
        'publisher'       => array(
            '@id' => pdfviewer_schema_urls('/#organization'),
        ),
        'potentialAction' => array(
            '@type'       => 'SearchAction',
            'target'      => array(
                '@type'       => 'EntryPoint',
                'urlTemplate' => PDFVIEWER_VANITY_DOMAIN . '/?s={search_term_string}',
            ),
            'query-input' => 'required name=search_term_string',
        ),
    );
}

/**
 * 3. WebPage schema for the current page.
 *
 * @param string $site_name        Cached site name from get_bloginfo().
 * @param string $site_description Cached site description from get_bloginfo().
 * @return array Schema.org WebPage data.
 */
function pdfviewer_schema_webpage($site_name, $site_description) {
    $page_slug = '';
    $page_title = $site_name;
    $page_description = $site_description;
    $page_url = pdfviewer_schema_urls('/');

    if (is_front_page()) {
        $page_title = $site_name . ' - ' . $site_description;
        $page_description = 'Display PDFs beautifully with SEO-optimized pages. Schema.org markup, AI-ready structured data, and analytics for WordPress, Drupal, and React/Next.js.';
    } elseif (is_page()) {
        global $post;
        if ($post) {
            $page_slug = $post->post_name;
        }
        $page_title = get_the_title();
        $page_url = pdfviewer_schema_urls('/' . $page_slug . '/');

        $custom_descriptions = array(
            'pro'                  => 'Premium WordPress plugin with advanced PDF analytics, password protection, reading progress tracking, and custom branding.',
            'documentation'        => 'Complete documentation for installing and configuring PDF Embed & SEO Optimize plugin for WordPress and Drupal.',
            'changelog'            => 'Version history and release notes for PDF Embed & SEO Optimize plugin. Track new features, improvements, and bug fixes.',
            'examples'             => 'Interactive examples demonstrating PDF Embed & SEO Optimize features including in-page embedding, standalone viewing, and password protection.',
            'wordpress-pdf-viewer' => 'Free WordPress plugin to embed PDFs with SEO-optimized pages, clean URLs, and structured data.',
            'drupal-pdf-viewer'    => 'Free Drupal module to embed PDFs with SEO-optimized pages, clean URLs, and structured data.',
            'nextjs-pdf-viewer'    => 'Free React/Next.js components to embed PDFs with SEO optimization and TypeScript support.',
            'enterprise'           => 'Enterprise-grade PDF management with advanced analytics, 2FA, audit logs, and compliance features.',
        );

        if (isset($custom_descriptions[$page_slug])) {
            $page_description = $custom_descriptions[$page_slug];
        } else {
            $page_description = get_the_excerpt() ?: wp_trim_words(wp_strip_all_tags(get_the_content()), 30);
        }
    }

    return array(
        '@type'        => 'WebPage',
        '@id'          => $page_url . '#webpage',
        'name'         => $page_title,
        'description'  => $page_description,
        'url'          => $page_url,
        'isPartOf'     => array(
            '@id' => pdfviewer_schema_urls('/#website'),
        ),
        'about'        => array(
            '@id' => pdfviewer_schema_urls('/#product'),
        ),
        'dateModified' => get_the_modified_date('c') ?: gmdate('c'),
    );
}

/**
 * 4. Product schema with AggregateRating.
 *
 * Single consolidated product present on all pages to ensure
 * AggregateRating is always visible to search engines.
 *
 * @return array Schema.org Product data with AggregateRating.
 */
function pdfviewer_schema_product() {
    $price_valid_until = gmdate('Y-m-d', strtotime('+1 year'));

    return array(
        '@type'           => 'Product',
        '@id'             => pdfviewer_schema_urls('/#product'),
        'name'            => 'PDF Embed & SEO Optimize',
        'description'     => 'Display PDFs beautifully with SEO-optimized pages. Schema.org markup, AI-ready structured data, and analytics for WordPress, Drupal, and React/Next.js.',
        'brand'           => array(
            '@type' => 'Brand',
            'name'  => 'Dross:Media',
        ),
        'image'           => array(
            PDFVIEWER_THEME_URI . '/assets/images/og-image.jpg',
            PDFVIEWER_THEME_URI . '/assets/images/pwa-512x512.png',
        ),
        'url'             => pdfviewer_schema_urls('/'),
        'sku'             => 'PDF-EMBED-SEO',
        'category'        => 'Web Development Plugins',
        'offers'          => array(
            '@type'         => 'AggregateOffer',
            'lowPrice'      => '0',
            'highPrice'     => '399',
            'priceCurrency' => 'EUR',
            'availability'  => 'https://schema.org/InStock',
            'offerCount'    => '5',
            'offers'        => array(
                array(
                    '@type'         => 'Offer',
                    'name'          => 'Free',
                    'price'         => '0',
                    'priceCurrency' => 'EUR',
                    'availability'  => 'https://schema.org/InStock',
                    'url'           => pdfviewer_schema_urls('/wordpress-pdf-viewer/'),
                ),
                array(
                    '@type'           => 'Offer',
                    'name'            => 'Pro 1 Site',
                    'price'           => '49',
                    'priceCurrency'   => 'EUR',
                    'availability'    => 'https://schema.org/InStock',
                    'priceValidUntil' => $price_valid_until,
                    'url'             => pdfviewer_schema_urls('/pro/'),
                ),
                array(
                    '@type'           => 'Offer',
                    'name'            => 'Pro 5 Sites',
                    'price'           => '99',
                    'priceCurrency'   => 'EUR',
                    'availability'    => 'https://schema.org/InStock',
                    'priceValidUntil' => $price_valid_until,
                    'url'             => pdfviewer_schema_urls('/pro/'),
                ),
                array(
                    '@type'           => 'Offer',
                    'name'            => 'Pro Unlimited',
                    'price'           => '199',
                    'priceCurrency'   => 'EUR',
                    'availability'    => 'https://schema.org/InStock',
                    'priceValidUntil' => $price_valid_until,
                    'url'             => pdfviewer_schema_urls('/pro/'),
                ),
                array(
                    '@type'           => 'Offer',
                    'name'            => 'Pro Lifetime',
                    'price'           => '399',
                    'priceCurrency'   => 'EUR',
                    'availability'    => 'https://schema.org/InStock',
                    'priceValidUntil' => $price_valid_until,
                    'url'             => pdfviewer_schema_urls('/pro/'),
                ),
            ),
        ),
        'aggregateRating' => pdfviewer_schema_aggregate_rating(),
    );
}

/**
 * 5. SiteNavigationElement schema for all website links/pages.
 *
 * @return array[] Array of Schema.org SiteNavigationElement items.
 */
function pdfviewer_schema_site_navigation() {
    $nav_links = array(
        array('name' => 'Home',                      'path' => '/'),
        array('name' => 'Features',                   'path' => '/#features'),
        array('name' => 'Examples',                   'path' => '/examples/'),
        array('name' => 'Pro',                        'path' => '/pro/'),
        array('name' => 'Documentation',              'path' => '/documentation/'),
        array('name' => 'Changelog',                  'path' => '/changelog/'),
        array('name' => 'WordPress PDF Viewer',       'path' => '/wordpress-pdf-viewer/'),
        array('name' => 'Drupal PDF Viewer',          'path' => '/drupal-pdf-viewer/'),
        array('name' => 'React / Next.js PDF Viewer', 'path' => '/nextjs-pdf-viewer/'),
        array('name' => 'Plugin Comparison',          'path' => '/compare/'),
        array('name' => 'Enterprise',                 'path' => '/enterprise/'),
        array('name' => 'PDF Archive',                'path' => '/pdf/'),
        array('name' => 'PDF Gallery',                'path' => '/pdf-grid/'),
        array('name' => 'Contact', 'path' => null, 'url' => 'https://dross.net/contact/?topic=pdfviewer'),
    );

    $items = array();
    foreach ($nav_links as $link) {
        $url = isset($link['url']) ? $link['url'] : pdfviewer_schema_urls($link['path']);
        $items[] = array(
            '@type' => 'SiteNavigationElement',
            '@id'   => $url,
            'name'  => $link['name'],
            'url'   => $url,
        );
    }

    return $items;
}

/**
 * 6. SoftwareApplication schema with AggregateRating (all pages).
 *
 * Core fields appear on every page to ensure AggregateRating visibility.
 * Extended details (featureList, screenshot) added on platform and pro pages.
 *
 * @return array Schema.org SoftwareApplication data with AggregateRating.
 */
function pdfviewer_schema_software_application() {
    $schema = array(
        '@type'               => 'SoftwareApplication',
        '@id'                 => pdfviewer_schema_urls('/#software'),
        'name'                => 'PDF Embed & SEO Optimize',
        'applicationCategory' => 'WebApplication',
        'operatingSystem'     => 'WordPress, Drupal, React, Next.js',
        'url'                 => pdfviewer_schema_urls('/'),
        'softwareVersion'     => '1.3.0',
        'downloadUrl'         => 'https://wordpress.org/plugins/pdf-embed-seo-optimize',
        'offers'              => array(
            '@type'         => 'Offer',
            'price'         => '0',
            'priceCurrency' => 'EUR',
            'availability'  => 'https://schema.org/InStock',
        ),
        'aggregateRating'     => pdfviewer_schema_aggregate_rating(),
    );

    // Extended details on platform and pro pages.
    $plugin_pages = array('wordpress-pdf-viewer', 'drupal-pdf-viewer', 'nextjs-pdf-viewer', 'pro');
    if (is_page($plugin_pages)) {
        $schema['featureList'] = array(
            'SEO-optimized PDF embedding',
            'Clean URLs for PDFs (/pdf/your-document/)',
            'Schema.org DigitalDocument markup',
            'Automatic XML sitemap',
            'Open Graph and Twitter Cards',
            'Responsive PDF viewer with Mozilla PDF.js',
            'Print and download control per document',
            'View analytics and tracking',
            'Password protection',
            'Reading progress tracking',
        );
        $schema['screenshot'] = array(
            PDFVIEWER_THEME_URI . '/assets/images/og-image.jpg',
        );

        if (is_page('nextjs-pdf-viewer')) {
            $schema['programmingLanguage'] = 'TypeScript';
            $schema['runtimePlatform']     = 'React, Next.js';
        }
    }

    return $schema;
}

/**
 * 7. CollectionPage schema (examples page, PDF archive, PDF grid).
 *
 * @return array|null Schema.org CollectionPage data, or null if not applicable.
 */
function pdfviewer_schema_collection_page() {
    // Examples page.
    if (is_page('examples')) {
        $examples = array(
            array('name' => 'In-Page PDF with Download & Print',   'description' => 'PDF embedded directly in page with download and print buttons enabled.',    'url' => PDFVIEWER_VANITY_DOMAIN . '/pdf/example-1/'),
            array('name' => 'In-Page PDF (View Only)',              'description' => 'PDF embedded in page without download or print options for secure viewing.', 'url' => PDFVIEWER_VANITY_DOMAIN . '/pdf/example-2/'),
            array('name' => 'Standalone PDF with Download & Print', 'description' => 'PDF opens in new tab with full download and print capabilities.',           'url' => PDFVIEWER_VANITY_DOMAIN . '/pdf/example-3/'),
            array('name' => 'Standalone PDF (View Only)',           'description' => 'PDF opens in new tab without download or print options.',                    'url' => PDFVIEWER_VANITY_DOMAIN . '/pdf/example-4/'),
            array('name' => 'Password Protected In-Page PDF',      'description' => 'PDF embedded in page requiring password authentication.',                    'url' => PDFVIEWER_VANITY_DOMAIN . '/pdf/example-5/'),
            array('name' => 'Password Protected Standalone PDF',   'description' => 'PDF opens in new tab requiring password authentication.',                    'url' => PDFVIEWER_VANITY_DOMAIN . '/pdf/example-6/'),
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

        return array(
            '@type'       => 'CollectionPage',
            '@id'         => pdfviewer_schema_urls('/examples/#collectionpage'),
            'name'        => 'PDF Embed Examples - Live Demos',
            'description' => 'Interactive examples demonstrating PDF Embed & SEO Optimize features including in-page embedding, standalone viewing, and password protection.',
            'url'         => pdfviewer_schema_urls('/examples/'),
            'isPartOf'    => array(
                '@id' => pdfviewer_schema_urls('/#website'),
            ),
            'mainEntity'  => array(
                '@type'           => 'ItemList',
                'name'            => 'PDF Embedding Examples',
                'numberOfItems'   => count($examples),
                'itemListElement' => $list_items,
            ),
        );
    }

    // PDF archive or grid pages (plugin-generated, detected via request URI).
    $request_uri = isset($_SERVER['REQUEST_URI'])
        ? sanitize_text_field(wp_unslash($_SERVER['REQUEST_URI']))
        : '';

    if ('/pdf/' === $request_uri || '/pdf-grid/' === $request_uri) {
        $is_grid = ('/pdf-grid/' === $request_uri);

        return array(
            '@type'       => 'CollectionPage',
            '@id'         => pdfviewer_schema_urls($request_uri) . '#collectionpage',
            'name'        => $is_grid ? 'PDF Gallery Grid View' : 'PDF Document Archive',
            'description' => $is_grid
                ? 'Browse all PDF documents in a visual grid layout with thumbnails.'
                : 'Browse all SEO-optimized PDF documents on this site.',
            'url'         => pdfviewer_schema_urls($request_uri),
            'isPartOf'    => array(
                '@id' => pdfviewer_schema_urls('/#website'),
            ),
        );
    }

    return null;
}

/**
 * 8. FAQPage schema (auto-detected from page content).
 *
 * Scans the current page content for accordion-trigger buttons or
 * details/summary elements and generates FAQ structured data.
 *
 * @return array|null Schema.org FAQPage data, or null if no FAQ content found.
 */
function pdfviewer_schema_faq_page() {
    if (!is_singular()) {
        return null;
    }

    $post = get_post();
    if (!$post) {
        return null;
    }

    $content = $post->post_content;

    // Early exit if no FAQ-related content markers.
    if (strpos($content, 'faq') === false && strpos($content, 'accordion') === false) {
        return null;
    }

    $faq_items = array();

    // Match accordion-style FAQs.
    if (preg_match_all('/<button[^>]*class="[^"]*accordion-trigger[^"]*"[^>]*>([^<]+)<\/button>.*?<div[^>]*class="[^"]*accordion-content[^"]*"[^>]*>(.*?)<\/div>/is', $content, $matches, PREG_SET_ORDER)) {
        foreach ($matches as $match) {
            $question = wp_strip_all_tags(trim($match[1]));
            $answer   = wp_strip_all_tags(trim($match[2]));

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

    // Match details/summary elements.
    if (preg_match_all('/<summary[^>]*>(.*?)<\/summary>.*?<div[^>]*>(.*?)<\/div>/is', $content, $matches, PREG_SET_ORDER)) {
        foreach ($matches as $match) {
            $question = wp_strip_all_tags(trim($match[1]));
            $answer   = wp_strip_all_tags(trim($match[2]));

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
        return null;
    }

    return array(
        '@type'      => 'FAQPage',
        '@id'        => pdfviewer_schema_urls('/' . $post->post_name . '/#faqpage'),
        'mainEntity' => $faq_items,
    );
}

/**
 * 9. Article schema for the front page highlight/purpose text.
 *
 * Describes the product's purpose using the hero and problem/solution
 * content from the front page as structured Article data.
 *
 * @return array|null Schema.org Article data, or null if not front page.
 */
function pdfviewer_schema_article() {
    if (!is_front_page()) {
        return null;
    }

    return array(
        '@type'            => 'Article',
        '@id'              => pdfviewer_schema_urls('/#article'),
        'headline'         => 'Share PDFs That Get Found',
        'description'      => 'Help customers find your documents on Google and AI tools like ChatGPT. Display PDFs beautifully on any device and track who\'s reading them.',
        'articleBody'      => 'Right now, uploading PDFs to WordPress means losing control over how customers find and experience your important documents. Direct PDF links expose your file structure, offer no SEO value, and provide no analytics. PDF Embed & SEO Optimize turns your documents into professional web pages that customers can find, read, and share easily with clean branded URLs, Schema.org markup, and full view tracking.',
        'author'           => array(
            '@id' => pdfviewer_schema_urls('/#organization'),
        ),
        'publisher'        => array(
            '@id' => pdfviewer_schema_urls('/#organization'),
        ),
        'mainEntityOfPage' => array(
            '@id' => pdfviewer_schema_urls('/#webpage'),
        ),
        'image'            => PDFVIEWER_THEME_URI . '/assets/images/og-image.jpg',
        'datePublished'    => '2025-01-01T00:00:00+00:00',
        'dateModified'     => get_the_modified_date('c') ?: gmdate('c'),
    );
}
