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

## Support

For XAMPP documentation, see: `C:\custom-xampp\htdocs\dashboard/`

For configuration help, consult the CLAUDE.md file (for AI) or check Apache/MySQL official documentation.
