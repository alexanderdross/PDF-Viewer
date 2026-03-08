import { useEffect } from "react";

interface SEOHeadProps {
  title: string;
  description: string;
  canonicalPath?: string;
  ogImage?: string;
  type?: "website" | "article";
  noindex?: boolean;
}

/**
 * SEO component for managing document head meta tags
 * Optimized for traditional SEO, GEO (Generative Engine Optimization), 
 * and AEO (Answer Engine Optimization)
 */
export function SEOHead({
  title,
  description,
  canonicalPath = "",
  ogImage = "/og-image.png",
  type = "website",
  noindex = false,
}: SEOHeadProps) {
  const baseUrl = "https://pdfviewermodule.com";
  const fullUrl = `${baseUrl}${canonicalPath}`;
  const fullTitle = title.includes("PDF Embed") ? title : `${title} | PDF Embed & SEO Optimize`;

  useEffect(() => {
    // Update document title
    document.title = fullTitle;

    // Helper to update or create meta tags
    const setMeta = (selector: string, content: string, attribute = "content") => {
      const element = document.querySelector(selector) as HTMLMetaElement | HTMLLinkElement;
      if (element) {
        element.setAttribute(attribute, content);
      }
    };

    const setMetaByName = (name: string, content: string) => {
      let element = document.querySelector(`meta[name="${name}"]`) as HTMLMetaElement;
      if (!element) {
        element = document.createElement("meta");
        element.name = name;
        document.head.appendChild(element);
      }
      element.content = content;
    };

    const setMetaByProperty = (property: string, content: string) => {
      let element = document.querySelector(`meta[property="${property}"]`) as HTMLMetaElement;
      if (!element) {
        element = document.createElement("meta");
        element.setAttribute("property", property);
        document.head.appendChild(element);
      }
      element.content = content;
    };

    // Update meta tags
    setMetaByName("description", description);
    setMetaByName("robots", noindex ? "noindex, nofollow" : "index, follow, max-image-preview:large");

    // Open Graph
    setMetaByProperty("og:title", fullTitle);
    setMetaByProperty("og:description", description);
    setMetaByProperty("og:url", fullUrl);
    setMetaByProperty("og:type", type);
    
    // Set OG image with WebP preference and fallback
    const ogImageUrl = ogImage.startsWith("http") ? ogImage : `${baseUrl}${ogImage}`;
    setMetaByProperty("og:image", ogImageUrl);
    setMetaByProperty("og:image:width", "1200");
    setMetaByProperty("og:image:height", "630");
    setMetaByProperty("og:image:type", ogImage.endsWith(".webp") ? "image/webp" : "image/png");

    // Twitter
    setMetaByName("twitter:title", fullTitle);
    setMetaByName("twitter:description", description);
    setMetaByName("twitter:url", fullUrl);
    setMetaByName("twitter:image", ogImage.startsWith("http") ? ogImage : `${baseUrl}${ogImage}`);

    // Canonical URL
    let canonical = document.querySelector('link[rel="canonical"]') as HTMLLinkElement;
    if (!canonical) {
      canonical = document.createElement("link");
      canonical.rel = "canonical";
      document.head.appendChild(canonical);
    }
    canonical.href = fullUrl;

  }, [fullTitle, description, fullUrl, ogImage, type, noindex]);

  return null;
}
