<?php require_once __DIR__ . '/../includes/config.php'; ?>
<?php
if (is_admin_logged_in()) { redirect('dashboard.php'); }
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = sanitize($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    if ($username && $password && $pdo) {
        $stmt = $pdo->prepare("SELECT id, password FROM admin_users WHERE username = ? LIMIT 1");
        $stmt->execute([$username]);
        if ($row = $stmt->fetch()) {
            if (password_verify($password, $row['password'])) {
                $_SESSION['admin_id'] = $row['id'];
                $_SESSION['admin_username'] = $username;
                redirect('dashboard.php');
            }
        }
        $error = 'Invalid credentials';
    } else {
        $error = 'Missing credentials or database not available';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"><title>Admin Login - HerbalGarden</title>
<link rel="stylesheet" href="/herbalgarden-ecommerce/assets/css/style.css" />
</head>
<body class="admin-auth">
  <div class="login-wrapper">
    <h2>Admin Login</h2>
    <?php if ($error): ?><div class="errors"><?php echo htmlspecialchars($error); ?></div><?php endif; ?>
    <form method="post">
      <label>Username <input type="text" name="username" required></label>
      <label>Password <input type="password" name="password" required></label>
      <button type="submit" class="btn">Login</button>
    </form>
    <p><a href="/herbalgarden-ecommerce/index.php">&larr; Back to site</a></p>
  </div>
</body>
</html>