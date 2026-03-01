# Lastenheft: License Checker & Dashboard System

**Projekt:** PDF Embed & SEO Optimize – Lizenzierungssystem
**Auftraggeber:** Dross:Media (Alexander Dross)
**Version:** 1.0
**Datum:** 2026-02-28
**Status:** Entwurf

---

## Inhaltsverzeichnis

1. [Projektziel & Kontext](#1-projektziel--kontext)
2. [Systemübersicht](#2-systemübersicht)
3. [Funktionale Anforderungen](#3-funktionale-anforderungen)
4. [Nicht-funktionale Anforderungen](#4-nicht-funktionale-anforderungen)
5. [Datenmodell](#5-datenmodell)
6. [API-Spezifikation](#6-api-spezifikation)
7. [Technologie-Stack](#7-technologie-stack)
8. [Phasenplan](#8-phasenplan)
9. [Akzeptanzkriterien](#9-akzeptanzkriterien)

---

## 1. Projektziel & Kontext

### 1.1 Ausgangslage

Das Produkt **PDF Embed & SEO Optimize** ist eine Multi-Plattform-Lösung (WordPress, Drupal 10/11, React/Next.js) zur PDF-Einbettung und SEO-Optimierung. Es existieren drei Lizenz-Stufen:

| Stufe | Preis | Plattformen |
|-------|-------|-------------|
| **Free** | kostenlos | WP, Drupal, React |
| **Premium (Pro)** | $49–$199/Jahr | WP, Drupal, React |
| **Pro+ Enterprise** | $199–$799/Jahr | WP, Drupal, React |

Die Lizenzvalidierung erfolgt derzeit **ausschließlich lokal** mittels Regex-Formatprüfung und Ablaufdatum-Check. Es existiert weder ein zentraler Lizenzserver noch ein Phone-Home-Mechanismus. Lizenzen werden manuell per E-Mail ausgeliefert.

### 1.2 Projektziel

Aufbau eines zentralen **License Checker & Dashboard Systems**, das folgende Kernziele verfolgt:

1. **Zentrale Lizenzverwaltung** – Erstellen, Aktivieren, Deaktivieren, Verlängern und Widerrufen von Lizenzschlüsseln
2. **Öffentliche License API** – Validierungs-Endpunkt, den alle Plugin-/Modul-Installationen ansprechen können
3. **Installations-Tracking** – Erfassung aller aktiven Installationen inkl. Geo-IP-Daten
4. **Statistik-Dashboard** – Übersicht über Lizenzen, Installationen, Geo-Verteilung und Nutzung
5. **Stripe-Integration** – Automatische Lizenzschlüssel-Generierung bei Kauf über den Online-Shop
6. **Plugin-/Modul-Anpassung** – Phone-Home-Mechanismus in WordPress, Drupal und React implementieren

### 1.3 Scope

| In Scope | Out of Scope |
|----------|--------------|
| License API (Public REST) | Kundenportal / Self-Service (Phase 2) |
| Admin-Dashboard | Marketplace / Plugin-Vertrieb |
| Stripe-Webhooks & Payment | Refund-Automatisierung |
| Geo-IP-Tracking (MaxMind GeoLite2) | Real-Time Usage Analytics |
| Plugin-Modifikation (WP, Drupal, React) | Feature-Flags per Lizenz |
| E-Mail-Benachrichtigungen (Ablauf, Aktivierung) | White-Label Dashboard |

### 1.4 Beteiligte Systeme

```
┌─────────────────────────────────────────────────────────────────────┐
│                     Bestehendes Ökosystem                           │
├─────────────────────────────────────────────────────────────────────┤
│                                                                     │
│  ┌──────────────┐  ┌──────────────┐  ┌──────────────────────────┐  │
│  │  WordPress   │  │   Drupal     │  │   React/Next.js          │  │
│  │  Plugin      │  │   Module     │  │   Packages               │  │
│  │  (PHP)       │  │   (PHP)      │  │   (TypeScript)           │  │
│  └──────┬───────┘  └──────┬───────┘  └────────────┬─────────────┘  │
│         │                 │                        │                │
│         └─────────────────┼────────────────────────┘                │
│                           │ HTTPS (Phone-Home)                     │
├───────────────────────────┼─────────────────────────────────────────┤
│                           ▼                                         │
│  ┌──────────────────────────────────────────────────────────────┐   │
│  │           License API (Neues System)                         │   │
│  │           api.pdfviewer.drossmedia.de                        │   │
│  └──────────────────────────┬───────────────────────────────────┘   │
│                              │                                      │
│         ┌────────────────────┼────────────────────┐                 │
│         ▼                    ▼                    ▼                 │
│  ┌─────────────┐  ┌──────────────────┐  ┌──────────────────┐      │
│  │  Dashboard   │  │  MySQL / MariaDB │  │  Stripe API      │      │
│  │  (Admin UI)  │  │  (Lizenzen,      │  │  (Payments,      │      │
│  │  dashboard.  │  │   Statistiken)   │  │   Subscriptions) │      │
│  │  pdfviewer.  │  │                  │  │                  │      │
│  │  drossmedia. │  └──────────────────┘  └──────────────────┘      │
│  │  de          │                                                   │
│  └─────────────┘                                                    │
│                                                                     │
│  ┌──────────────────────────────────────────────────────────────┐   │
│  │  Online-Shop: pdfviewer.drossmedia.de                        │   │
│  │  (WordPress + Stripe Plugin → Webhook → License API)         │   │
│  └──────────────────────────────────────────────────────────────┘   │
│                                                                     │
└─────────────────────────────────────────────────────────────────────┘
```

---

## 2. Systemübersicht

### 2.1 Architektur

Das System besteht aus vier Hauptkomponenten:

```
┌─────────────────────────────────────────────────────────────────┐
│              WordPress + Custom Plugin (pdf-license-manager)      │
│               pdfviewer.drossmedia.de                  │
├─────────────────────────────────────────────────────────────────┤
│                                                                  │
│  ┌─────────────────────────┐  ┌──────────────────────────────┐  │
│  │   Admin UI (WP-Admin)   │  │   REST API (WP REST)         │  │
│  │                         │  │                              │  │
│  │  • Dashboard Pages      │  │  /wp-json/plm/v1/license/    │  │
│  │  • Lizenz-Verwaltung    │  │    validate                  │  │
│  │  • Statistiken          │  │    activate                  │  │
│  │  • Geo-Map              │  │    deactivate                │  │
│  │  • Stripe-Übersicht     │  │    check                     │  │
│  │                         │  │  /wp-json/plm/v1/webhook/    │  │
│  │                         │  │    stripe                    │  │
│  └─────────────────────────┘  └──────────────────────────────┘  │
│                                                                  │
│  ┌─────────────────────────┐  ┌──────────────────────────────┐  │
│  │   Custom DB Tables      │  │   PHP Classes                │  │
│  │   (via dbDelta)         │  │                              │  │
│  │                         │  │  • PLM_License               │  │
│  │  • plm_licenses         │  │  • PLM_API                   │  │
│  │  • plm_installations    │  │  • PLM_Stripe                │  │
│  │  • plm_geo_data         │  │  • PLM_GeoIP                 │  │
│  │  • plm_audit_logs       │  │  • PLM_Admin                 │  │
│  │  • plm_stripe_events    │  │  • PLM_Database              │  │
│  │  • plm_stripe_product_  │  │                              │  │
│  │    map                  │  │                              │  │
│  └─────────────────────────┘  └──────────────────────────────┘  │
│                                                                  │
├─────────────────────────────────────────────────────────────────┤
│           MySQL / MariaDB          MaxMind GeoLite2               │
│              (Datenbank)           (Lokale GeoIP DB)              │
└─────────────────────────────────────────────────────────────────┘
```

### 2.2 Hosting-Struktur

| Komponente | Domain | Beschreibung |
|------------|--------|-------------|
| Online-Shop | `pdfviewer.drossmedia.de` | WordPress + Stripe (Bestand) |
| License Dashboard | `pdfviewer.drossmedia.de` | Admin-Dashboard (Neu) |
| License API | `pdfviewer.drossmedia.de/api/v1/` | Öffentliche REST API (Neu) |

### 2.3 Authentifizierung

| Zugriff | Methode |
|---------|---------|
| Dashboard (Admin-UI) | Admin-Login mit E-Mail + Passwort (ggf. 2FA) |
| License API (Plugin-Calls) | License Key + Site URL (öffentlich, kein Auth-Token nötig) |
| Stripe Webhooks | Stripe Webhook Signature Verification |

---

## 3. Funktionale Anforderungen

### 3.1 License API (Öffentliche Endpunkte)

Die License API ist der zentrale Anlaufpunkt für alle Plugin-/Modul-Installationen. Sie muss **ohne Authentifizierung** erreichbar sein (der License Key selbst dient als Identifikation).

#### FA-API-01: Lizenz validieren

| Attribut | Beschreibung |
|----------|-------------|
| **Endpunkt** | `POST /api/v1/license/validate` |
| **Beschreibung** | Prüft ob ein Lizenzschlüssel gültig ist, ohne eine Aktivierung vorzunehmen |
| **Input** | `license_key`, `site_url`, `platform` (wordpress\|drupal\|react) |
| **Output** | Status (valid, invalid, expired, grace_period), Lizenz-Typ, Ablaufdatum, verbleibende Tage |
| **Priorität** | MUSS |

#### FA-API-02: Lizenz aktivieren

| Attribut | Beschreibung |
|----------|-------------|
| **Endpunkt** | `POST /api/v1/license/activate` |
| **Beschreibung** | Aktiviert eine Lizenz für eine bestimmte Site-URL. Prüft Site-Limit. |
| **Input** | `license_key`, `site_url`, `platform`, `plugin_version`, `php_version` (optional), `wp_version` (optional), `drupal_version` (optional), `node_version` (optional) |
| **Output** | Aktivierungs-Status, verbleibende Sites, Aktivierungs-ID |
| **Logik** | Prüft ob Site-Limit erreicht. localhost/staging zählt nicht. Erstellt Installations-Eintrag mit Geo-IP. |
| **Priorität** | MUSS |

#### FA-API-03: Lizenz deaktivieren

| Attribut | Beschreibung |
|----------|-------------|
| **Endpunkt** | `POST /api/v1/license/deactivate` |
| **Beschreibung** | Deaktiviert eine Lizenz für eine bestimmte Site-URL. Gibt Site-Slot frei. |
| **Input** | `license_key`, `site_url` |
| **Output** | Bestätigung, verbleibende aktive Sites |
| **Priorität** | MUSS |

#### FA-API-04: Lizenzstatus prüfen (Check / Heartbeat)

| Attribut | Beschreibung |
|----------|-------------|
| **Endpunkt** | `POST /api/v1/license/check` |
| **Beschreibung** | Periodischer Heartbeat-Check. Aktualisiert letzte Kontaktzeit und Plugin-Version. |
| **Input** | `license_key`, `site_url`, `plugin_version`, `platform` |
| **Output** | Status, Ablaufdatum, Update-verfügbar-Flag, Nachricht (z.B. "Lizenz läuft in 14 Tagen ab") |
| **Logik** | Aktualisiert `last_checked_at` und `plugin_version` in der Installations-Tabelle. Löst Geo-IP-Update aus falls >30 Tage alt. |
| **Priorität** | MUSS |

#### FA-API-05: Rate Limiting

| Attribut | Beschreibung |
|----------|-------------|
| **Beschreibung** | Schutz vor Missbrauch der öffentlichen API |
| **Limits** | 60 Requests/Minute pro IP, 10 Aktivierungen/Stunde pro License Key, 1000 Checks/Tag pro License Key |
| **Response bei Überschreitung** | HTTP 429 mit `Retry-After` Header |
| **Priorität** | MUSS |

#### FA-API-06: API-Versionierung

| Attribut | Beschreibung |
|----------|-------------|
| **Beschreibung** | API ist unter `/api/v1/` versioniert. Zukünftige Breaking Changes unter `/api/v2/`. |
| **Abwärtskompatibilität** | v1 bleibt mindestens 12 Monate nach Release von v2 verfügbar |
| **Priorität** | SOLL |

---

### 3.2 Dashboard (Admin-Bereich)

Das Dashboard wird unter `pdfviewer.drossmedia.de` gehostet und ist ausschließlich für den Administrator zugänglich.

#### FA-DASH-01: Login & Authentifizierung

| Attribut | Beschreibung |
|----------|-------------|
| **Beschreibung** | Sicherer Admin-Login |
| **Methode** | E-Mail + Passwort |
| **Optional** | 2FA (TOTP) |
| **Session** | JWT mit HttpOnly Cookie, 24h Gültigkeit |
| **Priorität** | MUSS |

#### FA-DASH-02: Dashboard-Übersicht (Home)

| Attribut | Beschreibung |
|----------|-------------|
| **Beschreibung** | Startseite mit KPIs und Zusammenfassung |
| **KPI-Karten** | Aktive Lizenzen (gesamt), Aktive Installationen (gesamt), Umsatz (Monat/Jahr via Stripe), Ablaufende Lizenzen (nächste 30 Tage) |
| **Diagramme** | Neue Lizenzen pro Monat (Linienchart), Installationen nach Plattform (Donut: WP/Drupal/React), Umsatz pro Monat (Balkendiagramm) |
| **Letzte Aktivität** | 10 letzte Aktivierungen/Deaktivierungen |
| **Priorität** | MUSS |

#### FA-DASH-03: Lizenzverwaltung

| Attribut | Beschreibung |
|----------|-------------|
| **Beschreibung** | CRUD-Operationen für Lizenzschlüssel |
| **Listenansicht** | Tabelle mit Lizenz-Key (maskiert), Typ (Premium/Pro+), Plan (Starter/Professional/Agency/Enterprise), Status, Ablaufdatum, aktive Sites, Stripe-Customer-ID |
| **Filteroptionen** | Status, Typ, Plan, Plattform, Ablaufdatum-Bereich, Suchfeld (Key, E-Mail) |
| **Einzelansicht** | Alle Lizenzdetails, zugehörige Installationen, Aktivitäts-Log, Stripe-Zahlungshistorie |
| **Aktionen** | Erstellen (manuell), Verlängern, Sperren/Widerrufen, Reaktivieren, Löschen, Plan-Upgrade/Downgrade, Ablaufdatum ändern, Notiz hinzufügen |
| **Priorität** | MUSS |

#### FA-DASH-04: Lizenz manuell erstellen

| Attribut | Beschreibung |
|----------|-------------|
| **Beschreibung** | Manuelles Erstellen eines neuen Lizenzschlüssels |
| **Eingabefelder** | Lizenz-Typ (Premium/Pro+), Plan (Starter/Professional/Agency/Enterprise), Kunden-E-Mail, Gültigkeitsdauer (1 Jahr / Lifetime / Custom), Site-Limit (1/5/Unlimited/Custom), Notiz |
| **Automatisch** | Lizenzschlüssel wird generiert im bestehenden Format |
| **Priorität** | MUSS |

#### FA-DASH-05: Installations-Übersicht

| Attribut | Beschreibung |
|----------|-------------|
| **Beschreibung** | Übersicht aller aktiven Plugin-/Modul-Installationen |
| **Listenansicht** | Plattform (WP/Drupal/React), Root-Domain (Site-URL), Aktivierungsdatum, Verbleibende Tage (bis Lizenzablauf), Lizenz-Typ (Premium/Pro+), Plugin-Version, PHP/Node-Version, Lizenz-Key (maskiert), Land/Region, letzter Heartbeat |
| **Filteroptionen** | Plattform, Lizenz-Typ (Premium/Pro+), Plugin-Version, Land, Lizensstatus, verbleibende Tage (<30), letzter Heartbeat (aktiv/inaktiv >30 Tage) |
| **Sortierung** | Nach allen Spalten sortierbar |
| **Priorität** | MUSS |

#### FA-DASH-06: Geo-IP Statistiken

| Attribut | Beschreibung |
|----------|-------------|
| **Beschreibung** | Geografische Verteilung der Installationen |
| **Weltkarte** | Interaktive Karte mit Installationen pro Land (Heatmap) |
| **Tabelle** | Top-Länder mit Anzahl Installationen, aufschlüsselbar nach Plattform |
| **Details** | Drill-Down: Land → Region/Stadt → einzelne Installationen |
| **Datenquelle** | MaxMind GeoLite2 (lokale Datenbank, monatliches Update) |
| **Priorität** | MUSS |

#### FA-DASH-07: Statistiken & Reporting

| Attribut | Beschreibung |
|----------|-------------|
| **Beschreibung** | Umfassende Statistiken zu Lizenzen und Installationen |
| **Zeiträume** | 7 Tage, 30 Tage, 90 Tage, 1 Jahr, Gesamt, Custom Range |
| **Lizenz-Statistiken** | Aktive/Abgelaufene/Gesperrte Lizenzen, Neuregistrierungen pro Zeitraum, Verlängerungsrate (Renewal Rate), Churn Rate, Durchschnittliche Lizenz-Lebensdauer |
| **Installations-Statistiken** | Aktive Installationen nach Plattform (WP/Drupal/React), Plugin-Versions-Verteilung (welche Versionen sind im Einsatz), PHP/Node-Versions-Verteilung, Neue Installationen pro Zeitraum, Installationen ohne Heartbeat (>30 Tage = potentiell inaktiv) |
| **Umsatz-Statistiken** | MRR (Monthly Recurring Revenue), ARR (Annual Recurring Revenue), Umsatz pro Plan, Umsatz pro Plattform, Lifetime-Value pro Kunde |
| **Export** | CSV-Export für alle Statistiken |
| **Priorität** | MUSS |

#### FA-DASH-08: Plugin-Versions-Tracking

| Attribut | Beschreibung |
|----------|-------------|
| **Beschreibung** | Übersicht, welche Plugin-Versionen im Einsatz sind |
| **Darstellung** | Balkendiagramm der Versions-Verteilung, Tabelle mit Version → Anzahl Installationen |
| **Alerting** | Hinweis wenn veraltete Versionen (>2 Major-Versionen zurück) im Einsatz |
| **Priorität** | SOLL |

#### FA-DASH-09: Audit-Log

| Attribut | Beschreibung |
|----------|-------------|
| **Beschreibung** | Lückenlose Protokollierung aller Lizenz-Operationen |
| **Erfasste Events** | Lizenz erstellt, aktiviert, deaktiviert, verlängert, gesperrt, Stripe-Zahlung, Admin-Login, Einstellungsänderung |
| **Daten pro Event** | Zeitstempel, Event-Typ, Lizenz-Key, Site-URL, IP-Adresse, User-Agent, Details |
| **Aufbewahrung** | 2 Jahre |
| **Priorität** | SOLL |

#### FA-DASH-10: E-Mail-Benachrichtigungen

| Attribut | Beschreibung |
|----------|-------------|
| **Beschreibung** | Automatische E-Mail-Benachrichtigungen an Admin |
| **Events** | Neue Lizenz aktiviert, Lizenz läuft in 30/14/7/1 Tag(en) ab, Lizenz abgelaufen, Neue Stripe-Zahlung, Ungewöhnliche Aktivität (>5 Aktivierungen/Stunde) |
| **Priorität** | SOLL |

---

### 3.3 Stripe-Integration

#### FA-STRIPE-01: Webhook-Verarbeitung

| Attribut | Beschreibung |
|----------|-------------|
| **Endpunkt** | `POST /api/v1/webhook/stripe` |
| **Beschreibung** | Empfang und Verarbeitung von Stripe-Webhook-Events |
| **Events** | `checkout.session.completed` → Lizenz erstellen, `invoice.paid` → Lizenz verlängern, `customer.subscription.deleted` → Lizenz markieren (nicht sofort sperren), `invoice.payment_failed` → Warnung loggen |
| **Sicherheit** | Stripe Webhook Signature Verification (`stripe-signature` Header) |
| **Idempotenz** | Jedes Event wird nur einmal verarbeitet (Event-ID Tracking) |
| **Priorität** | MUSS |

#### FA-STRIPE-02: Automatische Lizenzschlüssel-Generierung

| Attribut | Beschreibung |
|----------|-------------|
| **Beschreibung** | Bei erfolgreichem Kauf wird automatisch ein Lizenzschlüssel generiert |
| **Trigger** | `checkout.session.completed` Webhook |
| **Mapping** | Stripe Product/Price → Lizenz-Typ + Plan + Site-Limit |
| **Schlüssel-Format** | Bestehendes Format beibehalten: `PDF$PRO#XXXX-XXXX@XXXX-XXXX!XXXX` bzw. `PDF$PRO+#XXXX-XXXX@XXXX-XXXX!XXXX` |
| **Zustellung** | Lizenzschlüssel wird in Stripe-Metadata gespeichert und an Kunden-E-Mail gesendet |
| **Priorität** | MUSS |

#### FA-STRIPE-03: Stripe-Produkt/Preis-Mapping

| Attribut | Beschreibung |
|----------|-------------|
| **Beschreibung** | Konfigurierbare Zuordnung von Stripe-Produkten zu Lizenz-Typen |
| **Konfiguration im Dashboard** | Stripe Product ID → { Lizenz-Typ, Plan, Site-Limit, Gültigkeitsdauer } |
| **Beispiel-Mapping** | |

| Stripe Product | Lizenz-Typ | Plan | Sites | Dauer |
|---------------|------------|------|-------|-------|
| `prod_premium_starter` | Premium | Starter | 1 | 1 Jahr |
| `prod_premium_pro` | Premium | Professional | 5 | 1 Jahr |
| `prod_premium_agency` | Premium | Agency | Unlimited | 1 Jahr |
| `prod_proplus_starter` | Pro+ | Starter | 1 | 1 Jahr |
| `prod_proplus_pro` | Pro+ | Professional | 5 | 1 Jahr |
| `prod_proplus_enterprise` | Pro+ | Enterprise | Unlimited | 1 Jahr |

| **Priorität** | MUSS |

#### FA-STRIPE-04: Umsatz-Synchronisation

| Attribut | Beschreibung |
|----------|-------------|
| **Beschreibung** | Stripe-Zahlungsdaten für Dashboard-Statistiken abrufbar |
| **Daten** | Zahlungsbetrag, Währung, Datum, Kunde, Produkt, Status |
| **Darstellung** | Im Dashboard unter Statistiken und in der Lizenz-Einzelansicht |
| **Priorität** | SOLL |

---

### 3.4 Geo-IP-Tracking

#### FA-GEO-01: IP-Geolokalisierung bei Aktivierung

| Attribut | Beschreibung |
|----------|-------------|
| **Beschreibung** | Bei jeder Lizenzaktivierung wird die IP-Adresse des Servers geolokalisiert |
| **Datenquelle** | MaxMind GeoLite2 City-Datenbank (lokal) |
| **Erfasste Daten** | Land (ISO-Code + Name), Region/Bundesland, Stadt, Breitengrad, Längengrad, Kontinent, Zeitzone |
| **Update-Intervall** | GeoLite2-Datenbank: monatliches Auto-Update (MaxMind Lizenz erforderlich, kostenlos) |
| **Priorität** | MUSS |

#### FA-GEO-02: IP-Aktualisierung bei Heartbeat

| Attribut | Beschreibung |
|----------|-------------|
| **Beschreibung** | Bei Heartbeat-Checks wird Geo-IP nur aktualisiert, wenn letztes Update >30 Tage |
| **Logik** | Vergleich `geo_updated_at` mit aktuellem Datum |
| **Priorität** | SOLL |

#### FA-GEO-03: DSGVO-konforme Verarbeitung

| Attribut | Beschreibung |
|----------|-------------|
| **Beschreibung** | IP-Adressen werden datenschutzkonform verarbeitet |
| **Speicherung** | Vollständige IP wird für die Geo-Lokalisierung genutzt, danach wird nur die anonymisierte IP (letztes Oktett = 0) gespeichert. Optional: vollständige IP speichern mit Rechtsgrundlage (berechtigtes Interesse, Art. 6 Abs. 1 lit. f DSGVO) |
| **Löschung** | IP-Daten werden nach 24 Monaten anonymisiert (Stadt/Region entfernt, nur Land bleibt) |
| **Keine externe Übermittlung** | MaxMind GeoLite2 läuft lokal, keine API-Calls zu Dritten |
| **Priorität** | MUSS |

---

### 3.5 Plugin-/Modul-Anpassungen (Phone-Home)

Die bestehenden Plugins/Module (WordPress, Drupal, React) müssen angepasst werden, um mit der License API zu kommunizieren.

#### FA-PLUGIN-01: Lizenzaktivierung im Plugin

| Attribut | Beschreibung |
|----------|-------------|
| **Beschreibung** | Beim Speichern eines Lizenzschlüssels wird die License API zur Aktivierung aufgerufen |
| **Workflow** | 1. Lokale Format-Validierung (bestehend) → 2. API-Call `POST /license/activate` → 3. Antwort speichern (Status, Ablaufdatum, Aktivierungs-ID) → 4. Erfolg/Fehler anzeigen |
| **Fehlerbehandlung** | Bei API-Timeout oder -Fehler: lokale Validierung als Fallback |
| **Plattformen** | WordPress, Drupal, React |
| **Priorität** | MUSS |

#### FA-PLUGIN-02: Lizenzdeaktivierung im Plugin

| Attribut | Beschreibung |
|----------|-------------|
| **Beschreibung** | Beim Entfernen/Deaktivieren eines Lizenzschlüssels wird die License API informiert |
| **Trigger (WP)** | Lizenzschlüssel-Feld geleert + gespeichert, Plugin deaktiviert |
| **Trigger (Drupal)** | Lizenzschlüssel-Feld geleert + gespeichert, Modul deinstalliert |
| **Trigger (React)** | `licenseKey` aus Config entfernt |
| **Priorität** | MUSS |

#### FA-PLUGIN-03: Periodischer Heartbeat

| Attribut | Beschreibung |
|----------|-------------|
| **Beschreibung** | Regelmäßiger Check gegen die License API |
| **Intervall** | Alle 24 Stunden (WordPress: WP-Cron, Drupal: Cron, React: beim App-Start mit Cache) |
| **Payload** | License Key, Site URL, Plugin-Version, Plattform |
| **Antwort-Verarbeitung** | Status-Update lokal speichern, "Update verfügbar"-Hinweis anzeigen |
| **Priorität** | MUSS |

#### FA-PLUGIN-04: Graceful Degradation

| Attribut | Beschreibung |
|----------|-------------|
| **Beschreibung** | Wenn die License API nicht erreichbar ist, darf das Plugin nicht blockiert werden |
| **Verhalten** | Letzter bekannter Status wird beibehalten. Erneuter Versuch nach 1h/6h/24h (Exponential Backoff). Maximale Offline-Toleranz: 30 Tage. Nach 30 Tagen ohne erfolgreichen Check: Status auf "unverified" setzen (Features bleiben aktiv, Admin-Warnung anzeigen). |
| **Priorität** | MUSS |

#### FA-PLUGIN-05: Übertragene Daten (Transparency)

| Attribut | Beschreibung |
|----------|-------------|
| **Beschreibung** | Transparenz darüber, welche Daten übertragen werden |
| **Daten** | License Key, Site URL (Domain), Plattform (WP/Drupal/React), Plugin-Version, PHP-Version (WP/Drupal), WordPress/Drupal-Version, Node.js-Version (React), Server-IP (automatisch via Request) |
| **Nicht übertragen** | Benutzer-Daten, Inhalte, PDF-Dateien, persönliche Informationen, Admin-E-Mail |
| **Hinweis im Plugin** | Unter Lizenz-Seite: "This plugin communicates with pdfviewer.drossmedia.de for license validation. [Learn more]" |
| **Priorität** | MUSS |

#### FA-PLUGIN-06: Update-Check

| Attribut | Beschreibung |
|----------|-------------|
| **Beschreibung** | License API liefert Information über verfügbare Updates |
| **Antwortfeld** | `latest_version` (String), `update_url` (Download-Link, nur für gültige Lizenzen) |
| **WordPress** | Einbindung in WP Update-Mechanismus (`pre_set_site_transient_update_plugins`) |
| **Drupal** | Einbindung in Drupal Update-Status (`update.module`) |
| **React** | Hinweis in Console Log / Provider Warning |
| **Priorität** | KANN |

---

## 4. Nicht-funktionale Anforderungen

### 4.1 Performance

| ID | Anforderung | Zielwert |
|----|-------------|----------|
| NFA-PERF-01 | API-Response-Zeit (Validate/Check) | < 200ms (p95) |
| NFA-PERF-02 | API-Response-Zeit (Activate/Deactivate) | < 500ms (p95) |
| NFA-PERF-03 | Dashboard-Seitenladezeit | < 2 Sekunden |
| NFA-PERF-04 | Gleichzeitige API-Requests | Mindestens 100 concurrent |
| NFA-PERF-05 | Datenbank-Abfragen | Indexierung auf License Key, Site URL, Status |

### 4.2 Verfügbarkeit

| ID | Anforderung | Zielwert |
|----|-------------|----------|
| NFA-AVAIL-01 | API Uptime | 99,5% (entspricht ~44h Downtime/Jahr) |
| NFA-AVAIL-02 | Geplante Wartung | Außerhalb Geschäftszeiten (EU), max. 1h |
| NFA-AVAIL-03 | Graceful Degradation | Plugins funktionieren bei API-Ausfall weiter (FA-PLUGIN-04) |

### 4.3 Sicherheit

| ID | Anforderung |
|----|-------------|
| NFA-SEC-01 | Ausschließlich HTTPS (TLS 1.2+) |
| NFA-SEC-02 | Rate Limiting auf allen öffentlichen Endpunkten |
| NFA-SEC-03 | Stripe Webhook Signature Verification |
| NFA-SEC-04 | CORS: Nur erlaubte Origins (Dashboard-Domain) für Admin-Endpunkte, API-Endpunkte für Plugins offen |
| NFA-SEC-05 | SQL Injection Prevention via WordPress `$wpdb->prepare()` (Parameterized Queries) |
| NFA-SEC-06 | Admin-Passwort: bcrypt mit Cost Factor ≥ 12 |
| NFA-SEC-07 | License Keys: kryptographisch sichere Generierung (`random_bytes()` PHP) |
| NFA-SEC-08 | Keine Speicherung sensibler Stripe-Daten (nur Referenz-IDs) |
| NFA-SEC-09 | Audit-Log für alle schreibenden Operationen |

### 4.4 DSGVO / Datenschutz

| ID | Anforderung |
|----|-------------|
| NFA-DSGVO-01 | IP-Anonymisierung konfigurierbar (Standard: letztes Oktett = 0 nach Geo-Lookup) |
| NFA-DSGVO-02 | Keine Übermittlung von IP-Daten an Dritte (GeoLite2 lokal) |
| NFA-DSGVO-03 | Datenminimierung: Nur technisch notwendige Daten erheben |
| NFA-DSGVO-04 | Löschkonzept: Geo-Details nach 24 Monaten anonymisieren |
| NFA-DSGVO-05 | Hosting in der EU (deutsche Server bevorzugt) |
| NFA-DSGVO-06 | Datenschutzhinweis in jedem Plugin unter der Lizenz-Seite |
| NFA-DSGVO-07 | Kein Tracking von Endbenutzer-Daten (nur Server-/Installations-Daten) |

### 4.5 Skalierbarkeit

| ID | Anforderung |
|----|-------------|
| NFA-SCALE-01 | System muss mit bis zu 10.000 aktiven Lizenzen umgehen können |
| NFA-SCALE-02 | Bis zu 50.000 Heartbeat-Checks pro Tag |
| NFA-SCALE-03 | Datenbank-Retention: 5 Jahre Lizenz-Historie |

### 4.6 Wartbarkeit

| ID | Anforderung |
|----|-------------|
| NFA-MAINT-01 | PHP 8.0+ Codebase mit strikter Typisierung (Typed Properties, Return Types) |
| NFA-MAINT-02 | Automatisierte Tests (Unit + Integration) mit ≥ 80% Coverage für API-Endpunkte |
| NFA-MAINT-03 | CI/CD Pipeline für automatisches Deployment |
| NFA-MAINT-04 | Structured Logging (JSON) für Fehleranalyse |
| NFA-MAINT-05 | Health-Check-Endpunkt (`GET /api/v1/health`) |

---

## 5. Datenmodell

### 5.1 Entity-Relationship-Diagramm

```
┌──────────────────────┐       ┌──────────────────────┐
│       License        │       │     Installation     │
├──────────────────────┤       ├──────────────────────┤
│ id (PK)              │       │ id (PK)              │
│ license_key (UNIQUE) │──1:N──│ license_id (FK)      │
│ type                 │       │ site_url              │
│ plan                 │       │ platform              │
│ status               │       │ plugin_version        │
│ site_limit           │       │ php_version           │
│ customer_email       │       │ cms_version           │
│ stripe_customer_id   │       │ node_version          │
│ stripe_subscription  │       │ activated_at          │
│ expires_at           │       │ last_checked_at       │
│ created_at           │       │ deactivated_at        │
│ updated_at           │       │ is_active             │
│ notes                │       │ is_local              │
└──────────────────────┘       └───────────┬──────────┘
                                           │
                                          1:1
                                           │
                               ┌───────────┴──────────┐
                               │      GeoData         │
                               ├──────────────────────┤
                               │ id (PK)              │
                               │ installation_id (FK) │
                               │ ip_anonymized        │
                               │ country_code         │
                               │ country_name         │
                               │ region               │
                               │ city                 │
                               │ latitude             │
                               │ longitude            │
                               │ continent            │
                               │ timezone             │
                               │ updated_at           │
                               └──────────────────────┘

┌──────────────────────┐       ┌──────────────────────┐
│     AuditLog         │       │    StripeEvent       │
├──────────────────────┤       ├──────────────────────┤
│ id (PK)              │       │ id (PK)              │
│ license_id (FK, opt) │       │ stripe_event_id (UQ) │
│ event_type           │       │ event_type           │
│ details (JSON)       │       │ license_id (FK, opt) │
│ ip_address           │       │ payload (JSON)       │
│ user_agent           │       │ processed            │
│ created_at           │       │ created_at           │
└──────────────────────┘       └──────────────────────┘

┌──────────────────────┐
│  StripeProductMap    │
├──────────────────────┤
│ id (PK)              │
│ stripe_product_id    │
│ stripe_price_id      │
│ license_type         │
│ plan                 │
│ site_limit           │
│ duration_days        │
│ created_at           │
└──────────────────────┘
```

### 5.2 Tabellen-Definitionen

#### `licenses` Tabelle

| Spalte | Typ | Beschreibung |
|--------|-----|-------------|
| `id` | UUID (PK) | Primärschlüssel |
| `license_key` | VARCHAR(100), UNIQUE | Lizenzschlüssel (Format: `PDF$PRO#...` / `PDF$PRO+#...`) |
| `type` | ENUM('premium', 'pro_plus') | Lizenz-Typ |
| `plan` | ENUM('starter', 'professional', 'agency', 'enterprise', 'lifetime', 'dev') | Plan |
| `status` | ENUM('active', 'expired', 'grace_period', 'revoked', 'inactive') | Aktueller Status |
| `site_limit` | INT | Maximale Anzahl aktiver Sites (0 = Unlimited) |
| `customer_email` | VARCHAR(255) | Kunden-E-Mail |
| `customer_name` | VARCHAR(255), NULL | Kundenname (optional) |
| `stripe_customer_id` | VARCHAR(255), NULL | Stripe Customer ID |
| `stripe_subscription_id` | VARCHAR(255), NULL | Stripe Subscription ID |
| `expires_at` | TIMESTAMP, NULL | Ablaufdatum (NULL = Lifetime) |
| `activated_at` | TIMESTAMP, NULL | Erste Aktivierung |
| `created_at` | TIMESTAMP | Erstellungsdatum |
| `updated_at` | TIMESTAMP | Letzte Änderung |
| `notes` | TEXT, NULL | Admin-Notizen |

**Indizes:** `license_key` (UNIQUE), `status`, `customer_email`, `stripe_customer_id`, `expires_at`

#### `installations` Tabelle

| Spalte | Typ | Beschreibung |
|--------|-----|-------------|
| `id` | UUID (PK) | Primärschlüssel |
| `license_id` | UUID (FK → licenses) | Zugehörige Lizenz |
| `site_url` | VARCHAR(500) | Normalisierte Site-URL (Domain ohne Protokoll/Pfad) |
| `platform` | ENUM('wordpress', 'drupal', 'react') | Plattform |
| `plugin_version` | VARCHAR(20) | Installierte Plugin-Version |
| `php_version` | VARCHAR(20), NULL | PHP-Version (WP/Drupal) |
| `cms_version` | VARCHAR(20), NULL | WordPress/Drupal-Version |
| `node_version` | VARCHAR(20), NULL | Node.js-Version (React) |
| `activated_at` | TIMESTAMP | Aktivierungszeitpunkt |
| `last_checked_at` | TIMESTAMP | Letzter Heartbeat |
| `deactivated_at` | TIMESTAMP, NULL | Deaktivierungszeitpunkt |
| `is_active` | BOOLEAN, DEFAULT TRUE | Aktiv/Inaktiv |
| `is_local` | BOOLEAN, DEFAULT FALSE | Lokale/Staging-Installation |

**Indizes:** `license_id`, `site_url`, `is_active`, `platform`, `last_checked_at`
**Unique Constraint:** `(license_id, site_url)` – Eine Lizenz kann pro Domain nur einmal aktiv sein

#### `geo_data` Tabelle

| Spalte | Typ | Beschreibung |
|--------|-----|-------------|
| `id` | UUID (PK) | Primärschlüssel |
| `installation_id` | UUID (FK → installations, UNIQUE) | Zugehörige Installation |
| `ip_anonymized` | VARCHAR(45), NULL | Anonymisierte IP (letztes Oktett = 0) |
| `country_code` | CHAR(2) | ISO 3166-1 Alpha-2 (z.B. "DE") |
| `country_name` | VARCHAR(100) | Land (z.B. "Germany") |
| `region` | VARCHAR(100), NULL | Bundesland/Region |
| `city` | VARCHAR(100), NULL | Stadt |
| `latitude` | DECIMAL(10,7), NULL | Breitengrad |
| `longitude` | DECIMAL(10,7), NULL | Längengrad |
| `continent` | VARCHAR(50), NULL | Kontinent |
| `timezone` | VARCHAR(50), NULL | Zeitzone |
| `updated_at` | TIMESTAMP | Letzte Geo-IP-Aktualisierung |

**Indizes:** `installation_id` (UNIQUE), `country_code`

#### `audit_logs` Tabelle

| Spalte | Typ | Beschreibung |
|--------|-----|-------------|
| `id` | UUID (PK) | Primärschlüssel |
| `license_id` | UUID (FK, NULL) | Zugehörige Lizenz (optional) |
| `event_type` | VARCHAR(50) | Event-Typ (z.B. `license.activated`, `license.expired`, `stripe.payment`) |
| `details` | JSONB | Event-Details |
| `ip_address` | VARCHAR(45), NULL | Anfrage-IP |
| `user_agent` | VARCHAR(500), NULL | User-Agent |
| `created_at` | TIMESTAMP | Zeitstempel |

**Indizes:** `license_id`, `event_type`, `created_at`

#### `stripe_events` Tabelle

| Spalte | Typ | Beschreibung |
|--------|-----|-------------|
| `id` | UUID (PK) | Primärschlüssel |
| `stripe_event_id` | VARCHAR(100), UNIQUE | Stripe Event ID (Idempotenz) |
| `event_type` | VARCHAR(100) | Stripe Event Typ |
| `license_id` | UUID (FK, NULL) | Zugeordnete Lizenz |
| `payload` | JSONB | Stripe Event Payload |
| `processed` | BOOLEAN, DEFAULT FALSE | Erfolgreich verarbeitet |
| `created_at` | TIMESTAMP | Eingang |

#### `stripe_product_map` Tabelle

| Spalte | Typ | Beschreibung |
|--------|-----|-------------|
| `id` | UUID (PK) | Primärschlüssel |
| `stripe_product_id` | VARCHAR(100) | Stripe Product ID |
| `stripe_price_id` | VARCHAR(100), NULL | Stripe Price ID (optional, für genaueres Mapping) |
| `license_type` | ENUM('premium', 'pro_plus') | Lizenz-Typ |
| `plan` | ENUM('starter', 'professional', 'agency', 'enterprise') | Plan |
| `site_limit` | INT | Site-Limit |
| `duration_days` | INT | Gültigkeitsdauer in Tagen (365 = 1 Jahr, 0 = Lifetime) |
| `created_at` | TIMESTAMP | Erstellt |

---

## 6. API-Spezifikation

### 6.1 Öffentliche Endpunkte (Plugin-Kommunikation)

Alle öffentlichen Endpunkte sind unter `https://pdfviewer.drossmedia.de/api/v1/` erreichbar.

---

#### `POST /api/v1/license/validate`

Validiert einen Lizenzschlüssel ohne Seiteneffekte.

**Request:**
```json
{
  "license_key": "PDF$PRO#A1B2-C3D4@E5F6-G7H8!I9J0",
  "site_url": "https://example.com",
  "platform": "wordpress"
}
```

**Response (200 OK):**
```json
{
  "valid": true,
  "status": "active",
  "type": "premium",
  "plan": "professional",
  "expires_at": "2027-02-28T00:00:00Z",
  "days_remaining": 365,
  "site_limit": 5,
  "active_sites": 2,
  "message": "License is valid."
}
```

**Response (200 OK – ungültig):**
```json
{
  "valid": false,
  "status": "expired",
  "type": "premium",
  "plan": "starter",
  "expires_at": "2026-01-15T00:00:00Z",
  "days_remaining": -44,
  "message": "License has expired. Please renew at https://pdfviewer.drossmedia.de"
}
```

**Error Responses:**
| Code | Beschreibung |
|------|-------------|
| 400 | Fehlende Pflichtfelder |
| 404 | Lizenzschlüssel nicht gefunden |
| 429 | Rate Limit überschritten |

---

#### `POST /api/v1/license/activate`

Aktiviert eine Lizenz für eine Site-URL.

**Request:**
```json
{
  "license_key": "PDF$PRO#A1B2-C3D4@E5F6-G7H8!I9J0",
  "site_url": "https://example.com",
  "platform": "wordpress",
  "plugin_version": "1.2.11",
  "php_version": "8.2.0",
  "cms_version": "6.4.2"
}
```

**Response (200 OK):**
```json
{
  "activated": true,
  "activation_id": "uuid-here",
  "status": "active",
  "site_limit": 5,
  "active_sites": 3,
  "remaining_sites": 2,
  "expires_at": "2027-02-28T00:00:00Z",
  "latest_version": "1.3.0",
  "message": "License activated successfully for example.com"
}
```

**Response (403 Forbidden – Limit erreicht):**
```json
{
  "activated": false,
  "error": "site_limit_reached",
  "site_limit": 1,
  "active_sites": 1,
  "active_domains": ["other-site.com"],
  "message": "Site limit reached. Deactivate an existing site or upgrade your plan."
}
```

**Logik für localhost/staging:**
- Domains die auf `localhost`, `127.0.0.1`, `.local`, `.test`, `.dev`, `.staging` enden, werden als `is_local = true` markiert und zählen nicht zum Site-Limit.

---

#### `POST /api/v1/license/deactivate`

Deaktiviert eine Lizenz für eine Site-URL.

**Request:**
```json
{
  "license_key": "PDF$PRO#A1B2-C3D4@E5F6-G7H8!I9J0",
  "site_url": "https://example.com"
}
```

**Response (200 OK):**
```json
{
  "deactivated": true,
  "active_sites": 2,
  "remaining_sites": 3,
  "message": "License deactivated for example.com"
}
```

---

#### `POST /api/v1/license/check`

Periodischer Heartbeat/Status-Check.

**Request:**
```json
{
  "license_key": "PDF$PRO#A1B2-C3D4@E5F6-G7H8!I9J0",
  "site_url": "https://example.com",
  "plugin_version": "1.2.11",
  "platform": "wordpress"
}
```

**Response (200 OK):**
```json
{
  "valid": true,
  "status": "active",
  "expires_at": "2027-02-28T00:00:00Z",
  "days_remaining": 365,
  "latest_version": "1.3.0",
  "update_available": true,
  "update_url": "https://pdfviewer.drossmedia.de/download/premium/1.3.0",
  "message": null,
  "checked_at": "2026-02-28T14:30:00Z"
}
```

**Hinweis-Nachrichten im `message`-Feld:**
- `"Your license expires in 14 days. Renew at https://pdfviewer.drossmedia.de"`
- `"Your license is in grace period. Features will be disabled on 2026-03-15."`
- `"A new version (1.3.0) is available. Update recommended."`

---

#### `GET /api/v1/health`

Health-Check für Monitoring.

**Response (200 OK):**
```json
{
  "status": "ok",
  "version": "1.0.0",
  "database": "connected",
  "timestamp": "2026-02-28T14:30:00Z"
}
```

---

### 6.2 Webhook-Endpunkt

#### `POST /api/v1/webhook/stripe`

Empfängt Stripe-Webhook-Events. Kein JSON-Schema-Input (Stripe sendet eigenes Format).

**Verarbeitung:**

| Stripe Event | Aktion |
|-------------|--------|
| `checkout.session.completed` | Neue Lizenz erstellen, Schlüssel per E-Mail senden |
| `invoice.paid` | Bestehende Lizenz verlängern (`expires_at` + 365 Tage) |
| `customer.subscription.deleted` | Status → `inactive` setzen (nach Ablauf) |
| `customer.subscription.updated` | Plan-Update prüfen (Up-/Downgrade) |
| `invoice.payment_failed` | Audit-Log-Eintrag, ggf. E-Mail-Warnung |

**Sicherheit:**
- Stripe Webhook Signing Secret zur Signaturprüfung
- Idempotenz über `stripe_event_id` (doppelte Events ignorieren)

---

### 6.3 Dashboard API (Admin-intern)

Diese Endpunkte sind nur für den authentifizierten Admin zugänglich (JWT Cookie).

| Methode | Endpunkt | Beschreibung |
|---------|----------|-------------|
| `POST` | `/api/admin/auth/login` | Admin-Login |
| `POST` | `/api/admin/auth/logout` | Admin-Logout |
| `GET` | `/api/admin/dashboard/stats` | KPI-Übersicht |
| `GET` | `/api/admin/licenses` | Lizenzen auflisten (paginiert, filterbar) |
| `GET` | `/api/admin/licenses/:id` | Lizenz-Details |
| `POST` | `/api/admin/licenses` | Lizenz manuell erstellen |
| `PATCH` | `/api/admin/licenses/:id` | Lizenz bearbeiten |
| `DELETE` | `/api/admin/licenses/:id` | Lizenz löschen |
| `POST` | `/api/admin/licenses/:id/revoke` | Lizenz sperren |
| `POST` | `/api/admin/licenses/:id/extend` | Lizenz verlängern |
| `GET` | `/api/admin/installations` | Installationen auflisten |
| `GET` | `/api/admin/installations/:id` | Installation-Details |
| `GET` | `/api/admin/stats/geo` | Geo-Verteilung |
| `GET` | `/api/admin/stats/platforms` | Plattform-Verteilung |
| `GET` | `/api/admin/stats/versions` | Versions-Verteilung |
| `GET` | `/api/admin/stats/revenue` | Umsatz-Statistiken (Stripe) |
| `GET` | `/api/admin/stats/timeline` | Zeitreihen-Daten |
| `GET` | `/api/admin/audit-log` | Audit-Log auflisten |
| `GET` | `/api/admin/stripe/products` | Stripe-Produkt-Mapping |
| `POST` | `/api/admin/stripe/products` | Produkt-Mapping erstellen |
| `PUT` | `/api/admin/stripe/products/:id` | Mapping bearbeiten |
| `GET` | `/api/admin/export/:type` | CSV-Export (licenses, installations, stats) |

---

## 7. Technologie-Stack

### 7.1 Backend & Frontend

| Komponente | Technologie | Begründung |
|------------|-------------|------------|
| **CMS** | WordPress 6.x | Bewährtes CMS, gleicher Stack wie Shop (pdfviewer.drossmedia.de) |
| **Sprache** | PHP 8.0+ | Standard WordPress-Entwicklung |
| **Datenbank** | MySQL 8.0 / MariaDB 10.6+ | WordPress-Standard, einfaches Hosting |
| **Plugin** | Custom WordPress Plugin (`pdf-license-manager`) | Eigene Admin-Seiten, REST API, DB-Tabellen |
| **Admin UI** | WordPress Admin + Custom CSS | Native WP-Admin-Integration, keine zusätzliche UI-Bibliothek |
| **Auth** | WordPress-eigenes Login-System | `manage_options` Capability für Admin-Zugriff |
| **E-Mail** | `wp_mail()` + SMTP Plugin | WordPress-Standard, SMTP über Plugin konfigurierbar |
| **Rate Limiting** | WordPress Transients API | Einfach, keine externen Abhängigkeiten |
| **Geo-IP** | MaxMind GeoLite2 + `maxmind-db/reader` (PHP) | Lokale IP-Geolokalisierung, kein externer API-Call |
| **Payments** | Stripe direkt (Webhook-Signatur-Verifikation in PHP) | Kein WooCommerce nötig, schlanke Stripe-Integration |
| **Validierung** | WordPress Sanitization API (`sanitize_text_field`, `absint`, etc.) | Bewährte WordPress-Sicherheitsfunktionen |

### 7.2 Infrastruktur

| Komponente | Empfehlung | Begründung |
|------------|------------|------------|
| **Hosting** | Hetzner Cloud oder Managed WordPress (Deutschland) | DSGVO-konform, EU-Standort |
| **Datenbank** | MySQL 8.0 (im WordPress-Hosting enthalten) | Standard, keine separate DB nötig |
| **Domain** | `pdfviewer.drossmedia.de` | Separate WordPress-Installation als Subdomain |
| **SSL** | Let's Encrypt (automatisch) | Kostenlos, automatische Erneuerung |
| **Monitoring** | Uptime-Kuma (Self-Hosted) oder Betterstack | Health-Check Monitoring |
| **Backups** | Automatisch täglich, 30 Tage Retention | Datensicherheit |

### 7.3 WordPress Plugin-Architektur

```
license-dashboard/                    (WordPress Plugin: pdf-license-manager)
├── pdf-license-manager.php           # Plugin-Hauptdatei, Bootstrap
├── includes/
│   ├── class-plm-database.php        # DB-Tabellen (dbDelta), Schema
│   ├── class-plm-license.php         # Key-Generator, Validierung, Hilfsfunktionen
│   ├── class-plm-api.php             # REST API (validate, activate, deactivate, check)
│   ├── class-plm-geoip.php           # MaxMind GeoLite2 Integration
│   ├── class-plm-stripe.php          # Stripe Webhook (ohne SDK, native PHP)
│   └── class-plm-admin.php           # Admin-Seiten, Form-Handler
├── admin/
│   ├── views/                        # PHP-Templates für Admin-Seiten
│   │   ├── dashboard.php             # KPI-Übersicht
│   │   ├── licenses.php              # Lizenz-Liste + Erstellen
│   │   ├── license-detail.php        # Einzelansicht + Aktionen
│   │   ├── installations.php         # Installations-Tabelle
│   │   ├── stats.php                 # Statistiken + Geo-Verteilung
│   │   ├── audit-log.php             # Audit-Log
│   │   └── settings.php              # Stripe-Mapping, System-Info
│   ├── css/admin.css                 # Admin-Styles
│   └── js/admin.js                   # Admin-Scripts
└── assets/                           # Statische Assets
```

### 7.4 MaxMind GeoLite2 Setup

| Aspekt | Details |
|--------|---------|
| **Datenbank** | GeoLite2-City (kostenlos, Registrierung erforderlich) |
| **Größe** | ~70 MB (unkomprimiert) |
| **Update** | Wöchentlich/Monatlich via `geoipupdate` CLI |
| **Integration** | `maxmind-db/reader` PHP Package (via Composer) oder PHP GeoIP Extension |
| **Speicherort** | `wp-content/uploads/plm/GeoLite2-City.mmdb` |
| **Genauigkeit** | Land: 99,8%, Stadt: ~75% (reicht für Dashboard) |
| **Lizenz** | Creative Commons Attribution-ShareAlike 4.0 |

### 7.5 Stripe-Integration (ohne WooCommerce)

Die Stripe-Integration erfolgt **direkt** ohne WooCommerce:

| Aspekt | Details |
|--------|---------|
| **Checkout** | Stripe Checkout Session (hosted) auf dem Shop (pdfviewer.drossmedia.de) |
| **Webhooks** | Empfang unter `pdfviewer.drossmedia.de/wp-json/plm/v1/webhook/stripe` |
| **Signatur** | Native PHP HMAC-SHA256 Verifikation (kein Stripe SDK nötig) |
| **Konfiguration** | `PLM_STRIPE_WEBHOOK_SECRET` in `wp-config.php` |
| **Produkt-Mapping** | Konfigurierbar im Dashboard unter Settings |

---

## 8. Phasenplan

### Phase 1: Foundation (Wochen 1–3)

| Aufgabe | Beschreibung | Priorität |
|---------|-------------|-----------|
| Projekt-Setup | WordPress-Installation, Custom Plugin, MySQL-Datenbank | MUSS |
| Datenbank-Schema | Alle Tabellen gem. Datenmodell (Kap. 5) anlegen | MUSS |
| Admin-Auth | Login-System für Dashboard | MUSS |
| License CRUD | Lizenzen erstellen, bearbeiten, löschen (Dashboard) | MUSS |
| License Key Generator | Automatische Schlüsselgenerierung im bestehenden Format | MUSS |

### Phase 2: Public API (Wochen 3–5)

| Aufgabe | Beschreibung | Priorität |
|---------|-------------|-----------|
| Validate-Endpunkt | `POST /license/validate` | MUSS |
| Activate-Endpunkt | `POST /license/activate` mit Site-Limit-Prüfung | MUSS |
| Deactivate-Endpunkt | `POST /license/deactivate` | MUSS |
| Check-Endpunkt | `POST /license/check` (Heartbeat) | MUSS |
| Rate Limiting | Request-Limitierung für alle öffentlichen Endpunkte | MUSS |
| Input-Validierung | WordPress Sanitization API für alle Requests | MUSS |
| Health-Check | `GET /health` Endpunkt | MUSS |

### Phase 3: Geo-IP & Installationen (Wochen 5–6)

| Aufgabe | Beschreibung | Priorität |
|---------|-------------|-----------|
| MaxMind Integration | GeoLite2-Datenbank einbinden, `maxmind-db/reader` (PHP) | MUSS |
| Geo-Daten bei Aktivierung | IP → Geo bei `/license/activate` | MUSS |
| Installations-Dashboard | Listenansicht aller Installationen | MUSS |
| Geo-Karte | Weltkarte mit Installationen pro Land | MUSS |
| IP-Anonymisierung | DSGVO-konforme IP-Verarbeitung | MUSS |

### Phase 4: Dashboard & Statistiken (Wochen 6–8)

| Aufgabe | Beschreibung | Priorität |
|---------|-------------|-----------|
| Dashboard Home | KPI-Karten, Diagramme, letzte Aktivität | MUSS |
| Lizenz-Verwaltung UI | Tabelle mit Filter, Einzelansicht, Aktionen | MUSS |
| Statistik-Seite | Zeitreihen, Plattform-Verteilung, Versions-Tracking | MUSS |
| Audit-Log UI | Chronologische Event-Ansicht | SOLL |
| CSV-Export | Export-Funktionalität für alle Datentypen | SOLL |

### Phase 5: Stripe Integration (Wochen 8–10)

| Aufgabe | Beschreibung | Priorität |
|---------|-------------|-----------|
| Stripe Webhook | Endpunkt + Signatur-Verifikation | MUSS |
| Auto-Lizenz-Generierung | Bei `checkout.session.completed` | MUSS |
| Produkt-Mapping | Dashboard-UI für Stripe-Product → License-Type | MUSS |
| Subscription-Handling | Verlängerung, Kündigung, Payment-Failed | MUSS |
| Umsatz-Dashboard | Stripe-Daten im Dashboard anzeigen | SOLL |

### Phase 6: Plugin-Anpassungen (Wochen 10–13)

| Aufgabe | Beschreibung | Priorität |
|---------|-------------|-----------|
| WordPress Plugin | Phone-Home bei Aktivierung, Heartbeat via WP-Cron | MUSS |
| Drupal Module | Phone-Home bei Aktivierung, Heartbeat via Cron | MUSS |
| React Package | Phone-Home bei Provider-Init, periodischer Check | MUSS |
| Graceful Degradation | Fallback-Logik bei API-Unerreichbarkeit | MUSS |
| Datenschutzhinweis | Transparenz-Text in allen Plugin-Lizenzseiten | MUSS |
| Update-Check | Versionsprüfung in API-Response nutzen | KANN |

### Phase 7: Testing & Go-Live (Wochen 13–14)

| Aufgabe | Beschreibung | Priorität |
|---------|-------------|-----------|
| Unit-Tests | API-Endpunkte, Services, Lizenz-Logik | MUSS |
| Integration-Tests | Stripe-Webhooks, Plugin-API-Kommunikation | MUSS |
| E2E-Tests | Dashboard-Workflows | SOLL |
| Security-Review | OWASP-Check, Penetration-Testing | SOLL |
| Deployment | Production-Setup, DNS, SSL | MUSS |
| Monitoring | Health-Check, Alerting | MUSS |
| Dokumentation | API-Docs, Plugin-Update-Guide | MUSS |

---

## 9. Akzeptanzkriterien

### 9.1 License API

| ID | Kriterium | Status |
|----|-----------|--------|
| AK-API-01 | Ein gültiger Lizenzschlüssel wird mit Status `active` und korrektem Plan validiert | ☐ |
| AK-API-02 | Ein abgelaufener Lizenzschlüssel wird mit Status `expired` und negativem `days_remaining` zurückgegeben | ☐ |
| AK-API-03 | Eine Lizenz innerhalb der 14-Tage Grace Period wird als `grace_period` mit Warnmeldung zurückgegeben | ☐ |
| AK-API-04 | Eine Aktivierung auf einer neuen Site bei erreichtem Site-Limit wird mit `403` und Fehlermeldung abgelehnt | ☐ |
| AK-API-05 | `localhost` / `.local` / `.test` Domains werden als `is_local` markiert und zählen nicht zum Site-Limit | ☐ |
| AK-API-06 | Eine Deaktivierung gibt den Site-Slot frei und nachfolgende Aktivierung auf neuer Domain ist möglich | ☐ |
| AK-API-07 | Rate Limiting greift bei mehr als 60 Requests/Minute pro IP (HTTP 429) | ☐ |
| AK-API-08 | API-Antwortzeiten liegen unter 200ms (p95) für Validate/Check | ☐ |
| AK-API-09 | Ein nicht existierender Lizenzschlüssel wird mit HTTP 404 beantwortet | ☐ |
| AK-API-10 | Heartbeat-Check aktualisiert `last_checked_at` und `plugin_version` | ☐ |

### 9.2 Dashboard

| ID | Kriterium | Status |
|----|-----------|--------|
| AK-DASH-01 | Admin kann sich einloggen und sieht die Dashboard-Übersicht mit KPIs | ☐ |
| AK-DASH-02 | Lizenzen können manuell erstellt werden mit automatischer Key-Generierung | ☐ |
| AK-DASH-03 | Lizenzliste ist filter- und sortierbar nach Status, Typ, Plan, Plattform | ☐ |
| AK-DASH-04 | Einzelansicht einer Lizenz zeigt alle zugehörigen Installationen | ☐ |
| AK-DASH-05 | Geo-IP-Weltkarte zeigt korrekte Verteilung der Installationen pro Land | ☐ |
| AK-DASH-06 | Plattform-Verteilung (WP/Drupal/React) wird korrekt als Diagramm dargestellt | ☐ |
| AK-DASH-07 | Plugin-Versions-Verteilung ist einsehbar | ☐ |
| AK-DASH-08 | CSV-Export für Lizenzen und Installationen funktioniert | ☐ |
| AK-DASH-09 | Audit-Log zeigt alle relevanten Events chronologisch | ☐ |
| AK-DASH-10 | Dashboard ist responsive und auf Tablet/Desktop nutzbar | ☐ |

### 9.3 Stripe-Integration

| ID | Kriterium | Status |
|----|-----------|--------|
| AK-STRIPE-01 | Bei erfolgreichem Checkout wird automatisch eine Lizenz erstellt | ☐ |
| AK-STRIPE-02 | Der Lizenzschlüssel wird per E-Mail an den Kunden gesendet | ☐ |
| AK-STRIPE-03 | Bei Subscription-Verlängerung (invoice.paid) wird `expires_at` um 365 Tage verlängert | ☐ |
| AK-STRIPE-04 | Doppelte Webhook-Events werden ignoriert (Idempotenz) | ☐ |
| AK-STRIPE-05 | Stripe-Produkt-Mapping ist im Dashboard konfigurierbar | ☐ |
| AK-STRIPE-06 | Umsatz-Statistiken werden korrekt im Dashboard angezeigt | ☐ |

### 9.4 Geo-IP

| ID | Kriterium | Status |
|----|-----------|--------|
| AK-GEO-01 | Bei Lizenzaktivierung wird das Land der Installation korrekt erkannt | ☐ |
| AK-GEO-02 | IP-Adressen werden nach Geo-Lookup anonymisiert (letztes Oktett = 0) | ☐ |
| AK-GEO-03 | Geo-Daten werden bei Heartbeat nur aktualisiert wenn >30 Tage alt | ☐ |
| AK-GEO-04 | Keine IP-Daten werden an externe Dienste gesendet | ☐ |

### 9.5 Plugin-Anpassungen

| ID | Kriterium | Status |
|----|-----------|--------|
| AK-PLUGIN-01 | WordPress-Plugin sendet Aktivierungs-Request bei Lizenz-Speicherung | ☐ |
| AK-PLUGIN-02 | Drupal-Modul sendet Aktivierungs-Request bei Lizenz-Speicherung | ☐ |
| AK-PLUGIN-03 | React-Package sendet Aktivierungs-Request bei Provider-Init mit License Key | ☐ |
| AK-PLUGIN-04 | Heartbeat wird alle 24h gesendet (WP-Cron / Drupal Cron / React Cache) | ☐ |
| AK-PLUGIN-05 | Bei API-Timeout/Fehler funktioniert Fallback auf lokale Validierung | ☐ |
| AK-PLUGIN-06 | Nach 30 Tagen ohne erfolgreichen API-Check zeigt Plugin "Unverified"-Warnung | ☐ |
| AK-PLUGIN-07 | Datenschutzhinweis ist auf der Lizenz-Seite jeder Plattform sichtbar | ☐ |
| AK-PLUGIN-08 | Plugin sendet nur technische Daten, keine Benutzer-/Inhaltsdaten | ☐ |

---

## Anhang A: Lizenzschlüssel-Format

### Bestehende Formate (beibehalten)

```
Premium:     PDF$PRO#XXXX-XXXX@XXXX-XXXX!XXXX
Pro+:        PDF$PRO+#XXXX-XXXX@XXXX-XXXX!XXXX
Unlimited:   PDF$UNLIMITED#XXXX@XXXX!XXXX
Development: PDF$DEV#XXXX-XXXX@XXXX!XXXX
```

Wobei `X` = alphanumerisches Zeichen (A-Z, 0-9), generiert via `random_bytes()` (PHP).

### Regex-Validierung

```regex
Premium:     ^PDF\$PRO#[A-Z0-9]{4}-[A-Z0-9]{4}@[A-Z0-9]{4}-[A-Z0-9]{4}![A-Z0-9]{4}$
Pro+:        ^PDF\$PRO\+#[A-Z0-9]{4}-[A-Z0-9]{4}@[A-Z0-9]{4}-[A-Z0-9]{4}![A-Z0-9]{4}$
Unlimited:   ^PDF\$UNLIMITED#[A-Z0-9]{4}@[A-Z0-9]{4}![A-Z0-9]{4}$
Development: ^PDF\$DEV#[A-Z0-9]{4}-[A-Z0-9]{4}@[A-Z0-9]{4}![A-Z0-9]{4}$
```

---

## Anhang B: Lokale Domain-Erkennung

Folgende Muster werden als lokale/Staging-Installationen behandelt und zählen nicht zum Site-Limit:

| Muster | Beispiele |
|--------|-----------|
| `localhost` | `http://localhost`, `http://localhost:3000` |
| `127.0.0.1` | `http://127.0.0.1:8080` |
| `*.local` | `mysite.local`, `dev.mysite.local` |
| `*.test` | `mysite.test` |
| `*.dev` (lokal) | `mysite.dev` (nur ohne öffentliche DNS-Auflösung) |
| `*.staging.*` | `staging.example.com`, `app.staging.example.com` |
| `*.stage.*` | `stage.example.com` |
| `10.x.x.x` | Private IP-Ranges |
| `192.168.x.x` | Private IP-Ranges |

---

## Anhang C: Glossar

| Begriff | Bedeutung |
|---------|-----------|
| **Heartbeat** | Periodischer API-Call vom Plugin zum Lizenzserver zur Status-Prüfung |
| **Phone-Home** | Kommunikation des Plugins mit dem zentralen Lizenzserver |
| **Grace Period** | 14-Tage-Übergangszeitraum nach Lizenz-Ablauf, in dem Features aktiv bleiben |
| **Site-Limit** | Maximale Anzahl gleichzeitig aktivierter Produktions-Domains pro Lizenz |
| **Activation** | Registrierung einer konkreten Site-URL für eine Lizenz |
| **MRR** | Monthly Recurring Revenue – monatlich wiederkehrender Umsatz |
| **ARR** | Annual Recurring Revenue – jährlich wiederkehrender Umsatz |
| **Churn Rate** | Anteil der Kunden, die ihre Lizenz nicht verlängern |

---

*Erstellt von Dross:Media – Version 1.0 – 28.02.2026*
