=== PDF Embed & SEO Optimize ===
Contributors: drossmedia
Donate link: https://pdfviewer.drossmedia.de/pro/
Tags: pdf, pdf viewer, pdf embed, seo, document viewer, gutenberg, elementor
Requires at least: 6.0
Tested up to: 6.7
Stable tag: 1.0.0
Requires PHP: 8.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

The only WordPress PDF plugin that makes your PDFs discoverable by search engines. Embed PDFs with full SEO optimization, XML sitemaps, and schema markup.

== Description ==

**PDF Embed & SEO Optimize** is the first WordPress plugin designed specifically to make your PDF documents discoverable by search engines while providing a beautiful, responsive viewing experience.

### The Problem with Other PDF Plugins

Most PDF plugins simply embed a viewer on your page. But search engines can't properly index embedded PDFs, meaning your valuable content remains invisible to Google and potential visitors.

### Our Solution

PDF Embed & SEO Optimize creates dedicated, SEO-optimized landing pages for each PDF with:

* **Automatic meta tags** - Title, description, and keywords extracted from your PDFs
* **XML Sitemap integration** - Your PDFs appear in a dedicated sitemap for search engines
* **Schema.org markup** - Rich structured data helps Google understand your documents
* **Social sharing optimization** - Open Graph and Twitter Card support
* **GEO (Generative Engine Optimization)** - Optimized for AI-powered search engines

### Key Features

= PDF Viewing Options =
* **In-Page Viewer** - Embed PDFs directly in your content
* **Standalone Viewer** - Full-page PDF viewing experience
* **Responsive Design** - Works perfectly on all devices
* **Custom Dimensions** - Set width and height per embed

= SEO Features =
* Automatic PDF text extraction for meta descriptions
* Dedicated XML sitemap for all PDF documents
* Schema.org Document markup
* Canonical URLs to prevent duplicate content
* Breadcrumb navigation support

= Display Options =
* List view archive page (`/pdf/`)
* Grid view with thumbnails (`/pdf-grid/`)
* Customizable viewer toolbar
* Enable/disable download button
* Enable/disable print button

= Integrations =
* Gutenberg block editor
* Classic editor shortcodes
* Elementor widget
* WPBakery support
* REST API endpoints

### Shortcode Usage

**Basic embed:**
`[pdf_viewer id="123"]`

**With custom dimensions:**
`[pdf_viewer id="123" width="100%" height="600px"]`

**Standalone viewer (opens in new tab):**
`[pdf_viewer id="123" mode="standalone"]`

**PDF sitemap list:**
`[pdf_viewer_sitemap orderby="date" order="DESC" limit="10"]`

### Gutenberg Block

Simply search for "PDF" in the block inserter and drag the **PDF Embed** block to your content. Select your PDF from the media library and customize the display options in the sidebar.

### REST API

Access your PDF documents programmatically:

`GET /wp-json/pdf-embed-seo/v1/documents`
`GET /wp-json/pdf-embed-seo/v1/documents/{id}`

### Pro Features

Upgrade to [PDF Embed Pro](https://pdfviewer.drossmedia.de/pro/) for advanced features:

* **Password Protection** - Secure sensitive documents
* **Analytics Dashboard** - Track views, downloads, and engagement
* **Custom Branding** - Remove plugin branding, add your logo
* **Expiring Links** - Time-limited access to documents
* **IP Anonymization** - GDPR-compliant tracking
* **Priority Support** - Direct email support

[Compare Free vs Pro](https://pdfviewer.drossmedia.de/pro/#comparison)

== Installation ==

= Automatic Installation =

1. Go to **Plugins > Add New** in your WordPress admin
2. Search for "PDF Embed & SEO Optimize"
3. Click **Install Now** and then **Activate**

= Manual Installation =

1. Download the plugin ZIP file
2. Go to **Plugins > Add New > Upload Plugin**
3. Choose the ZIP file and click **Install Now**
4. Activate the plugin

= WP-CLI Installation =

`wp plugin install pdf-embed-seo-optimize --activate`

= After Activation =

1. Go to **Settings > PDF Embed & SEO**
2. Configure your preferred viewer settings
3. Upload your first PDF via **Media > Add New**
4. Create a PDF post or use the shortcode/block

== Frequently Asked Questions ==

= Does this plugin work with any PDF? =

Yes! The plugin works with any valid PDF file. For best SEO results, use PDFs with selectable text rather than scanned images.

= Will my existing PDFs be automatically indexed? =

PDFs uploaded after activation will be automatically processed. For existing PDFs, go to **Tools > PDF Embed** and click "Reprocess All PDFs."

= Can I customize the viewer appearance? =

Yes, you can customize colors, toolbar buttons, and dimensions through the settings page or per-embed using shortcode attributes.

= Is this plugin GDPR compliant? =

Yes. The free version doesn't collect any user data. The Pro version includes IP anonymization for analytics.

= Does it work with page builders? =

Yes! We support Gutenberg, Elementor, WPBakery, Divi, and most other page builders through our shortcode and dedicated widgets.

= Can I disable downloads for certain PDFs? =

Yes, you can disable the download button per PDF or globally in settings.

= How does the SEO optimization work? =

The plugin creates a dedicated page for each PDF with optimized meta tags, schema markup, and adds them to a XML sitemap. This helps search engines discover and index your PDF content.

= Is there a limit to how many PDFs I can embed? =

No, the free version has no limits on the number of PDFs.

== Screenshots ==

1. In-page PDF viewer with responsive design
2. PDF archive list view
3. PDF archive grid view with thumbnails
4. Gutenberg block in the editor
5. Plugin settings page
6. Individual PDF SEO settings
7. XML sitemap output
8. Schema.org markup preview

== Changelog ==

= 1.0.0 =
* Initial release
* In-page and standalone PDF viewers
* Gutenberg block and shortcode support
* XML sitemap generation
* Schema.org markup
* REST API endpoints
* Elementor widget
* Archive list and grid views

== Upgrade Notice ==

= 1.0.0 =
Initial release of PDF Embed & SEO Optimize. Make your PDFs discoverable!

== Additional Info ==

**Documentation:** [https://pdfviewer.drossmedia.de/documentation/](https://pdfviewer.drossmedia.de/documentation/)

**Live Examples:** [https://pdfviewer.drossmedia.de/examples/](https://pdfviewer.drossmedia.de/examples/)

**Support:** [https://wordpress.org/support/plugin/pdf-embed-seo-optimize/](https://wordpress.org/support/plugin/pdf-embed-seo-optimize/)

**GitHub:** [https://github.com/alexanderdross/pdf-embed-seo-optimize](https://github.com/alexanderdross/pdf-embed-seo-optimize)
