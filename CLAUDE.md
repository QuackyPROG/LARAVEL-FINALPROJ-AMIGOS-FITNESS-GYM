# AmigosFitnessGym — Claude Code Harness

## Stack
Laravel 12 / Livewire 3 + Flux UI / Supabase PostgreSQL / PayMongo (kirame/laravel-paymongo) / Pest / Pint

## Three-Agent Architecture
Planner → Generator → Evaluator. Each runs in a separate Claude session. No shared memory or context.

**Which agent am I?** Read your role file:
- Planner → `agents/planner.md`
- Generator → `agents/generator.md`
- Evaluator → `agents/evaluator.md`

**Current build state** → `artifacts/handoff_state.json`

## Agent Communication
Agents communicate via files in `artifacts/` only. One agent writes; the next reads and responds.

## Hard Rules
- **IMPORTANT:** Evaluator navigates the live app via Playwright — never scores static code
- **IMPORTANT:** Generator runs `php artisan test` and `./vendor/bin/pint --test` before every QA handoff
- Never stub a feature and mark a sprint passing
- Never commit to `main` — feature branch per sprint (`sprint/N`), merge only after QA passes
- Never write raw SQL — use Eloquent
- Never hardcode credentials — use `config()` backed by `.env`
