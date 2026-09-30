# Deploy And Verify

- 2026-09-30 — Production: `https://recursivefrog.deskpulse.click`, ssh/scp alias `rf-live`, app root `~/public_html/recursivefrog.deskpulse.click`. Deploy a changed blade file, then `php artisan view:clear` on the server.
- 2026-09-30 — `public/build` is gitignored, so any change to `resources/css` or `resources/js` requires `npm run build` on the server. Verified `public/build/assets` is present.
- 2026-09-30 — Before declaring an upload done, hash-compare repo files against production (`Get-FileHash` vs `sha256sum`) rather than trusting the scp result. This is what surfaced the `.htaccess` formatting defect.
- 2026-09-30 — Verification sweep that passed: 45 tests (163 assertions), all 17 sitemap URLs 200, `/.env`, `/artisan`, `/storage/logs/laravel.log`, `/composer.json`, `/vendor/autoload.php` all 403, live hero measured filling the fold at 1440x900 and 390x844.
- 2026-09-30 — PowerShell/SSH `cat` mangles UTF-8 on capture (em-dash rendered as mojibake). Use `scp` to move server files locally when byte fidelity matters; only the transport was wrong, not the file.
