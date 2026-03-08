import { useEffect, useState, useMemo } from "react";
import { Link, useLocation, useNavigate } from "react-router-dom";
import {
  ArrowRight, Shield, FileText, Lock, Key, Clock, Server,
  Building2, FlaskConical, Scale, Landmark, GraduationCap,
  Check,
} from "lucide-react";
import { Layout } from "@/components/layout/Layout";
import { SEOHead } from "@/components/SEOHead";
import { Button } from "@/components/ui/button";
import { Breadcrumb } from "@/components/ui/breadcrumb";
import {
  Accordion,
  AccordionContent,
  AccordionItem,
  AccordionTrigger,
} from "@/components/ui/accordion";

/* ─────────── Data ─────────── */

const trustBadges = [
  { icon: Lock, text: "GDPR Compliant" },
  { icon: FileText, text: "Audit-Trail Ready" },
  { icon: FlaskConical, text: "Life Sciences Trusted" },
  { icon: Key, text: "SSO / Azure AD Integration" },
  { icon: Shield, text: "Role-Based Access Control" },
];

const painPoints = [
  {
    challenge: "Regulatory Compliance",
    problem: "No audit trail, no access logs — fails GxP/GDPR audits",
    solution: "Full access audit logs, expiring links, user-level tracking",
  },
  {
    challenge: "Security & Access",
    problem: "PDFs exposed via direct URLs, no authentication layer",
    solution: "Secure URLs, password protection, login requirements, CSRF protection",
  },
  {
    challenge: "IT Integration",
    problem: "No API, no SSO, no role management — shadow IT results",
    solution: "Full REST API, role-based access, integrates with your existing IAM",
  },
  {
    challenge: "Scalability",
    problem: "Breaks under bulk imports or multi-site setups",
    solution: "Bulk CSV/ZIP import, unlimited sites, white-label deployment",
  },
  {
    challenge: "Vendor Accountability",
    problem: "No SLA, no support contract — unacceptable for regulated environments",
    solution: "Dedicated SLA, named account manager, priority escalation path",
  },
];

const complianceFeatures = [
  {
    icon: FileText,
    title: "Audit-Ready Access Logs",
    description: "Every document view, download, and access attempt is logged with user identity, timestamp, and IP. Exportable in CSV/JSON for audit submissions and internal reviews.",
  },
  {
    icon: Shield,
    title: "Role-Based Access Control (RBAC)",
    description: "Restrict document visibility and download permissions by user role or group. Prevent unauthorized access to confidential clinical, regulatory, or commercial materials.",
  },
  {
    icon: Clock,
    title: "Expiring Access Links",
    description: "Generate time-limited, single-use document links for external reviewers, auditors, or partners. Links expire automatically — no manual revocation needed.",
  },
  {
    icon: Key,
    title: "SSO-Ready Architecture",
    description: "The REST API and role structure are designed to integrate with your existing Identity Provider (Azure AD, Okta, SAML 2.0). Custom integration scoping available for Enterprise customers.",
  },
  {
    icon: Lock,
    title: "GDPR-Compliant by Design",
    description: "No third-party CDN dependencies for document delivery. Documents never leave your infrastructure. Full data processing transparency for DPA requirements.",
  },
  {
    icon: Server,
    title: "Validated Deployment Support",
    description: "Enterprise customers receive deployment documentation, a configuration checklist, and optional validation support for regulated environments requiring IQ/OQ-style change documentation.",
  },
];

const useCases = [
  {
    icon: FlaskConical,
    title: "Pharmaceutical & Life Sciences",
    description: "Securely manage regulatory submissions, SOPs, clinical study reports, and medical information documents. Control who can view, download, or share sensitive materials — with a full audit trail.",
    docs: "SOPs, CSRs, SmPC drafts, regulatory dossiers, training materials",
  },
  {
    icon: Scale,
    title: "Legal & Compliance Teams",
    description: "Publish internal policies, contracts, and compliance documentation with enforced access controls. Generate expiring links for external counsel or auditors without exposing direct file URLs.",
    docs: "Policies, NDAs, audit reports, regulatory guidance documents",
  },
  {
    icon: Landmark,
    title: "Finance & Corporate",
    description: "Distribute investor materials, annual reports, and internal financial documents with controlled access. Track who opened what and when — critical for disclosure documentation.",
    docs: "Annual reports, investor decks, board materials, financial statements",
  },
  {
    icon: GraduationCap,
    title: "Training & Knowledge Management",
    description: "Manage large libraries of training materials, e-learnings, and product documentation. Resume reading, progress tracking, and categorization keep learners engaged and organized.",
    docs: "Training modules, product manuals, onboarding materials",
  },
];

