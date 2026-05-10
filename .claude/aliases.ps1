# PowerShell aliases for XAMPP commands
# Source this in your PowerShell profile with: . C:\custom-xampp\.claude\aliases.ps1

# Database user creation command aliases
function create-db-user {
    C:\custom-xampp\create-db-user.bat
}

function createuser {
    C:\custom-xampp\create-db-user.bat
}

function new-db-user {
    C:\custom-xampp\create-db-user.bat
}

function adduser {
    C:\custom-xampp\create-db-user.bat
}

# Service management aliases
function xampp-start {
    C:\custom-xampp\xampp_start.bat
}

function xampp-stop {
    C:\custom-xampp\xampp_stop.bat
}

function apache-start {
    C:\custom-xampp\apache_start.bat
}

function apache-stop {
    C:\custom-xampp\apache_stop.bat
}

function mysql-start {
    C:\custom-xampp\mysql_start.bat
}

function mysql-stop {
    C:\custom-xampp\mysql_stop.bat
}

# Export all functions
Export-ModuleMember -Function create-db-user, createuser, new-db-user, adduser, xampp-start, xampp-stop, apache-start, apache-stop, mysql-start, mysql-stop
