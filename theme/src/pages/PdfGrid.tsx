import { Layout } from "@/components/layout/Layout";
import { SEOHead } from "@/components/SEOHead";
import { Breadcrumb } from "@/components/ui/breadcrumb";
import { Link } from "react-router-dom";
import pdfThumbnail from "@/assets/pdf-thumbnail.jpg";

const pdfDocuments = [
  {
    id: 1,
    title: "Standalone PDF with Password Protection",
    date: "29. January 2026",
    href: "/pdf/example-1/",
  },
  {
    id: 2,
    title: "In-Page PDF with Password Protection",
    date: "29. January 2026",
    href: "/pdf/example-2/",
  },
  {
    id: 3,
    title: "Standalone PDF without Download/Print",
    date: "29. January 2026",
    href: "/pdf/example-3/",
  },
  {
    id: 4,
    title: "Standalone PDF with Download & Print",
    date: "29. January 2026",
    href: "/pdf/example-4/",
  },
  {
    id: 5,
    title: "In-Page PDF without Download/Print",
    date: "29. January 2026",
    href: "/pdf/example-5/",
  },
  {
    id: 6,
    title: "In-Page PDF with Download & Print",
    date: "29. January 2026",
    href: "/pdf/example-6/",
  },
];

export default function PdfGrid() {
  return (
    <Layout>
      <SEOHead
        title="PDF Documents | PDF Embed & SEO Optimize Plugin"
        description="Browse all available PDF documents. View examples of PDF embedding with various configurations."
        canonicalPath="/pdf-grid/"
      />

      <div className="container mx-auto px-4 py-8">
        {/* Breadcrumb */}
        <Breadcrumb
          className="mb-4"
          homeLabel="PDF Embed & SEO Optimize Plugin"
        />

        {/* Header */}
        <header className="text-center mb-12">
          <h1 className="text-3xl md:text-4xl font-bold mb-4">PDF Documents</h1>
          <p className="text-muted-foreground text-lg">
            Browse all available PDF documents.
          </p>
        </header>

        {/* Grid */}
        <div className="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
          {pdfDocuments.map((doc) => (
            <Link
              key={doc.id}
              to={doc.href}
              title={`View ${doc.title}`}
              className="flex flex-col bg-card rounded-xl shadow-sm overflow-hidden hover:shadow-md transition-shadow"
            >
              <article>
                {/* Thumbnail */}
                <div className="aspect-[4/3] overflow-hidden">
                  <img 
                    src={pdfThumbnail} 
                    alt={`${doc.title} thumbnail`}
                    className="w-full h-full object-cover"
                    loading="lazy"
                  />
                </div>

                {/* Content */}
                <div className="p-5 flex flex-col flex-1">
                  {/* Title */}
                  <h2 className="text-lg font-bold leading-tight mb-1">
                    {doc.title}
                  </h2>
                  
                  {/* Date */}
                  <p className="text-sm text-muted-foreground mb-4">{doc.date}</p>

                  {/* Button visual */}
                  <span className="inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-md text-sm font-medium h-10 px-4 py-2 bg-primary text-primary-foreground w-fit mt-auto">
                    View PDF
                  </span>
                </div>
              </article>
            </Link>
          ))}
        </div>
      </div>
    </Layout>
  );
}
