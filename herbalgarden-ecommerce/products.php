<?php require_once __DIR__ . '/includes/config.php'; ?>
<?php include __DIR__ . '/includes/header.php'; ?>
<?php $searchQ = isset($_GET['q']) ? trim($_GET['q']) : ''; ?>
<h2>All Products<?php if($searchQ){ echo ' – Searching for “' . htmlspecialchars($searchQ) . '”'; } ?></h2>
<form method="get" class="filter-form">
  <label>Category:
    <select name="category" onchange="this.form.submit()">
      <option value="">All</option>
      <?php
      if ($pdo) {
          $cats = $pdo->query("SELECT DISTINCT category FROM products WHERE category IS NOT NULL AND category <> '' ORDER BY category ASC");
          $current = isset($_GET['category']) ? $_GET['category'] : '';
          while ($row = $cats->fetch()) {
              $cat = $row['category'];
              $sel = $current === $cat ? 'selected' : '';
              echo '<option '.$sel.' value="'.htmlspecialchars($cat).'">'.htmlspecialchars($cat).'</option>';
          }
      }
      ?>
    </select>
  </label>
  <noscript><button type="submit">Filter</button></noscript>
</form>
<div class="product-grid">
<?php
if ($pdo) {
    $query = "SELECT * FROM products";
    $where = [];
    $params = [];
    if (!empty($_GET['category'])) {
        $where[] = "category = ?";
        $params[] = $_GET['category'];
    }
    if ($searchQ !== '') {
        $where[] = "(name LIKE ? OR description LIKE ?)";
        $like = '%' . $searchQ . '%';
        $params[] = $like; $params[] = $like;
    }
    if ($where) { $query .= ' WHERE ' . implode(' AND ', $where); }
    $query .= " ORDER BY created_at DESC";
    $stmt = $pdo->prepare($query);
    $stmt->execute($params);
    $foundAny = false;
    while ($p = $stmt->fetch()) {
        $foundAny = true;
        $id = (int)$p['id'];
        echo '<div class="product-card">';
        if (!empty($p['image'])) {
            echo '<img src="assets/uploads/' . htmlspecialchars($p['image']) . '" alt="'.htmlspecialchars($p['name']).'" />';
        } else { echo '<div class="placeholder">No Image</div>'; }
        echo '<h4>' . htmlspecialchars($p['name']) . '</h4>';
        echo '<p class="price">RS' . format_price((float)$p['price']) . '</p>';
        echo '<a class="btn-sm" href="product_detail.php?id='.$id.'">View</a>';
        echo '</div>';
    }
    if (!$foundAny) {
        echo '<p class="notice">No products matched your filters.</p>';
    }
} else {
    echo '<p class="notice">Database not connected. Run create_database.php first.</p>';
}
?>
</div>
<?php include __DIR__ . '/includes/footer.php'; ?>