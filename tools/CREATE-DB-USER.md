# Database User Creator Command

Interactive tool to create MySQL users and grant permissions to databases in XAMPP.

## Quick Start

### Windows (Batch Script)
```batch
C:\custom-xampp\create-db-user.bat
```

Or double-click: `create-db-user.bat`

### Command Line (PHP)
```bash
php C:\custom-xampp\tools\create-db-user.php
```

### WSL / Git Bash / Unix-like
```bash
bash C:\custom-xampp\tools\create-db-user.sh
```

---

## Features

✅ **Interactive prompts** — Easy-to-follow step-by-step wizard  
✅ **User creation** — Create new MySQL users with passwords  
✅ **Permission management** — Grant granular permissions (SELECT, INSERT, UPDATE, DELETE, ALL)  
✅ **Database selection** — Choose which database(s) to grant access to  
✅ **Validation** — Input validation for usernames, passwords, database names  
✅ **Confirmation** — Review before execution  
✅ **Cross-platform** — Works on Windows, WSL, Git Bash, Unix  

---

## Step-by-Step Usage

### 1. Start the Tool
Run the appropriate command for your platform (see Quick Start above)

**Expected Output:**
```
============================================================
Database User Creator
============================================================

ℹ Connecting to MySQL server...
✓ Connected to MySQL
```

### 2. Enter Username
```
Enter new username: myappuser
```

**Requirements:**
- Max 32 characters
- Only letters, numbers, underscore (`_`), and hyphen (`-`)
- Must not already exist

### 3. Enter Host
```
Enter host [localhost]:
```

**Common values:**
- `localhost` — Local connections only (default)
- `127.0.0.1` — Loopback IPv4 address
- `%` — Any host (use with caution)
- `192.168.1.*` — Specific network range

Press Enter to use default (`localhost`)

### 4. Enter Password (if new user)

If the user already exists, you'll skip to permission grants.

```
Enter password: ••••••••
Confirm password: ••••••••
```

**Requirements:**
- Password must match confirmation
- No specific complexity requirements (you set the policy)

### 5. Choose Permission Level
```
Select permissions to grant:
  1. SELECT (read-only access)
  2. SELECT, INSERT, UPDATE, DELETE (full data modification)
  3. ALL PRIVILEGES (full administrative access)
  4. Custom permissions

Choose option [2]:
```

**Predefined options:**
- **1 (SELECT)** — Read-only access; useful for reporting tools
- **2 (SELECT, INSERT, UPDATE, DELETE)** — Full data modification; typical for applications
- **3 (ALL PRIVILEGES)** — Full control; use carefully
- **4 (Custom)** — Enter comma-separated permissions manually

### 6. Choose Database
```
Select database to grant permissions on:
  0. All databases (*)
  1. information_schema
  2. mysql
  3. performance_schema
  4. mydb
  5. Other database name

Choose option [0]:
```

**Options:**
- **0 (All databases)** — Grant permissions on all databases (`*.*`)
- **1-N** — Select from the listed databases
- **N+1 (Other)** — Enter a custom database name

### 7. Review and Confirm
```
ℹ Summary:
  Username: myappuser
  Host: localhost
  Permissions: SELECT, INSERT, UPDATE, DELETE
  Database: mydb

Proceed? [y]: y
```

**Check the details carefully before confirming!**

### 8. Success
```
✓ User created and permissions granted successfully!

Connection details for your application:
  Host: localhost
  Username: myappuser
  Database: mydb
```

---

## Permission Levels

### SELECT (Read-Only)
```sql
GRANT SELECT ON database.* TO 'user'@'localhost';
```
- User can read data but cannot modify it
- **Use for:** Reports, dashboards, read-only access

### SELECT, INSERT, UPDATE, DELETE (Full Modification)
```sql
GRANT SELECT, INSERT, UPDATE, DELETE ON database.* TO 'user'@'localhost';
```
- User can read, create, modify, and delete records
- Cannot create/drop tables or alter schema
- **Use for:** Web applications, standard applications

### ALL PRIVILEGES (Full Control)
```sql
GRANT ALL PRIVILEGES ON database.* TO 'user'@'localhost';
```
- User has complete control
- Can create/drop tables, create triggers, manage permissions
- **Use for:** Database administrators, application ownership

### Custom
```sql
GRANT SELECT, INSERT, UPDATE ON database.* TO 'user'@'localhost';
```
- Manually specify permissions separated by commas
- Common permissions: `SELECT`, `INSERT`, `UPDATE`, `DELETE`, `CREATE`, `DROP`, `ALTER`, `REFERENCES`, `INDEX`, `EXECUTE`

---

## Examples

### Example 1: Web Application User
Create a user for a web application with standard permissions:

```
Username: webapp_user
Host: localhost
Password: SecurePassword123
Permissions: SELECT, INSERT, UPDATE, DELETE (option 2)
Database: myapp_db
```

### Example 2: Read-Only Reporting User
Create a user for a reporting tool with read-only access:

```
Username: report_user
Host: localhost
Password: ReportPass456
Permissions: SELECT (option 1)
Database: analytics_db
```

