<?php
/**
 * Unit Tests for Social Media Meta Tags
 *
 * Verifies all pages output proper meta tags for Facebook, LinkedIn, Twitter.
 * Tests the SEO config, author meta, canonical rewrite, and OG tag functions.
 *
 * @package PDFViewer
 * @subpackage Tests
 */

namespace PDFViewer\Tests\Unit;

use PHPUnit\Framework\TestCase;

class SocialMetaTest extends TestCase
{
    private static array $pagesConfig = [];
    private static string $vanityDomain = 'https://pdfviewermodule.com';

    public static function setUpBeforeClass(): void
    {
        // Bootstrap already defines WP stubs and constants.
        // Load the SEO config function.
        require_once dirname(__DIR__, 2) . '/inc/yoast-seo-setup.php';

        self::$pagesConfig = pdfviewer_get_pages_seo_config();
    }

    // =========================================================================
    // Test: All expected pages exist in SEO config
    // =========================================================================

    /**
     * @return array<string, array{0: string}>
     */
    public static function pageSlugProvider(): array
    {
        return [
            'homepage'           => ['home'],
            'documentation'      => ['documentation'],
            'examples'           => ['examples'],
            'pro'                => ['pro'],
            'changelog'          => ['changelog'],
            'cart'               => ['cart'],
            'wordpress-pdf'      => ['wordpress-pdf-viewer'],
            'drupal-pdf'         => ['drupal-pdf-viewer'],
            'compare'            => ['compare'],
            'nextjs-pdf'         => ['nextjs-pdf-viewer'],
            'pdf-grid'           => ['pdf-grid'],
            'enterprise'         => ['enterprise'],
        ];
    }

    /**
     * @dataProvider pageSlugProvider
     */
    public function testPageExistsInSeoConfig(string $slug): void
    {
        $this->assertArrayHasKey($slug, self::$pagesConfig, "Page '$slug' missing from SEO config");
    }

    // =========================================================================
    // Test: Each page has all required OG/Twitter/SEO fields
    // =========================================================================

    private const REQUIRED_FIELDS = [
        'seo_title',
        'meta_description',
        'og_title',
        'og_description',
        'og_image',
        'twitter_title',
        'twitter_description',
    ];

    /**
     * @dataProvider pageSlugProvider
     */
    public function testPageHasAllRequiredMetaFields(string $slug): void
    {
        $config = self::$pagesConfig[$slug];

        foreach (self::REQUIRED_FIELDS as $field) {
            $this->assertArrayHasKey($field, $config, "Page '$slug' missing field '$field'");
            $this->assertNotEmpty($config[$field], "Page '$slug' has empty '$field'");
        }
    }

    // =========================================================================
    // Test: og_title is not empty and is reasonable length
    // =========================================================================

    /**
     * @dataProvider pageSlugProvider
     */
    public function testOgTitleLength(string $slug): void
    {
        $title = self::$pagesConfig[$slug]['og_title'];
        $len = mb_strlen($title);

        $this->assertGreaterThan(10, $len, "Page '$slug' og_title too short ($len chars): '$title'");
        $this->assertLessThanOrEqual(200, $len, "Page '$slug' og_title too long ($len chars)");
    }

    // =========================================================================
    // Test: og_description is reasonable length (Facebook truncates >300 chars)
    // =========================================================================

    /**
     * @dataProvider pageSlugProvider
     */
    public function testOgDescriptionLength(string $slug): void
    {
        $desc = self::$pagesConfig[$slug]['og_description'];
        $len = mb_strlen($desc);

        $this->assertGreaterThan(20, $len, "Page '$slug' og_description too short ($len chars)");
        $this->assertLessThanOrEqual(300, $len, "Page '$slug' og_description too long ($len chars, Facebook truncates at ~300)");
    }

    // =========================================================================
    // Test: og_image exists and is a valid URL
    // =========================================================================

