import { Check, FileText, Search, Code2, BarChart3, Share2, Layers, Link2, Shield, Palette, Image, Blocks } from "lucide-react";

const features = [
  {
    icon: Link2,
    title: "Clean, Branded URLs",
    description: "Each PDF gets a professional URL like /pdf/annual-report/ instead of messy upload paths.",
  },
  {
    icon: Search,
    title: "Full SEO Optimization",
    description: "Yoast SEO integration with title tags, meta descriptions, JSON-LD schema markup, and XML sitemap inclusion.",
  },
  {
    icon: Share2,
    title: "OpenGraph & Twitter Cards",
    description: "Auto-generated thumbnails from your PDF's first page create beautiful social media previews.",
  },
  {
    icon: Shield,
    title: "Content Protection",
    description: "Hide direct file URLs and control print/download permissions per document via AJAX loading.",
  },
  {
    icon: BarChart3,
    title: "Built-in View Statistics",
    description: "Track how often each PDF is viewed and know which documents are most popular.",
  },
  {
    icon: FileText,
    title: "Mozilla PDF.js Viewer",
    description: "Industry-standard rendering that works consistently across all browsers and devices.",
  },
  {
    icon: Palette,
    title: "Light & Dark Themes",
    description: "Customizable viewer appearance that matches your brand and website design.",
  },
  {
    icon: Blocks,
    title: "Gutenberg Block & Shortcodes",
    description: "Native block editor support plus [pdf_viewer] and [pdf_viewer_sitemap] for flexible placement.",
  },
  {
    icon: Layers,
    title: "PDF Archive Page",
    description: "Auto-generated listing at /pdf/ showing all your documents with CollectionPage schema.",
  },
];

export function Solution() {
  return (
    <section id="features" className="py-20 lg:py-32" aria-labelledby="solution-heading">
      <div className="container mx-auto px-4 lg:px-8">
        <header className="max-w-3xl mx-auto text-center mb-16">
          <div className="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-primary/10 text-primary text-sm font-medium mb-6">
            <Check className="w-4 h-4" aria-hidden="true" />
            <span>The Solution</span>
          </div>
          <h2 id="solution-heading" className="text-3xl md:text-4xl font-bold mb-6">
            <span className="text-gradient">Make Your PDFs Work Harder</span>
          </h2>
          <p className="text-lg text-muted-foreground">
            Turn your documents into professional web pages that customers can find, 
            read, and share easily.
          </p>
        </header>

        {/* Example */}
        <div className="max-w-3xl mx-auto mb-16">
          <div className="p-6 rounded-xl bg-primary/5 border border-primary/20">
            <p className="text-sm font-medium text-primary mb-2">✅ What your customers see with our plugin:</p>
            <a 
              href="https://pdfviewermodule.com/pdf/example-1/"
              target="_blank"
              rel="noopener noreferrer"
              className="text-sm text-primary font-semibold break-all hover:underline transition-colors"
              title="View example of clean PDF URL with our plugin (opens in new tab)"
              aria-label="Example clean PDF URL with our plugin - opens in new tab"
            >
              <code>yourdomain.com/pdf/product-brochure/</code>
            </a>
            <p className="text-xs text-muted-foreground mt-3">
              A clean, professional link that tells everyone exactly what they'll find
            </p>
          </div>
        </div>

        <ul className="grid md:grid-cols-2 lg:grid-cols-3 gap-6 list-none" role="list" aria-label="Plugin features">
          {features.map((feature, index) => (
            <li
              key={feature.title}
              className="group p-6 rounded-2xl bg-card border border-border shadow-soft hover:shadow-medium hover:border-primary/30 transition-all duration-300 animate-fade-in"
              style={{ animationDelay: `${index * 0.05}s` }}
            >
              <div className="w-10 h-10 rounded-xl gradient-hero flex items-center justify-center mb-4 group-hover:shadow-glow transition-shadow">
                <feature.icon className="w-5 h-5 text-primary-foreground" aria-hidden="true" />
              </div>
              <h3 className="text-lg font-semibold mb-2">{feature.title}</h3>
              <p className="text-sm text-muted-foreground">{feature.description}</p>
            </li>
          ))}
        </ul>
      </div>
    </section>
  );
}
