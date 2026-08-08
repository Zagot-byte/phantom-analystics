<?php
$pageTitle = 'My Profile';
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/includes/header.php';

$username = $_SESSION['user'];
$me = $collection->findOne(['username' => $username]);
if ($me === null) {
    $me = [
        'username' => $username,
        'role' => $_SESSION['role'],
        'display_name' => $_SESSION['display_name'],
        'email' => $username . '@phantom.local',
        'created_at' => '2023-04-18',
        'last_login' => '2026-08-07 09:14:22',
    ];
}

$flash = '';
$flashType = 'success';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['display_name'])) {
    $name = trim($_POST['display_name']);
    if ($name !== '') {
        $collection->updateOne(
            ['username' => $username],
            ['$set' => ['display_name' => $name]]
        );
        $_SESSION['display_name'] = $name;
        $me = $collection->findOne(['username' => $username]);
        $flash = 'Display name updated successfully.';
    } else {
        $flash = 'Display name cannot be empty.';
        $flashType = 'error';
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['current_password'])) {
    $new = isset($_POST['new_password']) ? $_POST['new_password'] : '';
    $confirm = isset($_POST['confirm_password']) ? $_POST['confirm_password'] : '';
    if ($new !== '' && $new === $confirm) {
        $flash = 'Password changed. Use the new password on your next sign-in.';
    } else {
        $flash = 'New password and confirmation do not match.';
        $flashType = 'error';
    }
}

$displayName = isset($me['display_name']) ? $me['display_name'] : $username;
$email = isset($me['email']) ? $me['email'] : $username . '@phantom.local';
$role = isset($me['role']) ? $me['role'] : $_SESSION['role'];
$created = isset($me['created_at']) ? $me['created_at'] : '2023-04-18';
$lastLogin = isset($me['last_login']) ? $me['last_login'] : '—';
?>

<?php if ($flash !== ''): ?>
<div class="flash flash-<?php echo $flashType; ?>"><?php echo htmlspecialchars($flash); ?></div>
<?php endif; ?>

<div class="grid-3">
  <div class="card">
    <div class="card-head">
      <div>
        <div class="card-title">Account</div>
        <div class="card-subtitle">Your identity on the network</div>
      </div>
    </div>
    <div class="card-body" style="text-align: center; padding-bottom: 8px;">
      <span class="avatar avatar-lg" style="margin: 4px auto 14px;"><?php echo htmlspecialchars(user_initials($displayName)); ?></span>
      <div style="font-size: 16px; font-weight: 700;"><?php echo htmlspecialchars($displayName); ?></div>
      <div style="color: var(--muted); font-size: 13px;">@<?php echo htmlspecialchars($username); ?></div>
      <div style="margin-top: 8px;">
        <?php if ($role === 'admin'): ?>
        <span class="badge badge-blue">admin</span>
        <?php else: ?>
        <span class="badge badge-grey">member</span>
        <?php endif; ?>
      </div>
    </div>
    <div class="card-body">
      <div class="admin-status-row">
        <span style="color: var(--muted); width: 110px;">Username</span>
        <span><?php echo htmlspecialchars($username); ?></span>
      </div>
      <div class="admin-status-row">
        <span style="color: var(--muted); width: 110px;">Email</span>
        <span><?php echo htmlspecialchars($email); ?></span>
      </div>
      <div class="admin-status-row">
        <span style="color: var(--muted); width: 110px;">Member since</span>
        <span><?php echo htmlspecialchars($created); ?></span>
      </div>
      <div class="admin-status-row">
        <span style="color: var(--muted); width: 110px;">Last login</span>
        <span><?php echo htmlspecialchars($lastLogin); ?></span>
      </div>
    </div>
  </div>

  <div class="card">
    <div class="card-head">
      <div>
        <div class="card-title">Display Name</div>
        <div class="card-subtitle">Shown across the dashboard</div>
      </div>
    </div>
    <div class="card-body">
      <form method="post" action="/profile.php">
        <div class="form-group">
          <label for="display_name">Display name</label>
          <input class="form-control" type="text" id="display_name" name="display_name" value="<?php echo htmlspecialchars($displayName); ?>">
          <div class="form-hint">Used in activity feeds and the header.</div>
        </div>
        <button class="btn btn-primary" type="submit">Save changes</button>
      </form>
    </div>
    <div class="card-head" style="border-top: 1px solid var(--border-soft);">
      <div>
        <div class="card-title">Profile Picture</div>
        <div class="card-subtitle">Uploaded media is stored on the media server</div>
      </div>
    </div>
    <div class="card-body">
      <p style="font-size: 13px; color: var(--muted); margin-bottom: 12px;">
        You can upload a new avatar image using the media upload tool. PNG, JPG, GIF, SVG and WEBP are accepted.
      </p>
      <a class="btn" href="/admin/upload.php">Upload new picture</a>
    </div>
  </div>

  <div class="card">
    <div class="card-head">
      <div>
        <div class="card-title">Security</div>
        <div class="card-subtitle">Password management</div>
      </div>
    </div>
    <div class="card-body">
      <form method="post" action="/profile.php">
        <div class="form-group">
          <label for="current_password">Current password</label>
          <input class="form-control" type="password" id="current_password" name="current_password" autocomplete="current-password">
        </div>
        <div class="form-group">
          <label for="new_password">New password</label>
          <input class="form-control" type="password" id="new_password" name="new_password" autocomplete="new-password">
        </div>
        <div class="form-group">
          <label for="confirm_password">Confirm new password</label>
          <input class="form-control" type="password" id="confirm_password" name="confirm_password" autocomplete="new-password">
        </div>
        <button class="btn" type="submit">Change password</button>
      </form>
    </div>
  </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
