<?php
/**
 * Template Name: React / Next.js PDF Viewer
 * React / Next.js PDF Viewer landing page
 *
 * @package PDFViewer
 */

get_header();

$requirements = array(
    'React 18+ or Next.js 13+',
    'Node.js 16 or higher',
    'npm or yarn package manager',
);

$viewer_features = array(
    array(
        'icon'        => 'file-text',
        'title'       => 'TypeScript Support',
        'description' => 'Full TypeScript definitions for type-safe development with autocomplete in your IDE.',
    ),
    array(
        'icon'        => 'zap',
        'title'       => 'Tree-shakeable',
        'description' => 'Only import what you need. Optimized bundle size for faster page loads.',
    ),
    array(
        'icon'        => 'sparkles',
        'title'       => 'SSR Compatible',
        'description' => 'Works with Next.js App Router, Pages Router, and server-side rendering.',
    ),
);

$component_props = array(
    array('prop' => 'src', 'type' => 'string', 'required' => true, 'description' => 'URL or path to the PDF file'),
    array('prop' => 'title', 'type' => 'string', 'required' => false, 'description' => 'Document title for SEO and accessibility'),
    array('prop' => 'height', 'type' => 'string', 'required' => false, 'description' => 'Viewer height (default: "600px")'),
    array('prop' => 'width', 'type' => 'string', 'required' => false, 'description' => 'Viewer width (default: "100%")'),
    array('prop' => 'allowDownload', 'type' => 'boolean', 'required' => false, 'description' => 'Show download button (default: true)'),
    array('prop' => 'allowPrint', 'type' => 'boolean', 'required' => false, 'description' => 'Show print button (default: true)'),
    array('prop' => 'theme', 'type' => '"light" | "dark"', 'required' => false, 'description' => 'Viewer theme (default: "light")'),
    array('prop' => 'initialPage', 'type' => 'number', 'required' => false, 'description' => 'Initial page to display (default: 1)'),
    array('prop' => 'onLoad', 'type' => 'function', 'required' => false, 'description' => 'Callback when PDF loads'),
    array('prop' => 'onError', 'type' => 'function', 'required' => false, 'description' => 'Callback on load error'),
    array('prop' => 'onPageChange', 'type' => 'function', 'required' => false, 'description' => 'Callback when page changes'),
);
?>

<!-- Hero -->
<section class="py-16 lg:py-24 bg-card" aria-labelledby="viewer-hero-heading">
    <div class="container mx-auto px-4 lg:px-8">
        <div class="max-w-4xl mx-auto text-center">
            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-primary/20 text-foreground text-sm font-medium mb-6">
                <?php pdfviewer_react_icon(16); ?>
                <span><?php esc_html_e('React / Next.js PDF Viewer', 'pdfviewer'); ?></span>
            </div>
            <h1 id="viewer-hero-heading" class="text-4xl md:text-5xl lg:text-6xl font-extrabold tracking-tight leading-tight mb-6">
                <?php esc_html_e('Modern', 'pdfviewer'); ?> <span class="text-gradient"><?php esc_html_e('React PDF Components', 'pdfviewer'); ?></span>
            </h1>
            <p class="text-xl md:text-2xl text-muted-foreground max-w-3xl mx-auto mb-10">
                <?php esc_html_e('Display PDFs beautifully in React and Next.js applications. TypeScript support, SSR compatible, and optimized for performance.', 'pdfviewer'); ?>
            </p>
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                <a href="#download-react"
                   class="btn btn-primary btn-lg gradient-hero shadow-glow gap-2 min-w-[240px] justify-center">
                    <?php echo pdfviewer_icon('download', 20); ?>
                    <?php esc_html_e('Download Free PDF Module', 'pdfviewer'); ?>
                </a>
                <a href="<?php echo esc_url(home_url('/documentation/')); ?>#react" class="btn btn-outline btn-lg gap-2">
                    <?php esc_html_e('View Docs', 'pdfviewer'); ?>
                    <?php echo pdfviewer_icon('arrow-right', 20); ?>
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Quick Start -->
<section class="py-16 lg:py-24" aria-labelledby="quickstart-heading">
    <div class="container mx-auto px-4 lg:px-8">
        <div class="max-w-4xl mx-auto">
            <div class="text-center mb-12">
                <h2 id="quickstart-heading" class="text-3xl font-bold mb-4">
                    <?php esc_html_e('Quick Start', 'pdfviewer'); ?>
                </h2>
                <p class="text-lg text-muted-foreground">
                    <?php esc_html_e('Get up and running in minutes with just a few lines of code.', 'pdfviewer'); ?>
                </p>
            </div>

            <div class="grid md:grid-cols-2 gap-8">
                <!-- Requirements -->
                <div class="bg-card rounded-2xl p-6 border border-border">
                    <h3 class="text-xl font-bold mb-4"><?php esc_html_e('Requirements', 'pdfviewer'); ?></h3>
                    <ul class="space-y-3 list-none">
                        <?php foreach ($requirements as $req) : ?>
                            <li class="flex items-start gap-2">
                                <?php echo pdfviewer_icon('check', 18, 'text-primary shrink-0 mt-0.5'); ?>
                                <span class="text-muted-foreground"><?php echo esc_html($req); ?></span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>

                <!-- Installation -->
                <div class="bg-card rounded-2xl p-6 border border-border">
                    <h3 class="text-xl font-bold mb-4"><?php esc_html_e('Installation', 'pdfviewer'); ?></h3>
                    <div class="space-y-4">
                        <div>
                            <p class="text-sm font-medium text-foreground mb-2"><?php esc_html_e('npm:', 'pdfviewer'); ?></p>
                            <code class="block bg-muted px-4 py-3 rounded-lg font-mono text-primary text-sm">npm install @pdf-embed-seo/react</code>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-foreground mb-2"><?php esc_html_e('yarn:', 'pdfviewer'); ?></p>
                            <code class="block bg-muted px-4 py-3 rounded-lg font-mono text-primary text-sm">yarn add @pdf-embed-seo/react</code>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-foreground mb-2"><?php esc_html_e('pnpm:', 'pdfviewer'); ?></p>
                            <code class="block bg-muted px-4 py-3 rounded-lg font-mono text-primary text-sm">pnpm add @pdf-embed-seo/react</code>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Code Examples -->
