import { useEffect } from "react";

export function ProSchema() {
  useEffect(() => {
    const schema = {
      "@context": "https://schema.org",
      "@type": "Product",
      name: "PDF Embed & SEO Optimize Pro",
      description: "Premium plugin for advanced PDF management with analytics dashboard, password protection, reading progress tracking, and priority support. Available for WordPress, Drupal, and React.",
      brand: {
        "@type": "Brand",
        name: "PDF Embed & SEO Optimize",
      },
      category: "CMS Plugin",
      url: [
        "https://pdfviewermodule.com/pro/",
        "https://pdfviewer.drossmedia.de/pro/",
      ],
      offers: [
        {
          "@type": "Offer",
          name: "Starter",
          description: "1 site - Analytics Dashboard, Password Protection, Detailed View Tracking",
          price: "49.00",
          priceCurrency: "USD",
          priceValidUntil: "2026-12-31",
          availability: "https://schema.org/InStock",
          url: [
            "https://pdfviewermodule.com/pro/#pricing",
            "https://pdfviewer.drossmedia.de/pro/#pricing",
          ],
          seller: {
            "@type": "Organization",
            name: "Dross:Media",
          },
        },
        {
          "@type": "Offer",
          name: "Professional",
          description: "5 sites - All Starter features plus Reading Progress, XML Sitemap, Categories & Tags, CSV/JSON Export",
          price: "99.00",
          priceCurrency: "USD",
          priceValidUntil: "2026-12-31",
          availability: "https://schema.org/InStock",
          url: [
            "https://pdfviewermodule.com/pro/#pricing",
            "https://pdfviewer.drossmedia.de/pro/#pricing",
          ],
        },
        {
          "@type": "Offer",
          name: "Agency",
          description: "Unlimited sites - All Professional features plus Role-Based Access, Bulk Import, Full REST API, White-Label Options",
          price: "199.00",
          priceCurrency: "USD",
          priceValidUntil: "2026-12-31",
          availability: "https://schema.org/InStock",
          url: [
            "https://pdfviewermodule.com/pro/#pricing",
            "https://pdfviewer.drossmedia.de/pro/#pricing",
          ],
        },
        {
          "@type": "Offer",
          name: "Lifetime",
          description: "One-time payment for perpetual access to all Agency features on unlimited sites",
          price: "799.00",
          priceCurrency: "USD",
          priceValidUntil: "2026-12-31",
          availability: "https://schema.org/InStock",
          url: [
            "https://pdfviewermodule.com/pro/#pricing",
            "https://pdfviewer.drossmedia.de/pro/#pricing",
          ],
        },
      ],
      aggregateRating: {
        "@type": "AggregateRating",
        ratingValue: "4.9",
        reviewCount: "482",
        bestRating: "5",
        worstRating: "1",
      },
      review: [
        {
          "@type": "Review",
          author: {
            "@type": "Person",
            name: "Sarah Mitchell",
          },
          reviewRating: {
            "@type": "Rating",
            ratingValue: "5",
            bestRating: "5",
          },
          reviewBody: "The analytics alone are worth the upgrade. We finally know which PDFs our clients actually read.",
        },
        {
          "@type": "Review",
          author: {
            "@type": "Person",
            name: "James Rodriguez",
          },
          reviewRating: {
            "@type": "Rating",
            ratingValue: "5",
            bestRating: "5",
          },
          reviewBody: "Best PDF plugin I've worked with in 10 years of WordPress development.",
        },
      ],
    };

    const script = document.createElement("script");
    script.type = "application/ld+json";
    script.id = "pro-product-schema";
    script.textContent = JSON.stringify(schema);

    const existing = document.getElementById("pro-product-schema");
    if (existing) existing.remove();
    document.head.appendChild(script);

    return () => {
      const element = document.getElementById("pro-product-schema");
      if (element) element.remove();
    };
  }, []);

  return null;
}
