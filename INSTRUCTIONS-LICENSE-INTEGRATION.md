# License Integration Instructions: Connecting PDF Modules to the License Dashboard

**Date:** 2026-03-03
**Status:** Implementation Complete
**Scope:** WordPress Premium/Pro+, Drupal Premium/Pro+, React/Next.js Premium/Pro+

---

## Overview

This document describes how the PDF Embed & SEO Optimize plugins/modules across all three platforms (WordPress, Drupal, React/Next.js) connect to the **PDF License Manager (PLM)** dashboard hosted at `pdfviewer.drossmedia.de`.

### End-to-End Purchase Flow

```
1. Customer visits pdfviewer.drossmedia.de/pricing
2. Clicks "Buy Now" on a plan → Stripe Checkout Session created
3. Customer completes payment on Stripe
4. Stripe fires checkout.session.completed webhook
5. PLM receives webhook → verifies signature → creates license in DB
6. PLM sends license key to customer via email
7. Customer enters key in their WordPress/Drupal/React plugin settings
8. Plugin calls PLM API to validate + activate the key
9. PLM records the installation (site URL, platform, versions)
10. Plugin appears in the License Dashboard → Installations page
11. Plugin sends daily heartbeat to keep the installation alive
```

---

## Architecture

```
                    ┌─────────────────────────────────────┐
                    │  pdfviewer.drossmedia.de (PLM API)  │
                    │                                     │
                    │  POST /plm/v1/license/validate      │
                    │  POST /plm/v1/license/activate      │
                    │  POST /plm/v1/license/deactivate    │
                    │  POST /plm/v1/license/check         │
                    └──────────┬──────────────────────────┘
                               │
              ┌────────────────┼────────────────┐
              │                │                │
     ┌────────▼──────┐  ┌─────▼──────┐  ┌──────▼──────┐
     │  WordPress    │  │  Drupal    │  │  React/     │
     │  Premium/Pro+ │  │  Premium/  │  │  Next.js    │
     │               │  │  Pro+      │  │  Pro/Pro+   │
     │  wp_remote_   │  │  Guzzle   │  │  fetch()    │
     │  post()       │  │  HTTP     │  │  server-    │
     │               │  │  Client   │  │  side       │
     └───────────────┘  └──────────┘  └─────────────┘
```

---

## API Endpoints Used

### 1. Validate License (`POST /plm/v1/license/validate`)

Called when the user first enters their license key.

**Request:**
```json
{
  "license_key": "PDF$PRO#A1B2-C3D4@E5F6-G7H8!I9J0",
  "platform": "wordpress"
}
```

**Response:**
```json
{
  "valid": true,
  "status": "active",
  "type": "premium",
  "plan": "professional",
  "expires_at": "2027-03-03T00:00:00",
  "days_remaining": 365,
  "site_limit": 5,
  "active_sites": 2,
  "message": "License is valid."
}
```

### 2. Activate License (`POST /plm/v1/license/activate`)

Called after successful validation to register this site/installation.

**Request:**
```json
{
  "license_key": "PDF$PRO#A1B2-C3D4@E5F6-G7H8!I9J0",
  "site_url": "https://customer-site.com",
  "platform": "wordpress",
  "plugin_version": "1.3.0",
  "php_version": "8.2.0",
  "cms_version": "6.4.2"
}
```

**Response:**
```json
{
  "activated": true,
  "activation_id": 42,
  "status": "active",
  "site_limit": 5,
  "active_sites": 3,
  "remaining_sites": 2,
  "expires_at": "2027-03-03T00:00:00",
  "latest_version": "1.3.0",
  "message": "License activated successfully for customer-site.com"
}
```

### 3. Heartbeat / Check (`POST /plm/v1/license/check`)

Called every 24 hours via cron to keep the installation alive and check validity.

**Request:**
```json
{
  "license_key": "PDF$PRO#A1B2-C3D4@E5F6-G7H8!I9J0",
  "site_url": "https://customer-site.com",
  "plugin_version": "1.3.0",
  "platform": "wordpress"
}
```

