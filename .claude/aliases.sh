#!/bin/bash
# Shell aliases for XAMPP commands (for Bash, WSL, Git Bash)
# Source this in your .bashrc or .bash_profile with: source C:\\custom-xampp\\.claude\\aliases.sh

# Database user creation command aliases
create-db-user() {
    php "C:\\custom-xampp\\tools\\create-db-user.php"
}

createuser() {
    php "C:\\custom-xampp\\tools\\create-db-user.php"
}

new-db-user() {
    php "C:\\custom-xampp\\tools\\create-db-user.php"
}

adduser() {
    php "C:\\custom-xampp\\tools\\create-db-user.php"
}

# Service management aliases
xampp-start() {
    "C:\\custom-xampp\\xampp_start.bat"
}

xampp-stop() {
    "C:\\custom-xampp\\xampp_stop.bat"
}

apache-start() {
    "C:\\custom-xampp\\apache_start.bat"
}

apache-stop() {
    "C:\\custom-xampp\\apache_stop.bat"
}

mysql-start() {
    "C:\\custom-xampp\\mysql_start.bat"
}

mysql-stop() {
    "C:\\custom-xampp\\mysql_stop.bat"
}

# Export aliases for subshells
export -f create-db-user createuser new-db-user adduser
export -f xampp-start xampp-stop apache-start apache-stop mysql-start mysql-stop
