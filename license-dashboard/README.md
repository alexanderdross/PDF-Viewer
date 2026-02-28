# PDF License Manager (PLM)

**Zentrales Lizenz-Management-Dashboard** f&uuml;r **PDF Embed & SEO Optimize** (WordPress, Drupal, React/Next.js).

| Eigenschaft | Wert |
|-------------|------|
| **Plugin-Slug** | `pdf-license-manager` |
| **Version** | 1.0.0 |
| **Plattform** | WordPress 6.0+ / PHP 8.0+ / MySQL 8.0+ |
| **Domain** | `dashboard.pdfviewer.drossmedia.de` |
| **Autor** | Dross:Media |
| **Lizenz** | GPL v2 or later |

---

## Inhaltsverzeichnis

1. [&Uuml;bersicht](#&uuml;bersicht)
2. [Architektur](#architektur)
3. [Installation & Setup](#installation--setup)
4. [Konfiguration](#konfiguration)
5. [Datenbank-Schema](#datenbank-schema)
6. [REST API Endpunkte](#rest-api-endpunkte)
7. [Admin Dashboard](#admin-dashboard)
8. [Stripe Integration](#stripe-integration)
9. [GeoIP Integration](#geoip-integration)
10. [Lizenzschl&uuml;ssel-Formate](#lizenzschl&uuml;ssel-formate)
11. [Sicherheit & DSGVO](#sicherheit--dsgvo)
12. [Dateistruktur](#dateistruktur)
13. [Anforderungen (Lastenheft-Referenz)](#anforderungen-lastenheft-referenz)

---

## &Uuml;bersicht

Das PDF License Manager Plugin ist ein eigenst&auml;ndiges WordPress-Plugin, das auf einer separaten WordPress-Installation (`dashboard.pdfviewer.drossmedia.de`) betrieben wird. Es stellt eine REST API bereit, die von den PDF Embed & SEO Optimize Plugins/Modulen (WordPress, Drupal, React/Next.js) angesprochen wird, um Lizenzschl&uuml;ssel zu validieren, zu aktivieren und zu &uuml;berpr&uuml;fen.

### Kernfunktionen

- **Lizenz-Verwaltung**: Erstellen, Anzeigen, Verl&auml;ngern, Sperren von Lizenzschl&uuml;sseln
- **&Ouml;ffentliche REST API**: Validate, Activate, Deactivate, Check (Heartbeat), Health
- **Installations-Tracking**: Plattform, Domain, Plugin-Version, Aktivierungsdatum
- **Geo-IP**: MaxMind GeoLite2 f&uuml;r Standort-Erkennung der Installationen
- **Stripe-Webhooks**: Automatische Lizenzerstellung bei Kauf (kein WooCommerce)
- **Audit-Log**: L&uuml;ckenlose Protokollierung aller Aktionen
- **Statistiken**: Plattform-Verteilung, Geo-Verteilung, Versions-Tracking

---

## Architektur

```
┌──────────────────────────────────────────────────────────────┐
│           WordPress-Plugins / Drupal-Module / React          │
│           (PDF Embed & SEO Optimize)                         │
└──────────────────────┬───────────────────────────────────────┘
                       │ HTTPS (Phone-Home / Heartbeat)
                       ▼
┌──────────────────────────────────────────────────────────────┐
│  WordPress + pdf-license-manager Plugin                      │
│  dashboard.pdfviewer.drossmedia.de                           │
├──────────────────────────────────────────────────────────────┤
│                                                              │
│  REST API (WP REST)         Admin UI (WP-Admin)              │
│  ├─ POST /license/validate  ├─ Dashboard (KPIs)              │
│  ├─ POST /license/activate  ├─ Lizenz-Verwaltung             │
│  ├─ POST /license/deactivate├─ Installations-&Uuml;bersicht         │
│  ├─ POST /license/check     ├─ Statistiken                   │
│  ├─ POST /webhook/stripe    ├─ Audit-Log                     │
│  └─ GET  /health            └─ Einstellungen                 │
│                                                              │
│  PHP-Klassen               Custom DB-Tabellen                │
│  ├─ PLM_License            ├─ wp_plm_licenses                │
│  ├─ PLM_API                ├─ wp_plm_installations            │
│  ├─ PLM_Stripe             ├─ wp_plm_geo_data                │
│  ├─ PLM_GeoIP              ├─ wp_plm_audit_logs              │
│  ├─ PLM_Admin              ├─ wp_plm_stripe_events           │
│  └─ PLM_Database           └─ wp_plm_stripe_product_map     │
│                                                              │
├──────────────────────────────────────────────────────────────┤
│  MySQL / MariaDB            MaxMind GeoLite2 (lokal)         │
└──────────────────────────────────────────────────────────────┘
```

### Hosting-Struktur

| Komponente | Domain | Beschreibung |
|------------|--------|-------------|
| Online-Shop | `pdfviewer.drossmedia.de` | WordPress + Stripe (bestehend) |
| License Dashboard | `dashboard.pdfviewer.drossmedia.de` | Separate WordPress-Installation |
| API Base URL | `dashboard.pdfviewer.drossmedia.de/wp-json/plm/v1/` | &Ouml;ffentliche REST API |

---

## Installation & Setup

### Voraussetzungen

- WordPress 6.0+
- PHP 8.0+
- MySQL 8.0 oder MariaDB 10.6+
- SSL-Zertifikat (Let's Encrypt reicht aus)
- Composer (f&uuml;r MaxMind GeoLite2 Reader)

### Schritt 1: WordPress installieren

Frische WordPress-Installation auf `dashboard.pdfviewer.drossmedia.de` aufsetzen.

### Schritt 2: Plugin hochladen

```bash
# Ordner license-dashboard/ als Plugin in WordPress kopieren:
cp -r license-dashboard/ /path/to/wordpress/wp-content/plugins/pdf-license-manager/
```

### Schritt 3: MaxMind GeoLite2 einrichten

```bash
# Composer-Paket installieren (im Plugin-Verzeichnis):
cd /path/to/wordpress/wp-content/plugins/pdf-license-manager/
composer require maxmind-db/reader

# GeoLite2-City.mmdb herunterladen (MaxMind Account erforderlich):
mkdir -p /path/to/wordpress/wp-content/uploads/plm/
# Datei ablegen unter: wp-content/uploads/plm/GeoLite2-City.mmdb
```

### Schritt 4: Plugin aktivieren

Im WordPress-Admin unter **Plugins** das Plugin **PDF License Manager** aktivieren. Die Datenbanktabellen werden automatisch via `dbDelta()` erstellt.

### Schritt 5: Stripe konfigurieren

In `wp-config.php` den Stripe Webhook Secret hinterlegen:

```php
define( 'PLM_STRIPE_WEBHOOK_SECRET', 'whsec_...' );
```

---

## Konfiguration

### wp-config.php Konstanten

| Konstante | Beschreibung | Beispiel |
|-----------|-------------|---------|
| `PLM_STRIPE_WEBHOOK_SECRET` | Stripe Webhook Signing Secret | `'whsec_abc123...'` |
| `PLM_GEOIP_DB_PATH` | Alternativer Pfad zur GeoLite2-Datenbank | `'/opt/geoip/GeoLite2-City.mmdb'` |

### Plugin-Konstanten (pdf-license-manager.php)

| Konstante | Standardwert | Beschreibung |
|-----------|-------------|-------------|
| `PLM_VERSION` | `'1.0.0'` | Plugin-Version |
| `PLM_GRACE_PERIOD_DAYS` | `14` | Tage nach Ablauf, in denen Features aktiv bleiben |
| `PLM_LATEST_PLUGIN_VERSION` | `'1.3.0'` | Aktuelle Version der PDF-Plugins (f&uuml;r Update-Checks) |

---

## Datenbank-Schema

Das Plugin erstellt 6 Custom Tables mit dem WordPress-Prefix (`wp_plm_*`):

### wp_plm_licenses

| Spalte | Typ | Beschreibung |
|--------|-----|-------------|
| `id` | BIGINT UNSIGNED PK | Auto-Increment ID |
| `license_key` | VARCHAR(100) UNIQUE | Lizenzschl&uuml;ssel |
| `license_type` | ENUM('premium','pro_plus') | Lizenz-Typ |
| `plan` | ENUM('starter','professional','agency','enterprise','lifetime','dev') | Plan |
| `status` | ENUM('active','expired','grace_period','revoked','inactive') | Status |
| `site_limit` | INT UNSIGNED | Max. Anzahl Produktions-Domains (0 = unbegrenzt) |
| `customer_email` | VARCHAR(255) | Kunden-E-Mail |
| `customer_name` | VARCHAR(255) | Kundenname |
| `stripe_customer_id` | VARCHAR(255) | Stripe Customer ID |
| `stripe_subscription_id` | VARCHAR(255) | Stripe Subscription ID |
| `expires_at` | DATETIME | Ablaufdatum (NULL = Lifetime) |
| `activated_at` | DATETIME | Erstaktivierung |
| `created_at` | DATETIME | Erstelldatum |
| `updated_at` | DATETIME | Letzte &Auml;nderung |
| `notes` | TEXT | Interne Notizen |

### wp_plm_installations

| Spalte | Typ | Beschreibung |
|--------|-----|-------------|
| `id` | BIGINT UNSIGNED PK | Auto-Increment ID |
| `license_id` | BIGINT UNSIGNED FK | Referenz auf Lizenz |
| `site_url` | VARCHAR(500) | Normalisierte Domain |
| `platform` | ENUM('wordpress','drupal','react') | Plattform |
| `plugin_version` | VARCHAR(20) | Plugin-Version |
| `php_version` | VARCHAR(20) | PHP-Version (WP/Drupal) |
| `cms_version` | VARCHAR(20) | CMS-Version (WP/Drupal) |
| `node_version` | VARCHAR(20) | Node.js-Version (React) |
| `activated_at` | DATETIME | Aktivierungsdatum |
| `last_checked_at` | DATETIME | Letzter Heartbeat |
| `deactivated_at` | DATETIME | Deaktivierungsdatum |
| `is_active` | TINYINT(1) | Aktiv-Flag |
| `is_local` | TINYINT(1) | Lokale/Staging-Domain |

**Unique Constraint:** `(license_id, site_url)` &mdash; verhindert doppelte Aktivierungen.

### wp_plm_geo_data

| Spalte | Typ | Beschreibung |
|--------|-----|-------------|
| `id` | BIGINT UNSIGNED PK | Auto-Increment ID |
| `installation_id` | BIGINT UNSIGNED UNIQUE FK | 1:1-Beziehung zu Installation |
| `ip_anonymized` | VARCHAR(45) | Anonymisierte IP (letztes Oktett = 0) |
| `country_code` | CHAR(2) | ISO 3166-1 Alpha-2 L&auml;ndercode |
| `country_name` | VARCHAR(100) | Klartext-L&auml;ndername |
| `region` | VARCHAR(100) | Bundesland/Region |
| `city` | VARCHAR(100) | Stadt |
| `latitude` | DECIMAL(10,7) | Breitengrad |
| `longitude` | DECIMAL(10,7) | L&auml;ngengrad |
| `continent` | VARCHAR(50) | Kontinent |
| `timezone` | VARCHAR(50) | Zeitzone |
| `updated_at` | DATETIME | Letzte Aktualisierung |

### wp_plm_audit_logs

| Spalte | Typ | Beschreibung |
|--------|-----|-------------|
| `id` | BIGINT UNSIGNED PK | Auto-Increment ID |
| `license_id` | BIGINT UNSIGNED | Betroffene Lizenz (nullable) |
| `event_type` | VARCHAR(50) | Event-Typ (z.B. `license.activated`) |
| `details` | LONGTEXT | JSON-Details |
| `ip_address` | VARCHAR(45) | Anonymisierte IP des Ausl&ouml;sers |
| `user_agent` | VARCHAR(500) | User-Agent |
| `created_at` | DATETIME | Zeitstempel |

### wp_plm_stripe_events

| Spalte | Typ | Beschreibung |
|--------|-----|-------------|
| `id` | BIGINT UNSIGNED PK | Auto-Increment ID |
| `stripe_event_id` | VARCHAR(100) UNIQUE | Stripe Event ID (Idempotenz) |
| `event_type` | VARCHAR(100) | z.B. `checkout.session.completed` |
| `license_id` | BIGINT UNSIGNED | Verkn&uuml;pfte Lizenz |
| `payload` | LONGTEXT | Voller Webhook-Payload (JSON) |
| `processed` | TINYINT(1) | Erfolgreich verarbeitet |
| `created_at` | DATETIME | Empfangszeitpunkt |

### wp_plm_stripe_product_map

| Spalte | Typ | Beschreibung |
|--------|-----|-------------|
| `id` | BIGINT UNSIGNED PK | Auto-Increment ID |
| `stripe_product_id` | VARCHAR(100) | Stripe Product ID |
| `stripe_price_id` | VARCHAR(100) | Stripe Price ID |
| `license_type` | ENUM('premium','pro_plus') | Lizenz-Typ |
| `plan` | ENUM('starter','professional','agency','enterprise') | Plan |
| `site_limit` | INT UNSIGNED | Site-Limit f&uuml;r dieses Produkt |
| `duration_days` | INT UNSIGNED | Laufzeit in Tagen (365 = 1 Jahr) |
| `created_at` | DATETIME | Erstelldatum |

---

## REST API Endpunkte

**Base URL:** `https://dashboard.pdfviewer.drossmedia.de/wp-json/plm/v1/`

Alle &ouml;ffentlichen Endpunkte sind ohne Authentifizierung zug&auml;nglich (f&uuml;r Plugin-Kommunikation).

### GET /health

Health-Check f&uuml;r Monitoring.

**Response (200):**
```json
{
  "status": "ok",
  "version": "1.0.0",
  "database": "connected",
  "timestamp": "2026-02-28T12:00:00+00:00"
}
```

### POST /license/validate

Validiert einen Lizenzschl&uuml;ssel und gibt Status + Details zur&uuml;ck.

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
  "expires_at": "2027-02-28T00:00:00",
  "days_remaining": 365,
  "site_limit": 5,
  "active_sites": 2,
  "message": "License is valid."
}
```

**Fehler:**
- `400` &mdash; `missing_field` (license_key fehlt)
- `404` &mdash; `not_found` (Schl&uuml;ssel nicht gefunden)
- `429` &mdash; `rate_limit_exceeded`

### POST /license/activate

Aktiviert eine Lizenz f&uuml;r eine bestimmte Site. Erstellt eine Installation, f&uuml;hrt Geo-IP-Lookup durch.

**Request:**
```json
{
  "license_key": "PDF$PRO#A1B2-C3D4@E5F6-G7H8!I9J0",
  "site_url": "https://example.com",
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
  "expires_at": "2027-02-28T00:00:00",
  "latest_version": "1.3.0",
  "message": "License activated successfully for example.com"
}
```

**Fehler:**
- `400` &mdash; `missing_field` oder `invalid_platform`
- `403` &mdash; Lizenz expired/revoked/inactive oder `site_limit_reached`
- `404` &mdash; Lizenzschl&uuml;ssel nicht gefunden
- `429` &mdash; Rate Limit

### POST /license/deactivate

Deaktiviert eine Lizenz f&uuml;r eine Site und gibt den Site-Slot frei.

**Request:**
```json
{
  "license_key": "PDF$PRO#A1B2-C3D4@E5F6-G7H8!I9J0",
  "site_url": "https://example.com"
}
```

**Response (200):**
```json
{
  "deactivated": true,
  "active_sites": 2,
  "remaining_sites": 3,
  "message": "License deactivated for example.com"
}
```

### POST /license/check (Heartbeat)

Periodischer Heartbeat-Check (alle 24h vom Plugin gesendet). Aktualisiert `last_checked_at`, `plugin_version`, und erneuert Geo-Daten wenn &auml;lter als 30 Tage.

**Request:**
```json
{
  "license_key": "PDF$PRO#A1B2-C3D4@E5F6-G7H8!I9J0",
  "site_url": "https://example.com",
  "plugin_version": "1.3.0",
  "platform": "wordpress"
}
```

**Response (200):**
```json
{
  "valid": true,
  "status": "active",
  "expires_at": "2027-02-28T00:00:00",
  "days_remaining": 365,
  "latest_version": "1.3.0",
  "update_available": false,
  "update_url": null,
  "message": null,
  "checked_at": "2026-02-28T12:00:00+00:00"
}
```

### POST /webhook/stripe

Stripe Webhook-Empf&auml;nger. Erfordert g&uuml;ltige Stripe-Signatur.

**Verarbeitete Events:**

| Event | Aktion |
|-------|--------|
| `checkout.session.completed` | Neue Lizenz erstellen (anhand Produkt-Mapping) |
| `invoice.paid` | Lizenz um 365 Tage verl&auml;ngern |
| `customer.subscription.deleted` | K&uuml;ndigung protokollieren |
| `invoice.payment_failed` | Fehlgeschlagene Zahlung protokollieren |

---

## Rate Limiting

Alle &ouml;ffentlichen Endpunkte sind per WordPress Transients API rate-limited:

| Typ | Limit | Fenster | Identifier |
|-----|-------|---------|------------|
| API (allgemein) | 60 Requests | pro Minute | Client-IP |
| Activate | 10 Requests | pro Stunde | License Key |
| Check (Heartbeat) | 1.000 Requests | pro Tag | License Key |

&Uuml;berschreitung liefert HTTP 429 mit `rate_limit_exceeded`.

---

## Admin Dashboard

Das Plugin registriert 6 Admin-Seiten im WordPress-Backend (erfordert `manage_options` Capability):

### Dashboard (`/wp-admin/admin.php?page=plm-dashboard`)

- KPI-Karten: Aktive Lizenzen, Aktive Installationen, Gesamt-Lizenzen, Abl&auml;ufe (30 Tage)
- Quick Actions: Lizenz erstellen, Installationen anzeigen, Statistiken
- API-Endpunkt-&Uuml;bersicht

### Lizenzen (`/wp-admin/admin.php?page=plm-licenses`)

- Filterable Lizenzliste (Status, Typ, Suche nach Key/E-Mail)
- Spalten: Schl&uuml;ssel (maskiert), Typ, Plan, Status, Sites, Ablauf, Erstellt
- Formular zur manuellen Lizenzerstellung (Typ, Plan, E-Mail, Site-Limit, Laufzeit)

### Lizenz-Einzelansicht (`?action=view&license_id=X`)

- Volle Lizenzdetails inkl. Stripe-Referenzen
- Installations-Tabelle (Plattform, Domain, Plugin-Version, Letzter Check, Geo)
- Audit-Log f&uuml;r diese Lizenz
- Aktionen: Verl&auml;ngern (90/180/365 Tage), Sperren

### Installationen (`/wp-admin/admin.php?page=plm-installations`)

Installations&uuml;bersicht mit allen Spalten gem&auml;&szlig; Lastenheft FA-DASH-05:

| Spalte | Beschreibung |
|--------|-------------|
| Plattform | WordPress / Drupal / React (farbcodiert) |
| Root Domain | Normalisierte Site-URL |
| Aktivierungsdatum | Zeitpunkt der Erstaktivierung |
| Verbleibende Tage | Tage bis Lizenzablauf |
| Lizenz-Typ | Premium / Pro+ |
| Plugin-Version | Installierte Plugin-Version |
| Lizenzschl&uuml;ssel | Maskiert (z.B. `PDF$PRO#****I9J0`) |
| Land/Region | Geo-IP-basiert |
| Letzter Heartbeat | Zeitpunkt des letzten Check-Calls |

### Statistiken (`/wp-admin/admin.php?page=plm-stats`)

- Geo-Verteilung: Top 20 L&auml;nder mit Balkendiagramm
- Plattform-Verteilung (WP/Drupal/React)
- Lizenzen nach Typ (Premium/Pro+) und Plan (Starter/Professional/Agency/Enterprise)
- Plugin-Versions-Verteilung

### Audit-Log (`/wp-admin/admin.php?page=plm-audit-log`)

- Chronologische Event-Liste
- Event-Typen: `license.created`, `license.activated`, `license.deactivated`, `license.renewed`, `license.revoked`, `license.extended`, `subscription.cancelled`, `payment.failed`
- Details: JSON-Details, maskierter Lizenzschl&uuml;ssel, IP, Zeitstempel

### Einstellungen (`/wp-admin/admin.php?page=plm-settings`)

- Stripe-Produkt-Mapping-Tabelle
- System-Informationen (PHP-Version, DB-Version, GeoIP-Status)
- wp-config.php Konfigurationsanleitung

---

## Stripe Integration

### Ablauf (ohne WooCommerce)

```
Kunde          Shop                  Stripe              License Dashboard
  │              │                     │                       │
  │─ Checkout ──▶│                     │                       │
  │              │─ Checkout Session ──▶│                       │
  │              │                     │                       │
  │◀─ Payment ──│◀─ Payment Success ──│                       │
  │              │                     │                       │
  │              │                     │── Webhook ───────────▶│
  │              │                     │  (checkout.session.   │
  │              │                     │   completed)          │
  │              │                     │                       │── Lizenz erstellen
  │              │                     │                       │── E-Mail senden
  │◀────────── Lizenzschl&uuml;ssel per E-Mail ──────────────────│
```

### Webhook-Signatur-Verifikation

Die Signatur-Pr&uuml;fung erfolgt in nativem PHP (kein Stripe SDK n&ouml;tig):

1. Header `stripe-signature` parsen (`t=timestamp,v1=signature`)
2. Timestamp-Toleranz: max. 5 Minuten
3. HMAC-SHA256 mit dem Webhook Secret &uuml;ber `timestamp.payload`
4. Constant-Time-Vergleich via `hash_equals()`

### Produkt-Mapping

Im Dashboard unter **Einstellungen** wird konfiguriert, welches Stripe-Produkt zu welchem Lizenz-Typ + Plan geh&ouml;rt:

| Stripe Product ID | Lizenz-Typ | Plan | Site-Limit | Laufzeit |
|-------------------|-----------|------|------------|----------|
| `prod_abc123` | premium | starter | 1 | 365 Tage |
| `prod_def456` | premium | professional | 5 | 365 Tage |
| `prod_ghi789` | pro_plus | agency | 0 (unbegrenzt) | 365 Tage |

### Idempotenz

Jedes Stripe-Event wird via `stripe_event_id` (UNIQUE) gespeichert. Doppelte Webhook-Zustellungen werden erkannt und ignoriert.

---

## GeoIP Integration

### MaxMind GeoLite2

| Eigenschaft | Details |
|-------------|---------|
| **Datenbank** | GeoLite2-City (kostenlose Registrierung bei MaxMind) |
| **Dateigr&ouml;&szlig;e** | ~70 MB unkomprimiert |
| **Speicherort** | `wp-content/uploads/plm/GeoLite2-City.mmdb` |
| **PHP-Paket** | `maxmind-db/reader` (via Composer) |
| **Fallback** | PHP GeoIP Extension (`geoip_record_by_name()`) |
| **Update-Intervall** | Wöchentlich/monatlich via `geoipupdate` CLI |
| **Genauigkeit** | Land: 99,8% / Stadt: ~75% |

### Funktionsweise

1. Bei **Lizenz-Aktivierung** (`/license/activate`): IP-Adresse wird per GeoLite2 aufgel&ouml;st und in `wp_plm_geo_data` gespeichert
2. Bei **Heartbeat** (`/license/check`): Geo-Daten werden aktualisiert, wenn &auml;lter als 30 Tage
3. **IP-Anonymisierung**: Letztes Oktett wird nach dem Geo-Lookup auf `0` gesetzt (DSGVO)
4. **Private IPs**: Werden &uuml;bersprungen (10.x.x.x, 192.168.x.x, etc.)

---

## Lizenzschl&uuml;ssel-Formate

### Formate

| Typ | Format | Beispiel |
|-----|--------|---------|
| Premium | `PDF$PRO#XXXX-XXXX@XXXX-XXXX!XXXX` | `PDF$PRO#A1B2-C3D4@E5F6-G7H8!I9J0` |
| Pro+ | `PDF$PRO+#XXXX-XXXX@XXXX-XXXX!XXXX` | `PDF$PRO+#A1B2-C3D4@E5F6-G7H8!I9J0` |
| Unlimited | `PDF$UNLIMITED#XXXX@XXXX!XXXX` | `PDF$UNLIMITED#A1B2@C3D4!E5F6` |
| Development | `PDF$DEV#XXXX-XXXX@XXXX!XXXX` | `PDF$DEV#A1B2-C3D4@E5F6!G7H8` |

`X` = alphanumerisch (A-Z, 0-9), generiert via `random_bytes()` (PHP).

### Pläne und Site-Limits

| Plan | Site-Limit | Preis (j&auml;hrlich) |
|------|------------|---------------------|
| Starter | 1 Site | ab 49 &euro; |
| Professional | 5 Sites | ab 99 &euro; |
| Agency | Unbegrenzt | ab 199 &euro; |
| Enterprise | Unbegrenzt | ab 399 &euro; |
| Dev | 1 Site | Kostenlos (Entwicklung) |

### Status-Lebenszyklus

```
inactive ──▶ active ──▶ grace_period (14 Tage) ──▶ expired
                │                                       │
                └──── revoked ◀─────────────────────────┘
```

- **inactive**: Lizenz erstellt, aber noch nicht auf einer Site aktiviert
- **active**: Mindestens eine aktive Installation, Lizenz g&uuml;ltig
- **grace_period**: Lizenz abgelaufen, 14-Tage-&Uuml;bergangszeit (Features bleiben aktiv)
- **expired**: Grace Period abgelaufen, Features deaktiviert
- **revoked**: Manuell gesperrt durch Admin

---

## Lokale Domain-Erkennung

Folgende Domains z&auml;hlen **nicht** zum Site-Limit:

| Muster | Beispiele |
|--------|-----------|
| `localhost` | `http://localhost`, `http://localhost:3000` |
| `127.0.0.1` | `http://127.0.0.1:8080` |
| `*.local` | `mysite.local`, `dev.mysite.local` |
| `*.test` | `mysite.test` |
| `*.dev` | `mysite.dev` |
| `*.staging.*` | `staging.example.com` |
| `*.stage.*` | `stage.example.com` |
| `10.x.x.x` | Private IP-Ranges |
| `192.168.x.x` | Private IP-Ranges |

---

## Sicherheit & DSGVO

### Sicherheitsma&szlig;nahmen

| Ma&szlig;nahme | Implementierung |
|------------|----------------|
| HTTPS-Pflicht | TLS 1.2+ (Let's Encrypt) |
| SQL Injection | `$wpdb->prepare()` f&uuml;r alle Queries |
| Rate Limiting | WordPress Transients API (60/min, 10 Aktivierungen/h, 1000 Checks/Tag) |
| Stripe Webhook | HMAC-SHA256 Signatur-Verifikation |
| Input Sanitization | `sanitize_text_field()`, `esc_url_raw()`, `absint()` |
| Output Escaping | `esc_html()`, `esc_attr()`, `esc_url()` |
| Admin-Zugang | WordPress Login + `manage_options` Capability |
| Audit Trail | Alle schreibenden Operationen werden protokolliert |
| Key-Generierung | `random_bytes()` (kryptographisch sicher) |

### DSGVO-Konformit&auml;t

| Anforderung | Umsetzung |
|-------------|-----------|
| IP-Anonymisierung | Letztes Oktett = 0 nach Geo-Lookup (IPv4), letzte 80 Bit = 0 (IPv6) |
| Lokale Geo-Daten | MaxMind GeoLite2 lokal &mdash; keine IP-Daten an Dritte |
| Datenminimierung | Nur technische Daten (Domain, Plattform, Version), keine Benutzer-/Inhaltsdaten |
| EU-Hosting | Deutsche Server empfohlen (Hetzner Cloud) |
| Kein Endbenutzer-Tracking | Nur Server-/Installations-Daten werden erhoben |
| L&ouml;schkonzept | Geo-Details nach 24 Monaten anonymisieren |

---

## Dateistruktur

```
license-dashboard/
├── pdf-license-manager.php            # Plugin-Hauptdatei, Bootstrap, Singleton
├── README.md                          # Diese Dokumentation
│
├── includes/
│   ├── class-plm-database.php         # DB-Schema (6 Tabellen via dbDelta)
│   ├── class-plm-license.php          # Key-Generator, Validierung, IP-Anonymisierung
│   ├── class-plm-api.php              # REST API (5 Endpunkte + Rate Limiting)
│   ├── class-plm-geoip.php            # MaxMind GeoLite2 Integration
│   ├── class-plm-stripe.php           # Stripe Webhook (native PHP, kein SDK)
│   └── class-plm-admin.php            # Admin-Seiten, Menü, Form-Handler
│
├── admin/
│   ├── views/
│   │   ├── dashboard.php              # KPI-Übersicht + Quick Actions
│   │   ├── licenses.php               # Lizenz-Liste + Erstellen-Formular
│   │   ├── license-detail.php         # Einzelansicht + Aktionen
│   │   ├── installations.php          # Installations-Tabelle (9 Spalten)
│   │   ├── stats.php                  # Statistiken + Geo-Verteilung
│   │   ├── audit-log.php              # Chronologisches Audit-Log
│   │   └── settings.php              # Stripe-Mapping + System-Info
│   ├── css/
│   │   └── admin.css                  # Admin-Styles (KPIs, Badges, Charts)
│   └── js/
│       └── admin.js                   # Plan → Site-Limit Auto-Update
│
└── assets/                            # Reserviert für statische Assets
```

---

## Anforderungen (Lastenheft-Referenz)

Die vollst&auml;ndige Spezifikation befindet sich in `/LASTENHEFT-LICENSE-CHECKER.md`.

### Funktionale Anforderungen (Zusammenfassung)

| ID | Bereich | Beschreibung | Status |
|----|---------|-------------|--------|
| FA-API-01 | API | POST /license/validate | Implementiert |
| FA-API-02 | API | POST /license/activate mit Site-Limit-Pr&uuml;fung | Implementiert |
| FA-API-03 | API | POST /license/deactivate | Implementiert |
| FA-API-04 | API | POST /license/check (Heartbeat) | Implementiert |
| FA-API-05 | API | GET /health | Implementiert |
| FA-API-06 | API | Rate Limiting (60/min, 10 activate/h, 1000 check/day) | Implementiert |
| FA-DASH-01 | Dashboard | KPI-&Uuml;bersicht | Implementiert |
| FA-DASH-02 | Dashboard | Lizenz-CRUD | Implementiert |
| FA-DASH-03 | Dashboard | Lizenzliste mit Filtern | Implementiert |
| FA-DASH-04 | Dashboard | Lizenz-Einzelansicht | Implementiert |
| FA-DASH-05 | Dashboard | Installations-Tabelle (9 Spalten) | Implementiert |
| FA-DASH-06 | Dashboard | Statistiken | Implementiert |
| FA-DASH-07 | Dashboard | Audit-Log | Implementiert |
| FA-GEO-01 | Geo-IP | MaxMind GeoLite2 Integration | Implementiert |
| FA-GEO-02 | Geo-IP | IP-Anonymisierung | Implementiert |
| FA-STRIPE-01 | Stripe | Webhook-Empfang + Signatur-Verifikation | Implementiert |
| FA-STRIPE-02 | Stripe | Auto-Lizenzerstellung bei checkout.session.completed | Implementiert |
| FA-STRIPE-03 | Stripe | Subscription-Verlängerung bei invoice.paid | Implementiert |
| FA-STRIPE-04 | Stripe | Produkt-Mapping (Dashboard UI) | Implementiert |

### Nicht-funktionale Anforderungen

| ID | Bereich | Zielwert |
|----|---------|----------|
| NFA-PERF-01 | API-Antwortzeit | < 200ms (p95) |
| NFA-AVAIL-01 | Uptime | 99,5% |
| NFA-SEC-01 | Transport | Ausschlie&szlig;lich HTTPS (TLS 1.2+) |
| NFA-SEC-05 | SQL-Schutz | `$wpdb->prepare()` f&uuml;r alle Queries |
| NFA-SCALE-01 | Lizenzen | Bis zu 10.000 aktive Lizenzen |
| NFA-SCALE-02 | Heartbeats | Bis zu 50.000 Checks pro Tag |
| NFA-DSGVO-01 | IP-Schutz | Anonymisierung nach Geo-Lookup |

### Offene Punkte (TODO)

| Aufgabe | Priorit&auml;t | Beschreibung |
|---------|---------|-------------|
| E-Mail-Versand | MUSS | `wp_mail()` f&uuml;r Lizenzschl&uuml;ssel nach Stripe-Kauf |
| CSV-Export | SOLL | Export-Funktionalit&auml;t f&uuml;r Lizenzen und Installationen |
| Geo-Weltkarte | SOLL | Interaktive Karte mit Installationen pro Land |
| Plugin-Anpassungen | MUSS | Phone-Home-Integration in WP/Drupal/React Plugins |
| Monitoring | MUSS | Health-Check Alerting (Uptime-Kuma oder Betterstack) |
| GeoLite2 Auto-Update | SOLL | Cronjob f&uuml;r automatisches DB-Update |

---

## Lizenz

GPL v2 or later &mdash; https://www.gnu.org/licenses/gpl-2.0.html

Made with care by [Dross:Media](https://dross.net/media/)