**Response:**
```json
{
  "valid": true,
  "status": "active",
  "expires_at": "2027-03-03T00:00:00",
  "days_remaining": 365,
  "latest_version": "1.3.0",
  "update_available": false,
  "update_url": null,
  "message": null,
  "checked_at": "2026-03-03T12:00:00+00:00"
}
```

### 4. Deactivate (`POST /plm/v1/license/deactivate`)

Called when the user deactivates their license or uninstalls the plugin.

**Request:**
```json
{
  "license_key": "PDF$PRO#A1B2-C3D4@E5F6-G7H8!I9J0",
  "site_url": "https://customer-site.com"
}
```

---

## Platform-Specific Implementation

### WordPress (Premium & Pro+)

**Files modified:**
- `wordpress-pdf-embed-seo/premium/class-pdf-embed-seo-premium.php`
- `wordpress-pdf-embed-seo/pro-plus/class-pdf-embed-seo-pro-plus.php`

**Integration points:**

| Event | Action | Method |
|-------|--------|--------|
| License key saved | Validate + Activate remotely | `validate_license()` |
| Daily cron | Heartbeat check | `heartbeat_check()` via `pdf_embed_seo_license_check` |
| Plugin deactivation | Deactivate remotely | `deactivate_license()` via `register_deactivation_hook` |
| License removed from settings | Deactivate remotely | `validate_license()` detects empty key |

**Fallback behavior:**
- If the API is unreachable, fall back to local regex validation
- If the heartbeat fails, retry next day (features stay active)
- Grace period of 14 days after expiration

**WordPress options stored:**
- `pdf_embed_seo_premium_license_key` - The key itself
- `pdf_embed_seo_premium_license_status` - `valid`, `invalid`, `expired`, `inactive`, `grace_period`
- `pdf_embed_seo_premium_license_expires` - ISO date
- `pdf_embed_seo_premium_license_type` - `premium` or `pro_plus`
- `pdf_embed_seo_premium_license_plan` - `starter`, `professional`, `agency`, `enterprise`
- `pdf_embed_seo_premium_last_check` - Timestamp of last heartbeat

---

### Drupal (Premium & Pro+)

**Files modified:**
- `drupal-pdf-embed-seo/modules/pdf_embed_seo_premium/src/Service/LicenseValidator.php` (new)
- `drupal-pdf-embed-seo/modules/pdf_embed_seo_premium/pdf_embed_seo_premium.services.yml` (updated)
- `drupal-pdf-embed-seo/modules/pdf_embed_seo_premium/src/Form/PdfPremiumSettingsForm.php`
- `drupal-pdf-embed-seo/modules/pdf_embed_seo_premium/pdf_embed_seo_premium.module`
- `drupal-pdf-embed-seo/modules/pdf_embed_seo_pro_plus/src/Service/LicenseValidator.php`
- `drupal-pdf-embed-seo/modules/pdf_embed_seo_pro_plus/pdf_embed_seo_pro_plus.module` (new/updated)

**Integration points:**

| Event | Action | Method |
|-------|--------|--------|
| License key saved via form | Validate + Activate remotely | `LicenseValidator::validateRemote()` |
| Drupal cron | Heartbeat check (every 24h) | `pdf_embed_seo_premium_cron()` |
| Module uninstall | Deactivate remotely | `LicenseValidator::deactivateRemote()` |
| License removed via form | Deactivate remotely | `PdfPremiumSettingsForm::deactivateLicense()` |

**Drupal config/state stored:**
- Config: `pdf_embed_seo_premium.settings.license_key`
- Config: `pdf_embed_seo_premium.settings.license_status`
- Config: `pdf_embed_seo_premium.settings.license_expires`
- Config: `pdf_embed_seo_premium.settings.license_type`
- Config: `pdf_embed_seo_premium.settings.license_plan`
- State: `pdf_embed_seo_premium.last_license_check` (timestamp)

