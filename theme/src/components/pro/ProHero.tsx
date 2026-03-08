import { ArrowRight, Zap, Shield, BarChart3 } from "lucide-react";
import { Link } from "react-router-dom";
import { Button } from "@/components/ui/button";

const highlights = [
  { icon: BarChart3, text: "Analytics Dashboard" },
  { icon: Shield, text: "Password Protection" },
  { icon: Zap, text: "Reading Progress" },
];

export function ProHero() {
  return (
    <section className="relative pt-8 pb-20 md:pt-12 lg:pt-16 lg:pb-32 overflow-hidden">
      {/* Background */}
      <div className="absolute inset-0 bg-gradient-to-b from-primary/5 via-background to-background" aria-hidden="true" />
      <div className="absolute top-20 left-1/4 w-96 h-96 bg-primary/10 rounded-full blur-3xl" aria-hidden="true" />
      <div className="absolute bottom-20 right-1/4 w-80 h-80 bg-accent/10 rounded-full blur-3xl" aria-hidden="true" />

      <div className="container relative mx-auto px-4 lg:px-8">
        <div className="max-w-4xl mx-auto text-center">
          {/* Badge */}
          <div className="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-primary/10 border border-primary/20 text-primary text-sm font-medium mb-8 animate-fade-in">
            <Zap className="w-4 h-4" aria-hidden="true" />
            v1.2.11 | WordPress, Drupal & React
          </div>

          {/* Headline */}
          <h1 className="text-4xl md:text-5xl lg:text-6xl font-bold text-foreground mb-6 animate-fade-in" style={{ animationDelay: "0.1s" }}>
            <span className="text-gradient">
              PDF Embed & SEO Optimize
            </span>{" "}
            Pro
          </h1>

          {/* Subheadline */}
          <p className="text-lg md:text-xl text-muted-foreground max-w-2xl mx-auto mb-8 animate-fade-in" style={{ animationDelay: "0.2s" }}>
            From personal sites to regulated enterprise environments — choose the plan that fits your scale.
            All plans include a 30-day money-back guarantee.
          </p>

          {/* Highlights */}
          <div className="flex flex-wrap justify-center gap-4 mb-10 animate-fade-in" style={{ animationDelay: "0.3s" }}>
            {highlights.map((item) => (
              <div key={item.text} className="flex items-center gap-2 px-4 py-2 rounded-lg bg-card border border-border">
                <item.icon className="w-4 h-4 text-primary" aria-hidden="true" />
                <span className="text-sm font-medium text-foreground">{item.text}</span>
              </div>
            ))}
          </div>

          {/* CTAs */}
          <div className="flex flex-col sm:flex-row items-center justify-center gap-4 animate-fade-in" style={{ animationDelay: "0.4s" }}>
            <Button size="lg" className="gradient-hero shadow-glow text-lg px-8" asChild>
              <a href="#pricing" title="View pricing plans" aria-label="See all pricing plans">
                See All Plans
                <ArrowRight className="w-5 h-5 ml-2" aria-hidden="true" />
              </a>
            </Button>
            <Button variant="outline" size="lg" className="text-lg px-8" asChild>
              <a href="#compare" title="Compare features across all tiers" aria-label="Compare features across all plans">
                Compare Features
              </a>
            </Button>
            <Button variant="ghost" size="lg" className="text-lg px-8 text-accent" asChild>
              <Link to="/enterprise/" title="Enterprise plans from $2,500/year" aria-label="View Enterprise plans for regulated industries">
                Enterprise Options
              </Link>
            </Button>
          </div>
        </div>
      </div>
    </section>
  );
}
