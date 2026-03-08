import { Check, Star, Zap, ArrowRight, Building2 } from "lucide-react";
import { Link } from "react-router-dom";
import { Button } from "@/components/ui/button";

const proFeatures = [
  "1 site license",
  "Analytics Dashboard",
  "Password Protection",
  "Reading Progress Tracking",
  "XML Sitemap (/pdf/sitemap.xml)",
  "Categories & Tags",
  "CSV/JSON Export",
  "1 year of updates",
  "Priority email support",
];

const proPlusFeatures = [
  "Unlimited site licenses",
  "Everything in Pro, plus:",
  "Role-Based Access Control",
  "Bulk Import (CSV/ZIP)",
  "Full REST API",
  "White-Label Options",
  "Priority + Chat support",
];

const plans = [
  {
    name: "Pro",
    price: "$49",
    period: "/year",
    description: "All essential Pro features for a single site",
    features: proFeatures,
    popular: true,
    cta: "Get Pro",
  },
  {
    name: "Pro+",
    price: "$199",
    period: "/year",
    description: "Unlimited sites for agencies and teams",
    features: proPlusFeatures,
    popular: false,
    cta: "Get Pro+",
  },
];

const lifetimePlan = {
  name: "Lifetime",
  price: "$799",
  period: "one-time",
  description: "Pay once, own it forever. Best value for long-term users and agencies who want to eliminate recurring costs.",
  features: [
    "All current and future Pro features",
    "Unlimited site licenses",
    "Lifetime updates — no renewals, ever",
    "Priority support included for life",
  ],
};

const enterpriseFeatures = [
  "Unlimited sites and users",
  "Lifetime updates",
  "Dedicated account manager",
  "SLA agreement (99.5%+ uptime)",
  "SSO integration support (Azure AD, Okta)",
  "Compliance documentation package",
  "Expiring access links & audit logs",
  "Custom onboarding & training",
  "Data Processing Agreement (DPA) available",
  "Custom development (scoped)",
];

