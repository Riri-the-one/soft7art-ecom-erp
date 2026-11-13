# Testing and validation

## Automated suite

Run `php vendor/bin/phpunit`. phpunit.xml forces testing environment, SQLite and :memory:, with DB_URL cleared. Tests supply an ephemeral test-only key and disable Vite integration. No real .env or existing database is required. RefreshDatabase only recreates in-memory tables.

Local result: **40 tests, 134 assertions**, PHP 8.2.12, PHPUnit 11.5.58. Coverage includes Breeze login/logout/reset/profile, disabled registration, anonymous access, role denials, product create/update/delete and validation, historical product protection, server-side order totals, stock reservation/restoration, stale-model repeated confirmation, multi-line rollback, invalid reopening, tracking tokens, financial snapshots, delivered revenue, CRM rate, empty reports, migration preservation and CSV formula neutralization.

This is scenario coverage, not measured line coverage. No coverage percentage is claimed. CSRF middleware is supplied by Laravel and disabled by its HTTP testing harness; the real-browser login exercises CSRF. SQLite tests do not prove simultaneous MySQL row-lock correctness.

## Other checks

```bash
composer validate --strict
php vendor/bin/pint --test app routes database tests
npm ci
npm run build
composer audit
npm audit
git diff --check
```

The local dependency update resolved reported advisories within existing version constraints. Dependency audit results are time-sensitive; run them again when reviewing the PR.

## Real browser reproduction

On Windows with Edge installed, from the repository root:

```powershell
npm.cmd install --prefix .audit --no-save playwright
node tools/browser-check.cjs
```

The script creates a NEW timestamped SQLite file under ignored .audit, sets connection variables only in its child processes, migrates/seeds fictional data, generates a temporary application key/password, serves localhost:8127 and drives headless Edge. It closes the browser/server afterward. It never loads an existing business database. Do not run with a cached Laravel production configuration.

Captures are written to docs/screenshots; structured results are written to .audit/browser-results.json. It checks login, six main pages, five pages at 320/390/768/1440 px, JavaScript errors and AJAX product search. Screenshots contain fictional demo data only. Browser mutation workflows, all keyboard/screen-reader interactions, SMTP delivery and simultaneous database connections are not exhaustively covered.

## CI

.github/workflows/ci.yml runs on pushes to main/feat branches and pull requests to main, using PHP 8.2, Node 22 and SQLite in memory. It installs locked dependencies, builds assets, checks Pint, runs PHPUnit and audits dependencies. Remote status must be read from the actual GitHub Actions run; local success alone is not CI success.
