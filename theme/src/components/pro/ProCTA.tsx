import { ArrowRight, Shield, RefreshCw, Headphones } from "lucide-react";
import { Button } from "@/components/ui/button";

const guarantees = [
  { icon: Shield, text: "30-Day Money-Back Guarantee" },
  { icon: RefreshCw, text: "1 Year of Updates Included" },
  { icon: Headphones, text: "Priority Support" },
];

export function ProCTA() {
  return (
    <section className="py-20 lg:py-32 bg-background" aria-labelledby="pro-cta-heading">
      <div className="container mx-auto px-4 lg:px-8">
        <div className="max-w-4xl mx-auto">
          <div className="relative overflow-hidden rounded-3xl gradient-hero p-8 md:p-12 text-center shadow-glow">
            {/* Background decoration */}
            <div className="absolute inset-0 opacity-10" aria-hidden="true">
              <div className="absolute top-0 left-0 w-64 h-64 bg-white/20 rounded-full blur-3xl" />
              <div className="absolute bottom-0 right-0 w-80 h-80 bg-white/10 rounded-full blur-3xl" />
            </div>

            <div className="relative">
              <h2 id="pro-cta-heading" className="text-3xl md:text-4xl font-bold text-primary-foreground mb-4">
                Ready to Go Pro?
              </h2>
              <p className="text-lg text-primary-foreground/80 mb-8 max-w-2xl mx-auto">
                Join thousands of WordPress and Drupal users who trust PDF Embed Pro for their document management needs.
              </p>

              <div className="flex flex-col sm:flex-row items-center justify-center gap-4 mb-8">
                <Button
                  size="lg"
                  variant="secondary"
                  className="text-lg px-8 bg-white text-primary hover:bg-white/90"
                  asChild
                >
                  <a href="#pricing" aria-label="Get PDF Embed Pro now - scroll to pricing section">
                    Get Pro Now
                    <ArrowRight className="w-5 h-5 ml-2" aria-hidden="true" />
                  </a>
                </Button>
              </div>

              {/* Guarantees */}
              <div className="flex flex-wrap items-center justify-center gap-6">
                {guarantees.map((item) => (
                  <div key={item.text} className="flex items-center gap-2 text-primary-foreground/90">
                    <item.icon className="w-4 h-4" aria-hidden="true" />
                    <span className="text-sm font-medium">{item.text}</span>
                  </div>
                ))}
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  );
}
