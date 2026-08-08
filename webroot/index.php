<?php
require_once __DIR__ . '/includes/auth.php';

if (!isset($_SESSION['user'])) {
    header('Location: /login.php');
    exit;
}

header('Location: /dashboard.php');
exit;
