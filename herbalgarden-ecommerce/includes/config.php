<?php
// Core configuration, database connection, and helper utilities
// Adjust DB credentials if needed.

declare(strict_types=1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$DB_HOST = 'localhost';
$DB_NAME = 'herbalgarden_db';
$DB_USER = 'root';
$DB_PASS = '';
$DB_CHARSET = 'utf8mb4';

$dsn = "mysql:host=$DB_HOST;dbname=$DB_NAME;charset=$DB_CHARSET";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $DB_USER, $DB_PASS, $options);
} catch (PDOException $e) {
    // If DB does not exist yet (before running create_database.php) we suppress fatal error on normal pages
    // For create_database.php we'll build a separate connection without db name.
    $pdo = null; // Allow pages like create_database.php to still run.
}

function db_raw_connection_no_db(): PDO {
    global $DB_HOST, $DB_USER, $DB_PASS, $DB_CHARSET;
    $dsn = "mysql:host=$DB_HOST;charset=$DB_CHARSET";
    return new PDO($dsn, $DB_USER, $DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
}

function sanitize(string $value): string {
    return htmlspecialchars(trim($value), ENT_QUOTES, 'UTF-8');
}

function redirect(string $path): void {
    header("Location: $path");
    exit;
}

function is_admin_logged_in(): bool {
    return isset($_SESSION['admin_id']);
}

function require_admin(): void {
    if (!is_admin_logged_in()) {
        redirect('login.php');
    }
}

function format_price(float $price): string {
    return number_format($price, 2);
}

?>