# PDF License Dashboard

License management dashboard for **PDF Embed & SEO Optimize** (WordPress, Drupal, React/Next.js).

## Tech Stack

- **Framework:** Next.js 15 (App Router)
- **Database:** PostgreSQL 16 + Prisma ORM
- **Styling:** Tailwind CSS 4
- **Auth:** JWT (HttpOnly Cookie)
- **Payments:** Stripe SDK
- **Geo-IP:** MaxMind GeoLite2 (local database)

## Setup

### 1. Install dependencies

```bash
npm install
```

### 2. Configure environment

```bash
cp .env.example .env
# Edit .env with your database URL, Stripe keys, etc.
```

### 3. Generate admin password hash

```bash
node -e "const bcrypt = require('bcryptjs'); bcrypt.hash('your-password', 12).then(h => console.log(h))"
```

Add the hash to `ADMIN_PASSWORD_HASH` in `.env`.

### 4. Setup database

```bash
npx prisma db push    # Create tables
npx prisma generate   # Generate client
```

### 5. Setup MaxMind GeoLite2

1. Register at [maxmind.com](https://www.maxmind.com/en/geolite2/signup)
2. Download GeoLite2-City.mmdb
3. Place in `./data/GeoLite2-City.mmdb`

### 6. Run

```bash
npm run dev       # Development
npm run build     # Production build
npm run start     # Production server
```

## Public API Endpoints

| Method | Endpoint | Description |
|--------|----------|-------------|
| `POST` | `/api/v1/license/validate` | Validate a license key |
| `POST` | `/api/v1/license/activate` | Activate license for a site |
| `POST` | `/api/v1/license/deactivate` | Deactivate license for a site |
| `POST` | `/api/v1/license/check` | Heartbeat / status check |
| `POST` | `/api/v1/webhook/stripe` | Stripe webhook receiver |
| `GET` | `/api/v1/health` | Health check |

## Hosting

Deploy to `dashboard.pdfviewer.drossmedia.de` with:
- Hetzner Cloud (EU/Germany) or Vercel
- Managed PostgreSQL
- Let's Encrypt SSL (automatic)
