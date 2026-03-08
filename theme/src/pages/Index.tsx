import { Layout } from "@/components/layout/Layout";
import { SEOHead } from "@/components/SEOHead";
import { Hero } from "@/components/home/Hero";
import { Problem } from "@/components/home/Problem";
import { Solution } from "@/components/home/Solution";
import { ComparisonTable } from "@/components/home/ComparisonTable";
import { KeyBenefits } from "@/components/home/KeyBenefits";
import { GEOSection } from "@/components/home/GEOSection";
import { XMLSitemap } from "@/components/home/XMLSitemap";
import { HowItWorks } from "@/components/home/HowItWorks";
import { FAQ } from "@/components/home/FAQ";
import { CTA } from "@/components/home/CTA";
import { PlatformsSection } from "@/components/home/PlatformsSection";

const Index = () => {
  return (
    <Layout>
      <SEOHead
        title="PDF Embed & SEO Optimize – Free WordPress Plugin for SEO-Friendly PDFs"
        description="The best free WordPress plugin for embedding PDFs. Transform your WordPress site's PDFs into SEO-optimized pages with clean URLs, JSON-LD schema, OpenGraph cards, view analytics, and Mozilla PDF.js viewer."
        canonicalPath="/"
      />
      <Hero />
      <PlatformsSection />
      <Problem />
      <Solution />
      <ComparisonTable />
      <KeyBenefits />
      <GEOSection />
      <XMLSitemap />
      <HowItWorks />
      <FAQ />
      <CTA />
    </Layout>
  );
};

export default Index;
