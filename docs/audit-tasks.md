# Codebase Audit: Task List

> Generated from audit of `dojo` — Laravel 13 Kempo tournament management application.
> Last updated: 2026-06-13

---

## 1. Architecture & Structure

- [ ] Refactor `app/Http/Controllers/SvelteMonitorController.php` (2,957 lines, 42 methods) into smaller controllers:
  - [ ] `CourtController`
  - [ ] `ScoringStateController`
  - [ ] `RefereeAssignmentController`
  - [ ] `TimerController`
  - [ ] `EmbuScoringController`
  - [ ] `RandoriScoringController`
- [ ] Reduce route bloat in `routes/web.php` (451 lines); remove or consolidate duplicate/legacy `/new-*` and `/master/*` routes.
- [ ] Unify layout systems (`admin`, `premium`, `home-dashboard`, `tailwick`, `mobile-app`).
- [ ] Introduce Form Request classes for repeated validation logic.
- [ ] Introduce Service layer / Action classes for business logic currently living in controllers.

---

## 2. PHP / Laravel Best Practices

- [ ] Add explicit return types and typed parameters to all controller/Livewire methods where missing.
- [ ] Migrate inline validation in controllers to Form Request classes.
- [ ] Implement Authorization Policies for all resources, especially scoring and match management.
- [ ] Add proper authorization checks inside API endpoints beyond `auth` middleware.
- [ ] Review and throttle scoring/monitor API endpoints.
- [ ] Replace remaining `bcrypt('password')` hardcoded defaults with secure generated passwords.

---

## 3. Frontend Audit

- [ ] Remove 65+ `console.log/error/warn` statements from production Svelte code.
- [ ] Split oversized Svelte components (11,355 lines across ~14 files) into smaller reusable components.
- [ ] Standardize HTTP client usage: prefer Inertia helpers or a shared API client over raw `fetch()`.
- [ ] Ensure all Svelte `fetch()` calls include CSRF token handling consistently.
- [ ] Increase `#[Validate]` usage in Livewire components (currently only 6 found across 139 components).
- [ ] Add pagination to Livewire list components that currently use `->get()`.
- [ ] Review Tailwind 4 setup; ensure `tailwind.config.js` content paths match actual Svelte/Blade files.

---

## 4. Security

- [ ] Implement Laravel Policies for every model with admin/contingent ownership rules.
- [ ] Add request validation to all scoring API endpoints in `SvelteMonitorController`.
- [ ] Add route-level rate limiting for:
  - [ ] `/admin/api/scoring/*`
  - [ ] `/api/svelte-monitor/*`
  - [ ] `/admin/referee/scoring/*`
- [ ] Disable `APP_DEBUG=true` in production `.env` and update `.env.example` guidance.
- [ ] Remove or randomize hardcoded default passwords (e.g., `NewCourtIndex`).
- [ ] Audit file upload handling for athlete photos, identity documents, and BPJS cards.
- [ ] Review session/cookie configuration for production use (`SESSION_ENCRYPT`, secure cookies).

---

## 5. Database & Query Performance

- [ ] Add database indexes on frequently queried foreign keys:
  - [ ] `match_number_id`
  - [ ] `court_id`
  - [ ] `rundown_id`
  - [ ] `session_time_id`
  - [ ] `registration_id`
  - [ ] `drawing_match_number_id`
- [ ] Replace `MatchNumber::query()->update([...])` full-table updates with scoped/batched updates.
- [ ] Optimize heavy eager-loading chains in state endpoints; return only required fields using API Resources.
- [ ] Move ranking/aggregation logic from PHP `map()` collections into SQL queries or database views.
- [ ] Audit pivot tables and normalize schema where possible.
- [ ] Add foreign-key constraints where missing.

---

## 6. Caching & Real-Time Performance

- [ ] Cache read-heavy monitor state endpoints (court state, referee state, hasil state).
- [ ] Add `Cache-Control` / ETag headers for polling endpoints.
- [ ] Increase polling interval or migrate to WebSockets/Laravel Echo for monitor updates.
- [ ] Use Redis for cache/session in production instead of database.
- [ ] Profile `SvelteMonitorController` endpoints under tournament load.

---

## 7. Testing

- [ ] Add feature tests for `SvelteMonitorController` scoring workflows:
  - [ ] Timer control
  - [ ] Referee assignment
  - [ ] Embu scoring flow
  - [ ] Randori bracket progression
  - [ ] Champion confirmation
- [ ] Add tests for each Livewire admin component.
- [ ] Add unit tests beyond `ExampleTest.php`.
- [ ] Add authorization/policy tests.
- [ ] Set up CI to run `composer ci:check` on pull requests.

---

## 8. Documentation & Project Hygiene

- [ ] Create `AGENTS.md` with project-specific agent instructions.
- [ ] Document architecture decisions (Livewire vs Inertia/Svelte split).
- [ ] Add setup/deployment guide for production.
- [ ] Clean up remaining scratch files (`update_script.php`, `update_ui.py`, `update_view.php`, `generate_scoring_blades.php`) if no longer needed.
- [ ] Remove or archive `smart_perkemi_backup.sql` and `procbt-installation.html` from repository if they contain sensitive data.
- [ ] Run `vendor/bin/pint --dirty --format agent` across modified PHP files.

---

## Priority Legend

| Priority | Items |
|----------|-------|
| **High** | Controller refactor, authorization policies, API validation, rate limiting, test coverage |
| **Medium** | Frontend cleanup, pagination, database indexes, hardcoded passwords, layout unification |
| **Low** | Documentation, hygiene, Tailwind config review |

---

## Notes

- Application stack: PHP 8.3, Laravel 13, Livewire 3, Volt 1, Inertia 3, Svelte 5, Tailwind 4, Pest 4.
- Estimated code volume: ~201 PHP files in `app/`, ~213 Blade files, ~14 Svelte/JS files (~11,355 lines), 84 migrations, 40 test files.
- `git pull` completed at 2026-06-13: fast-forward `2fbcc59..de9bb33`, removed ~1,546 lines of legacy code.
