import { Layout } from "@/components/layout/Layout";
import { SEOHead } from "@/components/SEOHead";
import { useJsonLd } from "@/hooks/use-json-ld";
import { Button } from "@/components/ui/button";
import { 
  FileText, 
  Download, 
  ArrowRight, 
  Monitor,
  Smartphone,
  Tablet,
  Moon,
  Sun,
  Zap,
  Shield,
  Globe,
  CheckCircle,
  Check,
  Code,
  Blocks,
  Paintbrush,
  Search,
  Share2,
  BarChart3
} from "lucide-react";
import { WordpressComparisonTable } from "@/components/wordpress/WordpressComparisonTable";

const viewerFeatures = [
  {
    icon: Monitor,
    title: "Desktop Optimized",
    description: "Full-featured viewer with zoom, search, page navigation, and document outline on desktop browsers."
  },
  {
    icon: Smartphone,
    title: "Mobile Responsive",
    description: "Touch-friendly controls and responsive layout that works perfectly on phones."
  },
  {
    icon: Tablet,
    title: "Tablet Ready",
    description: "Optimized for iPad and Android tablets with gesture support."
  }
];

const themeOptions = [
  {
    icon: Sun,
    title: "Light Theme",
    description: "Clean, bright interface that matches light WordPress themes."
  },
  {
    icon: Moon,
    title: "Dark Theme",
    description: "Easy on the eyes for sites with dark mode enabled."
  }
];

const requirements = [
  "WordPress 5.8 or higher",
  "PHP 7.4 or higher",
  "Optional: Yoast SEO (for enhanced SEO)",
  "Optional: ImageMagick or Ghostscript (for auto thumbnails)"
];

const installSteps = [
  "Download the plugin ZIP file",
  "Go to Plugins > Add New > Upload Plugin",
  "Upload and activate the plugin",
  "Go to PDF Documents to start adding PDFs"
];

const embedMethods = [
  {
    icon: Blocks,
    title: "Gutenberg Block",
    description: "Simply add the PDF Viewer block in the WordPress editor, select your PDF, and publish. No coding required.",
    code: null
  },
  {
    icon: Code,
    title: "Shortcode",
    description: "Use a simple shortcode anywhere on your WordPress site—posts, pages, widgets, or theme files.",
    code: '[pdf_viewer id="123"]'
  },
  {
    icon: Paintbrush,
    title: "Theme Integration",
    description: "For developers, integrate directly into your WordPress theme using PHP.",
    code: "<?php echo do_shortcode('[pdf_viewer id=\"123\"]'); ?>"
  }
];

const seoBenefits = [
  {
    icon: Search,
    title: "SEO-Optimized URLs",
    description: "Each embedded PDF gets a clean URL like /pdf/document-name/ instead of ugly upload paths."
  },
  {
    icon: Share2,
    title: "Social Sharing Ready",
    description: "Auto-generated OpenGraph and Twitter cards with PDF thumbnail previews."
  },
  {
    icon: BarChart3,
    title: "View Analytics",
    description: "Track how many people view each embedded PDF on your WordPress site."
  },
  {
    icon: FileText,
    title: "Beautiful Viewer",
    description: "Mozilla PDF.js provides consistent rendering across all browsers and devices."
  }
];

const pdfViewerSchema = {
  "@context": "https://schema.org",
  "@type": "SoftwareApplication",
  "name": "WordPress PDF Viewer Plugin",
  "alternateName": "PDF Embed & SEO Optimize",
  "applicationCategory": "WordPress Plugin",
  "applicationSubCategory": "PDF Viewer",
  "operatingSystem": "WordPress 5.8+",
  "softwareVersion": "1.2.11",
  "offers": {
    "@type": "Offer",
    "price": "0",
    "priceCurrency": "EUR"
  },
  "description": "The best free WordPress PDF viewer plugin. Display PDFs beautifully with Mozilla PDF.js, SEO optimization, clean URLs, and view analytics. Works with any WordPress theme.",
  "featureList": [
    "Mozilla PDF.js integration (v4.0)",
    "Cross-browser compatible",
    "Mobile responsive viewer",
    "Light and dark themes",
    "SEO-optimized URLs",
    "JSON-LD schema markup",
    "View statistics",
    "Print and download controls",
    "Gutenberg block editor support",
    "Shortcode support",
    "iOS/Safari print support",
    "Yoast SEO integration",
    "Works with any WordPress theme"
  ],
  "downloadUrl": "https://wordpress.org/plugins/pdf-embed-seo-optimize",
  "softwareRequirements": "WordPress 5.8+, PHP 7.4+"
};

