<?php
session_start();

if (isset($_SESSION['user'])) {
    header('Location: /dashboard.php');
    exit;
}

require_once __DIR__ . '/config.php';

$error = '';
$username = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = isset($_POST['username']) ? trim($_POST['username']) : '';

    try {
        $user = $collection->findOne([
            'username' => $_POST['username'],
            'password' => $_POST['password']
        ]);

        if ($user !== null) {
            session_regenerate_id(true);
            $_SESSION['user'] = $user['username'];
            $_SESSION['role'] = isset($user['role']) ? $user['role'] : 'user';
            $_SESSION['display_name'] = isset($user['display_name']) ? $user['display_name'] : $user['username'];
            $collection->updateOne(
                ['_id' => $user['_id']],
                ['$set' => ['last_login' => date('Y-m-d H:i:s')]]
            );
            header('Location: /dashboard.php');
            exit;
        }

        $error = 'Invalid credentials.';
    } catch (Exception $e) {
        $error = 'Authentication service unavailable. Please try again later.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Sign in · Phantom Analytics</title>
<link rel="stylesheet" href="/assets/style.css">
</head>
<body>
<div class="login-body">
  <div class="login-card">
    <div class="login-logo">
      <img src="/assets/logo.svg" alt="Phantom Analytics">
    </div>
    <div class="login-tagline">Internal staff access · corporate network only</div>

    <?php if ($error !== ''): ?>
    <div class="login-error"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <form method="post" action="/login.php">
      <div class="form-group">
        <label for="username">Username</label>
        <input class="form-control" type="text" id="username" name="username" value="<?php echo htmlspecialchars($username); ?>" autocomplete="username" placeholder="e.g. jsmith" autofocus>
      </div>
      <div class="form-group">
        <label for="password">Password</label>
        <input class="form-control" type="password" id="password" name="password" autocomplete="current-password" placeholder="Your password">
      </div>
      <div class="form-group" style="margin-bottom: 20px;">
        <label class="login-checkbox">
          <input type="checkbox" name="remember" value="1">
          Remember me on this device
        </label>
      </div>
      <button class="btn btn-primary btn-block" type="submit">Sign in</button>
      <div class="login-links">
        <a href="/reset.php">Forgot password?</a>
        <a href="/alerts.php">System status</a>
      </div>
    </form>

    <div class="login-footer">
      Phantom Analytics v2.4.1 · Authorized personnel only.<br>
      All access is logged and monitored.
    </div>
  </div>
</div>
</body>
</html>
