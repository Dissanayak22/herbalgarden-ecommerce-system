<?php require_once __DIR__ . '/includes/config.php'; ?>
<?php
if (!isset($_SESSION['cart'])) { $_SESSION['cart'] = []; }

// Handle actions: add, update, remove, clear
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Support remove button that sets name="remove" value="<id>"
  if (isset($_POST['remove'])) {
    $rid = (int)$_POST['remove'];
    unset($_SESSION['cart'][$rid]);
    redirect('cart.php');
  }
  $action = $_POST['action'] ?? '';
    if ($action === 'add') {
        $id = (int)($_POST['id'] ?? 0);
        $qty = max(1, (int)($_POST['quantity'] ?? 1));
        if ($pdo && $id > 0) {
            // Fetch product to validate and get details
            $stmt = $pdo->prepare("SELECT id, name, price, image FROM products WHERE id = ? LIMIT 1");
            $stmt->execute([$id]);
            if ($prod = $stmt->fetch()) {
                if (isset($_SESSION['cart'][$id])) {
                    $_SESSION['cart'][$id]['quantity'] += $qty;
                    $_SESSION['cart'][$id]['subtotal'] = $_SESSION['cart'][$id]['quantity'] * $_SESSION['cart'][$id]['price'];
                } else {
                    $_SESSION['cart'][$id] = [
                        'id' => $prod['id'],
                        'name' => $prod['name'],
                        'price' => (float)$prod['price'],
                        'image' => $prod['image'],
                        'quantity' => $qty,
                        'subtotal' => (float)$prod['price'] * $qty,
                    ];
                }
            }
        }
        redirect('cart.php');
    } elseif ($action === 'update') {
        foreach ($_POST['qty'] as $id => $qty) {
            $id = (int)$id; $qty = (int)$qty;
            if ($qty <= 0) { unset($_SESSION['cart'][$id]); continue; }
            if (isset($_SESSION['cart'][$id])) {
                $_SESSION['cart'][$id]['quantity'] = $qty;
                $_SESSION['cart'][$id]['subtotal'] = $qty * $_SESSION['cart'][$id]['price'];
            }
        }
        redirect('cart.php');
    } elseif ($action === 'remove') {
        $id = (int)($_POST['id'] ?? 0);
        unset($_SESSION['cart'][$id]);
        redirect('cart.php');
    } elseif ($action === 'clear') {
        $_SESSION['cart'] = [];
        redirect('cart.php');
    }
}

$total = 0.0;
// Sanitize cart structure in case of corruption
foreach ($_SESSION['cart'] as $key => $item) {
  if (!is_array($item) || !isset($item['price'], $item['quantity'])) {
    unset($_SESSION['cart'][$key]);
    continue;
  }
  // Ensure numeric types
  $price = isset($item['price']) ? (float)$item['price'] : 0.0;
  $qty   = isset($item['quantity']) ? (int)$item['quantity'] : 0;
  if ($qty <= 0) { unset($_SESSION['cart'][$key]); continue; }
  // Recompute subtotal defensively
  $_SESSION['cart'][$key]['subtotal'] = $price * $qty;
  $total += $_SESSION['cart'][$key]['subtotal'];
}
?>
<?php include __DIR__ . '/includes/header.php'; ?>
<h2>Your Cart</h2>
<?php if (empty($_SESSION['cart'])): ?>
  <p>Your cart is empty. <a href="products.php">Browse products</a></p>
<?php else: ?>
<form method="post">
  <input type="hidden" name="action" value="update" />
  <table class="cart-table">
    <thead><tr><th>Product</th><th>Price</th><th>Quantity</th><th>Subtotal</th><th></th></tr></thead>
    <tbody>
      <?php foreach ($_SESSION['cart'] as $c): if (!is_array($c)) continue; ?>
        <tr>
          <td><?php echo htmlspecialchars($c['name']); ?></td>
          <td>RS<?php echo format_price((float)$c['price']); ?></td>
          <td><input type="number" name="qty[<?php echo $c['id']; ?>]" value="<?php echo $c['quantity']; ?>" min="0" /></td>
          <td>RS<?php echo format_price((float)$c['subtotal']); ?></td>
          <td>
            <button name="remove" value="<?php echo $c['id']; ?>" class="link-btn" formaction="cart.php" formmethod="post" onclick="return confirm('Remove item?')">Remove</button>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
  <div class="cart-actions">
    <button type="submit" class="btn-sm">Update Cart</button>
    <input type="hidden" name="action" value="update" />
  </div>
</form>
<form id="remove-form" method="post" style="display:none;">
  <input type="hidden" name="action" value="remove" />
  <input type="hidden" name="id" id="remove-id" />
</form>
<form method="post" onsubmit="return confirm('Clear entire cart?')" style="display:inline-block; margin-right:1rem;">
  <input type="hidden" name="action" value="clear">
  <button class="btn-sm secondary">Clear Cart</button>
</form>
<a class="btn" href="checkout.php">Proceed to Checkout (RS<?php echo format_price($total); ?>)</a>
<?php endif; ?>
<?php include __DIR__ . '/includes/footer.php'; ?>