Run the interactive database user creation wizard for this XAMPP installation.

Execute: `C:\custom-xampp\create-db-user.bat`

Guide the user through the interactive prompts:
1. Ask for the new MySQL **username**
2. Ask for a **password** and confirmation
3. Ask **where the user can connect from** (access level):
   - Local only (localhost) — recommended for web apps running on this server
   - Anywhere (%) — for remote connections from any host; warn this is less secure
   - Custom host or IP — for a specific machine
4. Ask which **permissions** to grant:
   - SELECT (read-only)
   - SELECT, INSERT, UPDATE, DELETE (standard app user)
   - ALL PRIVILEGES (admin)
   - Custom
5. Ask which **database** to grant access to (list available databases, or all)
6. Show a summary and **confirm** before executing

After creation, show the connection details the user can copy into their app config.

To update an existing user's permissions later, use: `/update-db-user`

Requirements: MySQL must be running in XAMPP Control Panel.
