<?php
$pageTitle = 'Access Logs';
require_once __DIR__ . '/../includes/auth.php';
require_admin();
require_once __DIR__ . '/../includes/header.php';

$lines = [
    '[07/Aug/2026:14:02:11 +0000] 10.0.0.17 - - "GET /dashboard.php HTTP/1.1" 200 18421',
    '[07/Aug/2026:14:01:58 +0000] 10.0.0.23 - - "POST /login.php HTTP/1.1" 302 0',
    '[07/Aug/2026:14:01:40 +0000] 10.0.0.17 - - "GET /reports.php HTTP/1.1" 200 24601',
    '[07/Aug/2026:14:00:55 +0000] 172.16.3.8 - - "GET /team.php HTTP/1.1" 200 31280',
    '[07/Aug/2026:13:58:12 +0000] 10.0.0.23 - - "GET /alerts.php HTTP/1.1" 200 12874',
    '2026-08-07 13:42:06 [app] memory usage on node-3 reached 92% (threshold 85%)',
    '[07/Aug/2026:13:41:05 +0000] 10.0.0.17 - - "POST /login.php HTTP/1.1" 302 0',
    '[07/Aug/2026:13:40:52 +0000] 10.0.0.17 - - "GET /login.php HTTP/1.1" 200 4872',
    '2026-08-07 13:40:31 [app] user jsmith logged in from 10.0.0.17',
    '[07/Aug/2026:13:38:44 +0000] 172.16.4.11 - - "GET /assets/style.css HTTP/1.1" 304 0',
    '[07/Aug/2026:13:30:20 +0000] 10.0.0.44 - - "GET /admin/index.php HTTP/1.1" 302 0',
    '[07/Aug/2026:13:30:18 +0000] 10.0.0.44 - - "GET /dashboard.php HTTP/1.1" 200 18421',
    '2026-08-07 13:22:47 [cron] backup ph-db-01 completed (1.2 GB, 4m 12s)',
    '[07/Aug/2026:13:15:33 +0000] 10.0.0.7 - - "GET /profile.php HTTP/1.1" 200 15602',
    '[07/Aug/2026:13:02:09 +0000] 10.0.0.44 - - "GET /reports.php HTTP/1.1" 200 24601',
    '[07/Aug/2026:12:58:47 +0000] 172.16.3.2 - - "GET /uploads/avatars/sarah_avatar.png HTTP/1.1" 200 188416',
    '[07/Aug/2026:12:40:11 +0000] 10.0.0.17 - - "POST /admin/upload.php HTTP/1.1" 302 0',
    '2026-08-07 12:40:11 [app] file sarah_avatar.png uploaded by jsmith (184 KB)',
    '[07/Aug/2026:12:05:29 +0000] 10.0.0.44 - - "GET /admin/settings.php HTTP/1.1" 302 0',
    '[07/Aug/2026:11:56:03 +0000] 172.16.4.11 - - "GET /dashboard.php HTTP/1.1" 200 18421',
    '2026-08-07 11:18:52 [auth] 3 failed login attempts in 5 minutes from 10.0.0.44',
    '[07/Aug/2026:11:18:44 +0000] 10.0.0.44 - - "POST /login.php HTTP/1.1" 401 512',
    '[07/Aug/2026:11:18:37 +0000] 10.0.0.44 - - "POST /login.php HTTP/1.1" 401 512',
    '[07/Aug/2026:11:18:29 +0000] 10.0.0.44 - - "POST /login.php HTTP/1.1" 401 512',
    '[07/Aug/2026:11:15:02 +0000] 10.0.0.23 - - "GET /logout.php HTTP/1.1" 302 0',
    '2026-08-07 11:14:55 [app] user g.liu logged out',
    '[07/Aug/2026:09:17:55 +0000] 10.0.0.44 - - "POST /login.php HTTP/1.1" 401 512',
    '2026-08-07 09:17:55 [auth] failed login for \'admin\' from 10.0.0.44 (3 consecutive failures)',
    '[07/Aug/2026:09:17:52 +0000] 10.0.0.44 - - "POST /login.php HTTP/1.1" 401 512',
    '[07/Aug/2026:09:17:49 +0000] 10.0.0.44 - - "POST /login.php HTTP/1.1" 401 512',
    '[07/Aug/2026:09:17:40 +0000] 10.0.0.44 - - "GET /login.php HTTP/1.1" 200 4872',
    '2026-08-07 09:15:00 [cron] nightly aggregation job finished (41 reports)',
    '[06/Aug/2026:18:05:44 +0000] 10.0.0.7 - - "POST /admin/settings.php HTTP/1.1" 302 0',
    '2026-08-06 18:05:10 [app] deploy build 3842 promoted to production',
    '[06/Aug/2026:17:52:40 +0000] 10.0.0.23 - - "POST /login.php HTTP/1.1" 302 0',
    '[06/Aug/2026:17:52:21 +0000] 10.0.0.23 - - "GET /login.php HTTP/1.1" 200 4872',
    '2026-08-06 16:58:02 [app] user b.thomas disabled (offboarding ticket #4471)',
    '[06/Aug/2026:15:22:19 +0000] 10.0.0.17 - - "POST /admin/upload.php HTTP/1.1" 302 0',
    '2026-08-06 15:22:19 [app] file brand_guide_v3.pdf uploaded by g.liu (4.1 MB)',
    '[06/Aug/2026:14:22:03 +0000] 172.16.3.8 - - "GET /admin/logs.php HTTP/1.1" 200 18211',
    '2026-08-06 14:22:01 [app] disk usage above 85% on /dev/sda1 (file server)',
];
?>

<div class="card">
  <div class="toolbar">
    <div class="form-group" style="flex: 1; max-width: 420px;">
      <label for="logFilter">Filter logs</label>
      <input class="form-control" type="text" id="logFilter" placeholder="e.g. 10.0.0.44, upload, login..." onkeyup="filterLogs(this)">
    </div>
    <button class="btn" type="button" style="margin-left: auto;">Refresh</button>
    <button class="btn btn-primary" type="button">Download Logs</button>
  </div>

  <div class="card-body" style="padding-top: 0;">
    <div class="log-viewer" id="logViewer">
      <?php foreach ($lines as $line): ?>
      <?php
      $isError = strpos($line, "failed login for 'admin'") !== false;
      $isAuth = strpos($line, '[auth]') !== false || strpos($line, '[app]') !== false;
      ?>
      <div class="log-line">
        <?php if ($isError): ?>
        <span class="log-error"><?php echo htmlspecialchars($line); ?></span>
        <?php else: ?>
        <?php echo htmlspecialchars($line); ?>
        <?php endif; ?>
      </div>
      <?php endforeach; ?>
    </div>
    <div class="form-hint" style="margin-top: 10px;">
      Showing the last <?php echo count($lines); ?> events · logs are rotated daily at 00:00 UTC
    </div>
  </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
