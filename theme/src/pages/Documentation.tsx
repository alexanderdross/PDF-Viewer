import { useState, useEffect } from "react";
import { Layout } from "@/components/layout/Layout";
import { SEOHead } from "@/components/SEOHead";
import { Link, useLocation, useNavigate } from "react-router-dom";
import { 
  FileText, 
  Code2, 
  Puzzle, 
  Globe, 
  Zap, 
  Database, 
  Terminal,
  Settings,
  Shield,
  BookOpen,
  BarChart3,
  Lock,
  Clock
} from "lucide-react";
import { Button } from "@/components/ui/button";
import { Tabs, TabsContent, TabsList, TabsTrigger } from "@/components/ui/tabs";
import { WordPressIcon, DrupalIcon, ReactIcon } from "@/components/icons/PlatformIcons";

const Documentation = () => {
  const location = useLocation();
  const navigate = useNavigate();
  
  // Get initial platform from hash or default to wordpress
  const getInitialPlatform = (): "wordpress" | "drupal" | "react" => {
    const hash = location.hash.replace("#", "");
    if (hash === "drupal") return "drupal";
    if (hash === "react") return "react";
    return "wordpress";
  };

  const [platform, setPlatform] = useState<"wordpress" | "drupal" | "react">(getInitialPlatform);

  // Sync hash with platform state
  useEffect(() => {
    const hash = location.hash.replace("#", "");
    if (hash === "wordpress" || hash === "drupal" || hash === "react") {
      setPlatform(hash);
    }
  }, [location.hash]);

  const handlePlatformChange = (value: string) => {
    const newPlatform = value as "wordpress" | "drupal" | "react";
    setPlatform(newPlatform);
    navigate(`#${newPlatform}`, { replace: true });
  };

  return (
    <Layout>
      <SEOHead
        title="Documentation – Getting Started with PDF Embed & SEO Optimize"
        description="Complete guide to using PDF Embed & SEO Optimize for WordPress, Drupal, and React/Next.js. Learn about installation, configuration, REST API, hooks, components, and premium features."
        canonicalPath="/documentation/"
      />
      
      {/* Hero */}
      <section className="py-16 lg:py-24 bg-card" aria-labelledby="docs-heading">
        <div className="container mx-auto px-4 lg:px-8">
          <div className="max-w-3xl">
            <div className="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-primary/10 text-primary text-sm font-medium mb-6">
              <BookOpen className="w-4 h-4" aria-hidden="true" />
              <span>Documentation</span>
            </div>
            <h1 id="docs-heading" className="text-4xl md:text-5xl font-bold mb-6">
              Getting Started Guide
            </h1>
            <p className="text-xl text-muted-foreground">
              Everything you need to know to start sharing your PDFs professionally.
              Complete documentation for WordPress, Drupal, and React/Next.js.
            </p>
          </div>
        </div>
      </section>

      {/* Platform Tabs */}
      <section className="py-8 border-b border-border sticky top-12 bg-background z-40">
        <div className="container mx-auto px-4 lg:px-8">
          <Tabs value={platform} onValueChange={handlePlatformChange} className="w-full" aria-label="Select platform documentation">
            <TabsList className="grid w-full max-w-lg grid-cols-3">
              <TabsTrigger value="wordpress" className="gap-2" aria-label="WordPress PDF Embed plugin documentation - installation, shortcodes, and hooks" title="WordPress PDF Viewer Plugin Documentation - Install and configure the free WordPress plugin">
                <WordPressIcon size={18} aria-hidden="true" />
                WordPress
              </TabsTrigger>
              <TabsTrigger value="drupal" className="gap-2" aria-label="Drupal PDF Embed module documentation - installation, blocks, and hooks" title="Drupal PDF Viewer Module Documentation - Install and configure the free Drupal module">
                <DrupalIcon size={18} aria-hidden="true" />
                Drupal
              </TabsTrigger>
              <TabsTrigger value="react" className="gap-2" aria-label="React and Next.js PDF viewer components documentation - npm package and hooks" title="React/Next.js PDF Viewer Components - Install via npm and use PdfViewer component">
                <ReactIcon size={18} aria-hidden="true" />
                React
              </TabsTrigger>
            </TabsList>
          </Tabs>
        </div>
      </section>

      {/* Content */}
      <section className="py-16 lg:py-24">
        <div className="container mx-auto px-4 lg:px-8">
          <div className="max-w-4xl mx-auto space-y-16">
            
            {/* Getting Started */}
            <div className="animate-fade-in">
              <div className="flex items-start gap-4 mb-6">
                <div className="w-12 h-12 rounded-xl gradient-hero flex items-center justify-center shrink-0">
                  <Terminal className="w-6 h-6 text-primary-foreground" />
                </div>
                <div>
                  <h2 className="text-2xl font-bold mb-2">Getting Started</h2>
                  <p className="text-muted-foreground">
                    Install and configure the plugin in minutes
                  </p>
                </div>
              </div>

              {platform === "wordpress" ? (
                <div className="space-y-4">
                  <div className="bg-card rounded-2xl border border-border p-6">
                    <p className="text-sm font-medium text-foreground mb-3">Via WordPress Admin:</p>
                    <ol className="list-decimal list-inside space-y-2 text-muted-foreground">
                      <li>Go to Plugins {">"} Add New</li>
                      <li>Search for "PDF Embed SEO Optimize"</li>
                      <li>Click Install, then Activate</li>
                    </ol>
                  </div>
                  <div className="bg-card rounded-2xl border border-border p-6">
                    <p className="text-sm font-medium text-foreground mb-3">Via WP-CLI:</p>
                    <code className="block bg-muted px-4 py-3 rounded-lg font-mono text-primary text-sm">
                      wp plugin install pdf-embed-seo-optimize --activate
                    </code>
                  </div>
                </div>
              ) : platform === "drupal" ? (
                <div className="space-y-4">
                  <div className="bg-card rounded-2xl border border-border p-6">
                    <p className="text-sm font-medium text-foreground mb-3">Via Composer (recommended):</p>
                    <div className="space-y-2">
                      <code className="block bg-muted px-4 py-3 rounded-lg font-mono text-primary text-sm">
                        composer require drossmedia/pdf_embed_seo
                      </code>
                      <code className="block bg-muted px-4 py-3 rounded-lg font-mono text-primary text-sm">
                        drush en pdf_embed_seo
                      </code>
                    </div>
                  </div>
                  <div className="bg-card rounded-2xl border border-border p-6">
                    <p className="text-sm font-medium text-foreground mb-3">For Premium:</p>
                    <code className="block bg-muted px-4 py-3 rounded-lg font-mono text-primary text-sm">
                      drush en pdf_embed_seo_premium
                    </code>
                  </div>
                </div>
              ) : (
                <div className="space-y-4">
                  <div className="bg-card rounded-2xl border border-border p-6">
                    <p className="text-sm font-medium text-foreground mb-3">Via npm/yarn/pnpm:</p>
                    <div className="space-y-2">
                      <code className="block bg-muted px-4 py-3 rounded-lg font-mono text-primary text-sm">
                        npm install @pdf-embed-seo/react
                      </code>
                      <code className="block bg-muted px-4 py-3 rounded-lg font-mono text-primary text-sm">
                        yarn add @pdf-embed-seo/react
                      </code>
                      <code className="block bg-muted px-4 py-3 rounded-lg font-mono text-primary text-sm">
                        pnpm add @pdf-embed-seo/react
                      </code>
                    </div>
                  </div>
                  <div className="bg-card rounded-2xl border border-border p-6">
                    <p className="text-sm font-medium text-foreground mb-3">For Premium:</p>
                    <code className="block bg-muted px-4 py-3 rounded-lg font-mono text-primary text-sm">
                      npm install @pdf-embed-seo/react-premium
                    </code>
                  </div>
                  <div className="bg-card rounded-2xl border border-border p-6">
                    <h3 className="font-semibold text-foreground mb-4">Quick Embed Steps</h3>
                    <ol className="list-decimal list-inside space-y-2 text-muted-foreground">
                      <li>Wrap your app with PdfProvider</li>
                      <li>Import the PdfViewer component</li>
                      <li>Pass a PDF document URL or object</li>
                      <li>Configure SEO with generatePdfMetadata (Next.js)</li>
                    </ol>
                  </div>
                </div>
              )}
            </div>

            {/* Configuration */}
            <div className="animate-fade-in" style={{ animationDelay: "0.1s" }}>
              <div className="flex items-start gap-4 mb-6">
                <div className="w-12 h-12 rounded-xl gradient-accent flex items-center justify-center shrink-0">
                  <Settings className="w-6 h-6 text-accent-foreground" />
                </div>
                <div>
                  <h2 className="text-2xl font-bold mb-2">Configuration</h2>
                  <p className="text-muted-foreground">
                    {platform === "wordpress"
                      ? "Navigate to: PDF Documents > Settings"
                      : platform === "drupal"
                      ? "Navigate to: Admin > Configuration > Content > PDF Embed & SEO"
                      : "Configure via PdfProvider config prop"}
                  </p>
                </div>
              </div>

              <div className="overflow-x-auto rounded-2xl border border-border">
                <table className="w-full min-w-[400px]">
                  <thead>
                    <tr className="bg-muted">
                      <th className="text-left py-3 px-4 font-semibold text-foreground">Setting</th>
                      <th className="text-left py-3 px-4 font-semibold text-foreground">Default</th>
                      <th className="text-left py-3 px-4 font-semibold text-foreground hidden md:table-cell">Description</th>
                    </tr>
                  </thead>
                  <tbody>
                    {[
                      { setting: "Allow Download by Default", default: "Yes", description: "New PDFs allow downloads" },
                      { setting: "Allow Print by Default", default: "Yes", description: "New PDFs allow printing" },
                      { setting: "Auto-generate Thumbnails", default: "Yes", description: "Create thumbnails from PDF" },
                      { setting: "Viewer Theme", default: "Light", description: "Light or Dark theme" },
                      { setting: "Viewer Height", default: "800px", description: "Default viewer height" },
                      { setting: "Archive Posts per Page", default: "12", description: "PDFs per archive page" },
                    ].map((row, index) => (
                      <tr key={row.setting} className={index % 2 === 0 ? "bg-background" : "bg-muted/30"}>
                        <td className="py-3 px-4 text-foreground font-medium whitespace-nowrap">{row.setting}</td>
                        <td className="py-3 px-4 text-muted-foreground">{row.default}</td>
                        <td className="py-3 px-4 text-muted-foreground hidden md:table-cell">{row.description}</td>
                      </tr>
                    ))}
                  </tbody>
                </table>
              </div>
            </div>

            {/* XML Sitemap */}
            <div className="animate-fade-in" style={{ animationDelay: "0.2s" }}>
              <div className="flex items-start gap-4 mb-6">
                <div className="w-12 h-12 rounded-xl gradient-accent flex items-center justify-center shrink-0">
                  <Globe className="w-6 h-6 text-accent-foreground" />
                </div>
                <div>
                  <h2 className="text-2xl font-bold mb-2">Automatic Google Sitemap</h2>
                  <p className="text-muted-foreground">
                    Help search engines find all your PDF documents automatically
                  </p>
                </div>
              </div>

              <div className="bg-card rounded-2xl border border-border p-6 mb-6">
                <p className="text-sm font-medium text-muted-foreground mb-3">Your PDF sitemap is available at:</p>
                <code className="block bg-primary/5 px-4 py-3 rounded-lg font-mono text-primary border border-primary/20">
                  yourdomain.com/pdf/sitemap.xml
                </code>
              </div>

              <p className="text-muted-foreground mb-4">
                The plugin automatically creates a special sitemap just for your PDFs. This helps Google and 
                other search engines discover and index all your documents, making them easier for customers to find.
              </p>

              <div className="bg-muted/50 rounded-xl p-4 border-l-4 border-accent">
                <p className="text-sm text-muted-foreground">
                  <strong>Good to know:</strong> You can submit this sitemap to Google Search Console to help 
                  your documents appear in search results faster.
                </p>
              </div>
            </div>

            {/* Shortcodes & Blocks */}
            <div className="animate-fade-in" style={{ animationDelay: "0.3s" }}>
              <div className="flex items-start gap-4 mb-6">
                <div className="w-12 h-12 rounded-xl gradient-hero flex items-center justify-center shrink-0">
                  <Code2 className="w-6 h-6 text-primary-foreground" />
                </div>
                <div>
                  <h2 className="text-2xl font-bold mb-2">
                    {platform === "react" ? "Components & Hooks" : "Shortcodes & Blocks"}
                  </h2>
                  <p className="text-muted-foreground">
                    Embed PDFs anywhere on your site
                  </p>
                </div>
              </div>

              {platform === "wordpress" ? (
                <div className="space-y-6">
                  <div className="bg-card rounded-2xl border border-border p-6">
                    <h3 className="font-semibold text-foreground mb-3">[pdf_viewer]</h3>
                    <p className="text-sm text-muted-foreground mb-4">Embed a single PDF viewer.</p>
                    <div className="overflow-x-auto">
                      <table className="w-full text-sm">
                        <thead>
                          <tr className="bg-muted">
                            <th className="text-left py-2 px-3 font-medium">Attribute</th>
                            <th className="text-left py-2 px-3 font-medium">Default</th>
                            <th className="text-left py-2 px-3 font-medium">Description</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr className="border-t border-border">
                            <td className="py-2 px-3 font-mono text-primary">id</td>
                            <td className="py-2 px-3 text-muted-foreground">Current post</td>
                            <td className="py-2 px-3 text-muted-foreground">PDF document ID</td>
                          </tr>
                          <tr className="border-t border-border">
                            <td className="py-2 px-3 font-mono text-primary">width</td>
                            <td className="py-2 px-3 text-muted-foreground">100%</td>
                            <td className="py-2 px-3 text-muted-foreground">Viewer width</td>
                          </tr>
                          <tr className="border-t border-border">
                            <td className="py-2 px-3 font-mono text-primary">height</td>
                            <td className="py-2 px-3 text-muted-foreground">800px</td>
                            <td className="py-2 px-3 text-muted-foreground">Viewer height</td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                    <code className="block bg-muted px-4 py-3 rounded-lg font-mono text-primary text-sm mt-4">
                      [pdf_viewer id="123" width="100%" height="600px"]
                    </code>
                  </div>

                  <div className="bg-card rounded-2xl border border-border p-6">
                    <h3 className="font-semibold text-foreground mb-3">[pdf_viewer_sitemap]</h3>
                    <p className="text-sm text-muted-foreground mb-4">Display a list of all PDFs.</p>
                    <div className="overflow-x-auto">
                      <table className="w-full text-sm">
                        <thead>
                          <tr className="bg-muted">
                            <th className="text-left py-2 px-3 font-medium">Attribute</th>
                            <th className="text-left py-2 px-3 font-medium">Default</th>
                            <th className="text-left py-2 px-3 font-medium">Description</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr className="border-t border-border">
                            <td className="py-2 px-3 font-mono text-primary">orderby</td>
                            <td className="py-2 px-3 text-muted-foreground">title</td>
                            <td className="py-2 px-3 text-muted-foreground">Sort: title, date, menu_order</td>
                          </tr>
                          <tr className="border-t border-border">
                            <td className="py-2 px-3 font-mono text-primary">order</td>
                            <td className="py-2 px-3 text-muted-foreground">ASC</td>
                            <td className="py-2 px-3 text-muted-foreground">Direction: ASC, DESC</td>
                          </tr>
                          <tr className="border-t border-border">
                            <td className="py-2 px-3 font-mono text-primary">limit</td>
                            <td className="py-2 px-3 text-muted-foreground">-1</td>
                            <td className="py-2 px-3 text-muted-foreground">Number of PDFs (-1 for all)</td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                    <code className="block bg-muted px-4 py-3 rounded-lg font-mono text-primary text-sm mt-4">
                      [pdf_viewer_sitemap orderby="date" order="DESC" limit="10"]
                    </code>
                  </div>

                  <div className="bg-card rounded-2xl border border-border p-6">
                    <h3 className="font-semibold text-foreground mb-3">Gutenberg Block</h3>
                    <ol className="list-decimal list-inside space-y-2 text-muted-foreground">
                      <li>In the block editor, click "+" to add a block</li>
                      <li>Search for "PDF Viewer"</li>
                      <li>Select the PDF document from the dropdown</li>
                      <li>Adjust width/height in block settings</li>
                    </ol>
                  </div>
                </div>
              ) : platform === "drupal" ? (
                <div className="bg-card rounded-2xl border border-border p-6">
                  <h3 className="font-semibold text-foreground mb-3">Drupal Block</h3>
                  <ol className="list-decimal list-inside space-y-2 text-muted-foreground">
                    <li>Go to Admin {">"} Structure {">"} Block Layout</li>
                    <li>Place a "PDF Viewer" block</li>
                    <li>Configure: Select PDF document, set viewer height, toggle title visibility</li>
                  </ol>
                </div>
              ) : (
                <div className="space-y-6">
                  <div className="bg-card rounded-2xl border border-border p-6">
                    <h3 className="font-semibold text-foreground mb-3">{"<PdfViewer />"}</h3>
                    <p className="text-sm text-muted-foreground mb-4">Main PDF viewer component.</p>
                    <div className="overflow-x-auto">
                      <table className="w-full text-sm">
                        <thead>
                          <tr className="bg-muted">
                            <th className="text-left py-2 px-3 font-medium">Prop</th>
                            <th className="text-left py-2 px-3 font-medium">Type</th>
                            <th className="text-left py-2 px-3 font-medium">Description</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr className="border-t border-border">
                            <td className="py-2 px-3 font-mono text-primary">src</td>
                            <td className="py-2 px-3 text-muted-foreground">string | PdfDocument</td>
                            <td className="py-2 px-3 text-muted-foreground">PDF URL or document object</td>
                          </tr>
                          <tr className="border-t border-border">
                            <td className="py-2 px-3 font-mono text-primary">height</td>
                            <td className="py-2 px-3 text-muted-foreground">string</td>
                            <td className="py-2 px-3 text-muted-foreground">Viewer height (e.g., "800px")</td>
                          </tr>
                          <tr className="border-t border-border">
                            <td className="py-2 px-3 font-mono text-primary">theme</td>
                            <td className="py-2 px-3 text-muted-foreground">"light" | "dark" | "system"</td>
                            <td className="py-2 px-3 text-muted-foreground">Viewer color theme</td>
                          </tr>
                          <tr className="border-t border-border">
                            <td className="py-2 px-3 font-mono text-primary">allowDownload</td>
                            <td className="py-2 px-3 text-muted-foreground">boolean</td>
                            <td className="py-2 px-3 text-muted-foreground">Show download button</td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                    <pre className="bg-muted px-4 py-3 rounded-lg font-mono text-primary text-sm mt-4 overflow-x-auto">
                      <code>{`<PdfViewer
  src="/documents/report.pdf"
  height="800px"
  theme="light"
  allowDownload
/>`}</code>
                    </pre>
                  </div>

                  <div className="bg-card rounded-2xl border border-border p-6">
                    <h3 className="font-semibold text-foreground mb-3">{"<PdfArchive />"}</h3>
                    <p className="text-sm text-muted-foreground mb-4">Display a grid/list of PDF documents.</p>
                    <pre className="bg-muted px-4 py-3 rounded-lg font-mono text-primary text-sm overflow-x-auto">
                      <code>{`<PdfArchive
  view="grid"
  columns={3}
  perPage={12}
  showSearch
  showSort
/>`}</code>
                    </pre>
                  </div>

                  <div className="bg-card rounded-2xl border border-border p-6">
                    <h3 className="font-semibold text-foreground mb-3">React Hooks</h3>
                    <ul className="space-y-2 text-sm">
                      <li className="flex gap-3">
                        <code className="text-primary bg-muted px-2 py-0.5 rounded shrink-0">usePdfDocument(id)</code>
                        <span className="text-muted-foreground">Fetch a single PDF document</span>
                      </li>
                      <li className="flex gap-3">
                        <code className="text-primary bg-muted px-2 py-0.5 rounded shrink-0">usePdfDocuments()</code>
                        <span className="text-muted-foreground">Fetch paginated document list</span>
                      </li>
                      <li className="flex gap-3">
                        <code className="text-primary bg-muted px-2 py-0.5 rounded shrink-0">usePdfViewer()</code>
                        <span className="text-muted-foreground">Control viewer state (page, zoom)</span>
                      </li>
                      <li className="flex gap-3">
                        <code className="text-primary bg-muted px-2 py-0.5 rounded shrink-0">usePdfSeo(document)</code>
                        <span className="text-muted-foreground">Generate SEO metadata</span>
                      </li>
                    </ul>
                  </div>

                  <div className="bg-card rounded-2xl border border-border p-6">
                    <h3 className="font-semibold text-foreground mb-3">Next.js App Router</h3>
                    <pre className="bg-muted px-4 py-3 rounded-lg font-mono text-primary text-sm overflow-x-auto">
                      <code>{`// app/pdf/[slug]/page.tsx
import { generatePdfMetadata } from '@pdf-embed-seo/react/nextjs';

export async function generateMetadata({ params }) {
  const doc = await getPdfDocument(params.slug);
  return generatePdfMetadata(doc, {
    siteUrl: process.env.NEXT_PUBLIC_SITE_URL,
  });
}

export default async function PdfPage({ params }) {
  const doc = await getPdfDocument(params.slug);
  return <PdfViewer src={doc} height="800px" />;
}`}</code>
                    </pre>
                  </div>
                </div>
              )}
            </div>

            {/* REST API */}
            <div className="animate-fade-in" style={{ animationDelay: "0.4s" }}>
              <div className="flex items-start gap-4 mb-6">
                <div className="w-12 h-12 rounded-xl gradient-hero flex items-center justify-center shrink-0">
                  <Database className="w-6 h-6 text-primary-foreground" />
                </div>
                <div>
                  <h2 className="text-2xl font-bold mb-2">REST API Reference</h2>
                  <p className="text-muted-foreground">
                    {platform === "react"
                      ? "Connect to WordPress or Drupal via PdfProvider"
                      : `Base URL: ${platform === "wordpress"
                          ? "/wp-json/pdf-embed-seo/v1/"
                          : "/api/pdf-embed-seo/v1/"}`}
                  </p>
                </div>
              </div>

              <div className="space-y-4">
                <div className="bg-card rounded-2xl border border-border p-6">
                  <h3 className="font-semibold text-foreground mb-3">GET /documents</h3>
                  <p className="text-sm text-muted-foreground mb-4">List all published PDF documents.</p>
                  <div className="overflow-x-auto">
                    <table className="w-full text-sm">
                      <thead>
                        <tr className="bg-muted">
                          <th className="text-left py-2 px-3 font-medium">Parameter</th>
                          <th className="text-left py-2 px-3 font-medium">Type</th>
                          <th className="text-left py-2 px-3 font-medium">Description</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr className="border-t border-border">
                          <td className="py-2 px-3 font-mono text-primary">page</td>
                          <td className="py-2 px-3 text-muted-foreground">int</td>
                          <td className="py-2 px-3 text-muted-foreground">Page number (default: 1)</td>
                        </tr>
                        <tr className="border-t border-border">
                          <td className="py-2 px-3 font-mono text-primary">per_page</td>
                          <td className="py-2 px-3 text-muted-foreground">int</td>
                          <td className="py-2 px-3 text-muted-foreground">Items per page (max 100)</td>
                        </tr>
                        <tr className="border-t border-border">
                          <td className="py-2 px-3 font-mono text-primary">search</td>
                          <td className="py-2 px-3 text-muted-foreground">string</td>
                          <td className="py-2 px-3 text-muted-foreground">Search term</td>
                        </tr>
                        <tr className="border-t border-border">
                          <td className="py-2 px-3 font-mono text-primary">orderby</td>
                          <td className="py-2 px-3 text-muted-foreground">string</td>
                          <td className="py-2 px-3 text-muted-foreground">date, title, modified, views</td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>

                <div className="bg-card rounded-2xl border border-border p-6">
                  <h3 className="font-semibold text-foreground mb-3">GET /documents/{'{id}'}</h3>
                  <p className="text-sm text-muted-foreground mb-4">Get single PDF document details.</p>
                  <p className="text-xs text-muted-foreground">Returns: id, title, slug, url, description, thumbnail, file_size, page_count, view_count, dates</p>
                </div>

                <div className="bg-card rounded-2xl border border-border p-6">
                  <h3 className="font-semibold text-foreground mb-3">GET /documents/{'{id}'}/data</h3>
                  <p className="text-sm text-muted-foreground mb-4">Get secure PDF URL for viewer (AJAX-based).</p>
                  <p className="text-xs text-muted-foreground">Returns: success, url (temporary secure URL), expires</p>
                </div>

                <div className="bg-card rounded-2xl border border-border p-6">
                  <h3 className="font-semibold text-foreground mb-3">POST /documents/{'{id}'}/view</h3>
                  <p className="text-sm text-muted-foreground">Track a PDF view (increment view counter).</p>
                </div>

                <div className="bg-card rounded-2xl border border-border p-6">
                  <h3 className="font-semibold text-foreground mb-3">GET /settings</h3>
                  <p className="text-sm text-muted-foreground">Get public plugin settings (viewer theme, height, permissions).</p>
                </div>

                <div className="bg-card rounded-2xl border border-border p-6">
                  <div className="flex items-center gap-2 mb-3">
                    <h3 className="font-semibold text-foreground">Premium Endpoints</h3>
                    <span className="text-xs font-medium px-2 py-1 rounded bg-primary/10 text-primary">Pro</span>
                  </div>
                  <ul className="space-y-2 text-sm text-muted-foreground">
                    <li><code className="text-primary bg-muted px-2 py-0.5 rounded">GET /analytics</code> - Dashboard summary (total views, unique visitors, popular docs)</li>
                    <li><code className="text-primary bg-muted px-2 py-0.5 rounded">GET /analytics/documents</code> - Per-document analytics</li>
                    <li><code className="text-primary bg-muted px-2 py-0.5 rounded">GET /analytics/export</code> - Export data (CSV/JSON)</li>
                    <li><code className="text-primary bg-muted px-2 py-0.5 rounded">GET /documents/{'{id}'}/progress</code> - Get reading progress</li>
                    <li><code className="text-primary bg-muted px-2 py-0.5 rounded">POST /documents/{'{id}'}/progress</code> - Save reading progress (page, scroll, zoom)</li>
                    <li><code className="text-primary bg-muted px-2 py-0.5 rounded">POST /documents/{'{id}'}/verify-password</code> - Verify password for protected PDFs</li>
                    <li><code className="text-primary bg-muted px-2 py-0.5 rounded">POST /documents/{'{id}'}/download</code> - Track PDF download</li>
                    <li><code className="text-primary bg-muted px-2 py-0.5 rounded">POST /documents/{'{id}'}/expiring-link</code> - Generate expiring access link</li>
                    <li><code className="text-primary bg-muted px-2 py-0.5 rounded">GET /documents/{'{id}'}/expiring-link/{'{token}'}</code> - Access PDF via expiring link</li>
                    <li><code className="text-primary bg-muted px-2 py-0.5 rounded">GET /categories</code> - Get all PDF categories</li>
                    <li><code className="text-primary bg-muted px-2 py-0.5 rounded">GET /tags</code> - Get all PDF tags</li>
                    <li><code className="text-primary bg-muted px-2 py-0.5 rounded">POST /bulk/import</code> - Start bulk PDF import (admin only)</li>
                    <li><code className="text-primary bg-muted px-2 py-0.5 rounded">GET /bulk/import/status</code> - Get bulk import status (admin only)</li>
                  </ul>
                </div>

                <div className="bg-card rounded-2xl border border-border p-6">
                  <h3 className="font-semibold text-foreground mb-3">Example Response</h3>
                  <pre className="bg-muted px-4 py-3 rounded-lg font-mono text-xs overflow-x-auto">
                    <code className="text-primary">{`{
  "id": 123,
  "title": "Annual Report 2024",
  "slug": "annual-report-2024",
  "url": "https://example.com/pdf/annual-report-2024/",
  "excerpt": "Company annual report...",
  "date": "2024-01-15T10:30:00+00:00",
  "views": 1542,
  "thumbnail": "https://example.com/wp-content/uploads/thumb.jpg"
}`}</code>
                  </pre>
                </div>
              </div>
            </div>

            {/* Hooks */}
            <div className="animate-fade-in" style={{ animationDelay: "0.5s" }}>
              <div className="flex items-start gap-4 mb-6">
                <div className="w-12 h-12 rounded-xl gradient-accent flex items-center justify-center shrink-0">
                  <Puzzle className="w-6 h-6 text-accent-foreground" />
                </div>
                <div>
                  <h2 className="text-2xl font-bold mb-2">
                    {platform === "wordpress" ? "WordPress Hooks" : platform === "drupal" ? "Drupal Hooks" : "Callbacks & Customization"}
                  </h2>
                  <p className="text-muted-foreground">
                    Extend and customize the {platform === "react" ? "component" : "plugin"} functionality
                  </p>
                </div>
              </div>

              {platform === "wordpress" ? (
                <div className="space-y-4">
                  <div className="bg-card rounded-2xl border border-border p-6">
                    <h3 className="font-semibold text-foreground mb-3">Actions</h3>
                    <ul className="space-y-2 text-sm">
                      <li className="flex gap-3">
                        <code className="text-primary bg-muted px-2 py-0.5 rounded shrink-0">pdf_embed_seo_pdf_viewed</code>
                        <span className="text-muted-foreground">Fired when PDF is viewed ($post_id, $count)</span>
                      </li>
                      <li className="flex gap-3">
                        <code className="text-primary bg-muted px-2 py-0.5 rounded shrink-0">pdf_embed_seo_premium_init</code>
                        <span className="text-muted-foreground">Premium features initialized</span>
                      </li>
                      <li className="flex gap-3">
                        <code className="text-primary bg-muted px-2 py-0.5 rounded shrink-0">pdf_embed_seo_settings_saved</code>
                        <span className="text-muted-foreground">Settings saved</span>
                      </li>
                    </ul>
                  </div>

                  <div className="bg-card rounded-2xl border border-border p-6">
                    <h3 className="font-semibold text-foreground mb-3">Filters</h3>
                    <div className="overflow-x-auto">
                      <table className="w-full text-sm">
                        <thead>
                          <tr className="bg-muted">
                            <th className="text-left py-2 px-3 font-medium">Hook Name</th>
                            <th className="text-left py-2 px-3 font-medium hidden md:table-cell">Parameters</th>
                            <th className="text-left py-2 px-3 font-medium">Description</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr className="border-t border-border">
                            <td className="py-2 px-3"><code className="text-primary text-xs">pdf_embed_seo_schema_data</code></td>
                            <td className="py-2 px-3 text-muted-foreground hidden md:table-cell">$schema, $post_id</td>
                            <td className="py-2 px-3 text-muted-foreground">Modify Schema.org data for a single PDF</td>
                          </tr>
                          <tr className="border-t border-border">
                            <td className="py-2 px-3"><code className="text-primary text-xs">pdf_embed_seo_archive_schema_data</code></td>
                            <td className="py-2 px-3 text-muted-foreground hidden md:table-cell">$schema</td>
                            <td className="py-2 px-3 text-muted-foreground">Modify Schema.org data for archive page</td>
                          </tr>
                          <tr className="border-t border-border">
                            <td className="py-2 px-3"><code className="text-primary text-xs">pdf_embed_seo_archive_query</code></td>
                            <td className="py-2 px-3 text-muted-foreground hidden md:table-cell">$posts_per_page</td>
                            <td className="py-2 px-3 text-muted-foreground">Modify archive page posts per page</td>
                          </tr>
                          <tr className="border-t border-border">
                            <td className="py-2 px-3"><code className="text-primary text-xs">pdf_embed_seo_sitemap_query_args</code></td>
                            <td className="py-2 px-3 text-muted-foreground hidden md:table-cell">$query_args, $atts</td>
                            <td className="py-2 px-3 text-muted-foreground">Modify sitemap shortcode query args</td>
                          </tr>
                          <tr className="border-t border-border">
                            <td className="py-2 px-3"><code className="text-primary text-xs">pdf_embed_seo_archive_title</code></td>
                            <td className="py-2 px-3 text-muted-foreground hidden md:table-cell">$title</td>
                            <td className="py-2 px-3 text-muted-foreground">Modify the archive page title</td>
                          </tr>
                          <tr className="border-t border-border">
                            <td className="py-2 px-3"><code className="text-primary text-xs">pdf_embed_seo_archive_description</code></td>
                            <td className="py-2 px-3 text-muted-foreground hidden md:table-cell">$description</td>
                            <td className="py-2 px-3 text-muted-foreground">Modify the archive page description</td>
                          </tr>
                          <tr className="border-t border-border">
                            <td className="py-2 px-3"><code className="text-primary text-xs">pdf_embed_seo_viewer_options</code></td>
                            <td className="py-2 px-3 text-muted-foreground hidden md:table-cell">$options, $post_id</td>
                            <td className="py-2 px-3 text-muted-foreground">Modify PDF.js viewer options</td>
                          </tr>
                          <tr className="border-t border-border">
                            <td className="py-2 px-3"><code className="text-primary text-xs">pdf_embed_seo_allowed_types</code></td>
                            <td className="py-2 px-3 text-muted-foreground hidden md:table-cell">$types</td>
                            <td className="py-2 px-3 text-muted-foreground">Modify allowed MIME types for upload</td>
                          </tr>
                          <tr className="border-t border-border">
                            <td className="py-2 px-3"><code className="text-primary text-xs">pdf_embed_seo_rest_document</code></td>
                            <td className="py-2 px-3 text-muted-foreground hidden md:table-cell">$data, $post, $detailed</td>
                            <td className="py-2 px-3 text-muted-foreground">Modify REST API document response</td>
                          </tr>
                          <tr className="border-t border-border">
                            <td className="py-2 px-3"><code className="text-primary text-xs">pdf_embed_seo_rest_document_data</code></td>
                            <td className="py-2 px-3 text-muted-foreground hidden md:table-cell">$data, $post_id</td>
                            <td className="py-2 px-3 text-muted-foreground">Modify REST API document data</td>
                          </tr>
                          <tr className="border-t border-border">
                            <td className="py-2 px-3"><code className="text-primary text-xs">pdf_embed_seo_rest_settings</code></td>
                            <td className="py-2 px-3 text-muted-foreground hidden md:table-cell">$settings</td>
                            <td className="py-2 px-3 text-muted-foreground">Modify REST API settings response</td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>

                  <div className="bg-card rounded-2xl border border-border p-6">
                    <h3 className="font-semibold text-foreground mb-3">Example: Add custom schema data</h3>
                    <pre className="bg-muted px-4 py-3 rounded-lg font-mono text-sm overflow-x-auto">
                      <code className="text-primary">{`add_filter( 'pdf_embed_seo_schema_data', function( $schema, $post_id ) {
    $schema['author'] = [
        '@type' => 'Person',
        'name'  => get_post_meta( $post_id, '_pdf_author', true ),
    ];
    return $schema;
}, 10, 2 );`}</code>
                    </pre>
                  </div>
                </div>
              ) : platform === "drupal" ? (
                <div className="space-y-4">
                  <div className="bg-card rounded-2xl border border-border p-6">
                    <h3 className="font-semibold text-foreground mb-3">Alter Hooks</h3>
                    <ul className="space-y-2 text-sm">
                      <li className="flex gap-3">
                        <code className="text-primary bg-muted px-2 py-0.5 rounded shrink-0">hook_pdf_embed_seo_document_data_alter</code>
                        <span className="text-muted-foreground">Modify API document data</span>
                      </li>
                      <li className="flex gap-3">
                        <code className="text-primary bg-muted px-2 py-0.5 rounded shrink-0">hook_pdf_embed_seo_schema_alter</code>
                        <span className="text-muted-foreground">Modify Schema.org output</span>
                      </li>
                      <li className="flex gap-3">
                        <code className="text-primary bg-muted px-2 py-0.5 rounded shrink-0">hook_pdf_embed_seo_viewer_options_alter</code>
                        <span className="text-muted-foreground">Modify viewer options</span>
                      </li>
                    </ul>
                  </div>

                  <div className="bg-card rounded-2xl border border-border p-6">
                    <h3 className="font-semibold text-foreground mb-3">Event Hooks</h3>
                    <ul className="space-y-2 text-sm">
                      <li className="flex gap-3">
                        <code className="text-primary bg-muted px-2 py-0.5 rounded shrink-0">hook_pdf_embed_seo_view_tracked</code>
                        <span className="text-muted-foreground">PDF view was tracked</span>
                      </li>
                      <li className="flex gap-3">
                        <code className="text-primary bg-muted px-2 py-0.5 rounded shrink-0">hook_pdf_embed_seo_document_saved</code>
                        <span className="text-muted-foreground">PDF document saved</span>
                      </li>
                    </ul>
                  </div>

                  <div className="bg-card rounded-2xl border border-border p-6">
                    <h3 className="font-semibold text-foreground mb-3">Example: Custom schema</h3>
                    <pre className="bg-muted px-4 py-3 rounded-lg font-mono text-sm overflow-x-auto">
                      <code className="text-primary">{`/**
 * Implements hook_pdf_embed_seo_schema_alter().
 */
function mymodule_pdf_embed_seo_schema_alter(array &$schema, $document) {
  $schema['author'] = [
    '@type' => 'Person',
    'name' => $document->get('field_author')->value,
  ];
}`}</code>
                    </pre>
                  </div>
                </div>
              ) : (
                <div className="space-y-4">
                  <div className="bg-card rounded-2xl border border-border p-6">
                    <h3 className="font-semibold text-foreground mb-3">Event Callbacks</h3>
                    <ul className="space-y-2 text-sm">
                      <li className="flex gap-3">
                        <code className="text-primary bg-muted px-2 py-0.5 rounded shrink-0">onDocumentLoad</code>
                        <span className="text-muted-foreground">Called when PDF document loads</span>
                      </li>
                      <li className="flex gap-3">
                        <code className="text-primary bg-muted px-2 py-0.5 rounded shrink-0">onPageChange</code>
                        <span className="text-muted-foreground">Called when page number changes</span>
                      </li>
                      <li className="flex gap-3">
                        <code className="text-primary bg-muted px-2 py-0.5 rounded shrink-0">onError</code>
                        <span className="text-muted-foreground">Called when an error occurs</span>
                      </li>
                      <li className="flex gap-3">
                        <code className="text-primary bg-muted px-2 py-0.5 rounded shrink-0">onZoomChange</code>
                        <span className="text-muted-foreground">Called when zoom level changes</span>
                      </li>
                    </ul>
                  </div>

                  <div className="bg-card rounded-2xl border border-border p-6">
                    <h3 className="font-semibold text-foreground mb-3">Custom Render Props</h3>
                    <ul className="space-y-2 text-sm">
                      <li className="flex gap-3">
                        <code className="text-primary bg-muted px-2 py-0.5 rounded shrink-0">renderCard</code>
                        <span className="text-muted-foreground">Custom document card renderer</span>
                      </li>
                      <li className="flex gap-3">
                        <code className="text-primary bg-muted px-2 py-0.5 rounded shrink-0">renderToolbar</code>
                        <span className="text-muted-foreground">Custom viewer toolbar</span>
                      </li>
                      <li className="flex gap-3">
                        <code className="text-primary bg-muted px-2 py-0.5 rounded shrink-0">renderLoading</code>
                        <span className="text-muted-foreground">Custom loading component</span>
                      </li>
                    </ul>
                  </div>

                  <div className="bg-card rounded-2xl border border-border p-6">
                    <h3 className="font-semibold text-foreground mb-3">Example: Custom callbacks</h3>
                    <pre className="bg-muted px-4 py-3 rounded-lg font-mono text-sm overflow-x-auto">
                      <code className="text-primary">{`<PdfViewer
  src={document}
  onDocumentLoad={(info) => {
    console.log('Loaded:', info.numPages, 'pages');
    analytics.track('pdf_viewed', { id: document.id });
  }}
  onPageChange={(page) => {
    console.log('Now on page:', page);
  }}
  onError={(error) => {
    console.error('PDF error:', error.message);
    toast.error('Failed to load PDF');
  }}
/>`}</code>
                    </pre>
                  </div>
                </div>
              )}
            </div>

            {/* Theming & Templates */}
            <div className="animate-fade-in" style={{ animationDelay: "0.6s" }}>
              <div className="flex items-start gap-4 mb-6">
                <div className="w-12 h-12 rounded-xl gradient-hero flex items-center justify-center shrink-0">
                  <FileText className="w-6 h-6 text-primary-foreground" />
                </div>
                <div>
                  <h2 className="text-2xl font-bold mb-2">Theming & Templates</h2>
                  <p className="text-muted-foreground">
                    Customize the look and feel
                  </p>
                </div>
              </div>

              {platform === "wordpress" ? (
                <div className="bg-card rounded-2xl border border-border p-6">
                  <h3 className="font-semibold text-foreground mb-3">Override in your theme:</h3>
                  <ul className="space-y-2 text-sm">
                    <li className="flex gap-3">
                      <code className="text-primary bg-muted px-2 py-0.5 rounded">single-pdf_document.php</code>
                      <span className="text-muted-foreground">Single PDF page</span>
                    </li>
                    <li className="flex gap-3">
                      <code className="text-primary bg-muted px-2 py-0.5 rounded">archive-pdf_document.php</code>
                      <span className="text-muted-foreground">Archive page</span>
                    </li>
                  </ul>
                </div>
              ) : platform === "drupal" ? (
                <div className="bg-card rounded-2xl border border-border p-6">
                  <h3 className="font-semibold text-foreground mb-3">Override in your theme:</h3>
                  <ul className="space-y-2 text-sm">
                    <li className="flex gap-3">
                      <code className="text-primary bg-muted px-2 py-0.5 rounded">pdf-document.html.twig</code>
                      <span className="text-muted-foreground">Single PDF display</span>
                    </li>
                    <li className="flex gap-3">
                      <code className="text-primary bg-muted px-2 py-0.5 rounded">pdf-viewer.html.twig</code>
                      <span className="text-muted-foreground">PDF.js viewer</span>
                    </li>
                    <li className="flex gap-3">
                      <code className="text-primary bg-muted px-2 py-0.5 rounded">pdf-archive.html.twig</code>
                      <span className="text-muted-foreground">Archive listing</span>
                    </li>
                    <li className="flex gap-3">
                      <code className="text-primary bg-muted px-2 py-0.5 rounded">pdf-password-form.html.twig</code>
                      <span className="text-muted-foreground">Password form (Premium)</span>
                    </li>
                  </ul>
                </div>
              ) : (
                <div className="space-y-4">
                  <div className="bg-card rounded-2xl border border-border p-6">
                    <h3 className="font-semibold text-foreground mb-3">CSS Classes</h3>
                    <ul className="space-y-2 text-sm">
                      <li className="flex gap-3">
                        <code className="text-primary bg-muted px-2 py-0.5 rounded">.pdf-viewer-wrapper</code>
                        <span className="text-muted-foreground">Main viewer container</span>
                      </li>
                      <li className="flex gap-3">
                        <code className="text-primary bg-muted px-2 py-0.5 rounded">.pdf-viewer-toolbar</code>
                        <span className="text-muted-foreground">Viewer toolbar</span>
                      </li>
                      <li className="flex gap-3">
                        <code className="text-primary bg-muted px-2 py-0.5 rounded">.pdf-archive-grid</code>
                        <span className="text-muted-foreground">Archive grid layout</span>
                      </li>
                      <li className="flex gap-3">
                        <code className="text-primary bg-muted px-2 py-0.5 rounded">.pdf-archive-card</code>
                        <span className="text-muted-foreground">Document card</span>
                      </li>
                    </ul>
                  </div>

                  <div className="bg-card rounded-2xl border border-border p-6">
                    <h3 className="font-semibold text-foreground mb-3">CSS Custom Properties</h3>
                    <pre className="bg-muted px-4 py-3 rounded-lg font-mono text-sm overflow-x-auto">
                      <code className="text-primary">{`:root {
  --pdf-viewer-bg: #ffffff;
  --pdf-viewer-text: #1a1a1a;
  --pdf-viewer-toolbar-bg: #f5f5f5;
  --pdf-viewer-border: #e5e5e5;
  --pdf-viewer-accent: #0070f3;
}`}</code>
                    </pre>
                  </div>

                  <div className="bg-card rounded-2xl border border-border p-6">
                    <h3 className="font-semibold text-foreground mb-3">Tailwind CSS Integration</h3>
                    <p className="text-sm text-muted-foreground mb-3">
                      Pass className prop to customize with Tailwind:
                    </p>
                    <pre className="bg-muted px-4 py-3 rounded-lg font-mono text-sm overflow-x-auto">
                      <code className="text-primary">{`<PdfViewer
  src={doc}
  className="rounded-xl shadow-lg border-2 border-gray-200"
/>`}</code>
                    </pre>
                  </div>
                </div>
              )}
            </div>

            {/* Premium Features */}
            <div className="animate-fade-in" style={{ animationDelay: "0.7s" }}>
              <div className="flex items-start gap-4 mb-6">
                <div className="w-12 h-12 rounded-xl bg-purple-500/20 flex items-center justify-center shrink-0">
                  <Zap className="w-6 h-6 text-purple-600" />
                </div>
                <div>
                  <div className="flex items-center gap-3">
                    <h2 className="text-2xl font-bold">Premium Features</h2>
                    <span className="text-xs font-medium px-2 py-1 rounded bg-primary/10 text-primary">Pro</span>
                  </div>
                  <p className="text-muted-foreground">
                    Advanced functionality for Pro users
                  </p>
                </div>
              </div>

              <div className="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div className="bg-card rounded-2xl border border-border p-6">
                  <div className="flex items-center gap-3 mb-3">
                    <BarChart3 className="w-5 h-5 text-primary" />
                    <h3 className="font-semibold text-foreground">Analytics Dashboard</h3>
                  </div>
                  <ul className="text-sm text-muted-foreground space-y-1">
                    <li>• Total views and unique visitors</li>
                    <li>• Popular documents ranking</li>
                    <li>• Time period filters (7d, 30d, 90d, 12m)</li>
                    <li>• CSV/JSON export</li>
                  </ul>
                </div>

                <div className="bg-card rounded-2xl border border-border p-6">
                  <div className="flex items-center gap-3 mb-3">
                    <Lock className="w-5 h-5 text-primary" />
                    <h3 className="font-semibold text-foreground">Password Protection</h3>
                  </div>
                  <ul className="text-sm text-muted-foreground space-y-1">
                    <li>• Per-PDF password settings</li>
                    <li>• Secure password hashing</li>
                    <li>• Brute-force protection</li>
                    <li>• AJAX-based verification</li>
                  </ul>
                </div>

                <div className="bg-card rounded-2xl border border-border p-6">
                  <div className="flex items-center gap-3 mb-3">
                    <Clock className="w-5 h-5 text-primary" />
                    <h3 className="font-semibold text-foreground">Reading Progress</h3>
                  </div>
                  <ul className="text-sm text-muted-foreground space-y-1">
                    <li>• Auto-save reading position</li>
                    <li>• Resume from last page</li>
                    <li>• Track scroll and zoom</li>
                    <li>• Works for all users</li>
                  </ul>
                </div>

                <div className="bg-card rounded-2xl border border-border p-6">
                  <div className="flex items-center gap-3 mb-3">
                    <Globe className="w-5 h-5 text-primary" />
                    <h3 className="font-semibold text-foreground">XML Sitemap</h3>
                  </div>
                  <ul className="text-sm text-muted-foreground space-y-1">
                    <li>• Available at /pdf/sitemap.xml</li>
                    <li>• XSL-styled browser view</li>
                    <li>• Auto-updates on changes</li>
                    <li>• Submit to Google Search Console</li>
                  </ul>
                </div>

                <div className="bg-card rounded-2xl border border-border p-6">
                  <div className="flex items-center gap-3 mb-3">
                    <Database className="w-5 h-5 text-primary" />
                    <h3 className="font-semibold text-foreground">Download Tracking</h3>
                  </div>
                  <ul className="text-sm text-muted-foreground space-y-1">
                    <li>• Track downloads separately from views</li>
                    <li>• Separate download counter per PDF</li>
                    <li>• User attribution for logged-in users</li>
                    <li>• REST API endpoint for tracking</li>
                  </ul>
                </div>

                <div className="bg-card rounded-2xl border border-border p-6">
                  <div className="flex items-center gap-3 mb-3">
                    <Shield className="w-5 h-5 text-primary" />
                    <h3 className="font-semibold text-foreground">Expiring Access Links</h3>
                  </div>
                  <ul className="text-sm text-muted-foreground space-y-1">
                    <li>• Generate time-limited URLs</li>
                    <li>• Configurable expiration (5min-30 days)</li>
                    <li>• Max usage limits per link</li>
                    <li>• Secure token-based access</li>
                  </ul>
                </div>
              </div>

              <div className="flex flex-col sm:flex-row gap-4 mt-8">
                <Button asChild>
                  <Link to="/pro/" title="View all Pro features and pricing" aria-label="View all PDF Embed Pro features and pricing options">
                    <Zap className="w-4 h-4 mr-2" aria-hidden="true" />
                    View All Pro Features
                  </Link>
                </Button>
                <Button variant="outline" asChild>
                  <Link to="/pro/#developer-docs-heading" title="View developer documentation" aria-label="View developer documentation for PDF Embed Pro">
                    <Code2 className="w-4 h-4 mr-2" aria-hidden="true" />
                    Developer Docs
                  </Link>
                </Button>
              </div>
            </div>

            {/* Support */}
            <div className="animate-fade-in bg-card rounded-2xl border border-border p-8 text-center" style={{ animationDelay: "0.8s" }}>
              <Shield className="w-12 h-12 text-primary mx-auto mb-4" />
              <h2 className="text-2xl font-bold mb-2">Need Help?</h2>
              <p className="text-muted-foreground mb-6">
                Check our resources or contact support
              </p>
              <nav className="flex flex-wrap justify-center gap-4 text-sm" aria-label="Support resources">
                <a href="https://pdfviewermodule.com/docs" target="_blank" rel="noopener noreferrer" className="text-primary hover:underline" title="View full documentation on pdfviewermodule.com" aria-label="View full documentation (opens in new tab)">
                  Full Documentation
                </a>
                <span className="text-muted-foreground" aria-hidden="true">•</span>
                <a href="mailto:support@drossmedia.de" className="text-primary hover:underline" title="Send email to support" aria-label="Contact support via email">
                  support@drossmedia.de
                </a>
              </nav>
            </div>

          </div>
        </div>
      </section>
    </Layout>
  );
};

export default Documentation;
