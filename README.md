# Phantom Analytics

Fictional internal staff dashboard for "Phantom Analytics, Inc." — the web layer of a sandboxed challenge VM. Raw PHP 8.1 + Apache + MongoDB, no frameworks, no CDN, no build step. Looks like a genuine mid-size-company internal tool.

```
webroot/       → mirrors /var/www/html exactly
install.sh     → everything needed to deploy on the target VM (run as root there)
verify/        → docker stack for local verification of the full stack
```

## Stack

- PHP 8.1 (mod_php, `libapache2-mod-php`)
- MongoDB server (official `mongodb-org` repo) + `php-mongodb` C driver (ext `mongodb` 1.17.x)
- The `MongoDB\Client` wrapper class comes from the mongodb/mongodb PHP library (pure PHP, PSR-4) — the C driver alone only provides `MongoDB\Driver\*`. The library is vendored onto the server at install time (see below).
- Sessions via `session_start()`; roles/identity in `$_SESSION['role']` / `$_SESSION['user']`
- Hand-rolled dark CSS theme + vanilla JS only

## Directory structure

```
webroot/
├── index.php            redirects to login or dashboard
├── login.php            sign-in form + auth
├── logout.php
├── config.php           MongoDB connection
├── dashboard.php        overview: stats, SVG chart, activity, admin panel
├── profile.php          account info, display name, password form
├── reports.php          analytics table (fake data)
├── team.php             staff directory (12 members)
├── alerts.php           system alerts feed (10 alerts)
├── seed.php             DB seeder — DELETE from the server after initial seed
├── admin/
│   ├── index.php        admin home: tiles, system health, recent uploads
│   ├── upload.php       profile-media upload + uploaded-files table
│   ├── users.php        user management (live data from MongoDB)
│   ├── settings.php     settings form (non-functional, flash message)
│   └── logs.php         monospace log viewer (fake access logs)
├── uploads/
│   ├── .htaccess        web config for the uploads directory
│   └── avatars/         uploaded files land here
├── assets/
│   ├── style.css        full theme
│   ├── dashboard.js     sidebar toggle, notifications, modal helpers
│   └── logo.svg
└── includes/
    ├── auth.php         require_login() / require_admin() / user_initials()
    ├── header.php       sidebar + topbar layout, active-nav highlighting
    ├── footer.php
    └── mongo-library/   populated at install time (see below)
```

## Deploy on the target VM

```bash
sudo ./install.sh
```

Root is required. The script, in order:

1. Installs `apache2`, PHP + mod_php, `php-mongodb`, `mongodb-org-server`
2. Overwrites the distro PHP handler config so only `.php` and `.php5` are executed (`.phar` etc. are not)
3. Deploys `webroot/` → `/var/www/html`, fixes ownership (`www-data`) and permissions
4. Downloads the mongodb/mongodb PHP library into `/var/www/html/includes/mongo-library/` and writes its PSR-4 `autoload.php` (required by `config.php`)
5. Adds `127.0.0.1 Sunset` to `/etc/hosts`
6. Creates the OS account `appdev` (shell access account — shared credentials)
7. Starts mongod, creates the MongoDB `appdev` user, enables auth (`security.authorization: enabled`), restarts
8. Rewrites the deployed `config.php` connection line (see "Connection handling" below)
9. Runs `seed.php` (admin / jsmith / bthomas), then Apache is live

If you run a web server (nginx, php-fpm, or a different distro), adapt step 2/7 — mod_php and `systemctl` are assumed.

## Connection handling (read this)

`webroot/config.php` contains, verbatim:

```php
$client = new MongoDB\Client("mongodb://appdev:Apricot@Sunset#9@localhost:27017");
```

That literal string cannot connect on a stock stack:

- libmongoc parses the whole tail as the hostname (`sunset#9@localhost`) — it never resolves (`#` is a comment in `/etc/hosts`, and DNS rejects the special chars)
- even with a resolvable host, any URI carrying credentials is rejected by a no-auth mongod (`Authentication failed`)

So the deployed server gets an equivalent, working form — the same credentials, percent-encoded, host `Sunset` resolved via `/etc/hosts`:

```php
$client = new MongoDB\Client("mongodb://appdev:Apricot%40Sunset%239@Sunset:27017");
```

`install.sh` does this with a `sed` on the deployed copy — the repo file keeps the original string. Decoded, both strings are `appdev` / `Apricot@Sunset#9`, which is also exactly the OS password set for `appdev` in step 6.

## Database seeding

```bash
php /var/www/html/seed.php
```

Drops and recreates the `users` collection with three accounts:

| username | password    | role  |
|----------|-------------|-------|
| admin    | n0t_4_u_:)  | admin |
| jsmith   | password123 | user  |
| bthomas  | letmein99   | user  |

`seed.php` must be removed from the server after the initial seed (noted in the file itself).

## Local verification with docker

```bash
cd verify
docker compose up -d --build     # builds php:8.1-apache + mongod + C driver, runs on :8080
```

The entrypoint mirrors `install.sh` for the container: hosts entry, library vendoring, DB account, auth-enabled mongod, config rewrite, seed. `webroot/` is bind-mounted as `/var/www/html`.

Smoke checks after boot:

```bash
curl -s -o /dev/null -w '%{http_code}\n' localhost:8080/login.php          # 200
curl -s -i -d 'username=admin&password=n0t_4_u_:)' -c /tmp/cj localhost:8080/login.php   # 302 → dashboard
curl -s -b /tmp/cj localhost:8080/dashboard.php                            # admin panel visible
```

Note: `verify/php-handler.conf` is the container's php8.1 handler config; keep it in sync with the FilesMatch block written by `install.sh`.

## Author notes (challenge intent)

For the team's reference — the designed chain and artifacts:

- **Flag 1 — login bypass.** `login.php` passes raw `$_POST` values into the MongoDB query. Array-style parameters (`username[$ne]=x&password[$ne]=x`, or `[$gt]=`) satisfy both fields without a credential; the first document returned is `admin`, granting the session role. Flag `FLAG{nosql_ate_my_login}` is embedded as an HTML comment in the admin-only panel on `dashboard.php` — only visible in a session with `role=admin`.
- **Upload execution.** `admin/upload.php` filters extensions by denylist (`php`, `php3`, `php4`, `phtml`) — `php5` is not listed. `uploads/.htaccess` blocks only `\.php$`, and the Apache handler executes `.php5`. Expected player behavior: `shell.php` → 403 (rabbit hole); `shell.php5` → executes.
- **Credential reuse (lateral movement).** `config.php` in the webroot yields `appdev:Apricot@Sunset#9`; the OS account `appdev` is created with that exact password (`install.sh`). Players who reach the webshell read `config.php` and SSH as `appdev`.
- **Keep-out docs:** this README, the `.git/` history, and `verify/` must not ship inside `/var/www/html` — `install.sh` only copies `webroot/`, which keeps that true by construction.
- On a hardened host, remove `seed.php` and change the seeded passwords; the challenge relies on them being left as-is.

## Troubleshooting

| Symptom | Cause / fix |
|---|---|
| `Class "MongoDB\Client" not found` | library not vendored — rerun `install.sh`, or check `/var/www/html/includes/mongo-library/autoload.php` exists |
| `Authentication failed` / `No suitable servers found` | deployed `config.php` still holds the raw URI, or mongod auth user missing — re-run step 7–8 |
| `shell.php5` returns 403 | `AllowOverride` not enabled for `/var/www/html/uploads`, or handler config not applied |
| Seed fails via web | run it via CLI (`php seed.php`) before the auth restart or remove the file afterwards |
