<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>VENDOL</title>
<link rel="stylesheet" href="/herbalgarden-ecommerce/assets/css/style.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" integrity="sha512-yH6Z0F+J5m8G1ZC8X0Sqn5GkT7F7lZl5GJzYjQpLrZ9G0K2kLw7P1P1Zg1P+XzV3JrJ6uZ7V+JqfV9G7V2lYog==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body>
<div class="header-top-bar">
  <div class="container top-bar-inner">
    <div class="top-info left">
  <span class="ti-item"><i class="fa fa-phone"></i> +1 234 567 8900</span>
      <span class="ti-sep">|</span>
  <span class="ti-item"><i class="fa fa-envelope"></i> info@vendollanka.com</span>
    </div>
    <div class="top-info right">
  <span class="ti-item"><i class="fa fa-truck"></i> Free shipping over RS50</span>
    </div>
  </div>
</div>
<header class="site-header enhanced">
  <div class="container header-flex header-main">
    <h1 class="logo"><a href="/herbalgarden-ecommerce/index.php">VENDOL</a></h1>
    <form class="search-bar" action="/herbalgarden-ecommerce/products.php" method="get">
      <input type="text" name="q" value="<?php echo isset($_GET['q']) ? htmlspecialchars($_GET['q']) : ''; ?>" placeholder="Search herbal products..." aria-label="Search products" />
      <button type="submit">🔍</button>
    </form>
    <nav>
      <ul class="nav">
        <li><a href="/herbalgarden-ecommerce/index.php">Home</a></li>
        <li><a href="/herbalgarden-ecommerce/products.php">Products</a></li>
        <li><a href="/herbalgarden-ecommerce/products.php#categories">Categories</a></li>
        <li><a href="/herbalgarden-ecommerce/cart.php">Cart<?php
            if (!empty($_SESSION['cart'])) { echo ' ('.array_sum(array_column($_SESSION['cart'],'quantity')).')'; }
        ?></a></li>
        <li><a href="/herbalgarden-ecommerce/admin/">Admin</a></li>
      </ul>
    </nav>
  </div>
</header>
<main class="container main-content">
