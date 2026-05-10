<?php
/**
 * Database User Creator
 * Interactive script to create MySQL users and grant permissions
 *
 * Usage: php create-db-user.php
 */

// XAMPP MySQL configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', ''); // Default XAMPP root password is empty, adjust if needed
define('DB_PORT', 3306);

// Color output for CLI
class CLIColor {
    const RESET = "\033[0m";
    const RED = "\033[31m";
    const GREEN = "\033[32m";
    const YELLOW = "\033[33m";
    const BLUE = "\033[34m";
    const CYAN = "\033[36m";
}

function isWindows() {
    return strtoupper(substr(PHP_OS, 0, 3)) === 'WIN';
}

function printHeader($text) {
    echo "\n" . str_repeat("=", 60) . "\n";
    echo $text . "\n";
    echo str_repeat("=", 60) . "\n\n";
}

function printSuccess($text) {
    if (!isWindows()) {
        echo CLIColor::GREEN . "✓ " . $text . CLIColor::RESET . "\n";
    } else {
        echo "[OK] " . $text . "\n";
    }
}

function printError($text) {
    if (!isWindows()) {
        echo CLIColor::RED . "✗ " . $text . CLIColor::RESET . "\n";
    } else {
        echo "[ERROR] " . $text . "\n";
    }
}

function printWarning($text) {
    if (!isWindows()) {
        echo CLIColor::YELLOW . "⚠ " . $text . CLIColor::RESET . "\n";
    } else {
        echo "[WARN] " . $text . "\n";
    }
}

function printInfo($text) {
    if (!isWindows()) {
        echo CLIColor::BLUE . "ℹ " . $text . CLIColor::RESET . "\n";
    } else {
        echo "[INFO] " . $text . "\n";
    }
}

function readInput($prompt, $default = '') {
    echo (isWindows() ? $prompt : CLIColor::CYAN . $prompt . CLIColor::RESET);
    if ($default) {
        echo " [" . $default . "]";
    }
    echo ": ";

    $input = trim(fgets(STDIN));
    return $input ?: $default;
}

function readPassword($prompt) {
    echo (isWindows() ? $prompt : CLIColor::CYAN . $prompt . CLIColor::RESET) . ": ";

    if (isWindows()) {
        // Windows: use stty alternative or plain input
        return trim(fgets(STDIN));
    } else {
        // Linux/Mac: disable echo
        system('stty -echo');
        $password = trim(fgets(STDIN));
        system('stty echo');
        echo "\n";
        return $password;
    }
}

function validateUsername($username) {
    if (empty($username)) {
        return false;
    }
    if (strlen($username) > 32) {
        printError("Username too long (max 32 characters)");
        return false;
    }
    if (!preg_match('/^[a-zA-Z0-9_-]+$/', $username)) {
        printError("Username can only contain letters, numbers, underscore, and hyphen");
        return false;
    }
    return true;
}

function validateDatabaseName($dbname) {
    if (empty($dbname)) {
        return false;
    }
    if (strlen($dbname) > 64) {
        printError("Database name too long (max 64 characters)");
        return false;
    }
    if (!preg_match('/^[a-zA-Z0-9_]+$/', $dbname)) {
        printError("Database name can only contain letters, numbers, and underscore");
        return false;
    }
    return true;
}

function getDatabaseList($conn) {
    try {
        $result = mysqli_query($conn, "SHOW DATABASES");
        $databases = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $databases[] = $row['Database'];
        }
        return $databases;
    } catch (Exception $e) {
        return [];
    }
}

function getUserList($conn) {
    try {
        $result = mysqli_query($conn, "SELECT USER FROM mysql.user");
        $users = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $users[] = $row['USER'];
        }
        return $users;
    } catch (Exception $e) {
        return [];
    }
}

function userExists($conn, $username, $host) {
    try {
        $result = mysqli_query($conn, "SELECT USER FROM mysql.user WHERE USER = '$username' AND HOST = '$host'");
        return mysqli_num_rows($result) > 0;
    } catch (Exception $e) {
        return false;
    }
}

function databaseExists($conn, $dbname) {
    try {
        $result = mysqli_query($conn, "SHOW DATABASES LIKE '$dbname'");
        return mysqli_num_rows($result) > 0;
    } catch (Exception $e) {
        return false;
    }
}

function createUser($conn, $username, $password, $host) {
    try {
        // Escape username for SQL
        $username = mysqli_real_escape_string($conn, $username);
        $password = mysqli_real_escape_string($conn, $password);

        $query = "CREATE USER '$username'@'$host' IDENTIFIED BY '$password'";
        if (mysqli_query($conn, $query)) {
            printSuccess("User '$username'@'$host' created successfully");
            return true;
        } else {
            printError("Failed to create user: " . mysqli_error($conn));
            return false;
        }
    } catch (Exception $e) {
        printError("Error creating user: " . $e->getMessage());
        return false;
    }
}