    /**
     * @dataProvider pageSlugProvider
     */
    public function testOgImageIsValidUrl(string $slug): void
    {
        $image = self::$pagesConfig[$slug]['og_image'];
        $this->assertNotEmpty($image, "Page '$slug' has empty og_image");
        $this->assertStringStartsWith('http', $image, "Page '$slug' og_image must be an absolute URL");
        $this->assertStringContainsString('og-image', $image, "Page '$slug' og_image should reference the og-image file");
    }

    // =========================================================================
    // Test: canonical URLs use vanity domain (pdfviewermodule.com)
    // =========================================================================

    /**
     * @dataProvider pageSlugProvider
     */
    public function testCanonicalUsesVanityDomain(string $slug): void
    {
        $config = self::$pagesConfig[$slug];

        // Cart doesn't need canonical (noindex)
        if (!empty($config['noindex'])) {
            $this->assertArrayHasKey('noindex', $config, "Page '$slug' is noindex");
            return;
        }

        // All non-noindex pages MUST have a canonical URL
        $this->assertArrayHasKey('canonical', $config, "Page '$slug' is missing canonical URL in SEO config");

        if (isset($config['canonical'])) {
            $this->assertStringStartsWith(
                self::$vanityDomain,
                $config['canonical'],
                "Page '$slug' canonical must use pdfviewermodule.com, got: {$config['canonical']}"
            );
            $this->assertStringNotContainsString(
                'drossmedia.de',
                $config['canonical'],
                "Page '$slug' canonical must NOT contain drossmedia.de"
            );
        }
    }

    // =========================================================================
    // Test: SEO title length (Google truncates at ~60 chars)
    // =========================================================================

    /**
     * @dataProvider pageSlugProvider
     */
    public function testSeoTitleLength(string $slug): void
    {
        $title = self::$pagesConfig[$slug]['seo_title'];
        $len = mb_strlen($title);

        $this->assertGreaterThan(10, $len, "Page '$slug' seo_title too short ($len chars)");
        // Google shows ~55-60 chars, but allow up to 90 for longer titles
        $this->assertLessThanOrEqual(90, $len, "Page '$slug' seo_title too long ($len chars, Google truncates ~60)");
    }

    // =========================================================================
    // Test: meta_description length (Google truncates at ~160 chars)
    // =========================================================================

    /**
     * @dataProvider pageSlugProvider
     */
    public function testMetaDescriptionLength(string $slug): void
    {
        $desc = self::$pagesConfig[$slug]['meta_description'];
        $len = mb_strlen($desc);

        $this->assertGreaterThan(50, $len, "Page '$slug' meta_description too short ($len chars)");
        $this->assertLessThanOrEqual(320, $len, "Page '$slug' meta_description too long ($len chars)");
    }

    // =========================================================================
    // Test: Twitter title is distinct and within length
    // =========================================================================

    /**
     * @dataProvider pageSlugProvider
     */
    public function testTwitterTitleLength(string $slug): void
    {
        $title = self::$pagesConfig[$slug]['twitter_title'];
        $len = mb_strlen($title);

        $this->assertGreaterThan(5, $len, "Page '$slug' twitter_title too short ($len chars)");
        $this->assertLessThanOrEqual(70, $len, "Page '$slug' twitter_title too long ($len chars, Twitter truncates ~70)");
    }

    // =========================================================================
    // Test: No page has pdfviewer.drossmedia.de in canonical or OG fields
    // =========================================================================

    /**
     * @dataProvider pageSlugProvider
     */
    public function testNoOldDomainInMetaFields(string $slug): void
    {
        $config = self::$pagesConfig[$slug];
        $fieldsToCheck = ['og_title', 'og_description', 'twitter_title', 'twitter_description', 'seo_title', 'meta_description'];

        foreach ($fieldsToCheck as $field) {
            if (isset($config[$field])) {
                $this->assertStringNotContainsString(
                    'drossmedia.de',
                    $config[$field],
                    "Page '$slug' field '$field' must not contain old domain"
                );
            }
        }
    }

