# Parallel Rebuild: Smart Perkemi → Full TypeScript (Bun + Elysia + SvelteKit 5)

## Context

The current app is a Laravel 13 + Livewire (129 components) + Inertia/Svelte 5 (12 pages) tournament management and live-scoring system. The client complains it "feels like a web form, not an app." Investigation found: the live scoring/monitor screens work via HTTP polling (300ms–1500ms per client, no websockets, timer state in Laravel Cache), most UI is server-rendered Blade/Livewire forms, and maintaining PHP+Blade+Livewire+Volt+Inertia+Svelte is too much surface area to keep developing.

Decision (made with the user): **parallel rebuild in a new, separate repo** — Bun + Elysia backend, SvelteKit 5 (runes) frontend, WebSockets for realtime, all TypeScript. The old Laravel app stays live during the rebuild; cutover happens screen-by-screen. First proof-of-concept covers **both** auth + Contingent CRUD **and** the live scoring/monitor realtime slice. DB engine/schema are open to redesign (user confirmed). User explicitly chose Elysia over Hono/NestJS/AdonisJS.

Known flaws in the current app that the rebuild fixes by construction (NOT to carry over):
- RBAC roles are never enforced at route level (only `auth` middleware; roles just pick post-login redirects).
- A hardcoded backdoor password exists in `app/Livewire/Auth/NewLoginIndex.php` (user chose to leave it in the Laravel app for now; it must not exist in the new system).

## Stack decisions

