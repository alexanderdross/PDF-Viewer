import { Link } from "react-router-dom";
import { ExternalLink, Zap, ShoppingCart, Mail, ChevronDown } from "lucide-react";
import { Button } from "@/components/ui/button";
import {
  DropdownMenu,
  DropdownMenuContent,
  DropdownMenuItem,
  DropdownMenuTrigger,
} from "@/components/ui/dropdown-menu";
import { WordPressIcon, DrupalIcon } from "@/components/icons/PlatformIcons";

export function DesktopActions() {
  return (
    <div className="hidden lg:flex items-center gap-3">
      <Button variant="ghost" size="sm" asChild>
        <Link to="/contact/" className="gap-2" title="Contact us for support or inquiries">
          <Mail className="w-4 h-4" aria-hidden="true" />
          Contact
        </Link>
      </Button>
      
      <DropdownMenu>
        <DropdownMenuTrigger asChild>
          <Button variant="outline" size="sm" className="gap-2">
            Download Free
            <ChevronDown className="w-3 h-3" aria-hidden="true" />
          </Button>
        </DropdownMenuTrigger>
        <DropdownMenuContent align="end" className="bg-popover border border-border z-50">
          <DropdownMenuItem asChild>
            <a
              href="https://wordpress.org/plugins/pdf-embed-seo-optimize"
              target="_blank"
              rel="noopener noreferrer"
              className="flex items-center gap-2 cursor-pointer"
            >
              <WordPressIcon size={16} />
              WordPress Plugin
              <ExternalLink className="w-3 h-3 ml-auto" aria-hidden="true" />
            </a>
          </DropdownMenuItem>
          <DropdownMenuItem asChild>
            <a
              href="https://www.drupal.org/project/pdf-embed-seo-optimize"
              target="_blank"
              rel="noopener noreferrer"
              className="flex items-center gap-2 cursor-pointer"
            >
              <DrupalIcon size={16} />
              Drupal Module
              <ExternalLink className="w-3 h-3 ml-auto" aria-hidden="true" />
            </a>
          </DropdownMenuItem>
        </DropdownMenuContent>
      </DropdownMenu>
      
      <Button variant="ghost" size="icon" asChild className="relative">
        <Link to="/cart/" title="View shopping cart">
          <ShoppingCart className="w-4 h-4" aria-hidden="true" />
          <span className="sr-only">Shopping Cart</span>
        </Link>
      </Button>
      
      <Button size="sm" className="gradient-hero shadow-glow" asChild>
        <Link to="/pro/" className="gap-2" title="Upgrade to PDF Embed Pro for advanced features">
          <Zap className="w-4 h-4" aria-hidden="true" />
          Get Pro
        </Link>
      </Button>
    </div>
  );
}
