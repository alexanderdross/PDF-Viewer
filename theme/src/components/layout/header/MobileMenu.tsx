import { Link } from "react-router-dom";
import { ExternalLink, Zap, ShoppingCart, Mail } from "lucide-react";
import { Button } from "@/components/ui/button";
import { cn } from "@/lib/utils";
import { WordPressIcon, DrupalIcon } from "@/components/icons/PlatformIcons";
import { navigation, platformPages, NavigationItem } from "./navigation-config";

interface MobileMenuProps {
  isOpen: boolean;
  onClose: () => void;
  isActive: (href: string) => boolean;
}

function NavIcon({ icon: Icon }: { icon: NavigationItem["icon"] }) {
  if (!Icon) return null;
  return <Icon className="w-4 h-4" />;
}

export function MobileMenu({ isOpen, onClose, isActive }: MobileMenuProps) {
  if (!isOpen) return null;

  const getLinkClasses = (href: string) => cn(
    "flex items-center gap-2 text-base font-medium transition-colors py-3 min-h-[48px]",
    "focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 rounded-md",
    "active:bg-muted/50 touch-manipulation",
    isActive(href) ? "text-primary" : "text-muted-foreground"
  );

  return (
    <div
      id="mobile-menu"
      className="lg:hidden glass border-t border-border"
      role="navigation"
      aria-label="Mobile navigation"
    >
      <div className="container mx-auto py-4 px-4 space-y-1 max-h-[calc(100dvh-80px)] overflow-y-auto overscroll-contain" style={{ WebkitOverflowScrolling: "touch", paddingBottom: "env(safe-area-inset-bottom, 16px)" }}>
        {navigation.map((item) => {
          const isHashLink = item.href.startsWith("/#");

          if (isHashLink) {
            return (
              <a
                key={item.name}
                href={item.href}
                onClick={onClose}
                title={item.title}
                aria-label={`Navigate to ${item.name} section`}
                className={getLinkClasses(item.href)}
              >
                <NavIcon icon={item.icon} />
                {item.name}
              </a>
            );
          }

          return (
            <Link
              key={item.name}
              to={item.href}
              onClick={onClose}
              title={item.title}
              aria-label={`Go to ${item.name} page`}
              className={getLinkClasses(item.href)}
            >
              <NavIcon icon={item.icon} />
              {item.name}
            </Link>
          );
        })}

        {/* Platform Pages */}
        <div className="pt-3 mt-2 border-t border-border/50 space-y-1">
          <span className="text-xs font-semibold text-muted-foreground uppercase tracking-wider block py-2">Platforms</span>
          {platformPages.map((item) => (
            <Link
              key={item.name}
              to={item.href}
              onClick={onClose}
              title={item.title}
              aria-label={`View ${item.name} PDF viewer documentation and installation guide`}
              className={getLinkClasses(item.href)}
            >
              <NavIcon icon={item.icon} />
              {item.name}
            </Link>
          ))}
        </div>
        <div className="pt-4 mt-2 border-t border-border flex flex-col gap-2">
          <Button variant="outline" size="default" className="h-12 touch-manipulation" asChild>
            <Link
              to="/contact/"
              onClick={onClose}
              className="gap-2 justify-center"
              title="Contact us for support or inquiries"
              aria-label="Go to contact page"
            >
              <Mail className="w-4 h-4" aria-hidden="true" />
              Contact
            </Link>
          </Button>
          <Button variant="outline" size="default" className="h-12 touch-manipulation" asChild>
            <a
              href="https://wordpress.org/plugins/pdf-embed-seo-optimize"
              target="_blank"
              rel="noopener noreferrer"
              className="gap-2 justify-center"
              title="Download free PDF Embed & SEO Optimize plugin for WordPress"
              aria-label="Download free WordPress plugin from WordPress.org (opens in new tab)"
            >
              <WordPressIcon size={16} />
              WordPress Plugin
              <ExternalLink className="w-3 h-3" aria-hidden="true" />
            </a>
          </Button>
          <Button variant="outline" size="default" className="h-12 touch-manipulation" asChild>
            <a
              href="https://www.drupal.org/project/pdf-embed-seo-optimize"
              target="_blank"
              rel="noopener noreferrer"
              className="gap-2 justify-center"
              title="Download free PDF Embed & SEO Optimize module for Drupal"
              aria-label="Download free Drupal module from Drupal.org (opens in new tab)"
            >
              <DrupalIcon size={16} />
              Drupal Module
              <ExternalLink className="w-3 h-3" aria-hidden="true" />
            </a>
          </Button>
          <Button variant="outline" size="default" className="h-12 touch-manipulation" asChild>
            <Link to="/cart/" className="gap-2 justify-center" onClick={onClose} title="View your shopping cart" aria-label="Go to shopping cart">
              <ShoppingCart className="w-4 h-4" aria-hidden="true" />
              Cart
            </Link>
          </Button>
          <Button size="default" className="gradient-hero h-12 touch-manipulation" asChild>
            <Link to="/pro/" className="gap-2 justify-center" onClick={onClose} title="PDF Embed Pro - Advanced analytics, password protection, and more" aria-label="Upgrade to PDF Embed Pro for advanced features">
              <Zap className="w-4 h-4" aria-hidden="true" />
              Get Pro
            </Link>
          </Button>
        </div>
      </div>
    </div>
  );
}
