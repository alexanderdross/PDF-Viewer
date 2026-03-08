import { useJsonLd } from "@/hooks/use-json-ld";

const productSchema = {
  "@context": "https://schema.org",
  "@type": "Product",
  "name": "PDF Embed & SEO Optimize",
  "description": "The best free plugin for embedding PDFs with SEO optimization, clean URLs, social sharing previews, and view analytics. Available for WordPress and Drupal.",
  "brand": {
    "@type": "Brand",
    "name": "Dross:Media"
  },
  "offers": {
    "@type": "Offer",
    "price": "0",
    "priceCurrency": "EUR",
    "availability": "https://schema.org/InStock",
    "url": "https://wordpress.org/plugins/pdf-embed-seo-optimize"
  },
  "aggregateRating": {
    "@type": "AggregateRating",
    "ratingValue": "4.9",
    "bestRating": "5",
    "worstRating": "1",
    "ratingCount": "482"
  },
  "category": "CMS Plugin",
  "url": [
    "https://pdfviewermodule.com/",
    "https://pdfviewer.drossmedia.de/"
  ]
};

export function ProductSchema() {
  useJsonLd("product-schema", productSchema);
  return null;
}
