<?php require_once __DIR__ . '/../includes/config.php'; ?>
<?php require_admin(); ?>
<?php
if (isset($_GET['complete'])) {
    $cid = (int)$_GET['complete'];
    if ($pdo && $cid) {
        $stmt = $pdo->prepare("UPDATE orders SET status='Completed' WHERE id=? LIMIT 1");
        $stmt->execute([$cid]);
        redirect('view_orders.php');
    }
}

$orders = [];
if ($pdo) {
    $orders = $pdo->query("SELECT * FROM orders ORDER BY created_at DESC")->fetchAll();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"><title>Orders - Admin</title>
<link rel="stylesheet" href="/herbalgarden-ecommerce/assets/css/style.css" />
</head>
<body class="admin">
<header class="admin-header">
  <h1>Orders</h1>
  <nav>
    <a href="dashboard.php">Dashboard</a>
    <a href="add_product.php">Add Product</a>
    <a href="view_products.php">Products</a>
    <a href="view_orders.php" class="active">Orders</a>
    <a href="logout.php">Logout</a>
  </nav>
</header>
<main class="admin-main">
  <table class="admin-table">
    <thead><tr><th>ID</th><th>Name</th><th>Email</th><th>Total</th><th>Status</th><th>Date</th><th>Actions</th></tr></thead>
    <tbody>
      <?php foreach ($orders as $o): ?>
        <tr>
          <td><?php echo (int)$o['id']; ?></td>
          <td><?php echo htmlspecialchars($o['name']); ?></td>
          <td><?php echo htmlspecialchars($o['email']); ?></td>
          <td>RS<?php echo format_price((float)$o['total']); ?></td>
          <td><?php echo htmlspecialchars($o['status']); ?></td>
          <td><?php echo htmlspecialchars($o['created_at']); ?></td>
          <td>
            <a href="order_detail.php?id=<?php echo (int)$o['id']; ?>">View</a>
            <?php if ($o['status'] !== 'Completed'): ?> | <a href="view_orders.php?complete=<?php echo (int)$o['id']; ?>" onclick="return confirm('Mark as completed?')">Complete</a><?php endif; ?>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</main>
</body>
</html>