type FeatureValue = boolean;
interface MatrixFeature {
  name: string;
  starter: FeatureValue;
  professional: FeatureValue;
  agency: FeatureValue;
  enterprise: FeatureValue;
}

const matrixFeatures: MatrixFeature[] = [
  { name: "Secure PDF URLs", starter: true, professional: true, agency: true, enterprise: true },
  { name: "Analytics Dashboard", starter: true, professional: true, agency: true, enterprise: true },
  { name: "Password Protection", starter: true, professional: true, agency: true, enterprise: true },
  { name: "Role-Based Access Control", starter: false, professional: false, agency: true, enterprise: true },
  { name: "Full REST API", starter: false, professional: false, agency: true, enterprise: true },
  { name: "Expiring Access Links", starter: false, professional: false, agency: true, enterprise: true },
  { name: "Bulk Import (CSV/ZIP)", starter: false, professional: false, agency: true, enterprise: true },
  { name: "White-Label Options", starter: false, professional: false, agency: true, enterprise: true },
  { name: "Unlimited Sites", starter: false, professional: false, agency: true, enterprise: true },
  { name: "Lifetime Updates", starter: false, professional: false, agency: false, enterprise: true },
  { name: "Dedicated Account Manager", starter: false, professional: false, agency: false, enterprise: true },
  { name: "SLA Agreement (99.5%+ uptime)", starter: false, professional: false, agency: false, enterprise: true },
  { name: "SSO Integration Support", starter: false, professional: false, agency: false, enterprise: true },
  { name: "Custom Onboarding & Training", starter: false, professional: false, agency: false, enterprise: true },
  { name: "Compliance Documentation Package", starter: false, professional: false, agency: false, enterprise: true },
  { name: "Custom Development (scoped)", starter: false, professional: false, agency: false, enterprise: true },
  { name: "Priority Escalation Path", starter: false, professional: false, agency: false, enterprise: true },
];

const matrixCols = [
  { key: "starter" as const, label: "Starter" },
  { key: "professional" as const, label: "Professional" },
  { key: "agency" as const, label: "Agency" },
  { key: "enterprise" as const, label: "Enterprise" },
];

const enterprisePlans = [
  {
    name: "Enterprise Starter",
    price: "from $2,500 / year",
    audience: "Single department or regional team deployments (up to 500 active users)",
    features: [
      "All Agency features",
      "Unlimited sites",
      "Lifetime updates",
      "SLA: Response within 24 business hours",
      "Email + Chat support",
      "Onboarding session (1h)",
      "Compliance documentation package",
    ],
    cta: "Request a Quote",
  },
  {
    name: "Enterprise Professional",
    price: "from $8,000 / year",
    audience: "Multi-department or global intranet deployments (up to 5,000 active users)",
    includes: "Everything in Enterprise Starter, plus:",
    features: [
      "Dedicated account manager",
      "SSO integration support (scoped separately if custom)",
      "SLA: Response within 8 business hours, 99.5% uptime commitment",
      "Quarterly business review",
      "Custom onboarding & training workshop",
    ],
    cta: "Request a Quote",
  },
  {
    name: "Enterprise Corporate",
    price: "pricing on request",
    audience: "Entire organization deployment, multi-instance, white-label, or OEM integration",
    includes: "Everything in Enterprise Professional, plus:",
    features: [
      "Custom development (scoped)",
      "Custom SLA terms",
      "Full white-label / rebrand",
      "OEM licensing for internal platforms",
      "Source code escrow option",
      "Named technical contact",
    ],
    cta: "Schedule a Discovery Call",
  },
];

