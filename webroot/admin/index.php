<?php
$pageTitle = 'Admin Panel';
require_once __DIR__ . '/../includes/auth.php';
require_admin();
require_once __DIR__ . '/../includes/header.php';

$recentUploads = [
    ['sarah_avatar.png', 'jsmith', '2026-08-07 12:40', '184 KB'],
    ['brand_guide_v3.pdf', 'g.liu', '2026-08-06 15:22', '4.1 MB'],
    ['quarterly_sales.xlsx', 'n.patel', '2026-08-05 09:11', '612 KB'],
];
?>

<div class="admin-tiles">
  <a href="/admin/users.php" class="admin-tile">
    <div class="admin-tile-icon">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
    </div>
    <div class="admin-tile-title">User Management</div>
    <div class="admin-tile-desc">Create, edit and disable staff accounts.</div>
  </a>
  <a href="/admin/upload.php" class="admin-tile">
    <div class="admin-tile-icon">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="17 8 12 3 7 8"></polyline><line x1="12" y1="3" x2="12" y2="15"></line></svg>
    </div>
    <div class="admin-tile-title">Media Uploads</div>
    <div class="admin-tile-desc">Upload avatars and profile media.</div>
  </a>
  <a href="/admin/settings.php" class="admin-tile">
    <div class="admin-tile-icon">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="4" y1="21" x2="4" y2="14"></line><line x1="4" y1="10" x2="4" y2="3"></line><line x1="12" y1="21" x2="12" y2="12"></line><line x1="12" y1="8" x2="12" y2="3"></line><line x1="20" y1="21" x2="20" y2="16"></line><line x1="20" y1="12" x2="20" y2="3"></line><line x1="1" y1="14" x2="7" y2="14"></line><line x1="9" y1="8" x2="15" y2="8"></line><line x1="17" y1="16" x2="23" y2="16"></line></svg>
    </div>
    <div class="admin-tile-title">System Settings</div>
    <div class="admin-tile-desc">Application and mail configuration.</div>
  </a>
  <a href="/admin/logs.php" class="admin-tile">
    <div class="admin-tile-icon">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line></svg>
    </div>
    <div class="admin-tile-title">Access Logs</div>
    <div class="admin-tile-desc">Web server and authentication logs.</div>
  </a>
</div>

<div class="grid-2">
  <div class="card">
    <div class="card-head">
      <div>
        <div class="card-title">System Health</div>
        <div class="card-subtitle">Primary application server</div>
      </div>
      <span class="badge badge-green">healthy</span>
    </div>
    <div class="card-body">
      <div class="health-row">
        <div class="health-label"><span>CPU</span><b>42%</b></div>
        <div class="progress"><div class="progress-bar" style="width: 42%;"></div></div>
      </div>
      <div class="health-row">
        <div class="health-label"><span>Memory</span><b>61%</b></div>
        <div class="progress"><div class="progress-bar warn" style="width: 61%;"></div></div>
      </div>
      <div class="health-row">
        <div class="health-label"><span>Disk</span><b>38%</b></div>
        <div class="progress"><div class="progress-bar" style="width: 38%;"></div></div>
      </div>
      <div class="health-row">
        <div class="health-label"><span>Load average</span><b>1.24 / 0.98 / 0.87</b></div>
        <div class="progress"><div class="progress-bar" style="width: 18%;"></div></div>
      </div>
      <div class="admin-status-row" style="margin-top: 14px;">
        <span class="status-dot online"></span>
        <span style="font-size: 13px;">All services operational · last check 14:00 UTC</span>
      </div>
    </div>
  </div>

  <div class="card">
    <div class="card-head">
      <div>
        <div class="card-title">Recent Uploads</div>
        <div class="card-subtitle">Media server activity</div>
      </div>
      <a href="/admin/upload.php" class="btn btn-sm">Upload</a>
    </div>
    <div class="table-wrap" style="max-height: none; border: none; border-radius: 0;">
      <table class="table">
        <thead>
          <tr>
            <th>File</th>
            <th>Uploaded By</th>
            <th>Date</th>
            <th>Size</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($recentUploads as $u): ?>
          <tr>
            <td class="mono"><?php echo htmlspecialchars($u[0]); ?></td>
            <td><?php echo htmlspecialchars($u[1]); ?></td>
            <td><?php echo htmlspecialchars($u[2]); ?></td>
            <td><?php echo htmlspecialchars($u[3]); ?></td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
