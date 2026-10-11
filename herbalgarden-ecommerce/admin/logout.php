<?php require_once __DIR__ . '/../includes/config.php'; ?>
<?php
session_unset();
session_destroy();
session_start();
redirect('login.php');