<section class="py-16 lg:py-24 bg-card" aria-labelledby="examples-heading">
    <div class="container mx-auto px-4 lg:px-8">
        <div class="max-w-4xl mx-auto">
            <div class="text-center mb-12">
                <h2 id="examples-heading" class="text-3xl font-bold mb-4">
                    <?php esc_html_e('Code Examples', 'pdfviewer'); ?>
                </h2>
                <p class="text-lg text-muted-foreground">
                    <?php esc_html_e('Simple, intuitive API for embedding PDFs in your React applications.', 'pdfviewer'); ?>
                </p>
            </div>

            <div class="space-y-8">
                <!-- Basic Usage -->
                <div class="bg-background rounded-2xl border border-border p-6">
                    <h3 class="font-semibold text-lg mb-4"><?php esc_html_e('Basic Usage', 'pdfviewer'); ?></h3>
                    <pre class="bg-muted px-4 py-4 rounded-lg font-mono text-primary text-sm overflow-x-auto"><code>import { PdfViewer } from '@pdf-embed-seo/react';

export default function DocumentPage() {
  return (
    &lt;PdfViewer
      src="/documents/annual-report.pdf"
      title="Annual Report 2024"
      height="600px"
    /&gt;
  );
}</code></pre>
                </div>

                <!-- Next.js App Router -->
                <div class="bg-background rounded-2xl border border-border p-6">
                    <h3 class="font-semibold text-lg mb-4"><?php esc_html_e('Next.js App Router', 'pdfviewer'); ?></h3>
                    <pre class="bg-muted px-4 py-4 rounded-lg font-mono text-primary text-sm overflow-x-auto"><code>'use client';

import dynamic from 'next/dynamic';

const PdfViewer = dynamic(
  () => import('@pdf-embed-seo/react').then(mod => mod.PdfViewer),
  {
    ssr: false,
    loading: () => &lt;div&gt;Loading PDF...&lt;/div&gt;
  }
);

export default function DocumentPage() {
  return (
    &lt;PdfViewer
      src="/documents/whitepaper.pdf"
      title="Product Whitepaper"
      allowDownload={true}
      allowPrint={true}
    /&gt;
  );
}</code></pre>
                </div>

                <!-- With Event Handlers -->
                <div class="bg-background rounded-2xl border border-border p-6">
                    <h3 class="font-semibold text-lg mb-4"><?php esc_html_e('With Event Handlers', 'pdfviewer'); ?></h3>
                    <pre class="bg-muted px-4 py-4 rounded-lg font-mono text-primary text-sm overflow-x-auto"><code>import { PdfViewer } from '@pdf-embed-seo/react';

