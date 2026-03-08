import { Download, ArrowRight } from "lucide-react";
import { Button } from "@/components/ui/button";

export function CTA() {
  return (
    <section className="py-20 lg:py-32 relative overflow-hidden" aria-labelledby="cta-heading">
      {/* Background - decorative */}
      <div className="absolute inset-0 gradient-hero opacity-95" aria-hidden="true" />
      <div className="absolute top-0 left-0 w-96 h-96 bg-accent/30 rounded-full blur-3xl" aria-hidden="true" />
      <div className="absolute bottom-0 right-0 w-96 h-96 bg-primary-foreground/10 rounded-full blur-3xl" aria-hidden="true" />

      <div className="container mx-auto px-4 lg:px-8 relative">
        <div className="max-w-3xl mx-auto text-center">
          <h2 id="cta-heading" className="text-3xl md:text-4xl lg:text-5xl font-bold text-primary-foreground mb-6">
            Ready to Get Your PDFs Found?
          </h2>
          <p className="text-xl text-primary-foreground/80 mb-10">
            Join thousands of businesses using our plugin to share documents professionally. 
            Available for WordPress and Drupal — completely free and takes just minutes to set up.
          </p>

          <div className="flex flex-col sm:flex-row items-center justify-center gap-4 mb-12 w-full max-w-lg mx-auto">
            <Button
              size="lg"
              className="gradient-accent shadow-accent-glow text-lg px-8 py-6 w-full sm:w-auto sm:min-w-[220px]"
              asChild
            >
              <a
                href="https://wordpress.org/plugins/pdf-embed-seo-optimize"
                target="_blank"
                rel="noopener noreferrer"
                className="gap-2"
                title="Download the free PDF Embed plugin from WordPress.org"
                aria-label="Download PDF Embed & SEO Optimize free plugin (opens in new tab)"
              >
                <Download className="w-5 h-5" aria-hidden="true" />
                Download Free Plugin
              </a>
            </Button>
            <Button
              size="lg"
              variant="outline"
              className="text-lg px-8 py-6 border-primary-foreground/30 text-primary-foreground hover:bg-primary-foreground/10 w-full sm:w-auto sm:min-w-[220px]"
              asChild
            >
              <a
                href="/examples/"
                className="gap-2"
                title="See live examples of PDF embedding in action"
                aria-label="View live examples of embedded PDFs"
              >
                View Examples
                <ArrowRight className="w-5 h-5" aria-hidden="true" />
              </a>
            </Button>
          </div>

          <a
            href="/documentation/"
            className="inline-flex items-center gap-2 text-primary-foreground/80 hover:text-primary-foreground transition-colors"
            title="Read the complete PDF Embed plugin documentation"
            aria-label="Read the plugin documentation"
          >
            Read the documentation
            <ArrowRight className="w-4 h-4" aria-hidden="true" />
          </a>
        </div>
      </div>
    </section>
  );
}
