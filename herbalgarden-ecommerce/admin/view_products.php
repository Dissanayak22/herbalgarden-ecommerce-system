<?php require_once __DIR__ . '/../includes/config.php'; ?>
<?php require_admin(); ?>
<?php
// Delete handler
if (isset($_GET['delete'])) {
    $delId = (int)$_GET['delete'];
    if ($pdo && $delId > 0) {
        $stmtImg = $pdo->prepare("SELECT image FROM products WHERE id=?");
        $stmtImg->execute([$delId]);
        if ($row = $stmtImg->fetch()) {
            if ($row['image']) {
                $path = __DIR__ . '/../assets/uploads/' . $row['image'];
                if (is_file($path)) { @unlink($path); }
            }
        }
        $stmt = $pdo->prepare("DELETE FROM products WHERE id=? LIMIT 1");
        $stmt->execute([$delId]);
        redirect('view_products.php');
    }
}
$products = [];
if ($pdo) {
    $products = $pdo->query("SELECT * FROM products ORDER BY created_at DESC")->fetchAll();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"><title>Products - Admin</title>
<link rel="stylesheet" href="/herbalgarden-ecommerce/assets/css/style.css" />
</head>
<body class="admin">
<header class="admin-header">
  <h1>Products</h1>
  <nav>
    <a href="dashboard.php">Dashboard</a>
    <a href="add_product.php">Add Product</a>
    <a href="view_products.php" class="active">Products</a>
    <a href="view_orders.php">Orders</a>
    <a href="logout.php">Logout</a>
  </nav>
</header>
<main class="admin-main">
  <table class="admin-table">
    <thead><tr><th>ID</th><th>Name</th><th>Category</th><th>Price</th><th>Image</th><th>Actions</th></tr></thead>
    <tbody>
      <?php foreach ($products as $p): ?>
        <tr>
          <td><?php echo (int)$p['id']; ?></td>
          <td><?php echo htmlspecialchars($p['name']); ?></td>
          <td><?php echo htmlspecialchars($p['category']); ?></td>
          <td>RS<?php echo format_price((float)$p['price']); ?></td>
          <td><?php if ($p['image']): ?><img src="/herbalgarden-ecommerce/assets/uploads/<?php echo htmlspecialchars($p['image']); ?>" alt="" style="height:40px;"><?php endif; ?></td>
          <td>
            <a href="edit_product.php?id=<?php echo (int)$p['id']; ?>">Edit</a> |
            <a href="view_products.php?delete=<?php echo (int)$p['id']; ?>" onclick="return confirm('Delete product?')">Delete</a>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</main>
</body>
</html>