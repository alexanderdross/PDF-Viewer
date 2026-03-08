import { useJsonLd } from "@/hooks/use-json-ld";

const organizationSchema = {
  "@context": "https://schema.org",
  "@type": "Organization",
  "name": "Dross:Media",
  "alternateName": "PDF Embed & SEO Optimize",
  "url": "https://dross.net",
  "logo": "https://pdfviewermodule.com/og-image.png",
  "description": "Creator of the PDF Embed & SEO Optimize plugin - the best free plugin for SEO-friendly PDF embedding and document management for WordPress and Drupal.",
  "sameAs": [
    "https://pdfviewermodule.com",
    "https://pdfviewer.drossmedia.de",
    "https://wordpress.org/plugins/pdf-embed-seo-optimize",
    "https://www.drupal.org/project/pdf-embed-seo-optimize"
  ],
  "knowsAbout": [
    "WordPress Plugin Development",
    "Drupal Module Development",
    "PDF Embedding",
    "CMS SEO",
    "Document Management"
  ],
  "contactPoint": {
    "@type": "ContactPoint",
    "url": "https://dross.net/contact/",
    "contactType": "customer support",
    "email": "support@drossmedia.de",
    "availableLanguage": ["English"]
  },
  "address": {
    "@type": "PostalAddress",
    "addressCountry": "US"
  }
};

export function OrganizationSchema() {
  useJsonLd("organization-schema", organizationSchema);
  return null;
}
