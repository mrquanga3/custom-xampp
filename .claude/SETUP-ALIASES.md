# Setting Up Command Aliases

This guide explains how to set up shell aliases for XAMPP commands.

## Quick Setup

### PowerShell (Windows)

1. **Open your PowerShell profile:**
   ```powershell
   code $PROFILE
   ```
   If the file doesn't exist, create it first:
   ```powershell
   New-Item -Path $PROFILE -ItemType File -Force
   ```

2. **Add this line to your profile:**
   ```powershell
   . C:\custom-xampp\.claude\aliases.ps1
   ```

3. **Reload your profile:**
   ```powershell
   . $PROFILE
   ```

4. **Test the alias:**
   ```powershell
   create-db-user
   ```

### Bash / WSL / Git Bash

1. **Open your `.bashrc` or `.bash_profile`:**
   ```bash
   nano ~/.bashrc
   # Or for Mac:
   nano ~/.bash_profile
   ```

2. **Add this line:**
   ```bash
   source /c/custom-xampp/.claude/aliases.sh
   # Or for WSL:
   source /mnt/c/custom-xampp/.claude/aliases.sh
   ```

3. **Reload your shell:**
   ```bash
   source ~/.bashrc
   # Or:
   exec bash
   ```

4. **Test the alias:**
   ```bash
   create-db-user
   ```

---

## Available Aliases

### Database User Management
| Alias | Function |
|-------|----------|
| `create-db-user` | Create MySQL database user |
| `createuser` | Create MySQL database user (alt) |
| `new-db-user` | Create MySQL database user (alt) |
| `adduser` | Create MySQL database user (alt) |

### Service Management
| Alias | Function |
|-------|----------|
| `xampp-start` | Start all XAMPP services |
| `xampp-stop` | Stop all XAMPP services |
| `apache-start` | Start Apache only |
| `apache-stop` | Stop Apache only |
| `mysql-start` | Start MySQL only |
| `mysql-stop` | Stop MySQL only |

---

## Usage Examples

### Create a database user
```bash
create-db-user
# Follow the interactive prompts
```

### Start XAMPP services
```bash
xampp-start
```

### Stop just Apache
```bash
apache-stop
```

---

## Troubleshooting

### PowerShell: "running scripts is disabled"
If you get an execution policy error:
```powershell
Set-ExecutionPolicy -ExecutionPolicy RemoteSigned -Scope CurrentUser
```

### Bash: "command not found"
Make sure you sourced the aliases file:
```bash
source /c/custom-xampp/.claude/aliases.sh
# Then try again:
create-db-user
```

### Aliases not persisting
Add the source command to your shell initialization file (`.bashrc`, `.bash_profile`, or PowerShell `$PROFILE`) so it runs every time you open a new shell.

---

## Claude Code Integration

When Claude Code runs commands, it automatically has permission to execute:
- `C:\custom-xampp\create-db-user.bat`
- `php C:\custom-xampp\tools\create-db-user.php`

This is configured in `.claude/settings.json` under permissions.allow.

You can ask Claude Code:
```
"Create a MySQL user named 'myapp' for database 'mydb'"
```

And it will run the command and guide you through the setup.
