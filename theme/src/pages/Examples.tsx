import { Layout } from "@/components/layout/Layout";
import { SEOHead } from "@/components/SEOHead";
import { FileText, ExternalLink, ArrowRight, Check, X, Link2, Lock } from "lucide-react";
import { Button } from "@/components/ui/button";
import { useEffect } from "react";

const examples = [
  {
    title: "In-Page PDF with Download & Print",
    description: "PDF renders directly on the page with download and print buttons enabled for user convenience.",
    url: "https://pdfviewermodule.com/pdf/example-1/",
    features: ["Embedded viewer", "Download button", "Print button"],
  },
  {
    title: "In-Page PDF without Download/Print",
    description: "PDF renders on the page with download and print functionality disabled for protected content.",
    url: "https://pdfviewermodule.com/pdf/example-2/",
    features: ["Embedded viewer", "No download", "View-only mode"],
  },
  {
    title: "Standalone PDF with Download & Print",
    description: "PDF opens in a new tab as a standalone viewer with full download and print capabilities.",
    url: "https://pdfviewermodule.com/pdf/example-3/",
    features: ["Opens in new tab", "Download button", "Print button"],
  },
  {
    title: "Standalone PDF without Download/Print",
    description: "PDF opens in a new tab as a standalone viewer with download and print disabled.",
    url: "https://pdfviewermodule.com/pdf/example-4/",
    features: ["Opens in new tab", "No download", "View-only mode"],
  },
  {
    title: "In-Page PDF with Password Protection",
    description: "PDF renders directly on the page but requires a password to view. Password: PDF-Reader-Test",
    url: "https://pdfviewermodule.com/pdf/example-5/",
    features: ["Embedded viewer", "Password required", "Secure access"],
    icon: Lock,
  },
  {
    title: "Standalone PDF with Password Protection",
    description: "PDF opens in a new tab as a standalone viewer with password protection. Password: PDF-Reader-Test",
    url: "https://pdfviewermodule.com/pdf/example-6/",
    features: ["Opens in new tab", "Password required", "Secure access"],
    icon: Lock,
  },
  {
    title: "HTML Sitemap – List View",
    description: "Auto-generated archive page displaying all PDFs in a clean, scannable list format.",
    url: "https://pdfviewermodule.com/pdf/",
    features: ["List layout", "Auto-generated", "SEO-friendly"],
  },
  {
    title: "HTML Sitemap – Grid View",
    description: "Visual archive page with PDF thumbnails arranged in a responsive grid layout.",
    url: "https://pdfviewermodule.com/pdf-grid/",
    features: ["Grid layout", "Thumbnails", "Visual browsing"],
  },
];

const scrollToHash = () => {
  const hash = window.location.hash;
  if (hash) {
    const element = document.querySelector(hash);
    if (element) {
      setTimeout(() => {
        element.scrollIntoView({ behavior: "smooth", block: "start" });
      }, 100);
    }
  }
};

const updateHash = (id: string) => {
  window.history.pushState(null, "", `#${id}`);
};

