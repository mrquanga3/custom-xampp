# XAMPP Installation Guide

This is a custom XAMPP installation located at `C:\custom-xampp` (non-default location).

## What's Installed

| Component | Version | Location |
|-----------|---------|----------|
| Apache | 2.4.58 | `C:\custom-xampp\apache\` |
| MariaDB | 10.4.32 | `C:\custom-xampp\mysql\` |
| PHP | 8.2.12 (64-bit) | `C:\custom-xampp\php\` |
| phpMyAdmin | 5.2.1 | `C:\custom-xampp\phpMyAdmin\` |

**Active Services:** Apache and MySQL only (FileZilla, Mercury, Tomcat are disabled)

---

## Quick Start

### 1. Start XAMPP

**Option A — Using Control Panel:**
```
Double-click: C:\custom-xampp\xampp-control.exe
Click "Start" next to Apache and MySQL
```

**Option B — Using Batch Scripts:**
```bat
C:\custom-xampp\xampp_start.bat
```

### 2. Access Your Projects

Once Apache and MySQL are running:

- **Web Root:** `http://localhost/`
- **phpMyAdmin:** `http://localhost/phpmyadmin`
- **Projects:** `http://localhost/project-folder/`

**Current projects:**
- `http://localhost/opencart1/`
- `http://localhost/opencart-4-setup/`
- `http://localhost/tcexam1/`
- `http://localhost/cpuexam/`

### 3. Stop XAMPP

**Option A — Control Panel:**
```
Open xampp-control.exe
Click "Stop" next to Apache and MySQL
```

**Option B — Batch Scripts:**
```bat
C:\custom-xampp\xampp_stop.bat
```

---

## Claude Code Commands

This XAMPP installation includes custom commands for Claude Code (AI assistant) to help with common tasks.

### Create Database User

Create MySQL users and grant permissions interactively.

**Usage from Claude Code (Recommended):**
```
/create-db-user
```

Just type the slash command and Claude Code will guide you through the interactive wizard!

**Or ask Claude directly:**
```
"Create a MySQL user named 'myapp_user' for database 'mydb' with full data modification permissions"
```

**Usage from command line:**
```bash
# Windows Command Prompt / PowerShell
C:\custom-xampp\create-db-user.bat

# Via PHP
php C:\custom-xampp\tools\create-db-user.php

# WSL / Git Bash
bash C:\custom-xampp\tools\create-db-user.sh
```

Or use shell aliases (after setup):
```powershell
# PowerShell
create-db-user
createuser
new-db-user
adduser
```

**What It Does:**
- ✅ Creates new MySQL database users
- ✅ Prompts for username, password, host
- ✅ Grants specific permissions (SELECT, INSERT, UPDATE, DELETE, ALL)
- ✅ Assigns user to database(s)
- ✅ Validates inputs and confirms before execution

**Quick Example:**
```
1. Run: /create-db-user
2. Username: myapp_user
3. Host: localhost (press Enter)
4. Password: ••••••••
5. Confirm: ••••••••
6. Permissions: 2 (SELECT, INSERT, UPDATE, DELETE)
7. Database: Select your database
8. Confirm: y

✓ User created with full data modification rights
```

**Documentation:**
- `QUICKSTART-DB-USER.txt` — Quick reference
- `tools/CREATE-DB-USER.md` — Comprehensive guide with examples
- `.claude/SETUP-ALIASES.md` — Shell alias setup instructions

### Using Commands with Claude Code

**What is Claude Code?**

Claude Code is an AI assistant that integrates with your IDE (VS Code, JetBrains, or web interface). It can execute commands, edit files, and run tests to help automate development tasks.

**How to Ask Claude Code to Create a User:**

1. **Open Claude Code** in your IDE or at `claude.ai/code`

2. **Request a user creation:**
   ```
   Please create a MySQL user named 'webapp_user' for the 'myapp' 
   database with full data modification permissions
   ```

3. **Claude will:**
   - Recognize the XAMPP environment from `CLAUDE.md`
   - Run the `create-db-user` command
   - Follow the interactive prompts
   - Report success with connection details

**Example Interaction:**

