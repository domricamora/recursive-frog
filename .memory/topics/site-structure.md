# Site Structure

- 2026-09-30 — There is no `/pricing` route; a 404 there is a wrong guess, not a broken site. Tiers live at `/services` and `/services/{tier}` (`/services/recursive-3`, `-5`, `-7`).
- 2026-09-30 — The tier model is not `App\Models\Tier`. Don't guess namespaces in `tinker`; take real URLs from `sitemap.xml`, which is the reliable source of truth for verifying every page.
- 2026-09-30 — Full GET route set: `/`, `/about`, `/contact`, `/contact/thank-you`, `/faq`, `/how-it-works`, `/services`, `/services/{tier}`, `/work`, `/work/{project}`, `/robots.txt`, `/sitemap.xml`, `/storage/{path}`.
- 2026-09-30 — Homepage intentionally shows three team cards; Nick is the About-page technical specialist. Team cards centre via auto margins that collapse when the rail overflows. The CSS edge fade sits at a `1070px` breakpoint measured against 1071px overflow — re-measure and update it if card count or width changes.
- 2026-09-30 — Security follow-up still outstanding: production database and admin passwords were disclosed in chat and must be rotated to distinct values, then server config updated and login/database access re-verified.