export function ProPricing() {
  return (
    <section id="pricing" className="py-20 lg:py-32 bg-background" aria-labelledby="pricing-heading">
      <div className="container mx-auto px-4 lg:px-8">
        <header className="max-w-3xl mx-auto text-center mb-12">
          <h2 id="pricing-heading" className="text-3xl md:text-4xl font-bold mb-6">
            Simple, Transparent Pricing
          </h2>
          <p className="text-lg text-muted-foreground">
            Choose the plan that fits your needs. All plans include a 30-day money-back guarantee.
          </p>
        </header>

        {/* Annual Plans */}
        <div className="max-w-3xl mx-auto text-center mb-8">
          <h3 className="text-2xl font-bold text-foreground mb-2">Annual Plans</h3>
          <p className="text-muted-foreground">All premium features — choose your scale</p>
        </div>

        <div className="grid grid-cols-1 md:grid-cols-2 gap-6 max-w-3xl mx-auto mb-12 justify-items-center">
          {plans.map((plan, index) => (
            <article
              key={plan.name}
              className={`relative p-6 rounded-2xl border transition-all duration-300 animate-fade-in flex flex-col w-full ${
                plan.popular
                  ? "bg-primary/10 border-primary shadow-glow"
                  : "bg-card border-border hover:border-primary/30"
              }`}
              style={{ animationDelay: `${index * 0.1}s` }}
            >
              {plan.popular && (
                <div className="absolute -top-3 left-1/2 -translate-x-1/2 px-3 py-1 rounded-full bg-primary text-primary-foreground text-xs font-semibold flex items-center gap-1">
                  <Star className="w-3 h-3" aria-hidden="true" />
                  Most Popular
                </div>
              )}

              <div className="text-center mb-6">
                <h4 className="text-xl font-semibold text-foreground mb-1">{plan.name}</h4>
                <div className="flex items-baseline justify-center gap-1">
                  <span className="text-4xl font-bold text-foreground">{plan.price}</span>
                  <span className="text-muted-foreground">{plan.period}</span>
                </div>
                <p className="text-sm text-muted-foreground mt-2">{plan.description}</p>
              </div>

              <ul className="space-y-3 mb-6 flex-1">
                {plan.features.map((feature) => (
                  <li key={feature} className="flex items-center gap-2 text-sm">
                    {feature.includes("Everything in") ? (
                      <span className="text-xs font-semibold text-primary uppercase tracking-wide">{feature}</span>
                    ) : (
                      <>
                        <Check className="w-4 h-4 text-primary shrink-0" aria-hidden="true" />
                        <span className="text-foreground">{feature}</span>
                      </>
                    )}
                  </li>
                ))}
              </ul>

              <Button
                className={`w-full mt-auto ${plan.popular ? "gradient-hero shadow-glow" : ""}`}
                variant={plan.popular ? "default" : "outline"}
                title={`Purchase ${plan.name} plan for ${plan.price} per year`}
                aria-label={`Purchase ${plan.name} plan for ${plan.price} per year`}
              >
                {plan.cta}
              </Button>
            </article>
          ))}
        </div>

        {/* Lifetime Plan */}
        <div className="max-w-3xl mx-auto mb-20">
          <article className="p-8 rounded-2xl bg-gradient-to-br from-primary/15 via-accent/10 to-background border border-primary/30 shadow-soft animate-fade-in" style={{ animationDelay: "0.4s" }}>
            <div className="flex flex-col md:flex-row items-center gap-6">
              <div className="w-16 h-16 rounded-2xl gradient-hero flex items-center justify-center shadow-glow shrink-0">
                <Zap className="w-8 h-8 text-primary-foreground" aria-hidden="true" />
              </div>

              <div className="flex-1 text-center md:text-left">
                <h4 className="text-2xl font-bold text-foreground mb-1">{lifetimePlan.name}</h4>
                <div className="flex items-baseline justify-center md:justify-start gap-2 mb-2">
                  <span className="text-3xl font-bold text-primary">{lifetimePlan.price}</span>
                  <span className="text-muted-foreground">{lifetimePlan.period}</span>
                </div>
                <p className="text-muted-foreground">{lifetimePlan.description}</p>
              </div>

              <Button size="lg" className="gradient-hero shadow-glow shrink-0" title="Get lifetime access for a one-time payment of $799" aria-label="Purchase Lifetime access for $799 one-time payment">
                Get Lifetime Access
              </Button>
            </div>

            <div className="grid grid-cols-2 md:grid-cols-4 gap-3 mt-6 pt-6 border-t border-primary/20">
              {lifetimePlan.features.map((feature) => (
                <div key={feature} className="flex items-center gap-2 text-sm">
                  <Check className="w-4 h-4 text-primary shrink-0" aria-hidden="true" />
                  <span className="text-foreground">{feature}</span>
                </div>
              ))}
            </div>

            <p className="text-xs text-muted-foreground mt-4 text-center italic">
              Limited availability — price subject to change as features grow.
            </p>
          </article>
        </div>

        {/* Enterprise Section */}
        <div className="max-w-3xl mx-auto">
          <div className="text-center mb-8">
            <h3 className="text-2xl font-bold text-foreground mb-2">Enterprise</h3>
            <p className="text-muted-foreground">For Pharma, Life Sciences, Healthcare & large organizations</p>
          </div>

          <article className="p-8 rounded-2xl bg-gradient-to-br from-accent/10 via-background to-primary/5 border border-accent/30 shadow-soft animate-fade-in">
            <div className="flex items-center gap-3 mb-4">
              <div className="w-12 h-12 rounded-xl bg-accent/20 flex items-center justify-center shrink-0">
                <Building2 className="w-6 h-6 text-accent" aria-hidden="true" />
              </div>
              <div>
                <h4 className="text-xl font-bold text-foreground">Everything in Pro+, plus:</h4>
                <div className="flex items-baseline gap-2 mt-1">
                  <span className="text-2xl font-bold text-accent">from $2,500</span>
                  <span className="text-sm text-muted-foreground">/year</span>
                </div>
              </div>
            </div>

            <p className="text-sm text-muted-foreground mb-5">
              Custom pricing based on deployment size, integration requirements, and SLA needs.
            </p>

            <ul className="grid grid-cols-1 sm:grid-cols-2 gap-2 mb-6">
              {enterpriseFeatures.map((feature) => (
                <li key={feature} className="flex items-center gap-2 text-sm">
                  <Check className="w-4 h-4 text-accent shrink-0" aria-hidden="true" />
                  <span className="text-foreground">{feature}</span>
                </li>
              ))}
            </ul>

            <Button className="w-full bg-accent hover:bg-accent/90 text-accent-foreground" title="Request Enterprise quote" aria-label="Request Enterprise quote — plans from $2,500/year" asChild>
              <Link to="/enterprise/">
                Request Enterprise Quote
                <ArrowRight className="w-4 h-4 ml-2" aria-hidden="true" />
              </Link>
            </Button>

            <p className="text-xs text-muted-foreground mt-4 text-center">
              Trusted by teams in pharmaceutical, healthcare, and finance.{" "}
              <Link to="/enterprise/" className="text-accent hover:underline" title="Learn more about Enterprise PDF management" aria-label="Learn more about our Enterprise offering">
                Learn more about our Enterprise offering →
              </Link>
            </p>
          </article>
        </div>
      </div>
    </section>
  );
}
