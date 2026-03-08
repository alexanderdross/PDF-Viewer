import { Globe, Search, FileText, ArrowRight } from "lucide-react";

export function XMLSitemap() {
  return (
    <section className="py-20 lg:py-32" aria-labelledby="sitemap-heading">
      <div className="container mx-auto px-4 lg:px-8">
        <div className="max-w-4xl mx-auto">
          <div className="grid lg:grid-cols-2 gap-12 items-center">
            {/* Content */}
            <div className="animate-fade-in">
              <div className="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-300 text-sm font-medium mb-6">
                <Globe className="w-4 h-4" aria-hidden="true" />
                <span>Automatic Sitemap</span>
              </div>
              <h2 id="sitemap-heading" className="text-3xl md:text-4xl font-bold mb-6">
                Let Google Find Your Documents
              </h2>
              <p className="text-lg text-muted-foreground mb-6">
                The plugin automatically creates a special sitemap just for your PDFs. 
                Submit it to Google Search Console and watch your documents appear in search results.
              </p>
              
              <ul className="space-y-4 list-none" role="list">
                <li className="flex items-start gap-3">
                  <div className="w-8 h-8 rounded-lg bg-primary/10 flex items-center justify-center shrink-0 mt-0.5">
                    <Search className="w-4 h-4 text-primary" aria-hidden="true" />
                  </div>
                  <div>
                    <h3 className="font-semibold mb-1">Faster Indexing</h3>
                    <p className="text-sm text-muted-foreground">
                      Google discovers your new PDFs automatically, without you having to do anything.
                    </p>
                  </div>
                </li>
                <li className="flex items-start gap-3">
                  <div className="w-8 h-8 rounded-lg bg-primary/10 flex items-center justify-center shrink-0 mt-0.5">
                    <FileText className="w-4 h-4 text-primary" aria-hidden="true" />
                  </div>
                  <div>
                    <h3 className="font-semibold mb-1">All Documents Included</h3>
                    <p className="text-sm text-muted-foreground">
                      Every PDF you publish is automatically added to the sitemap.
                    </p>
                  </div>
                </li>
              </ul>
            </div>

            {/* Visual */}
            <figure className="animate-fade-in" style={{ animationDelay: "0.2s" }} aria-label="XML Sitemap example">
              <div className="bg-card rounded-2xl border border-border p-6 shadow-soft">
                <div className="flex items-center gap-2 mb-4" aria-hidden="true">
                  <div className="flex gap-1.5">
                    <div className="w-3 h-3 rounded-full bg-destructive/60" />
                    <div className="w-3 h-3 rounded-full bg-accent/60" />
                    <div className="w-3 h-3 rounded-full bg-primary/60" />
                  </div>
                  <span className="text-xs text-muted-foreground ml-2">sitemap.xml</span>
                </div>
                
                <div className="bg-muted rounded-lg p-4 mb-4">
                  <p className="text-sm font-medium text-muted-foreground mb-2">Your PDF sitemap:</p>
                  <code className="text-sm text-primary font-semibold break-all">
                    yourdomain.com/pdf/sitemap.xml
                  </code>
                </div>

                <ul className="space-y-2 text-sm list-none" role="list">
                  <li className="flex items-center gap-2 text-muted-foreground">
                    <ArrowRight className="w-4 h-4 text-primary" aria-hidden="true" />
                    <span>Contains all your PDF pages</span>
                  </li>
                  <li className="flex items-center gap-2 text-muted-foreground">
                    <ArrowRight className="w-4 h-4 text-primary" aria-hidden="true" />
                    <span>Updates automatically</span>
                  </li>
                  <li className="flex items-center gap-2 text-muted-foreground">
                    <ArrowRight className="w-4 h-4 text-primary" aria-hidden="true" />
                    <span>Ready for Google Search Console</span>
                  </li>
                </ul>
              </div>
            </figure>
          </div>
        </div>
      </div>
    </section>
  );
}
