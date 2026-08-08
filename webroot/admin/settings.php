<?php
$pageTitle = 'System Settings';
require_once __DIR__ . '/../includes/auth.php';
require_admin();
require_once __DIR__ . '/../includes/header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_SESSION['flash'] = 'Settings saved successfully. Changes take effect within 60 seconds.';
    header('Location: /admin/settings.php');
    exit;
}

$flash = isset($_SESSION['flash']) ? $_SESSION['flash'] : '';
unset($_SESSION['flash']);
?>

<?php if ($flash !== ''): ?>
<div class="flash flash-success"><?php echo htmlspecialchars($flash); ?></div>
<?php endif; ?>

<form method="post" action="/admin/settings.php">
  <div class="card">
    <div class="card-head">
      <div>
        <div class="card-title">Application</div>
        <div class="card-subtitle">General platform settings</div>
      </div>
    </div>
    <div class="card-body">
      <div class="form-row">
        <div class="form-group">
          <label for="app_name">App Name</label>
          <input class="form-control" type="text" id="app_name" name="app_name" value="Phantom Analytics">
        </div>
        <div class="form-group">
          <label for="support_email">Support Email</label>
          <input class="form-control" type="email" id="support_email" name="support_email" value="support@phantom.local">
        </div>
      </div>
      <div class="form-row">
        <div class="form-group">
          <label for="max_upload">Max Upload Size (MB)</label>
          <input class="form-control" type="number" id="max_upload" name="max_upload" value="5">
        </div>
        <div class="form-group">
          <label for="session_timeout">Session Timeout (minutes)</label>
          <input class="form-control" type="number" id="session_timeout" name="session_timeout" value="30">
        </div>
      </div>
      <div class="form-row">
        <div class="form-group">
          <label for="smtp_host">SMTP Server</label>
          <input class="form-control" type="text" id="smtp_host" name="smtp_host" value="smtp.phantom.local">
        </div>
        <div class="form-group">
          <label for="smtp_port">SMTP Port</label>
          <input class="form-control" type="number" id="smtp_port" name="smtp_port" value="587">
        </div>
      </div>
    </div>
  </div>

  <div class="card">
    <div class="card-head">
      <div>
        <div class="card-title">Security &amp; Maintenance</div>
        <div class="card-subtitle">Access and platform behavior</div>
      </div>
    </div>
    <div class="card-body">
      <div class="switch-row">
        <div>
          <div class="switch-row-label">Enable two-factor authentication</div>
          <div class="switch-row-hint">Require TOTP codes for all staff accounts.</div>
        </div>
        <label class="switch">
          <input type="checkbox" name="enable_2fa" checked>
          <span class="track"></span>
        </label>
      </div>
      <div class="switch-row">
        <div>
          <div class="switch-row-label">Maintenance mode</div>
          <div class="switch-row-hint">Show a maintenance page to non-admin users.</div>
        </div>
        <label class="switch">
          <input type="checkbox" name="maintenance_mode">
          <span class="track"></span>
        </label>
      </div>
      <div class="switch-row">
        <div>
          <div class="switch-row-label">Password expiry</div>
          <div class="switch-row-hint">Force password rotation every 90 days.</div>
        </div>
        <label class="switch">
          <input type="checkbox" name="password_expiry" checked>
          <span class="track"></span>
        </label>
      </div>
    </div>
  </div>

  <div style="display: flex; gap: 10px;">
    <button class="btn btn-primary" type="submit">Save settings</button>
    <button class="btn" type="button">Discard changes</button>
  </div>
</form>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
