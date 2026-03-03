# Stripe Integration Concept for PDF Embed & SEO Optimize

**Date:** 2026-03-03
**Author:** Dross:Media
**Status:** Concept / Draft
**Domain:** pdfviewer.drossmedia.de

---

## Table of Contents

1. [Overview](#1-overview)
2. [Architecture](#2-architecture)
3. [Stripe Setup on the Website](#3-stripe-setup-on-the-website)
4. [Product & Pricing Configuration](#4-product--pricing-configuration)
5. [Checkout Flow](#5-checkout-flow)
6. [License Key Generation](#6-license-key-generation)
7. [Stripe Webhook Processing](#7-stripe-webhook-processing)
8. [License Validation API](#8-license-validation-api)
9. [Phone-Home Integration (WordPress)](#9-phone-home-integration-wordpress)
10. [Phone-Home Integration (Drupal)](#10-phone-home-integration-drupal)
11. [Phone-Home Integration (React/Next.js)](#11-phone-home-integration-reactnextjs)
12. [License Dashboard Admin](#12-license-dashboard-admin)
13. [Email Notifications](#13-email-notifications)
14. [Security Considerations](#14-security-considerations)
15. [Implementation Roadmap](#15-implementation-roadmap)
16. [Current Status & Gap Analysis](#16-current-status--gap-analysis)

---

## 1. Overview

This document describes the end-to-end Stripe integration for selling PDF Embed & SEO Optimize licenses on `pdfviewer.drossmedia.de`. The system covers:

- Stripe Checkout on the website (customer purchases a plan)
- Automatic license key generation via Stripe webhooks
- License delivery via email
- License validation API (already built in the License Dashboard plugin)
- Phone-home integration in WordPress, Drupal, and React plugins/modules

### Systems Involved

| System | Domain | Role |
|--------|--------|------|
| **Website + Shop** | `pdfviewer.drossmedia.de` | Product pages, Stripe Checkout, webhook receiver, License Dashboard |
| **Stripe** | `stripe.com` | Payment processing, subscription management |
| **WordPress Plugin** | Customer sites | Premium/Pro+ with phone-home license check |
| **Drupal Module** | Customer sites | Premium/Pro+ with phone-home license check |
| **React Package** | Customer apps | Pro/Pro+ with license validation |

---

## 2. Architecture

```
┌─────────────────────────────────────────────────────────────────────┐
│                        Customer Browser                              │
└──────────────┬──────────────────────────────────────┬───────────────┘
               │ 1. Select Plan                        │ 4. Receive Key
               ▼                                       │
┌──────────────────────────────────────┐               │
│  pdfviewer.drossmedia.de             │               │
│  (WordPress + PDF License Manager)   │               │
├──────────────────────────────────────┤               │
│                                      │               │
│  Pricing Page                        │               │
│  ├─ Plan Cards (Free/Premium/Pro+)   │               │
│  └─ "Buy Now" → Stripe Checkout ────┼──► Stripe     │
│                                      │     │         │
│  REST API (PLM)                      │     │         │
│  ├─ POST /webhook/stripe ◄───────────┼─────┘         │
│  │   → Verify signature              │  2. Webhook   │
│  │   → Create license                │               │
│  │   → Send email ───────────────────┼───────────────┘
│  │                                   │  3. wp_mail()
│  ├─ POST /license/validate           │
│  ├─ POST /license/activate   ◄───────┼─── WordPress/Drupal/React
│  ├─ POST /license/deactivate         │    plugins phone home
│  ├─ POST /license/check              │    every 24 hours
│  └─ GET  /health                     │
│                                      │
│  Admin Dashboard                     │
│  ├─ License management               │
│  ├─ Installation tracking            │
│  ├─ Stripe product mapping           │
│  └─ Analytics & audit log            │
└──────────────────────────────────────┘
```

---

## 3. Stripe Setup on the Website

### 3.1 Prerequisites

- Stripe account (live + test mode)
- SSL certificate on `pdfviewer.drossmedia.de`
- PDF License Manager plugin installed and activated

### 3.2 Stripe Dashboard Configuration

1. **Create Products** in Stripe Dashboard → Products:
   - One product per plan (e.g., "Premium Starter", "Premium Professional", "Pro+ Agency")
   - Each product has a recurring price (yearly) or one-time price (lifetime)

2. **Create Webhook Endpoint** in Stripe Dashboard → Developers → Webhooks:
   - **URL:** `https://pdfviewer.drossmedia.de/wp-json/plm/v1/webhook/stripe`
   - **Events to listen for:**
     - `checkout.session.completed`
     - `invoice.paid`
     - `invoice.payment_failed`
     - `customer.subscription.deleted`

3. **Copy Webhook Signing Secret** (`whsec_...`)

### 3.3 WordPress Configuration

Add to `wp-config.php`:

```php
// Stripe Webhook Secret (from Stripe Dashboard → Webhooks → Signing secret)
define( 'PLM_STRIPE_WEBHOOK_SECRET', 'whsec_your_secret_here' );
```

### 3.4 Stripe Checkout Integration on the Pricing Page

The website pricing page needs JavaScript to create a Stripe Checkout Session and redirect the customer. Two approaches:

#### Option A: Server-side Checkout Session (Recommended)

Add a custom REST endpoint or AJAX handler that creates a Stripe Checkout Session:

```php
// In a custom plugin or theme functions.php on the shop site:
add_action( 'wp_ajax_nopriv_plm_create_checkout', 'plm_create_checkout_session' );
add_action( 'wp_ajax_plm_create_checkout', 'plm_create_checkout_session' );

function plm_create_checkout_session() {
    $price_id = sanitize_text_field( $_POST['price_id'] ?? '' );

    // Stripe API call (requires stripe-php SDK or manual cURL)
    $response = wp_remote_post( 'https://api.stripe.com/v1/checkout/sessions', array(
        'headers' => array(
            'Authorization' => 'Bearer ' . STRIPE_SECRET_KEY,
            'Content-Type'  => 'application/x-www-form-urlencoded',
        ),
        'body' => array(
            'mode'                => 'subscription', // or 'payment' for lifetime
            'line_items[0][price]' => $price_id,
            'line_items[0][quantity]' => 1,
            'success_url'         => home_url( '/thank-you/?session_id={CHECKOUT_SESSION_ID}' ),
            'cancel_url'          => home_url( '/pricing/' ),
            'metadata[product_id]' => $price_id, // Used by webhook to map to license type
        ),
    ) );

    $session = json_decode( wp_remote_retrieve_body( $response ) );
    wp_send_json_success( array( 'url' => $session->url ) );
}
```

#### Option B: Stripe Payment Links (Simpler)

Create Payment Links in the Stripe Dashboard and link directly from the pricing page buttons. No server code needed, but less flexibility with metadata.

### 3.5 Pricing Page Button Example

```html
<button class="buy-button" data-price="price_premium_starter">
  Buy Premium Starter - €49/year
</button>

<script>
document.querySelectorAll('.buy-button').forEach(btn => {
  btn.addEventListener('click', async () => {
    const res = await fetch('/wp-admin/admin-ajax.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body: `action=plm_create_checkout&price_id=${btn.dataset.price}`
    });
    const data = await res.json();
    if (data.success) {
      window.location.href = data.data.url;
    }
  });
});
</script>
```

---

## 4. Product & Pricing Configuration

### 4.1 Plan Matrix

| Plan | License Type | Sites | Price (yearly) | Stripe Product ID |
|------|-------------|-------|----------------|-------------------|
| **Premium Starter** | `premium` | 1 | €49 | `prod_premium_starter` |
| **Premium Professional** | `premium` | 5 | €99 | `prod_premium_pro` |
| **Premium Agency** | `premium` | Unlimited | €199 | `prod_premium_agency` |
| **Pro+ Starter** | `pro_plus` | 1 | €99 | `prod_proplus_starter` |
| **Pro+ Professional** | `pro_plus` | 5 | €199 | `prod_proplus_pro` |
| **Pro+ Agency** | `pro_plus` | Unlimited | €399 | `prod_proplus_agency` |
| **Pro+ Enterprise** | `pro_plus` | Unlimited | €799 | `prod_proplus_enterprise` |

### 4.2 Stripe Product Mapping (in License Dashboard)

The `wp_plm_stripe_product_map` table maps Stripe products to license parameters. Configure via **License Dashboard → Settings**:

| Stripe Product ID | Stripe Price ID | License Type | Plan | Site Limit | Duration (days) |
|-------------------|----------------|-------------|------|------------|----------------|
| `prod_premium_starter` | `price_xxx` | `premium` | `starter` | 1 | 365 |
| `prod_premium_pro` | `price_xxx` | `premium` | `professional` | 5 | 365 |
| `prod_premium_agency` | `price_xxx` | `premium` | `agency` | 0 (unlimited) | 365 |
| `prod_proplus_starter` | `price_xxx` | `pro_plus` | `starter` | 1 | 365 |
| `prod_proplus_pro` | `price_xxx` | `pro_plus` | `professional` | 5 | 365 |
| `prod_proplus_agency` | `price_xxx` | `pro_plus` | `agency` | 0 (unlimited) | 365 |
| `prod_proplus_enterprise` | `price_xxx` | `pro_plus` | `enterprise` | 0 (unlimited) | 365 |

### 4.3 Features per Tier

| Feature | Free | Premium | Pro+ |
|---------|:----:|:-------:|:----:|
| PDF.js Viewer, SEO, REST API | Yes | Yes | Yes |
| Analytics, Passwords, Progress | - | Yes | Yes |
| Categories, Tags, Bulk Import | - | Yes | Yes |
| XML Sitemap, Expiring Links | - | Yes | Yes |
| Annotations, Heatmaps | - | - | Yes |
| Document Versioning | - | - | Yes |
| Webhooks, 2FA, Compliance | - | - | Yes |

---

## 5. Checkout Flow

```
 Customer              Website                   Stripe              License Dashboard
    │                     │                        │                        │
    │─ Click "Buy Now" ──▶│                        │                        │
    │                     │─ Create Checkout ──────▶│                        │
    │                     │  Session (price_id,     │                        │
    │                     │  metadata.product_id)   │                        │
    │                     │◀─ Session URL ──────────│                        │
    │◀─ Redirect ─────────│                        │                        │
    │                     │                        │                        │
    │─ Enter Payment ────────────────────────────▶│                        │
    │◀─ Payment Success ─────────────────────────│                        │
    │                     │                        │                        │
    │─ Redirect to ──────▶│ /thank-you/            │                        │
    │  success_url         │                        │                        │
    │                     │                        │── Webhook ────────────▶│
    │                     │                        │  checkout.session.     │
    │                     │                        │  completed             │
    │                     │                        │                        │
    │                     │                        │                  ┌─────┤
    │                     │                        │                  │ 1. Verify signature
    │                     │                        │                  │ 2. Lookup product map
    │                     │                        │                  │ 3. Generate license key
    │                     │                        │                  │ 4. Store in DB
    │                     │                        │                  │ 5. Send email
    │                     │                        │                  └─────┤
    │◀────────── License key via email ────────────────────────────────────│
```

### 5.1 Thank You Page

The thank you page should:
1. Confirm the purchase
2. Display: "Your license key will be sent to your email within a few minutes"
3. Optionally use the Stripe session ID to show order details

---

## 6. License Key Generation

### 6.1 Key Formats (Already Implemented)

The License Dashboard plugin (`class-plm-license.php`) generates keys using cryptographically secure `random_bytes()`:

| Type | Format | Example |
|------|--------|---------|
| **Premium** | `PDF$PRO#XXXX-XXXX@XXXX-XXXX!XXXX` | `PDF$PRO#A1B2-C3D4@E5F6-G7H8!I9J0` |
| **Pro+** | `PDF$PRO+#XXXX-XXXX@XXXX-XXXX!XXXX` | `PDF$PRO+#A1B2-C3D4@E5F6-G7H8!I9J0` |
| **Unlimited** | `PDF$UNLIMITED#XXXX@XXXX!XXXX` | `PDF$UNLIMITED#A1B2@C3D4!E5F6` |
| **Development** | `PDF$DEV#XXXX-XXXX@XXXX!XXXX` | `PDF$DEV#A1B2-C3D4@E5F6!G7H8` |

`X` = alphanumeric (A-Z, 0-9), generated via `random_bytes()`.

### 6.2 Generation Code (Existing)

```php
// In class-plm-license.php:
public static function generate_key( string $type = 'premium' ): string {
    $s = fn( int $n ) => self::random_segment( $n );

    return match ( $type ) {
        'pro_plus'  => "PDF\$PRO+#{$s(4)}-{$s(4)}@{$s(4)}-{$s(4)}!{$s(4)}",
        'unlimited' => "PDF\$UNLIMITED#{$s(4)}@{$s(4)}!{$s(4)}",
        'dev'       => "PDF\$DEV#{$s(4)}-{$s(4)}@{$s(4)}!{$s(4)}",
        default     => "PDF\$PRO#{$s(4)}-{$s(4)}@{$s(4)}-{$s(4)}!{$s(4)}",
    };
}
```

### 6.3 When Keys Are Generated

| Trigger | Method | Result |
|---------|--------|--------|
| Stripe webhook `checkout.session.completed` | Automatic | Key generated + stored + emailed |
| Admin creates manually in Dashboard | Manual | Key generated via admin form |

---

## 7. Stripe Webhook Processing

### 7.1 Webhook Handler (Already Implemented)

The `PLM_Stripe` class in `class-plm-stripe.php` handles all webhook events:

```
POST https://pdfviewer.drossmedia.de/wp-json/plm/v1/webhook/stripe
```

### 7.2 Signature Verification (Already Implemented)

Native PHP implementation (no Stripe SDK required):
1. Parse `stripe-signature` header (`t=timestamp,v1=signature`)
2. Reject timestamps older than 5 minutes
3. HMAC-SHA256 verification using `PLM_STRIPE_WEBHOOK_SECRET`
4. Constant-time comparison via `hash_equals()`

### 7.3 Events Processed

| Stripe Event | Action | Status |
|-------------|--------|--------|
| `checkout.session.completed` | Create license from product mapping, send email | **Implemented** (email TODO) |
| `invoice.paid` | Extend license by 365 days (renewal) | **Implemented** |
| `invoice.payment_failed` | Log failed payment, audit trail | **Implemented** |
| `customer.subscription.deleted` | Log cancellation, license expires at end of period | **Implemented** |
| `charge.refunded` | Revoke license immediately | **TODO** |

### 7.4 Checkout → License Creation Flow (Existing Code)

```php
// In PLM_Stripe::handle_checkout_completed():
// 1. Extract customer_email and metadata.product_id from session
// 2. Lookup product mapping in wp_plm_stripe_product_map
// 3. Generate license key: PLM_License::generate_key($key_type)
// 4. Insert into wp_plm_licenses with status='inactive'
// 5. Audit log: license.created
// 6. TODO: Send email via wp_mail()
```

### 7.5 Idempotency (Already Implemented)

Each Stripe event is stored with its `stripe_event_id` (UNIQUE constraint). Duplicate webhook deliveries are detected and ignored.

---

## 8. License Validation API

### 8.1 Endpoints (Already Implemented)

**Base URL:** `https://pdfviewer.drossmedia.de/wp-json/plm/v1/`

| Method | Endpoint | Purpose | Auth |
|--------|----------|---------|------|
| `GET` | `/health` | System health check | None |
| `POST` | `/license/validate` | Validate a license key | None |
| `POST` | `/license/activate` | Activate on a site | None |
| `POST` | `/license/deactivate` | Deactivate from a site | None |
| `POST` | `/license/check` | Heartbeat (24h interval) | None |
| `POST` | `/webhook/stripe` | Stripe webhook receiver | Stripe signature |

### 8.2 Validate Endpoint

**Request:**
```json
{
  "license_key": "PDF$PRO#A1B2-C3D4@E5F6-G7H8!I9J0",
  "platform": "wordpress"
}
```

**Response (200):**
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

### 8.3 Activate Endpoint

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

**Response (200):**
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

### 8.4 Check / Heartbeat Endpoint

**Request (sent every 24 hours by the plugin):**
```json
{
  "license_key": "PDF$PRO#A1B2-C3D4@E5F6-G7H8!I9J0",
  "site_url": "https://customer-site.com",
  "plugin_version": "1.3.0",
  "platform": "wordpress"
}
```

**Response (200):**
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

### 8.5 Rate Limiting (Already Implemented)

| Endpoint | Limit | Window | Identifier |
|----------|-------|--------|------------|
| All API | 60 requests | per minute | Client IP |
| Activate | 10 requests | per hour | License key |
| Check | 1,000 requests | per day | License key |

---

## 9. Phone-Home Integration (WordPress)

### 9.1 Current State

The WordPress Premium plugin (`class-pdf-embed-seo-premium.php`) currently validates license keys **locally only** by regex pattern matching. It does **not** communicate with the License Dashboard API.

**What exists:**
- License key input page (Settings → License)
- Local regex validation of key format
- License status stored in `wp_options` (`pdf_embed_seo_premium_license_status`)
- Grace period logic (14 days)
- Admin notices for expired/invalid/inactive keys

**What's missing:**
- No HTTP call to `pdfviewer.drossmedia.de/wp-json/plm/v1/license/validate`
- No activation call (site registration)
- No heartbeat/check calls
- No deactivation on plugin deactivate

### 9.2 Required Changes

#### 9.2.1 On License Key Save → Validate + Activate

Replace the local regex validation in `validate_license()` with a remote API call:

```php
private function validate_license( $license_key ) {
    if ( empty( $license_key ) ) {
        update_option( 'pdf_embed_seo_premium_license_status', 'inactive' );
        return;
    }

    // Remote validation against License Dashboard
    $response = wp_remote_post( 'https://pdfviewer.drossmedia.de/wp-json/plm/v1/license/validate', array(
        'timeout' => 15,
        'body'    => array(
            'license_key' => $license_key,
            'platform'    => 'wordpress',
        ),
    ) );

    if ( is_wp_error( $response ) ) {
        // Network error: keep current status, retry later
        return;
    }

    $data = json_decode( wp_remote_retrieve_body( $response ), true );

    if ( ! empty( $data['valid'] ) ) {
        update_option( 'pdf_embed_seo_premium_license_status', 'valid' );
        update_option( 'pdf_embed_seo_premium_license_expires', $data['expires_at'] ?? '' );
        update_option( 'pdf_embed_seo_premium_license_type', $data['type'] ?? 'premium' );
        update_option( 'pdf_embed_seo_premium_license_plan', $data['plan'] ?? 'starter' );

        // Activate on this site
        $this->activate_on_site( $license_key );
    } else {
        update_option( 'pdf_embed_seo_premium_license_status', $data['status'] ?? 'invalid' );
    }
}
```

#### 9.2.2 Activate on This Site

```php
private function activate_on_site( $license_key ) {
    wp_remote_post( 'https://pdfviewer.drossmedia.de/wp-json/plm/v1/license/activate', array(
        'timeout' => 15,
        'body'    => array(
            'license_key'    => $license_key,
            'site_url'       => home_url(),
            'platform'       => 'wordpress',
            'plugin_version' => PDF_EMBED_SEO_VERSION,
            'php_version'    => phpversion(),
            'cms_version'    => get_bloginfo( 'version' ),
        ),
    ) );
}
```

#### 9.2.3 Heartbeat (Daily Cron)

```php
// In premium init:
add_action( 'pdf_embed_seo_license_check', array( $this, 'heartbeat_check' ) );

if ( ! wp_next_scheduled( 'pdf_embed_seo_license_check' ) ) {
    wp_schedule_event( time(), 'daily', 'pdf_embed_seo_license_check' );
}

public function heartbeat_check() {
    $license_key = get_option( 'pdf_embed_seo_premium_license_key', '' );
    if ( empty( $license_key ) ) {
        return;
    }

    $response = wp_remote_post( 'https://pdfviewer.drossmedia.de/wp-json/plm/v1/license/check', array(
        'timeout' => 15,
        'body'    => array(
            'license_key'    => $license_key,
            'site_url'       => home_url(),
            'plugin_version' => PDF_EMBED_SEO_VERSION,
            'platform'       => 'wordpress',
        ),
    ) );

    if ( is_wp_error( $response ) ) {
        return; // Fail gracefully, retry next day
    }

    $data = json_decode( wp_remote_retrieve_body( $response ), true );

    if ( isset( $data['valid'] ) ) {
        $status = $data['valid'] ? 'valid' : ( $data['status'] ?? 'expired' );
        update_option( 'pdf_embed_seo_premium_license_status', $status );
        update_option( 'pdf_embed_seo_premium_license_expires', $data['expires_at'] ?? '' );

        if ( ! empty( $data['update_available'] ) && ! empty( $data['update_url'] ) ) {
            update_option( 'pdf_embed_seo_premium_update_available', $data['latest_version'] );
            update_option( 'pdf_embed_seo_premium_update_url', $data['update_url'] );
        }
    }
}
```

#### 9.2.4 Deactivate on Plugin Deactivation

```php
// In main plugin file:
register_deactivation_hook( __FILE__, 'pdf_embed_seo_premium_deactivate_license' );

function pdf_embed_seo_premium_deactivate_license() {
    $license_key = get_option( 'pdf_embed_seo_premium_license_key', '' );
    if ( empty( $license_key ) ) {
        return;
    }

    wp_remote_post( 'https://pdfviewer.drossmedia.de/wp-json/plm/v1/license/deactivate', array(
        'timeout' => 10,
        'body'    => array(
            'license_key' => $license_key,
            'site_url'    => home_url(),
        ),
    ) );
}
```

#### 9.2.5 Admin Notice for Update Available

```php
// Display notice when heartbeat detects a new version
$update_version = get_option( 'pdf_embed_seo_premium_update_available', '' );
if ( ! empty( $update_version ) && version_compare( $update_version, PDF_EMBED_SEO_VERSION, '>' ) ) {
    // Show "Update available" admin notice
}
```

### 9.3 Offline / Network Failure Handling

The phone-home system must be resilient:

| Scenario | Behavior |
|----------|----------|
| Network timeout on validate | Keep current status, show warning |
| Network timeout on heartbeat | Retry next day, features stay active |
| API returns 429 (rate limit) | Back off, retry next cycle |
| API returns 404 (key not found) | Set status to `invalid` |
| Dashboard server down | Features remain active based on last known status |
| First activation offline | Fall back to local regex check, activate on next heartbeat |

---

## 10. Phone-Home Integration (Drupal)

### 10.1 Current State

The Drupal Pro+ module has a `LicenseValidator` service (`src/Service/LicenseValidator.php`) that validates keys **locally by regex only**, similar to WordPress.

**What exists:**
- License key configuration in settings form
- Local regex validation for Pro+, Unlimited, and Dev key formats
- Expiration check from config
- License status stored in Drupal config (`pdf_embed_seo_pro_plus.settings`)

**What's missing:**
- No HTTP call to the License Dashboard API
- No site activation/deactivation
- No heartbeat cron job

### 10.2 Required Changes

#### 10.2.1 Remote Validation Service

Update `LicenseValidator::validate()` to call the remote API:

```php
public function validate(?string $license_key = NULL): string {
    if ($license_key === NULL) {
        $config = $this->configFactory->get('pdf_embed_seo_pro_plus.settings');
        $license_key = $config->get('license_key') ?? '';
    }

    if (empty($license_key)) {
        return 'inactive';
    }

    // Remote validation
    try {
        $response = $this->httpClient->post(
            'https://pdfviewer.drossmedia.de/wp-json/plm/v1/license/validate',
            [
                'form_params' => [
                    'license_key' => $license_key,
                    'platform'    => 'drupal',
                ],
                'timeout' => 15,
            ]
        );

        $data = json_decode($response->getBody(), TRUE);

        if (!empty($data['valid'])) {
            return 'valid';
        }
        return $data['status'] ?? 'invalid';
    }
    catch (\Exception $e) {
        // Network failure: fall back to local validation
        return $this->validateLocally($license_key);
    }
}
```

#### 10.2.2 Heartbeat via Cron

```php
// In pdf_embed_seo_pro_plus.module:
function pdf_embed_seo_pro_plus_cron() {
    $license_key = \Drupal::config('pdf_embed_seo_pro_plus.settings')->get('license_key');
    if (empty($license_key)) {
        return;
    }

    // Check every 24 hours (use state to track last check)
    $last_check = \Drupal::state()->get('pdf_embed_seo_pro_plus.last_license_check', 0);
    if (time() - $last_check < 86400) {
        return;
    }

    $client = \Drupal::httpClient();
    try {
        $response = $client->post('https://pdfviewer.drossmedia.de/wp-json/plm/v1/license/check', [
            'form_params' => [
                'license_key'    => $license_key,
                'site_url'       => \Drupal::request()->getSchemeAndHttpHost(),
                'plugin_version' => \Drupal::service('extension.list.module')
                    ->getExtensionInfo('pdf_embed_seo_pro_plus')['version'] ?? '1.3.0',
                'platform'       => 'drupal',
            ],
            'timeout' => 15,
        ]);

        $data = json_decode($response->getBody(), TRUE);
        \Drupal::state()->set('pdf_embed_seo_pro_plus.last_license_check', time());
        \Drupal::state()->set('pdf_embed_seo_pro_plus.license_valid', !empty($data['valid']));
    }
    catch (\Exception $e) {
        // Fail gracefully
    }
}
```

---

## 11. Phone-Home Integration (React/Next.js)

### 11.1 Current State

The React Pro+ package has a `validateProPlusLicense()` utility (`src/utils/license.ts`) that validates keys **locally by regex only**.

### 11.2 Required Changes

Add a server-side license validation hook:

```typescript
// packages/react-pro-plus/src/hooks/useLicense.ts
import { useState, useEffect, useCallback } from 'react';

interface LicenseStatus {
  valid: boolean;
  status: string;
  type: string;
  plan: string;
  expiresAt: string | null;
  daysRemaining: number | null;
}

export function useLicense(licenseKey: string) {
  const [license, setLicense] = useState<LicenseStatus | null>(null);
  const [loading, setLoading] = useState(true);

  const validate = useCallback(async () => {
    try {
      const res = await fetch(
        'https://pdfviewer.drossmedia.de/wp-json/plm/v1/license/validate',
        {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({
            license_key: licenseKey,
            platform: 'react',
          }),
        }
      );
      const data = await res.json();
      setLicense({
        valid: data.valid,
        status: data.status,
        type: data.type,
        plan: data.plan,
        expiresAt: data.expires_at,
        daysRemaining: data.days_remaining,
      });
    } catch {
      // Network error: keep last known state
    } finally {
      setLoading(false);
    }
  }, [licenseKey]);

  useEffect(() => {
    validate();
  }, [validate]);

  return { license, loading, revalidate: validate };
}
```

**Important:** For React/Next.js, the validation should be done **server-side** (in API routes or `getServerSideProps`) to avoid exposing the license key in client bundles.

```typescript
// Next.js API route: pages/api/validate-license.ts
import type { NextApiRequest, NextApiResponse } from 'next';

export default async function handler(req: NextApiRequest, res: NextApiResponse) {
  const licenseKey = process.env.PDF_LICENSE_KEY;

  const response = await fetch(
    'https://pdfviewer.drossmedia.de/wp-json/plm/v1/license/validate',
    {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({
        license_key: licenseKey,
        platform: 'react',
      }),
    }
  );

  const data = await response.json();
  res.json({ valid: data.valid, type: data.type, plan: data.plan });
}
```

---

## 12. License Dashboard Admin

### 12.1 Already Implemented

The License Dashboard plugin provides a full admin UI at `pdfviewer.drossmedia.de/wp-admin/`:

| Page | URL | Features |
|------|-----|----------|
| Dashboard | `?page=plm-dashboard` | KPIs, quick actions |
| Licenses | `?page=plm-licenses` | List, filter, create, view details |
| License Detail | `?action=view&license_id=X` | Full details, installations, extend/revoke |
| Installations | `?page=plm-installations` | All installations with geo data |
| Statistics | `?page=plm-stats` | Geo distribution, platform stats |
| Audit Log | `?page=plm-audit-log` | All events, paginated |
| Settings | `?page=plm-settings` | Stripe product mapping, system info |

### 12.2 Dashboard Widget (v1.0.1)

A WordPress Dashboard widget shows quick KPIs:
- Active licenses count
- Active installations count
- Expiring within 30 days
- Quick links to all admin pages

---

## 13. Email Notifications

### 13.1 Current Status: TODO

The `PLM_Stripe::handle_checkout_completed()` method has a `// TODO: Send license key email` comment. This needs to be implemented.

### 13.2 Required Email Templates

| Event | Email Content |
|-------|--------------|
| **Purchase Complete** | Welcome, license key, activation instructions per platform, support link |
| **Renewal Success** | Confirmation, new expiry date |
| **Expiring Soon** (14 days) | Reminder, renewal link |
| **License Expired** | Notification, renewal link, grace period info |
| **Payment Failed** | Alert, update payment method link |

### 13.3 Implementation

```php
// In PLM_Stripe::handle_checkout_completed(), replace the TODO:
$subject = sprintf(
    __( 'Your %s License Key - PDF Embed & SEO Optimize', 'pdf-license-manager' ),
    ucfirst( $mapping->license_type )
);

$message = sprintf(
    "Thank you for purchasing PDF Embed & SEO Optimize %s!\n\n" .
    "Your license key: %s\n\n" .
    "Plan: %s\n" .
    "Sites: %s\n" .
    "Valid until: %s\n\n" .
    "Activation instructions:\n" .
    "WordPress: Settings → PDF Embed SEO → License → Paste key\n" .
    "Drupal: Configuration → PDF Embed SEO → License key\n" .
    "React: Set PDF_LICENSE_KEY in your .env file\n\n" .
    "Documentation: https://pdfviewer.drossmedia.de/documentation/\n" .
    "Support: https://pdfviewer.drossmedia.de/support/\n",
    ucfirst( $mapping->license_type ),
    $license_key,
    ucfirst( $mapping->plan ),
    0 === (int) $mapping->site_limit ? 'Unlimited' : $mapping->site_limit,
    $expires_at ? date( 'F j, Y', strtotime( $expires_at ) ) : 'Lifetime'
);

wp_mail( $customer_email, $subject, $message );
```

---

## 14. Security Considerations

### 14.1 Already Implemented

| Measure | Implementation |
|---------|---------------|
| Webhook signature | HMAC-SHA256 with `hash_equals()` |
| Timestamp tolerance | 5 minutes max |
| Idempotency | Unique `stripe_event_id` |
| SQL injection | `$wpdb->prepare()` everywhere |
| Input sanitization | `sanitize_text_field()`, `esc_url_raw()` |
| Rate limiting | 60/min API, 10/hr activate, 1000/day check |
| IP anonymization | Last octet zeroed (GDPR) |
| Key generation | `random_bytes()` (CSPRNG) |
| Admin access | `manage_options` capability required |

### 14.2 Additional Recommendations

| Area | Recommendation |
|------|---------------|
| **HTTPS enforcement** | Reject non-HTTPS requests to the API |
| **CORS headers** | Restrict API access to known origins |
| **License key in transit** | Always over HTTPS, never logged in plaintext |
| **Stripe secret key** | Only in `wp-config.php`, never in DB or version control |
| **Checkout session** | Always create server-side, never expose secret key to frontend |
| **Webhook IP allowlist** | Optional: Only accept webhooks from Stripe IPs |

---

## 15. Implementation Roadmap

### Phase 1: Email Delivery (Priority: MUST)

| Task | Effort | Status |
|------|--------|--------|
| Implement `wp_mail()` in Stripe webhook handler | 2h | TODO |
| Create email templates (purchase, renewal, expiring) | 4h | TODO |
| Test email delivery with Stripe test webhooks | 2h | TODO |

### Phase 2: Pricing Page + Stripe Checkout (Priority: MUST)

| Task | Effort | Status |
|------|--------|--------|
| Install Stripe PHP SDK or implement Checkout API with `wp_remote_post` | 2h | TODO |
| Create Checkout Session endpoint (AJAX or REST) | 4h | TODO |
| Build/update pricing page with plan cards and buy buttons | 4h | TODO |
| Create thank-you page | 2h | TODO |
| Configure Stripe products and product mapping | 1h | TODO |
| Test full purchase flow (test mode) | 4h | TODO |

### Phase 3: Phone-Home (WordPress) (Priority: MUST)

| Task | Effort | Status |
|------|--------|--------|
| Replace local regex validation with remote API call | 4h | TODO |
| Add site activation on license save | 2h | TODO |
| Add daily heartbeat cron job | 3h | TODO |
| Add deactivation on plugin deactivate | 1h | TODO |
| Offline/network failure handling | 2h | TODO |
| Test full lifecycle (activate → heartbeat → expire → renew) | 4h | TODO |

### Phase 4: Phone-Home (Drupal) (Priority: MUST)

| Task | Effort | Status |
|------|--------|--------|
| Update `LicenseValidator` to call remote API | 4h | TODO |
| Add activation on settings save | 2h | TODO |
| Add heartbeat via Drupal cron | 3h | TODO |
| Add deactivation on module uninstall | 1h | TODO |
| Test full lifecycle | 4h | TODO |

### Phase 5: Phone-Home (React/Next.js) (Priority: SHOULD)

| Task | Effort | Status |
|------|--------|--------|
| Create `useLicense` hook with remote validation | 3h | TODO |
| Create Next.js API route for server-side validation | 2h | TODO |
| Document environment variable setup | 1h | TODO |
| Test with demo app | 2h | TODO |

### Phase 6: Refund Handling + Extras (Priority: SHOULD)

| Task | Effort | Status |
|------|--------|--------|
| Handle `charge.refunded` webhook → revoke license | 2h | TODO |
| CSV export for licenses and installations | 4h | TODO |
| Interactive geo map on stats page | 8h | TODO |
| GeoLite2 auto-update cron | 2h | TODO |
| Health check alerting (Uptime-Kuma) | 2h | TODO |

---

## 16. Current Status & Gap Analysis

### 16.1 What's Already Built

| Component | Status | Location |
|-----------|--------|----------|
| License Dashboard plugin (v1.0.1) | **Done** | `license-dashboard/` |
| Database schema (6 tables) | **Done** | `class-plm-database.php` |
| License key generation | **Done** | `class-plm-license.php` |
| REST API (5 endpoints) | **Done** | `class-plm-api.php` |
| Stripe webhook handler | **Done** | `class-plm-stripe.php` |
| Signature verification | **Done** | `class-plm-stripe.php` |
| Rate limiting | **Done** | `class-plm-api.php` |
| GeoIP integration | **Done** | `class-plm-geoip.php` |
| Admin dashboard (7 pages) | **Done** | `admin/views/` |
| Dashboard widget | **Done** | `class-plm-dashboard-widget.php` |
| WP Premium local license check | **Done** | `class-pdf-embed-seo-premium.php` |
| WP Pro+ local license check | **Done** | `class-pdf-embed-seo-pro-plus.php` |
| Drupal Pro+ local license check | **Done** | `LicenseValidator.php` |
| React Pro+ local license check | **Done** | `utils/license.ts` |

### 16.2 What's Missing (Gaps)

| Gap | Priority | Effort |
|-----|----------|--------|
| **Email delivery after purchase** | MUST | 8h |
| **Stripe Checkout on pricing page** | MUST | 12h |
| **WP Premium phone-home to API** | MUST | 16h |
| **WP Pro+ phone-home to API** | MUST | 16h |
| **Drupal Premium phone-home to API** | MUST | 14h |
| **Drupal Pro+ phone-home to API** | MUST | 14h |
| **React phone-home to API** | SHOULD | 8h |
| **Refund webhook handling** | SHOULD | 2h |
| **Stripe SDK or manual Checkout API** | MUST | 4h |
| **Thank-you page** | MUST | 2h |
| **Admin disclosure notice** | MUST | 1h |

### 16.3 Admin Disclosure Notice

WordPress.org plugin guidelines require disclosure when a plugin communicates with external servers. Add to the plugin description and settings page:

> "This plugin communicates with pdfviewer.drossmedia.de for license validation. Your site URL, plugin version, and license key are transmitted. [Learn more](https://pdfviewer.drossmedia.de/privacy/)"

---

## Database Schema Reference

### Existing Tables (License Dashboard)

| Table | Purpose | Rows (expected) |
|-------|---------|-----------------|
| `wp_plm_licenses` | License keys, status, expiry | Up to 10,000 |
| `wp_plm_installations` | Active site registrations | Up to 50,000 |
| `wp_plm_geo_data` | GeoIP data per installation | 1:1 with installations |
| `wp_plm_audit_logs` | All license events | Grows over time |
| `wp_plm_stripe_events` | Incoming Stripe webhooks | Grows over time |
| `wp_plm_stripe_product_map` | Product → license mapping | ~10 rows |

---

## License

GPL v2 or later - https://www.gnu.org/licenses/gpl-2.0.html

Made with care by [Dross:Media](https://dross.net/media/)
