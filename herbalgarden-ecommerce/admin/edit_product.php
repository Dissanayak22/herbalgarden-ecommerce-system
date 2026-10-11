<?php require_once __DIR__ . '/../includes/config.php'; ?>
<?php require_admin(); ?>
<?php
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if (!$id) { redirect('view_products.php'); }
$stmt = $pdo->prepare("SELECT * FROM products WHERE id=? LIMIT 1");
$stmt->execute([$id]);
$product = $stmt->fetch();
if (!$product) { redirect('view_products.php'); }

$name = $product['name'];
$description = $product['description'];
$price = $product['price'];
$category = $product['category'];
$message = '';
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = sanitize($_POST['name'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $price = trim($_POST['price'] ?? '0');
    $category = sanitize($_POST['category'] ?? '');
    $imageName = $product['image'];

    if ($name === '') $errors[] = 'Name required';
    if (!is_numeric($price)) $errors[] = 'Valid price required';

    if (!empty($_FILES['image']['name'])) {
        $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg','jpeg','png','gif','webp'];
        if (!in_array($ext, $allowed)) {
            $errors[] = 'Invalid image type';
        } else {
            // remove old
            if ($imageName) {
                $oldPath = __DIR__ . '/../assets/uploads/' . $imageName;
                if (is_file($oldPath)) { @unlink($oldPath); }
            }
            $imageName = 'prod_' . time() . '_' . mt_rand(1000,9999) . '.' . $ext;
            $dest = __DIR__ . '/../assets/uploads/' . $imageName;
            if (!move_uploaded_file($_FILES['image']['tmp_name'], $dest)) {
                $errors[] = 'Image upload failed';
            }
        }
    }

    if (!$errors) {
        $upd = $pdo->prepare("UPDATE products SET name=?, description=?, price=?, image=?, category=? WHERE id=? LIMIT 1");
        $upd->execute([$name, $description, (float)$price, $imageName, $category, $id]);
        $message = 'Updated successfully';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"><title>Edit Product - Admin</title>
<link rel="stylesheet" href="/herbalgarden-ecommerce/assets/css/style.css" />
</head>
<body class="admin">
<header class="admin-header">
  <h1>Edit Product</h1>
  <nav>
    <a href="dashboard.php">Dashboard</a>
    <a href="add_product.php">Add Product</a>
    <a href="view_products.php">Products</a>
    <a href="view_orders.php">Orders</a>
    <a href="logout.php">Logout</a>
  </nav>
</header>
<main class="admin-main">
  <?php if ($message): ?><div class="success"><?php echo htmlspecialchars($message); ?></div><?php endif; ?>
  <?php if ($errors): ?><div class="errors"><ul><?php foreach ($errors as $e) echo '<li>'.htmlspecialchars($e).'</li>'; ?></ul></div><?php endif; ?>
  <form method="post" enctype="multipart/form-data" class="admin-form">
    <label>Name<input type="text" name="name" value="<?php echo htmlspecialchars($name); ?>" required></label>
    <label>Category<input type="text" name="category" value="<?php echo htmlspecialchars($category); ?>"></label>
    <label>Price<input type="number" step="0.01" name="price" value="<?php echo htmlspecialchars($price); ?>" required></label>
    <label>Description<textarea name="description" rows="5"><?php echo htmlspecialchars($description); ?></textarea></label>
    <label>Image (leave blank to keep current)<input type="file" name="image" accept="image/*"></label>
    <?php if ($product['image']): ?><p>Current: <img src="/herbalgarden-ecommerce/assets/uploads/<?php echo htmlspecialchars($product['image']); ?>" style="height:60px;" alt=""></p><?php endif; ?>
    <button type="submit" class="btn">Update</button>
  </form>
</main>
</body>
</html>