export default function DocumentPage() {
  const handleLoad = (pdf) => {
    console.log('PDF loaded:', pdf.numPages, 'pages');
  };

  const handlePageChange = (page) => {
    console.log('Current page:', page);
  };

  return (
    &lt;PdfViewer
      src="/documents/guide.pdf"
      onLoad={handleLoad}
      onPageChange={handlePageChange}
      onError={(error) => console.error(error)}
    /&gt;
  );
}</code></pre>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Component Props -->
<section class="py-16 lg:py-24" aria-labelledby="props-heading">
    <div class="container mx-auto px-4 lg:px-8">
        <div class="max-w-4xl mx-auto">
            <div class="text-center mb-12">
                <h2 id="props-heading" class="text-3xl font-bold mb-4">
                    <?php esc_html_e('Component Props', 'pdfviewer'); ?>
                </h2>
                <p class="text-lg text-muted-foreground">
                    <?php esc_html_e('Full TypeScript support with detailed prop definitions.', 'pdfviewer'); ?>
                </p>
            </div>

            <div class="overflow-x-auto rounded-2xl border border-border">
                <table class="w-full min-w-[600px]">
                    <thead>
                        <tr class="bg-muted">
                            <th class="text-left py-3 px-4 font-semibold text-foreground"><?php esc_html_e('Prop', 'pdfviewer'); ?></th>
                            <th class="text-left py-3 px-4 font-semibold text-foreground"><?php esc_html_e('Type', 'pdfviewer'); ?></th>
                            <th class="text-left py-3 px-4 font-semibold text-foreground"><?php esc_html_e('Required', 'pdfviewer'); ?></th>
                            <th class="text-left py-3 px-4 font-semibold text-foreground"><?php esc_html_e('Description', 'pdfviewer'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($component_props as $index => $prop) : ?>
                            <tr class="<?php echo $index % 2 === 0 ? 'bg-background' : 'bg-muted/30'; ?>">
                                <td class="py-3 px-4 font-mono text-primary text-sm"><?php echo esc_html($prop['prop']); ?></td>
                                <td class="py-3 px-4 font-mono text-muted-foreground text-sm"><?php echo esc_html($prop['type']); ?></td>
                                <td class="py-3 px-4">
                                    <?php if ($prop['required']) : ?>
                                        <span class="text-primary font-semibold"><?php esc_html_e('Yes', 'pdfviewer'); ?></span>
                                    <?php else : ?>
                                        <span class="text-muted-foreground"><?php esc_html_e('No', 'pdfviewer'); ?></span>
                                    <?php endif; ?>
                                </td>
                                <td class="py-3 px-4 text-muted-foreground text-sm"><?php echo esc_html($prop['description']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>

<!-- Features -->
<section class="py-16 lg:py-24 bg-card" aria-labelledby="features-heading">
    <div class="container mx-auto px-4 lg:px-8">
        <div class="max-w-4xl mx-auto">
            <div class="text-center mb-12">
                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-300 text-sm font-medium mb-6">
                    <?php echo pdfviewer_icon('zap', 16); ?>
                    <span><?php esc_html_e('Powered by Mozilla PDF.js', 'pdfviewer'); ?></span>
                </div>
                <h2 id="features-heading" class="text-3xl font-bold mb-4">
                    <?php esc_html_e('Built for Modern React', 'pdfviewer'); ?>
                </h2>
                <p class="text-lg text-muted-foreground max-w-2xl mx-auto">
                    <?php esc_html_e('The same technology that powers Firefox\'s PDF viewer, optimized for React and Next.js applications.', 'pdfviewer'); ?>
                </p>
            </div>
            <div class="grid md:grid-cols-3 gap-6">
                <?php foreach ($viewer_features as $index => $feature) : ?>
                    <div class="bg-background rounded-2xl p-6 border border-border text-center shadow-sm animate-fade-in" style="animation-delay: <?php echo esc_attr($index * 0.1); ?>s">
                        <div class="w-14 h-14 rounded-xl gradient-hero flex items-center justify-center mx-auto mb-4">
                            <?php echo pdfviewer_icon($feature['icon'], 28, 'text-primary-foreground'); ?>
                        </div>
                        <h3 class="font-semibold text-lg mb-2"><?php echo esc_html($feature['title']); ?></h3>
                        <p class="text-muted-foreground text-sm"><?php echo esc_html($feature['description']); ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>

<!-- Headless CMS Integration -->
<section class="py-16 lg:py-24" aria-labelledby="headless-heading">
    <div class="container mx-auto px-4 lg:px-8">
        <div class="max-w-4xl mx-auto">
            <div class="text-center mb-12">
                <h2 id="headless-heading" class="text-3xl font-bold mb-4">
                    <?php esc_html_e('Headless CMS Integration', 'pdfviewer'); ?>
                </h2>
                <p class="text-lg text-muted-foreground">
                    <?php esc_html_e('Fetch PDFs from WordPress or Drupal via their REST APIs and display them in your React frontend.', 'pdfviewer'); ?>
                </p>
            </div>

            <div class="bg-card rounded-2xl border border-border p-6">
                <h3 class="font-semibold text-lg mb-4"><?php esc_html_e('WordPress REST API Example', 'pdfviewer'); ?></h3>
                <pre class="bg-muted px-4 py-4 rounded-lg font-mono text-primary text-sm overflow-x-auto"><code>import { PdfViewer } from '@pdf-embed-seo/react';
import { useEffect, useState } from 'react';

export default function DocumentPage({ slug }) {
  const [pdf, setPdf] = useState(null);

  useEffect(() => {
    fetch(`https://your-wp-site.com/wp-json/pdf-embed-seo/v1/documents/${slug}`)
      .then(res => res.json())
      .then(data => setPdf(data));
  }, [slug]);

  if (!pdf) return &lt;div&gt;Loading...&lt;/div&gt;;

  return (
    &lt;PdfViewer
      src={pdf.pdf_url}
      title={pdf.title}
      allowDownload={pdf.allow_download}
      allowPrint={pdf.allow_print}
    /&gt;
  );
}</code></pre>
            </div>
        </div>
    </div>
</section>

<!-- Free vs Pro vs Pro+ Comparison -->
<section class="py-16 lg:py-24" aria-labelledby="react-comparison-heading">
    <div class="container mx-auto px-4 lg:px-8">
        <div class="max-w-4xl mx-auto">
            <div class="text-center mb-12">
                <h2 id="react-comparison-heading" class="text-3xl font-bold mb-4">
                    <?php esc_html_e('Free vs Pro vs Pro+', 'pdfviewer'); ?>
                </h2>
                <p class="text-lg text-muted-foreground">
                    <?php esc_html_e('Compare free, Pro, and Pro+ React components and hooks.', 'pdfviewer'); ?>
                </p>
            </div>

            <?php
            $react_comparison = array(
                array('feature' => 'PdfViewer Component', 'free' => true, 'pro' => true, 'proPlus' => true),
                array('feature' => 'PdfArchive Component', 'free' => true, 'pro' => true, 'proPlus' => true),
                array('feature' => 'Light/Dark/System Theme', 'free' => true, 'pro' => true, 'proPlus' => true),
                array('feature' => 'Print/Download Controls', 'free' => true, 'pro' => true, 'proPlus' => true),
                array('feature' => 'Schema.org SEO', 'free' => true, 'pro' => true, 'proPlus' => true),
                array('feature' => 'Next.js Metadata API', 'free' => true, 'pro' => true, 'proPlus' => true),
                array('feature' => 'React Hooks', 'free' => true, 'pro' => true, 'proPlus' => true),
                array('feature' => 'API Client (WP/Drupal)', 'free' => true, 'pro' => true, 'proPlus' => true),
                array('feature' => 'Text Search (PdfSearch)', 'free' => false, 'pro' => true, 'proPlus' => true),
                array('feature' => 'Bookmark Navigation (PdfBookmarks)', 'free' => false, 'pro' => true, 'proPlus' => true),
                array('feature' => 'Password Protection (PdfPasswordModal)', 'free' => false, 'pro' => true, 'proPlus' => true),
                array('feature' => 'Analytics Dashboard (PdfAnalytics)', 'free' => false, 'pro' => true, 'proPlus' => true),
                array('feature' => 'useAnalytics Hook', 'free' => false, 'pro' => true, 'proPlus' => true),
                array('feature' => 'usePassword Hook', 'free' => false, 'pro' => true, 'proPlus' => true),
                array('feature' => 'useSearch Hook', 'free' => false, 'pro' => true, 'proPlus' => true),
                array('feature' => 'useBookmarks Hook', 'free' => false, 'pro' => true, 'proPlus' => true),
                array('feature' => 'Reading Progress (PdfProgressBar)', 'free' => false, 'pro' => true, 'proPlus' => true),
                array('feature' => 'AI/GEO/AEO Schema', 'free' => false, 'pro' => true, 'proPlus' => true),
                array('feature' => 'Expiring Access Links', 'free' => false, 'pro' => true, 'proPlus' => true),
                array('feature' => 'Priority Support', 'free' => false, 'pro' => true, 'proPlus' => true),
                array('feature' => 'PDF Annotations', 'free' => false, 'pro' => false, 'proPlus' => true),
                array('feature' => 'Document Versioning', 'free' => false, 'pro' => false, 'proPlus' => true),
                array('feature' => 'Heatmaps & Engagement Scoring', 'free' => false, 'pro' => false, 'proPlus' => true),
                array('feature' => 'Two-Factor Authentication (2FA)', 'free' => false, 'pro' => false, 'proPlus' => true),
                array('feature' => 'Webhooks API (Zapier, etc.)', 'free' => false, 'pro' => false, 'proPlus' => true),
                array('feature' => 'White Label Mode', 'free' => false, 'pro' => false, 'proPlus' => true),
                array('feature' => 'HIPAA/GDPR Compliance Mode', 'free' => false, 'pro' => false, 'proPlus' => true),
                array('feature' => 'Dedicated Account Manager + SLA', 'free' => false, 'pro' => false, 'proPlus' => true),
            );

            if (!function_exists('pdfviewer_react_feature_cell')) {
                function pdfviewer_react_feature_cell($value) {
                    if ($value === true) {
                        return '<span class="text-primary">' . pdfviewer_icon('check', 20) . '</span>';
                    }
                    return '<span class="text-destructive">' . pdfviewer_icon('x', 20) . '</span>';
                }
            }
            ?>

            <div class="bg-card rounded-2xl border border-border overflow-hidden">
                <!-- Table Header - Desktop -->
                <div class="hidden md:grid grid-cols-4 bg-muted p-4 font-semibold text-sm sticky top-0 text-foreground">
                    <div><?php esc_html_e('Feature', 'pdfviewer'); ?></div>
                    <div class="text-center"><?php esc_html_e('Free', 'pdfviewer'); ?></div>
                    <div class="text-center text-primary"><?php esc_html_e('Pro', 'pdfviewer'); ?></div>
                    <div class="text-center text-accent"><?php esc_html_e('Pro+', 'pdfviewer'); ?></div>
                </div>

                <!-- Table Header - Mobile -->
                <div class="md:hidden grid grid-cols-4 bg-muted p-3 font-semibold text-xs sticky top-0 text-foreground">
                    <div><?php esc_html_e('Feature', 'pdfviewer'); ?></div>
                    <div class="text-center"><?php esc_html_e('Free', 'pdfviewer'); ?></div>
                    <div class="text-center text-primary"><?php esc_html_e('Pro', 'pdfviewer'); ?></div>
                    <div class="text-center text-accent"><?php esc_html_e('Pro+', 'pdfviewer'); ?></div>
                </div>

                <!-- Table Body -->
                <?php foreach ($react_comparison as $index => $item) : ?>
                    <!-- Desktop Row -->
                    <div class="hidden md:grid grid-cols-4 px-4 py-3 text-sm <?php echo $index % 2 === 0 ? 'bg-background' : 'bg-muted/50'; ?>">
                        <div class="text-foreground"><?php echo esc_html($item['feature']); ?></div>
                        <div class="text-center"><?php echo pdfviewer_react_feature_cell($item['free']); ?></div>
                        <div class="text-center"><?php echo pdfviewer_react_feature_cell($item['pro']); ?></div>
                        <div class="text-center"><?php echo pdfviewer_react_feature_cell($item['proPlus']); ?></div>
                    </div>
                    <!-- Mobile Row -->
                    <div class="md:hidden grid grid-cols-4 px-3 py-2 text-xs <?php echo $index % 2 === 0 ? 'bg-background' : 'bg-muted/50'; ?> border-t border-border/50">
                        <div class="text-foreground font-medium"><?php echo esc_html($item['feature']); ?></div>
                        <div class="text-center"><?php echo pdfviewer_react_feature_cell($item['free']); ?></div>
                        <div class="text-center"><?php echo pdfviewer_react_feature_cell($item['pro']); ?></div>
                        <div class="text-center"><?php echo pdfviewer_react_feature_cell($item['proPlus']); ?></div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>

<!-- CTA -->
<section class="py-16 lg:py-24 bg-card" aria-labelledby="react-cta-heading">
    <div class="container mx-auto px-4 lg:px-8">
        <div class="max-w-3xl mx-auto text-center">
            <h2 id="react-cta-heading" class="text-3xl font-bold mb-6">
                <?php esc_html_e('Start Building Today', 'pdfviewer'); ?>
            </h2>
            <p class="text-lg text-muted-foreground mb-8">
                <?php esc_html_e('Add professional PDF viewing to your React or Next.js application in minutes. Free to use, MIT licensed.', 'pdfviewer'); ?>
            </p>
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4 mb-8">
                <div class="flex items-center gap-2 px-6 py-3 bg-muted rounded-lg font-mono text-sm">
                    <span class="text-muted-foreground">$</span>
                    <code class="text-primary font-semibold">npm install @pdf-embed-seo/react</code>
                </div>
                <a href="<?php echo esc_url(home_url('/documentation/')); ?>#react" class="btn btn-primary btn-lg gradient-hero shadow-glow gap-2">
                    <?php esc_html_e('Read Documentation', 'pdfviewer'); ?>
                    <?php echo pdfviewer_icon('arrow-right', 20); ?>
                </a>
            </div>
            <p class="text-muted-foreground">
                <?php esc_html_e('Using WordPress or Drupal instead?', 'pdfviewer'); ?>
                <a href="<?php echo esc_url(home_url('/wordpress-pdf-viewer/')); ?>" class="text-foreground hover:underline font-semibold">
                    <?php esc_html_e('Get the WordPress plugin', 'pdfviewer'); ?> &rarr;
                </a>
                &nbsp;|&nbsp;
                <a href="<?php echo esc_url(home_url('/drupal-pdf-viewer/')); ?>" class="text-foreground hover:underline font-semibold">
                    <?php esc_html_e('Get the Drupal module', 'pdfviewer'); ?> &rarr;
                </a>
            </p>
        </div>
    </div>
</section>

<!-- Download Free React Module -->
<section id="download-react" class="py-16 lg:py-24" aria-labelledby="download-react-heading">
    <div class="container mx-auto px-4 lg:px-8">
        <div class="max-w-3xl mx-auto text-center">
            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-primary/20 text-foreground text-sm font-medium mb-6">
                <?php echo pdfviewer_icon('download', 16); ?>
                <span><?php esc_html_e('Free Download', 'pdfviewer'); ?></span>
            </div>
            <h2 id="download-react-heading" class="text-3xl font-bold mb-6">
                <?php esc_html_e('Download Free PDF Viewer Module for React / Next.js', 'pdfviewer'); ?>
            </h2>
            <p class="text-lg text-muted-foreground mb-8 max-w-2xl mx-auto">
                <?php esc_html_e('Get the open-source PDF viewer component for your React or Next.js website. Lightweight, TypeScript-ready, and fully customizable. No registration required.', 'pdfviewer'); ?>
            </p>
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                <a href="https://www.npmjs.com/package/@pdf-embed-seo/react?ref=pdfviewer"
                   target="_blank"
                   rel="noopener"
                   class="btn btn-primary btn-lg gradient-hero shadow-glow gap-2 inline-flex items-center focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2"
                   aria-label="<?php esc_attr_e('Download @pdf-embed-seo/react from npm (opens in new tab)', 'pdfviewer'); ?>"
                   title="<?php esc_attr_e('Download the free React PDF viewer component from npm', 'pdfviewer'); ?>">
                    <?php pdfviewer_react_icon(20); ?>
                    <?php esc_html_e('Download from npm', 'pdfviewer'); ?>
                    <?php echo pdfviewer_icon('external-link', 18); ?>
                </a>
                <a href="<?php echo esc_url(home_url('/pro/#pricing')); ?>"
                   class="btn btn-outline btn-lg gap-2 inline-flex items-center focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2"
                   aria-label="<?php esc_attr_e('View Pro version pricing and features', 'pdfviewer'); ?>"
                   title="<?php esc_attr_e('Upgrade to Pro for advanced features like password protection, analytics, and more', 'pdfviewer'); ?>">
                    <?php echo pdfviewer_icon('zap', 20); ?>
                    <?php esc_html_e('Get Pro', 'pdfviewer'); ?>
                </a>
            </div>
        </div>
    </div>
</section>

<?php get_footer(); ?>
