import { useMemo } from "react";
import { useLocation } from "react-router-dom";
import { useJsonLd } from "@/hooks/use-json-ld";

interface BreadcrumbItem {
  name: string;
  path: string;
}

const routeNames: Record<string, string> = {
  "/": "Home",
  "/documentation/": "Documentation",
  "/examples/": "Examples",
  "/pro/": "Pro",
  "/changelog/": "Changelog",
  "/cart/": "Cart",
  "/wordpress-pdf-viewer/": "WordPress PDF Viewer",
  "/drupal-pdf-viewer/": "Drupal PDF Viewer",
  "/nextjs-pdf-viewer/": "React/Next.js PDF Viewer",
  "/enterprise/": "Enterprise",
  "/contact/": "Contact",
};

const baseUrl = "https://pdfviewermodule.com";

export function BreadcrumbSchema() {
  const location = useLocation();

  const schema = useMemo(() => {
    const pathSegments = location.pathname.split("/").filter(Boolean);
    
    // Build breadcrumb items
    const breadcrumbs: BreadcrumbItem[] = [
      { name: "Home", path: "/" }
    ];

    if (pathSegments.length > 0) {
      let currentPath = "";
      pathSegments.forEach((segment) => {
        currentPath += `/${segment}/`;
        const name = routeNames[currentPath] || segment.charAt(0).toUpperCase() + segment.slice(1);
        breadcrumbs.push({ name, path: currentPath });
      });
    }

    return {
      "@context": "https://schema.org",
      "@type": "BreadcrumbList",
      "itemListElement": breadcrumbs.map((item, index) => ({
        "@type": "ListItem",
        "position": index + 1,
        "name": item.name,
        "item": `${baseUrl}${item.path}`
      }))
    };
  }, [location.pathname]);

  useJsonLd("breadcrumb-schema", schema);

  return null;
}
