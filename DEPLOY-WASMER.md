# Deploying to Wasmer Edge

Wasmer runs this app with PHP 8.3 and a managed Postgres database (set up in `app.yaml`).
Your local SQLite data is not copied; the online app starts empty.

## One-time setup (on your computer, in the project folder)

1. Install the Wasmer CLI (Windows PowerShell):
   ```
   iwr https://win.wasmer.io -useb | iex
   ```
   Then open a new terminal.
2. Sign in: `wasmer login`
3. Open `app.yaml` and replace `YOUR-WASMER-USERNAME` with your Wasmer username.
4. Make sure the PHP packages are installed: `composer install --no-dev --optimize-autoloader`
5. Generate an app key and store it as a secret (it is never written to the repo):
   ```
   php artisan key:generate --show
   wasmer app secrets create APP_KEY base64:PASTE-THE-KEY-HERE
   ```
   (If the app doesn't exist yet, run step 6 first, then this, then deploy again.)

## Deploy

6. `wasmer deploy`
7. Open the URL it prints and **create your account straight away**. Until an
   account exists, whoever opens the site first could create it.

To redeploy after changes: `git pull`, `composer install --no-dev`, then `wasmer deploy`.

## Settings used (`app.yaml`)

| Setting | Why |
| --- | --- |
| `DB_CONNECTION=pgsql`, `DB_SSLMODE=require` | Wasmer's database needs TLS; `require` encrypts without checking Wasmer's private certificate. |
| `SESSION_DRIVER` / `CACHE_STORE=database` | Keeps sign-ins working across Wasmer's instances. |
| `LOG_CHANNEL=stderr`, `VIEW_COMPILED_PATH=/tmp` | The app folder may be read-only on Wasmer. |
| `migrate` job | Creates or updates the tables after each deploy. |

## If something fails

- **Tables missing / "relation does not exist":** the migrate job didn't run. Check the app's
  Jobs/Logs page in the Wasmer dashboard.
- **500 error right after deploy:** check that the `APP_KEY` secret exists (`wasmer app secrets list`).
- **Logs:** `wasmer app logs`
