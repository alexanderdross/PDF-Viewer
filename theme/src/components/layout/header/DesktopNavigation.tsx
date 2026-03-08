import { Link } from "react-router-dom";
import { ChevronDown } from "lucide-react";
import { cn } from "@/lib/utils";
import { navigation, platformPages, NavigationItem } from "./navigation-config";
import {
  DropdownMenu,
  DropdownMenuContent,
  DropdownMenuItem,
  DropdownMenuTrigger,
} from "@/components/ui/dropdown-menu";

interface DesktopNavigationProps {
  isActive: (href: string) => boolean;
}

function NavIcon({ icon: Icon }: { icon: NavigationItem["icon"] }) {
  if (!Icon) return null;
  return <Icon className="w-3.5 h-3.5" />;
}

export function DesktopNavigation({ isActive }: DesktopNavigationProps) {
  const linkClasses = (active: boolean) => cn(
    "text-sm font-medium transition-colors hover:text-primary flex items-center gap-1.5",
    "focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 rounded-sm",
    active ? "text-primary" : "text-muted-foreground"
  );

  // Items to hide on tablet landscape (lg) to prevent overflow
  const tabletHidden = ["Changelog"];

  return (
    <div className="hidden lg:flex items-center gap-3 xl:gap-6" role="navigation" aria-label="Primary navigation">
      {navigation.map((item) => {
        const isHashLink = item.href.startsWith("/#");
        const hideOnTablet = tabletHidden.includes(item.name);
        const wrapperClass = hideOnTablet ? "hidden xl:flex" : "";

        if (isHashLink) {
          return (
            <a
              key={item.name}
              href={item.href}
              title={item.title}
              className={cn(linkClasses(isActive(item.href)), wrapperClass)}
            >
              {item.name}
            </a>
          );
        }

        return (
          <Link
            key={item.name}
            to={item.href}
            title={item.title}
            className={cn(linkClasses(isActive(item.href)), wrapperClass)}
          >
            {item.name}
          </Link>
        );
      })}

      {/* Platforms Dropdown */}
      <DropdownMenu>
        <DropdownMenuTrigger className={cn(linkClasses(platformPages.some(p => isActive(p.href))), "cursor-pointer")}>
          Platforms
          <ChevronDown className="w-3 h-3" aria-hidden="true" />
        </DropdownMenuTrigger>
        <DropdownMenuContent align="center" className="bg-popover border border-border z-50">
          {platformPages.map((item) => (
            <DropdownMenuItem key={item.name} asChild>
              <Link
                to={item.href}
                title={item.title}
                className="flex items-center gap-2 cursor-pointer"
              >
                <NavIcon icon={item.icon} />
                {item.name}
              </Link>
            </DropdownMenuItem>
          ))}
        </DropdownMenuContent>
      </DropdownMenu>
    </div>
  );
}
