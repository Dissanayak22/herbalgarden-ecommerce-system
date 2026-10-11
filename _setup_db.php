<?php
// Temporary DB setup script - uses 127.0.0.1 to bypass Windows named-pipe issue
$host = '127.0.0.1';
$user = 'root';
$pass = 'wampp';
$port = 3306;

try {
    $pdo = new PDO("mysql:host=$host;port=$port;charset=utf8mb4", $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_TIMEOUT => 5,
    ]);
    echo "Connected to MySQL " . $pdo->query('SELECT VERSION()')->fetchColumn() . "\n";

    $sql = file_get_contents(__DIR__ . '/herbalgarden_db.sql');
    // Remove comment lines and execute statement by statement
    $statements = array_filter(
        array_map('trim', explode(';', $sql)),
        fn($s) => $s !== '' && !preg_match('/^--/', ltrim($s))
    );
    foreach ($statements as $stmt) {
        if (trim($stmt) === '') continue;
        try {
            $pdo->exec($stmt);
            echo "OK: " . substr(trim($stmt), 0, 60) . "\n";
        } catch (PDOException $e) {
            echo "SKIP/ERR: " . $e->getMessage() . "\n";
        }
    }
    echo "\nDone! Database herbalgarden_db is ready.\n";
} catch (PDOException $e) {
    echo "FATAL: " . $e->getMessage() . "\n";
}