```
User: Create a MySQL user called 'api_user' for database 'api_db' 
with SELECT and INSERT permissions

Claude: I'll create that user for you...
[Runs: C:\custom-xampp\create-db-user.bat]
[Provides username, password, selects permissions, confirms]

✓ User 'api_user'@'localhost' created successfully

Connection details:
  Host: localhost
  Username: api_user
  Password: [generated-secure-password]
  Database: api_db
  Permissions: SELECT, INSERT
```

**Why Use Claude for This?**

- ✅ No manual command typing or menu navigation
- ✅ Automatic validation and error handling
- ✅ Detailed documentation reference in `CLAUDE.md`
- ✅ Quick integration into development workflows
- ✅ Consistent permission management across projects

---

## Configuration

### Apache Configuration Files

| File | Purpose | Location |
|------|---------|----------|
| `httpd.conf` | Main Apache config | `C:\custom-xampp\apache\conf\httpd.conf` |
| `httpd-vhosts.conf` | Virtual hosts config | `C:\custom-xampp\apache\conf\extra\httpd-vhosts.conf` |

### PHP Configuration

```
C:\custom-xampp\php\php.ini
```

### MySQL Configuration

```
C:\custom-xampp\mysql\bin\my.ini
```

---

## Changing the XAMPP Installation Path

If you want to move XAMPP to a different location (e.g., from `C:\custom-xampp` to `D:\xampp`), follow these steps:

### Step 1: Prepare
1. Stop all XAMPP services (Apache, MySQL)
2. Note the **old path** and **new path**
   - Old: `C:\custom-xampp`
   - New: `D:\xampp` (example)

### Step 2: Files That Need Path Updates

The following files contain hardcoded paths and **must** be updated:

#### Apache Configuration Files:
- `apache/conf/httpd.conf` — DocumentRoot, Directory blocks
- `apache/conf/extra/httpd-vhosts.conf` — VirtualHost paths
- `apache/conf/extra/httpd-ssl.conf` — SSL certificate paths
- `apache/conf/extra/httpd-xampp.conf` — XAMPP-specific paths
- Other files in `apache/conf/extra/` — various module configs

#### MySQL Configuration:
- `mysql/bin/my.ini` — data directory, log paths

#### PHP Configuration:
- `php/php.ini` — extension paths, session directory

#### Dashboard/Documentation:
- `htdocs/dashboard/` — various HTML docs with hardcoded paths

#### Control Panel:
- `xampp-control.ini` — XAMPP Control Panel settings

### Step 3: Move the Folder

1. **Copy** the entire `C:\custom-xampp` folder to the new location
   ```cmd
   xcopy C:\custom-xampp D:\xampp /E /I
   ```

2. **Verify** the copy completed successfully

### Step 4: Update Configuration Files

Use **Find & Replace** in a text editor (e.g., VS Code, Notepad++):

**Search:** `C:\custom-xampp`  
**Replace with:** `D:\xampp` (or your new path)

Apply to these file types in the new location:
- `*.conf` (Apache configs)
- `*.ini` (MySQL, PHP, XAMPP control)
- `*.html` (documentation)
- `xampp-control.ini`

### Step 5: Update Windows Registry (Optional)

If XAMPP was registered as a Windows Service:
```cmd
sc delete MySQLService
sc delete ApacheService
```

