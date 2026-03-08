import { LucideIcon, Sparkles, BookOpen, Zap, History, Eye, Building2 } from "lucide-react";
import { WordPressIcon, DrupalIcon, NextjsIcon } from "@/components/icons/PlatformIcons";

export interface NavItem {
  name: string;
  href: string;
  icon: LucideIcon | React.ComponentType<{ className?: string }>;
  title: string;
  external?: boolean;
}

// Alias for backward compatibility
export type NavigationItem = NavItem;

export const navigation: NavItem[] = [
  { name: "Features", href: "/#features", icon: Sparkles, title: "Explore key features" },
  { name: "Examples", href: "/examples/", icon: Eye, title: "View live examples" },
  { name: "Pro", href: "/pro/", icon: Zap, title: "View Pro features and pricing" },
  { name: "Docs", href: "/documentation/", icon: BookOpen, title: "Read documentation" },
  { name: "Changelog", href: "/changelog/", icon: History, title: "View version history" },
  { name: "Enterprise", href: "/enterprise/", icon: Building2, title: "Enterprise plans for regulated industries" },
];

// Platform-specific landing pages for header dropdown
export const platformPages: NavItem[] = [
  { name: "WordPress", href: "/wordpress-pdf-viewer/", icon: WordPressIcon, title: "WordPress PDF Viewer Plugin" },
  { name: "Drupal", href: "/drupal-pdf-viewer/", icon: DrupalIcon, title: "Drupal PDF Viewer Module" },
  { name: "React/Next.js", href: "/nextjs-pdf-viewer/", icon: NextjsIcon, title: "React/Next.js PDF Viewer Components" },
];

export const siteNavigationSchema = {
  "@context": "https://schema.org",
  "@type": "SiteNavigationElement",
  name: "Main Navigation",
  hasPart: [
    {
      "@type": "SiteNavigationElement",
      name: "Features",
      url: ["https://pdfviewermodule.com/#features", "https://pdfviewer.drossmedia.de/#features"]
    },
    {
      "@type": "SiteNavigationElement",
      name: "Examples",
      url: ["https://pdfviewermodule.com/examples/", "https://pdfviewer.drossmedia.de/examples/"]
    },
    {
      "@type": "SiteNavigationElement",
      name: "Pro",
      url: ["https://pdfviewermodule.com/pro/", "https://pdfviewer.drossmedia.de/pro/"]
    },
    {
      "@type": "SiteNavigationElement",
      name: "Documentation",
      url: ["https://pdfviewermodule.com/documentation/", "https://pdfviewer.drossmedia.de/documentation/"]
    },
    {
      "@type": "SiteNavigationElement",
      name: "Changelog",
      url: ["https://pdfviewermodule.com/changelog/", "https://pdfviewer.drossmedia.de/changelog/"]
    },
    {
      "@type": "SiteNavigationElement",
      name: "Enterprise",
      url: ["https://pdfviewermodule.com/enterprise/", "https://pdfviewer.drossmedia.de/enterprise/"]
    },
    {
      "@type": "SiteNavigationElement",
      name: "WordPress PDF Viewer",
      url: ["https://pdfviewermodule.com/wordpress-pdf-viewer/", "https://pdfviewer.drossmedia.de/wordpress-pdf-viewer/"]
    },
    {
      "@type": "SiteNavigationElement",
      name: "Drupal PDF Viewer",
      url: ["https://pdfviewermodule.com/drupal-pdf-viewer/", "https://pdfviewer.drossmedia.de/drupal-pdf-viewer/"]
    },
    {
      "@type": "SiteNavigationElement",
      name: "React/Next.js PDF Viewer",
      url: ["https://pdfviewermodule.com/nextjs-pdf-viewer/", "https://pdfviewer.drossmedia.de/nextjs-pdf-viewer/"]
    },
    {
      "@type": "SiteNavigationElement",
      name: "Cart",
      url: ["https://pdfviewermodule.com/cart/", "https://pdfviewer.drossmedia.de/cart/"]
    },
    {
      "@type": "SiteNavigationElement",
      name: "Contact",
      url: ["https://pdfviewermodule.com/contact/", "https://pdfviewer.drossmedia.de/contact/"]
    }
  ]
};
