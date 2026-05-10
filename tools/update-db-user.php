<?php
/**
 * Database User Permission Updater
 * Interactive script to update MySQL user permissions and access
 *
 * Usage: php update-db-user.php
 */

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_PORT', 3306);

function isWindows() {
    return strtoupper(substr(PHP_OS, 0, 3)) === 'WIN';
}

function printHeader($text) {
    echo "\n" . str_repeat("=", 60) . "\n";
    echo $text . "\n";
    echo str_repeat("=", 60) . "\n\n";
}

function printSuccess($text) {
    echo (isWindows() ? "[OK] " : "\033[32m✓ \033[0m") . $text . "\n";
}

function printError($text) {
    echo (isWindows() ? "[ERROR] " : "\033[31m✗ \033[0m") . $text . "\n";
}

function printWarning($text) {
    echo (isWindows() ? "[WARN] " : "\033[33m⚠ \033[0m") . $text . "\n";
}

function printInfo($text) {
    echo (isWindows() ? "[INFO] " : "\033[34mℹ \033[0m") . $text . "\n";
}

function readInput($prompt, $default = '') {
    $suffix = $default !== '' ? " [$default]" : '';
    echo (isWindows() ? $prompt : "\033[36m$prompt\033[0m") . "$suffix: ";
    $input = trim(fgets(STDIN));
    return $input !== '' ? $input : $default;
}

function getDatabaseList($conn) {
    $result = mysqli_query($conn, "SHOW DATABASES");
    $dbs = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $dbs[] = $row['Database'];
    }
    return $dbs;
}

function getUsersWithHosts($conn) {
    $result = mysqli_query($conn, "SELECT User, Host FROM mysql.user WHERE User != '' ORDER BY User, Host");
    $users = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $users[] = ['user' => $row['User'], 'host' => $row['Host']];
    }
    return $users;
}

function getCurrentGrants($conn, $username, $host) {
    $username = mysqli_real_escape_string($conn, $username);
    $result = mysqli_query($conn, "SHOW GRANTS FOR '$username'@'$host'");
    if (!$result) return [];
    $grants = [];
    while ($row = mysqli_fetch_row($result)) {
        $grants[] = $row[0];
    }
    return $grants;
}

function revokeAll($conn, $username, $host) {
    $username = mysqli_real_escape_string($conn, $username);
    $result = mysqli_query($conn, "REVOKE ALL PRIVILEGES, GRANT OPTION FROM '$username'@'$host'");
    if (!$result) {
        printError("Failed to revoke: " . mysqli_error($conn));
        return false;
    }
    printSuccess("Revoked existing permissions from '$username'@'$host'");
    return true;
}

function grantPermissions($conn, $username, $host, $database, $permissions) {
    $username = mysqli_real_escape_string($conn, $username);
    $perm_string = implode(', ', $permissions);
    $target = ($database === '*') ? "*.*" : "`" . mysqli_real_escape_string($conn, $database) . "`.*";
    $result = mysqli_query($conn, "GRANT $perm_string ON $target TO '$username'@'$host'");
    if (!$result) {
        printError("Failed to grant: " . mysqli_error($conn));
        return false;
    }
    printSuccess("Granted: $perm_string ON $target");
    return true;
}

function renameHost($conn, $username, $old_host, $new_host) {
    $username = mysqli_real_escape_string($conn, $username);
    $result = mysqli_query($conn, "RENAME USER '$username'@'$old_host' TO '$username'@'$new_host'");
    if (!$result) {
        printError("Failed to rename user host: " . mysqli_error($conn));
        return false;
    }
    printSuccess("Access changed: '$username'@'$old_host' → '$username'@'$new_host'");
    return true;
}

// ─── Main ────────────────────────────────────────────────────────────────────

printHeader("Database User Permission Updater");

printInfo("Connecting to MySQL...");
$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, '', DB_PORT);
if (!$conn) {
    printError("Failed to connect: " . mysqli_connect_error());
    printInfo("Make sure MySQL is running. Check password in C:\\custom-xampp\\passwords.txt");
    exit(1);
}
printSuccess("Connected to MySQL");

// ── Step 1: Pick a user ──────────────────────────────────────────────────────
echo "\n";
$users = getUsersWithHosts($conn);
if (empty($users)) {
    printError("No users found.");
    exit(1);
}

printInfo("Existing users:");
foreach ($users as $i => $u) {
    $host_label = match($u['host']) {
        'localhost', '127.0.0.1' => "local only",
        '%'                      => "anywhere",
        default                  => $u['host'],
    };
    echo "  " . ($i + 1) . ". {$u['user']}@{$u['host']}  ($host_label)\n";
}

