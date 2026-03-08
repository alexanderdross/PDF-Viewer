import { useState, useCallback, useEffect } from "react";
import { Menu, X } from "lucide-react";
import { useNavigationSchema } from "@/hooks/use-navigation-schema";
import { useActiveRoute } from "@/hooks/use-active-route";
import { HeaderLogo } from "./header/HeaderLogo";
import { DesktopNavigation } from "./header/DesktopNavigation";
import { DesktopActions } from "./header/DesktopActions";
import { MobileMenu } from "./header/MobileMenu";

export function Header() {
  const [mobileMenuOpen, setMobileMenuOpen] = useState(false);
  const [isScrolled, setIsScrolled] = useState(false);
  const { isActive } = useActiveRoute();

  // Inject SiteNavigationElement schema
  useNavigationSchema();

  // Detect scroll position for shrinking header
  useEffect(() => {
    const handleScroll = () => {
      setIsScrolled(window.scrollY > 20);
    };

    window.addEventListener("scroll", handleScroll, { passive: true });
    handleScroll(); // Check initial position

    return () => window.removeEventListener("scroll", handleScroll);
  }, []);

  const handleMobileMenuClose = useCallback(() => {
    setMobileMenuOpen(false);
  }, []);

  const handleMobileMenuToggle = useCallback(() => {
    setMobileMenuOpen(prev => !prev);
  }, []);

  return (
    <header 
      className={`fixed top-0 left-0 right-0 z-50 glass transition-all duration-300 ${
        isScrolled ? "shadow-soft" : ""
      }`} 
      role="banner"
    >
      <nav 
        className={`container mx-auto flex items-center justify-between px-4 lg:px-8 transition-all duration-300 ${
          isScrolled ? "py-2" : "py-4"
        }`} 
        aria-label="Main navigation"
      >
        <HeaderLogo isScrolled={isScrolled} />
        <DesktopNavigation isActive={isActive} />
        <DesktopActions />

        {/* Mobile/Tablet Menu Button */}
        <button
          className="lg:hidden p-2 text-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 rounded-md"
          onClick={handleMobileMenuToggle}
          aria-expanded={mobileMenuOpen}
          aria-controls="mobile-menu"
          aria-label={mobileMenuOpen ? "Close navigation menu" : "Open navigation menu"}
        >
          {mobileMenuOpen ? (
            <X className="w-6 h-6" aria-hidden="true" />
          ) : (
            <Menu className="w-6 h-6" aria-hidden="true" />
          )}
        </button>
      </nav>

      <MobileMenu 
        isOpen={mobileMenuOpen} 
        onClose={handleMobileMenuClose} 
        isActive={isActive} 
      />
    </header>
  );
}
