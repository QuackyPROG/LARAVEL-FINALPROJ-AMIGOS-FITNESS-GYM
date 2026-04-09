# AmigosFitnessGym — Three-Agent Laravel Harness

Built on the architecture from [Anthropic Engineering: Harness Design for Long-Running Apps](https://www.anthropic.com/engineering/harness-design-long-running-apps).

## What This Is

A Laravel 12 SaaS starter wired to a three-agent Claude harness. You give it a 1–4 sentence product idea; the harness builds it sprint by sprint, tests it with Playwright, and iterates until it passes quality thresholds.

## The Three Agents

| Agent | Role | Reads | Writes |
|---|---|---|---|
| **Planner** | Expands a prompt into a full product spec | User prompt, `skills/frontend-design/SKILL.md` | `artifacts/product_spec.md` |
| **Generator** | Builds the app sprint by sprint | `artifacts/product_spec.md`, `artifacts/sprint_contract.md` | Code, `artifacts/handoff_state.json`, `artifacts/qa_report.md` |
| **Evaluator** | Tests the live app via Playwright, grades it | `artifacts/sprint_contract.md`, `agents/criteria/*.md` | `artifacts/qa_report.md`, `artifacts/scores.json` |

Agents run in **separate Claude sessions**. They communicate only via files in `artifacts/`.

## Stack

- **Framework:** Laravel 12 (PHP 8.2+)
- **Frontend:** Livewire 3 + Blade + Tailwind CSS + Flux UI
- **Database:** Supabase PostgreSQL (standard `pgsql` driver)
- **Auth:** Laravel Sanctum
- **Payments:** PayMongo via `kirame/laravel-paymongo`
  - Supported: GCash, Maya, GrabPay, Card, QR Ph
- **Storage:** Supabase Storage REST API
- **Queue:** Laravel database driver
- **Testing:** Pest PHP 3
- **Code Style:** Pint (PSR-12)

## How to Run

### Option 1 — Full autonomous loop (Python harness)
```bash
# Install Python dependencies
pip install -r harness/requirements.txt

# Set your Anthropic API key
export ANTHROPIC_API_KEY=sk-ant-...

# Run the harness with your product idea
python harness/orchestrator.py "A fitness gym membership app for a Philippine gym. Members can book classes, track workouts, and pay with GCash or card via PayMongo."
```

### Option 2 — Manual slash commands (interactive)
```bash
# In a Claude Code session:
/plan   "your product idea"    # Planner generates product spec
/build                          # Generator proposes Sprint 1 contract
/qa                             # Evaluator tests and grades

# Or run the full loop:
/run-harness "your product idea"
```

## Setup

```bash
# 1. Copy .env
cp .env.example .env

# 2. Fill in your credentials:
#    - SUPABASE_DB_URL (from Supabase project settings)
#    - PAYMONGO_PUBLIC_KEY + PAYMONGO_SECRET_KEY (from PayMongo dashboard)
#    - ANTHROPIC_API_KEY

# 3. Generate app key
php artisan key:generate

# 4. Run migrations
php artisan migrate

# 5. Install frontend dependencies
npm install

# 6. Start dev server
php artisan serve
npm run dev
```

## PayMongo Test Credentials (for QA)

| Method | Test value |
|---|---|
| Card | `4343434343434345`, any future expiry, any 3-digit CVV |
| GCash | Sandbox mode — auto-approves, no real number needed |
| Maya | Sandbox mode — use PayMongo test dashboard |

Get test keys from: [PayMongo Dashboard → Developers](https://dashboard.paymongo.com/developers)

## Quality Thresholds

The evaluator grades each sprint on four criteria. **Any score below 7/10 fails the sprint.**

| Criterion | What it measures |
|---|---|
| `design_quality` | Coherent visual identity, custom colors, smooth Livewire UX |
| `originality` | Deliberate creative decisions, not a generic template |
| `functionality` | All features work end-to-end, PayMongo flows complete, webhooks update DB |
| `code_quality` | No raw SQL, thin controllers, passing Pest tests, passing Pint |

## Upgrading to a Newer Claude Model

When a new Claude model supersedes `claude-sonnet-4-6`:
1. Update the model string in `harness/orchestrator.py` and `harness/loop.py`
2. Update `config/services.php` → `anthropic.model`
3. Remove the context-management scaffolding in `harness/context_reset.py` if the new model's context window makes it unnecessary
4. Re-tune the evaluation rubrics in `agents/criteria/` if the new model scores differently

The harness complexity scales inversely with model capability — a more capable model needs less orchestration scaffolding.

## Project Structure

```
.
├── CLAUDE.md                    # Harness rules (read by all agents)
├── agents/
│   ├── planner.md               # Planner system prompt
│   ├── generator.md             # Generator system prompt
│   ├── evaluator.md             # Evaluator system prompt
│   ├── criteria/                # Grading rubrics
│   └── few_shot/                # Example passing/failing QA reports
├── artifacts/                   # Agent communication files
├── skills/
│   ├── frontend-design/         # UI design principles for planner
│   └── playwright-qa/           # QA flow guide for evaluator
├── harness/                     # Python orchestration
│   ├── orchestrator.py          # Entry point
│   ├── loop.py                  # Generator ↔ evaluator feedback loop
│   ├── sprint_manager.py        # Contract negotiation
│   └── context_reset.py        # State serialisation
├── app/
│   ├── Services/PayMongoService.php
│   ├── Events/Payment{Succeeded,Failed}.php
│   ├── Listeners/HandlePaymentSucceeded.php
│   └── Http/Controllers/PayMongoWebhookController.php
└── .claude/
    ├── settings.json            # Hooks: Pint on save, test on save
    └── commands/                # /plan /build /qa /run-harness
```
