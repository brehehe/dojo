# Agents Guide — Dojo (Laravel Kempo Tournament Management)

## Project Overview
- **Stack**: PHP 8.3, Laravel 13, Livewire 3, Volt 1, Inertia 3 (Svelte 5), Tailwind 4, Pest 4
- **Purpose**: Tournament management for Kempo martial arts — scoring, brackets, referee assignment, real-time monitors
- **Key domains**: Athletes, Contingents, Registrations, MatchNumbers, Courts, Embu (kata) scoring, Randori (sparring) brackets

## Architecture
- **Controllers**: `app/Http/Controllers/` — Monitor, ScoringDashboard, EmbuScoring, RandoriScoring, RefereeAssignment, Timer, RefereeScoring
- **Livewire/Volt**: `app/Livewire/Admin/` — Most admin pages use Livewire components  
- **Svelte**: `resources/js/` — Monitor and scoring dashboards use Svelte via Inertia
- **Services**: `app/Services/BracketService.php` — Bracket logic for randori double-elimination
- **Policies**: `app/Policies/` — Role-based authorization (admin, koordinator, panitera, contingent)
- **Form Requests**: `app/Http/Requests/` — Validation for ALL scoring API endpoints

## Key Conventions
- Admin routes: `routes/web.php` prefix `admin.`
- Scoring API: `/admin/api/scoring/*` — uses Svelte frontend, returns JSON
- Monitor API: `/api/svelte-monitor/*` — poll-based state endpoints
- Referee scoring: `/admin/referee/scoring/*` — uses Svelte, returns JSON
- Roles: `admin`, `koordinator`, `panitera`, `contingent` (via spatie/laravel-permission)
- Language: UI text is in Indonesian (Bahasa Indonesia)

## Testing
- Run `composer test` or `php artisan test`
- Run `composer lint:check` for Pint code style checks
- Framework: Pest 4

## Database
- Migrations in `database/migrations/`
- Key indexes added in `add_performance_indexes_to_tables` migration
- Cache driver: file (should be Redis in production)
- Session: file (should be Redis/database in production)

## Common Tasks
- **Add a new Livewire admin page**: Create in `app/Livewire/Admin/`, register route in `routes/web.php` under admin prefix
- **Add scoring endpoint**: Create Form Request in `app/Http/Requests/`, add method to appropriate controller, register route
- **Add monitor API**: Add method to MonitorController, register in svelte-monitor API route group
