<?php
/**
 * Unit Tests for template tag functions
 *
 * @package PDFViewer
 * @subpackage Tests
 */

namespace PDFViewer\Tests\Unit;

use PHPUnit\Framework\TestCase;

/**
 * Test template tag functions
 */
class TemplateFunctionsTest extends TestCase
{
    /**
     * Set up test environment
     */
    public static function setUpBeforeClass(): void
    {
        // Define WordPress constants if not defined
        if (!defined('ABSPATH')) {
            define('ABSPATH', '/var/www/html/');
        }

        // Load WordPress function stubs
        self::loadWordPressFunctionStubs();

        // Load the template tags file
        require_once dirname(__DIR__, 2) . '/inc/template-tags.php';
    }

    /**
     * Load WordPress function stubs for testing
     */
    private static function loadWordPressFunctionStubs(): void
    {
        if (!function_exists('wp_parse_args')) {
            function wp_parse_args($args, $defaults = []) {
                if (is_array($args)) {
                    return array_merge($defaults, $args);
                }
                return $defaults;
            }
        }

        if (!function_exists('esc_url')) {
            function esc_url($url) {
                return filter_var($url, FILTER_SANITIZE_URL);
            }
        }

        if (!function_exists('esc_attr')) {
            function esc_attr($text) {
                return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
            }
        }

        if (!function_exists('esc_html_e')) {
            function esc_html_e($text, $domain = 'default') {
                echo htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
            }
        }

        if (!function_exists('esc_attr_e')) {
            function esc_attr_e($text, $domain = 'default') {
                echo htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
            }
        }

        if (!function_exists('__')) {
            function __($text, $domain = 'default') {
                return $text;
            }
        }

        if (!function_exists('home_url')) {
            function home_url($path = '') {
                return 'https://example.com' . $path;
            }
        }

        if (!defined('PDFVIEWER_THEME_URI')) {
            define('PDFVIEWER_THEME_URI', 'https://example.com/wp-content/themes/pdfviewer-theme');
        }

        if (!function_exists('wp_date')) {
            function wp_date($format, $timestamp = null, $timezone = null) {
                return date($format, $timestamp ?? time());
            }
        }
    }

    /**
     * Test pdfviewer_current_year returns current year
     */
    public function testCurrentYearReturnsCurrentYear(): void
    {
        $result = pdfviewer_current_year();
        $expected = date('Y');

        $this->assertEquals($expected, $result);
    }

    /**
     * Test pdfviewer_responsive_picture returns empty for missing sources
     */
    public function testResponsivePictureReturnsEmptyForMissingSources(): void
    {
        $result = pdfviewer_responsive_picture([
            'alt' => 'Test',
        ]);

        $this->assertEmpty($result);
    }

    /**
     * Test pdfviewer_responsive_picture returns empty for missing alt
     */
    public function testResponsivePictureReturnsEmptyForMissingAlt(): void
    {
        $result = pdfviewer_responsive_picture([
            'sources' => [
                ['webp' => 'test.webp', 'fallback' => 'test.png'],
            ],
        ]);

        $this->assertEmpty($result);
    }

    /**
     * Test pdfviewer_responsive_picture returns valid HTML
     */
    public function testResponsivePictureReturnsValidHtml(): void
    {
        $result = pdfviewer_responsive_picture([
            'sources' => [
                [
                    'webp' => 'https://example.com/image.webp',
                    'fallback' => 'https://example.com/image.png',
                    'width' => 800,
                ],
            ],
            'alt' => 'Test image',
            'width' => 800,
            'height' => 600,
        ]);

        $this->assertStringContainsString('<picture>', $result);
        $this->assertStringContainsString('</picture>', $result);
        $this->assertStringContainsString('<img', $result);
        $this->assertStringContainsString('alt="Test image"', $result);
    }

    /**
     * Test pdfviewer_simple_picture returns valid HTML
     */
    public function testSimplePictureReturnsValidHtml(): void
    {
        $result = pdfviewer_simple_picture([
            'webp' => 'https://example.com/image.webp',
            'fallback' => 'https://example.com/image.png',
            'alt' => 'Simple test',
            'width' => 400,
            'height' => 300,
        ]);

        $this->assertStringContainsString('<picture>', $result);
        $this->assertStringContainsString('<source type="image/webp"', $result);
        $this->assertStringContainsString('<img', $result);
    }

    /**
     * Test pdfviewer_simple_picture handles srcset
     */
    public function testSimplePictureHandlesSrcset(): void
    {
        $result = pdfviewer_simple_picture([
            'webp' => 'https://example.com/image.webp',
            'fallback' => 'https://example.com/image.png',
            'alt' => 'Test',
            'srcset_webp' => 'image-1x.webp 1x, image-2x.webp 2x',
            'srcset' => 'image-1x.png 1x, image-2x.png 2x',
        ]);

        $this->assertStringContainsString('srcset="image-1x.webp 1x, image-2x.webp 2x"', $result);
    }

    /**
     * Test pdfviewer_get_logo returns picture element
     */
    public function testGetLogoReturnsPictureElement(): void
    {
        $result = pdfviewer_get_logo(true);

        $this->assertStringContainsString('<picture>', $result);
        $this->assertStringContainsString('logo', $result);
    }

    /**
     * Test logo has accessibility attributes
     */
    public function testLogoHasAccessibilityAttributes(): void
    {
        $result = pdfviewer_get_logo();

        $this->assertStringContainsString('alt=', $result);
    }

    /**
     * Test skip link outputs correct HTML
     */
    public function testSkipLinkOutputsCorrectHtml(): void
    {
        ob_start();
        pdfviewer_skip_link();
        $result = ob_get_clean();

        $this->assertStringContainsString('href="#main-content"', $result);
        $this->assertStringContainsString('skip-link', $result);
        $this->assertStringContainsString('sr-only', $result);
    }
}