Then re-register from the new location (use XAMPP Control Panel's service installation feature).

### Step 6: Update Environment Variables (If Set)

If you previously set environment variables pointing to the old path:
1. Press `Win + X` → **System**
2. Go to **Advanced system settings**
3. Click **Environment Variables**
4. Search for any variables containing the old path and update them

### Step 7: Test the Installation

1. Open XAMPP Control Panel from the new location
   ```
   D:\xampp\xampp-control.exe
   ```

2. Start Apache and MySQL

3. Test in browser:
   - `http://localhost/` → should show XAMPP dashboard
   - `http://localhost/phpmyadmin` → should show phpMyAdmin

4. Check error logs if something fails:
   - Apache errors: `D:\xampp\apache\logs\error.log`
   - MySQL errors: `D:\xampp\mysql\data\mysql_error.log`

### Step 8: Delete Old Installation (When Confirmed Working)

Once you've confirmed everything works from the new location:
```cmd
rmdir /S /Q C:\custom-xampp
```

---

## Batch Script Reference

| Script | Purpose | Location |
|--------|---------|----------|
| `xampp_start.bat` | Start all services | `C:\custom-xampp\xampp_start.bat` |
| `xampp_stop.bat` | Stop all services | `C:\custom-xampp\xampp_stop.bat` |
| `apache_start.bat` | Start Apache only | `C:\custom-xampp\apache_start.bat` |
| `apache_stop.bat` | Stop Apache only | `C:\custom-xampp\apache_stop.bat` |
| `mysql_start.bat` | Start MariaDB only | `C:\custom-xampp\mysql_start.bat` |
| `mysql_stop.bat` | Stop MariaDB only | `C:\custom-xampp\mysql_stop.bat` |

---

## Database Access

### Via phpMyAdmin (GUI)
```
http://localhost/phpmyadmin
```

### Via Command Line
```bat
C:\custom-xampp\mysql\bin\mysql.exe -u root -p
```

**Default root password:** See `C:\custom-xampp\passwords.txt`

---

## PHP Development

### Run PHP Script
```bat
C:\custom-xampp\php\php.exe script.php
```

### Check PHP Syntax
```bat
C:\custom-xampp\php\php.exe -l script.php
```

### Built-in PHP Server
```bat
C:\custom-xampp\php\php.exe -S localhost:8080 -t public/
```

---

## Adding a New Website

### Method 1: Alias (Simple - `http://localhost/myapp`)

Edit `C:\custom-xampp\apache\conf\httpd.conf` and add after line ~279:

```apache
Alias /myapp "C:/path/to/myapp"
<Directory "C:/path/to/myapp">
    AllowOverride All
    Require all granted
</Directory>
```

Then restart Apache.

### Method 2: VirtualHost (Advanced - `http://myapp.local`)

1. **Add to `C:\custom-xampp\apache\conf\extra\httpd-vhosts.conf`:**
   ```apache
   <VirtualHost *:80>
       ServerName myapp.local
       DocumentRoot "C:/path/to/myapp"
       <Directory "C:/path/to/myapp">
           AllowOverride All
           Require all granted
       </Directory>
   </VirtualHost>
   ```

2. **Edit `C:\Windows\System32\drivers\etc\hosts`** (requires Admin):
   ```
   127.0.0.1 myapp.local
   ```

3. **Restart Apache**

4. **Access:** `http://myapp.local`

---

## Troubleshooting

### Apache Won't Start
1. Check syntax: Open command prompt in `C:\custom-xampp\apache\bin\` and run:
   ```bat
   httpd.exe -t
   ```

2. Check logs:
   ```
   C:\custom-xampp\apache\logs\error.log
   ```

3. Common issues:
   - Port 80 already in use → change port in `httpd.conf`
   - Syntax error in `httpd-vhosts.conf` → validate XML/Apache syntax
   - Invalid path in config → verify all paths use forward slashes `/` or double backslashes `\\`

### MySQL Won't Start
1. Check logs:
   ```
   C:\custom-xampp\mysql\data\mysql_error.log
   ```

2. Verify data folder exists:
   ```
   C:\custom-xampp\mysql\data\
   ```

3. Check if port 3306 is already in use

### phpMyAdmin Not Accessible
1. Ensure MySQL is running
2. Check `C:\custom-xampp\phpMyAdmin\config.inc.php`
3. Verify Apache's `httpd-xampp.conf` includes phpMyAdmin alias

---

## Available Tools and Scripts

### Claude Code Skills (Slash Commands)

These are registered as `/` commands in Claude Code:

| Command | Source file | Purpose |
|---------|------------|---------|
| `/setup-xampp` | `.claude/commands/setup-xampp.md` | Setup XAMPP (Windows) |
| `/create-db-user` | `.claude/commands/create-db-user.md` | Create MySQL user and grant permissions |

Just type the slash command in Claude Code and follow the prompts!

> **How it works:** Any `.md` file placed in `.claude/commands/` automatically becomes a `/command` in Claude Code's palette. The filename (without `.md`) is the command name.

### Batch Scripts (Windows)

These scripts help manage XAMPP services:

| Script | Purpose | Location |
|--------|---------|----------|
| `xampp_start.bat` | Start all services (Apache, MySQL) | `C:\custom-xampp\xampp_start.bat` |
| `xampp_stop.bat` | Stop all services | `C:\custom-xampp\xampp_stop.bat` |
| `apache_start.bat` | Start Apache only | `C:\custom-xampp\apache_start.bat` |
| `apache_stop.bat` | Stop Apache only | `C:\custom-xampp\apache_stop.bat` |
| `mysql_start.bat` | Start MariaDB only | `C:\custom-xampp\mysql_start.bat` |
| `mysql_stop.bat` | Stop MariaDB only | `C:\custom-xampp\mysql_stop.bat` |
| `create-db-user.bat` | Create MySQL user and grant permissions | `C:\custom-xampp\create-db-user.bat` |

### PHP Scripts (In `tools/` directory)

These PHP scripts provide functionality that can be invoked from Claude Code or command line:

| Script | Purpose | Location |
|--------|---------|----------|
| `create-db-user.php` | Interactive database user creation | `C:\custom-xampp\tools\create-db-user.php` |

### Documentation

| File | Purpose |
|------|---------|
| `CLAUDE.md` | AI/Claude Code environment documentation |
| `README.md` | This file - user guide |
| `SKILL.md` | Technical reference for XAMPP configuration |
| `QUICKSTART-DB-USER.txt` | Quick reference card for user creation |
| `tools/CREATE-DB-USER.md` | Complete guide with examples and troubleshooting |

---

## Asking Claude Code for Help

### Using Slash Commands

**Create a database user:**
```
/create-db-user
```

Then follow the interactive prompts to set up your user.

### Common Chat Requests

**"Create a new database user"**
```
Please create a MySQL user for [username] with access to [database]
Permissions: [SELECT/INSERT/UPDATE/DELETE/ALL]
```

**"Set up a new project"**
```
Help me set up a new PHP project called [projectname] with:
- Database user
- Virtual host or alias
- Initial database structure
```

**"Check database permissions"**
```
Show me what permissions user [username] has on [database]
```

**"List all database users"**
```
Show me all MySQL users and their hosts
```

### Tips for Better Results

1. **Be specific** — Include database names, usernames, and required permissions
2. **Mention constraints** — Network access, security requirements, performance needs
3. **Reference documentation** — Ask Claude to check `CLAUDE.md` or `SKILL.md`
4. **Confirm before executing** — Ask Claude to review the plan before running commands
5. **Test after creation** — Ask Claude to verify new users with phpMyAdmin or CLI

### What Claude Can Help With

✅ Create and manage database users  
✅ Set up virtual hosts or aliases  
✅ Configure Apache and MySQL settings  
✅ Troubleshoot connection issues  
✅ Optimize performance  
✅ Migrate XAMPP to different locations  
✅ Document your setup  

---

## Troubleshooting with Claude Code

If something doesn't work:

1. **Ask Claude to diagnose:**
   ```
   MySQL won't start - what's wrong?
   Check the error log and help me fix it
   ```

2. **Claude will:**
   - Check error logs
   - Verify service status
   - Review configuration files
   - Suggest and implement fixes

3. **Check logs directly if needed:**
   ```
   Apache errors: C:\custom-xampp\apache\logs\error.log
   MySQL errors: C:\custom-xampp\mysql\data\mysql_error.log
   ```

---

## Support

**For XAMPP-specific help:**
- XAMPP dashboard: `http://localhost/`
- XAMPP documentation: `C:\custom-xampp\htdocs\dashboard/`

**For configuration details:**
- AI/Claude reference: `CLAUDE.md`
- Technical details: `SKILL.md`
- User guide: This `README.md`

**For command-specific help:**
- Database user creation: `tools/CREATE-DB-USER.md`
- Quick reference: `QUICKSTART-DB-USER.txt`

**For official documentation:**
- Apache: https://httpd.apache.org/docs/
- MySQL/MariaDB: https://dev.mysql.com/doc/ or https://mariadb.com/docs/
- PHP: https://www.php.net/docs.php
- phpMyAdmin: https://www.phpmyadmin.net/

---

## Next Steps

1. **Start XAMPP** — Run `xampp_start.bat` or use Control Panel
2. **Access phpMyAdmin** — Visit `http://localhost/phpmyadmin`
3. **Create first user** — Run `create-db-user.bat` or ask Claude Code
4. **Set up a project** — Add alias or virtual host (see "Adding a New Site")
5. **Test connection** — Verify user can access database from your app

Enjoy! 🚀