const howToSchema = {
  "@context": "https://schema.org",
  "@type": "HowTo",
  "name": "How to Embed PDF in WordPress",
  "description": "Learn three easy methods to embed PDF files in WordPress with SEO optimization, clean URLs, and view tracking.",
  "step": [
    {
      "@type": "HowToStep",
      "name": "Install the Plugin",
      "text": "Download and install PDF Embed & SEO Optimize from the WordPress plugin repository."
    },
    {
      "@type": "HowToStep",
      "name": "Upload Your PDF",
      "text": "Upload your PDF file through the WordPress media library or directly in the plugin settings."
    },
    {
      "@type": "HowToStep",
      "name": "Embed Using Block or Shortcode",
      "text": "Add the PDF Viewer Gutenberg block or use the [pdf_viewer id=\"123\"] shortcode in your content."
    },
    {
      "@type": "HowToStep",
      "name": "Publish and Share",
      "text": "Publish your page. Your PDF now has a clean URL, SEO optimization, and view tracking."
    }
  ],
  "tool": {
    "@type": "SoftwareApplication",
    "name": "PDF Embed & SEO Optimize for WordPress",
    "applicationCategory": "WordPress Plugin",
    "operatingSystem": "WordPress 5.8+",
    "softwareVersion": "1.2.2"
  }
};

