import { ArrowRight, FileText, Search, Zap, Download } from "lucide-react";
import { Button } from "@/components/ui/button";

export function Hero() {
  return (
    <section className="relative overflow-hidden" aria-labelledby="hero-heading">
      {/* Background Elements - decorative */}
      <div className="absolute inset-0 gradient-hero opacity-5" aria-hidden="true" />
      <div className="absolute top-20 left-10 w-72 h-72 bg-primary/20 rounded-full blur-3xl" aria-hidden="true" />
      <div className="absolute bottom-20 right-10 w-96 h-96 bg-accent/20 rounded-full blur-3xl" aria-hidden="true" />
      
      <div className="container mx-auto px-4 py-24 lg:py-32 relative">
        <div className="max-w-4xl mx-auto text-center">
          {/* Badge */}
          <div className="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-primary/10 text-primary text-sm font-medium mb-8 animate-fade-in">
            <Zap className="w-4 h-4" aria-hidden="true" />
            <span>Free Plugin for WordPress & Drupal</span>
          </div>

          {/* Headline - H1 for SEO */}
          <h1 id="hero-heading" className="text-4xl md:text-5xl lg:text-6xl font-extrabold tracking-tight mb-6 animate-fade-in" style={{ animationDelay: "0.1s" }}>
            Share PDFs That <span className="text-gradient">Get Found</span>
          </h1>

          {/* Subheadline */}
          <p className="text-xl md:text-2xl text-muted-foreground max-w-2xl mx-auto mb-10 animate-fade-in" style={{ animationDelay: "0.2s" }}>
            Help customers find your documents on Google and AI tools like ChatGPT. 
            Display PDFs beautifully on any device and track who's reading them.
          </p>

          {/* CTAs */}
          <div className="flex flex-col items-center gap-4 mb-16 animate-fade-in" style={{ animationDelay: "0.3s" }}>
            <div className="flex flex-col items-center justify-center gap-4 w-full max-w-md">
              <Button size="lg" className="gradient-hero shadow-glow text-lg px-8 py-6 w-full" asChild>
                <a
                  href="https://wordpress.org/plugins/pdf-embed-seo-optimize"
                  target="_blank"
                  rel="noopener noreferrer"
                  className="gap-2 w-full justify-center"
                  title="Download the free PDF Embed & SEO Optimize plugin from WordPress.org"
                  aria-label="Download PDF Embed & SEO Optimize for WordPress (opens in new tab)"
                >
                  <Download className="w-5 h-5" aria-hidden="true" />
                  Download for WordPress
                </a>
              </Button>
              <Button size="lg" variant="outline" className="text-lg px-8 py-6 w-full" asChild>
                <a
                  href="/drupal-pdf-viewer/"
                  className="gap-2 w-full justify-center"
                  title="Get the PDF Embed module for Drupal"
                  aria-label="Get PDF Embed & SEO Optimize for Drupal"
                >
                  <Download className="w-5 h-5" aria-hidden="true" />
                  Get Drupal Module
                </a>
              </Button>
              <Button size="lg" variant="outline" className="text-lg px-8 py-6 w-full font-mono text-base" asChild>
                <a
                  href="/nextjs-pdf-viewer/"
                  className="gap-2 w-full justify-center"
                  title="Install the PDF Embed React/Next.js package via npm"
                  aria-label="View React and Next.js PDF viewer installation"
                >
                  <span aria-hidden="true">&gt;_</span>
                  npm install @pdf-embed-seo/react
                </a>
              </Button>
            </div>
            <a
              href="/examples/"
              className="inline-flex items-center gap-2 text-muted-foreground hover:text-primary transition-colors"
              title="See live examples of PDF embedding in action"
              aria-label="View live examples of embedded PDFs"
            >
              View Live Examples
              <ArrowRight className="w-4 h-4" aria-hidden="true" />
            </a>
          </div>

          {/* Feature Pills - semantic list */}
          <ul className="flex flex-wrap items-center justify-center gap-4 animate-fade-in list-none" style={{ animationDelay: "0.4s" }} aria-label="Key features">
            <li className="flex items-center gap-2 px-4 py-2 bg-card rounded-full shadow-soft">
              <FileText className="w-4 h-4 text-primary" aria-hidden="true" />
              <span className="text-sm font-medium">Beautiful Viewer</span>
            </li>
            <li className="flex items-center gap-2 px-4 py-2 bg-card rounded-full shadow-soft">
              <Search className="w-4 h-4 text-primary" aria-hidden="true" />
              <span className="text-sm font-medium">SEO & GEO Ready</span>
            </li>
            <li className="flex items-center gap-2 px-4 py-2 bg-card rounded-full shadow-soft">
              <Zap className="w-4 h-4 text-accent" aria-hidden="true" />
              <span className="text-sm font-medium">Found by AI & LLMs</span>
            </li>
          </ul>
        </div>

        {/* Browser Mockup - decorative illustration */}
        <figure className="max-w-5xl mx-auto mt-16 animate-fade-in-up" style={{ animationDelay: "0.5s" }} aria-label="PDF Viewer preview illustration">
          <div className="relative rounded-2xl overflow-hidden shadow-large bg-card border border-border" role="img" aria-label="Browser window showing PDF Viewer with clean URL structure">
            {/* Browser Chrome - decorative */}
            <div className="flex items-center gap-2 px-4 py-3 bg-muted border-b border-border" aria-hidden="true">
              <div className="flex gap-2">
                <div className="w-3 h-3 rounded-full bg-destructive/60" />
                <div className="w-3 h-3 rounded-full bg-accent/60" />
                <div className="w-3 h-3 rounded-full bg-primary/60" />
              </div>
              <div className="flex-1 mx-4">
                <div className="bg-background rounded-md px-4 py-1.5 text-sm text-muted-foreground text-center">
                  yourdomain.com/pdf/document-name/
                </div>
              </div>
            </div>
            
            {/* Content Preview */}
            <div className="aspect-[16/9] bg-gradient-to-br from-primary/5 to-accent/5 flex items-center justify-center">
              <div className="text-center">
                <div className="w-24 h-32 mx-auto mb-6 gradient-hero rounded-lg flex items-center justify-center shadow-glow animate-float">
                  <FileText className="w-12 h-12 text-primary-foreground" aria-hidden="true" />
                </div>
                <p className="text-lg font-semibold text-foreground">PDF Viewer Preview</p>
                <p className="text-sm text-muted-foreground">Beautiful, responsive PDF viewing experience</p>
              </div>
            </div>
          </div>
        </figure>
      </div>
    </section>
  );
}
