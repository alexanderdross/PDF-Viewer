import { Layout } from "@/components/layout/Layout";
import { SEOHead } from "@/components/SEOHead";
import { useJsonLd } from "@/hooks/use-json-ld";
import { Button } from "@/components/ui/button";
import {
  FileText,
  Download,
  ArrowRight,
  Monitor,
  Smartphone,
  Tablet,
  Moon,
  Sun,
  Zap,
  Shield,
  Globe,
  CheckCircle,
  Check,
  Code,
  Blocks,
  Search,
  Share2,
  BarChart3,
  Terminal,
  Package,
  Layers
} from "lucide-react";
import { NextjsIcon, ReactIcon, TypeScriptIcon } from "@/components/icons/PlatformIcons";

const viewerFeatures = [
  {
    icon: Monitor,
    title: "Desktop Optimized",
    description: "Full-featured viewer with zoom, search, page navigation, and document outline on desktop browsers."
  },
  {
    icon: Smartphone,
    title: "Mobile Responsive",
    description: "Touch-friendly controls and responsive layout that works perfectly on phones."
  },
  {
    icon: Tablet,
    title: "Tablet Ready",
    description: "Optimized for iPad and Android tablets with gesture support."
  }
];

const themeOptions = [
  {
    icon: Sun,
    title: "Light Theme",
    description: "Clean, bright interface that matches light mode applications."
  },
  {
    icon: Moon,
    title: "Dark Theme",
    description: "Easy on the eyes for dark mode applications and themes."
  }
];

const requirements = [
  "React 18.0 or higher",
  "Next.js 13.0+ (optional, for SSR/SSG)",
  "TypeScript 5.0+ (recommended)",
  "Node.js 18+ runtime"
];

const installSteps = [
  "Install the package via npm, yarn, or pnpm",
  "Wrap your app with PdfProvider",
  "Import and use the PdfViewer component",
  "Configure SEO metadata generation (optional)"
];

const coreFeatures = [
  {
    icon: FileText,
    title: "PDF.js Viewer",
    description: "Built on Mozilla PDF.js v4.0 for reliable, high-quality PDF rendering in any browser."
  },
  {
    icon: Blocks,
    title: "React Components",
    description: "Ready-to-use components: PdfViewer, PdfArchive, PdfCard, PdfBreadcrumbs, and more."
  },
  {
    icon: Search,
    title: "SEO Optimized",
    description: "Schema.org JSON-LD, OpenGraph, Twitter Cards - all handled automatically."
  },
  {
    icon: Code,
    title: "TypeScript Native",
    description: "100% TypeScript with strict mode. Full type safety and IntelliSense support."
  },
  {
    icon: Layers,
    title: "Next.js Integration",
    description: "First-class support for App Router, generateMetadata, generateStaticParams, and more."
  },
  {
    icon: Globe,
    title: "Headless CMS Ready",
    description: "Connect to WordPress, Drupal, or any backend with built-in API adapters."
  }
];

const seoBenefits = [
  {
    icon: Search,
    title: "SEO-Optimized Routes",
    description: "Clean URLs like /pdf/document-name/ with full SSR/SSG support."
  },
  {
    icon: Share2,
    title: "Social Sharing Ready",
    description: "Auto-generated OpenGraph and Twitter cards with PDF thumbnail previews."
  },
  {
    icon: BarChart3,
    title: "View Analytics",
    description: "Track PDF views and engagement with built-in analytics hooks."
  },
  {
    icon: FileText,
    title: "Beautiful Viewer",
    description: "Mozilla PDF.js provides consistent rendering across all browsers and devices."
  }
];

const packages = [
  {
    name: "@pdf-embed-seo/core",
    description: "Shared utilities, types, and API clients. Used by other packages.",
    license: "MIT",
    size: "~15KB gzipped",
    command: "npm i @pdf-embed-seo/core",
    primary: false,
  },
  {
    name: "@pdf-embed-seo/react",
    description: "React components, hooks, and Next.js utilities. Everything you need for free features.",
    license: "MIT",
    size: "~50KB gzipped",
    command: "npm i @pdf-embed-seo/react",
    primary: true,
  },
  {
    name: "@pdf-embed-seo/react-premium",
    description: "Premium features: password protection, reading progress, analytics, search, and more.",
    license: "Commercial",
    size: "~30KB gzipped",
    command: null,
    primary: false,
    isPremium: true,
  },
];

