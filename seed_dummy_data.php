<?php
// Idempotent dummy data seeder for development.
// Visit once in browser: http://localhost/herbalgarden-ecommerce/seed_dummy_data.php
// Remove or protect in production.
require_once __DIR__ . '/includes/config.php';

if (!$pdo) { die('Database connection not available. Run create_database.php first.'); }

$addedProducts = 0; $addedOrders = 0; $addedItems = 0;

// Seed products only if less than a threshold (prevents duplicates)
$existing = (int)$pdo->query("SELECT COUNT(*) FROM products")->fetchColumn();
if ($existing < 10) {
    $products = [
        ['Calendula Salve','Soothing salve for minor skin irritations.',7.90,'Skin Care'],
        ['Elderberry Syrup','Immune support elderberry botanical syrup.',13.50,'Supplements'],
        ['Holy Basil (Tulsi) Tea','Adaptogenic tulsi tea for calm focus.',6.20,'Tea'],
        ['Lemongrass Stalks','Aromatic lemongrass for infusion and cooking.',3.95,'Herbs'],
        ['Arnica Oil','Topical arnica infusion for muscle relief.',10.75,'Essential Oils'],
    ];
    $ins = $pdo->prepare("INSERT INTO products (name, description, price, category) VALUES (?,?,?,?)");
    foreach ($products as $p) {
        $ins->execute([$p[0], $p[1], $p[2], $p[3]]);
        $addedProducts++;
    }
}

// Seed sample orders only if none exist
$orderCount = (int)$pdo->query("SELECT COUNT(*) FROM orders")->fetchColumn();
if ($orderCount === 0) {
    $pdo->beginTransaction();
    try {
        // Create two sample orders
        $orderStmt = $pdo->prepare("INSERT INTO orders (name, email, address, total, status) VALUES (?,?,?,?,?)");
        $itemStmt = $pdo->prepare("INSERT INTO order_items (order_id, product_id, quantity, subtotal) VALUES (?,?,?,?)");

        // Fetch some product IDs
        $prodIds = $pdo->query("SELECT id, price FROM products ORDER BY id ASC LIMIT 6")->fetchAll();
        if (count($prodIds) >= 3) {
            // Order 1
            $orderTotal1 = 0; $items1 = [];
            $items1[] = ['pid'=>$prodIds[0]['id'],'qty'=>2,'price'=>$prodIds[0]['price']];
            $items1[] = ['pid'=>$prodIds[1]['id'],'qty'=>1,'price'=>$prodIds[1]['price']];
            foreach ($items1 as $it) { $orderTotal1 += $it['qty'] * $it['price']; }
            $orderStmt->execute(['Jane Herbalist','jane@example.com','123 Green Lane\nHerb City',''.$orderTotal1,'Pending']);
            $orderId1 = $pdo->lastInsertId();
            foreach ($items1 as $it) { $itemStmt->execute([$orderId1,$it['pid'],$it['qty'],$it['qty']*$it['price']]); $addedItems++; }
            $addedOrders++;

            // Order 2
            $orderTotal2 = 0; $items2 = [];
            $items2[] = ['pid'=>$prodIds[2]['id'],'qty'=>3,'price'=>$prodIds[2]['price']];
            if (isset($prodIds[3])) $items2[] = ['pid'=>$prodIds[3]['id'],'qty'=>1,'price'=>$prodIds[3]['price']];
            foreach ($items2 as $it) { $orderTotal2 += $it['qty'] * $it['price']; }
            $orderStmt->execute(['Mark Botanist','mark@example.com','789 Healing Rd\nPlantville',''.$orderTotal2,'Completed']);
            $orderId2 = $pdo->lastInsertId();
            foreach ($items2 as $it) { $itemStmt->execute([$orderId2,$it['pid'],$it['qty'],$it['qty']*$it['price']]); $addedItems++; }
            $addedOrders++;
        }
        $pdo->commit();
    } catch (Throwable $e) {
        $pdo->rollBack();
        die('Order seed failed: ' . $e->getMessage());
    }
}

header('Content-Type: text/plain');
echo "Dummy data seeding complete\n";
echo "Products added: $addedProducts\n";
echo "Orders added: $addedOrders\n";
echo "Order items added: $addedItems\n";
