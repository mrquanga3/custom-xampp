# create-db-user

Create MySQL database users and grant permissions interactively.

## Description
Interactive tool to create new MySQL users and grant database permissions through a guided wizard. Supports custom permission levels and database selection.

## Usage
```
/create-db-user
```

## What it does
- ✅ Creates new MySQL users with password confirmation
- ✅ Prompts for username, password, and host
- ✅ Provides preset permission levels (SELECT, INSERT, UPDATE, DELETE, ALL)
- ✅ Allows custom permission selection
- ✅ Selects specific databases or all databases
- ✅ Validates all inputs before execution
- ✅ Shows confirmation before creating user

## Requirements
- MySQL must be running (start from XAMPP Control Panel)
- Root access to MySQL (uses default configuration)

## Examples

**Create a web application user:**
```
/create-db-user
Username: webapp_user
Host: localhost
Password: [secure password]
Permissions: 2 (SELECT, INSERT, UPDATE, DELETE)
Database: myapp_db
```

Result: User 'webapp_user'@'localhost' created with full data modification permissions.

**Create a read-only reporting user:**
```
/create-db-user
Username: report_user
Host: localhost
Password: [password]
Permissions: 1 (SELECT)
Database: analytics_db
```

Result: User 'report_user'@'localhost' created with SELECT-only permissions.

## Related
- [Database User Creation Guide](../../tools/CREATE-DB-USER.md)
- [Quick Reference](../../QUICKSTART-DB-USER.txt)
- [Setup Instructions](./SETUP-ALIASES.md)