---

### React/Next.js (Premium & Pro+)

**Files modified:**
- `react-pdf-embed-seo/packages/react-pro-plus/src/hooks/useLicense.ts` (new)
- `react-pdf-embed-seo/packages/react-pro-plus/src/utils/license.ts` (updated)
- `react-pdf-embed-seo/packages/react-pro-plus/src/index.ts` (updated exports)
- `react-pdf-embed-seo/packages/react-premium/src/hooks/useLicense.ts` (new)
- `react-pdf-embed-seo/packages/react-premium/src/utils/license.ts` (new)
- `react-pdf-embed-seo/packages/react-premium/src/index.ts` (updated exports)

**Integration points:**

| Event | Action | Method |
|-------|--------|--------|
| App initialization | Validate license server-side | `useLicense()` hook or API route |
| Next.js API route | Server-side validation | `/api/validate-license` |
| Environment variable | License key from `.env` | `PDF_LICENSE_KEY` or `NEXT_PUBLIC_PDF_LICENSE_KEY` |

**Important:** License validation for React MUST happen server-side to avoid exposing keys in client bundles. The `useLicense()` hook calls a local API route which then calls the PLM API.

---

## Network Failure Handling

All platforms follow the same resilience strategy:

| Scenario | Behavior |
|----------|----------|
| API unreachable on first activation | Fall back to local regex validation |
| API unreachable on heartbeat | Retry next day, features stay active |
| API returns 429 (rate limited) | Back off, retry next cycle |
| API returns invalid/expired | Update local status accordingly |
| Dashboard server completely down | Features remain active based on last known status |
| No internet on customer site | Local regex + cached status keeps features active |

---

## How Purchases Appear in the License Dashboard

After a customer purchases and activates:

1. **License Dashboard > Licenses page** shows:
   - License key (masked)
   - Type (premium/pro_plus)
   - Plan (starter/professional/agency/enterprise)
   - Status (active/inactive/expired)
   - Customer email
   - Created date, expiry date
   - Number of active installations

2. **License Dashboard > Installations page** shows:
   - Site URL
   - Platform (wordpress/drupal/react)
   - Plugin version
   - PHP version, CMS version
   - Last heartbeat timestamp
   - GeoIP data (country, city)
   - Activation date

3. **License Dashboard > Audit Log** shows:
   - `license.created` (from Stripe webhook)
   - `license.activated` (from plugin phone-home)
   - `license.checked` (from daily heartbeat)
   - `license.deactivated` (from plugin deactivation)

---

## Testing the Integration

### Quick Test (Local Development)

1. Start with a known license key from the PLM admin
2. Enter the key in the plugin settings (WP/Drupal) or `.env` (React)
3. Verify the plugin calls the validate endpoint (check PLM audit log)
4. Verify the installation appears in PLM > Installations
5. Wait for or trigger cron to verify heartbeat appears in audit log
6. Deactivate the license and verify installation is removed

### Full End-to-End Test (Stripe Test Mode)

1. Configure Stripe in test mode on the website
2. Purchase a plan using test card `4242 4242 4242 4242`
3. Verify Stripe webhook creates a license in PLM
4. Verify license key email is received
5. Enter key in plugin settings on a test site
6. Verify installation appears in PLM dashboard
7. Verify daily heartbeat works
8. Deactivate plugin and verify installation is removed

---

## Admin Disclosure Notice

WordPress.org and Drupal.org require disclosure when plugins communicate with external servers. The following notice is displayed on license settings pages:

> "This plugin communicates with pdfviewer.drossmedia.de for license validation. Your site URL, plugin version, and license key are transmitted securely over HTTPS. [Privacy Policy](https://pdfviewer.drossmedia.de/privacy/)"

---

## License

GPL v2 or later - https://www.gnu.org/licenses/gpl-2.0.html

Made with care by [Dross:Media](https://dross.net/media/)