### Example 3: Network Access
Create a user that can connect from any machine:

```
Username: remote_user
Host: %
Password: RemotePass789
Permissions: SELECT, INSERT, UPDATE, DELETE (option 2)
Database: shared_db
```

---

## Testing the New User

### Using Command Line
```bash
C:\custom-xampp\mysql\bin\mysql.exe -u myappuser -p mydb
```

Then enter the password when prompted.

### Using phpMyAdmin
1. Log in to `http://localhost/phpmyadmin`
2. Use the new username and password
3. Verify you can access the correct database and objects

### From PHP Code
```php
<?php
$conn = mysqli_connect(
    "localhost",      // host
    "myappuser",      // username
    "password123",    // password
    "mydb"            // database
);

if (mysqli_connect_errno()) {
    echo "Connection failed: " . mysqli_connect_error();
}
?>
```

---

## Troubleshooting

### "Failed to connect to MySQL"
- **Cause:** MySQL is not running
- **Solution:**
  1. Open XAMPP Control Panel (`C:\custom-xampp\xampp-control.exe`)
  2. Click "Start" next to MySQL
  3. Wait 2-3 seconds and try again

### "User already exists"
- **Cause:** The username@host combination already exists
- **Solution:**
  - Use a different username
  - Or choose a different host
  - Or use option to grant additional permissions to existing user

### "Passwords do not match"
- **Cause:** Password and confirmation don't match
- **Solution:** Re-enter passwords carefully (note: input is hidden)

### "Invalid database name"
- **Cause:** Database name contains invalid characters
- **Solution:** Use only letters, numbers, and underscore (`_`)

### "Access denied for user 'root'"
- **Cause:** Incorrect root password in script
- **Solution:**
  1. Check root password in `C:\custom-xampp\passwords.txt`
  2. Edit `C:\custom-xampp\tools\create-db-user.php`
  3. Find line with `define('DB_PASS', '');`
  4. Update with correct password (if not default empty string)

---

## Advanced Usage

### Setting Root Password in Script
Edit `create-db-user.php`, find the configuration section:

```php
define('DB_PASS', ''); // Change this if root password is set
```

If your root password is `mypassword`:

```php
define('DB_PASS', 'mypassword');
```

### Running from Claude Code
You can invoke this from Claude Code with:

```bash
php C:\custom-xampp\tools\create-db-user.php
```

Or let Claude automate it with input piping:

```bash
# This would require scripting to provide inputs programmatically
# Example with echo piping:
echo -e "username\nlocalhost\npassword\npassword\n2\n0\ny" | php C:\custom-xampp\tools\create-db-user.php
```

### Scripting User Creation
For CI/CD or automation, create a non-interactive version:

```php
<?php
// Programmatic approach (not interactive)
$conn = mysqli_connect('localhost', 'root', '');
mysqli_query($conn, "CREATE USER 'autouser'@'localhost' IDENTIFIED BY 'autopass'");
mysqli_query($conn, "GRANT SELECT, INSERT, UPDATE, DELETE ON mydb.* TO 'autouser'@'localhost'");
mysqli_query($conn, "FLUSH PRIVILEGES");
?>
```

---

## Files Reference

| File | Purpose | Platform |
|------|---------|----------|
| `create-db-user.bat` | Batch wrapper | Windows (CMD, PowerShell) |
| `tools/create-db-user.php` | Main PHP script | Windows, WSL, Unix |
| `tools/create-db-user.sh` | Shell wrapper | WSL, Git Bash, Unix |
| `tools/CREATE-DB-USER.md` | This documentation | All |

---

## Related Commands

### Create Database
```bash
C:\custom-xampp\mysql\bin\mysql.exe -u root -p -e "CREATE DATABASE newdb CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
```

### List Users
```bash
C:\custom-xampp\mysql\bin\mysql.exe -u root -p -e "SELECT USER, HOST FROM mysql.user;"
```

### Drop User
```bash
C:\custom-xampp\mysql\bin\mysql.exe -u root -p -e "DROP USER 'username'@'localhost';"
```

### Check User Permissions
```bash
C:\custom-xampp\mysql\bin\mysql.exe -u root -p -e "SHOW GRANTS FOR 'username'@'localhost';"
```

---

## Security Notes

⚠️ **Best Practices:**
- Never set `Host` to `%` unless necessary (security risk)
- Use strong passwords for application users
- Grant minimum required permissions (principle of least privilege)
- Avoid using root for application connections
- Regularly review user permissions with `SHOW GRANTS`
- Use `ALL PRIVILEGES` sparingly

⚠️ **Passwords in Scripts:**
- Never commit passwords to git
- Use environment variables or `.env` files
- Keep `passwords.txt` secure and inaccessible to web server

---

## Support

For issues or improvements:
1. Check MySQL error log: `C:\custom-xampp\mysql\data\mysql_error.log`
2. Check XAMPP Control Panel for service status
3. Review SKILL.md and CLAUDE.md for environment details
4. Test with `mysql.exe` CLI directly to isolate issues
