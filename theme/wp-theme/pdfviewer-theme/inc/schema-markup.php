<?php
/**
 * JSON-LD Schema Markup (Consolidated @graph)
 *
 * Outputs a single JSON-LD block with all structured data merged into one @graph.
 * Schemas: Organization, WebSite, WebPage, Product, SoftwareApplication,
 *          SiteNavigationElement, CollectionPage, FAQPage, Article.
 *
 * @package PDFViewer
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
 * @param string $path The path to append.
 * @return string Canonical URL using the vanity domain.
 */
function pdfviewer_schema_urls($path = '/') {
    return PDFVIEWER_VANITY_DOMAIN . $path;
}

/**
 * Output the consolidated JSON-LD @graph schema.
 * All schema types are merged into a single script tag.
 */
function pdfviewer_consolidated_schema() {
    $graph = array();

    // 1. Organization (all pages)
    $graph[] = pdfviewer_schema_organization();

    // 2. WebSite (all pages)
    $graph[] = pdfviewer_schema_website();

    // 3. WebPage (all pages)
    $graph[] = pdfviewer_schema_webpage();

    // 4. Product with AggregateRating (all pages)
    $graph[] = pdfviewer_schema_product();

    // 5. SiteNavigationElement (all pages)
    $nav_items = pdfviewer_schema_site_navigation();
    foreach ($nav_items as $nav_item) {
        $graph[] = $nav_item;
    }

    // 6. SoftwareApplication (platform pages + pro)
    $software = pdfviewer_schema_software_application();
    if ($software) {
        $graph[] = $software;
    }

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

    echo '<script type="application/ld+json">' . wp_json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . '</script>' . "\n";
}
add_action('wp_head', 'pdfviewer_consolidated_schema', 15);

/**
 * 1. Organization schema.
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
 */
function pdfviewer_schema_website() {
    return array(
        '@type'           => 'WebSite',
        '@id'             => pdfviewer_schema_urls('/#website'),
        'name'            => get_bloginfo('name'),
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
 */
function pdfviewer_schema_webpage() {
    $page_slug = '';
    $page_title = get_bloginfo('name');
    $page_description = get_bloginfo('description');
    $page_url = pdfviewer_schema_urls('/');

    if (is_front_page()) {
        $page_title = get_bloginfo('name') . ' - ' . get_bloginfo('description');
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
        '@type'       => 'WebPage',
        '@id'         => $page_url . '#webpage',
        'name'        => $page_title,
        'description' => $page_description,
        'url'         => $page_url,
        'isPartOf'    => array(
            '@id' => pdfviewer_schema_urls('/#website'),
        ),
        'about'       => array(
            '@id' => pdfviewer_schema_urls('/#product'),
        ),
        'dateModified' => get_the_modified_date('c') ?: gmdate('c'),
    );
}

/**
 * 4. Product schema with AggregateRating.
 * Single consolidated product across all pages.
 */
function pdfviewer_schema_product() {
    $product = array(
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
                    '@type'           => 'Offer',
                    'name'            => 'Free',
                    'price'           => '0',
                    'priceCurrency'   => 'EUR',
                    'availability'    => 'https://schema.org/InStock',
                    'url'             => pdfviewer_schema_urls('/wordpress-pdf-viewer/'),
                ),
                array(
                    '@type'           => 'Offer',
                    'name'            => 'Pro 1 Site',
                    'price'           => '49',
                    'priceCurrency'   => 'EUR',
                    'availability'    => 'https://schema.org/InStock',
                    'priceValidUntil' => gmdate('Y-m-d', strtotime('+1 year')),
                    'url'             => pdfviewer_schema_urls('/pro/'),
                ),
                array(
                    '@type'           => 'Offer',
                    'name'            => 'Pro 5 Sites',
                    'price'           => '99',
                    'priceCurrency'   => 'EUR',
                    'availability'    => 'https://schema.org/InStock',
                    'priceValidUntil' => gmdate('Y-m-d', strtotime('+1 year')),
                    'url'             => pdfviewer_schema_urls('/pro/'),
                ),
                array(
                    '@type'           => 'Offer',
                    'name'            => 'Pro Unlimited',
                    'price'           => '199',
                    'priceCurrency'   => 'EUR',
                    'availability'    => 'https://schema.org/InStock',
                    'priceValidUntil' => gmdate('Y-m-d', strtotime('+1 year')),
                    'url'             => pdfviewer_schema_urls('/pro/'),
                ),
                array(
                    '@type'           => 'Offer',
                    'name'            => 'Pro Lifetime',
                    'price'           => '399',
                    'priceCurrency'   => 'EUR',
                    'availability'    => 'https://schema.org/InStock',
                    'priceValidUntil' => gmdate('Y-m-d', strtotime('+1 year')),
                    'url'             => pdfviewer_schema_urls('/pro/'),
                ),
            ),
        ),
        'aggregateRating' => array(
            '@type'       => 'AggregateRating',
            'ratingValue' => '4.8',
            'ratingCount' => '487',
            'bestRating'  => '5',
            'worstRating' => '1',
        ),
    );

    return $product;
}

