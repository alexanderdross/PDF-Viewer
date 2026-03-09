<?php
/**
 * Unit Tests for pdfviewer_picture() and related functions
 *
 * @package PDFViewer
 * @subpackage Tests
 */

namespace PDFViewer\Tests\Unit;

use PHPUnit\Framework\TestCase;

/**
 * Test picture helper functions
 */
class PictureFunctionTest extends TestCase
{
    /**
     * Set up test environment
     */
    public static function setUpBeforeClass(): void
    {
        // Load WordPress function stubs
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

        // Load the function file
        require_once dirname(__DIR__, 2) . '/functions.php';
    }

    /**
     * Test picture returns valid HTML structure
     */
    public function testReturnsValidPictureElement(): void
    {
        $result = pdfviewer_picture([
            'src_webp' => 'https://example.com/image.webp',
            'src_fallback' => 'https://example.com/image.png',
            'alt' => 'Test image',
            'width' => 100,
            'height' => 100,
        ]);

        $this->assertStringContainsString('<picture>', $result);
        $this->assertStringContainsString('</picture>', $result);
        $this->assertStringContainsString('<img', $result);
    }

    /**
     * Test picture includes WebP source
     */
    public function testIncludesWebpSource(): void
    {
        $result = pdfviewer_picture([
            'src_webp' => 'https://example.com/image.webp',
            'src_fallback' => 'https://example.com/image.png',
            'alt' => 'Test image',
        ]);

        $this->assertStringContainsString('<source type="image/webp"', $result);
        $this->assertStringContainsString('image.webp', $result);
    }

    /**
     * Test picture includes alt attribute
     */
    public function testIncludesAltAttribute(): void
    {
        $result = pdfviewer_picture([
            'src_fallback' => 'https://example.com/image.png',
            'alt' => 'Descriptive alt text',
        ]);

        $this->assertStringContainsString('alt="Descriptive alt text"', $result);
    }

    /**
     * Test picture includes width and height for CLS
     */
    public function testIncludesWidthAndHeight(): void
    {
        $result = pdfviewer_picture([
            'src_fallback' => 'https://example.com/image.png',
            'alt' => 'Test',
            'width' => 800,
            'height' => 600,
        ]);

        $this->assertStringContainsString('width="800"', $result);
        $this->assertStringContainsString('height="600"', $result);
    }

    /**
     * Test lazy loading is enabled by default
     */
    public function testLazyLoadingEnabledByDefault(): void
    {
        $result = pdfviewer_picture([
            'src_fallback' => 'https://example.com/image.png',
            'alt' => 'Test',
        ]);

        $this->assertStringContainsString('loading="lazy"', $result);
        $this->assertStringContainsString('decoding="async"', $result);
    }

    /**
     * Test eager loading when lazy is false
     */
    public function testEagerLoadingWhenLazyFalse(): void
    {
        $result = pdfviewer_picture([
            'src_fallback' => 'https://example.com/image.png',
            'alt' => 'Test',
            'lazy' => false,
        ]);

        $this->assertStringContainsString('loading="eager"', $result);
        $this->assertStringContainsString('fetchpriority="high"', $result);
    }

    /**
     * Test custom class is added
     */
    public function testCustomClassAdded(): void
    {
        $result = pdfviewer_picture([
            'src_fallback' => 'https://example.com/image.png',
            'alt' => 'Test',
            'class' => 'hero-image rounded-lg',
        ]);

        $this->assertStringContainsString('class="hero-image rounded-lg"', $result);
    }

    /**
     * Test title attribute is added
     */
    public function testTitleAttributeAdded(): void
    {
        $result = pdfviewer_picture([
            'src_fallback' => 'https://example.com/image.png',
            'alt' => 'Test',
            'title' => 'Image title for SEO',
        ]);

        $this->assertStringContainsString('title="Image title for SEO"', $result);
    }

    /**
     * Test aria-label is added
     */
    public function testAriaLabelAdded(): void
    {
        $result = pdfviewer_picture([
            'src_fallback' => 'https://example.com/image.png',
            'alt' => 'Test',
            'aria_label' => 'Accessible label',
        ]);

        $this->assertStringContainsString('aria-label="Accessible label"', $result);
    }

    /**
     * Test XSS protection in alt attribute
     */
    public function testXssProtectionInAlt(): void
    {
        $result = pdfviewer_picture([
            'src_fallback' => 'https://example.com/image.png',
            'alt' => '<script>alert("xss")</script>',
        ]);

        $this->assertStringNotContainsString('<script>', $result);
    }

    /**
     * Test handles missing webp source gracefully
     */
    public function testHandlesMissingWebpSource(): void
    {
        $result = pdfviewer_picture([
            'src_fallback' => 'https://example.com/image.png',
            'alt' => 'Test',
        ]);

        $this->assertStringContainsString('<picture>', $result);
        $this->assertStringContainsString('<img', $result);
    }

    /**
     * Test sizes attribute is added
     */
    public function testSizesAttributeAdded(): void
    {
        $result = pdfviewer_picture([
            'src_webp' => 'https://example.com/image.webp',
            'src_fallback' => 'https://example.com/image.png',
            'alt' => 'Test',
            'sizes' => '(max-width: 768px) 100vw, 50vw',
        ]);

        $this->assertStringContainsString('sizes="(max-width: 768px) 100vw, 50vw"', $result);
    }
}
