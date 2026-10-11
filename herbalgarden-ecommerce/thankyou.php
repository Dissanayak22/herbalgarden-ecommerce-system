<?php require_once __DIR__ . '/includes/config.php'; ?>
<?php include __DIR__ . '/includes/header.php'; ?>
<?php $orderId = isset($_GET['order']) ? (int)$_GET['order'] : 0; ?>
<h2>Thank You!</h2>
<p>Your order<?php if ($orderId) echo ' #' . $orderId; ?> has been placed successfully. We'll process it shortly.</p>
<p><a href="products.php" class="btn-sm">Continue Shopping</a></p>
<?php include __DIR__ . '/includes/footer.php'; ?>