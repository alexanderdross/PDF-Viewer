<?php
/**
 * Unit Tests for pdfviewer_icon() function
 *
 * @package PDFViewer
 * @subpackage Tests
 */

namespace PDFViewer\Tests\Unit;

use PHPUnit\Framework\TestCase;

/**
 * Test the pdfviewer_icon function
 */
class IconFunctionTest extends TestCase
{
    /**
     * Set up test environment
     */
    public static function setUpBeforeClass(): void
    {
        // Load WordPress test stubs
        if (!function_exists('esc_attr')) {
            function esc_attr($text) {
                return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
            }
        }

        // Load the function file
        require_once dirname(__DIR__, 2) . '/functions.php';
    }

    /**
     * Test icon returns valid SVG for known icon
     */
    public function testReturnsValidSvgForKnownIcon(): void
    {
        $result = pdfviewer_icon('menu', 24);

        $this->assertStringContainsString('<svg', $result);
        $this->assertStringContainsString('</svg>', $result);
        $this->assertStringContainsString('width="24"', $result);
        $this->assertStringContainsString('height="24"', $result);
    }

    /**
     * Test icon returns empty string for unknown icon
     */
    public function testReturnsEmptyStringForUnknownIcon(): void
    {
        $result = pdfviewer_icon('nonexistent-icon', 24);

        $this->assertEmpty($result);
    }

    /**
     * Test icon respects custom size
     */
    public function testRespectsCustomSize(): void
    {
        $result = pdfviewer_icon('check', 16);

        $this->assertStringContainsString('width="16"', $result);
        $this->assertStringContainsString('height="16"', $result);
    }

    /**
     * Test icon adds custom class
     */
    public function testAddsCustomClass(): void
    {
        $result = pdfviewer_icon('zap', 24, 'text-primary');

        $this->assertStringContainsString('class="text-primary"', $result);
    }

    /**
     * Test icon has aria-hidden attribute
     */
    public function testHasAriaHiddenAttribute(): void
    {
        $result = pdfviewer_icon('download', 24);

        $this->assertStringContainsString('aria-hidden="true"', $result);
    }

    /**
     * Test icon has viewBox attribute
     */
    public function testHasViewBoxAttribute(): void
    {
        $result = pdfviewer_icon('search', 24);

        $this->assertStringContainsString('viewBox="0 0 24 24"', $result);
    }

    /**
     * Test all registered icons are valid
     *
     * @dataProvider iconNamesProvider
     */
    public function testAllRegisteredIconsAreValid(string $iconName): void
    {
        $result = pdfviewer_icon($iconName, 24);

        $this->assertNotEmpty($result, "Icon '$iconName' should return non-empty string");
        $this->assertStringContainsString('<svg', $result);
    }

    /**
     * Data provider for icon names
     */
    public static function iconNamesProvider(): array
    {
        return [
            ['menu'],
            ['x'],
            ['download'],
            ['arrow-right'],
            ['external-link'],
            ['check'],
            ['x-circle'],
            ['zap'],
            ['file-text'],
            ['search'],
            ['chevron-down'],
            ['sparkles'],
            ['book-open'],
            ['eye'],
            ['lock'],
            ['shopping-cart'],
            ['link'],
            ['terminal'],
            ['settings'],
            ['code-2'],
            ['puzzle'],
            ['database'],
            ['shield'],
            ['globe'],
            ['quote'],
            ['message-square'],
            ['star-filled'],
            ['file-code'],
            ['building-2'],
        ];
    }

    /**
     * Test icon handles zero size
     */
    public function testHandlesZeroSize(): void
    {
        $result = pdfviewer_icon('check', 0);

        $this->assertStringContainsString('width="0"', $result);
        $this->assertStringContainsString('height="0"', $result);
    }

    /**
     * Test icon escapes class attribute
     */
    public function testEscapesClassAttribute(): void
    {
        $result = pdfviewer_icon('check', 24, '<script>alert("xss")</script>');

        $this->assertStringNotContainsString('<script>', $result);
    }
}
