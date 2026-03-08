import { useCallback } from "react";
import { useLocation } from "react-router-dom";

export function useActiveRoute() {
  const location = useLocation();

  const isActive = useCallback((href: string) => {
    if (href.startsWith("/#")) {
      return location.pathname === "/" && location.hash === href.slice(1);
    }
    // Match with or without trailing slash
    const normalizedPath = location.pathname.endsWith('/') ? location.pathname : `${location.pathname}/`;
    const normalizedHref = href.endsWith('/') ? href : `${href}/`;
    return normalizedPath === normalizedHref;
  }, [location.pathname, location.hash]);

  return { isActive };
}
