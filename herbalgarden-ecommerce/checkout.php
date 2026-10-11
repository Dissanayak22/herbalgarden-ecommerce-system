<?php require_once __DIR__ . '/includes/config.php'; ?>
<?php
if (empty($_SESSION['cart'])) {
    redirect('cart.php');
}

$errors = [];
$name = $email = $address = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = sanitize($_POST['name'] ?? '');
    $email = sanitize($_POST['email'] ?? '');
    $address = sanitize($_POST['address'] ?? '');

    if ($name === '') $errors[] = 'Name required';
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Valid email required';
    if ($address === '') $errors[] = 'Address required';

    if (!$errors && $pdo) {
        $pdo->beginTransaction();
        try {
            $total = 0.0; foreach ($_SESSION['cart'] as $it) { $total += $it['subtotal']; }
            $stmt = $pdo->prepare("INSERT INTO orders (name, email, address, total, status) VALUES (?,?,?,?, 'Pending')");
            $stmt->execute([$name, $email, $address, $total, $phonenumber]);
            $orderId = (int)$pdo->lastInsertId();

            $itemStmt = $pdo->prepare("INSERT INTO order_items (order_id, product_id, quantity, subtotal) VALUES (?,?,?,?)");
            foreach ($_SESSION['cart'] as $it) {
                $itemStmt->execute([$orderId, $it['id'], $it['quantity'], $it['subtotal']]);
            }
            $pdo->commit();
            $_SESSION['cart'] = [];
            redirect('thankyou.php?order=' . $orderId);
        } catch (Throwable $e) {
            $pdo->rollBack();
            $errors[] = 'Order failed: ' . $e->getMessage();
        }
    }
}
?>
<?php include __DIR__ . '/includes/header.php'; ?>
<h2>Checkout</h2>
<?php if ($errors): ?>
  <div class="errors">
    <ul><?php foreach ($errors as $er) echo '<li>'.htmlspecialchars($er).'</li>'; ?></ul>
  </div>
<?php endif; ?>
<form method="post" class="checkout-form">
  <label>Name
    <input type="text" name="name" value="<?php echo htmlspecialchars($name); ?>" required>
  </label>
  <label>Email
    <input type="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
  </label>
  <label>Address
    <textarea name="address" required><?php echo htmlspecialchars($address); ?></textarea>
  </label>
  <button type="submit" class="btn">Place Order</button>
</form>
<?php include __DIR__ . '/includes/footer.php'; ?>