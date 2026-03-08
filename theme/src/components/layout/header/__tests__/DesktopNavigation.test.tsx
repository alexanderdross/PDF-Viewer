import { describe, it, expect, vi } from "vitest";
import { render, screen } from "@testing-library/react";
import { BrowserRouter } from "react-router-dom";
import { DesktopNavigation } from "../DesktopNavigation";

const renderWithRouter = (component: React.ReactNode) => {
  return render(<BrowserRouter>{component}</BrowserRouter>);
};

describe("DesktopNavigation", () => {
  const mockIsActive = vi.fn().mockReturnValue(false);

  it("renders main navigation items", () => {
    renderWithRouter(<DesktopNavigation isActive={mockIsActive} />);

    expect(screen.getByText("Features")).toBeInTheDocument();
    expect(screen.getByText("Pro")).toBeInTheDocument();
    expect(screen.getByText("Docs")).toBeInTheDocument();
    expect(screen.getByText("Examples")).toBeInTheDocument();
    expect(screen.getByText("Changelog")).toBeInTheDocument();
  });

  it("renders Platforms dropdown trigger", () => {
    renderWithRouter(<DesktopNavigation isActive={mockIsActive} />);

    expect(screen.getByText("Platforms")).toBeInTheDocument();
  });

  it("has correct navigation role and aria-label", () => {
    renderWithRouter(<DesktopNavigation isActive={mockIsActive} />);

    const nav = screen.getByRole("navigation", { name: /primary navigation/i });
    expect(nav).toBeInTheDocument();
  });

  it("applies active styling when isActive returns true", () => {
    const isActiveMock = vi.fn((href: string) => href === "/pro/");
    renderWithRouter(<DesktopNavigation isActive={isActiveMock} />);

    const proLink = screen.getByText("Pro").closest("a");
    expect(proLink?.className).toContain("text-primary");
  });

  it("has accessible focus styles on all links", () => {
    renderWithRouter(<DesktopNavigation isActive={mockIsActive} />);

    const links = screen.getAllByRole("link");
    links.forEach((link) => {
      expect(link.className).toContain("focus-visible:ring-2");
    });
  });
});
