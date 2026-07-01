# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project overview

Boat rental management system: a Laravel 12 backend paired with a Vue 3 / Vuetify 3 single-page-app frontend built on the **Vuexy** admin template (`vuexy-vuejs-laravel-admin-template`, see `package.json`). The domain is boat rentals — branches, boats, pricing tiers, customers, rentals, invoices, and signatures.

The repo is early-stage: the database schema and Eloquent models for the full domain exist, but almost no backend controllers/API routes or frontend admin pages have been built yet (see "Current state" below).

## Commands

### Backend (PHP / Laravel)
- Install deps: `composer install`
- Copy env + generate key (first-time setup): `cp .env.example .env && php artisan key:generate`
- Run all services for local dev (server + queue listener + log tailer + Vite, via `concurrently`): `composer run dev`
- Run PHP server alone: `php artisan serve`
- Run tests: `composer test` (clears config cache, then `php artisan test`)
- Run a single test: `php artisan test --filter=TestName` or target a file: `php artisan test tests/Feature/ExampleTest.php`
- Format PHP (Laravel Pint is a dev dependency): `./vendor/bin/pint`
- Run migrations: `php artisan migrate`

### Frontend (Vue / Vite)
- Install deps: `bun install` (repo also has a `pnpm-lock.yaml` alongside `bun.lock` — pick one lockfile intentionally and don't let both drift; the README and `postinstall` hook assume Bun)
- Dev server: `bun run dev` (Vite on :5173)
- Production build: `bun run build`
- Lint + autofix JS/Vue: `npm run lint` (ESLint over `.ts,.js,.cjs,.vue,.tsx,.jsx`)
- Lint CSS/SCSS: no npm script is wired up; run directly, e.g. `npx stylelint "resources/**/*.{scss,vue}"`
- Regenerate iconify icon bundle (also runs automatically post-install): `npm run build:icons`

Default local URLs: backend `http://127.0.0.1:8000`, Vite dev server `http://localhost:5173`.

## Architecture

### Backend is a thin, mostly-unbuilt Laravel shell
- `routes/web.php` has a single catch-all route (`Route::get('{any?}', ...)`) that returns `view('application')` for literally every path — there is no server-rendered routing and **no `routes/api.php`**. The Vue app owns all client-side routing.
- `app/Http/Controllers/Controller.php` is the only controller in the app — no domain controllers or API endpoints exist yet. When adding backend functionality, you're building it from scratch, not extending existing patterns.
- `app/Providers/AppServiceProvider.php` is empty/default.

### Domain model graph (`app/Models`)
`Branch` is the tenant root of the business and `hasMany` on almost everything: `Boat`, `Addon`, `PricingTier`, `Rental`, `Invoice`, `InvoicePayment`, `CompanySetting`, `CommunicationSetting`, `SignatureSetting`.

- `Boat` belongsTo `BoatType` (via `type_id`) and `Branch`; hasMany `BoatImage`, `BoatPricingTier`, `Rental`.
- `PricingTier` / `BoatPricingTier` is a many-to-many-style join between boats and pricing tiers (per-branch pricing tiers applied to specific boats).
- `Rental` belongsTo `Customer`, `Boat`, `Branch`, `Invoice`; hasMany `RentalAddon`, `Signature`.
- `Invoice` belongsTo `Customer`, `Rental`, `Branch`; hasMany `InvoiceLineItem`, `InvoicePayment`, `Signature`.
- `Signature` belongsTo `Rental`, `Invoice`, `Customer` (e-signature capture tied to any of the three).
- Settings models (`CompanySetting`, `CommunicationSetting`, `SignatureSetting`) are per-branch configuration.

All models use PHP 8 `casts()` methods (not the older `$casts` property) for attribute casting.

### Database (`database/migrations`, SQLite by default)
- 22 sequential migrations build the schema described above, plus later migrations that patch earlier design issues: `..._000020_add_performance_indexes`, `..._000021_fix_rentals_invoice_id_foreign_key`, `..._000022_cleanup_json_fields_optional`.
- `database/OPTIMIZATION_ANALYSIS.md` and `database/OPTIMIZATION_GUIDE.md` (written in Vietnamese) document known/ongoing schema debt — missing composite indexes for hot query paths (availability checks on `rentals(boat_id, start_time, end_time)`, branch dashboards, invoice reports), and legacy JSON columns (`boats.prices_json`, `rentals.addons_json`) that duplicate the normalized `boat_pricing_tiers` / `rental_addons` tables and should eventually be removed. Check these docs before altering indexes or those JSON columns so you don't redo already-analyzed work.
- Default connection is `sqlite` (`.env.example`); `compose.yaml` also defines a full Laravel Sail stack (MySQL, Redis, Mailpit, Selenium) for anyone who wants a containerized setup instead.

### Frontend is the stock Vuexy template, mostly unmodified
- Routing is file-based via `unplugin-vue-router`, scanning `resources/js/pages/**`. Currently that folder only contains the template's stock pages (`index.vue`, `login.vue`, `second-page.vue`, `[...error].vue`) — no boat/rental admin screens have been built yet.
- Layouts are auto-applied from `resources/js/layouts/` via `vite-plugin-vue-meta-layouts` (default layout: `default`).
- Heavy auto-import setup: `unplugin-auto-import` (Vue, Vue Router, VueUse, vue-i18n, Pinia, plus everything under `resources/js/@core/utils`, `@core/composable`, `composables/`, `utils/`) and `unplugin-vue-components` (auto-registers components from `@core/components`, `views/demos`, `components/`). This means most composables/components are used without explicit `import` statements — check `auto-imports.d.ts` / `components.d.ts` (both generated, don't hand-edit) if unsure whether something is auto-available.
- `resources/js/@core` holds the reusable template "core" (framework-agnostic-ish UI kit); `resources/js/@layouts` holds the layout system; app-specific code goes in `resources/js/{components,composables,pages,views,navigation,utils}`.
- Path aliases (`@`, `@core`, `@layouts`, `@themeConfig`, `@images`, `@styles`, `@validators`, `@db`, `@api-utils`, `@core-scss`) are declared in **both** `vite.config.js` and `jsconfig.json` — update both when adding a new alias.
- `@db` and `@api-utils` point at `resources/js/plugins/fake-api/...`, which **does not exist** in this repo (the upstream template's MSW mock-API layer was stripped out, but the aliases/`msw` devDependency were left in place). Don't assume a fake API layer is available; real API calls should go through `VITE_API_BASE_URL` (declared but unset in `.env.example`).
- State management is Pinia (`resources/js/plugins/2.pinia.js`); auth/permissions tooling includes `@casl/ability` + `@casl/vue`.

### Code style (enforced by ESLint config, not just convention)
- No semicolons (`semi: never`), single quotes, 2-space indent, trailing commas on multiline, `arrow-parens: as-needed`, `camelCase` required.
- PHP formatting follows Laravel Pint defaults (no custom `pint.json`).