const freeVsProVsProPlus = [
  { feature: "PdfViewer Component", free: true, pro: true, proPlus: true },
  { feature: "PdfArchive Component", free: true, pro: true, proPlus: true },
  { feature: "Light/Dark/System Theme", free: true, pro: true, proPlus: true },
  { feature: "Print/Download Controls", free: true, pro: true, proPlus: true },
  { feature: "Schema.org SEO", free: true, pro: true, proPlus: true },
  { feature: "Next.js Metadata API", free: true, pro: true, proPlus: true },
  { feature: "React Hooks", free: true, pro: true, proPlus: true },
  { feature: "API Client (WP/Drupal)", free: true, pro: true, proPlus: true },
  { feature: "Text Search (PdfSearch)", free: false, pro: true, proPlus: true },
  { feature: "Bookmark Navigation (PdfBookmarks)", free: false, pro: true, proPlus: true },
  { feature: "Password Protection (PdfPasswordModal)", free: false, pro: true, proPlus: true },
  { feature: "Analytics Dashboard (PdfAnalytics)", free: false, pro: true, proPlus: true },
  { feature: "useAnalytics Hook", free: false, pro: true, proPlus: true },
  { feature: "usePassword Hook", free: false, pro: true, proPlus: true },
  { feature: "useSearch Hook", free: false, pro: true, proPlus: true },
  { feature: "useBookmarks Hook", free: false, pro: true, proPlus: true },
  { feature: "Reading Progress (PdfProgressBar)", free: false, pro: true, proPlus: true },
  { feature: "AI/GEO/AEO Schema", free: false, pro: true, proPlus: true },
  { feature: "Expiring Access Links", free: false, pro: true, proPlus: true },
  { feature: "Priority Support", free: false, pro: true, proPlus: true },
  { feature: "PDF Annotations", free: false, pro: false, proPlus: true },
  { feature: "Document Versioning", free: false, pro: false, proPlus: true },
  { feature: "Heatmaps & Engagement Scoring", free: false, pro: false, proPlus: true },
  { feature: "Two-Factor Authentication (2FA)", free: false, pro: false, proPlus: true },
  { feature: "Webhooks API (Zapier, etc.)", free: false, pro: false, proPlus: true },
  { feature: "White Label Mode", free: false, pro: false, proPlus: true },
  { feature: "HIPAA/GDPR Compliance Mode", free: false, pro: false, proPlus: true },
  { feature: "Dedicated Account Manager + SLA", free: false, pro: false, proPlus: true },
];

const pdfViewerSchema = {
  "@context": "https://schema.org",
  "@type": "SoftwareApplication",
  "name": "React PDF Viewer Component",
  "alternateName": "@pdf-embed-seo/react",
  "applicationCategory": "npm Package",
  "applicationSubCategory": "React Component Library",
  "operatingSystem": "React 18+, Next.js 13+",
  "softwareVersion": "1.2.11",
  "offers": {
    "@type": "Offer",
    "price": "0",
    "priceCurrency": "EUR"
  },
  "description": "Modern React components for PDF viewing with TypeScript support, SSR/SSG, and SEO optimization. Works with Next.js App Router and Pages Router.",
  "featureList": [
    "Mozilla PDF.js integration (v4.0)",
    "React 18+ components",
    "Next.js App Router support",
    "TypeScript native",
    "SSR/SSG support",
    "Schema.org SEO",
    "Headless CMS integration",
    "React Hooks API",
    "Light/Dark/System themes",
    "iOS/Safari print support",
    "Customizable styling"
  ],
  "downloadUrl": "https://www.npmjs.com/package/@pdf-embed-seo/react",
  "softwareRequirements": "React 18+, Node.js 18+"
};

