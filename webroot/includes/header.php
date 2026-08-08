<?php
require_once __DIR__ . '/auth.php';
require_login();

$script = basename($_SERVER['SCRIPT_NAME']);
$adminArea = strpos($_SERVER['SCRIPT_NAME'], '/admin/') !== false;

$navMap = [
    'dashboard.php' => 'dashboard',
    'reports.php' => 'reports',
    'team.php' => 'team',
    'alerts.php' => 'alerts',
    'profile.php' => 'profile',
];
$activeNav = isset($navMap[$script]) ? $navMap[$script] : ($adminArea ? 'admin' : '');

if (!isset($pageTitle)) {
    $pageTitle = 'Phantom Analytics';
}

$sessionUser = isset($_SESSION['user']) ? $_SESSION['user'] : 'user';
$sessionRole = isset($_SESSION['role']) ? $_SESSION['role'] : 'user';
$sessionName = isset($_SESSION['display_name']) ? $_SESSION['display_name'] : $sessionUser;
$initials = user_initials($sessionName);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?php echo htmlspecialchars($pageTitle); ?> · Phantom Analytics</title>
<link rel="stylesheet" href="/assets/style.css">
</head>
<body>
<div class="layout">

  <aside class="sidebar">
    <div class="sidebar-logo">
      <img src="/assets/logo.svg" alt="Phantom Analytics">
    </div>

    <nav class="sidebar-nav">
      <a href="/dashboard.php" class="nav-link<?php echo $activeNav === 'dashboard' ? ' active' : ''; ?>">
        <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7" rx="1"></rect><rect x="14" y="3" width="7" height="7" rx="1"></rect><rect x="3" y="14" width="7" height="7" rx="1"></rect><rect x="14" y="14" width="7" height="7" rx="1"></rect></svg>
        <span>Overview</span>
      </a>
      <a href="/reports.php" class="nav-link<?php echo $activeNav === 'reports' ? ' active' : ''; ?>">
        <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="20" x2="12" y2="10"></line><line x1="18" y1="20" x2="18" y2="4"></line><line x1="6" y1="20" x2="6" y2="16"></line></svg>
        <span>Reports</span>
      </a>
      <a href="/team.php" class="nav-link<?php echo $activeNav === 'team' ? ' active' : ''; ?>">
        <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
        <span>Team Directory</span>
      </a>
      <a href="/alerts.php" class="nav-link<?php echo $activeNav === 'alerts' ? ' active' : ''; ?>">
        <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path><path d="M13.73 21a2 2 0 0 1-3.46 0"></path></svg>
        <span>Alerts</span>
      </a>
      <a href="/profile.php" class="nav-link<?php echo $activeNav === 'profile' ? ' active' : ''; ?>">
        <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
        <span>My Profile</span>
      </a>
    </nav>

    <?php if ($sessionRole === 'admin'): ?>
    <div class="nav-section">Administration</div>
    <nav class="sidebar-nav">
      <a href="/admin/index.php" class="nav-link<?php echo $activeNav === 'admin' && $script === 'index.php' ? ' active' : ''; ?>">
        <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg>
        <span>Admin Panel</span>
      </a>
      <a href="/admin/users.php" class="nav-link<?php echo $activeNav === 'admin' && $script === 'users.php' ? ' active' : ''; ?>">
        <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
        <span>User Management</span>
      </a>
      <a href="/admin/upload.php" class="nav-link<?php echo $activeNav === 'admin' && $script === 'upload.php' ? ' active' : ''; ?>">
        <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="17 8 12 3 7 8"></polyline><line x1="12" y1="3" x2="12" y2="15"></line></svg>
        <span>Media Uploads</span>
      </a>
      <a href="/admin/settings.php" class="nav-link<?php echo $activeNav === 'admin' && $script === 'settings.php' ? ' active' : ''; ?>">
        <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="4" y1="21" x2="4" y2="14"></line><line x1="4" y1="10" x2="4" y2="3"></line><line x1="12" y1="21" x2="12" y2="12"></line><line x1="12" y1="8" x2="12" y2="3"></line><line x1="20" y1="21" x2="20" y2="16"></line><line x1="20" y1="12" x2="20" y2="3"></line><line x1="1" y1="14" x2="7" y2="14"></line><line x1="9" y1="8" x2="15" y2="8"></line><line x1="17" y1="16" x2="23" y2="16"></line></svg>
        <span>Settings</span>
      </a>
      <a href="/admin/logs.php" class="nav-link<?php echo $activeNav === 'admin' && $script === 'logs.php' ? ' active' : ''; ?>">
        <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line></svg>
        <span>Access Logs</span>
      </a>
    </nav>
    <?php endif; ?>

    <div class="sidebar-footer">
      <div class="user-chip">
        <span class="avatar avatar-sm"><?php echo htmlspecialchars($initials); ?></span>
        <div class="user-chip-meta">
          <div class="user-chip-name"><?php echo htmlspecialchars($sessionName); ?></div>
          <div class="user-chip-role"><?php echo htmlspecialchars(ucfirst($sessionRole)); ?></div>
        </div>
      </div>
      <a href="/logout.php" class="logout-link">
        <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg>
        <span>Sign out</span>
      </a>
    </div>
  </aside>

  <div class="main">
    <header class="topbar">
      <div class="topbar-left">
        <button class="icon-btn" id="sidebarToggle" title="Toggle sidebar" type="button">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="3" y1="6" x2="21" y2="6"></line><line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="18" x2="21" y2="18"></line></svg>
        </button>
        <h1 class="page-title"><?php echo htmlspecialchars($pageTitle); ?></h1>
      </div>
      <div class="topbar-right">
        <div class="notif-wrap">
          <button class="icon-btn" id="notifBtn" title="Notifications" type="button">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path><path d="M13.73 21a2 2 0 0 1-3.46 0"></path></svg>
            <span class="notif-badge">3</span>
          </button>
          <div class="notif-dropdown" id="notifDropdown">
            <div class="notif-head">Notifications</div>
            <a href="/alerts.php" class="notif-item">
              <span class="notif-dot notif-dot-warn"></span>
              <div class="notif-body">
                <div class="notif-title">High memory usage on node-3</div>
                <div class="notif-time">12 minutes ago</div>
              </div>
            </a>
            <a href="/alerts.php" class="notif-item">
              <span class="notif-dot notif-dot-ok"></span>
              <div class="notif-body">
                <div class="notif-title">Backup completed (ph-db-01)</div>
                <div class="notif-time">2 hours ago</div>
              </div>
            </a>
            <a href="/alerts.php" class="notif-item">
              <span class="notif-dot notif-dot-warn"></span>
              <div class="notif-body">
                <div class="notif-title">SSL certificate renewing in 14 days</div>
                <div class="notif-time">Yesterday</div>
              </div>
            </a>
            <a href="/alerts.php" class="notif-viewall">View all alerts</a>
          </div>
        </div>
        <a href="/profile.php" class="topbar-user">
          <span class="avatar avatar-sm"><?php echo htmlspecialchars($initials); ?></span>
          <span class="topbar-username"><?php echo htmlspecialchars($sessionName); ?></span>
        </a>
      </div>
    </header>

    <main class="content">
