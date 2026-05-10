Run the interactive database user permission updater for this XAMPP installation.

Execute: `C:\custom-xampp\update-db-user.bat`

Guide the user through the steps:

1. **List existing users** — show all MySQL users with their current host/access level
2. **Select user** — ask which user to update
3. **Show current grants** — display the user's existing permissions
4. **Change access level** — ask where the user should be able to connect from:
   - Local only (localhost) — recommended for web apps running on this server
   - Anywhere (%) — for remote connections from any host; warn this is less secure
   - Custom host or IP — for a specific machine
   - Keep current (no change)
5. **Select new permissions**:
   - SELECT (read-only)
   - SELECT, INSERT, UPDATE, DELETE (standard app user)
   - ALL PRIVILEGES (admin)
   - Custom
6. **Select database** — which database to apply permissions to (list available or all)
7. **Confirm summary** — show a clear before/after summary and ask to proceed

After updating, show the updated connection details.

Requirements: MySQL must be running in XAMPP Control Panel.
