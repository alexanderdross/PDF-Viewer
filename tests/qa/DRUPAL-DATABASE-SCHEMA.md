# Drupal PDF Embed SEO - Complete Database Schema Reference

**Date:** 2026-03-16
**Version:** Free 1.2.17, Premium 1.2.17, Pro+ 1.3.2

---

## Table Overview

| # | Table Name | Owner | Purpose | Rows (Typical) |
|---|---|---|---|---|
| 1 | `pdf_embed_seo_analytics` | Free | View/event tracking | High volume |
| 2 | `pdf_embed_seo_progress` | Premium | Reading progress | 1 per user/doc |
| 3 | `pdf_embed_seo_access_tokens` | Premium | Expiring links | Low |
| 4 | `pdf_embed_seo_rate_limit` | Premium | Brute force protection | Low |
| 5 | `pdf_embed_seo_versions` | Pro+ | Document version history | Medium |
| 6 | `pdf_embed_seo_annotations` | Pro+ | User annotations | Medium |
| 7 | `pdf_embed_seo_audit_log` | Pro+ | Compliance audit trail | High volume |
| 8 | `pdf_embed_seo_webhooks` | Pro+ | Webhook configurations | Low |
| 9 | `pdf_embed_seo_webhook_deliveries` | Pro+ | Webhook delivery log | High volume |
| 10 | `pdf_embed_seo_consents` | Pro+ | GDPR consent records | Medium |
| 11 | `pdf_embed_seo_heatmaps` | Pro+ | Page interaction data | Very high volume |
| 12 | `pdf_embed_seo_2fa_tokens` | Pro+ | 2FA verification tokens | Low (ephemeral) |
| 13 | `pdf_embed_seo_2fa_secrets` | Pro+ | TOTP secrets | 1 per user |

---

## 1. pdf_embed_seo_analytics

**Owner:** Free module (`pdf_embed_seo.install`)
**Extended by:** Premium (adds `time_spent`, `pages_viewed`, `created`), Pro+ (adds `event_type`, `session_id`, `country`, `device_type`)

| Column | Type | Null | Default | Added By | Description |
|---|---|---|---|---|---|
| `id` | serial unsigned | NO | auto | Free | Primary key |
| `pdf_document_id` | int unsigned | NO | 0 | Free | PDF entity ID |
| `user_id` | int unsigned | YES | NULL | Free | Drupal user ID (0 for anonymous) |
| `ip_address` | varchar(45) | YES | NULL | Free | Client IP (may be anonymized) |
| `user_agent` | varchar(512) | YES | NULL | Free | Browser user agent |
| `referrer` | varchar(512) | YES | NULL | Free | HTTP referrer |
| `timestamp` | int unsigned | NO | 0 | Free | Unix timestamp (**integer**) |
| `time_spent` | int unsigned | NO | 0 | Premium | Seconds spent viewing |
| `pages_viewed` | int unsigned | NO | 1 | Premium | Pages viewed in session |
| `created` | int unsigned | NO | 0 | Premium | Unix timestamp of creation |
| `event_type` | varchar(32) | NO | 'view' | Pro+ | Event: view, download, print |
| `session_id` | varchar(64) | YES | NULL | Pro+ | Visitor session ID |
| `country` | varchar(2) | YES | NULL | Pro+ | ISO 3166-1 alpha-2 code |
| `device_type` | varchar(20) | YES | NULL | Pro+ | desktop, mobile, tablet |

**Primary key:** `id`
**Indexes:** `pdf_document_id`, `timestamp`, `created` (Premium)

**IMPORTANT:** The `timestamp` column is an **integer** (Unix timestamp). Use `FROM_UNIXTIME(timestamp)` for date formatting in SQL.

---

## 2. pdf_embed_seo_progress

**Owner:** Premium module

| Column | Type | Null | Default | Description |
|---|---|---|---|---|
| `id` | serial unsigned | NO | auto | Primary key |
| `pdf_document_id` | int unsigned | NO | 0 | PDF entity ID |
| `user_id` | int unsigned | NO | 0 | Drupal user ID |
| `session_id` | varchar(128) | NO | '' | Session ID (anonymous) |
| `current_page` | int unsigned | NO | 1 | Current page number |
| `scroll_position` | float | NO | 0 | Scroll position on page |
| `zoom_level` | float | NO | 1 | Current zoom level |
| `updated` | int unsigned | NO | 0 | Unix timestamp |

**Primary key:** `id`
**Unique keys:** `user_document` (`pdf_document_id`, `user_id`, `session_id`)
**Indexes:** `pdf_document_id`, `user_id`

---

## 3. pdf_embed_seo_access_tokens

**Owner:** Premium module

| Column | Type | Null | Default | Description |
|---|---|---|---|---|
| `id` | serial unsigned | NO | auto | Primary key |
| `token` | varchar(64) | NO | - | Access token string |
| `pdf_document_id` | int unsigned | NO | - | PDF entity ID |
| `created_by` | int unsigned | NO | 0 | Creator user ID |
| `expires` | int unsigned | NO | - | Expiry Unix timestamp |
| `max_uses` | int unsigned | NO | 0 | Max uses (0=unlimited) |
| `use_count` | int unsigned | NO | 0 | Current use count |
| `created` | int unsigned | NO | - | Creation Unix timestamp |

