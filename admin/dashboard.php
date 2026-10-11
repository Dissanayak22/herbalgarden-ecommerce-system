<?php require_once __DIR__ . '/../includes/config.php'; ?>
<?php require_admin(); ?>
<?php
$productCount = $orderCount = $pendingCount = 0;
if ($pdo) {
    $productCount = (int)$pdo->query("SELECT COUNT(*) FROM products")->fetchColumn();
    $orderCount = (int)$pdo->query("SELECT COUNT(*) FROM orders")->fetchColumn();
    $pendingCount = (int)$pdo->query("SELECT COUNT(*) FROM orders WHERE status='Pending'")->fetchColumn();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"><title>Admin Dashboard - HerbalGarden</title>
<link rel="stylesheet" href="/herbalgarden-ecommerce/assets/css/style.css" />
</head>
<body class="admin">
  <header class="admin-header">
    <h1>Dashboard</h1>
    <nav>
      <a href="dashboard.php">Dashboard</a>
      <a href="add_product.php">Add Product</a>
      <a href="view_products.php">Products</a>
      <a href="view_orders.php">Orders</a>
      <a href="logout.php" onclick="return confirm('Logout?')">Logout (<?php echo htmlspecialchars($_SESSION['admin_username']); ?>)</a>
    </nav>
  </header>
  <main class="admin-main">
    <div class="stats-grid">
      <div class="stat-box"><h3>Products</h3><p><?php echo $productCount; ?></p></div>
      <div class="stat-box"><h3>Orders</h3><p><?php echo $orderCount; ?></p></div>
      <div class="stat-box"><h3>Pending</h3><p><?php echo $pendingCount; ?></p></div>
    </div>
  </main>
</body>
</html>