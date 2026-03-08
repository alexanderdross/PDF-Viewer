import { useEffect } from "react";
import { siteNavigationSchema } from "@/components/layout/header/navigation-config";

export function useNavigationSchema() {
  useEffect(() => {
    const script = document.createElement("script");
    script.type = "application/ld+json";
    script.id = "site-navigation-schema";
    script.textContent = JSON.stringify(siteNavigationSchema);

    const existing = document.getElementById("site-navigation-schema");
    if (existing) existing.remove();
    document.head.appendChild(script);

    return () => {
      const element = document.getElementById("site-navigation-schema");
      if (element) element.remove();
    };
  }, []);
}
