<?php
$pageTitle = 'Team Directory';
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/header.php';

$staff = [
    ['Sarah Chen', 'Senior Data Analyst', 'Data Science', 's.chen@phantom.local', true],
    ['Marcus Webb', 'DevOps Engineer', 'Platform', 'm.webb@phantom.local', true],
    ['Priya Sharma', 'Product Manager', 'Product', 'p.sharma@phantom.local', false],
    ['James Carter', 'Backend Engineer', 'Engineering', 'j.carter@phantom.local', true],
    ['Elena Rodriguez', 'UX Designer', 'Design', 'e.rodriguez@phantom.local', false],
    ['David Kim', 'QA Lead', 'Engineering', 'd.kim@phantom.local', true],
    ['Amara Okafor', 'Data Scientist', 'Data Science', 'a.okafor@phantom.local', true],
    ['Tom Becker', 'IT Support Specialist', 'Operations', 't.becker@phantom.local', true],
    ['Nina Patel', 'Financial Analyst', 'Finance', 'n.patel@phantom.local', false],
    ['Leo Fontaine', 'Security Engineer', 'Security', 'l.fontaine@phantom.local', true],
    ['Grace Liu', 'Marketing Manager', 'Marketing', 'g.liu@phantom.local', false],
    ['Admin', 'System Administrator', 'IT', 'admin@phantom.local', true],
];
?>

<div class="card">
  <div class="toolbar">
    <div class="search-wrap">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
      <input class="search-input" type="text" placeholder="Search by name, title or department...">
    </div>
    <select class="form-control" style="width: 200px;">
      <option>All departments</option>
      <option>Data Science</option>
      <option>Engineering</option>
      <option>Operations</option>
      <option>Finance</option>
      <option>Security</option>
      <option>Marketing</option>
      <option>Product</option>
      <option>Design</option>
      <option>IT</option>
    </select>
    <div style="margin-left: auto;">
      <span class="badge badge-green">8 online</span>
      <span class="badge badge-grey">4 away</span>
    </div>
  </div>

  <div class="card-body">
    <div class="team-grid">
      <?php foreach ($staff as $m): ?>
      <div class="team-card">
        <span class="avatar avatar-md"><?php echo htmlspecialchars(user_initials($m[0])); ?></span>
        <div class="team-meta">
          <div class="team-name"><?php echo htmlspecialchars($m[0]); ?></div>
          <div class="team-title"><?php echo htmlspecialchars($m[1]); ?></div>
          <span class="team-dept"><?php echo htmlspecialchars($m[2]); ?></span>
          <div class="team-email"><?php echo htmlspecialchars($m[3]); ?></div>
        </div>
        <div class="team-status">
          <span class="status-dot <?php echo $m[4] ? 'online' : 'offline'; ?>"></span>
          <?php echo $m[4] ? 'Online' : 'Away'; ?>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
