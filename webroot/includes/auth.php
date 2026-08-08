<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function require_login() {
    if (!isset($_SESSION['user'])) {
        header('Location: /login.php');
        exit;
    }
}

function require_admin() {
    require_login();
    if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
        header('Location: /dashboard.php');
        exit;
    }
}

function user_initials($name) {
    $name = trim($name);
    if ($name === '') {
        return '?';
    }
    $parts = preg_split('/[\s._-]+/', $name);
    if (count($parts) > 1 && $parts[1] !== '') {
        return strtoupper(substr($parts[0], 0, 1) . substr($parts[1], 0, 1));
    }
    $initials = strtoupper(substr($name, 0, 2));
    return $initials;
}