const Examples = () => {
  useEffect(() => {
    scrollToHash();
  }, []);

  return (
    <Layout>
      <SEOHead
        title="Live Examples – See PDF Embed & SEO Optimize in Action"
        description="View live examples of the PDF Embed & SEO Optimize WordPress plugin. See clean URLs, professional PDF display, and SEO-optimized document pages."
        canonicalPath="/examples/"
      />
      
      {/* Hero */}
      <section className="py-16 lg:py-24 bg-card" aria-labelledby="examples-heading">
        <div className="container mx-auto px-4 lg:px-8">
          <div className="max-w-3xl">
            <div className="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-300 text-sm font-medium mb-6">
              <FileText className="w-4 h-4" aria-hidden="true" />
              <span>Examples</span>
            </div>
            <h1 id="examples-heading" className="text-4xl md:text-5xl font-bold mb-6">
              See It In Action
            </h1>
            <p className="text-xl text-muted-foreground">
              See how your documents look with clean, professional links that 
              customers can easily find and share.
            </p>
          </div>
        </div>
      </section>

      {/* URL Structure Comparison */}
      <section id="url-comparison" className="py-16 lg:py-24 scroll-mt-24">
        <div className="container mx-auto px-4 lg:px-8">
          <div className="max-w-4xl mx-auto">
            <h2 
              className="text-2xl font-bold mb-4 text-center cursor-pointer hover:text-primary transition-colors"
              onClick={() => updateHash("url-comparison")}
            >
              <a href="#url-comparison" className="hover:underline" title="Jump to URL comparison section" aria-label="Before and After URL Comparison">Before & After Comparison</a>
            </h2>
            <p className="text-muted-foreground text-center mb-8 max-w-2xl mx-auto">
              Compare how your PDF links look with standard WordPress versus our plugin. 
              Clean, professional URLs make a big difference for both users and search engines.
            </p>
            
            <div className="grid md:grid-cols-2 gap-6 mb-12">
              {/* Without Plugin */}
              <article className="bg-card rounded-2xl border border-destructive/20 p-6 animate-fade-in" aria-label="Standard WordPress URL example showing messy upload paths">
                <div className="flex items-center gap-2 mb-4">
                  <div className="w-8 h-8 rounded-lg bg-destructive/10 flex items-center justify-center">
                    <X className="w-4 h-4 text-destructive" />
                  </div>
                  <span className="font-semibold">Standard WordPress</span>
                </div>
                <div className="bg-destructive/5 rounded-lg p-4 mb-4">
                  <a 
                    href="https://pdfviewermodule.com/wp-content/uploads/2025/03/example-1.pdf" 
                    target="_blank" 
                    rel="noopener noreferrer"
                    className="text-sm text-muted-foreground break-all hover:text-destructive hover:underline transition-colors"
                    title="View example of standard WordPress PDF URL (opens in new tab)"
                    aria-label="Example standard WordPress PDF upload URL - opens in new tab"
                  >
                    <code>domain.com/wp-content/uploads/2025/03/example-1.pdf</code>
                  </a>
                </div>
                <ul className="space-y-2 text-sm text-muted-foreground">
                  <li className="flex items-start gap-2">
                    <X className="w-4 h-4 text-destructive shrink-0 mt-0.5" />
                    Messy, confusing link
                  </li>
                  <li className="flex items-start gap-2">
                    <X className="w-4 h-4 text-destructive shrink-0 mt-0.5" />
                    Hard to find on Google
                  </li>
                  <li className="flex items-start gap-2">
                    <X className="w-4 h-4 text-destructive shrink-0 mt-0.5" />
                    No way to track who reads it
                  </li>
                </ul>
              </article>

              {/* With Plugin */}
              <article className="bg-card rounded-2xl border border-primary/20 p-6 animate-fade-in" style={{ animationDelay: "0.1s" }} aria-label="With Our Plugin URL example showing clean professional paths">
                <div className="flex items-center gap-2 mb-4">
                  <div className="w-8 h-8 rounded-lg bg-primary/10 flex items-center justify-center">
                    <Check className="w-4 h-4 text-primary" />
                  </div>
                  <span className="font-semibold">With Our Plugin</span>
                </div>
                <div className="bg-primary/5 rounded-lg p-4 mb-4">
                  <a 
                    href="https://pdfviewermodule.com/pdf/example-1/" 
                    target="_blank" 
                    rel="noopener noreferrer"
                    className="text-sm text-primary font-semibold break-all hover:underline transition-colors"
                    title="View example of clean PDF URL with our plugin (opens in new tab)"
                    aria-label="Example clean PDF URL with our plugin - opens in new tab"
                  >
                    <code>domain.com/pdf/product-brochure/</code>
                  </a>
                </div>
                <ul className="space-y-2 text-sm text-muted-foreground">
                  <li className="flex items-start gap-2">
                    <Check className="w-4 h-4 text-primary shrink-0 mt-0.5" />
                    Clean, professional link
                  </li>
                  <li className="flex items-start gap-2">
                    <Check className="w-4 h-4 text-primary shrink-0 mt-0.5" />
                    Shows up in Google searches
                  </li>
                  <li className="flex items-start gap-2">
                    <Check className="w-4 h-4 text-primary shrink-0 mt-0.5" />
                    Track views and sharing
                  </li>
                </ul>
              </article>
            </div>

            {/* URL Structure */}
            <article id="url-structure" className="bg-card rounded-2xl border border-border p-8 mb-12 animate-fade-in scroll-mt-24" style={{ animationDelay: "0.2s" }} aria-label="URL structure examples showing how your links will look">
              <div className="flex items-center gap-3 mb-4">
                <Link2 className="w-5 h-5 text-primary" />
                <h3 
                  className="text-lg font-semibold cursor-pointer hover:text-primary transition-colors"
                  onClick={() => updateHash("url-structure")}
                >
                  <a href="#url-structure" className="hover:underline" title="Jump to URL structure section" aria-label="How Your Links Will Look">How Your Links Will Look</a>
                </h3>
              </div>
              <p className="text-muted-foreground mb-6">
                Your PDFs get clean, memorable URLs that are easy to share and look professional in emails, social media, and print materials.
              </p>
              <div className="space-y-4">
                <div className="flex flex-col sm:flex-row sm:items-center gap-2 sm:gap-4">
                  <code className="bg-muted px-4 py-2 rounded-lg font-mono text-sm">
                    yourdomain.com/pdf/
                  </code>
                  <ArrowRight className="w-4 h-4 text-muted-foreground hidden sm:block" />
                  <span className="text-muted-foreground text-sm">
                    A page listing all your PDF documents
                  </span>
                </div>
                <div className="flex flex-col sm:flex-row sm:items-center gap-2 sm:gap-4">
                  <code className="bg-muted px-4 py-2 rounded-lg font-mono text-sm">
                    yourdomain.com/pdf/your-document-name/
                  </code>
                  <ArrowRight className="w-4 h-4 text-muted-foreground hidden sm:block" />
                  <span className="text-muted-foreground text-sm">
                    Each PDF gets its own professional page
                  </span>
                </div>
                <div className="flex flex-col sm:flex-row sm:items-center gap-2 sm:gap-4">
                  <code className="bg-muted px-4 py-2 rounded-lg font-mono text-sm text-foreground font-semibold">
                    yourdomain.com/pdf/sitemap.xml
                  </code>
                  <ArrowRight className="w-4 h-4 text-muted-foreground hidden sm:block" />
                  <span className="text-muted-foreground text-sm">
                    Automatic sitemap for Google to find all your PDFs
                  </span>
                </div>
              </div>
              <div className="mt-6 pt-6 border-t border-border">
                <p className="text-sm text-muted-foreground">
                  <strong className="text-foreground">Bonus:</strong> The plugin automatically creates an XML sitemap 
                  just for your PDFs, helping search engines discover and index all your documents.
                </p>
              </div>
            </article>
          </div>
        </div>
      </section>

      {/* Example PDFs */}
      <section id="live-examples" className="py-16 lg:py-24 bg-card scroll-mt-24" aria-labelledby="live-examples-heading">
        <div className="container mx-auto px-4 lg:px-8">
          <div className="max-w-4xl mx-auto">
            <h2 
              id="live-examples-heading" 
              className="text-2xl font-bold mb-4 text-center cursor-pointer hover:text-primary transition-colors"
              onClick={() => updateHash("live-examples")}
            >
              <a href="#live-examples" className="hover:underline" title="Jump to live examples section" aria-label="Try These Live Examples">Try These Live Examples</a>
            </h2>
            <p className="text-muted-foreground text-center mb-8 max-w-2xl mx-auto">
              Click on any example below to see exactly how your PDFs will look and function. 
              Each demo showcases different configuration options available in the plugin.
            </p>
            
            <div className="grid gap-6 md:grid-cols-2">
              {examples.map((example, index) => {
                const IconComponent = example.icon || FileText;
                return (
                  <article
                    key={example.url}
                    className="group bg-background rounded-xl border border-border hover:border-primary/30 hover:shadow-medium transition-all duration-300 animate-fade-in overflow-hidden flex flex-col"
                    style={{ animationDelay: `${index * 0.1}s` }}
                    aria-label={`${example.title}: ${example.description}`}
                  >
                    <div className="p-6 flex flex-col flex-1">
                      <div className="flex items-start gap-4 mb-4">
                        <div className={`w-12 h-12 rounded-xl flex items-center justify-center group-hover:shadow-glow transition-shadow flex-shrink-0 ${example.icon === Lock ? 'bg-amber-500' : 'gradient-hero'}`}>
                          <IconComponent className="w-6 h-6 text-primary-foreground" aria-hidden="true" />
                        </div>
                        <div className="flex-1 min-w-0">
                          <h3 className="font-semibold text-lg mb-1 group-hover:text-primary transition-colors">
                            {example.title}
                          </h3>
                          <p className="text-sm text-muted-foreground">
                            {example.description}
                          </p>
                        </div>
                      </div>
                      
                      <div className="flex flex-wrap gap-2 mb-4">
                        {example.features.map((feature) => (
                          <span
                            key={feature}
                            className="inline-flex items-center px-2.5 py-1 rounded-full bg-primary/10 text-primary text-xs font-medium"
                          >
                            {feature}
                          </span>
                        ))}
                      </div>
                      
                      <Button className="w-full mt-auto" variant="outline" asChild>
                        <a
                          href={example.url}
                          target="_blank"
                          rel="noopener noreferrer"
                          className="gap-2"
                          title={`View ${example.title} demo (opens in new tab)`}
                          aria-label={`View ${example.title} example - opens in new tab`}
                        >
                          View Example
                          <ExternalLink className="w-4 h-4" aria-hidden="true" />
                        </a>
                      </Button>
                    </div>
                  </article>
                );
              })}
            </div>
          </div>
        </div>
      </section>
    </Layout>
  );
};

export default Examples;