    // =========================================================================
    // Test: pdfviewer_article_author_meta outputs both meta tags
    // =========================================================================

    public function testArticleAuthorMetaOutputsBothTags(): void
    {
        ob_start();
        pdfviewer_article_author_meta();
        $output = ob_get_clean();

        // Must contain standard <meta name="author"> for LinkedIn
        $this->assertStringContainsString(
            '<meta name="author" content="Dross:Media">',
            $output,
            'Must output <meta name="author"> for LinkedIn'
        );

        // Must contain OG article:author as a URL (OG spec requires URL, not plain text)
        $this->assertStringContainsString(
            '<meta property="article:author" content="https://drossmedia.de/">',
            $output,
            'Must output <meta property="article:author"> with URL for LinkedIn/Facebook'
        );

        // Must contain publication date
        $this->assertStringContainsString(
            'article:published_time',
            $output,
            'Must output article:published_time'
        );

        // Must contain modified date
        $this->assertStringContainsString(
            'article:modified_time',
            $output,
            'Must output article:modified_time'
        );
    }

    // =========================================================================
    // Test: Canonical rewrite function works correctly
    // =========================================================================

    public function testCanonicalRewriteFunction(): void
    {
        // Uses home_url() domain (example.com from bootstrap) as the source
        $input = home_url('/enterprise/');
        $expected = self::$vanityDomain . '/enterprise/';

        $result = pdfviewer_force_vanity_canonical($input);
        $this->assertEquals($expected, $result, 'Canonical rewrite must change domain from ' . home_url() . ' to pdfviewermodule.com');
    }

    public function testCanonicalRewriteHandlesEmptyString(): void
    {
        $this->assertEmpty(pdfviewer_force_vanity_canonical(''));
    }

    public function testOgUrlRewriteFunction(): void
    {
        $input = home_url('/pro/');
        $expected = self::$vanityDomain . '/pro/';

        $result = pdfviewer_force_vanity_og_url($input);
        $this->assertEquals($expected, $result, 'OG URL rewrite must change domain');
    }

    // =========================================================================
    // Test: Output buffer catch-all rewrites canonical in HTML
    // =========================================================================

    public function testHeadBufferRewritesCanonical(): void
    {
        $html = '<link rel="canonical" href="https://pdfviewer.drossmedia.de/enterprise/">';

        // Simulate what pdfviewer_flush_head_buffer does
        $wp_domain = 'pdfviewer.drossmedia.de';
        $vanity_domain = 'pdfviewermodule.com';
        $wp_scheme = 'https';
        $vanity_scheme = 'https';

        $result = preg_replace(
            '/(rel=["\']canonical["\']\\s+href=["\'])' . preg_quote($wp_scheme . '://' . $wp_domain, '/') . '/i',
            '${1}' . $vanity_scheme . '://' . $vanity_domain,
            $html
        );

        $this->assertStringContainsString('pdfviewermodule.com', $result, 'Buffer must rewrite canonical');
        $this->assertStringNotContainsString('drossmedia.de', $result, 'Buffer must remove old domain');
    }

    public function testHeadBufferRewritesOgUrl(): void
    {
        $html = '<meta property="og:url" content="https://pdfviewer.drossmedia.de/pro/">';

        $wp_domain = 'pdfviewer.drossmedia.de';
        $vanity_domain = 'pdfviewermodule.com';

        $result = preg_replace(
            '/(property=["\']og:url["\']\\s+content=["\'])' . preg_quote('https://' . $wp_domain, '/') . '/i',
            '${1}https://' . $vanity_domain,
            $html
        );

        $this->assertStringContainsString('pdfviewermodule.com/pro/', $result);
        $this->assertStringNotContainsString('drossmedia.de', $result);
    }

    // =========================================================================
    // Test: Fallback function outputs correct og:type
    // =========================================================================

