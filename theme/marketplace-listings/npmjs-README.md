# @drossmedia/pdf-embed-seo

[![npm version](https://img.shields.io/npm/v/@drossmedia/pdf-embed-seo.svg)](https://www.npmjs.com/package/@drossmedia/pdf-embed-seo)
[![npm downloads](https://img.shields.io/npm/dm/@drossmedia/pdf-embed-seo.svg)](https://www.npmjs.com/package/@drossmedia/pdf-embed-seo)
[![License: MIT](https://img.shields.io/badge/License-MIT-blue.svg)](https://opensource.org/licenses/MIT)
[![TypeScript](https://img.shields.io/badge/TypeScript-Ready-blue.svg)](https://www.typescriptlang.org/)

**The only React PDF component that makes your PDFs discoverable by search engines.** Embed PDFs with full SEO optimization, automatic meta tags, and schema markup.

## Features

- **SEO Optimized** - Automatic meta tags, Open Graph, and schema.org markup
- **Server-Side Rendering** - Full Next.js App Router & Pages Router support
- **TypeScript** - Complete type definitions included
- **Responsive** - Mobile-friendly, works on all screen sizes
- **Accessible** - WCAG 2.1 AA compliant with keyboard navigation
- **Lightweight** - Tree-shakeable, minimal bundle impact
- **Customizable** - Extensive styling and configuration options

## Installation

```bash
npm install @drossmedia/pdf-embed-seo
```

```bash
yarn add @drossmedia/pdf-embed-seo
```

```bash
pnpm add @drossmedia/pdf-embed-seo
```

## Quick Start

### Basic Usage

```tsx
import { PdfViewer } from '@drossmedia/pdf-embed-seo';

function MyComponent() {
  return (
    <PdfViewer
      src="/documents/annual-report.pdf"
      title="Annual Report 2024"
    />
  );
}
```

### With SEO Metadata

```tsx
import { PdfViewer, PdfSeoHead } from '@drossmedia/pdf-embed-seo';

function DocumentPage() {
  return (
    <>
      <PdfSeoHead
        title="Annual Report 2024 - Company Name"
        description="View our comprehensive annual report covering financial performance, strategic initiatives, and future outlook."
        url="https://example.com/documents/annual-report"
        pdfUrl="https://example.com/documents/annual-report.pdf"
      />
      <PdfViewer
        src="/documents/annual-report.pdf"
        title="Annual Report 2024"
        width="100%"
        height="800px"
      />
    </>
  );
}
```

### Next.js App Router

```tsx
// app/documents/[slug]/page.tsx
import { PdfViewer } from '@drossmedia/pdf-embed-seo';
import { Metadata } from 'next';

interface Props {
  params: { slug: string };
}

export async function generateMetadata({ params }: Props): Promise<Metadata> {
  const document = await getDocument(params.slug);

  return {
    title: document.title,
    description: document.description,
    openGraph: {
      title: document.title,
      description: document.description,
      type: 'article',
      url: `https://example.com/documents/${params.slug}`,
    },
    other: {
      'schema:type': 'DigitalDocument',
    },
  };
}

export default async function DocumentPage({ params }: Props) {
  const document = await getDocument(params.slug);

  return (
    <main>
      <h1>{document.title}</h1>
      <PdfViewer
        src={document.pdfUrl}
        title={document.title}
        showToolbar
        enableDownload
        enablePrint
      />
    </main>
  );
}
```

### Next.js Pages Router

```tsx
// pages/documents/[slug].tsx
import { PdfViewer, PdfSeoHead } from '@drossmedia/pdf-embed-seo';
import { GetServerSideProps } from 'next';
import Head from 'next/head';

interface Props {
  document: {
    title: string;
    description: string;
    pdfUrl: string;
  };
}

export default function DocumentPage({ document }: Props) {
  return (
    <>
      <Head>
        <title>{document.title}</title>
        <meta name="description" content={document.description} />
      </Head>
      <PdfSeoHead
        title={document.title}
        description={document.description}
        pdfUrl={document.pdfUrl}
      />
      <PdfViewer
        src={document.pdfUrl}
        title={document.title}
      />
    </>
  );
}

export const getServerSideProps: GetServerSideProps = async ({ params }) => {
  const document = await getDocument(params?.slug as string);
  return { props: { document } };
};
```

## Components

### `<PdfViewer />`

The main PDF viewer component.

```tsx
import { PdfViewer } from '@drossmedia/pdf-embed-seo';

<PdfViewer
  src="/path/to/document.pdf"
  title="Document Title"
  width="100%"
  height="600px"
  showToolbar={true}
  enableDownload={true}
  enablePrint={true}
  enableFullscreen={true}
  initialPage={1}
  zoom="auto"
  theme="light"
  onLoad={() => console.log('PDF loaded')}
  onError={(error) => console.error('PDF error:', error)}
  onPageChange={(page) => console.log('Page:', page)}
  className="my-pdf-viewer"
/>
```

#### Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `src` | `string` | required | URL or path to the PDF file |
| `title` | `string` | `''` | Document title for accessibility |
| `width` | `string \| number` | `'100%'` | Viewer width |
| `height` | `string \| number` | `'600px'` | Viewer height |
| `showToolbar` | `boolean` | `true` | Show/hide toolbar |
| `enableDownload` | `boolean` | `true` | Enable download button |
| `enablePrint` | `boolean` | `true` | Enable print button |
| `enableFullscreen` | `boolean` | `true` | Enable fullscreen button |
| `enableSearch` | `boolean` | `true` | Enable search functionality |
| `initialPage` | `number` | `1` | Initial page to display |
| `zoom` | `'auto' \| 'fit' \| 'width' \| number` | `'auto'` | Initial zoom level |
| `theme` | `'light' \| 'dark' \| 'auto'` | `'auto'` | Color theme |
| `locale` | `string` | `'en'` | UI language |
| `onLoad` | `() => void` | - | Callback when PDF loads |
| `onError` | `(error: Error) => void` | - | Callback on load error |
| `onPageChange` | `(page: number) => void` | - | Callback on page change |
| `className` | `string` | - | Additional CSS class |
| `style` | `CSSProperties` | - | Inline styles |

### `<PdfSeoHead />`

Component for injecting SEO meta tags.

```tsx
import { PdfSeoHead } from '@drossmedia/pdf-embed-seo';

<PdfSeoHead
  title="Annual Report 2024"
  description="Comprehensive annual report covering..."
  url="https://example.com/documents/annual-report"
  pdfUrl="https://example.com/documents/annual-report.pdf"
  image="https://example.com/images/report-thumbnail.jpg"
  author="Company Name"
  publishedDate="2024-03-15"
  modifiedDate="2024-03-20"
  keywords={['annual report', 'financial', '2024']}
/>
```

#### Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `title` | `string` | required | Document title |
| `description` | `string` | required | Document description |
| `url` | `string` | - | Canonical URL |
| `pdfUrl` | `string` | - | Direct URL to PDF file |
| `image` | `string` | - | Thumbnail/preview image |
| `author` | `string` | - | Document author |
| `publishedDate` | `string` | - | ISO date string |
| `modifiedDate` | `string` | - | ISO date string |
| `keywords` | `string[]` | - | SEO keywords |

### `<PdfThumbnail />`

Generate PDF thumbnail previews.

```tsx
import { PdfThumbnail } from '@drossmedia/pdf-embed-seo';

<PdfThumbnail
  src="/path/to/document.pdf"
  page={1}
  width={200}
  height={280}
  alt="Document preview"
/>
```

### `<PdfPageCount />`

Display PDF page count.

```tsx
import { PdfPageCount } from '@drossmedia/pdf-embed-seo';

<PdfPageCount src="/path/to/document.pdf">
  {(count) => <span>{count} pages</span>}
</PdfPageCount>
```

## Hooks

### `usePdfDocument`

```tsx
import { usePdfDocument } from '@drossmedia/pdf-embed-seo';

function MyComponent() {
  const {
    document,
    isLoading,
    error,
    pageCount,
    currentPage,
    goToPage,
    nextPage,
    prevPage,
  } = usePdfDocument('/path/to/document.pdf');

  if (isLoading) return <div>Loading...</div>;
  if (error) return <div>Error: {error.message}</div>;

  return (
    <div>
      <p>Page {currentPage} of {pageCount}</p>
      <button onClick={prevPage}>Previous</button>
      <button onClick={nextPage}>Next</button>
    </div>
  );
}
```

### `usePdfMetadata`

Extract metadata from PDF files.

```tsx
import { usePdfMetadata } from '@drossmedia/pdf-embed-seo';

function MetadataDisplay() {
  const { metadata, isLoading } = usePdfMetadata('/path/to/document.pdf');

  if (isLoading) return <div>Extracting metadata...</div>;

  return (
    <dl>
      <dt>Title</dt>
      <dd>{metadata?.title}</dd>
      <dt>Author</dt>
      <dd>{metadata?.author}</dd>
      <dt>Created</dt>
      <dd>{metadata?.createdDate}</dd>
      <dt>Pages</dt>
      <dd>{metadata?.pageCount}</dd>
    </dl>
  );
}
```

## Schema.org Markup

The `<PdfSeoHead />` component automatically generates schema.org JSON-LD:

```json
{
  "@context": "https://schema.org",
  "@type": "DigitalDocument",
  "name": "Annual Report 2024",
  "description": "Comprehensive annual report covering...",
  "url": "https://example.com/documents/annual-report",
  "encodingFormat": "application/pdf",
  "author": {
    "@type": "Organization",
    "name": "Company Name"
  },
  "datePublished": "2024-03-15",
  "dateModified": "2024-03-20"
}
```

## Styling

### CSS Custom Properties

```css
:root {
  --pdf-viewer-bg: #ffffff;
  --pdf-viewer-toolbar-bg: #f5f5f5;
  --pdf-viewer-toolbar-color: #333333;
  --pdf-viewer-border-color: #e0e0e0;
  --pdf-viewer-button-hover: #e8e8e8;
  --pdf-viewer-loading-color: #3b82f6;
}

/* Dark mode */
[data-theme="dark"] {
  --pdf-viewer-bg: #1a1a1a;
  --pdf-viewer-toolbar-bg: #2d2d2d;
  --pdf-viewer-toolbar-color: #ffffff;
  --pdf-viewer-border-color: #404040;
  --pdf-viewer-button-hover: #404040;
}
```

### Tailwind CSS

```tsx
<PdfViewer
  src="/document.pdf"
  className="rounded-lg shadow-xl border border-gray-200"
/>
```

### CSS Modules

```tsx
import styles from './PdfViewer.module.css';

<PdfViewer
  src="/document.pdf"
  className={styles.customViewer}
/>
```

## Server-Side Rendering

The package is SSR-compatible. The viewer renders a loading placeholder on the server and hydrates on the client.

```tsx
// Works with:
// - Next.js App Router (RSC compatible)
// - Next.js Pages Router
// - Remix
// - Gatsby
// - Any SSR framework
```

## Pro Version

Upgrade to [PDF Embed Pro](https://pdfviewer.drossmedia.de/pro/) for advanced features:

- **Password Protection** - Secure sensitive documents
- **Analytics** - Track views, downloads, time spent
- **Watermarks** - Dynamic text/image watermarks
- **Annotations** - Highlighting and commenting
- **Custom Branding** - Remove branding, add your logo
- **Expiring Links** - Time-limited document access
- **Priority Support** - Direct support channel

```tsx
import { PdfViewerPro } from '@drossmedia/pdf-embed-seo-pro';

<PdfViewerPro
  src="/document.pdf"
  licenseKey="your-license-key"
  password="optional-password"
  watermark={{
    text: 'CONFIDENTIAL',
    opacity: 0.3,
  }}
  analytics={{
    trackViews: true,
    trackDownloads: true,
  }}
/>
```

[Compare Free vs Pro](https://pdfviewer.drossmedia.de/pro/#comparison)

## Browser Support

| Browser | Version |
|---------|---------|
| Chrome | 90+ |
| Firefox | 88+ |
| Safari | 14+ |
| Edge | 90+ |
| iOS Safari | 14+ |
| Android Chrome | 90+ |

## Bundle Size

```
@drossmedia/pdf-embed-seo
├── ESM: 45kb (gzipped: 15kb)
├── CJS: 48kb (gzipped: 16kb)
└── Types: 8kb
```

Tree-shakeable - only import what you use:

```tsx
// Full bundle
import { PdfViewer, PdfSeoHead, PdfThumbnail } from '@drossmedia/pdf-embed-seo';

// Minimal (viewer only)
import { PdfViewer } from '@drossmedia/pdf-embed-seo/viewer';
```

## TypeScript

Full TypeScript support with exported types:

```tsx
import type {
  PdfViewerProps,
  PdfSeoHeadProps,
  PdfDocument,
  PdfMetadata,
  PdfViewerTheme,
} from '@drossmedia/pdf-embed-seo';
```

## Examples

- [Basic Viewer](https://pdfviewer.drossmedia.de/examples/#basic)
- [With SEO Metadata](https://pdfviewer.drossmedia.de/examples/#seo)
- [Next.js App Router](https://pdfviewer.drossmedia.de/examples/#nextjs-app)
- [Next.js Pages Router](https://pdfviewer.drossmedia.de/examples/#nextjs-pages)
- [Custom Styling](https://pdfviewer.drossmedia.de/examples/#styling)
- [Document Gallery](https://pdfviewer.drossmedia.de/examples/#gallery)

## Contributing

Contributions are welcome! Please read our [contributing guidelines](https://github.com/alexanderdross/pdf-embed-seo/blob/main/CONTRIBUTING.md).

```bash
git clone https://github.com/alexanderdross/pdf-embed-seo.git
cd pdf-embed-seo
npm install
npm run dev
```

## Links

- **Documentation:** [https://pdfviewer.drossmedia.de/documentation/#react](https://pdfviewer.drossmedia.de/documentation/#react)
- **Live Examples:** [https://pdfviewer.drossmedia.de/examples/](https://pdfviewer.drossmedia.de/examples/)
- **GitHub:** [https://github.com/alexanderdross/pdf-embed-seo](https://github.com/alexanderdross/pdf-embed-seo)
- **Issues:** [https://github.com/alexanderdross/pdf-embed-seo/issues](https://github.com/alexanderdross/pdf-embed-seo/issues)
- **Changelog:** [https://pdfviewer.drossmedia.de/changelog/](https://pdfviewer.drossmedia.de/changelog/)

## License

MIT License - see [LICENSE](https://github.com/alexanderdross/pdf-embed-seo/blob/main/LICENSE) for details.

---

Made with care by [Dross:Media](https://drossmedia.de/)
