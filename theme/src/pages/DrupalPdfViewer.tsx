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
  Terminal
} from "lucide-react";
import { DrupalComparisonTable } from "@/components/drupal/DrupalComparisonTable";

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
    description: "Clean, bright interface that matches light Drupal themes."
  },
  {
    icon: Moon,
    title: "Dark Theme",
    description: "Easy on the eyes for sites with dark mode enabled."
  }
];

const requirements = [
  "Drupal 10 or 11",
  "PHP 8.1 or higher",
  "Optional: ImageMagick or Ghostscript (for auto thumbnails)"
];

const pdfViewerSchema = {
  "@context": "https://schema.org",
  "@type": "SoftwareApplication",
  "name": "Drupal PDF Viewer Module",
  "alternateName": "PDF Embed & SEO Optimize for Drupal",
  "applicationCategory": "Drupal Module",
  "applicationSubCategory": "PDF Viewer",
  "operatingSystem": "Drupal 10+, Drupal 11+",
  "softwareVersion": "1.2.11",
  "offers": {
    "@type": "Offer",
    "price": "0",
    "priceCurrency": "EUR"
  },
  "description": "The best free Drupal PDF viewer module, reviewed by Acquia. Display PDFs beautifully with Mozilla PDF.js, SEO optimization, clean URLs, and view analytics. Works with any Drupal theme.",
  "featureList": [
    "Mozilla PDF.js integration (v4.0)",
    "Cross-browser compatible",
    "Mobile responsive viewer",
    "Light and dark themes",
    "SEO-optimized URLs",
    "JSON-LD schema markup",
    "View statistics",
    "Print and download controls",
    "Drupal block support",
    "Multi-language support",
    "Media Library integration",
    "Works with any Drupal theme"
  ],
  "url": [
    "https://pdfviewermodule.com/drupal-pdf-viewer/",
    "https://pdfviewer.drossmedia.de/drupal-pdf-viewer/"
  ],
  "downloadUrl": "https://www.drupal.org/project/pdf-embed-seo-optimize",
  "softwareRequirements": "Drupal 10+, PHP 8.1+"
};

