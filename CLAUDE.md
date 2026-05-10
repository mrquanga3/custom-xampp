# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Environment

XAMPP 8.2.12 installed at `C:\custom-xampp` (non-default drive).

| Component | Version |
|-----------|---------|
| Apache | 2.4.58 |
| MariaDB | 10.4.32 |
| PHP | 8.2.12 (64-bit, thread-safe) |
| phpMyAdmin | 5.2.1 |

Active modules: **Apache** and **MySQL** only. FileZilla, Mercury, and Tomcat are disabled.

## Starting and Stopping Services

Use the XAMPP Control Panel (`xampp-control.exe`) or the batch scripts at `C:\custom-xampp\`:

```bat
# Start/stop individual services
mysql_start.bat       # Start MariaDB
mysql_stop.bat        # Stop MariaDB
apache_start.bat      # Start Apache
apache_stop.bat       # Stop Apache

# Start/stop everything
xampp_start.bat
xampp_stop.bat
```

## Key Configuration Files

| Purpose | Path |
|---------|------|
| Apache main config | `apache\conf\httpd.conf` |
| Virtual hosts | `apache\conf\extra\httpd-vhosts.conf` |
| PHP config | `php\php.ini` |
| MariaDB config | `mysql\bin\my.ini` |
| phpMyAdmin | `phpMyAdmin\config.inc.php` |

**Note:** `httpd-vhosts.conf` is always included (unconditional `Include` at line 521 of `httpd.conf`). Syntax errors there will prevent Apache from starting.

## Web Root and Projects

Default DocumentRoot: `C:/custom-xampp/htdocs`

Custom projects currently in `htdocs\`:
- `opencart1/` — OpenCart e-commerce installation
- `opencart-4-setup/` — OpenCart 4 setup files
- `tcexam1/` — TCExam online exam system
- `cpuexam/` — local exam project

Access via `http://localhost/<project-folder>/`.  
phpMyAdmin: `http://localhost/phpmyadmin`

## Adding a New Site

**Option A — Alias** (simpler, keeps `http://localhost/<name>`): add to `httpd.conf` after the `htdocs` Directory block (~line 279):
```apache
Alias /myapp "D:/path/to/myapp"
<Directory "D:/path/to/myapp">
    AllowOverride All
    Require all granted
</Directory>
```

**Option B — VirtualHost** (custom hostname like `http://myapp.local`): add two blocks to `httpd-vhosts.conf` (a default `localhost` block + the new one), then add `127.0.0.1 myapp.local` to `C:\Windows\System32\drivers\etc\hosts` with Admin rights.

Always quote paths containing spaces in Apache config.

## PHP Development

Run PHP CLI directly:
```bat
C:\custom-xampp\php\php.exe script.php
C:\custom-xampp\php\php.exe -l script.php   # syntax check
C:\custom-xampp\php\php.exe -S localhost:8080 -t public/   # built-in server
```

Composer (if needed) should be run via `C:\custom-xampp\php\php.exe composer.phar`.

## Database

Connect via phpMyAdmin (`http://localhost/phpmyadmin`) or CLI:
```bat
C:\custom-xampp\mysql\bin\mysql.exe -u root -p
```

Default root password is stored in `C:\custom-xampp\passwords.txt`. MariaDB data files live in `C:\custom-xampp\mysql\data\`.

## Database User Management

Create MySQL users and manage permissions interactively using the registered skill:

```
/create-db-user
```

Update an existing user's permissions and access:

```
/update-db-user
```

**Or from command line:**
```bash
# Windows batch script
C:\custom-xampp\create-db-user.bat
C:\custom-xampp\update-db-user.bat

# Via PHP CLI
php C:\custom-xampp\tools\create-db-user.php
php C:\custom-xampp\tools\update-db-user.php
```

**create-db-user provides:**
- Interactive wizard to create new database users
- Password entry with confirmation
- Access level selection (local only / anywhere / custom host)
- Preset permission levels (SELECT, INSERT, UPDATE, DELETE, ALL)
- Database selection (specific or all databases)
- Input validation and confirmation before execution

**update-db-user provides:**
- Lists all existing MySQL users with their current access level
- Shows current grants before making changes
- Change access level (local ↔ anywhere ↔ custom host)
- Replace permissions with new selection
- Before/after summary before applying

**Documentation:**
- `.claude/commands/create-db-user.md` — Slash command definition
- `.claude/commands/update-db-user.md` — Slash command definition
- `tools/CREATE-DB-USER.md` — Complete guide with examples
- `QUICKSTART-DB-USER.txt` — Quick reference
- `.claude/SETUP-ALIASES.md` — Shell alias setup

**How custom commands work:**
Place `.md` files in `.claude/commands/` — the filename becomes the `/command` name in Claude Code's palette.

## Changing the XAMPP Installation Path

If you need to move XAMPP to a different location, see:
- **SKILL.md** — Technical reference for configuration file updates (for AI/Claude)
- **README.md** — User-friendly step-by-step guide

**Key files requiring path updates:**
- `apache/conf/httpd.conf` and `apache/conf/extra/*.conf`
- `mysql/bin/my.ini`
- `php/php.ini`
- `xampp-control.ini`
- Dashboard documentation files in `htdocs/dashboard/`

Use find & replace to update `C:\custom-xampp` to the new path in all `.conf`, `.ini`, and `.html` files.
