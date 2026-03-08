import { describe, it, expect } from "vitest";
import { navigation, platformPages, siteNavigationSchema, NavigationItem } from "../navigation-config";

describe("navigation config", () => {
  it("exports navigation array with correct structure", () => {
    expect(Array.isArray(navigation)).toBe(true);
    expect(navigation.length).toBeGreaterThan(0);

    navigation.forEach((item: NavigationItem) => {
      expect(item).toHaveProperty("name");
      expect(item).toHaveProperty("href");
      expect(item).toHaveProperty("title");
      expect(item).toHaveProperty("icon");
    });
  });

  it("contains required navigation items", () => {
    const names = navigation.map((item) => item.name);

    expect(names).toContain("Features");
    expect(names).toContain("Pro");
    expect(names).toContain("Docs");
    expect(names).toContain("Examples");
    expect(names).toContain("Changelog");
  });

  it("exports platformPages with WordPress and Drupal", () => {
    expect(Array.isArray(platformPages)).toBe(true);

    const names = platformPages.map((item) => item.name);
    expect(names).toContain("WordPress");
    expect(names).toContain("Drupal");
  });

  it("has icon components for all items", () => {
    navigation.forEach((item) => {
      // Icons can be functions (plain components) or objects (ForwardRef components)
      expect(item.icon).toBeTruthy();
      expect(["function", "object"]).toContain(typeof item.icon);
    });

    platformPages.forEach((item) => {
      expect(item.icon).toBeTruthy();
      expect(["function", "object"]).toContain(typeof item.icon);
    });
  });

  it("all hrefs start with / or /#", () => {
    navigation.forEach((item) => {
      expect(item.href.startsWith("/")).toBe(true);
    });

    platformPages.forEach((item) => {
      expect(item.href.startsWith("/")).toBe(true);
    });
  });

  it("all titles are descriptive", () => {
    navigation.forEach((item) => {
      expect(item.title.length).toBeGreaterThan(10);
    });

    platformPages.forEach((item) => {
      expect(item.title.length).toBeGreaterThan(10);
    });
  });
});

describe("siteNavigationSchema", () => {
  it("has correct schema.org context", () => {
    expect(siteNavigationSchema["@context"]).toBe("https://schema.org");
  });

  it("has correct type", () => {
    expect(siteNavigationSchema["@type"]).toBe("SiteNavigationElement");
  });

  it("contains hasPart array with navigation items", () => {
    expect(Array.isArray(siteNavigationSchema.hasPart)).toBe(true);
    expect(siteNavigationSchema.hasPart.length).toBeGreaterThan(0);
  });

  it("each hasPart item has required properties", () => {
    siteNavigationSchema.hasPart.forEach((item) => {
      expect(item["@type"]).toBe("SiteNavigationElement");
      expect(item.name).toBeTruthy();
      expect(item.url).toMatch(/^https:\/\//);
    });
  });
});