const faqs = [
  {
    q: "Do you offer a formal vendor questionnaire / security assessment?",
    a: "Yes. Enterprise customers can submit a vendor security questionnaire (VSQ) or information security assessment form. We provide completed documentation within 5 business days.",
  },
  {
    q: "Can you sign a Data Processing Agreement (DPA)?",
    a: "Yes. A standard DPA is available for all Enterprise plans. Custom DPA terms can be negotiated for Enterprise Professional and Corporate tiers.",
  },
  {
    q: "Is the software validated for use in GxP-regulated environments?",
    a: "The software is designed with technical controls that support GxP-regulated workflows. Formal IQ/OQ validation documentation is available as an add-on for Enterprise Corporate customers. Validation responsibility remains with your organization.",
  },
  {
    q: "Where is document data stored? Does anything go to third-party servers?",
    a: "No. PDF Embed & SEO Optimize operates entirely within your own WordPress or Drupal infrastructure. No documents are transmitted to external servers. The plugin/module runs server-side on your hosting environment.",
  },
  {
    q: "Can you integrate with our SSO provider (Azure AD, Okta)?",
    a: "The plugin's role-based access system is compatible with WordPress/Drupal SSO plugins. For direct API-level SSO integration, this is scoped as custom development under Enterprise Professional and Corporate plans.",
  },
  {
    q: "What SLA do you offer?",
    a: "Enterprise Starter: 24-business-hour response. Enterprise Professional: 8-business-hour response with 99.5% uptime SLA. Enterprise Corporate: custom SLA terms including critical escalation paths.",
  },
  {
    q: "Do you offer multi-year contracts?",
    a: "Yes. Multi-year agreements are available for Enterprise Professional and Corporate, typically with a 10–15% discount on annual pricing.",
  },
  {
    q: "Has the Drupal module been reviewed by Acquia?",
    a: 'Yes. The Drupal Enterprise module has been reviewed by <a href="https://www.acquia.com" target="_blank" rel="noopener noreferrer" class="text-foreground hover:underline font-semibold">Acquia</a>, the leading Drupal enterprise platform. This review is available for the Enterprise module only.',
    isHtml: true,
  },
];

// Generate URL-friendly slug from question text
const generateSlug = (text: string) =>
  text.toLowerCase()
    .replace(/[^a-z0-9\s-]/g, '')
    .replace(/\s+/g, '-')
    .substring(0, 50);

/* ─────────── Component ─────────── */

