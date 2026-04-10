# AmigosFitnessGym — Production Deployment Checklist

**Stack:** Laravel 12 / Livewire 3 + Flux UI / Supabase PostgreSQL / PayMongo / Anthropic Claude

---

## Prerequisites

- PHP 8.3+ with extensions: `pdo_pgsql`, `redis` (or `database`), `gd` (optional — SVG QR code works without it), `mbstring`, `openssl`, `tokenizer`, `xml`, `ctype`, `json`
- Composer 2.x
- Node 20+ / npm
- A Supabase PostgreSQL database (connection pooler recommended)
- PayMongo account with live keys
- Anthropic API key
- SMTP provider (Mailgun, Postmark, SES, etc.)
- A process manager: Supervisor (for queue workers) + cron (for scheduler)

---

## Step 1 — Clone & Configure

```bash
git clone <repo-url> /var/www/amigosgym
cd /var/www/amigosgym
cp .env.example .env
```

Edit `.env` with production values:

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com
APP_KEY=          # generated in step 2

DB_CONNECTION=pgsql
DB_HOST=db.<ref>.supabase.co
DB_PORT=5432
DB_DATABASE=postgres
DB_USERNAME=postgres
DB_PASSWORD=<supabase-db-password>
DB_SSLMODE=require

PAYMONGO_PUBLIC_KEY=pk_live_...
PAYMONGO_SECRET_KEY=sk_live_...
PAYMONGO_WEBHOOK_SECRET=whsk_...

ANTHROPIC_API_KEY=sk-ant-...

QUEUE_CONNECTION=database     # or redis in production
CACHE_STORE=database          # or redis

MAIL_MAILER=smtp
MAIL_HOST=smtp.mailgun.org
MAIL_PORT=587
MAIL_USERNAME=<mailgun-user>
MAIL_PASSWORD=<mailgun-password>
MAIL_FROM_ADDRESS=noreply@amigosfitness.ph
MAIL_FROM_NAME="AmigosFitnessGym"

FILESYSTEM_DISK=local         # gov IDs stay local; use S3 for uploads in cloud
```

---

## Step 2 — Install Dependencies & Generate Key

```bash
composer install --no-dev --optimize-autoloader
npm ci && npm run build
php artisan key:generate
```

---

## Step 3 — Run Migrations

```bash
php artisan migrate --force
```

---

## Step 4 — Seed Production Data (first deploy only)

```bash
php artisan db:seed --class=ProductionSeeder   # creates admin user + default plans + site content
```

> Change the seeded admin password immediately after first login.

---

## Step 5 — Optimize

```bash
php artisan optimize          # caches config, routes, views, events
php artisan icons:cache       # Flux UI icon cache (if applicable)
```

---

## Step 6 — Storage

```bash
php artisan storage:link      # create public/storage symlink (if using public disk)
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

---

## Step 7 — Queue Worker (Supervisor)

Create `/etc/supervisor/conf.d/amigosgym-worker.conf`:

```ini
[program:amigosgym-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/amigosgym/artisan queue:work --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=/var/www/amigosgym/storage/logs/worker.log
stopwaitsecs=3600
```

```bash
supervisorctl reread && supervisorctl update && supervisorctl start amigosgym-worker:*
```

---

## Step 8 — Scheduler (Cron)

Add to `www-data` crontab (`crontab -u www-data -e`):

```cron
* * * * * cd /var/www/amigosgym && php artisan schedule:run >> /dev/null 2>&1
```

This runs the daily `memberships:send-expiry-warnings` command automatically.

---

## Step 9 — PayMongo Webhook

Register the webhook URL in PayMongo Dashboard:

```
https://yourdomain.com/api/webhook/paymongo
```

Events to subscribe:
- `payment.paid`
- `payment.failed`

Copy the webhook secret to `PAYMONGO_WEBHOOK_SECRET` in `.env`, then run `php artisan optimize` again.

---

## Step 10 — Verify

```bash
# Health checks
curl -f https://yourdomain.com/                     # public homepage
curl -f https://yourdomain.com/login                # login page
php artisan queue:monitor                           # queue workers running
php artisan schedule:list                           # verify scheduler entries

# Test email
php artisan tinker --execute="Mail::raw('Test', fn(\$m) => \$m->to('you@example.com'));"
```

---

## Post-Deploy Checklist

- [ ] `APP_DEBUG=false` in production `.env`
- [ ] Admin password changed from seeder default
- [ ] PayMongo webhook registered with correct URL + events
- [ ] Test PayMongo payment end-to-end with test keys before switching to live
- [ ] SSL certificate active (HTTPS enforced)
- [ ] Queue worker running via Supervisor
- [ ] Cron scheduler active
- [ ] Log rotation configured (`/etc/logrotate.d/amigosgym`)
- [ ] Supabase connection pooler enabled (pgBouncer) for production load
- [ ] Backup strategy configured for Supabase database

---

## Rollback

```bash
git checkout <previous-tag>
composer install --no-dev --optimize-autoloader
php artisan migrate:rollback   # only if migration needs reverting
php artisan optimize
supervisorctl restart amigosgym-worker:*
```
