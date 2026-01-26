# Log Reader – Deploy & Verification

## Root cause (why local worked, server did not)

- **haruncpi/laravel-log-reader** only reads `laravel-*.log` (daily files). It ignores `laravel.log` (single).
- If the app uses **single** logging (`LOG_CHANNEL=single` or stack→single), logs go to `laravel.log`. The reader found no `laravel-*.log` and returned "No log available" → UI showed empty.
- On **server**, `LOG_CHANNEL` or config cache could differ from local (e.g. single vs daily, or cached wrong channel). Same behaviour: reader looked only at daily files, saw none, UI empty.
- **Permissions**: Server `storage/logs` might not be writable/readable, leading to no logs or "file not readable" (we now surface that in the UI).

## Files changed

| File | Change |
|------|--------|
| `app/Services/LogReaderService.php` | **New.** Lists and reads both `laravel.log` and `laravel-YYYY-MM-DD.log`; parses Monolog format with raw fallback; clear errors when file missing/unreadable. |
| `app/Http/Controllers/Modules/LogReaderController.php` | **New.** Replaces package controller for log-reader routes; uses `LogReaderService`; always returns consistent `data` shape on API errors. |
| `routes/web.php` | Log-reader routes added in module group; local-only `/module/log-reader-test` route for verification. |
| `LOG_READER_DEPLOY.md` | **New.** This file. |

**Unchanged:** `config/logging.php`, `config/laravel-log-reader.php`, `.env.example`. Logging remains stack→daily; `LOG_CHANNEL=stack`, `LOG_LEVEL=debug` in `.env.example`.

## Deploy steps (run on server after deploy)

```bash
php artisan config:clear
php artisan cache:clear
php artisan config:cache
```

Ensure `.env` has:

- `LOG_CHANNEL=stack` (or `single`; reader supports both)
- `LOG_LEVEL=debug` or `info` (as desired)
- Logs written to `storage/logs` (stack→daily or stack→single).

Fix permissions if needed:

```bash
chmod -R 775 storage/logs
# or your web server user, e.g. www-data:
chown -R www-data:www-data storage
```

## Verification

1. **Local:** Visit `GET /module/log-reader-test` (only when `APP_ENV=local`). Then open `/module/log-reader`, choose today’s log (or `laravel.log`). You should see `LOG_READER_SERVER_TEST`. The test route is under the same auth/module middleware as Log Reader.
2. **Server:** Run `php artisan tinker` and execute:
   ```php
   \Illuminate\Support\Facades\Log::info('LOG_READER_SERVER_TEST', ['time' => now()]);
   ```
   Open Log Reader, select the matching date (or `laravel.log`). Confirm the test entry appears.

## Log Reader behaviour after fix

- **Select Date** includes both `laravel.log` and `laravel-YYYY-MM-DD.log` (when present).
- Missing or unreadable log file → clear UI message (no silent empty table).
- Non‑Monolog lines are shown as raw entries (type `RAW`).
