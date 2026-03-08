import { useMemo } from "react";
import { useLocation, Link } from "react-router-dom";
import { ChevronRight } from "lucide-react";

const routeNames: Record<string, string> = {
  "/": "PDF Viewer",
  "/documentation/": "Documentation",
  "/examples/": "Examples",
  "/pro/": "Pro",
  "/changelog/": "Changelog",
  "/cart/": "Cart",
  "/wordpress-pdf-viewer/": "WordPress PDF Viewer",
  "/drupal-pdf-viewer/": "Drupal PDF Viewer",
  "/nextjs-pdf-viewer/": "React/Next.js PDF Viewer",
  "/pdf-grid/": "PDF Grid",
};

export function VisualBreadcrumbs() {
  const location = useLocation();

  const breadcrumbs = useMemo(() => {
    const pathSegments = location.pathname.split("/").filter(Boolean);

    const items: { name: string; path: string; external?: boolean }[] = [
      { name: "Dross:Media", path: "https://dross.net/media/", external: true },
      { name: "PDF Viewer", path: "/" },
    ];

    if (pathSegments.length > 0) {
      let currentPath = "";
      pathSegments.forEach((segment) => {
        currentPath += `/${segment}/`;
        const name =
          routeNames[currentPath] ||
          segment
            .split("-")
            .map((w) => w.charAt(0).toUpperCase() + w.slice(1))
            .join(" ");
        items.push({ name, path: currentPath });
      });
    }

    return items;
  }, [location.pathname]);

  // Don't render breadcrumbs on the homepage
  if (location.pathname === "/") return null;

  return (
    <nav
      aria-label="Breadcrumb navigation"
      className="bg-muted/60 border-b border-border"
      role="navigation"
    >
      <div className="container mx-auto px-4 lg:px-8">
        <ol
          className="flex items-center gap-1 py-2.5 text-sm overflow-x-auto whitespace-nowrap scrollbar-breadcrumb"
          itemScope
          itemType="https://schema.org/BreadcrumbList"
        >
          {breadcrumbs.map((item, index) => {
            const isLast = index === breadcrumbs.length - 1;

            return (
              <li
                key={item.path}
                className="inline-flex items-center gap-1 shrink-0"
                itemScope
                itemProp="itemListElement"
                itemType="https://schema.org/ListItem"
              >
                {index > 0 && (
                  <ChevronRight
                    className="w-3.5 h-3.5 text-muted-foreground/60 shrink-0"
                    aria-hidden="true"
                  />
                )}
                {isLast ? (
                  <span
                    className="text-foreground font-medium"
                    aria-current="page"
                    itemProp="name"
                  >
                    {item.name}
                  </span>
                ) : item.external ? (
                  <a
                    href={item.path}
                    target="_blank"
                    rel="noopener noreferrer"
                    className="text-muted-foreground hover:text-primary transition-colors"
                    title={`Go to ${item.name} (opens in new window)`}
                    aria-label={`Go to ${item.name} (opens in new window)`}
                    itemProp="item"
                  >
                    <span itemProp="name">{item.name}</span>
                  </a>
                ) : (
                  <Link
                    to={item.path}
                    className="text-muted-foreground hover:text-primary transition-colors"
                    title={`Go to ${item.name}`}
                    aria-label={`Go to ${item.name}`}
                    itemProp="item"
                  >
                    <span itemProp="name">{item.name}</span>
                  </Link>
                )}
                <meta itemProp="position" content={String(index + 1)} />
              </li>
            );
          })}
        </ol>
      </div>
    </nav>
  );
}