const DrupalPdfViewer = () => {
  useJsonLd("drupal-pdf-viewer-schema", pdfViewerSchema);

  return (
    <Layout>
      <SEOHead
        title="Best Drupal PDF Viewer Module – Free, SEO-Optimized, Mobile Ready"
        description="Display PDFs beautifully on your Drupal site with the best free PDF viewer module. Mozilla PDF.js integration, mobile responsive, SEO optimized with clean URLs and schema markup."
        canonicalPath="/drupal-pdf-viewer/"
      />

      {/* Hero */}
      <section className="py-16 lg:py-24 bg-card" aria-labelledby="viewer-hero-heading">
        <div className="container mx-auto px-4 lg:px-8">
          <div className="max-w-4xl mx-auto text-center">
            <div className="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-primary/20 text-foreground text-sm font-medium mb-6">
              <FileText className="w-4 h-4" aria-hidden="true" />
              <span>Drupal PDF Viewer</span>
            </div>
            <h1 id="viewer-hero-heading" className="text-4xl md:text-5xl lg:text-6xl font-extrabold tracking-tight mb-6">
              The Best <span className="text-gradient">Drupal PDF Viewer</span> Module
            </h1>
            <p className="text-xl md:text-2xl text-muted-foreground max-w-3xl mx-auto mb-6">
              Display PDFs beautifully on any device with Mozilla's trusted PDF.js technology.
              Free, SEO-optimized, and works with every Drupal theme.
            </p>
            <div className="flex flex-wrap items-center justify-center gap-3 mb-10">
              <a href="https://www.acquia.com" target="_blank" rel="noopener noreferrer" className="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-card border border-border text-sm hover:border-primary/30 transition-colors" title="Visit Acquia — the leading Drupal enterprise platform">
                <Shield className="w-4 h-4 text-primary" aria-hidden="true" />
                <span className="font-medium text-foreground">Reviewed by Acquia</span>
              </a>
            </div>
            <div className="flex flex-col sm:flex-row items-center justify-center gap-4">
              <Button size="lg" className="gradient-hero shadow-glow text-lg px-8 py-6" asChild>
                <a
                  href="https://www.drupal.org/project/pdf-embed-seo-optimize"
                  target="_blank"
                  rel="noopener noreferrer"
                  className="gap-2"
                  title="Download the free Drupal PDF Viewer module from Drupal.org"
                  aria-label="Download free Drupal PDF Viewer module (opens in new tab)"
                >
                  <Download className="w-5 h-5" aria-hidden="true" />
                  Download Free Module
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

      {/* Requirements & Installation */}
      <section className="py-16 lg:py-24" aria-labelledby="requirements-heading">
        <div className="container mx-auto px-4 lg:px-8">
          <div className="max-w-4xl mx-auto">
            <div className="grid md:grid-cols-2 gap-8">
              {/* Requirements */}
              <div className="bg-card rounded-2xl p-6 border border-border">
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
              <div className="bg-card rounded-2xl p-6 border border-border">
                <h2 className="text-xl font-bold mb-4">Installation</h2>
                <div className="space-y-4">
                  <div>
                    <p className="text-sm font-medium text-foreground mb-2 flex items-center gap-2">
                      <Terminal className="w-4 h-4" aria-hidden="true" />
                      Via Composer (recommended):
                    </p>
                    <div className="bg-muted rounded-lg p-3">
                      <code className="text-sm text-primary font-mono">
                        composer require drossmedia/pdf_embed_seo
                      </code>
                    </div>
                    <div className="bg-muted rounded-lg p-3 mt-2">
                      <code className="text-sm text-primary font-mono">
                        drush en pdf_embed_seo
                      </code>
                    </div>
                  </div>
                  <div>
                    <p className="text-sm font-medium text-foreground mb-2">Manual Installation:</p>
                    <ol className="text-sm text-muted-foreground space-y-1 list-decimal list-inside">
                      <li>Download and extract to /modules/contrib/pdf_embed_seo</li>
                      <li>Enable via Admin {">"} Extend</li>
                      <li>Configure at Admin {">"} Configuration {">"} Content {">"} PDF Embed & SEO</li>
                    </ol>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>

      {/* PDF.js Section */}
      <section className="py-16 lg:py-24 bg-card" aria-labelledby="pdfjs-heading">
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
                  className="bg-background rounded-2xl p-6 border border-border text-center animate-fade-in"
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
      <section className="py-16 lg:py-24" aria-labelledby="themes-heading">
        <div className="container mx-auto px-4 lg:px-8">
          <div className="max-w-4xl mx-auto">
            <h2 id="themes-heading" className="text-3xl font-bold mb-8 text-center">
              Light & Dark Viewer Themes
            </h2>
            <div className="grid md:grid-cols-2 gap-6">
              {themeOptions.map((theme, index) => (
                <div 
                  key={theme.title}
                  className="bg-card rounded-2xl p-8 border border-border animate-fade-in"
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
      <DrupalComparisonTable />

      {/* Additional Features */}
      <section className="py-16 lg:py-24" aria-labelledby="drupal-extra-features-heading">
        <div className="container mx-auto px-4 lg:px-8">
          <div className="max-w-4xl mx-auto">
            <h2 id="drupal-extra-features-heading" className="text-3xl font-bold mb-12 text-center">
              More Than Just a PDF Viewer
            </h2>
            <div className="grid sm:grid-cols-3 gap-6">
              <article className="bg-card rounded-2xl p-6 border border-border text-center">
                <div className="w-12 h-12 rounded-xl gradient-hero flex items-center justify-center mx-auto mb-4" aria-hidden="true">
                  <Shield className="w-6 h-6 text-primary-foreground" />
                </div>
                <h3 className="font-semibold mb-2">Content Protection</h3>
                <p className="text-muted-foreground text-sm">
                  Control print and download options per PDF. Hide direct file URLs.
                </p>
              </article>
              <article className="bg-card rounded-2xl p-6 border border-border text-center">
                <div className="w-12 h-12 rounded-xl gradient-hero flex items-center justify-center mx-auto mb-4" aria-hidden="true">
                  <Globe className="w-6 h-6 text-primary-foreground" />
                </div>
                <h3 className="font-semibold mb-2">SEO & GEO Ready</h3>
                <p className="text-muted-foreground text-sm">
                  Optimized for Google and AI tools like ChatGPT with structured data.
                </p>
              </article>
              <article className="bg-card rounded-2xl p-6 border border-border text-center">
                <div className="w-12 h-12 rounded-xl gradient-hero flex items-center justify-center mx-auto mb-4" aria-hidden="true">
                  <Zap className="w-6 h-6 text-primary-foreground" />
                </div>
                <h3 className="font-semibold mb-2">Fast Loading</h3>
                <p className="text-muted-foreground text-sm">
                  AJAX loading keeps your pages fast. PDFs load only when viewed.
                </p>
              </article>
            </div>
          </div>
        </div>
      </section>

      {/* CTA */}
      <section className="py-16 lg:py-24 bg-card" aria-labelledby="drupal-cta-heading">
        <div className="container mx-auto px-4 lg:px-8">
          <div className="max-w-3xl mx-auto text-center">
            <h2 id="drupal-cta-heading" className="text-3xl font-bold mb-6">
              Try the Best Drupal PDF Viewer Today
            </h2>
            <p className="text-lg text-muted-foreground mb-8">
              Join thousands of Drupal users who display PDFs professionally. 
              Free forever, no restrictions, no credit card required.
            </p>
            <div className="flex flex-col sm:flex-row items-center justify-center gap-4 mb-8">
              <Button size="lg" className="gradient-hero shadow-glow text-lg px-8 py-6" asChild>
                <a
                  href="https://www.drupal.org/project/pdf-embed-seo-optimize"
                  target="_blank"
                  rel="noopener noreferrer"
                  className="gap-2"
                  title="Download the free Drupal PDF module from Drupal.org"
                  aria-label="Get free Drupal PDF module (opens in new tab)"
                >
                  <Download className="w-5 h-5" aria-hidden="true" />
                  Get Free Module
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
              Using WordPress instead?{" "}
              <a href="/wordpress-pdf-viewer/" className="text-foreground hover:underline font-semibold" title="Get the WordPress PDF Viewer plugin" aria-label="Get the WordPress PDF Viewer plugin">
                Get the WordPress PDF Viewer plugin →
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

export default DrupalPdfViewer;
