# SKILL.md — XAMPP Path Configuration Guide for AI/Claude

This document provides technical reference for Claude Code when working with XAMPP configuration path changes.

---

## Overview

This XAMPP installation was configured to use `C:\custom-xampp` instead of the default installation path (typically `D:\xampp` or `C:\Program Files\XAMPP`).

**Commit Reference:** `d60ff207 - Change default xampp folder`

---

## Files Affected by Path Changes

When the installation path is changed, these files **must** be updated with find-and-replace operations:

### 1. Apache Configuration Files

Located in: `{XAMPP_ROOT}/apache/conf/` and subdirectories

| File | What to Update | Example |
|------|---|---|
| `httpd.conf` | DocumentRoot, Directory blocks, module paths | `DocumentRoot "C:/custom-xampp/htdocs"` → `DocumentRoot "D:/xampp/htdocs"` |
| `extra/httpd-vhosts.conf` | VirtualHost document roots, directory paths | `DocumentRoot "C:/custom-xampp/htdocs/site1"` |
| `extra/httpd-ssl.conf` | SSL certificates, key files, log paths | `SSLCertificateFile conf/ssl.crt/` |
| `extra/httpd-xampp.conf` | Alias directives, Directory blocks | `Alias /phpmyadmin "C:/custom-xampp/phpMyAdmin"` |
| `extra/httpd-autoindex.conf` | Icon paths, document root | Icon path references |
| `extra/httpd-manual.conf` | Manual documentation paths | `Alias /manual "C:/custom-xampp/docs"` |
| `extra/httpd-dav.conf` | WebDAV storage locations | Lock and repository paths |

**Pattern to Find & Replace:**
```
Find:    C:\custom-xampp  (or old path like D:\xampp)
Replace: {NEW_XAMPP_PATH}
```

**Note on Path Format:**
- Apache config prefers forward slashes: `C:/custom-xampp/` ✓
- Both work, but forward slashes are standard

### 2. MySQL Configuration

Located in: `{XAMPP_ROOT}/mysql/bin/my.ini`

| Setting | What to Update | Example |
|---------|---|---|
| `basedir` | MySQL installation directory | `basedir = "C:\custom-xampp\mysql"` |
| `datadir` | Data storage location | `datadir = "C:\custom-xampp\mysql\data"` |
| `tmpdir` | Temporary directory | May use XAMPP temp folder |
| `log_error` | Error log location | `log_error = "C:\custom-xampp\mysql\data\mysql_error.log"` |

**Pattern to Find & Replace:**
```
Find:    C:\custom-xampp
Replace: {NEW_XAMPP_PATH}
```

**Note on Path Format:**
- MySQL config requires backslashes: `C:\custom-xampp\` (or quoted)
- Use double backslashes if escaping: `C:\\custom-xampp\\`

### 3. PHP Configuration

Located in: `{XAMPP_ROOT}/php/php.ini`

| Setting | What to Update | Example |
|---------|---|---|
| `extension_dir` | PHP extension directory | `extension_dir = "C:\custom-xampp\php\ext"` |
| `doc_root` | Document root | May reference xampp root |
| Session paths | Session storage | temp directories |

**Search Pattern:** Look for references to old XAMPP path in extension loading sections

### 4. XAMPP Control Panel Configuration

Located in: `{XAMPP_ROOT}/xampp-control.ini`

| Section | What to Update | Example |
|---------|---|---|
| `[Apache]` | Apache executable path | `Apache = "C:\custom-xampp\apache\bin\httpd.exe"` |
| `[MySQL]` | MySQL executable path | `MySQL = "C:\custom-xampp\mysql\bin\mysqld.exe"` |
| `[Config]` | Installation directory | `Installdir = "C:\custom-xampp"` |

**Pattern to Find & Replace:**
```
Find:    C:\custom-xampp
Replace: {NEW_XAMPP_PATH}
```

### 5. Dashboard and Documentation

Located in: `{XAMPP_ROOT}/htdocs/dashboard/`

These files may contain hardcoded paths in FAQ, documentation, and setup guides:

- `faq.html` and language variants (`de/faq.html`, `es/faq.html`, etc.)
- `docs/activate-use-xdebug.html`
- `docs/configure-vhosts.html`
- `docs/configure-wildcard-subdomains.html`
- `docs/create-framework-project-zf1.html`
- `docs/create-framework-project-zf2.html`

**Pattern to Find & Replace:**
```
Find:    C:\custom-xampp  (or D:\xampp, etc.)
Replace: {NEW_XAMPP_PATH}
```

**Note:** These are documentation only; changing them doesn't affect functionality but helps users follow instructions.

### 6. Perl Configuration (Optional)

Located in: `{XAMPP_ROOT}/perl/lib/CPAN/Config.pm`

May contain XAMPP path references for Perl modules.

---

## Automated Path Update Process

To programmatically update all configuration files:

### PowerShell Script Example

```powershell
# Define paths
$oldPath = "C:\custom-xampp"
$newPath = "D:\xampp"

# Files to update (relative to XAMPP root)
$filesToUpdate = @(
    "apache\conf\httpd.conf",
    "apache\conf\extra\*.conf",
    "mysql\bin\my.ini",
    "php\php.ini",
    "xampp-control.ini",
    "perl\lib\CPAN\Config.pm"
)

