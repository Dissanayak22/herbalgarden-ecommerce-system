<?php
// Landing for /admin/ : redirect to dashboard if logged in else login page.
require_once __DIR__ . '/../includes/config.php';
if (is_admin_logged_in()) {
    redirect('dashboard.php');
}
redirect('login.php');
