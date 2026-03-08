import { Link } from "react-router-dom";
import { OptimizedImage } from "@/components/ui/OptimizedImage";
import logoImagePng from "@/assets/logo.png";
import logoImageWebp from "@/assets/logo.webp";

interface HeaderLogoProps {
  isScrolled?: boolean;
}

export function HeaderLogo({ isScrolled = false }: HeaderLogoProps) {
  return (
    <Link 
      to="/" 
      className="flex items-center gap-2 group focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 rounded-lg" 
      aria-label="PDF Embed & SEO Optimize - Home" 
      title="Go to PDF Embed & SEO Optimize homepage"
    >
      <OptimizedImage
        src={{ srcWebp: logoImageWebp, srcFallback: logoImagePng }}
        alt="PDF Embed & SEO Optimize logo"
        width={isScrolled ? 32 : 40}
        height={isScrolled ? 32 : 40}
        loading="eager"
        fetchPriority="high"
        className="rounded-lg transition-all duration-300 group-hover:scale-105"
      />
      <div className={`flex flex-col transition-all duration-300 ${isScrolled ? "gap-0" : ""}`}>
        <span className={`font-bold text-foreground leading-tight transition-all duration-300 ${
          isScrolled ? "text-base" : "text-lg"
        }`}>PDF Viewer</span>
        <span className={`text-muted-foreground leading-tight transition-all duration-300 ${
          isScrolled ? "text-[10px]" : "text-xs"
        }`}>for WordPress & Drupal</span>
      </div>
    </Link>
  );
}