# Perform replacements
foreach ($pattern in $filesToUpdate) {
    $files = Get-ChildItem -Path "$newPath\*" -Include $pattern -Recurse -ErrorAction SilentlyContinue
    foreach ($file in $files) {
        $content = Get-Content $file.FullName -Raw
        $updated = $content -replace [regex]::Escape($oldPath), $newPath
        if ($updated -ne $content) {
            Set-Content -Path $file.FullName -Value $updated -Encoding UTF8
            Write-Host "Updated: $($file.FullName)"
        }
    }
}
```

### Manual Method (Find & Replace)

Using VS Code, Notepad++, or similar editor:

1. **Open Folder:** `{NEW_XAMPP_PATH}`
2. **Find & Replace** (Ctrl+H in VS Code)
   - **Find:** `C:\\custom-xampp` or `C:/custom-xampp`
   - **Replace:** `{NEW_XAMPP_PATH}` (with appropriate path separators)
   - **Files to include:** `**/*.conf`, `**/*.ini`, `**/*.html`
   - **Replace All**

---

## Verification Checklist After Path Change

### Apache Validation
```bat
# Check syntax
{NEW_XAMPP_PATH}\apache\bin\httpd.exe -t

# Should output: "Syntax OK"
```

### Service Startup Test
```bat
# Start Apache (test from Control Panel or batch scripts)
{NEW_XAMPP_PATH}\xampp_start.bat
```

### File Verification
- [ ] `apache/conf/httpd.conf` — no old paths in DocumentRoot
- [ ] `apache/conf/extra/httpd-vhosts.conf` — no old paths in VirtualHost
- [ ] `apache/conf/extra/httpd-ssl.conf` — SSL paths correct
- [ ] `mysql/bin/my.ini` — basedir and datadir updated
- [ ] `php/php.ini` — extension_dir updated
- [ ] `xampp-control.ini` — Apache/MySQL paths correct
- [ ] Check Apache error log: `{NEW_XAMPP_PATH}\apache\logs\error.log`
- [ ] Check MySQL error log: `{NEW_XAMPP_PATH}\mysql\data\mysql_error.log`

### Functionality Test
```bash
# Browser tests
http://localhost/           # XAMPP dashboard
http://localhost/phpmyadmin # phpMyAdmin
http://localhost/cpuexam    # Sample project
```

---

## Git Commit Pattern

When making path changes, the commit should typically:

1. **Search & replace** all configuration files
2. **Update CLAUDE.md** with new path reference
3. **Commit message:** `Change default xampp folder`

**Example Commit Output:**
```
 CLAUDE.md                              | 2 +-
 apache/conf/httpd.conf                 | 14 ++--
 apache/conf/extra/httpd-vhosts.conf    | 4 +-
 mysql/bin/my.ini                       | 26 ++++---
 xampp-control.ini                      | 84 +++++++++++++++++++++
 [and other .conf, .ini, .html files]
```

---

## Edge Cases & Known Issues

### 1. Paths with Spaces
If new path contains spaces, ensure it's properly quoted in config files:
```apache
# Correct (quoted)
DocumentRoot "C:/Program Files/Custom XAMPP/htdocs"

# Incorrect (unquoted)
DocumentRoot C:/Program Files/Custom XAMPP/htdocs
```

### 2. UNC Paths (Network Paths)
If using UNC path like `\\server\share\xampp`:
- Apache: Use `\\server/share/xampp` (forward slashes work in Apache)
- MySQL: Use `\\\\server\\share\\xampp` (double-escaped backslashes)

### 3. Symlinks
If the new location is a symlink, ensure all Apache options support symlinks:
```apache
<Directory "{NEW_PATH}">
    Options FollowSymLinks
    AllowOverride All
    Require all granted
</Directory>
```

### 4. Relative Path References
Some configs may use relative paths (e.g., `../htdocs`). These are safe during path migration as they resolve relative to the Apache executable or config file location.

---

## Related Documentation

- **CLAUDE.md** — AI-focused XAMPP environment documentation
- **README.md** — User-friendly XAMPP installation and usage guide
- **Apache Config Reference:** `{XAMPP_PATH}/apache/conf/original/httpd.conf.default`
- **MySQL Manual:** `{XAMPP_PATH}/mysql/docs/` or `https://dev.mysql.com/doc/`

---

## Troubleshooting Path Change Issues

### Issue: Apache Won't Start After Path Change
**Solution:**
1. Run syntax check: `{NEW_PATH}\apache\bin\httpd.exe -t`
2. Check error log for undefined variables or missing files
3. Verify all paths in httpd.conf use correct separators (forward or back)
4. Ensure all quoted paths have matching quotes

### Issue: MySQL Won't Start
**Solution:**
1. Verify datadir exists: `{NEW_PATH}\mysql\data\`
2. Check `my.ini` has correct basedir and datadir
3. Look for "data file is locked" errors — ensure no old MySQL instance is running
4. Check Windows Event Viewer for service errors

### Issue: phpMyAdmin Returns "Connection Refused"
**Solution:**
1. Verify MySQL is actually running
2. Check `phpMyAdmin/config.inc.php` for host/port settings
3. Ensure `httpd-xampp.conf` Alias points to correct phpMyAdmin path
4. Check MySQL socket/port in both `my.ini` and phpMyAdmin config

### Issue: Projects Return 404 or Permission Denied
**Solution:**
1. Verify VirtualHost DocumentRoot paths are correct
2. Check `<Directory>` block paths match
3. Ensure AllowOverride and Require directives are set
4. Verify .htaccess files aren't breaking rewrites with old paths

---

## Version History

- **v1.0** — Initial SKILL.md documentation for path change process
- **Based on commit:** `d60ff207` (Change default xampp folder)
