import { Link } from "react-router-dom";
import { Search, Palette, Shield, BarChart3, Bot, Share2, ArrowRight } from "lucide-react";

const benefits = [
  {
    icon: Search,
    title: "SEO & GEO Optimized",
    description: "Full SEO with meta tags, Yoast integration, JSON-LD schema, and XML sitemap. Plus GEO optimization so AI tools and LLMs can discover your content.",
    link: "/wordpress-pdf-viewer/",
    linkText: "Learn about WordPress PDF SEO"
  },
  {
    icon: Bot,
    title: "Found by AI & LLMs",
    description: "Structured data and semantic markup help ChatGPT, Google AI, and other language models understand and surface your PDF content.",
    link: null,
    linkText: null
  },
  {
    icon: Palette,
    title: "Brand Control",
    description: "Clean branded URLs like /pdf/annual-report/ and consistent viewer experience matching your site design.",
    link: "/wordpress-pdf-viewer/",
    linkText: "See the WordPress PDF viewer"
  },
  {
    icon: Shield,
    title: "Content Protection",
    description: "AJAX loading hides direct file URLs, with per-document print and download toggles for complete control.",
    link: null,
    linkText: null
  },
  {
    icon: BarChart3,
    title: "Analytics",
    description: "Built-in view statistics track document engagement so you know which PDFs resonate with your audience.",
    link: "/pro/",
    linkText: "Get advanced analytics with Pro"
  },
  {
    icon: Share2,
    title: "Social Sharing",
    description: "Auto-generated OpenGraph and Twitter Cards with PDF thumbnails create compelling social previews.",
    link: null,
    linkText: null
  },
];

export function KeyBenefits() {
  return (
    <section className="py-20 lg:py-32 bg-background" aria-labelledby="benefits-heading">
      <div className="container mx-auto px-4 lg:px-8">
        <header className="max-w-3xl mx-auto text-center mb-12">
          <h2 id="benefits-heading" className="text-3xl md:text-4xl font-bold mb-6">
            6 Key Benefits at a Glance
          </h2>
          <p className="text-lg text-muted-foreground">
            Everything you need to make your PDFs work harder for your WordPress website
          </p>
        </header>

        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 max-w-6xl mx-auto">
          {benefits.map((benefit, index) => (
            <article
              key={benefit.title}
              className="group p-6 rounded-2xl bg-card border border-border hover:border-primary/30 hover:shadow-soft transition-all duration-300 animate-fade-in"
              style={{ animationDelay: `${index * 0.1}s` }}
            >
              <div className="w-12 h-12 rounded-xl bg-primary/10 flex items-center justify-center mb-4 group-hover:bg-primary/20 transition-colors">
                <benefit.icon className="w-6 h-6 text-primary" aria-hidden="true" />
              </div>
              <h3 className="text-xl font-semibold text-foreground mb-2">
                {benefit.title}
              </h3>
              <p className="text-muted-foreground leading-relaxed mb-3">
                {benefit.description}
              </p>
              {benefit.link && (
                <Link 
                  to={benefit.link} 
                  className="inline-flex items-center gap-1 text-sm text-primary hover:text-primary/80 font-medium transition-colors"
                  title={benefit.linkText}
                  aria-label={`${benefit.linkText} - Learn more about ${benefit.title}`}
                >
                  {benefit.linkText}
                  <ArrowRight className="w-3 h-3" aria-hidden="true" />
                </Link>
              )}
            </article>
          ))}
        </div>
      </div>
    </section>
  );
}