| Concern | Choice | Rationale |
|---|---|---|
| Monorepo | Turborepo + Bun workspaces | cached parallel dev/build across api/web |
| Backend | **Elysia (pinned exact version)** on Bun | best Bun-native DX, Eden Treaty end-to-end types, native WS integration; pin + lockfile to mitigate its breaking-change history — upgrades are deliberate, never `^` |
| ORM / DB | **Drizzle + PostgreSQL** | native on Bun, plain-SQL migrations, JSONB for `bracket_node`/`metadata`/draft data; no Prisma engine/codegen friction |
| Auth | **Server-side sessions** (opaque token in HttpOnly cookie, `sessions` table) | instant revocation (stolen judge tablet), one identity source reused by the WS upgrade handshake; passwords via `Bun.password.hash` (argon2id) |
| RBAC | roles → permissions (many-to-many); `requirePermission('resource.action')` as an Elysia **macro** (e.g. `.get('/contingents', h, { permission: 'contingent.read' })`) resolving session→user→permissions in a `derive` | direct fix for the unenforced-roles bug; permission constants live in `packages/shared/permissions.ts` |
| REST types | **Eden Treaty** (`treaty<App>`) — web imports api's exported `App` *type* only, zero runtime import | end-to-end inference incl. params/query/body, no codegen |
| Validation & WS types | **TypeBox** (Elysia's native `t.*`) everywhere — route schemas inline, WS message envelope as a `t.Union` of discriminated variants in `packages/shared/ws-events.ts` | one validation library across HTTP and WS; Elysia validates WS `body` natively against the schema; `Static<typeof Schema>` gives client types |
| Realtime | Elysia native `.ws('/ws/court/:courtId', ...)` on Bun WS pub/sub: room per court (`court:{id}`) via `ws.subscribe()` + `monitor:global`; mutations run through shared service fns (same ones REST uses), then `app.server.publish()` | one authorization/validation path for HTTP and WS |
| Timer | **In-memory** `Map<courtId, TimerState>` in the API process (not DB); every change broadcasts `{status, elapsedMs, startedAtMs, countdownEndMs, serverTimeMs}`; clients keep a rAF-driven local clock corrected by `drift = serverTimeMs - Date.now()`, resynced on each push and on reconnect via `timer.snapshot` | push-model translation of today's Cache+poll+drift design |

## New repo layout

```
smart-perkemi-ts/
├── apps/api/src/
│   ├── index.ts                 # Elysia entry; exports `type App` for Eden
│   ├── routes/{auth,contingents,courts,scoring}.ts   # Elysia plugin per module
│   ├── ws/{court-ws.ts,protocol.ts}                  # .ws() route + TypeBox message union
│   ├── db/{schema.ts,client.ts,migrations/}
│   ├── auth/{session.ts,rbac.ts}                     # session derive + permission macro
│   └── services/{timer.ts,embu-scoring.ts,randori-scoring.ts}
├── apps/web/src/
│   ├── routes/ (auth)/login, (admin)/admin/contingents[...], (scoring)/scoring/{embu,randori}/[matchId], (public)/monitor/court/[courtId]
│   ├── lib/api.ts               # treaty<App>
│   ├── lib/ws/court-socket.svelte.ts   # runes class: $state timer/activeMatch/connectionStatus; Map-cached per courtId
│   └── hooks.server.ts          # session cookie → locals.user; +layout.server.ts gates per route group
└── packages/shared/src/{ws-events.ts,permissions.ts}  # TypeBox schemas + permission constants
```

## POC schema (Postgres/Drizzle)

- Auth: `users` (email, password_hash, referee_id?, contingent_id?), `roles`, `permissions`, `role_permissions`, `user_roles`, `sessions`.
- CRUD slice: `contingents` (fields as today: name, leader_name, leader_phone, email, address, kab_kota, user_id?), `athletes` (minimal), `athlete_contingent_history`.
- Scoring: `courts` (with `active_match_id/active_drawing_id/active_registration_id/active_bracket_node` pointers — kept, they give freshly-loading screens correct initial state via REST GET before WS join), `match_numbers` (`draft_type` enum embu|randori), `drawings`, `registrations`, `embu_scores` (judge_1..5, total, rank, tiebreak), `randori_match_results` (bracket_node_index, winner, score_red/blue, bracket_node jsonb), `referees`, `active_court_referees`.
- Deliberately deferred (no architectural risk): merges, tournament_results, embu_champions, randori_judge_scores, referee_score_details/signatures, full scheduling. Timer state intentionally has **no table**.

## Build order (each phase independently demoable)

0. **Scaffold** — Turborepo skeleton; Elysia `/health` route consumed via `treaty<App>` from a SvelteKit page (proves Eden type wiring). Pin exact Elysia version.
1. **Auth + RBAC** — login → session cookie; session `derive` + `permission` macro; protected `/admin` layout; test asserting a `wasit` user gets 403 on a `contingent.write` route.
2. **Contingent CRUD** — permission-gated REST (TypeBox-validated) + admin list/form pages (validates the boring-CRUD slice end to end).
3. **Courts/matches + WS echo** — tables + `.ws()` room join/leave + trivial broadcast between two tabs; session-authed WS upgrade; reconnect w/ exponential backoff + snapshot-on-rejoin built here as a first-class requirement.
4. **Timer engine** — highest-risk piece: in-memory state, start/pause/stop/countdown, `timer.changed` broadcast, client drift reconciliation + rAF interpolation; MonitorTimer-equivalent ticking across tabs. Test: kill the WS mid-run, confirm re-anchor.
5. **Embu scoring** — 5-judge decimal submit (referee-gated, row-level check vs `active_court_referees`), `embu.score.updated` broadcast, live monitor.
6. **Randori scoring** — red/blue entry, winner determination, `randori.result.updated`.
7. **Activate match / clear court** — PanggilDrawing-equivalent updating `courts.active_*` + `court.active_match.changed` broadcast.

Phases 1–2 and 3–7 can run as parallel workstreams (shared dependency: auth only).

## Key risks (flagged, not blockers for POC)

- **Elysia breaking changes** — pin exact version + lockfile committed; upgrade deliberately between tournaments, never mid-season. Eden Treaty and `.ws()` are the APIs most historically churned.
- **Reconnect/resync** under spotty venue wifi — snapshot-on-rejoin is mandatory, built in Phase 3.
- **Single-process in-memory timers** — restart mid-tournament loses running timers; acceptable for POC, production needs sticky single-instance deploy or Redis-backed timer state.
- **Referee offline input** — score entry should queue mutations with idempotency keys and retry on reconnect; not in POC happy path, explicitly a known gap.
- **Deployment target** must support Bun + long-lived WS (Fly.io/Railway/VM — not serverless/edge). Elysia locks the backend to Bun; acceptable, user chose it knowingly. Decide host before Phase 3.
- **Permission list drift** — enforce `resource.action` naming with `packages/shared/permissions.ts` as the single enumerated source from Phase 1.

## Repo/branch logistics

- The rebuild lives in a **new GitHub repo** (user's choice). Creating it needs the user to name it (suggest `smart-perkemi-ts`) and grant access; I can create it via the GitHub MCP `create_repository` tool or the user creates it and adds it to this session via `add_repo`.
- This plan document itself gets committed to the existing repo's designated branch `claude/nextjs-vs-sveltekit-refactor-1l6z32` (e.g. as `docs/rebuild-plan.md`) so the decision record lives with the current codebase.
- The Laravel app is untouched (including the backdoor, per the user's explicit choice).

## Verification

- Phase 1: automated test — non-admin session gets 403 on a `contingent.write` route; login/logout flow in browser.
- Phase 2: create/edit/delete a contingent through the UI; verify permission gating with a second, low-privilege user.
- Phase 3–4: two browser tabs on the same court; start timer in one, verify the other ticks smoothly and survives a forced WS disconnect/reconnect with correct re-anchoring.
- Phase 5–7: referee tab submits scores → monitor tab updates live without reload; announcer tab activates/clears a match → both screens follow.
