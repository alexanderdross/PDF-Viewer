import { Check, X } from "lucide-react";

const pluginComparisons = [
  {
    feature: "URL Structure",
    without: "Exposes file location (/uploads/doc.pdf)",
    with: "Clean, branded URLs (/pdf/document-name/)",
  },
  {
    feature: "SEO Value",
    without: "Search engines treat as file, no rankings",
    with: "Full SEO with Yoast integration, meta tags, schema markup",
  },
  {
    feature: "Social Sharing",
    without: "No preview image or description",
    with: "OpenGraph & Twitter Cards with auto-generated thumbnails",
  },
  {
    feature: "User Control",
    without: "No control over print/download",
    with: "Per-document print/download toggles",
  },
  {
    feature: "Analytics",
    without: "No way to track views",
    with: "Built-in view statistics tracking",
  },
  {
    feature: "Viewing Experience",
    without: "Users leave site to browser viewer",
    with: "Mozilla PDF.js viewer renders within your site",
  },
  {
    feature: "Mobile Experience",
    without: "Varies wildly by device/browser",
    with: "Responsive design works on all devices",
  },
  {
    feature: "Content Protection",
    without: "File URL easily shared/scraped",
    with: "Direct URLs hidden, harder to copy",
  },
  {
    feature: "Schema Markup",
    without: "No structured data for search engines",
    with: "DigitalDocument and CollectionPage schemas",
  },
];

export function ComparisonTable() {
  return (
    <section className="py-20 lg:py-32 bg-card" aria-labelledby="comparison-heading">
      <div className="container mx-auto px-4 lg:px-8">
        <header className="max-w-3xl mx-auto text-center mb-12">
          <h2 id="comparison-heading" className="text-3xl md:text-4xl font-bold mb-6">
            Why Use This Plugin Instead of Direct PDF Links?
          </h2>
          <p className="text-lg text-muted-foreground">
            See exactly what you gain by switching to PDF Embed & SEO Optimize
          </p>
        </header>

        <div className="max-w-5xl mx-auto">
          {/* Desktop Table */}
          <div className="hidden md:block overflow-hidden rounded-2xl border border-border shadow-soft">
            <table className="w-full" role="table" aria-label="Feature comparison between direct PDF links and PDF Embed plugin">
              <thead>
                <tr className="bg-muted">
                  <th className="text-left py-4 px-6 font-semibold text-foreground" scope="col">
                    Feature
                  </th>
                  <th className="text-left py-4 px-6 font-semibold text-destructive" scope="col">
                    <div className="flex items-center gap-2">
                      <X className="w-5 h-5" aria-hidden="true" />
                      Direct PDF Link
                    </div>
                  </th>
                  <th className="text-left py-4 px-6 font-semibold text-primary" scope="col">
                    <div className="flex items-center gap-2">
                      <Check className="w-5 h-5" aria-hidden="true" />
                      PDF Embed & SEO Optimize
                    </div>
                  </th>
                </tr>
              </thead>
              <tbody>
                {pluginComparisons.map((row, index) => (
                  <tr 
                    key={row.feature} 
                    className={index % 2 === 0 ? "bg-background" : "bg-muted/30"}
                  >
                    <td className="py-4 px-6 font-medium text-foreground">
                      {row.feature}
                    </td>
                    <td className="py-4 px-6 text-muted-foreground">
                      <div className="flex items-start gap-2">
                        <X className="w-4 h-4 text-destructive shrink-0 mt-0.5" aria-hidden="true" />
                        <span>{row.without}</span>
                      </div>
                    </td>
                    <td className="py-4 px-6 text-foreground">
                      <div className="flex items-start gap-2">
                        <Check className="w-4 h-4 text-primary shrink-0 mt-0.5" aria-hidden="true" />
                        <span>{row.with}</span>
                      </div>
                    </td>
                  </tr>
                ))}
              </tbody>
            </table>
          </div>

          {/* Mobile Cards */}
          <div className="md:hidden space-y-4">
            {pluginComparisons.map((row, index) => (
              <div 
                key={row.feature}
                className="bg-background rounded-xl border border-border p-4 animate-fade-in"
                style={{ animationDelay: `${index * 0.05}s` }}
              >
                <h3 className="font-semibold text-foreground mb-3">{row.feature}</h3>
                <div className="space-y-2">
                  <div className="flex items-start gap-2 text-sm">
                    <X className="w-4 h-4 text-destructive shrink-0 mt-0.5" aria-hidden="true" />
                    <span className="text-muted-foreground">{row.without}</span>
                  </div>
                  <div className="flex items-start gap-2 text-sm">
                    <Check className="w-4 h-4 text-primary shrink-0 mt-0.5" aria-hidden="true" />
                    <span className="text-foreground font-medium">{row.with}</span>
                  </div>
                </div>
              </div>
            ))}
          </div>

          {/* Summary */}
          <div className="mt-12 p-6 rounded-2xl bg-primary/5 border border-primary/20 text-center">
            <p className="text-lg font-medium text-foreground">
              Each PDF becomes a proper webpage that Google can rank, with title tags, meta descriptions, and structured data.
            </p>
          </div>
        </div>
      </div>
    </section>
  );
}