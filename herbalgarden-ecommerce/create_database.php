<?php
// Run this once to create database, tables, and default admin user.
require_once __DIR__ . '/includes/config.php';

$adminUser = 'admin';
$adminPassPlain = 'admin123';

try {
    $pdoNoDb = db_raw_connection_no_db();
    $pdoNoDb->exec("CREATE DATABASE IF NOT EXISTS `herbalgarden_db` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    echo "Database created or already exists.<br>";

    // Connect to the newly created DB
    $pdoDb = new PDO("mysql:host=localhost;dbname=herbalgarden_db;charset=utf8mb4", 'root', '', [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);

    // Create tables
    $pdoDb->exec("CREATE TABLE IF NOT EXISTS products (\n        id INT AUTO_INCREMENT PRIMARY KEY,\n        name VARCHAR(100) NOT NULL,\n        description TEXT,\n        price DECIMAL(10,2) NOT NULL DEFAULT 0.00,\n        image VARCHAR(255),\n        category VARCHAR(50),\n        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP\n    ) ENGINE=InnoDB");

    $pdoDb->exec("CREATE TABLE IF NOT EXISTS orders (\n        id INT AUTO_INCREMENT PRIMARY KEY,\n        name VARCHAR(100) NOT NULL,\n        email VARCHAR(100) NOT NULL,\n        address TEXT NOT NULL,\n        total DECIMAL(10,2) NOT NULL DEFAULT 0.00,\n        status VARCHAR(50) NOT NULL DEFAULT 'Pending',\n        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP\n    ) ENGINE=InnoDB");

    $pdoDb->exec("CREATE TABLE IF NOT EXISTS order_items (\n        id INT AUTO_INCREMENT PRIMARY KEY,\n        order_id INT NOT NULL,\n        product_id INT NOT NULL,\n        quantity INT NOT NULL DEFAULT 1,\n        subtotal DECIMAL(10,2) NOT NULL DEFAULT 0.00,\n        CONSTRAINT fk_order FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,\n        CONSTRAINT fk_product FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE\n    ) ENGINE=InnoDB");

    $pdoDb->exec("CREATE TABLE IF NOT EXISTS admin_users (\n        id INT AUTO_INCREMENT PRIMARY KEY,\n        username VARCHAR(50) NOT NULL UNIQUE,\n        password VARCHAR(255) NOT NULL\n    ) ENGINE=InnoDB");

    echo "Tables created or already exist.<br>";

    // Insert default admin if not exists
    $stmt = $pdoDb->prepare("SELECT id FROM admin_users WHERE username = ? LIMIT 1");
    $stmt->execute([$adminUser]);
    if (!$stmt->fetch()) {
        $hash = password_hash($adminPassPlain, PASSWORD_DEFAULT);
        $ins = $pdoDb->prepare("INSERT INTO admin_users (username, password) VALUES (?, ?)");
        $ins->execute([$adminUser, $hash]);
        echo "Default admin user created (username: $adminUser / password: $adminPassPlain).<br>";
    } else {
        echo "Admin user already exists.<br>";
    }

    echo "All done! You can now delete or protect this file.";
} catch (Throwable $e) {
    echo "Error: " . $e->getMessage();
}
