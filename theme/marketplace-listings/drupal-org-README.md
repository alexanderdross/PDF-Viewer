# PDF Embed & SEO Optimize

The only Drupal module that makes your PDFs discoverable by search engines. Embed PDFs with full SEO optimization, XML sitemaps, and schema markup.

## Table of Contents

- [Introduction](#introduction)
- [Requirements](#requirements)
- [Installation](#installation)
- [Configuration](#configuration)
- [Usage](#usage)
- [Theming](#theming)
- [API & Hooks](#api--hooks)
- [Pro Version](#pro-version)
- [Troubleshooting](#troubleshooting)
- [Maintainers](#maintainers)

## Introduction

**PDF Embed & SEO Optimize** is the first Drupal module designed specifically to make your PDF documents discoverable by search engines while providing a beautiful, responsive viewing experience.

### The Problem

Most PDF embedding solutions simply display a viewer on your page. Search engines can't properly index embedded PDFs, meaning your valuable content remains invisible to Google and potential visitors.

### The Solution

This module creates dedicated, SEO-optimized pages for each PDF document with:

- **Automatic meta tags** extracted from PDF metadata
- **XML Sitemap integration** via the Sitemap module
- **Schema.org markup** for rich search results
- **Open Graph tags** for social sharing
- **GEO optimization** for AI-powered search engines

### Key Features

| Feature | Description |
|---------|-------------|
| In-Page Viewer | Embed PDFs directly in content |
| Standalone Viewer | Full-page PDF experience |
| Responsive Design | Mobile-friendly on all devices |
| Archive Views | List and grid views for PDF collections |
| REST API | Programmatic access to documents |
| Blocks | Ready-to-use blocks for Layout Builder |

## Requirements

- Drupal 10.x or 11.x
- PHP 8.1 or higher
- File module (core)
- Field module (core)

### Recommended Modules

- [Simple XML Sitemap](https://www.drupal.org/project/simple_sitemap) - For XML sitemap integration
- [Metatag](https://www.drupal.org/project/metatag) - Enhanced meta tag management
- [Pathauto](https://www.drupal.org/project/pathauto) - Clean URL aliases

## Installation

### Via Composer (Recommended)

```bash
composer require drupal/pdf_embed_seo
drush en pdf_embed_seo -y
drush cr
```

### Manual Installation

1. Download the module from [Drupal.org](https://www.drupal.org/project/pdf_embed_seo)
2. Extract to `/modules/contrib/pdf_embed_seo`
3. Enable via **Extend** or Drush: `drush en pdf_embed_seo -y`
4. Clear caches: `drush cr`

## Configuration

### Basic Setup

1. Navigate to **Configuration > Media > PDF Embed & SEO**
2. Configure general settings:
   - Default viewer mode (in-page/standalone)
   - Default dimensions
   - Toolbar options (download, print)
3. Configure SEO settings:
   - Enable/disable XML sitemap
   - Schema.org markup options
   - Meta tag templates
4. Save configuration

### Permissions

Navigate to **People > Permissions** and configure:

| Permission | Description |
|------------|-------------|
| `administer pdf embed seo` | Full administrative access |
| `create pdf documents` | Create new PDF documents |
| `edit own pdf documents` | Edit own PDF uploads |
| `edit any pdf documents` | Edit all PDF documents |
| `view pdf documents` | View published PDFs |

## Usage

### Creating PDF Documents

1. Navigate to **Content > PDF Documents > Add PDF**
2. Upload your PDF file
3. Fill in the SEO fields (or let auto-extraction handle it):
   - Title
   - Description
   - Keywords
4. Configure display options
5. Save

### Embedding in Content

#### Using the Block

1. Go to **Structure > Block Layout**
2. Place the "PDF Viewer" block
3. Select the PDF document
4. Configure display options

#### Using Twig Templates

```twig
{# Render a PDF viewer #}
{{ drupal_block('pdf_embed_viewer', { 'pdf_id': 123 }) }}

{# Render with custom options #}
{{ drupal_block('pdf_embed_viewer', {
  'pdf_id': 123,
  'width': '100%',
  'height': '600px',
  'toolbar': true
}) }}
```

#### Using the Field Formatter

Add a "PDF Embed" field to any content type:

1. **Structure > Content types > [Type] > Manage fields**
2. Add field of type "File"
3. **Manage display** > Select "PDF Embed Viewer" formatter
4. Configure formatter settings

### Archive Pages

The module provides two archive views:

- **List View:** `/pdf` - Displays all PDFs in a list format
- **Grid View:** `/pdf-grid` - Displays PDFs with thumbnail previews

Configure archive settings at **Configuration > Media > PDF Embed & SEO > Archives**.

### REST API

The module provides REST endpoints:

```bash
# List all documents
GET /api/pdf-embed-seo/v1/documents

# Get single document
GET /api/pdf-embed-seo/v1/documents/{id}

# Search documents
GET /api/pdf-embed-seo/v1/documents?search=annual+report
```

Enable the REST resources at **Configuration > Web services > REST**.

## Theming

### Template Files

Override these templates in your theme:

```
pdf-embed-viewer.html.twig       # Main viewer template
pdf-embed-archive-list.html.twig # List archive view
pdf-embed-archive-grid.html.twig # Grid archive view
pdf-embed-toolbar.html.twig      # Viewer toolbar
```

### CSS Classes

The module adds these classes for styling:

```css
.pdf-embed-viewer { }
.pdf-embed-viewer--in-page { }
.pdf-embed-viewer--standalone { }
.pdf-embed-toolbar { }
.pdf-embed-toolbar__button { }
.pdf-embed-archive { }
.pdf-embed-archive--list { }
.pdf-embed-archive--grid { }
.pdf-embed-archive__item { }
```

### Preprocess Functions

```php
/**
 * Implements hook_preprocess_pdf_embed_viewer().
 */
function mytheme_preprocess_pdf_embed_viewer(&$variables) {
  $variables['attributes']['class'][] = 'my-custom-class';
}
```

## API & Hooks

### Available Hooks

```php
/**
 * Alter PDF document data before saving.
 *
 * @param array $data
 *   The document data array.
 * @param \Drupal\file\FileInterface $file
 *   The uploaded file entity.
 */
function hook_pdf_embed_seo_document_presave(array &$data, FileInterface $file) {
  // Custom processing
  $data['custom_field'] = 'value';
}

/**
 * React to PDF document being saved.
 *
 * @param \Drupal\pdf_embed_seo\Entity\PdfDocument $document
 *   The saved document entity.
 */
function hook_pdf_embed_seo_document_saved(PdfDocument $document) {
  // Log, notify, or trigger workflows
  \Drupal::logger('mymodule')->info('PDF saved: @title', [
    '@title' => $document->label(),
  ]);
}

/**
 * Alter the viewer render array.
 *
 * @param array $build
 *   The render array.
 * @param \Drupal\pdf_embed_seo\Entity\PdfDocument $document
 *   The document being rendered.
 */
function hook_pdf_embed_seo_viewer_alter(array &$build, PdfDocument $document) {
  // Add custom elements
  $build['custom_element'] = [
    '#markup' => '<div class="custom-overlay">Custom content</div>',
  ];
}

/**
 * Alter schema.org markup for a document.
 *
 * @param array $schema
 *   The schema.org data array.
 * @param \Drupal\pdf_embed_seo\Entity\PdfDocument $document
 *   The document entity.
 */
function hook_pdf_embed_seo_schema_alter(array &$schema, PdfDocument $document) {
  $schema['author'] = [
    '@type' => 'Organization',
    'name' => 'My Company',
  ];
}
```

### Services

```php
// Get the PDF manager service
$manager = \Drupal::service('pdf_embed_seo.manager');

// Load a document
$document = $manager->load(123);

// Get document metadata
$metadata = $manager->extractMetadata($document);

// Generate sitemap entries
$sitemap = $manager->getSitemapEntries();
```

### Events

The module dispatches these events:

| Event | Description |
|-------|-------------|
| `PdfViewedEvent` | Fired when a PDF is viewed |
| `PdfDownloadedEvent` | Fired when a PDF is downloaded |
| `PdfDocumentSavedEvent` | Fired after document save |

## Pro Version

Upgrade to [PDF Embed Pro](https://pdfviewer.drossmedia.de/pro/) for advanced features:

- **Password Protection** - Secure sensitive documents
- **Analytics Dashboard** - Track views, downloads, engagement
- **Custom Branding** - White-label the viewer
- **Expiring Links** - Time-limited document access
- **IP Anonymization** - GDPR-compliant tracking
- **Watermarks** - Add dynamic watermarks
- **Priority Support** - Direct email support

[Compare Free vs Pro](https://pdfviewer.drossmedia.de/pro/#comparison)

## Troubleshooting

### PDF Not Displaying

1. Check file permissions on the upload directory
2. Verify PHP memory limit (recommended: 256M+)
3. Clear Drupal caches: `drush cr`
4. Check browser console for JavaScript errors

### XML Sitemap Not Updating

1. Ensure Simple XML Sitemap module is installed
2. Regenerate sitemap: `drush simple-sitemap:generate`
3. Check sitemap settings include PDF documents

### Meta Tags Not Appearing

1. Install and enable the Metatag module
2. Clear caches after configuration changes
3. Check that documents have the "Published" status

### Performance Issues

1. Enable caching in module settings
2. Consider using a CDN for PDF delivery
3. Optimize PDF files before upload (reduce file size)

## Maintainers

- **Alexander Dross** - [drossmedia](https://www.drupal.org/u/drossmedia)

### Links

- **Documentation:** [https://pdfviewer.drossmedia.de/documentation/#drupal](https://pdfviewer.drossmedia.de/documentation/#drupal)
- **Issue Queue:** [https://www.drupal.org/project/issues/pdf_embed_seo](https://www.drupal.org/project/issues/pdf_embed_seo)
- **Live Examples:** [https://pdfviewer.drossmedia.de/examples/](https://pdfviewer.drossmedia.de/examples/)

## License

This project is licensed under the [GNU General Public License v2.0](https://www.gnu.org/licenses/gpl-2.0.html).
