import { Header } from "./Header";
import { Footer } from "./Footer";
import { VisualBreadcrumbs } from "./VisualBreadcrumbs";
import { BreadcrumbSchema } from "@/components/schemas/BreadcrumbSchema";
import { OrganizationSchema } from "@/components/schemas/OrganizationSchema";
import { ProductSchema } from "@/components/schemas/ProductSchema";

interface LayoutProps {
  children: React.ReactNode;
}

export function Layout({ children }: LayoutProps) {
  return (
    <div className="min-h-screen flex flex-col">
      {/* Schema markup for SEO */}
      <BreadcrumbSchema />
      <OrganizationSchema />
      <ProductSchema />
      
      {/* Skip to main content link for accessibility */}
      <a 
        href="#main-content" 
        className="sr-only focus:not-sr-only focus:absolute focus:top-4 focus:left-4 focus:z-[100] focus:px-4 focus:py-2 focus:bg-primary focus:text-primary-foreground focus:rounded-md focus:outline-none"
        title="Skip navigation and go directly to main content"
        aria-label="Skip to main content"
      >
        Skip to main content
      </a>
      
      <Header />
      
      <div className="pt-20">
        <VisualBreadcrumbs />
      </div>
      
      <main id="main-content" className="flex-1" role="main">
        {children}
      </main>
      
      <Footer />
    </div>
  );
}
