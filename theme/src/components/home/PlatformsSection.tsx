import { Check, ArrowRight } from "lucide-react";
import { Button } from "@/components/ui/button";
import { WordPressIcon, DrupalIcon, NextjsIcon } from "@/components/icons/PlatformIcons";

const platforms = [
  {
    name: "WordPress",
    version: "v1.2.8",
    icon: WordPressIcon,
    description: "Full-featured plugin for WordPress 5.8+ with Gutenberg block, shortcodes, and Yoast SEO integration.",
    features: ["Gutenberg Block", "Shortcode Support", "Yoast SEO Integration", "REST API"],
    learnMoreHref: "/wordpress-pdf-viewer/",
    downloadHref: "https://wordpress.org/plugins/pdf-embed-seo-optimize/",
    downloadText: "Download Free",
    featured: false,
  },
  {
    name: "Drupal",
    version: "v1.2.8",
    icon: DrupalIcon,
    description: "Comprehensive module for Drupal 10/11 with custom entity, block plugin, and full API support.",
    features: ["Custom Entity Type", "Block Plugin", "Twig Templates", "REST Resources"],
    learnMoreHref: "/drupal-pdf-viewer/",
    downloadHref: "https://www.drupal.org/project/pdf_embed_seo",
    downloadText: "Download Free",
    featured: false,
  },
  {
    name: "React / Next.js",
    version: "v1.3.0",
    icon: NextjsIcon,
    description: "Modern React components with full TypeScript support, SSR/SSG capabilities, and headless CMS integration.",
    features: ["React 18+ Components", "Next.js App Router", "TypeScript Native", "Headless CMS Ready"],
    learnMoreHref: "/nextjs-pdf-viewer/",
    downloadHref: "https://www.npmjs.com/package/@pdf-embed-seo/react",
    downloadText: "npm install",
    featured: true,
    badge: "NEW",
  },
];

export function PlatformsSection() {
  return (
    <section className="py-16 lg:py-24 bg-muted/30" aria-labelledby="platforms-heading">
      <div className="container mx-auto px-4 lg:px-8">
        <div className="text-center max-w-3xl mx-auto mb-12">
          <h2 id="platforms-heading" className="text-3xl md:text-4xl font-bold mb-4">
            Available for Your Platform
          </h2>
          <p className="text-lg text-muted-foreground">
            Whether you're using a traditional CMS or building with React, we've got you covered.
          </p>
        </div>

        <div className="grid md:grid-cols-2 lg:grid-cols-3 gap-6 lg:gap-8">
          {platforms.map((platform) => {
            const Icon = platform.icon;
            return (
              <div
                key={platform.name}
                className={`relative bg-card rounded-2xl border p-6 lg:p-8 transition-all hover:shadow-large hover:-translate-y-1 ${
                  platform.featured
                    ? "border-primary bg-gradient-to-b from-primary/5 to-card"
                    : "border-border"
                }`}
              >
                {platform.badge && (
                  <div className="absolute -top-3 right-6 px-3 py-1 bg-primary text-primary-foreground text-xs font-bold rounded-full">
                    {platform.badge}
                  </div>
                )}

                <div className="flex items-center justify-between mb-5">
                  <Icon size={48} className="text-foreground" />
                  <span className="text-xs font-medium px-2 py-1 rounded bg-muted text-muted-foreground">
                    {platform.version}
                  </span>
                </div>

                <h3 className="text-xl font-bold mb-3">{platform.name}</h3>
                <p className="text-muted-foreground mb-5 text-sm leading-relaxed">
                  {platform.description}
                </p>

                <ul className="space-y-2 mb-6">
                  {platform.features.map((feature) => (
                    <li key={feature} className="flex items-center gap-2 text-sm">
                      <Check className="w-4 h-4 text-primary shrink-0" aria-hidden="true" />
                      <span>{feature}</span>
                    </li>
                  ))}
                </ul>

                <div className="flex flex-col sm:flex-row gap-3">
                  <Button
                    variant={platform.featured ? "default" : "outline"}
                    className="flex-1"
                    asChild
                  >
                    <a
                      href={platform.learnMoreHref}
                      title={`Learn more about ${platform.name} PDF viewer`}
                    >
                      Learn More
                      <ArrowRight className="w-4 h-4 ml-2" aria-hidden="true" />
                    </a>
                  </Button>
                  <Button variant="ghost" className="flex-1 text-muted-foreground" asChild>
                    <a
                      href={platform.downloadHref}
                      target="_blank"
                      rel="noopener noreferrer"
                      title={`${platform.downloadText} for ${platform.name}`}
                    >
                      {platform.downloadText}
                    </a>
                  </Button>
                </div>
              </div>
            );
          })}
        </div>
      </div>
    </section>
  );
}
