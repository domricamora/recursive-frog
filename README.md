# Recursive Frog

Marketing and lead-capture site for Recursive Frog, a digital systems studio
for aesthetics clinics in Cebu. Public marketing pages plus a password-guarded
admin panel for managing tiers, projects, FAQs, team profiles, leads and site
settings.

Built on Laravel 13 with Vite and Tailwind CSS 4.

---

## Deploying

    pwsh -File scripts/deploy.ps1

Always deploy with the script; never hand-copy files to the server. It reads the
site slug from `config/site.php` and refuses any other URL, folder or database,
backs up the live site and database, uploads the commit plus a locally built
`public/build` (gitignored, so it has to be built here), migrates, then clears
and rebuilds every cache and verifies the public pages return 200.

This account also publishes the `patrice`, `irish` and `rgehotel` sites in
sibling folders with their own databases. The slug check is the guard that stops
this repository being published over one of them — the patrice/irish forks
overwrote each other on 2026-09-29 because their deploy scripts were
byte-identical. **Never copy `deploy.ps1` between project folders, and never
point it at another site's folder or database.** If OpenSSH rejects your key
for being too open, pass `-IdentityFile` pointing at a copy with clean
permissions (the recipe is in the script's header).

---

## Where to log in

The admin panel lives at **`/admin`**. It is not linked from the public
navigation — go to the URL directly.

| | URL |
|---|---|
| **Production** | `https://recursivefrog.deskpulse.click/admin` |
| **Local** | `http://localhost:8000/admin` |

`/admin` redirects to `/admin/login` when you are signed out. After signing in
you land on the dashboard.

### Accounts

`database/seeders/AdminUserSeeder.php` creates four accounts, **all with the
placeholder password `password`**:

| Email | Role | Can reach |
|---|---|---|
| `nick@recursivefrog.ph` | `admin` | Everything, including Settings and Audit log |
| `sales@recursivefrog.ph` | `editor` | Tiers, projects, FAQs, team |
| `front@recursivefrog.ph` | `editor` | Tiers, projects, FAQs, team |
| `tech@recursivefrog.ph` | `editor` | Tiers, projects, FAQs, team |

> **Change these before the site goes live.** The seeded password is in version
> control. Set a real password for `nick@recursivefrog.ph` and delete the three
> editor accounts you do not need, then confirm the panel is no longer
> reachable with `password`.

### Admin sections

| Path | Purpose |
|---|---|
| `/admin` | Dashboard |
| `/admin/leads` | Contact submissions, notes, CSV export |
| `/admin/services` | Service tiers and their features |
| `/admin/projects` | Portfolio projects and case studies |
| `/admin/faqs` | FAQ entries |
| `/admin/team` | Team profiles |
| `/admin/settings` | Company details, contact info, analytics IDs — `admin` only |
| `/admin/audit-log` | Change history — `admin` only |

The login page is `noindex, nofollow` and `/admin` is disallowed in
`robots.txt`, so the panel is kept out of search results.

---

## About this project

The public site and the admin panel are the only two entry points. Everything a
visitor reads — tiers, work, FAQs, team, contact details — comes from MySQL via
the admin panel rather than from templates, so copy and content changes do not
require a deploy.

## Local setup

```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
```

Create the database, then:

```bash
php artisan migrate --seed
npm run dev          # or: npm run build
php artisan serve
```

Requires PHP 8.3+ and MySQL 8 / MariaDB 10.6+.

## Tests

```bash
php artisan test
```

There is also a route-level smoke check that boots the kernel and requests
every public route:

```bash
php smoke.php              # public routes
php smoke.php --admin      # public + authenticated admin routes
php smoke.php /contact     # a single route
```

## Deploying

The app expects the document root to point at `public/`. On hosts where that
cannot be configured (shared cPanel subdomain folders), the repository ships an
`.htaccess` strategy that serves the app from the domain root while keeping
`app/`, `config/`, `storage/`, `vendor/` and dotfiles unreachable.

After uploading:

```bash
composer install --no-dev --optimize-autoloader
php artisan migrate --force
php artisan db:seed --force
php artisan config:cache && php artisan route:cache && php artisan view:cache
```

`public/build/` is git-ignored, so compiled assets must be built and uploaded
separately — the server needs Node for `npm run build`.

## Configuration

Site-wide details (company name, tagline, email, phone, address, map embed)
are set under **Admin → Settings** and stored in `site_settings`. Those values
override the `SITE_*` environment defaults at runtime.

Optional integrations stay inert until their environment variables are set:

| Variable | Effect |
|---|---|
| `HUBSPOT_ACCESS_TOKEN`, `HUBSPOT_PORTAL_ID` | Push new leads to the CRM |
| `GA4_MEASUREMENT_ID`, `GTM_CONTAINER_ID` | Analytics and tag management |
| `MAIL_MAILER`, `MAIL_HOST`, `MAIL_USERNAME`, `MAIL_PASSWORD` | Deliver lead emails instead of logging them |

## Credits

Background film is from [Mixkit](https://mixkit.co/free-license-video/) under
the Mixkit Free License. See `MEDIA-CREDITS.md`.