$choice = (int) readInput("Select user number");
if ($choice < 1 || $choice > count($users)) {
    printError("Invalid selection.");
    exit(1);
}

$username = $users[$choice - 1]['user'];
$old_host = $users[$choice - 1]['host'];

// ── Step 2: Show current grants ──────────────────────────────────────────────
echo "\n";
printInfo("Current grants for '$username'@'$old_host':");
foreach (getCurrentGrants($conn, $username, $old_host) as $g) {
    echo "  $g\n";
}

// ── Step 3: Change access level? ─────────────────────────────────────────────
echo "\n";
printInfo("Select new access level:");
echo "  1. Local only (localhost) — recommended for web apps on this server\n";
echo "  2. Anywhere (%) — allows remote connections from any host\n";
echo "  3. Custom host or IP address\n";
echo "  4. Keep current ({$old_host})\n";

$host_choice = readInput("Choose option", "4");
switch ($host_choice) {
    case '1': $new_host = 'localhost'; break;
    case '2':
        $new_host = '%';
        printWarning("User will be accessible from ANY host — use a strong password!");
        break;
    case '3':
        $new_host = readInput("Enter host or IP (e.g. 192.168.1.10)");
        if (empty($new_host)) $new_host = $old_host;
        break;
    default: $new_host = $old_host;
}

// ── Step 4: Select new permissions ───────────────────────────────────────────
echo "\n";
printInfo("Select new permissions:");
echo "  1. SELECT (read-only)\n";
echo "  2. SELECT, INSERT, UPDATE, DELETE (standard app user)\n";
echo "  3. ALL PRIVILEGES (admin)\n";
echo "  4. Custom\n";

$perm_choice = readInput("Choose option", "2");
switch ($perm_choice) {
    case '1': $permissions = ['SELECT']; break;
    case '3': $permissions = ['ALL PRIVILEGES']; break;
    case '4':
        $custom = readInput("Enter permissions (comma-separated, e.g. SELECT,INSERT,UPDATE)");
        $permissions = array_map('trim', explode(',', strtoupper($custom)));
        break;
    default: $permissions = ['SELECT', 'INSERT', 'UPDATE', 'DELETE'];
}

// ── Step 5: Select database ───────────────────────────────────────────────────
echo "\n";
printInfo("Select database to grant permissions on:");
$databases = getDatabaseList($conn);
echo "  0. All databases (*)\n";
foreach ($databases as $i => $db) {
    echo "  " . ($i + 1) . ". $db\n";
}
echo "  " . (count($databases) + 1) . ". Other (type name)\n";

$db_choice = readInput("Choose option", "0");
if ($db_choice === '0' || $db_choice === '') {
    $database = '*';
} elseif (is_numeric($db_choice) && $db_choice >= 1 && $db_choice <= count($databases)) {
    $database = $databases[$db_choice - 1];
} else {
    $database = readInput("Enter database name");
}

// ── Step 6: Confirm ───────────────────────────────────────────────────────────
$host_label = match($new_host) {
    'localhost', '127.0.0.1' => "local only (localhost)",
    '%'                      => "anywhere (%)",
    default                  => $new_host,
};

echo "\n";
printInfo("Summary of changes:");
echo "  User:        $username\n";
echo "  Access:      $old_host  →  $new_host  ($host_label)\n";
echo "  Permissions: " . implode(', ', $permissions) . "\n";
echo "  Database:    " . ($database === '*' ? 'ALL DATABASES' : $database) . "\n";
echo "\n";

$confirm = readInput("Apply changes?", "y");
if (strtolower($confirm) !== 'y') {
    printInfo("Cancelled — no changes made.");
    exit(0);
}

// ── Step 7: Apply ─────────────────────────────────────────────────────────────
$ok = true;

// Rename host if changed
if ($new_host !== $old_host) {
    $ok = renameHost($conn, $username, $old_host, $new_host);
}

// Revoke then regrant permissions
if ($ok) {
    $ok = revokeAll($conn, $username, $new_host);
}

if ($ok) {
    $ok = grantPermissions($conn, $username, $new_host, $database, $permissions);
}

if ($ok) {
    mysqli_query($conn, "FLUSH PRIVILEGES");
    echo "\n";
    printSuccess("User '$username' updated successfully!");
    echo "\nUpdated connection details:\n";
    echo "  Host:        " . ($new_host === '%' ? 'any host' : $new_host) . "\n";
    echo "  Username:    $username\n";
    echo "  Permissions: " . implode(', ', $permissions) . "\n";
    echo "  Database:    " . ($database === '*' ? '(all databases)' : $database) . "\n";
} else {
    printError("Update failed — check errors above.");
    exit(1);
}

mysqli_close($conn);
exit(0);
