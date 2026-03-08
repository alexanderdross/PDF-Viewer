import { useState, useEffect } from "react";
import { ArrowRight } from "lucide-react";
import { Layout } from "@/components/layout/Layout";
import { SEOHead } from "@/components/SEOHead";
import { Button } from "@/components/ui/button";
import { Breadcrumb } from "@/components/ui/breadcrumb";
import { ProHero } from "@/components/pro/ProHero";
import { ProComparisonTable } from "@/components/pro/ProComparisonTable";
import { ProPricing } from "@/components/pro/ProPricing";
import { ProTestimonials } from "@/components/pro/ProTestimonials";
import { ProDeveloperDocs } from "@/components/pro/ProDeveloperDocs";
import { ProFAQ } from "@/components/pro/ProFAQ";
import { ProCTA } from "@/components/pro/ProCTA";
import { ProSchema } from "@/components/pro/ProSchema";

const Pro = () => {
  const [showFloatingButton, setShowFloatingButton] = useState(false);

  useEffect(() => {
    const handleScroll = () => {
      setShowFloatingButton(window.scrollY > 600);
    };

    window.addEventListener("scroll", handleScroll);
    return () => window.removeEventListener("scroll", handleScroll);
  }, []);

  return (
    <Layout>
      <SEOHead
        title="PDF Embed Pro — Pricing & Plans | WordPress, Drupal & React"
        description="From $49/year to Enterprise — transparent pricing for the PDF Embed & SEO Optimize plugin. Analytics, password protection, RBAC, REST API. Enterprise plans from $2,500/year."
        canonicalPath="/pro/"
      />
      <ProSchema />

      {/* Breadcrumb Navigation */}
      <div className="container mx-auto px-4 pt-6">
        <Breadcrumb />
      </div>

      <ProHero />
      <ProPricing />
      <ProComparisonTable />
      <ProTestimonials />
      <ProDeveloperDocs />
      <ProFAQ />
      <ProCTA />

      {/* Sticky floating button */}
      <div
        aria-hidden={!showFloatingButton}
        className={`fixed bottom-6 right-6 z-50 transition-all duration-300 ${
          showFloatingButton
            ? "opacity-100 translate-y-0"
            : "opacity-0 translate-y-4 pointer-events-none"
        }`}
      >
        <Button size="lg" className="gradient-hero shadow-glow" asChild>
          <a href="#pricing" tabIndex={showFloatingButton ? 0 : -1} aria-label="Get PDF Embed Pro - scroll to pricing options">
            Get Pro
            <ArrowRight className="w-4 h-4 ml-2" aria-hidden="true" />
          </a>
        </Button>
      </div>
    </Layout>
  );
};

export default Pro;