**Primary key:** `id`
**Unique keys:** `token`
**Indexes:** `pdf_document_id`, `expires`

---

## 4. pdf_embed_seo_rate_limit

**Owner:** Premium module

| Column | Type | Null | Default | Description |
|---|---|---|---|---|
| `id` | serial unsigned | NO | auto | Primary key |
| `identifier` | varchar(128) | NO | - | Unique ID (IP+action+target) |
| `action` | varchar(64) | NO | - | Rate-limited action |
| `target_id` | int unsigned | NO | 0 | Target entity ID |
| `ip_address` | varchar(45) | NO | - | Client IP |
| `attempts` | int unsigned | NO | 0 | Attempt count |
| `window_start` | int unsigned | NO | - | Window start timestamp |
| `blocked_until` | int unsigned | NO | 0 | Block expiry timestamp |

**Primary key:** `id`
**Unique keys:** `identifier`
**Indexes:** `action`, `ip_address`, `blocked_until`

---

## 5. pdf_embed_seo_versions

**Owner:** Pro+ module

| Column | Type | Null | Default | Description |
|---|---|---|---|---|
| `id` | serial unsigned | NO | auto | Primary key |
| `document_id` | int unsigned | NO | - | PDF entity ID |
| `version_number` | varchar(20) | NO | - | Semver-like version string |
| `file_id` | int unsigned | YES | NULL | Drupal file entity ID |
| `file_url` | varchar(512) | YES | NULL | File URL |
| `file_size` | int unsigned | NO | 0 | File size in bytes |
| `change_notes` | text medium | YES | NULL | Version changelog |
| `created_by` | int unsigned | YES | NULL | Creator user ID |
| `is_current` | tinyint unsigned | NO | 0 | 1 if current version |
| `created_at` | varchar(20) | NO | - | `Y-m-d H:i:s` datetime |

**Primary key:** `id`
**Indexes:** `document_id`, `is_current` (`document_id`, `is_current`)

---

## 6. pdf_embed_seo_annotations

**Owner:** Pro+ module

| Column | Type | Null | Default | Description |
|---|---|---|---|---|
| `id` | serial unsigned | NO | auto | Primary key |
| `uuid` | varchar(36) | NO | - | UUID v4 identifier |
| `document_id` | int unsigned | NO | - | PDF entity ID |
| `user_id` | int unsigned | NO | - | Creator user ID |
| `page_number` | int unsigned | NO | - | Page number |
| `annotation_type` | varchar(32) | NO | - | highlight, note, bookmark, etc. |
| `content` | text medium | YES | NULL | Annotation text content |
| `position_x` | float | YES | 0 | X position (0-1 normalized) |
| `position_y` | float | YES | 0 | Y position (0-1 normalized) |
| `width` | float | YES | NULL | Width (0-1 normalized) |
| `height` | float | YES | NULL | Height (0-1 normalized) |
| `color` | varchar(20) | YES | '#ffff00' | Annotation color |
| `metadata` | text medium | YES | NULL | JSON extra data |
| `created_at` | varchar(20) | NO | - | `Y-m-d H:i:s` datetime |
| `updated_at` | varchar(20) | NO | - | `Y-m-d H:i:s` datetime |

**Primary key:** `id`
**Unique keys:** `uuid`
**Indexes:** `document_id`, `user_id`

---

## 7. pdf_embed_seo_audit_log

**Owner:** Pro+ module

| Column | Type | Null | Default | Description |
|---|---|---|---|---|
| `id` | serial unsigned | NO | auto | Primary key |
| `document_id` | int unsigned | YES | NULL | Related PDF entity ID |
| `user_id` | int unsigned | YES | NULL | Acting user ID |
| `action` | varchar(64) | NO | - | Action performed |
| `details` | text medium | YES | NULL | JSON details |
| `ip_address` | varchar(45) | YES | NULL | Client IP |
| `user_agent` | varchar(512) | YES | NULL | Browser user agent |
| `created_at` | varchar(20) | NO | - | `Y-m-d H:i:s` datetime |

**Primary key:** `id`
**Indexes:** `created_at`, `user_id`, `action`, `document_id`

---

## 8. pdf_embed_seo_webhooks

**Owner:** Pro+ module

| Column | Type | Null | Default | Description |
|---|---|---|---|---|
| `id` | serial unsigned | NO | auto | Primary key |
| `name` | varchar(255) | NO | - | Webhook display name |
| `url` | varchar(512) | NO | - | Target URL |
| `secret` | varchar(255) | YES | NULL | HMAC signing secret |
| `events` | text medium | YES | NULL | JSON array of events |
| `is_active` | tinyint unsigned | NO | 1 | Active flag |
| `failure_count` | int unsigned | NO | 0 | Consecutive failures |
| `last_triggered` | varchar(20) | YES | NULL | Last dispatch time |
| `last_status` | varchar(20) | YES | NULL | Last delivery status |
| `created_at` | varchar(20) | NO | - | `Y-m-d H:i:s` datetime |
| `updated_at` | varchar(20) | YES | NULL | `Y-m-d H:i:s` datetime |

