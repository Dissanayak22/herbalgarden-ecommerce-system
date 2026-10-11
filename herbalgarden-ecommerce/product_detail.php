<?php require_once __DIR__ . '/includes/config.php'; ?>
<?php
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$product = null;
if ($pdo && $id > 0) {
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ? LIMIT 1");
    $stmt->execute([$id]);
    $product = $stmt->fetch();
}
if (!$product) {
    header('HTTP/1.0 404 Not Found');
}
?>
<?php include __DIR__ . '/includes/header.php'; ?>
<?php if (!$product): ?>
  <h2>Product Not Found</h2>
  <p><a href="products.php">Back to products</a></p>
<?php else: ?>
  <div class="product-detail">
    <div class="image">
      <?php if (!empty($product['image'])): ?>
        <img src="assets/uploads/<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
      <?php else: ?>
        <div class="placeholder large">No Image</div>
      <?php endif; ?>
    </div>
    <div class="info">
      <h2><?php echo htmlspecialchars($product['name']); ?></h2>
      <p class="price">RS<?php echo format_price((float)$product['price']); ?></p>
      <p><?php echo nl2br(htmlspecialchars($product['description'])); ?></p>
      <form method="post" action="cart.php">
        <input type="hidden" name="action" value="add">
        <input type="hidden" name="id" value="<?php echo (int)$product['id']; ?>">
        <label>Quantity: <input type="number" name="quantity" value="1" min="1"></label>
        <button type="submit" class="btn">Add to Cart</button>
      </form>
      <p><a href="products.php">Back to Products</a></p>
    </div>
  </div>
<?php endif; ?>
<?php include __DIR__ . '/includes/footer.php'; ?>