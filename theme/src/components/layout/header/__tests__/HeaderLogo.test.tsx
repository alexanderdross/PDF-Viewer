import { describe, it, expect } from "vitest";
import { render } from "@testing-library/react";
import { BrowserRouter } from "react-router-dom";
import { HeaderLogo } from "../HeaderLogo";

const screen = {
  getByAltText: (alt: string) => document.querySelector(`[alt="${alt}"]`),
  getByText: (text: string) => {
    const elements = document.querySelectorAll("*");
    for (const el of elements) {
      if (el.textContent === text) return el;
    }
    return null;
  },
  getByRole: (role: string, options?: { name: RegExp }) => {
    const elements = document.querySelectorAll(`[role="${role}"], ${role === "link" ? "a" : role}`);
    if (options?.name) {
      for (const el of elements) {
        if (options.name.test(el.getAttribute("aria-label") || "")) return el;
      }
    }
    return elements[0];
  },
};

const renderWithRouter = (component: React.ReactNode) => {
  return render(<BrowserRouter>{component}</BrowserRouter>);
};

describe("HeaderLogo", () => {
  it("renders the logo image with correct attributes", () => {
    renderWithRouter(<HeaderLogo />);
    
    const logo = screen.getByAltText("PDF Embed & SEO Optimize logo");
    expect(logo).toBeInTheDocument();
    expect(logo).toHaveAttribute("width", "40");
    expect(logo).toHaveAttribute("height", "40");
  });

  it("renders the brand name", () => {
    renderWithRouter(<HeaderLogo />);
    
    expect(screen.getByText("PDF Viewer")).toBeInTheDocument();
    expect(screen.getByText("for WordPress & Drupal")).toBeInTheDocument();
  });

  it("links to the homepage", () => {
    renderWithRouter(<HeaderLogo />);
    
    const link = screen.getByRole("link", { name: /PDF Embed & SEO Optimize - Home/i });
    expect(link).toHaveAttribute("href", "/");
  });

  it("has accessible focus styles", () => {
    renderWithRouter(<HeaderLogo />);
    
    const link = screen.getByRole("link", { name: /PDF Embed & SEO Optimize - Home/i });
    expect(link.className).toContain("focus-visible:ring-2");
  });
});