**Primary key:** `id`

---

## 9. pdf_embed_seo_webhook_deliveries

**Owner:** Pro+ module

| Column | Type | Null | Default | Description |
|---|---|---|---|---|
| `id` | serial unsigned | NO | auto | Primary key |
| `webhook_id` | int unsigned | NO | - | Parent webhook ID |
| `event` | varchar(64) | NO | - | Event type |
| `payload` | text medium | YES | NULL | JSON payload sent |
| `response_code` | int | YES | NULL | HTTP response code |
| `response_body` | text medium | YES | NULL | Response body (max 1000 chars) |
| `duration_ms` | int unsigned | YES | NULL | Request duration in ms |
| `status` | varchar(20) | NO | 'pending' | pending, success, failed |
| `created_at` | varchar(20) | NO | - | `Y-m-d H:i:s` datetime |

**Primary key:** `id`
**Indexes:** `webhook_id`, `status`

---

## 10. pdf_embed_seo_consents

**Owner:** Pro+ module

| Column | Type | Null | Default | Description |
|---|---|---|---|---|
| `id` | serial unsigned | NO | auto | Primary key |
| `user_id` | int unsigned | YES | NULL | Drupal user ID |
| `session_id` | varchar(64) | YES | NULL | Session ID (anonymous) |
| `consent_type` | varchar(32) | NO | - | analytics, functional, marketing |
| `consented` | tinyint unsigned | NO | - | 1=yes, 0=no |
| `ip_address` | varchar(45) | YES | NULL | Client IP (may be anonymized) |
| `user_agent` | varchar(512) | YES | NULL | Browser user agent |
| `consent_text` | text medium | YES | NULL | Text shown to user |
| `created_at` | varchar(20) | NO | - | `Y-m-d H:i:s` datetime |
| `withdrawn_at` | varchar(20) | YES | NULL | When consent withdrawn |

**Primary key:** `id`
**Indexes:** `user_id`, `session_id`, `consent_type`

---

## 11. pdf_embed_seo_heatmaps

**Owner:** Pro+ module

| Column | Type | Null | Default | Description |
|---|---|---|---|---|
| `id` | serial unsigned | NO | auto | Primary key |
| `document_id` | int unsigned | NO | - | PDF entity ID |
| `page_number` | int unsigned | NO | - | Page number |
| `x_position` | float | NO | - | X position (0-1) |
| `y_position` | float | NO | - | Y position (0-1) |
| `interaction_type` | varchar(20) | NO | 'view' | view, click, scroll |
| `duration_ms` | int unsigned | NO | 0 | Interaction duration ms |
| `session_id` | varchar(64) | YES | NULL | Visitor session ID |
| `created_at` | varchar(20) | NO | - | `Y-m-d H:i:s` datetime |

**Primary key:** `id`
**Indexes:** `document_id`, `page_number` (`document_id`, `page_number`), `created_at`

**Note:** This is a high-volume table. Data retention should be configured (default: 90 days).

---

## 12. pdf_embed_seo_2fa_tokens

**Owner:** Pro+ module

| Column | Type | Null | Default | Description |
|---|---|---|---|---|
| `id` | serial unsigned | NO | auto | Primary key |
| `user_id` | int unsigned | NO | - | User ID |
| `token` | varchar(255) | NO | - | Hashed token |
| `method` | varchar(20) | NO | 'email' | email or totp |
| `expires_at` | varchar(20) | NO | - | Expiry datetime |
| `used_at` | varchar(20) | YES | NULL | When token was used |
| `created_at` | varchar(20) | NO | - | Creation datetime |

**Primary key:** `id`
**Indexes:** `user_id`, `expires_at`

---

## 13. pdf_embed_seo_2fa_secrets

**Owner:** Pro+ module

| Column | Type | Null | Default | Description |
|---|---|---|---|---|
| `id` | serial unsigned | NO | auto | Primary key |
| `user_id` | int unsigned | NO | - | User ID |
| `secret` | varchar(64) | NO | - | TOTP secret key |
| `updated_at` | varchar(20) | YES | NULL | Last update datetime |

**Primary key:** `id`
**Unique keys:** `user_id`

---

## Data Retention Defaults

| Table | Default Retention | Config Key |
|---|---|---|
| `pdf_embed_seo_analytics` | 365 days | `data_retention_days` |
| `pdf_embed_seo_audit_log` | 730 days | `audit_log_retention` |
| `pdf_embed_seo_consents` | 1825 days (5 years) | `consent_retention` |
| `pdf_embed_seo_heatmaps` | 90 days | `heatmap_retention` |
| `pdf_embed_seo_2fa_tokens` | Auto-expire | `expires_at` column |

Retention is enforced by `ComplianceManager::applyRetentionPolicy()`, which should be called via cron.