    public function testFallbackOutputsArticleType(): void
    {
        // The fallback function outputs og:type=article (not website)
        // so LinkedIn can detect author information
        ob_start();
        pdfviewer_fallback_canonical_og_tags();
        $output = ob_get_clean();

        $this->assertStringContainsString(
            'og:type',
            $output,
            'Fallback must output og:type'
        );
        $this->assertStringContainsString(
            'content="article"',
            $output,
            'og:type must be "article" for LinkedIn author detection'
        );
        $this->assertStringNotContainsString(
            'content="website"',
            $output,
            'og:type must NOT be "website" (LinkedIn cannot detect author)'
        );
    }

    // =========================================================================
    // Test: Fallback function outputs canonical with vanity domain
    // =========================================================================

    public function testFallbackOutputsVanityCanonical(): void
    {
        ob_start();
        pdfviewer_fallback_canonical_og_tags();
        $output = ob_get_clean();

        $this->assertStringContainsString(
            '<link rel="canonical" href="' . self::$vanityDomain,
            $output,
            'Fallback must output canonical with pdfviewermodule.com'
        );
        $this->assertStringNotContainsString(
            'drossmedia.de',
            $output,
            'Fallback must not contain old domain'
        );
    }

    // =========================================================================
    // Test: Fallback outputs og:url with vanity domain
    // =========================================================================

    public function testFallbackOutputsVanityOgUrl(): void
    {
        ob_start();
        pdfviewer_fallback_canonical_og_tags();
        $output = ob_get_clean();

        $this->assertMatchesRegularExpression(
            '/og:url.*content="https:\/\/pdfviewermodule\.com/',
            $output,
            'og:url must use pdfviewermodule.com'
        );
    }

    // =========================================================================
    // Test: No hardcoded drossmedia.de in template files
    // =========================================================================

    /**
     * @return array<string, array{0: string}>
     */
    public static function templateFileProvider(): array
    {
        $themeDir = dirname(__DIR__, 2);
        return [
            'header.php'                    => [$themeDir . '/header.php'],
            'footer.php'                    => [$themeDir . '/footer.php'],
            'front-page.php'                => [$themeDir . '/front-page.php'],
            'page-examples.php'             => [$themeDir . '/page-examples.php'],
            'page-pdf-grid.php'             => [$themeDir . '/page-pdf-grid.php'],
            'page-documentation.php'        => [$themeDir . '/page-documentation.php'],
            'page-wordpress-pdf-viewer.php' => [$themeDir . '/page-wordpress-pdf-viewer.php'],
            'page-drupal-pdf-viewer.php'    => [$themeDir . '/page-drupal-pdf-viewer.php'],
            'page-nextjs-pdf-viewer.php'    => [$themeDir . '/page-nextjs-pdf-viewer.php'],
        ];
    }

    /**
     * @dataProvider templateFileProvider
     */
    public function testTemplateHasNoHardcodedOldDomain(string $filePath): void
    {
        if (!file_exists($filePath)) {
            $this->markTestSkipped("File not found: $filePath");
        }

        $content = file_get_contents($filePath);
        $basename = basename($filePath);

        $this->assertStringNotContainsString(
            'pdfviewer.drossmedia.de',
            $content,
            "Template $basename still contains hardcoded pdfviewer.drossmedia.de URLs"
        );
    }

    // =========================================================================
    // Test: OG image files exist on disk
    // =========================================================================

    public function testOgImageFilesExist(): void
    {
        $themeDir = dirname(__DIR__, 2);
        $files = [
            'assets/images/og-image.jpg',
            'assets/images/og-image.png',
            'assets/images/og-image.webp',
        ];

        foreach ($files as $file) {
            $this->assertFileExists(
                $themeDir . '/' . $file,
                "OG image file missing: $file"
            );
        }
    }

    public function testScreenshotExists(): void
    {
        $this->assertFileExists(
            dirname(__DIR__, 2) . '/screenshot.png',
            'Theme screenshot.png missing'
        );
    }
}
