# QA Test Checklist - PDF Viewer WordPress Theme

## Version: 2.2.79
## Last Updated: 2026-02-02

---

## 1. Theme Installation Tests

### 1.1 Fresh Installation
- [ ] Theme ZIP downloads successfully (< 2MB)
- [ ] ZIP extracts without errors
- [ ] `style.css` is present at root level
- [ ] Theme appears in WordPress Appearance > Themes
- [ ] Theme activates without PHP errors
- [ ] No console errors on activation

### 1.2 Theme Update
- [ ] Previous theme settings preserved
- [ ] No broken layouts after update
- [ ] Customizer settings intact

---

## 2. Page Template Tests

### 2.1 Front Page (front-page.php)
- [ ] Hero section renders with gradient
- [ ] All homepage sections load in order
- [ ] CTAs link correctly
- [ ] Animations trigger on scroll
- [ ] Mobile responsive layout works

### 2.2 Documentation Page (page-documentation.php)
- [ ] Platform tabs (WordPress/Drupal) switch content
- [ ] Getting Started section shows installation steps
- [ ] Configuration table displays all settings
- [ ] Shortcodes & Blocks section shows code examples
- [ ] REST API Reference shows all endpoints
- [ ] WordPress Hooks section shows Actions and Filters
- [ ] Theming & Templates shows template files
- [ ] Premium Features grid displays 6 cards
- [ ] Hash navigation works (#drupal switches to Drupal)

### 2.3 Examples Page (page-examples.php)
- [ ] Example cards display correctly
- [ ] Hash navigation scrolls to sections
- [ ] Password example shows protected state

### 2.4 Pro Page (page-pro.php)
- [ ] Pricing cards display with features
- [ ] CTA buttons link correctly
- [ ] Feature comparison table renders

### 2.5 Changelog Page (page-changelog.php)
- [ ] Version entries display chronologically
- [ ] Version badges show correct colors (green/blue/amber/purple)
- [ ] Dates format correctly

### 2.6 404 Page (404.php)
- [ ] Custom 404 design displays
- [ ] Navigation still works
- [ ] Search or home link provided

---

## 3. Header/Navigation Tests

### 3.1 Desktop Navigation (≥1280px)
- [ ] Logo displays with correct dimensions
- [ ] Logo links to homepage
- [ ] Navigation items display horizontally
- [ ] Dropdown menus work on hover/click
- [ ] Download dropdown shows WordPress/Drupal options
- [ ] Cart icon displays
- [ ] Pro CTA button visible

### 3.2 Tablet Navigation (1024-1279px)
- [ ] Download dropdown next to Platforms
- [ ] Cart icon visible
- [ ] Menu items readable

### 3.3 Mobile Navigation (<1024px)
- [ ] Hamburger menu icon displays
- [ ] Mobile menu opens on click
- [ ] Menu closes on X click
- [ ] Menu closes on outside click
- [ ] All menu items accessible
- [ ] Menu scrolls if content overflows

### 3.4 Accessibility
- [ ] Skip link appears on focus
- [ ] Focus states visible on all links
- [ ] Tab order is logical
- [ ] ARIA labels present on interactive elements

---

## 4. Footer Tests

- [ ] Footer columns display correctly
- [ ] Links navigate to correct pages
- [ ] Social icons display and link correctly
- [ ] Copyright shows current year
- [ ] Legal links (Privacy, Terms) work

---

## 5. Typography & Styling Tests

### 5.1 Fonts
- [ ] Inter font loads for body text
- [ ] Outfit font loads for headings
- [ ] Fonts load from self-hosted files (no Google Fonts)
- [ ] Font weights (400, 600, 700) display correctly

### 5.2 Colors
- [ ] Primary color (blue) displays correctly
- [ ] Accent color (orange) displays correctly
- [ ] Contrast meets WCAG AA (4.5:1 for text)
- [ ] Dark mode colors work (if applicable)

### 5.3 Spacing
- [ ] Consistent spacing between sections
- [ ] Mobile padding adequate
- [ ] No horizontal overflow on mobile

---

## 6. Performance Tests

### 6.1 Loading
- [ ] Page loads < 3 seconds on 3G
- [ ] LCP (Largest Contentful Paint) < 2.5s
- [ ] FID (First Input Delay) < 100ms
- [ ] CLS (Cumulative Layout Shift) < 0.1

### 6.2 Assets
- [ ] CSS is minified
- [ ] JavaScript is minified
- [ ] Images use WebP with fallbacks
- [ ] Lazy loading works for below-fold images
- [ ] No render-blocking resources

### 6.3 Caching
- [ ] Browser caching headers set
- [ ] Static assets have version strings

---

## 7. SEO Tests

### 7.1 Meta Tags
- [ ] Title tag present and unique per page
- [ ] Meta description present
- [ ] Open Graph tags present
- [ ] Twitter Card tags present
- [ ] Canonical URL set

### 7.2 Structured Data
- [ ] JSON-LD schema outputs valid
- [ ] SoftwareApplication schema on homepage
- [ ] WebPage schema on other pages
- [ ] No schema validation errors (Google Rich Results Test)

### 7.3 Accessibility
- [ ] All images have alt text
- [ ] Headings follow hierarchy (h1 > h2 > h3)
- [ ] Links have descriptive text
- [ ] Forms have labels

---

## 8. Cross-Browser Tests

### 8.1 Desktop Browsers
- [ ] Chrome (latest)
- [ ] Firefox (latest)
- [ ] Safari (latest)
- [ ] Edge (latest)

### 8.2 Mobile Browsers
- [ ] Chrome Mobile (Android)
- [ ] Safari Mobile (iOS)
- [ ] Samsung Internet

---

## 9. Responsive Breakpoint Tests

- [ ] 320px (small mobile)
- [ ] 375px (iPhone SE)
- [ ] 414px (iPhone Plus)
- [ ] 768px (tablet portrait)
- [ ] 1024px (tablet landscape)
- [ ] 1280px (desktop)
- [ ] 1536px (large desktop)

---

## 10. JavaScript Functionality Tests

### 10.1 Mobile Menu
- [ ] Opens on hamburger click
- [ ] Closes on X click
- [ ] Closes on outside click
- [ ] Body scroll locked when open

### 10.2 Platform Tabs (Documentation)
- [ ] WordPress tab active by default
- [ ] Clicking Drupal switches all content
- [ ] URL hash updates (#drupal)
- [ ] Direct URL with hash works

### 10.3 Accordions (FAQ)
- [ ] Items expand on click
- [ ] Items collapse on second click
- [ ] Only one item open at a time (if designed)
- [ ] Keyboard accessible (Enter/Space)

### 10.4 Smooth Scroll
- [ ] Anchor links scroll smoothly
- [ ] Offset accounts for sticky header

---

## 11. WordPress Integration Tests

### 11.1 Menus
- [ ] Primary menu location works
- [ ] Footer menu location works
- [ ] Legal links menu works
- [ ] Custom menu walker applied

### 11.2 Customizer
- [ ] Logo upload works
- [ ] Site title/tagline editable
- [ ] Custom options save correctly

### 11.3 Block Editor
- [ ] Theme styles load in editor
- [ ] Block patterns available
- [ ] Custom block styles work

---

## 12. Error Handling Tests

- [ ] No PHP warnings/notices in debug mode
- [ ] No JavaScript console errors
- [ ] Graceful fallback for missing images
- [ ] Graceful fallback for missing fonts
- [ ] 404 page shows for invalid URLs

---

## Test Sign-off

| Tester | Date | Environment | Status |
|--------|------|-------------|--------|
| | | | |
| | | | |

## Notes

_Add any observations or issues found during testing:_

---
