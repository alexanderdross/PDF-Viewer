import { Layout } from "@/components/layout/Layout";
import { SEOHead } from "@/components/SEOHead";
import { Breadcrumb } from "@/components/ui/breadcrumb";
import { Button } from "@/components/ui/button";
import { ArrowRight, Mail, Building2, Shield } from "lucide-react";

const industries = ["Pharmaceutical", "Life Sciences", "Healthcare", "Finance", "Legal", "Other"];
const siteCounts = ["1–5", "6–20", "21–100", "100+"];

const Contact = () => {
  return (
    <Layout>
      <SEOHead
        title="Contact Us — Enterprise Consultation | PDF Embed & SEO Optimize"
        description="Get in touch for Enterprise pricing, compliance questions, or a tailored proposal. We respond within one business day."
        canonicalPath="/contact/"
      />

      {/* Breadcrumb */}
      <div className="container mx-auto px-4 pt-6">
        <Breadcrumb />
      </div>

      {/* Hero */}
      <section className="relative pt-8 pb-12 md:pt-12 lg:pt-16 lg:pb-16 overflow-hidden">
        <div className="absolute inset-0 bg-gradient-to-b from-accent/5 via-background to-background" aria-hidden="true" />
        <div className="container relative mx-auto px-4 lg:px-8">
          <div className="max-w-3xl mx-auto text-center">
            <div className="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-accent/10 border border-accent/20 text-accent text-sm font-medium mb-8 animate-fade-in">
              <Mail className="w-4 h-4" aria-hidden="true" />
              Contact
            </div>

            <h1 className="text-4xl md:text-5xl font-bold text-foreground mb-6 animate-fade-in" style={{ animationDelay: "0.1s" }}>
              Let's Talk About Your Requirements
            </h1>

            <p className="text-lg text-muted-foreground max-w-2xl mx-auto animate-fade-in" style={{ animationDelay: "0.2s" }}>
              Every deployment is different. Fill in the form below and a member of our team will reach out within one business day
              to understand your use case, answer compliance questions, and put together a tailored proposal.
            </p>
          </div>
        </div>
      </section>

      {/* Contact Form */}
      <section className="pb-20 lg:pb-32 bg-background">
        <div className="container mx-auto px-4 lg:px-8">
          <div className="max-w-2xl mx-auto">
            <form className="space-y-6 bg-card p-8 rounded-2xl border border-border shadow-soft" method="post">
              <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                  <label htmlFor="contact-fname" className="block text-sm font-medium text-foreground mb-1">
                    First Name
                  </label>
                  <input
                    id="contact-fname"
                    type="text"
                    required
                    className="w-full px-4 py-2 rounded-lg border border-border bg-background text-foreground focus:outline-none focus:ring-2 focus:ring-accent/50"
                  />
                </div>
                <div>
                  <label htmlFor="contact-lname" className="block text-sm font-medium text-foreground mb-1">
                    Last Name
                  </label>
                  <input
                    id="contact-lname"
                    type="text"
                    required
                    className="w-full px-4 py-2 rounded-lg border border-border bg-background text-foreground focus:outline-none focus:ring-2 focus:ring-accent/50"
                  />
                </div>
              </div>

              <div>
                <label htmlFor="contact-company" className="block text-sm font-medium text-foreground mb-1">
                  Company Name
                </label>
                <input
                  id="contact-company"
                  type="text"
                  className="w-full px-4 py-2 rounded-lg border border-border bg-background text-foreground focus:outline-none focus:ring-2 focus:ring-accent/50"
                />
              </div>

              <div>
                <label htmlFor="contact-email" className="block text-sm font-medium text-foreground mb-1">
                  Company Email
                </label>
                <input
                  id="contact-email"
                  type="email"
                  required
                  className="w-full px-4 py-2 rounded-lg border border-border bg-background text-foreground focus:outline-none focus:ring-2 focus:ring-accent/50"
                />
              </div>

              <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                  <label htmlFor="contact-industry" className="block text-sm font-medium text-foreground mb-1">
                    Industry
                  </label>
                  <select
                    id="contact-industry"
                    className="w-full px-4 py-2 rounded-lg border border-border bg-background text-foreground focus:outline-none focus:ring-2 focus:ring-accent/50"
                  >
                    <option value="">Select industry</option>
                    {industries.map((ind) => (
                      <option key={ind} value={ind}>{ind}</option>
                    ))}
                  </select>
                </div>
                <div>
                  <label htmlFor="contact-sites" className="block text-sm font-medium text-foreground mb-1">
                    Number of Sites
                  </label>
                  <select
                    id="contact-sites"
                    className="w-full px-4 py-2 rounded-lg border border-border bg-background text-foreground focus:outline-none focus:ring-2 focus:ring-accent/50"
                  >
                    <option value="">Select range</option>
                    {siteCounts.map((count) => (
                      <option key={count} value={count}>{count}</option>
                    ))}
                  </select>
                </div>
              </div>

              <div>
                <label htmlFor="contact-users" className="block text-sm font-medium text-foreground mb-1">
                  Estimated Active Users
                </label>
                <input
                  id="contact-users"
                  type="text"
                  className="w-full px-4 py-2 rounded-lg border border-border bg-background text-foreground focus:outline-none focus:ring-2 focus:ring-accent/50"
                />
              </div>

              <div>
                <label htmlFor="contact-message" className="block text-sm font-medium text-foreground mb-1">
                  Message / Use Case Description
                </label>
                <textarea
                  id="contact-message"
                  rows={4}
                  className="w-full px-4 py-2 rounded-lg border border-border bg-background text-foreground resize-y focus:outline-none focus:ring-2 focus:ring-accent/50"
                />
              </div>

              <label className="flex items-center gap-2 text-sm text-muted-foreground cursor-pointer">
                <input type="checkbox" className="rounded border-border" />
                I'd like to receive the Compliance Overview document
              </label>

              <label className="flex items-start gap-2 text-sm text-muted-foreground cursor-pointer">
                <input type="checkbox" required className="rounded border-border mt-0.5" />
                <span>
                  I accept the{" "}
                  <a href="https://dross.net/privacy-policy?ref=pdfviewer" target="_blank" rel="noopener" className="text-primary hover:underline">Privacy Policy</a>
                  {" "}and agree to the processing of my data. <span className="text-destructive">*</span>
                </span>
              </label>

              {/* Cloudflare Turnstile placeholder */}
              <div className="cf-turnstile" data-sitekey="YOUR_TURNSTILE_SITE_KEY" data-theme="auto">
                <div className="flex items-center gap-2 p-4 rounded-lg border border-dashed border-border bg-muted/30 text-sm text-muted-foreground">
                  <Shield className="w-4 h-4" aria-hidden="true" />
                  <span>Cloudflare Turnstile verification will appear here</span>
                </div>
              </div>

              <Button type="submit" size="lg" className="w-full bg-accent hover:bg-accent/90 text-accent-foreground">
                Request Consultation
                <ArrowRight className="w-5 h-5 ml-2" aria-hidden="true" />
              </Button>
            </form>

            <div className="mt-8 text-center space-y-2">
              <p className="text-sm text-muted-foreground">pdfviewer@drossmedia.de</p>
              <p className="text-sm text-muted-foreground">
                We typically respond within one business day.
              </p>
            </div>
          </div>

          {/* Trust indicators */}
          <div className="max-w-2xl mx-auto mt-12 flex flex-wrap items-center justify-center gap-6 text-sm text-muted-foreground">
            <div className="flex items-center gap-2">
              <Building2 className="w-4 h-4 text-accent" aria-hidden="true" />
              <span>Enterprise-ready</span>
            </div>
            <div className="flex items-center gap-2">
              <Shield className="w-4 h-4 text-accent" aria-hidden="true" />
              <span>GDPR Compliant</span>
            </div>
            <div className="flex items-center gap-2">
              <Mail className="w-4 h-4 text-accent" aria-hidden="true" />
              <span>Response within 1 business day</span>
            </div>
          </div>
        </div>
      </section>
    </Layout>
  );
};

export default Contact;
