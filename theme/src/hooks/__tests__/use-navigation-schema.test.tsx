import { describe, it, expect, afterEach } from "vitest";
import { renderHook, cleanup } from "@testing-library/react";
import { useNavigationSchema } from "../use-navigation-schema";

describe("useNavigationSchema", () => {
  afterEach(() => {
    cleanup();
    // Clean up any schema scripts added during tests
    const script = document.getElementById("site-navigation-schema");
    if (script) script.remove();
  });

  it("injects schema script into document head", () => {
    renderHook(() => useNavigationSchema());
    
    const script = document.getElementById("site-navigation-schema");
    expect(script).toBeInTheDocument();
  });

  it("sets correct script type", () => {
    renderHook(() => useNavigationSchema());
    
    const script = document.getElementById("site-navigation-schema");
    expect(script?.getAttribute("type")).toBe("application/ld+json");
  });

  it("contains valid JSON-LD content", () => {
    renderHook(() => useNavigationSchema());
    
    const script = document.getElementById("site-navigation-schema");
    const content = script?.textContent;
    
    expect(content).toBeTruthy();
    const parsed = JSON.parse(content!);
    
    expect(parsed["@context"]).toBe("https://schema.org");
    expect(parsed["@type"]).toBe("SiteNavigationElement");
    expect(parsed.name).toBe("Main Navigation");
    expect(Array.isArray(parsed.hasPart)).toBe(true);
  });

  it("includes expected navigation items", () => {
    renderHook(() => useNavigationSchema());
    
    const script = document.getElementById("site-navigation-schema");
    const content = JSON.parse(script?.textContent || "{}");
    
    const names = content.hasPart.map((item: { name: string }) => item.name);
    
    expect(names).toContain("Features");
    expect(names).toContain("Pro");
    expect(names).toContain("Documentation");
    expect(names).toContain("WordPress PDF Viewer");
    expect(names).toContain("Drupal PDF Viewer");
  });

  it("removes script on unmount", () => {
    const { unmount } = renderHook(() => useNavigationSchema());
    
    expect(document.getElementById("site-navigation-schema")).toBeInTheDocument();
    
    unmount();
    
    expect(document.getElementById("site-navigation-schema")).not.toBeInTheDocument();
  });
});
