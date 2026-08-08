<?php
$pageTitle = 'Upload Profile Media';
require_once __DIR__ . '/../includes/auth.php';
require_admin();
require_once __DIR__ . '/../includes/header.php';

$uploadsDir = __DIR__ . '/../uploads/avatars';
if (!is_dir($uploadsDir)) {
    mkdir($uploadsDir, 0775, true);
}

$message = '';
$messageType = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
        $ext = strtolower(pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION));
        $blocked = ['php', 'php3', 'php4', 'phtml'];
        if (in_array($ext, $blocked)) {
            $message = "File type not allowed.";
            $messageType = 'error';
        } else {
            $dest = '/var/www/html/uploads/avatars/' . basename($_FILES['file']['name']);
            move_uploaded_file($_FILES['file']['tmp_name'], $dest);
            $message = "File uploaded successfully.";
            $messageType = 'success';
        }
    } else {
        $message = "No file received. Please choose a file first.";
        $messageType = 'error';
    }
}

$entries = scandir($uploadsDir);
$files = [];
foreach ($entries as $entry) {
    if ($entry === '.' || $entry === '..') {
        continue;
    }
    $path = $uploadsDir . '/' . $entry;
    $files[] = [
        'name' => $entry,
        'size' => is_file($path) ? filesize($path) : 0,
        'date' => is_file($path) ? date('Y-m-d H:i', filemtime($path)) : '—',
    ];
}
usort($files, function ($a, $b) {
    return strcmp($b['date'], $a['date']);
});
?>

<?php if ($message !== ''): ?>
<div class="flash flash-<?php echo $messageType; ?>"><?php echo htmlspecialchars($message); ?></div>
<?php endif; ?>

<div class="grid-2">
  <div class="card">
    <div class="card-head">
      <div>
        <div class="card-title">Upload Media</div>
        <div class="card-subtitle">PNG, JPG, GIF, SVG, WEBP · max 5 MB</div>
      </div>
    </div>
    <div class="card-body">
      <form method="post" action="/admin/upload.php" enctype="multipart/form-data">
        <div class="dropzone">
          <div class="dropzone-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="17 8 12 3 7 8"></polyline><line x1="12" y1="3" x2="12" y2="15"></line></svg>
          </div>
          <div class="dropzone-title">Drag &amp; drop a file here</div>
          <div class="dropzone-sub">or use the file picker below</div>
          <input type="file" name="file" id="file">
        </div>
        <button class="btn btn-primary btn-block" type="submit" style="margin-top: 16px;">Upload file</button>
      </form>
    </div>
  </div>

  <div class="card">
    <div class="card-head">
      <div>
        <div class="card-title">Uploaded Files</div>
        <div class="card-subtitle"><?php echo count($files); ?> files on the media server</div>
      </div>
    </div>
    <div class="table-wrap" style="max-height: 420px; border: none; border-radius: 0;">
      <table class="table">
        <thead>
          <tr>
            <th>Filename</th>
            <th>Size</th>
            <th>Uploaded</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($files as $f): ?>
          <tr>
            <td class="mono"><?php echo htmlspecialchars($f['name']); ?></td>
            <td><?php echo $f['size'] > 0 ? number_format($f['size']) . ' B' : '—'; ?></td>
            <td><?php echo htmlspecialchars($f['date']); ?></td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
