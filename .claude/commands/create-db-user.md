Run the interactive database user creation wizard for this XAMPP installation.

Execute the command: `C:\custom-xampp\create-db-user.bat`

Guide the user through the interactive prompts:
1. Ask for the new MySQL **username**
2. Ask for the **host** (default: localhost)
3. Ask for a **password** and confirmation
4. Ask which **permissions** to grant:
   - 1: SELECT (read-only)
   - 2: SELECT, INSERT, UPDATE, DELETE (standard app user)
   - 3: ALL PRIVILEGES (admin)
   - 4: Custom
5. Ask which **database** to grant access to (list available databases or all)
6. Confirm before executing

After creation, show the connection details the user can copy into their app config.

Requirements: MySQL must be running in XAMPP Control Panel.
