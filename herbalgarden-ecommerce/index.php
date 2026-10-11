<?php require_once __DIR__ . '/includes/config.php'; ?>
<?php include __DIR__ . '/includes/header.php'; ?>

<!-- Modern Hero Section -->
<section class="hero-section home-hero">
  <div class="hero-inner">
    <div class="hero-copy">
      <h1>Discover Nature's Healing Power</h1>
      <p>Premium herbal products carefully sourced to support your daily wellness rituals and long-term vitality.</p>
      <div class="hero-cta">
        <a class="btn" href="products.php">Shop Now</a>
        <a class="btn secondary" href="#about">Learn More</a>
      </div>
      <ul class="hero-benefits">
        <li>✔ Organic & Sustainably Sourced</li>
        <li>✔ Lab Tested Purity</li>
        <li>✔ Ethical Supply Chain</li>
      </ul>
    </div>
    <div class="hero-art">
      <div class="hero-art-blob">
        <div class="leaf-ring"></div>
        <div class="seedling">🌿</div>
      </div>
    </div>
  </div>
  <div class="hero-wave" aria-hidden="true"></div>
</section>

<!-- Categories Snapshot -->
<section class="categories-section" id="categories">
  <div class="section-head">
    <h2 class="section-title">Shop by Category</h2>
    <p class="section-sub">Browse core herbal product families</p>
  </div>
  <div class="category-grid">
    <?php
    $catRows = [];
    if ($pdo) {
        $catStmt = $pdo->query("SELECT category, COUNT(*) c FROM products WHERE category IS NOT NULL AND category<>'' GROUP BY category ORDER BY c DESC, category ASC LIMIT 8");
        $catRows = $catStmt->fetchAll();
    }
    if ($catRows) {
        foreach ($catRows as $cr) {
            $cat = $cr['category'];
            $count = (int)$cr['c'];
            echo '<a class="category-card" href="products.php?category='.urlencode($cat).'">';
            echo '<div class="cc-icon">🍃</div>';
            echo '<div class="cc-name">'.htmlspecialchars($cat).'</div>';
            echo '<div class="cc-count">'.$count.' item'.($count!==1?'s':'').'</div>';
            echo '</a>';
        }
    } else {
        echo '<p class="notice" style="grid-column:1/-1;">No categories yet. Add products in admin.</p>';
    }
    ?>
  </div>
</section>

<!-- Featured Products -->
<section class="featured-section" id="featured">
  <div class="section-head">
    <h2 class="section-title">Featured Products</h2>
    <p class="section-sub">Latest additions & highlighted remedies</p>
  </div>
  <div class="product-grid">
  <?php
  if ($pdo) {
      $stmt = $pdo->query("SELECT * FROM products ORDER BY created_at DESC LIMIT 8");
      $index = 0;
      $now = time();
      while ($p = $stmt->fetch()) {
          $id = (int)$p['id'];
          $createdTs = strtotime($p['created_at']);
          $isNew = ($now - $createdTs) < 86400*14; // 14 days
          echo '<div class="product-card">';
          if ($isNew) { echo '<span class="badge new">NEW</span>'; }
          if (!empty($p['image'])) {
              echo '<img src="assets/uploads/' . htmlspecialchars($p['image']) . '" alt="'.htmlspecialchars($p['name']).'" />';
          } else {
              echo '<div class="placeholder">No Image</div>';
          }
          echo '<h4>' . htmlspecialchars($p['name']) . '</h4>';
          echo '<p class="price">RS. ' . format_price((float)$p['price']) . '</p>';
          echo '<a class="btn-sm" href="product_detail.php?id='.$id.'">View</a>';
          echo '</div>';
          $index++;
      }
  } else {
      echo '<p class="notice">Database not connected. Run create_database.php first.</p>';
  }
  ?>
  </div>
</section>

<!-- About / Story -->
<section class="about-section" id="about">
  <div class="about-inner">
    <div class="about-copy">
      <h2>Our Story</h2>
      <p>HerbalGarden was founded on the belief that daily wellness should be simple, ethical, and grounded in the potency of plants. We partner with sustainable growers and employ gentle extraction and drying methods to preserve full-spectrum benefits.</p>
      <div class="about-highlights">
        <div class="ah-item">🌱 100% Organic Sources</div>
        <div class="ah-item">🧪 Batch Purity Tested</div>
        <div class="ah-item">🤝 Fair & Transparent Sourcing</div>
        <div class="ah-item">♻ Eco-Friendly Packaging</div>
      </div>
      <a href="products.php" class="btn">Explore Catalog</a>
    </div>
    <div class="about-art">
      <div class="about-illustration">
        <div class="a-ring"></div>
        <div class="a-core">🌼</div>
      </div>
    </div>
  </div>
</section>
<?php include __DIR__ . '/includes/footer.php'; ?>