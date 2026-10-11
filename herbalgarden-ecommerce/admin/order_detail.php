<?php require_once __DIR__ . '/../includes/config.php'; ?>
<?php require_admin(); ?>
<?php
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if (!$id) { redirect('view_orders.php'); }
$order = null; $items = [];
if ($pdo) {
    $stmt = $pdo->prepare("SELECT * FROM orders WHERE id=? LIMIT 1");
    $stmt->execute([$id]);
    $order = $stmt->fetch();
    if ($order) {
        $stmtItems = $pdo->prepare("SELECT oi.*, p.name FROM order_items oi JOIN products p ON oi.product_id=p.id WHERE oi.order_id=?");
        $stmtItems->execute([$id]);
        $items = $stmtItems->fetchAll();
    }
}
if (!$order) { redirect('view_orders.php'); }
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"><title>Order #<?php echo $id; ?> - Admin</title>
<link rel="stylesheet" href="/herbalgarden-ecommerce/assets/css/style.css" />
</head>
<body class="admin">
<header class="admin-header">
  <h1>Order #<?php echo $id; ?></h1>
  <nav>
    <a href="dashboard.php">Dashboard</a>
    <a href="add_product.php">Add Product</a>
    <a href="view_products.php">Products</a>
    <a href="view_orders.php" class="active">Orders</a>
    <a href="logout.php">Logout</a>
  </nav>
</header>
<main class="admin-main">
  <section class="order-summary">
    <h2>Summary</h2>
    <p><strong>Name:</strong> <?php echo htmlspecialchars($order['name']); ?></p>
    <p><strong>Email:</strong> <?php echo htmlspecialchars($order['email']); ?></p>
    <p><strong>Address:</strong><br><?php echo nl2br(htmlspecialchars($order['address'])); ?></p>
    <p><strong>Total:</strong> RS<?php echo format_price((float)$order['total']); ?></p>
    <p><strong>Status:</strong> <?php echo htmlspecialchars($order['status']); ?></p>
    <?php if ($order['status'] !== 'Completed'): ?>
      <p><a class="btn-sm" href="view_orders.php?complete=<?php echo $id; ?>" onclick="return confirm('Mark as completed?')">Mark Completed</a></p>
    <?php endif; ?>
  </section>
  <section>
    <h2>Items</h2>
    <table class="admin-table">
      <thead><tr><th>Product</th><th>Qty</th><th>Subtotal</th></tr></thead>
      <tbody>
        <?php foreach ($items as $it): ?>
          <tr>
            <td><?php echo htmlspecialchars($it['name']); ?></td>
            <td><?php echo (int)$it['quantity']; ?></td>
            <td>RS<?php echo format_price((float)$it['subtotal']); ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </section>
</main>
</body>
</html>