function grantPermissions($conn, $username, $host, $database, $permissions) {
    try {
        $username = mysqli_real_escape_string($conn, $username);
        $database = mysqli_real_escape_string($conn, $database);

        $perm_string = implode(', ', $permissions);

        if ($database === '*') {
            $target = "*.*";
        } else {
            $target = "`$database`.*";
        }

        $query = "GRANT $perm_string ON $target TO '$username'@'$host'";
        if (mysqli_query($conn, $query)) {
            printSuccess("Permissions granted: " . $perm_string . " on $target");
            return true;
        } else {
            printError("Failed to grant permissions: " . mysqli_error($conn));
            return false;
        }
    } catch (Exception $e) {
        printError("Error granting permissions: " . $e->getMessage());
        return false;
    }
}

function flushPrivileges($conn) {
    try {
        if (mysqli_query($conn, "FLUSH PRIVILEGES")) {
            printSuccess("Privileges flushed");
            return true;
        }
    } catch (Exception $e) {
        printError("Error flushing privileges: " . $e->getMessage());
    }
    return false;
}

// Main execution
printHeader("Database User Creator");

// Connect to MySQL
printInfo("Connecting to MySQL server...");
$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, '', DB_PORT);

if (!$conn) {
    printError("Failed to connect to MySQL: " . mysqli_connect_error());
    printInfo("Make sure MySQL is running and the root password is correct.");
    if (file_exists('C:\\custom-xampp\\passwords.txt')) {
        printInfo("Check root password in: C:\\custom-xampp\\passwords.txt");
    }
    exit(1);
}

printSuccess("Connected to MySQL");

// Get input
echo "\n";
$username = '';
while (!validateUsername($username)) {
    $username = readInput("Enter new username");
}

$host = readInput("Enter host", "localhost");
if (empty($host)) {
    $host = "localhost";
}

// Check if user already exists
if (userExists($conn, $username, $host)) {
    printWarning("User '$username'@'$host' already exists. Proceeding to grant permissions...");
} else {
    $password = '';
    $password_confirm = '';
    while (empty($password) || $password !== $password_confirm) {
        $password = readPassword("Enter password");
        if (empty($password)) {
            printWarning("Password cannot be empty");
            continue;
        }
        $password_confirm = readPassword("Confirm password");
        if ($password !== $password_confirm) {
            printError("Passwords do not match");
        }
    }

    if (!createUser($conn, $username, $password, $host)) {
        exit(1);
    }
}

// Permission selection
echo "\n";
printInfo("Select permissions to grant:");
echo "  1. SELECT (read-only access)\n";
echo "  2. SELECT, INSERT, UPDATE, DELETE (full data modification)\n";
echo "  3. ALL PRIVILEGES (full administrative access)\n";
echo "  4. Custom permissions\n";

$perm_choice = readInput("Choose option", "2");

$permissions = [];
switch ($perm_choice) {
    case '1':
        $permissions = ['SELECT'];
        break;
    case '2':
        $permissions = ['SELECT', 'INSERT', 'UPDATE', 'DELETE'];
        break;
    case '3':
        $permissions = ['ALL PRIVILEGES'];
        break;
    case '4':
        $custom = readInput("Enter permissions (comma-separated, e.g., SELECT,INSERT,UPDATE,DELETE)");
        $permissions = array_map('trim', explode(',', $custom));
        break;
    default:
        $permissions = ['SELECT', 'INSERT', 'UPDATE', 'DELETE'];
}

// Database selection
echo "\n";
printInfo("Select database to grant permissions on:");

$databases = getDatabaseList($conn);
echo "  0. All databases (*)\n";
foreach ($databases as $index => $db) {
    echo "  " . ($index + 1) . ". " . $db . "\n";
}
echo "  " . (count($databases) + 1) . ". Other database name\n";

$db_choice = readInput("Choose option", "0");

$database = '';
if ($db_choice === '0' || empty($db_choice)) {
    $database = '*';
} elseif (is_numeric($db_choice) && $db_choice > 0 && $db_choice <= count($databases)) {
    $database = $databases[$db_choice - 1];
} else {
    $database = readInput("Enter database name");
    if (!validateDatabaseName($database)) {
        printError("Invalid database name");
        exit(1);
    }
}

// Confirm and execute
echo "\n";
printInfo("Summary:");
echo "  Username: $username\n";
echo "  Host: $host\n";
echo "  Permissions: " . implode(", ", $permissions) . "\n";
echo "  Database: " . ($database === '*' ? 'ALL DATABASES' : $database) . "\n";
echo "\n";

$confirm = readInput("Proceed?", "y");
if (strtolower($confirm) !== 'y' && strtolower($confirm) !== 'yes') {
    printInfo("Cancelled");
    exit(0);
}

// Grant permissions
if (grantPermissions($conn, $username, $host, $database, $permissions)) {
    flushPrivileges($conn);
    echo "\n";
    printSuccess("User created and permissions granted successfully!");
    echo "\nConnection details for your application:\n";
    echo "  Host: $host\n";
    echo "  Username: $username\n";
    echo "  Database: " . ($database === '*' ? '(all databases)' : $database) . "\n";
} else {
    printError("Failed to complete the operation");
    exit(1);
}

mysqli_close($conn);
exit(0);