/**
 * 5. SiteNavigationElement schema for all website links/pages.
 */
function pdfviewer_schema_site_navigation() {
    $nav_links = array(
        array('name' => 'Home',                     'path' => '/'),
        array('name' => 'Features',                  'path' => '/#features'),
        array('name' => 'Examples',                  'path' => '/examples/'),
        array('name' => 'Pro',                       'path' => '/pro/'),
        array('name' => 'Documentation',             'path' => '/documentation/'),
        array('name' => 'Changelog',                 'path' => '/changelog/'),
        array('name' => 'WordPress PDF Viewer',      'path' => '/wordpress-pdf-viewer/'),
        array('name' => 'Drupal PDF Viewer',         'path' => '/drupal-pdf-viewer/'),
        array('name' => 'React / Next.js PDF Viewer', 'path' => '/nextjs-pdf-viewer/'),
        array('name' => 'Plugin Comparison',         'path' => '/compare/'),
        array('name' => 'Enterprise',                'path' => '/enterprise/'),
        array('name' => 'PDF Archive',               'path' => '/pdf/'),
        array('name' => 'PDF Gallery',               'path' => '/pdf-grid/'),
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
 * 6. SoftwareApplication schema (platform pages + pro).
 */
function pdfviewer_schema_software_application() {
    $plugin_pages = array('wordpress-pdf-viewer', 'drupal-pdf-viewer', 'nextjs-pdf-viewer', 'pro');

    if (!is_page($plugin_pages)) {
        return null;
    }

    $schema = array(
        '@type'               => 'SoftwareApplication',
        '@id'                 => pdfviewer_schema_urls('/#software'),
        'name'                => 'PDF Embed & SEO Optimize',
        'applicationCategory' => 'WebApplication',
        'operatingSystem'     => 'WordPress, Drupal, React, Next.js',
        'url'                 => pdfviewer_schema_urls('/'),
        'softwareVersion'     => '1.3.0',
        'offers'              => array(
            '@type'         => 'Offer',
            'price'         => '0',
            'priceCurrency' => 'EUR',
            'availability'  => 'https://schema.org/InStock',
        ),
        'featureList'         => array(
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
        ),
        'screenshot'          => array(
            PDFVIEWER_THEME_URI . '/assets/images/og-image.jpg',
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

    // Add platform-specific details
    if (is_page('nextjs-pdf-viewer')) {
        $schema['programmingLanguage'] = 'TypeScript';
        $schema['runtimePlatform'] = 'React, Next.js';
    }

    return $schema;
}

/**
 * 7. CollectionPage schema (examples page, PDF archive, PDF grid).
 */
function pdfviewer_schema_collection_page() {
    // Examples page
    if (is_page('examples')) {
        $examples = array(
            array('name' => 'In-Page PDF with Download & Print',      'description' => 'PDF embedded directly in page with download and print buttons enabled.',        'url' => PDFVIEWER_VANITY_DOMAIN . '/pdf/example-1/'),
            array('name' => 'In-Page PDF (View Only)',                 'description' => 'PDF embedded in page without download or print options for secure viewing.',     'url' => PDFVIEWER_VANITY_DOMAIN . '/pdf/example-2/'),
            array('name' => 'Standalone PDF with Download & Print',    'description' => 'PDF opens in new tab with full download and print capabilities.',               'url' => PDFVIEWER_VANITY_DOMAIN . '/pdf/example-3/'),
            array('name' => 'Standalone PDF (View Only)',              'description' => 'PDF opens in new tab without download or print options.',                        'url' => PDFVIEWER_VANITY_DOMAIN . '/pdf/example-4/'),
            array('name' => 'Password Protected In-Page PDF',         'description' => 'PDF embedded in page requiring password authentication.',                        'url' => PDFVIEWER_VANITY_DOMAIN . '/pdf/example-5/'),
            array('name' => 'Password Protected Standalone PDF',      'description' => 'PDF opens in new tab requiring password authentication.',                        'url' => PDFVIEWER_VANITY_DOMAIN . '/pdf/example-6/'),
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

    // PDF archive or grid pages
    $request_uri = isset($_SERVER['REQUEST_URI']) ? sanitize_text_field(wp_unslash($_SERVER['REQUEST_URI'])) : '';

    if ($request_uri === '/pdf/' || $request_uri === '/pdf-grid/') {
        $is_grid = ($request_uri === '/pdf-grid/');

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

    // Check if page has FAQ content
    if (strpos($content, 'faq') === false && strpos($content, 'accordion') === false) {
        return null;
    }

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
 * Describes what the product does and why it exists.
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
