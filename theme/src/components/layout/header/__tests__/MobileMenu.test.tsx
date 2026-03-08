import { describe, it, expect, vi, beforeEach } from "vitest";
import { render } from "@testing-library/react";
import { BrowserRouter } from "react-router-dom";
import { MobileMenu } from "../MobileMenu";

const getScreen = () => ({
  queryByRole: (role: string) => {
    const selector = role === "navigation" ? "[role='navigation'], nav" : `[role="${role}"]`;
    return document.querySelector(selector);
  },
  getByRole: (role: string, options?: { name: RegExp }) => {
    const selector = role === "navigation" ? "[role='navigation'], nav" : `[role="${role}"]`;
    const elements = document.querySelectorAll(selector);
    if (options?.name) {
      for (const el of elements) {
        if (options.name.test(el.getAttribute("aria-label") || "")) return el;
      }
    }
    return elements[0];
  },
  getByText: (text: string) => {
    const walker = document.createTreeWalker(document.body, NodeFilter.SHOW_TEXT);
    while (walker.nextNode()) {
      if (walker.currentNode.textContent?.trim() === text) {
        return walker.currentNode.parentElement;
      }
    }
    return null;
  },
});

const triggerClick = (element: Element | null) => {
  if (element) {
    element.dispatchEvent(new MouseEvent("click", { bubbles: true }));
  }
};

const renderWithRouter = (component: React.ReactNode) => {
  return render(<BrowserRouter>{component}</BrowserRouter>);
};

describe("MobileMenu", () => {
  const mockOnClose = vi.fn();
  const mockIsActive = vi.fn().mockReturnValue(false);

  beforeEach(() => {
    vi.clearAllMocks();
  });

  it("renders nothing when isOpen is false", () => {
    renderWithRouter(
      <MobileMenu isOpen={false} onClose={mockOnClose} isActive={mockIsActive} />
    );
    
    const s = getScreen();
    expect(s.queryByRole("navigation")).not.toBeInTheDocument();
  });

  it("renders navigation when isOpen is true", () => {
    renderWithRouter(
      <MobileMenu isOpen={true} onClose={mockOnClose} isActive={mockIsActive} />
    );
    
    const s = getScreen();
    expect(s.getByRole("navigation", { name: /mobile navigation/i })).toBeInTheDocument();
  });

  it("renders all navigation items when open", () => {
    renderWithRouter(
      <MobileMenu isOpen={true} onClose={mockOnClose} isActive={mockIsActive} />
    );
    
    const s = getScreen();
    expect(s.getByText("Features")).toBeInTheDocument();
    expect(s.getByText("Pro")).toBeInTheDocument();
    expect(s.getByText("WordPress")).toBeInTheDocument();
    expect(s.getByText("Drupal")).toBeInTheDocument();
  });

  it("renders action buttons", () => {
    renderWithRouter(
      <MobileMenu isOpen={true} onClose={mockOnClose} isActive={mockIsActive} />
    );
    
    const s = getScreen();
    expect(s.getByText("Contact")).toBeInTheDocument();
    expect(s.getByText("WordPress Plugin")).toBeInTheDocument();
    expect(s.getByText("Drupal Module")).toBeInTheDocument();
    expect(s.getByText("Cart")).toBeInTheDocument();
    expect(s.getByText("Get Pro")).toBeInTheDocument();
  });

  it("calls onClose when a navigation link is clicked", () => {
    renderWithRouter(
      <MobileMenu isOpen={true} onClose={mockOnClose} isActive={mockIsActive} />
    );
    
    const s = getScreen();
    triggerClick(s.getByText("Features"));
    expect(mockOnClose).toHaveBeenCalledTimes(1);
  });

  it("calls onClose when Cart button is clicked", () => {
    renderWithRouter(
      <MobileMenu isOpen={true} onClose={mockOnClose} isActive={mockIsActive} />
    );
    
    const s = getScreen();
    triggerClick(s.getByText("Cart"));
    expect(mockOnClose).toHaveBeenCalled();
  });

  it("has correct id for mobile-menu", () => {
    renderWithRouter(
      <MobileMenu isOpen={true} onClose={mockOnClose} isActive={mockIsActive} />
    );
    
    expect(document.getElementById("mobile-menu")).toBeInTheDocument();
  });
});