const NextjsPdfViewer = () => {
  useJsonLd("nextjs-pdf-viewer-schema", pdfViewerSchema);

  return (
    <Layout>
      <SEOHead
        title="React/Next.js PDF Viewer – Modern Components with TypeScript & SEO"
        description="Modern React components for PDF viewing with full TypeScript support, SSR/SSG, and SEO optimization. Works with Next.js App Router and Pages Router. Free and open source."
        canonicalPath="/nextjs-pdf-viewer/"
      />

      {/* Hero Section */}
      <section className="relative py-16 lg:py-24 bg-gradient-to-b from-foreground to-foreground/95 text-primary-foreground overflow-hidden" aria-labelledby="hero-heading">
        <div className="absolute inset-0 opacity-10" aria-hidden="true">
          <div className="absolute top-20 left-10 w-72 h-72 bg-primary rounded-full blur-3xl" />
          <div className="absolute bottom-20 right-10 w-96 h-96 bg-accent rounded-full blur-3xl" />
        </div>

        <div className="container mx-auto px-4 lg:px-8 relative">
          <div className="max-w-4xl mx-auto text-center">
            <div className="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-primary/20 text-primary-foreground text-sm font-medium mb-6">
              <Zap className="w-4 h-4" aria-hidden="true" />
              <span>NEW</span>
              <span className="mx-2">•</span>
              <span>Version 1.2.11 Released</span>
            </div>

            <h1 id="hero-heading" className="text-4xl md:text-5xl lg:text-6xl font-extrabold tracking-tight mb-6">
              React/Next.js PDF Viewer
            </h1>

            <p className="text-xl md:text-2xl text-primary-foreground/80 max-w-2xl mx-auto mb-8">
              Modern, type-safe React components for displaying PDFs with full SEO optimization.
              Works seamlessly with Next.js App Router and Pages Router.
            </p>

            <div className="bg-foreground/30 backdrop-blur-sm rounded-lg px-6 py-4 inline-block mb-8 border border-primary-foreground/20">
              <code className="text-lg font-mono">npm install @pdf-embed-seo/react</code>
            </div>

            <div className="flex flex-col sm:flex-row items-center justify-center gap-4 mb-10">
              <Button size="lg" className="gradient-hero shadow-glow text-lg px-8" asChild>
                <a href="#get-started" title="Get started with the React PDF viewer">
                  Get Started
                  <ArrowRight className="w-5 h-5 ml-2" aria-hidden="true" />
                </a>
              </Button>
              <Button size="lg" variant="outline" className="text-lg px-8 border-primary-foreground/30 text-primary-foreground hover:bg-primary-foreground/10" asChild>
                <a
                  href="https://github.com/drossmedia/pdf-embed-seo-optimize"
                  target="_blank"
                  rel="noopener noreferrer"
                  title="View source on GitHub"
                >
                  View on GitHub
                </a>
              </Button>
            </div>

            <div className="flex items-center justify-center gap-4 text-primary-foreground/60">
              <span className="text-sm">Built for:</span>
              <div className="flex items-center gap-3">
                <ReactIcon size={28} className="text-primary-foreground/80" />
                <NextjsIcon size={28} className="text-primary-foreground/80" />
                <TypeScriptIcon size={28} className="text-primary-foreground/80" />
              </div>
            </div>
          </div>
        </div>
      </section>

      {/* Core Features */}
      <section className="py-16 lg:py-24 bg-muted/30" id="features" aria-labelledby="features-heading">
        <div className="container mx-auto px-4 lg:px-8">
          <div className="text-center max-w-3xl mx-auto mb-12">
            <h2 id="features-heading" className="text-3xl md:text-4xl font-bold mb-4">
              Everything You Need for PDFs in React
            </h2>
            <p className="text-lg text-muted-foreground">
              A complete toolkit for embedding and managing PDFs in modern React applications.
            </p>
          </div>

          <div className="grid md:grid-cols-2 lg:grid-cols-3 gap-6 lg:gap-8">
            {coreFeatures.map((feature) => (
              <div key={feature.title} className="bg-card rounded-2xl border border-border p-6 text-center hover:shadow-medium transition-shadow">
                <div className="w-14 h-14 rounded-xl gradient-hero flex items-center justify-center mx-auto mb-4">
                  <feature.icon className="w-7 h-7 text-primary-foreground" aria-hidden="true" />
                </div>
                <h3 className="text-lg font-semibold mb-2">{feature.title}</h3>
                <p className="text-sm text-muted-foreground">{feature.description}</p>
              </div>
            ))}
          </div>
        </div>
      </section>

      {/* Quick Start / Code Examples */}
      <section className="py-16 lg:py-24" id="get-started" aria-labelledby="quickstart-heading">
        <div className="container mx-auto px-4 lg:px-8">
          <div className="text-center max-w-3xl mx-auto mb-12">
            <h2 id="quickstart-heading" className="text-3xl md:text-4xl font-bold mb-4">
              Quick Start
            </h2>
            <p className="text-lg text-muted-foreground">
              Get up and running in minutes with these code examples.
            </p>
          </div>

          <div className="max-w-4xl mx-auto space-y-8">
            {/* Basic Usage */}
            <div className="bg-card rounded-2xl border border-border overflow-hidden">
              <div className="px-6 py-4 bg-muted border-b border-border">
                <h3 className="font-semibold">Basic Usage</h3>
              </div>
              <pre className="p-6 overflow-x-auto text-sm">
                <code className="text-primary">{`import { PdfViewer, PdfProvider } from '@pdf-embed-seo/react';
import '@pdf-embed-seo/react/styles';

function App() {
  return (
    <PdfProvider config={{ siteUrl: 'https://example.com' }}>
      <PdfViewer
        src="/documents/report.pdf"
        height="600px"
        theme="light"
        allowDownload
        onPageChange={(page) => console.log(\`Page: \${page}\`)}
      />
    </PdfProvider>
  );
}`}</code>
              </pre>
            </div>

            {/* Next.js App Router */}
            <div className="bg-card rounded-2xl border border-border overflow-hidden">
              <div className="px-6 py-4 bg-muted border-b border-border">
                <h3 className="font-semibold">Next.js App Router Integration</h3>
              </div>
              <pre className="p-6 overflow-x-auto text-sm">
                <code className="text-primary">{`// app/pdf/[slug]/page.tsx
import { PdfViewer, PdfJsonLd, PdfBreadcrumbs } from '@pdf-embed-seo/react';
import { generatePdfMetadata } from '@pdf-embed-seo/react/nextjs';

// Generate metadata for SEO
export async function generateMetadata({ params }) {
  const document = await getPdfDocument(params.slug);
  return generatePdfMetadata(document, {
    siteUrl: process.env.NEXT_PUBLIC_SITE_URL,
  });
}

// Generate static paths
export async function generateStaticParams() {
  const documents = await getAllPdfDocuments();
  return documents.map((doc) => ({ slug: doc.slug }));
}

export default async function PdfPage({ params }) {
  const document = await getPdfDocument(params.slug);

  return (
    <main>
      <PdfJsonLd document={document} includeBreadcrumbs />
      <PdfBreadcrumbs document={document} />
      <h1>{document.title}</h1>
      <PdfViewer src={document} height="800px" />
    </main>
  );
}`}</code>
              </pre>
            </div>

            {/* Using Hooks */}
            <div className="bg-card rounded-2xl border border-border overflow-hidden">
              <div className="px-6 py-4 bg-muted border-b border-border">
                <h3 className="font-semibold">Using React Hooks</h3>
              </div>
              <pre className="p-6 overflow-x-auto text-sm">
                <code className="text-primary">{`import { usePdfDocument, usePdfViewer, usePdfSeo } from '@pdf-embed-seo/react';

function CustomPdfPage({ slug }) {
  // Fetch document
  const { document, isLoading, error } = usePdfDocumentBySlug(slug);

  // Viewer controls
  const { currentPage, totalPages, nextPage, prevPage, zoomIn, zoomOut } = usePdfViewer();

  // SEO metadata
  const { jsonLd, metaTags } = usePdfSeo(document);

  if (isLoading) return <Loading />;
  if (error) return <Error message={error.message} />;

  return (
    <>
      <script type="application/ld+json"
        dangerouslySetInnerHTML={{ __html: JSON.stringify(jsonLd) }} />
      <div className="viewer-controls">
        <button onClick={prevPage}>Prev</button>
        <span>{currentPage} / {totalPages}</span>
        <button onClick={nextPage}>Next</button>
      </div>
      <PdfViewer src={document} />
    </>
  );
}`}</code>
              </pre>
            </div>
          </div>
        </div>
      </section>

      {/* PDF.js Viewer Section */}
      <section className="py-16 lg:py-24 bg-card" aria-labelledby="pdfjs-heading">
        <div className="container mx-auto px-4 lg:px-8">
          <div className="text-center max-w-3xl mx-auto mb-12">
            <h2 id="pdfjs-heading" className="text-3xl md:text-4xl font-bold mb-4">
              Built on Mozilla's PDF.js (v4.0)
            </h2>
            <p className="text-lg text-muted-foreground">
              Industry-standard PDF rendering that works consistently across all browsers and devices.
            </p>
          </div>

          <div className="grid md:grid-cols-3 gap-6 lg:gap-8 mb-12">
            {viewerFeatures.map((feature) => (
              <div key={feature.title} className="bg-background rounded-2xl border border-border p-6">
                <div className="w-12 h-12 rounded-xl bg-primary/10 flex items-center justify-center mb-4">
                  <feature.icon className="w-6 h-6 text-primary" aria-hidden="true" />
                </div>
                <h3 className="font-semibold text-lg mb-2">{feature.title}</h3>
                <p className="text-sm text-muted-foreground">{feature.description}</p>
              </div>
            ))}
          </div>

          {/* Theme Options */}
          <div className="text-center mb-8">
            <h3 className="text-2xl font-bold mb-8">Light & Dark Themes</h3>
          </div>
          <div className="grid md:grid-cols-2 gap-6 lg:gap-8 max-w-3xl mx-auto">
            {themeOptions.map((theme) => (
              <div key={theme.title} className="bg-background rounded-2xl border border-border p-6 text-center">
                <div className="w-12 h-12 rounded-xl bg-primary/10 flex items-center justify-center mx-auto mb-4">
                  <theme.icon className="w-6 h-6 text-primary" aria-hidden="true" />
                </div>
                <h4 className="text-xl font-semibold mb-2">{theme.title}</h4>
                <p className="text-sm text-muted-foreground">{theme.description}</p>
              </div>
            ))}
          </div>
        </div>
      </section>

      {/* Packages Section */}
      <section className="py-16 lg:py-24 bg-muted/30" aria-labelledby="packages-heading">
        <div className="container mx-auto px-4 lg:px-8">
          <div className="text-center max-w-3xl mx-auto mb-12">
            <h2 id="packages-heading" className="text-3xl md:text-4xl font-bold mb-4">
              Available Packages
            </h2>
            <p className="text-lg text-muted-foreground">
              Choose the package that fits your needs.
            </p>
          </div>

          <div className="grid md:grid-cols-3 gap-6 lg:gap-8">
            {packages.map((pkg) => (
              <div
                key={pkg.name}
                className={`relative bg-card rounded-2xl border p-6 ${
                  pkg.primary ? "border-primary shadow-medium" : "border-border"
                }`}
              >
                {pkg.isPremium && (
                  <div className="absolute -top-3 right-6 px-3 py-1 bg-accent text-accent-foreground text-xs font-bold rounded-full">
                    PRO
                  </div>
                )}
                <div className="flex items-center justify-between mb-3">
                  <h3 className="font-mono text-sm font-semibold">{pkg.name}</h3>
                  <span className="text-xs text-muted-foreground bg-muted px-2 py-1 rounded">
                    {pkg.license}
                  </span>
                </div>
                <p className="text-sm text-muted-foreground mb-4">{pkg.description}</p>
                <div className="flex gap-4 text-xs text-muted-foreground mb-4">
                  <span>{pkg.size}</span>
                </div>
                {pkg.command ? (
                  <pre className="bg-muted px-4 py-3 rounded-lg text-sm">
                    <code className="text-primary">{pkg.command}</code>
                  </pre>
                ) : (
                  <Button asChild className="w-full">
                    <a href="/pro/" title="Get Premium license">
                      Get Premium
                    </a>
                  </Button>
                )}
              </div>
            ))}
          </div>
        </div>
      </section>

      {/* Free vs Pro vs Pro+ Comparison */}
      <section className="py-16 lg:py-24" aria-labelledby="comparison-heading">
        <div className="container mx-auto px-4 lg:px-8">
          <div className="text-center max-w-3xl mx-auto mb-12">
            <h2 id="comparison-heading" className="text-3xl md:text-4xl font-bold mb-4">
              Free vs Pro vs Pro+
            </h2>
          </div>

          <div className="max-w-4xl mx-auto">
            {/* Desktop Table */}
            <div className="hidden md:block overflow-hidden rounded-2xl border border-border shadow-soft">
              <table className="w-full" role="table" aria-label="React/Next.js feature comparison between Free, Pro, and Pro+ versions">
                <thead>
                  <tr className="bg-muted">
                    <th scope="col" className="text-left py-4 px-6 font-semibold text-foreground">Feature</th>
                    <th scope="col" className="text-center py-4 px-6 font-semibold text-foreground w-24">Free</th>
                    <th scope="col" className="text-center py-4 px-6 font-semibold text-primary w-24">Pro</th>
                    <th scope="col" className="text-center py-4 px-6 font-semibold text-accent w-24">Pro+</th>
                  </tr>
                </thead>
                <tbody>
                  {freeVsProVsProPlus.map((row, index) => (
                    <tr key={row.feature} className={index % 2 === 0 ? "bg-background" : "bg-muted/30"}>
                      <td className="py-3 px-6 text-foreground">{row.feature}</td>
                      <td className="py-3 px-6 text-center">
                        {row.free ? (
                          <Check className="w-5 h-5 text-primary mx-auto" aria-label="Included" />
                        ) : (
                          <span className="text-muted-foreground" aria-label="Not included">-</span>
                        )}
                      </td>
                      <td className="py-3 px-6 text-center bg-primary/10">
                        {row.pro ? (
                          <Check className="w-5 h-5 text-primary mx-auto" aria-label="Included" />
                        ) : (
                          <span className="text-muted-foreground" aria-label="Not included">-</span>
                        )}
                      </td>
                      <td className="py-3 px-6 text-center bg-accent/10">
                        {row.proPlus ? (
                          <Check className="w-5 h-5 text-primary mx-auto" aria-label="Included" />
                        ) : (
                          <span className="text-muted-foreground" aria-label="Not included">-</span>
                        )}
                      </td>
                    </tr>
                  ))}
                </tbody>
                <tfoot>
                  <tr className="bg-muted">
                    <td className="py-4 px-6"></td>
                    <td className="py-4 px-6 text-center">
                      <Button variant="outline" size="sm" asChild>
                        <a
                          href="https://www.npmjs.com/package/@pdf-embed-seo/react"
                          target="_blank"
                          rel="noopener noreferrer"
                        >
                          Install Free
                        </a>
                      </Button>
                    </td>
                    <td className="py-4 px-6 text-center">
                      <Button size="sm" asChild>
                        <a href="/pro/">Get Pro</a>
                      </Button>
                    </td>
                    <td className="py-4 px-6 text-center">
                      <Button size="sm" className="gradient-accent" asChild>
                        <a href="/pro/">Get Pro+</a>
                      </Button>
                    </td>
                  </tr>
                </tfoot>
              </table>
            </div>

            {/* Mobile Cards */}
            <div className="md:hidden space-y-4">
              {freeVsProVsProPlus.map((row) => (
                <div key={row.feature} className="bg-background rounded-xl border border-border overflow-hidden p-4">
                  <div className="font-medium text-foreground mb-2">{row.feature}</div>
                  <div className="flex items-center gap-4 text-sm">
                    <div className="flex items-center gap-2">
                      <span className="text-muted-foreground">Free:</span>
                      {row.free ? (
                        <Check className="w-4 h-4 text-primary" aria-label="Included" />
                      ) : (
                        <span className="text-muted-foreground" aria-label="Not included">-</span>
                      )}
                    </div>
                    <div className="flex items-center gap-2">
                      <span className="text-primary font-medium">Pro:</span>
                      {row.pro ? (
                        <Check className="w-4 h-4 text-primary" aria-label="Included" />
                      ) : (
                        <span className="text-muted-foreground" aria-label="Not included">-</span>
                      )}
                    </div>
                    <div className="flex items-center gap-2">
                      <span className="text-accent font-medium">Pro+:</span>
                      {row.proPlus ? (
                        <Check className="w-4 h-4 text-primary" aria-label="Included" />
                      ) : (
                        <span className="text-muted-foreground" aria-label="Not included">-</span>
                      )}
                    </div>
                  </div>
                </div>
              ))}

              {/* Mobile CTA buttons */}
              <div className="flex flex-col gap-3 pt-4">
                <Button variant="outline" size="sm" asChild>
                  <a
                    href="https://www.npmjs.com/package/@pdf-embed-seo/react"
                    target="_blank"
                    rel="noopener noreferrer"
                  >
                    Install Free
                  </a>
                </Button>
                <Button size="sm" asChild>
                  <a href="/pro/">Get Pro</a>
                </Button>
                <Button size="sm" className="gradient-accent" asChild>
                  <a href="/pro/">Get Pro+</a>
                </Button>
              </div>
            </div>
          </div>
        </div>
      </section>

      {/* Requirements */}
      <section className="py-16 lg:py-24 bg-card" aria-labelledby="requirements-heading">
        <div className="container mx-auto px-4 lg:px-8">
          <div className="max-w-4xl mx-auto">
            <div className="grid md:grid-cols-2 gap-8 lg:gap-12">
              <div>
                <h2 id="requirements-heading" className="text-2xl font-bold mb-6">Requirements</h2>
                <ul className="space-y-3">
                  {requirements.map((req) => (
                    <li key={req} className="flex items-start gap-3">
                      <CheckCircle className="w-5 h-5 text-primary shrink-0 mt-0.5" aria-hidden="true" />
                      <span className="text-muted-foreground">{req}</span>
                    </li>
                  ))}
                </ul>
              </div>

              <div>
                <h2 className="text-2xl font-bold mb-6">Installation</h2>
                <ol className="space-y-3">
                  {installSteps.map((step, index) => (
                    <li key={step} className="flex items-start gap-3">
                      <span className="w-6 h-6 rounded-full bg-primary text-primary-foreground text-sm font-bold flex items-center justify-center shrink-0">
                        {index + 1}
                      </span>
                      <span className="text-muted-foreground">{step}</span>
                    </li>
                  ))}
                </ol>
              </div>
            </div>
          </div>
        </div>
      </section>

      {/* CTA Section */}
      <section className="py-16 lg:py-24 gradient-hero text-primary-foreground" aria-labelledby="cta-heading">
        <div className="container mx-auto px-4 lg:px-8 text-center">
          <h2 id="cta-heading" className="text-3xl md:text-4xl font-bold mb-4">
            Ready to Get Started?
          </h2>
          <p className="text-xl text-primary-foreground/80 mb-8 max-w-2xl mx-auto">
            Install the package and have a working PDF viewer in minutes.
          </p>

          <div className="bg-primary-foreground/10 backdrop-blur-sm rounded-lg px-6 py-4 inline-block mb-8 border border-primary-foreground/20">
            <code className="text-lg font-mono">npm install @pdf-embed-seo/react</code>
          </div>

          <div className="flex flex-col sm:flex-row items-center justify-center gap-4">
            <Button size="lg" variant="secondary" className="text-lg px-8" asChild>
              <a href="/documentation/#react" title="Read the React documentation">
                Read the Docs
              </a>
            </Button>
            <Button size="lg" variant="outline" className="text-lg px-8 border-primary-foreground/30 text-primary-foreground hover:bg-primary-foreground/10" asChild>
              <a
                href="https://github.com/drossmedia/pdf-embed-seo-optimize/tree/main/react-pdf-embed-seo"
                target="_blank"
                rel="noopener noreferrer"
                title="View source code"
              >
                View Source
              </a>
            </Button>
          </div>
        </div>
      </section>
    </Layout>
  );
};

export default NextjsPdfViewer;
