import { Link } from "react-router-dom";
import { ExternalLink, Heart } from "lucide-react";
import { OptimizedImage } from "@/components/ui/OptimizedImage";
import logoImagePng from "@/assets/logo.png";
import logoImageWebp from "@/assets/logo.webp";
import { WordPressIcon, DrupalIcon } from "@/components/icons/PlatformIcons";

export function Footer() {
  return (
    <footer className="gradient-dark text-primary-foreground" role="contentinfo">
      <div className="container mx-auto px-4 py-16 lg:px-8">
        <div className="grid grid-cols-1 md:grid-cols-4 gap-12">
          {/* Brand */}
          <div className="md:col-span-2">
            <Link 
              to="/" 
              className="flex items-center gap-2 mb-4 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 rounded-lg" 
              aria-label="PDF Embed & SEO Optimize - Home" 
              title="Go to PDF Embed & SEO Optimize homepage"
            >
              <OptimizedImage
                src={{ srcWebp: logoImageWebp, srcFallback: logoImagePng }}
                alt="PDF Embed & SEO Optimize logo"
                width={40}
                height={40}
                loading="lazy"
                className="rounded-lg"
              />
              <div className="flex flex-col">
                <span className="font-bold text-lg leading-tight">PDF Embed & SEO Optimize</span>
                <span className="text-xs text-primary-foreground/60 leading-tight">for WordPress & Drupal</span>
              </div>
            </Link>
            <p className="text-primary-foreground/70 max-w-md mb-6">
              The open-source plugin that transforms how you serve PDFs. 
              Built with Mozilla's PDF.js for seamless viewing and optimized for SEO.
              Available for both WordPress and Drupal.
            </p>
            <div className="flex items-center gap-4">
              <a
                href="https://wordpress.org/plugins/pdf-embed-seo-optimize"
                target="_blank"
                rel="noopener noreferrer"
                className="text-primary-foreground/60 hover:text-primary-foreground transition-colors flex items-center gap-1.5 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 rounded-sm"
                aria-label="Download from WordPress.org (opens in new tab)"
                title="Download free PDF Embed & SEO Optimize plugin from WordPress.org"
              >
                <WordPressIcon size={14} />
                <span className="text-sm">WordPress</span>
                <ExternalLink className="w-3.5 h-3.5" aria-hidden="true" />
              </a>
              <span className="text-primary-foreground/40" aria-hidden="true">|</span>
              <a
                href="https://www.drupal.org/project/pdf-embed-seo-optimize"
                target="_blank"
                rel="noopener noreferrer"
                className="text-primary-foreground/60 hover:text-primary-foreground transition-colors flex items-center gap-1.5 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 rounded-sm"
                aria-label="Download from Drupal.org (opens in new tab)"
                title="Download free PDF Embed & SEO Optimize module from Drupal.org"
              >
                <DrupalIcon size={14} />
                <span className="text-sm">Drupal</span>
                <ExternalLink className="w-3.5 h-3.5" aria-hidden="true" />
              </a>
            </div>
          </div>

          {/* Links */}
          <nav aria-label="Footer resources navigation">
            <h3 className="font-semibold mb-4 text-base">Resources</h3>
            <ul className="space-y-3" role="list">
              <li>
                <Link to="/documentation/" className="text-primary-foreground/70 hover:text-primary-foreground transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 rounded-sm" title="Read the plugin documentation and setup guides">
                  Documentation
                </Link>
              </li>
              <li>
                <Link to="/examples/" className="text-primary-foreground/70 hover:text-primary-foreground transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 rounded-sm" title="View example implementations and use cases">
                  Examples
                </Link>
              </li>
              <li>
                <Link to="/pro/" className="text-primary-foreground/70 hover:text-primary-foreground transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 rounded-sm" title="Explore Pro version features and pricing">
                  Pro Features
                </Link>
              </li>
              <li>
                <Link to="/changelog/" className="text-primary-foreground/70 hover:text-primary-foreground transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 rounded-sm" title="View version history and recent updates">
                  Changelog
                </Link>
              </li>
              <li>
                <Link to="/enterprise/" className="text-primary-foreground/70 hover:text-primary-foreground transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 rounded-sm" title="Enterprise plans for regulated industries">
                  Enterprise
                </Link>
              </li>
              <li>
                <Link to="/contact/" className="text-primary-foreground/70 hover:text-primary-foreground transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 rounded-sm" title="Contact us for enterprise consultation">
                  Contact
                </Link>
              </li>
            </ul>
          </nav>

          {/* Platform Guides */}
          <nav aria-label="Footer platform guides navigation">
            <h3 className="font-semibold mb-4 text-base">Platform Guides</h3>
            <ul className="space-y-3" role="list">
              <li>
                <Link to="/wordpress-pdf-viewer/" className="text-primary-foreground/70 hover:text-primary-foreground transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 rounded-sm flex items-center gap-1.5" title="WordPress PDF Viewer Plugin - embed and optimize PDFs">
                  <WordPressIcon size={12} />
                  WordPress PDF Viewer
                </Link>
              </li>
              <li>
                <Link to="/drupal-pdf-viewer/" className="text-primary-foreground/70 hover:text-primary-foreground transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 rounded-sm flex items-center gap-1.5" title="Drupal PDF Viewer Module - embed and optimize PDFs">
                  <DrupalIcon size={12} />
                  Drupal PDF Viewer
                </Link>
              </li>
            </ul>
          </nav>
        </div>

        {/* Bottom Bar */}
        <div className="border-t border-primary-foreground/10 mt-12 pt-8 flex flex-col md:flex-row items-center justify-between gap-4 text-sm">
          <p className="text-primary-foreground/60 leading-6">
            © {new Date().getFullYear()} PDF Embed & SEO Optimize. All rights reserved.
          </p>
          
          {/* Legal Links */}
          <nav className="flex items-center gap-4 leading-6" aria-label="Legal links">
            <a
              href="https://dross.net/imprint"
              target="_blank"
              rel="noopener noreferrer"
              className="text-primary-foreground/60 hover:text-primary-foreground transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 rounded-sm"
              title="View company imprint and contact information"
            >
              Imprint
            </a>
            <span className="text-primary-foreground/40" aria-hidden="true">|</span>
            <a
              href="https://dross.net/privacy-policy"
              target="_blank"
              rel="noopener noreferrer"
              className="text-primary-foreground/60 hover:text-primary-foreground transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 rounded-sm"
              title="Read our privacy policy"
            >
              Privacy Policy
            </a>
            <span className="text-primary-foreground/40" aria-hidden="true">|</span>
            <Link
              to="/contact/"
              className="text-primary-foreground/60 hover:text-primary-foreground transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 rounded-sm"
              title="Contact us"
            >
              Contact
            </Link>
          </nav>

          <p className="text-primary-foreground/60 inline-flex items-center gap-1 leading-6">
            Made with <Heart className="w-4 h-4 text-accent flex-shrink-0" aria-label="love" /> by{" "}
            <a
              href="https://dross.net/#media"
              target="_blank"
              rel="noopener noreferrer"
              className="text-accent hover:underline inline"
              title="Visit Dross:Media website"
            >
              Dross:Media
            </a>
          </p>
        </div>
      </div>
    </footer>
  );
}
