<?php
$pageTitle = 'System Alerts';
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/header.php';

$alerts = [
    ['CRIT', '2026-08-07 13:42', 'High memory usage on node-3 (92%). Process phantom-worker is the top consumer. Restart recommended.'],
    ['WARN', '2026-08-07 11:18', 'Failed login attempt from 10.0.0.44 — 3 attempts in 5 minutes against the staff portal.'],
    ['INFO', '2026-08-07 06:00', 'Backup completed (ph-db-01). 1.2 GB in 4m 12s. Retention policy applied.'],
    ['WARN', '2026-08-06 22:31', 'SSL certificate renewing in 14 days (api.phantom.local). Verify DNS and automation job.'],
    ['INFO', '2026-08-06 18:05', 'Deploy finished: build 3842 promoted to production. 0 rollbacks.'],
    ['CRIT', '2026-08-06 14:22', 'Disk usage above 85% on /dev/sda1 (file server). Cleanup job scheduled.'],
    ['INFO', '2026-08-06 09:15', 'Scheduled maintenance window confirmed for Sunday 02:00–04:00 UTC.'],
    ['WARN', '2026-08-05 23:40', 'API response latency above threshold (p95: 640ms) for 25 minutes. Investigate connection pool.'],
    ['INFO', '2026-08-05 16:10', 'User jsmith enabled multi-factor authentication.'],
    ['CRIT', '2026-08-05 08:03', 'Payment gateway webhook failing (timeout after 3 retries). On-call paged.'],
];

$sevBadge = [
    'CRIT' => 'badge-red',
    'WARN' => 'badge-yellow',
    'INFO' => 'badge-blue',
];
?>

<div class="card">
  <div class="toolbar">
    <div class="form-group">
      <label for="sev">Severity</label>
      <select class="form-control" id="sev">
        <option>All severities</option>
        <option>CRIT</option>
        <option>WARN</option>
        <option>INFO</option>
      </select>
    </div>
    <div class="form-group">
      <label for="status">Status</label>
      <select class="form-control" id="status">
        <option>All statuses</option>
        <option>Open</option>
        <option>Resolved</option>
      </select>
    </div>
    <button class="btn" type="button" style="margin-left: auto;">Apply filters</button>
    <button class="btn btn-primary" type="button">Acknowledge all</button>
  </div>

  <div class="card-body">
    <?php foreach ($alerts as $a): ?>
    <div class="alert-item">
      <span class="alert-sev"><span class="badge <?php echo $sevBadge[$a[0]]; ?>"><?php echo $a[0]; ?></span></span>
      <div class="alert-message"><?php echo htmlspecialchars($a[2]); ?></div>
      <span class="alert-time"><?php echo htmlspecialchars($a[1]); ?></span>
      <button class="btn btn-sm btn-ghost" type="button">Mark Resolved</button>
    </div>
    <?php endforeach; ?>
  </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
