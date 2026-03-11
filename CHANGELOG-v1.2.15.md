# Changelog — PDF AcroForms Release

**Release Date:** March 11, 2026

---

## WordPress — v1.2.12 (Free/Premium), v1.3.1 (Pro+)

### Added
- **PDF AcroForms (Public Form Filling)** — Fill text fields, checkboxes, radio buttons, dropdowns, and date pickers directly in the browser-based PDF viewer
- **Form Validation** — Real-time validation with visual indicators for required fields, email, phone, and date formats
- **Download Filled PDF** — Users can download the PDF with their filled-in form data embedded
- **Print Filled PDF** — Print the PDF including all form data with optimized print layout
- **Clear Form Data** — Reset all form fields to their default/empty state with confirmation dialog
- **State Persistence** — Form data survives page refresh, back/forward navigation, and device rotation via sessionStorage
- **Data Loss Warning** — Browser prompt warns users about unsaved form data when navigating away
- **Online Submission (Pro+)** — Optional server-side form submission endpoint for collecting completed forms
- **Responsive Form Fields** — Minimum 44x44px touch targets, mobile-optimized keyboards for email/phone/number fields
- **Privacy-First Architecture** — All form data stored in browser memory only, no server-side PII storage

### Fixed
- **License Validation** — Pro+ keys (`PDF$PRO+#...`) were rejected by the Premium module's local fallback validator

### Changed
- Version bump to 1.2.12 (Free/Premium), 1.3.1 (Pro+)

---

## Drupal — v1.2.15 (Free/Premium), v1.3.1 (Pro+)

### Added
- **PDF AcroForms (Public Form Filling)** — Fill text fields, checkboxes, radio buttons, dropdowns, and date pickers directly in the browser-based PDF viewer
- **Form Validation** — Real-time validation with visual indicators for required fields, email, phone, and date formats
- **Download Filled PDF** — Users can download the PDF with their filled-in form data embedded
- **Print Filled PDF** — Print the PDF including all form data with optimized print layout
- **Clear Form Data** — Reset all form fields to their default/empty state with confirmation dialog
- **State Persistence** — Form data survives page refresh, back/forward navigation, and device rotation via sessionStorage
- **Data Loss Warning** — Browser prompt warns users about unsaved form data when navigating away
- **Online Submission (Pro+)** — Optional server-side form submission endpoint for collecting completed forms
- **Responsive Form Fields** — Minimum 44x44px touch targets, mobile-optimized keyboards for email/phone/number fields
- **Privacy-First Architecture** — All form data stored in browser memory only, no server-side PII storage

### Changed
- Version bump to 1.2.15 (Free/Premium), 1.3.1 (Pro+)

---

## React/Next.js — v1.2.12 (Free/Premium), v1.3.1 (Pro+)

### Added
- **PDF AcroForms (Public Form Filling)** — Fill text fields, checkboxes, radio buttons, dropdowns, and date pickers directly in the browser-based PDF viewer
- **Form Validation** — Real-time validation with visual indicators for required fields, email, phone, and date formats
- **Download Filled PDF** — Users can download the PDF with their filled-in form data embedded
- **Print Filled PDF** — Print the PDF including all form data with optimized print layout
- **Clear Form Data** — Reset all form fields to their default/empty state with confirmation dialog
- **State Persistence** — Form data survives page refresh, back/forward navigation, and device rotation via sessionStorage
- **Data Loss Warning** — Browser prompt warns users about unsaved form data when navigating away
- **Online Submission (Pro+)** — Optional server-side form submission endpoint for collecting completed forms
- **Responsive Form Fields** — Minimum 44x44px touch targets, mobile-optimized keyboards for email/phone/number fields
- **Privacy-First Architecture** — All form data stored in browser memory only, no server-side PII storage
- **`usePdfForm` hook** — React hook for managing form state, validation, and submission
- **`PdfFormViewer` component** — Drop-in form-aware PDF viewer with built-in form toolbar

### Changed
- Version bump to 1.2.12 (Free/Premium), 1.3.1 (Pro+)

---

## NPM Packages

| Package | Version |
|---------|---------|
| `@pdf-embed-seo/core` | 1.2.12 |
| `@pdf-embed-seo/react` | 1.2.12 |
| `@pdf-embed-seo/react-premium` | 1.2.12 |
| `@pdf-embed-seo/react-pro-plus` | 1.3.1 |

---

## Key Features Summary

### PDF AcroForms — Public Form Filling

| Feature | Free | Premium | Pro+ |
|---------|:----:|:-------:|:----:|
| Fill text fields, checkboxes, radios, dropdowns | ✓ | ✓ | ✓ |
| Real-time form validation | ✓ | ✓ | ✓ |
| Download filled PDF | ✓ | ✓ | ✓ |
| Print filled PDF | ✓ | ✓ | ✓ |
| Clear/reset form data | ✓ | ✓ | ✓ |
| State persistence (sessionStorage) | ✓ | ✓ | ✓ |
| Data loss warning | ✓ | ✓ | ✓ |
| Responsive touch targets (44x44px) | ✓ | ✓ | ✓ |
| Online form submission | — | — | ✓ |

### Data Storage Architecture

| Tier | Storage | Persistence |
|------|---------|-------------|
| Browser Memory | JavaScript runtime | Current session only |
| sessionStorage | Per-tab browser storage | Survives refresh, lost on tab close |
| Downloaded File | User's device | Permanent (user-managed) |
| Server Submission (Pro+) | Backend endpoint | Configurable retention |

> **Privacy-First:** No form data is stored on the server by default. All PII remains in the user's browser until explicitly downloaded or submitted (Pro+ only).