const WordpressPdfViewer = () => {
  useJsonLd("pdf-viewer-schema", pdfViewerSchema);
  useJsonLd("embed-pdf-howto-schema", howToSchema);

  return (
    <Layout>
      <SEOHead
        title="WordPress PDF Viewer & Embed Plugin – Free, SEO-Optimized, Mobile Ready"
        description="The best free WordPress PDF plugin. Embed and display PDFs beautifully with Mozilla PDF.js, SEO optimization, clean URLs, Gutenberg blocks, shortcodes, and view analytics."
        canonicalPath="/wordpress-pdf-viewer/"
      />

      {/* Hero */}
      <section className="py-16 lg:py-24 bg-card" aria-labelledby="viewer-hero-heading">
        <div className="container mx-auto px-4 lg:px-8">
          <div className="max-w-4xl mx-auto text-center">
            <div className="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-primary/20 text-foreground text-sm font-medium mb-6">
              <FileText className="w-4 h-4" aria-hidden="true" />
              <span>WordPress PDF Plugin</span>
            </div>
            <h1 id="viewer-hero-heading" className="text-4xl md:text-5xl lg:text-6xl font-extrabold tracking-tight mb-6">
              The Best <span className="text-gradient">WordPress PDF Viewer</span> & Embed Plugin
            </h1>
            <p className="text-xl md:text-2xl text-muted-foreground max-w-3xl mx-auto mb-10">
              Embed and display PDFs beautifully on any device. SEO-optimized with clean URLs, 
              Gutenberg blocks, shortcodes, and view analytics—all for free.
            </p>
            <div className="flex flex-col sm:flex-row items-center justify-center gap-4">
              <Button size="lg" className="gradient-hero shadow-glow text-lg px-8 py-6" asChild>
                <a
                  href="https://wordpress.org/plugins/pdf-embed-seo-optimize"
                  target="_blank"
                  rel="noopener noreferrer"
                  className="gap-2"
                  title="Download the free WordPress PDF Viewer plugin from WordPress.org"
                  aria-label="Download free WordPress PDF Viewer plugin (opens in new tab)"
                >
                  <Download className="w-5 h-5" aria-hidden="true" />
                  Download Free Plugin
                </a>
              </Button>
              <Button size="lg" variant="outline" className="text-lg px-8 py-6" asChild>
                <a href="/examples/" className="gap-2" title="View live demos of the PDF viewer" aria-label="View live PDF viewer demos">
                  View Live Demo
                  <ArrowRight className="w-5 h-5" aria-hidden="true" />
                </a>
              </Button>
            </div>
          </div>
        </div>
      </section>

      {/* Problem vs Solution */}
      <section className="py-16 lg:py-24" aria-labelledby="problem-heading">
        <div className="container mx-auto px-4 lg:px-8">
          <div className="max-w-4xl mx-auto">
            <h2 id="problem-heading" className="text-3xl font-bold mb-8 text-center">
              Why Use Our WordPress PDF Plugin?
            </h2>
            <div className="grid md:grid-cols-2 gap-8">
              <div className="bg-destructive/5 rounded-2xl p-6 border border-destructive/20">
                <h3 className="font-semibold text-lg mb-4 text-destructive">Default WordPress Approach</h3>
                <ul className="space-y-3 text-muted-foreground">
                  <li className="flex items-start gap-2">
                    <span className="text-destructive mt-1">✗</span>
                    Ugly URLs: /wp-content/uploads/2025/01/file.pdf
                  </li>
                  <li className="flex items-start gap-2">
                    <span className="text-destructive mt-1">✗</span>
                    No SEO value—PDFs are invisible to Google
                  </li>
                  <li className="flex items-start gap-2">
                    <span className="text-destructive mt-1">✗</span>
                    No social sharing previews
                  </li>
                  <li className="flex items-start gap-2">
                    <span className="text-destructive mt-1">✗</span>
                    No way to track views
                  </li>
                  <li className="flex items-start gap-2">
                    <span className="text-destructive mt-1">✗</span>
                    Inconsistent display across devices
                  </li>
                </ul>
              </div>
              <div className="bg-primary/5 rounded-2xl p-6 border border-primary/20">
                <h3 className="font-semibold text-lg mb-4 text-primary">With PDF Embed & SEO Optimize</h3>
                <ul className="space-y-3 text-muted-foreground">
                  <li className="flex items-start gap-2">
                    <Check className="w-5 h-5 text-primary shrink-0 mt-0.5" />
                    Clean URLs: /pdf/document-name/
                  </li>
                  <li className="flex items-start gap-2">
                    <Check className="w-5 h-5 text-primary shrink-0 mt-0.5" />
                    Full SEO with schema markup & sitemaps
                  </li>
                  <li className="flex items-start gap-2">
                    <Check className="w-5 h-5 text-primary shrink-0 mt-0.5" />
                    OpenGraph & Twitter Card previews
                  </li>
                  <li className="flex items-start gap-2">
                    <Check className="w-5 h-5 text-primary shrink-0 mt-0.5" />
                    Built-in view analytics
                  </li>
                  <li className="flex items-start gap-2">
                    <Check className="w-5 h-5 text-primary shrink-0 mt-0.5" />
                    Mozilla PDF.js for perfect rendering
                  </li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </section>

      {/* Requirements & Installation */}
      <section className="py-16 lg:py-24 bg-card" aria-labelledby="requirements-heading">
        <div className="container mx-auto px-4 lg:px-8">
          <div className="max-w-4xl mx-auto">
            <div className="grid md:grid-cols-2 gap-8">
              {/* Requirements */}
              <div className="bg-background rounded-2xl p-6 border border-border">
                <h2 id="requirements-heading" className="text-xl font-bold mb-4">Requirements</h2>
                <ul className="space-y-3">
                  {requirements.map((req) => (
                    <li key={req} className="flex items-start gap-3">
                      <CheckCircle className="w-5 h-5 text-primary shrink-0 mt-0.5" aria-hidden="true" />
                      <span className="text-muted-foreground">{req}</span>
                    </li>
                  ))}
                </ul>
              </div>

              {/* Installation */}
              <div className="bg-background rounded-2xl p-6 border border-border">
                <h2 className="text-xl font-bold mb-4">Installation</h2>
                <ol className="space-y-3">
                  {installSteps.map((step, index) => (
                    <li key={step} className="flex items-start gap-3">
                      <span className="w-6 h-6 rounded-full gradient-hero text-primary-foreground text-sm font-bold flex items-center justify-center shrink-0">
                        {index + 1}
                      </span>
                      <span className="text-muted-foreground">{step}</span>
                    </li>
                  ))}
                </ol>
              </div>
            </div>
          </div>
        </div>
      </section>

      {/* How to Embed PDFs */}
      <section className="py-16 lg:py-24" aria-labelledby="embed-methods-heading">
        <div className="container mx-auto px-4 lg:px-8">
          <div className="max-w-4xl mx-auto">
            <div className="text-center mb-12">
              <h2 id="embed-methods-heading" className="text-3xl font-bold mb-4">
                3 Ways to Embed PDFs in WordPress
              </h2>
              <p className="text-lg text-muted-foreground">
                Choose the method that works best for your workflow
              </p>
            </div>
            <div className="grid gap-6">
              {embedMethods.map((method, index) => (
                <div 
                  key={method.title}
                  className="bg-card rounded-2xl p-6 border border-border animate-fade-in"
                  style={{ animationDelay: `${index * 0.1}s` }}
                >
                  <div className="flex items-start gap-4">
                    <div className="w-12 h-12 rounded-xl gradient-hero flex items-center justify-center shrink-0">
                      <method.icon className="w-6 h-6 text-primary-foreground" />
                    </div>
                    <div className="flex-1">
                      <h3 className="text-xl font-semibold mb-2">{method.title}</h3>
                      <p className="text-muted-foreground mb-4">{method.description}</p>
                      {method.code && (
                        <code className="block bg-muted px-4 py-3 rounded-lg text-sm font-mono overflow-x-auto">
                          {method.code}
                        </code>
                      )}
                    </div>
                  </div>
                </div>
              ))}
            </div>
          </div>
        </div>
      </section>

      {/* SEO Benefits */}
      <section className="py-16 lg:py-24 bg-card" aria-labelledby="seo-benefits-heading">
        <div className="container mx-auto px-4 lg:px-8">
          <div className="max-w-4xl mx-auto">
            <h2 id="seo-benefits-heading" className="text-3xl font-bold mb-12 text-center">
              SEO & Analytics Built-In
            </h2>
            <div className="grid sm:grid-cols-2 gap-6">
              {seoBenefits.map((benefit, index) => (
                <div 
                  key={benefit.title}
                  className="bg-background rounded-2xl p-6 border border-border animate-fade-in"
                  style={{ animationDelay: `${index * 0.1}s` }}
                >
                  <div className="w-10 h-10 rounded-lg gradient-hero flex items-center justify-center mb-4">
                    <benefit.icon className="w-5 h-5 text-primary-foreground" aria-hidden="true" />
                  </div>
                  <h3 className="font-semibold mb-2">{benefit.title}</h3>
                  <p className="text-muted-foreground text-sm">{benefit.description}</p>
                </div>
              ))}
            </div>
          </div>
        </div>
      </section>

      {/* PDF.js Section */}
      <section className="py-16 lg:py-24" aria-labelledby="pdfjs-heading">
        <div className="container mx-auto px-4 lg:px-8">
          <div className="max-w-4xl mx-auto">
            <div className="text-center mb-12">
              <div className="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-300 text-sm font-medium mb-6">
                <Zap className="w-4 h-4" aria-hidden="true" />
                <span>Powered by Mozilla</span>
              </div>
              <h2 id="pdfjs-heading" className="text-3xl font-bold mb-4">
                Built on Mozilla's PDF.js (v4.0)
              </h2>
              <p className="text-lg text-muted-foreground max-w-2xl mx-auto">
                The same technology that powers Firefox's PDF viewer—trusted by millions worldwide. 
                Consistent, reliable rendering on every browser and device.
              </p>
            </div>
            <div className="grid md:grid-cols-3 gap-6">
              {viewerFeatures.map((feature, index) => (
                <div 
                  key={feature.title}
                  className="bg-card rounded-2xl p-6 border border-border text-center animate-fade-in"
                  style={{ animationDelay: `${index * 0.1}s` }}
                >
                  <div className="w-14 h-14 rounded-xl gradient-hero flex items-center justify-center mx-auto mb-4">
                    <feature.icon className="w-7 h-7 text-primary-foreground" aria-hidden="true" />
                  </div>
                  <h3 className="font-semibold text-lg mb-2">{feature.title}</h3>
                  <p className="text-muted-foreground text-sm">{feature.description}</p>
                </div>
              ))}
            </div>
          </div>
        </div>
      </section>

      {/* Theme Options */}
      <section className="py-16 lg:py-24 bg-card" aria-labelledby="themes-heading">
        <div className="container mx-auto px-4 lg:px-8">
          <div className="max-w-4xl mx-auto">
            <h2 id="themes-heading" className="text-3xl font-bold mb-8 text-center">
              Light & Dark Viewer Themes
            </h2>
            <div className="grid md:grid-cols-2 gap-6">
              {themeOptions.map((theme, index) => (
                <div 
                  key={theme.title}
                  className="bg-background rounded-2xl p-8 border border-border animate-fade-in"
                  style={{ animationDelay: `${index * 0.1}s` }}
                >
                    <div className="flex items-center gap-4 mb-4">
                      <div className="w-12 h-12 rounded-xl gradient-hero flex items-center justify-center">
                        <theme.icon className="w-6 h-6 text-primary-foreground" aria-hidden="true" />
                      </div>
                      <h3 className="text-xl font-semibold">{theme.title}</h3>
                    </div>
                    <p className="text-muted-foreground">{theme.description}</p>
                </div>
              ))}
            </div>
          </div>
        </div>
      </section>

      {/* Feature Comparison Table */}
      <WordpressComparisonTable />

      {/* Additional Features */}
      <section className="py-16 lg:py-24" aria-labelledby="extra-features-heading">
        <div className="container mx-auto px-4 lg:px-8">
          <div className="max-w-4xl mx-auto">
            <h2 id="extra-features-heading" className="text-3xl font-bold mb-12 text-center">
              More Than Just a PDF Viewer
            </h2>
            <div className="grid sm:grid-cols-3 gap-6">
              <div className="bg-card rounded-2xl p-6 border border-border text-center">
                <div className="w-12 h-12 rounded-xl gradient-hero flex items-center justify-center mx-auto mb-4">
                  <Shield className="w-6 h-6 text-primary-foreground" aria-hidden="true" />
                </div>
                <h3 className="font-semibold mb-2">Content Protection</h3>
                <p className="text-muted-foreground text-sm">
                  Control print and download options per PDF. Hide direct file URLs.
                </p>
              </div>
              <div className="bg-card rounded-2xl p-6 border border-border text-center">
                <div className="w-12 h-12 rounded-xl gradient-hero flex items-center justify-center mx-auto mb-4">
                  <Globe className="w-6 h-6 text-primary-foreground" aria-hidden="true" />
                </div>
                <h3 className="font-semibold mb-2">SEO & GEO Ready</h3>
                <p className="text-muted-foreground text-sm">
                  Optimized for Google and AI tools like ChatGPT with structured data.
                </p>
              </div>
              <div className="bg-card rounded-2xl p-6 border border-border text-center">
                <div className="w-12 h-12 rounded-xl gradient-hero flex items-center justify-center mx-auto mb-4">
                  <Zap className="w-6 h-6 text-primary-foreground" aria-hidden="true" />
                </div>
                <h3 className="font-semibold mb-2">Fast Loading</h3>
                <p className="text-muted-foreground text-sm">
                  AJAX loading keeps your pages fast. PDFs load only when viewed.
                </p>
              </div>
            </div>
          </div>
        </div>
      </section>

      {/* CTA */}
      <section className="py-16 lg:py-24 bg-card" aria-labelledby="wp-cta-heading">
        <div className="container mx-auto px-4 lg:px-8">
          <div className="max-w-3xl mx-auto text-center">
            <h2 id="wp-cta-heading" className="text-3xl font-bold mb-6">
              Start Using the Best WordPress PDF Plugin Today
            </h2>
            <p className="text-lg text-muted-foreground mb-8">
              Join thousands of WordPress users who display PDFs professionally. 
              Free forever, no restrictions, no credit card required.
            </p>
            <div className="flex flex-col sm:flex-row items-center justify-center gap-4 mb-8">
              <Button size="lg" className="gradient-hero shadow-glow text-lg px-8 py-6" asChild>
              <a
                  href="https://wordpress.org/plugins/pdf-embed-seo-optimize"
                  target="_blank"
                  rel="noopener noreferrer"
                  className="gap-2"
                  title="Download the free WordPress PDF plugin from WordPress.org"
                  aria-label="Get the free WordPress PDF plugin (opens in new tab)"
                >
                  <Download className="w-5 h-5" aria-hidden="true" />
                  Get Free Plugin
                </a>
              </Button>
              <Button size="lg" variant="outline" className="text-lg px-8 py-6" asChild>
                <a href="/pro/" className="gap-2" title="Explore Pro version features" aria-label="See Pro version features and pricing">
                  See Pro Features
                  <ArrowRight className="w-5 h-5" aria-hidden="true" />
                </a>
              </Button>
            </div>
            <p className="text-muted-foreground">
              Using Drupal instead?{" "}
              <a href="/drupal-pdf-viewer/" className="text-foreground hover:underline font-semibold" title="Get the Drupal PDF Viewer module" aria-label="Switch to Drupal PDF Viewer module page">
                Get the Drupal PDF Viewer module →
              </a>
              {" | "}
              Using React/Next.js?{" "}
              <a href="/nextjs-pdf-viewer/" className="text-foreground hover:underline font-semibold" title="Get the React/Next.js PDF Viewer components" aria-label="Get the React/Next.js PDF Viewer components">
                Get the React components →
              </a>
            </p>
          </div>
        </div>
      </section>
    </Layout>
  );
};

export default WordpressPdfViewer;
