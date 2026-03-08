import { useMemo } from "react";
import { Link, useLocation } from "react-router-dom";
import { ChevronRight, Home } from "lucide-react";
import { cn } from "@/lib/utils";

interface BreadcrumbItem {
  name: string;
  path: string;
  isCurrentPage?: boolean;
}

interface BreadcrumbProps {
  /** Custom breadcrumb items (overrides auto-generation from route) */
  items?: BreadcrumbItem[];
  /** Additional CSS classes for the nav element */
  className?: string;
  /** Home label text (default: "PDF Embed & SEO Optimize") */
  homeLabel?: string;
  /** Show home icon instead of text on mobile */
  showHomeIcon?: boolean;
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
  "/pdf-grid/": "PDF Documents",
  "/compare/": "Compare Plugins",
  "/enterprise/": "Enterprise",
  "/contact/": "Contact",
};

export function Breadcrumb({
  items,
  className,
  homeLabel = "Dross:Media",
  showHomeIcon = false,
}: BreadcrumbProps) {
  const location = useLocation();

  const breadcrumbItems = useMemo(() => {
    if (items) return items;

    const pathSegments = location.pathname.split("/").filter(Boolean);
    const breadcrumbs: BreadcrumbItem[] = [
      { name: homeLabel, path: "/" },
      { name: "PDF Viewer", path: "/" }
    ];

    if (pathSegments.length > 0) {
      let currentPath = "";
      pathSegments.forEach((segment, index) => {
        currentPath += `/${segment}/`;
        const name = routeNames[currentPath] || segment.charAt(0).toUpperCase() + segment.slice(1).replace(/-/g, " ");
        breadcrumbs.push({
          name,
          path: currentPath,
          isCurrentPage: index === pathSegments.length - 1
        });
      });
    }

    return breadcrumbs;
  }, [items, location.pathname, homeLabel]);

  // Don't render breadcrumb on homepage
  if (location.pathname === "/" && !items) {
    return null;
  }

  return (
    <nav
      className={cn(
        "py-5",
        className
      )}
      aria-label="Breadcrumb"
    >
      <ol
        className="flex items-center flex-wrap gap-2 text-sm"
        itemScope
        itemType="https://schema.org/BreadcrumbList"
      >
        {breadcrumbItems.map((item, index) => {
          const isFirst = index === 0;
          const isLast = index === breadcrumbItems.length - 1;

          return (
            <li
              key={item.path}
              className="flex items-center gap-2"
              itemProp="itemListElement"
              itemScope
              itemType="https://schema.org/ListItem"
            >
              {/* Separator (not for first item) */}
              {!isFirst && (
                <ChevronRight
                  className="h-4 w-4 text-muted-foreground/60 flex-shrink-0"
                  aria-hidden="true"
                />
              )}

              {isLast || item.isCurrentPage ? (
                // Current page - not a link
                <span
                  className="text-muted-foreground font-medium"
                  aria-current="page"
                  itemProp="name"
                  title={item.name}
                >
                  {item.name}
                </span>
              ) : (
                // Link to previous page
                <Link
                  to={item.path}
                  className="text-primary hover:text-primary/80 hover:underline underline-offset-4 transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 rounded-sm"
                  itemProp="item"
                >
                  {/* Home icon with text on all viewports */}
                  {isFirst && showHomeIcon ? (
                    <span className="flex items-center gap-1.5">
                      <Home
                        className="h-4 w-4 flex-shrink-0"
                        aria-hidden="true"
                      />
                      <span
                        itemProp="name"
                        title={item.name}
                      >
                        {item.name}
                      </span>
                    </span>
                  ) : (
                    <span
                      itemProp="name"
                      title={item.name}
                    >
                      {item.name}
                    </span>
                  )}
                </Link>
              )}

              {/* Hidden position meta for schema */}
              <meta itemProp="position" content={String(index + 1)} />
            </li>
          );
        })}
      </ol>
    </nav>
  );
}

export default Breadcrumb;