const Enterprise = () => {
  const [showFloatingButton, setShowFloatingButton] = useState(false);
  const location = useLocation();
  const navigate = useNavigate();
  const [openFaq, setOpenFaq] = useState<string | undefined>(undefined);

  // Generate slugs for all FAQs
  const faqSlugs = useMemo(() => faqs.map(faq => generateSlug(faq.q)), []);

  // Handle hash on mount and hash changes
  useEffect(() => {
    const hash = location.hash.replace('#', '');
    if (hash) {
      const index = faqSlugs.findIndex(slug => slug === hash);
      if (index !== -1) {
        setOpenFaq(`ent-faq-${index}`);
        setTimeout(() => {
          document.getElementById(hash)?.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }, 100);
      }
    }
  }, [location.hash, faqSlugs]);

  // Update URL when FAQ accordion value changes
  const handleFaqChange = (value: string | undefined) => {
    setOpenFaq(value);
    if (value) {
      const index = parseInt(value.replace('ent-faq-', ''));
      const slug = faqSlugs[index];
      navigate(`${location.pathname}#${slug}`, { replace: true });
    } else {
      navigate(location.pathname, { replace: true });
    }
  };

  useEffect(() => {
    const handleScroll = () => setShowFloatingButton(window.scrollY > 600);
    window.addEventListener("scroll", handleScroll);
    return () => window.removeEventListener("scroll", handleScroll);
  }, []);

  // Inject Service schema
  useEffect(() => {
    const schema = {
      "@context": "https://schema.org",
      "@type": "Service",
      name: "PDF Embed & SEO Optimize Enterprise",
      description: "Enterprise PDF management for regulated industries. GDPR-compliant, audit-ready PDF delivery with role-based access, expiring links, and full REST API.",
      provider: {
        "@type": "Organization",
        name: "Dross:Media",
        url: [
          "https://pdfviewermodule.com",
          "https://pdfviewer.drossmedia.de",
        ],
      },
      serviceType: "Enterprise Software Licensing",
      areaServed: "Worldwide",
      offers: {
        "@type": "AggregateOffer",
        lowPrice: "2500",
        priceCurrency: "USD",
        offerCount: "3",
        availability: "https://schema.org/InStock",
      },
    };

    const script = document.createElement("script");
    script.type = "application/ld+json";
    script.id = "enterprise-service-schema";
    script.textContent = JSON.stringify(schema);
    const existing = document.getElementById("enterprise-service-schema");
    if (existing) existing.remove();
    document.head.appendChild(script);
    return () => {
      const el = document.getElementById("enterprise-service-schema");
      if (el) el.remove();
    };
  }, []);

  // Inject FAQPage schema
  useEffect(() => {
    const faqSchema = {
      "@context": "https://schema.org",
      "@type": "FAQPage",
      mainEntity: faqs.map((faq) => ({
        "@type": "Question",
        name: faq.q,
        acceptedAnswer: {
          "@type": "Answer",
          text: faq.a,
        },
      })),
    };

    const script = document.createElement("script");
    script.type = "application/ld+json";
    script.id = "enterprise-faq-schema";
    script.textContent = JSON.stringify(faqSchema);
    const existing = document.getElementById("enterprise-faq-schema");
    if (existing) existing.remove();
    document.head.appendChild(script);
    return () => {
      const el = document.getElementById("enterprise-faq-schema");
      if (el) el.remove();
    };
  }, []);

  return (
    <Layout>
      <SEOHead
        title="Enterprise PDF Management for Regulated Industries | PDF Embed Pro"
        description="GDPR-compliant, audit-ready PDF delivery for Pharma, Life Sciences, and Healthcare. Role-based access, expiring links, full REST API. Enterprise plans from $2,500/year."
        canonicalPath="/enterprise/"
      />

      {/* Breadcrumb */}
      <div className="container mx-auto px-4 pt-6">
        <Breadcrumb />
      </div>

      {/* ── 1. Hero ────────────────────────────────── */}
      <section className="relative pt-8 pb-20 md:pt-12 lg:pt-16 lg:pb-32 overflow-hidden">
        <div className="absolute inset-0 bg-gradient-to-b from-accent/5 via-background to-background" aria-hidden="true" />
        <div className="absolute top-20 left-1/4 w-96 h-96 bg-accent/10 rounded-full blur-3xl" aria-hidden="true" />
        <div className="absolute bottom-20 right-1/4 w-80 h-80 bg-primary/10 rounded-full blur-3xl" aria-hidden="true" />

        <div className="container relative mx-auto px-4 lg:px-8">
          <div className="max-w-4xl mx-auto text-center">
            <div className="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-accent/10 border border-accent/20 text-accent text-sm font-medium mb-8 animate-fade-in">
              <Building2 className="w-4 h-4" aria-hidden="true" />
              Enterprise
            </div>

            <h1 className="text-4xl md:text-5xl lg:text-6xl font-bold text-foreground mb-6 animate-fade-in" style={{ animationDelay: "0.1s" }}>
              PDF Management Built for{" "}
              <span className="text-gradient">Regulated Industries</span>
            </h1>

            <p className="text-lg md:text-xl text-muted-foreground max-w-3xl mx-auto mb-8 animate-fade-in" style={{ animationDelay: "0.2s" }}>
              Secure, auditable, and compliance-ready PDF delivery for Pharmaceutical, Life Sciences, and Healthcare organizations.
              GDPR-compliant, audit-trail enabled, and IT-team friendly — on WordPress, Drupal, and React.
            </p>

            {/* Trust Badges */}
            <div className="flex flex-wrap justify-center gap-3 mb-10 animate-fade-in" style={{ animationDelay: "0.3s" }}>
              {trustBadges.map((badge) => (
                <div key={badge.text} className="flex items-center gap-2 px-4 py-2 rounded-lg bg-card border border-border text-sm">
                  <badge.icon className="w-4 h-4 text-accent" aria-hidden="true" />
                  <span className="font-medium text-foreground">{badge.text}</span>
                </div>
              ))}
            </div>

            {/* CTAs */}
            <div className="flex flex-col sm:flex-row items-center justify-center gap-4 animate-fade-in" style={{ animationDelay: "0.4s" }}>
              <Button size="lg" className="bg-accent hover:bg-accent/90 text-accent-foreground text-lg px-8 shadow-glow" asChild>
                <Link to="/contact/" title="Request a personalized Enterprise demo and consultation" aria-label="Request Enterprise demo — schedule a consultation with our team">
                  Request Enterprise Demo
                  <ArrowRight className="w-5 h-5 ml-2" aria-hidden="true" />
                </Link>
              </Button>
              <Button variant="outline" size="lg" className="text-lg px-8" title="Download the Enterprise compliance overview as PDF" aria-label="Download Compliance Overview document (PDF)">
                Download Compliance Overview (PDF)
              </Button>
            </div>
          </div>
        </div>
      </section>

      {/* ── 2. Problem Statement ──────────────────── */}
      <section className="py-20 lg:py-32 bg-card" aria-labelledby="problems-heading">
        <div className="container mx-auto px-4 lg:px-8">
          <header className="max-w-3xl mx-auto text-center mb-12">
            <h2 id="problems-heading" className="text-3xl md:text-4xl font-bold mb-6">
              Why Standard PDF Plugins Fall Short in Enterprise
            </h2>
          </header>

          <div className="max-w-6xl mx-auto overflow-hidden rounded-2xl border border-border shadow-soft">
            <div className="overflow-x-auto">
              <table className="w-full min-w-[600px]" role="table" aria-label="Enterprise pain points and solutions">
                <thead>
                  <tr className="bg-muted">
                    <th className="text-left py-3 sm:py-4 px-3 sm:px-6 font-semibold text-foreground text-xs sm:text-sm" scope="col">Challenge</th>
                    <th className="text-left py-3 sm:py-4 px-3 sm:px-6 font-semibold text-destructive text-xs sm:text-sm" scope="col">What Goes Wrong</th>
                    <th className="text-left py-3 sm:py-4 px-3 sm:px-6 font-semibold text-primary text-xs sm:text-sm" scope="col">Our Solution</th>
                  </tr>
                </thead>
                <tbody>
                  {painPoints.map((point, i) => (
                    <tr key={point.challenge} className={i % 2 === 0 ? "bg-background" : "bg-muted/30"}>
                      <td className="py-3 sm:py-4 px-3 sm:px-6 font-semibold text-foreground text-xs sm:text-sm">{point.challenge}</td>
                      <td className="py-3 sm:py-4 px-3 sm:px-6 text-muted-foreground text-xs sm:text-sm">{point.problem}</td>
                      <td className="py-3 sm:py-4 px-3 sm:px-6 text-foreground text-xs sm:text-sm">{point.solution}</td>
                    </tr>
                  ))}
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </section>

      {/* ── 3. Compliance & Regulatory ────────────── */}
      <section className="py-20 lg:py-32 bg-background" aria-labelledby="compliance-heading">
        <div className="container mx-auto px-4 lg:px-8">
          <header className="max-w-3xl mx-auto text-center mb-6">
            <h2 id="compliance-heading" className="text-3xl md:text-4xl font-bold mb-6">
              Designed with Compliance in Mind
            </h2>
            <p className="text-lg text-muted-foreground">
              Organizations in pharmaceutical, medtech, and healthcare operate under strict documentation and data governance requirements.
              PDF Embed & SEO Optimize Pro provides the technical controls needed to support your compliance posture.
            </p>
          </header>

          <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 max-w-6xl mx-auto mt-12">
            {complianceFeatures.map((feat, i) => (
              <article key={feat.title} className="p-6 rounded-2xl bg-card border border-border hover:border-accent/30 transition-all duration-300 animate-fade-in" style={{ animationDelay: `${i * 0.08}s` }}>
                <div className="w-12 h-12 rounded-xl bg-accent/10 flex items-center justify-center mb-4">
                  <feat.icon className="w-6 h-6 text-accent" aria-hidden="true" />
                </div>
                <h3 className="text-lg font-semibold text-foreground mb-2">{feat.title}</h3>
                <p className="text-sm text-muted-foreground leading-relaxed">{feat.description}</p>
              </article>
            ))}
          </div>

          <p className="max-w-3xl mx-auto mt-8 text-xs text-muted-foreground text-center italic">
            Note: PDF Embed & SEO Optimize is a software tool that supports your compliance processes.
            It does not certify compliance with 21 CFR Part 11, EU Annex 11, or other regulations.
            Compliance responsibility remains with your organization.
          </p>
        </div>
      </section>

      {/* ── 4. Use Cases ──────────────────────────── */}
      <section className="py-20 lg:py-32 bg-card" aria-labelledby="usecases-heading">
        <div className="container mx-auto px-4 lg:px-8">
          <header className="max-w-3xl mx-auto text-center mb-12">
            <h2 id="usecases-heading" className="text-3xl md:text-4xl font-bold mb-6">
              Trusted by Teams Managing Critical Documents
            </h2>
          </header>

          <div className="grid grid-cols-1 md:grid-cols-2 gap-6 max-w-5xl mx-auto">
            {useCases.map((uc, i) => (
              <article key={uc.title} className="p-6 rounded-2xl bg-background border border-border hover:border-primary/30 transition-all duration-300 animate-fade-in" style={{ animationDelay: `${i * 0.1}s` }}>
                <div className="flex items-center gap-3 mb-4">
                  <div className="w-10 h-10 rounded-lg bg-primary/10 flex items-center justify-center">
                    <uc.icon className="w-5 h-5 text-primary" aria-hidden="true" />
                  </div>
                  <h3 className="text-lg font-semibold text-foreground">{uc.title}</h3>
                </div>
                <p className="text-sm text-muted-foreground leading-relaxed mb-3">{uc.description}</p>
                <p className="text-xs text-muted-foreground italic">Common document types: {uc.docs}</p>
              </article>
            ))}
          </div>
        </div>
      </section>

      {/* ── 5. Enterprise Features Matrix ─────────── */}
      <section className="py-20 lg:py-32 bg-background" aria-labelledby="matrix-heading">
        <div className="container mx-auto px-4 lg:px-8">
          <header className="max-w-3xl mx-auto text-center mb-12">
            <h2 id="matrix-heading" className="text-3xl md:text-4xl font-bold mb-6">
              Enterprise-Grade Capabilities, Out of the Box
            </h2>
          </header>

          <div className="max-w-4xl mx-auto overflow-hidden rounded-2xl border border-border shadow-soft">
            <div className="overflow-x-auto">
              <table className="w-full min-w-[480px]" role="table" aria-label="Enterprise feature comparison across Starter, Professional, Agency, and Enterprise plans">
                <thead>
                  <tr className="bg-muted">
                    <th className="text-left py-3 sm:py-4 px-3 sm:px-4 font-semibold text-foreground text-xs sm:text-sm sticky left-0 bg-muted z-10 min-w-[140px] sm:min-w-[200px]" scope="col">Capability</th>
                    {matrixCols.map((col) => (
                      <th key={col.key} className={`text-center py-3 sm:py-4 px-1.5 sm:px-3 font-semibold text-xs sm:text-sm w-16 sm:w-24 ${col.key === "enterprise" ? "text-accent" : "text-foreground"}`} scope="col">
                        {col.label}
                      </th>
                    ))}
                  </tr>
                </thead>
                <tbody>
                  {matrixFeatures.map((feat, i) => (
                    <tr key={feat.name} className={i % 2 === 0 ? "bg-background" : "bg-muted/30"}>
                      <td className="py-2 sm:py-3 px-3 sm:px-4 text-foreground text-xs sm:text-sm sticky left-0 bg-inherit z-10">{feat.name}</td>
                      {matrixCols.map((col) => (
                        <td key={col.key} className={`py-2 sm:py-3 px-1.5 sm:px-3 text-center ${col.key === "enterprise" ? "bg-accent/5" : ""}`}>
                          {feat[col.key] ? (
                            <Check className="w-4 h-4 sm:w-5 sm:h-5 text-primary mx-auto" aria-label="Included" />
                          ) : (
                            <span className="text-muted-foreground text-xs">—</span>
                          )}
                        </td>
                      ))}
                    </tr>
                  ))}
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </section>

      {/* ── 6. Enterprise Pricing ─────────────────── */}
      <section id="enterprise-pricing" className="py-20 lg:py-32 bg-card" aria-labelledby="ent-pricing-heading">
        <div className="container mx-auto px-4 lg:px-8">
          <header className="max-w-3xl mx-auto text-center mb-4">
            <h2 id="ent-pricing-heading" className="text-3xl md:text-4xl font-bold mb-6">
              Enterprise Plans — Built Around Your Scale
            </h2>
            <p className="text-lg text-muted-foreground">
              All Enterprise plans include all Agency features, lifetime updates, and dedicated support. Pricing is annual unless otherwise agreed.
            </p>
          </header>

          <div className="grid grid-cols-1 md:grid-cols-3 gap-6 max-w-6xl mx-auto mt-12">
            {enterprisePlans.map((plan, i) => (
              <article key={plan.name} className="p-6 rounded-2xl bg-background border border-border hover:border-accent/30 transition-all duration-300 animate-fade-in flex flex-col" style={{ animationDelay: `${i * 0.1}s` }}>
                <h3 className="text-xl font-bold text-foreground mb-1">{plan.name}</h3>
                <div className="text-2xl font-bold text-accent mb-2">{plan.price}</div>
                <p className="text-sm text-muted-foreground mb-4">Best for: {plan.audience}</p>

                {plan.includes && (
                  <p className="text-xs font-semibold text-accent uppercase tracking-wide mb-2">{plan.includes}</p>
                )}

                <ul className="space-y-2 mb-6 flex-1 text-left pl-0 list-none">
                  {plan.features.map((feat) => (
                    <li key={feat} className="flex items-start gap-2 text-sm">
                      <Check className="w-4 h-4 text-accent shrink-0 mt-0.5" aria-hidden="true" />
                      <span className="text-foreground">{feat}</span>
                    </li>
                  ))}
                </ul>

                <Button className="w-full mt-auto bg-accent hover:bg-accent/90 text-accent-foreground" asChild>
                  <Link to="/contact/" title={`${plan.cta} for the ${plan.name} plan`} aria-label={`${plan.cta} — ${plan.name} (${plan.price})`}>
                    {plan.cta}
                    <ArrowRight className="w-4 h-4 ml-2" aria-hidden="true" />
                  </Link>
                </Button>
              </article>
            ))}
          </div>

          <p className="max-w-2xl mx-auto mt-8 text-sm text-muted-foreground text-center">
            Not sure which plan fits? Send a brief description of your use case and team size to{" "}
            <Link to="/contact/" className="text-accent hover:underline" title="Contact our Enterprise team for a tailored proposal" aria-label="Contact us to discuss your Enterprise requirements">contact us</Link>{" "}
            and we'll respond within one business day.
          </p>
        </div>
      </section>

      {/* ── 7. Social Proof / Trust ───────────────── */}
      <section className="py-20 lg:py-32 bg-background" aria-labelledby="trust-heading">
        <div className="container mx-auto px-4 lg:px-8">
          <header className="max-w-3xl mx-auto text-center mb-12">
            <h2 id="trust-heading" className="text-3xl md:text-4xl font-bold mb-6">
              Trusted Across Regulated Environments
            </h2>
          </header>

          <div className="flex flex-wrap items-center justify-center gap-8 md:gap-16 mb-12">
            <div className="text-center">
              <div className="text-3xl font-bold text-foreground">2,000+</div>
              <div className="text-sm text-muted-foreground">Active Pro Users</div>
            </div>
            <div className="text-center">
              <div className="text-3xl font-bold text-foreground">3 Platforms</div>
              <div className="text-sm text-muted-foreground">WordPress, Drupal & React</div>
            </div>
            <div className="text-center">
              <div className="text-3xl font-bold text-foreground">GDPR</div>
              <div className="text-sm text-muted-foreground">Compliant Infrastructure</div>
            </div>
            <div className="text-center">
              <div className="text-3xl font-bold text-foreground">30-day</div>
              <div className="text-sm text-muted-foreground">Money-Back Guarantee</div>
            </div>
            <a href="https://www.acquia.com" target="_blank" rel="noopener noreferrer" className="text-center hover:opacity-80 transition-opacity" title="Visit Acquia — the leading Drupal enterprise platform">
              <div className="text-3xl font-bold text-foreground">Acquia</div>
              <div className="text-sm text-muted-foreground">Reviewed Drupal Module</div>
            </a>
          </div>

          <div className="max-w-3xl mx-auto space-y-6">
            <blockquote className="p-6 rounded-2xl bg-card border border-border">
              <p className="text-muted-foreground italic mb-3">
                "We needed a PDF solution that fit our compliance requirements without a six-figure budget. This delivered."
              </p>
              <cite className="text-sm font-medium text-foreground not-italic">
                — IT Lead, European Pharmaceutical Company
              </cite>
            </blockquote>
          </div>
        </div>
      </section>

      {/* ── 8. FAQ ────────────────────────────────── */}
      <section className="py-20 lg:py-32 bg-card" aria-labelledby="ent-faq-heading">
        <div className="container mx-auto px-4 lg:px-8">
          <header className="max-w-3xl mx-auto text-center mb-12">
            <h2 id="ent-faq-heading" className="text-3xl md:text-4xl font-bold mb-6">
              Enterprise FAQ
            </h2>
          </header>

          <div className="max-w-3xl mx-auto">
            <Accordion
              type="single"
              collapsible
              className="space-y-4"
              value={openFaq}
              onValueChange={handleFaqChange}
            >
              {faqs.map((faq, i) => (
                <AccordionItem
                  key={faq.q}
                  id={faqSlugs[i]}
                  value={`ent-faq-${i}`}
                  className="bg-background border border-border rounded-xl px-6 data-[state=open]:border-accent/30 transition-colors"
                  itemScope
                  itemProp="mainEntity"
                  itemType="https://schema.org/Question"
                >
                  <AccordionTrigger
                    className="text-left font-medium text-foreground hover:text-accent py-4"
                    itemProp="name"
                    title={faq.q}
                  >
                    {faq.q}
                  </AccordionTrigger>
                  <AccordionContent
                    className="text-muted-foreground pb-4"
                    itemScope
                    itemProp="acceptedAnswer"
                    itemType="https://schema.org/Answer"
                  >
                    {"isHtml" in faq && faq.isHtml ? (
                      <span itemProp="text" dangerouslySetInnerHTML={{ __html: faq.a }} />
                    ) : (
                      <span itemProp="text">{faq.a}</span>
                    )}
                  </AccordionContent>
                </AccordionItem>
              ))}
            </Accordion>
          </div>
        </div>
      </section>

      {/* ── 9. Contact / CTA ──────────────────────── */}
      <section id="contact" className="py-20 lg:py-32 bg-background" aria-labelledby="contact-heading">
        <div className="container mx-auto px-4 lg:px-8">
          <div className="max-w-3xl mx-auto text-center">
            <h2 id="contact-heading" className="text-3xl md:text-4xl font-bold mb-6">
              Let's Talk About Your Requirements
            </h2>
            <p className="text-lg text-muted-foreground mb-10">
              Every enterprise deployment is different. Get in touch and a member of our team will reach out within one business day
              to understand your use case, answer compliance questions, and put together a tailored proposal.
            </p>

            <Button size="lg" className="bg-accent hover:bg-accent/90 text-accent-foreground text-lg px-10" asChild>
              <Link to="/contact/" title="Contact us for Enterprise consultation" aria-label="Contact us — discuss your Enterprise PDF management requirements">
                Contact Us
                <ArrowRight className="w-5 h-5 ml-2" aria-hidden="true" />
              </Link>
            </Button>

            <p className="mt-6 text-sm text-muted-foreground">
              Or{" "}
              <Link to="/contact/" className="text-accent hover:underline" title="Send us a message through our Enterprise contact form" aria-label="Send us a message via the contact form">send us a message</Link>{" "}
              directly through our contact form.
            </p>
          </div>
        </div>
      </section>

      {/* Sticky floating button */}
      <div
        aria-hidden={!showFloatingButton}
        className={`fixed bottom-6 right-6 z-50 transition-all duration-300 ${
          showFloatingButton
            ? "opacity-100 translate-y-0"
            : "opacity-0 translate-y-4 pointer-events-none"
        }`}
      >
        <Button size="lg" className="bg-accent hover:bg-accent/90 text-accent-foreground shadow-glow" asChild>
          <Link to="/contact/" tabIndex={showFloatingButton ? 0 : -1} title="Request an Enterprise demo and consultation" aria-label="Request Enterprise consultation — schedule a demo">
            Request Demo
            <ArrowRight className="w-4 h-4 ml-2" aria-hidden="true" />
          </Link>
        </Button>
      </div>
    </Layout>
  );
};

export default Enterprise;
