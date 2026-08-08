<?php
$pageTitle = 'Overview';
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/header.php';

$isAdmin = isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
?>

<div class="stat-grid">
  <div class="stat-card">
    <div class="stat-label">Active Users</div>
    <div class="stat-value">1,284</div>
    <div class="stat-delta up">&#9650; 3.2% vs last week</div>
  </div>
  <div class="stat-card">
    <div class="stat-label">Reports Generated</div>
    <div class="stat-value">3,947</div>
    <div class="stat-delta up">&#9650; 1.8% vs last week</div>
  </div>
  <div class="stat-card">
    <div class="stat-label">Open Alerts</div>
    <div class="stat-value">12</div>
    <div class="stat-delta down">&#9660; 3 critical</div>
  </div>
  <div class="stat-card">
    <div class="stat-label">Uptime</div>
    <div class="stat-value">99.98%</div>
    <div class="stat-delta up">&#9650; 214 days since last restart</div>
  </div>
</div>

<div class="grid-2">
  <div class="card">
    <div class="card-head">
      <div>
        <div class="card-title">System Activity</div>
        <div class="card-subtitle">Requests per day · last 7 days</div>
      </div>
      <span class="badge badge-blue">Live</span>
    </div>
    <div class="card-body">
      <div class="chart">
        <svg viewBox="0 0 640 220" preserveAspectRatio="none" role="img" aria-label="7 day activity trend">
          <g class="chart-grid">
            <line x1="0" y1="20" x2="640" y2="20"></line>
            <line x1="0" y1="70" x2="640" y2="70"></line>
            <line x1="0" y1="120" x2="640" y2="120"></line>
            <line x1="0" y1="170" x2="640" y2="170"></line>
          </g>
          <path class="chart-area" d="M0,112 L106,96 L213,124 L320,88 L426,104 L533,58 L640,74 L640,200 L0,200 Z"></path>
          <polyline class="chart-line" points="0,112 106,96 213,124 320,88 426,104 533,58 640,74"></polyline>
          <circle class="chart-dot" cx="0" cy="112" r="3.5"></circle>
          <circle class="chart-dot" cx="106" cy="96" r="3.5"></circle>
          <circle class="chart-dot" cx="213" cy="124" r="3.5"></circle>
          <circle class="chart-dot" cx="320" cy="88" r="3.5"></circle>
          <circle class="chart-dot" cx="426" cy="104" r="3.5"></circle>
          <circle class="chart-dot" cx="533" cy="58" r="3.5"></circle>
          <circle class="chart-dot" cx="640" cy="74" r="3.5"></circle>
          <g class="chart-label">
            <text x="0" y="196" text-anchor="middle">Mon</text>
            <text x="106" y="196" text-anchor="middle">Tue</text>
            <text x="213" y="196" text-anchor="middle">Wed</text>
            <text x="320" y="196" text-anchor="middle">Thu</text>
            <text x="426" y="196" text-anchor="middle">Fri</text>
            <text x="533" y="196" text-anchor="middle">Sat</text>
            <text x="640" y="196" text-anchor="middle">Sun</text>
          </g>
        </svg>
      </div>
      <div class="chart-legend">
        <span><i style="background: var(--accent);"></i> Requests</span>
        <span><i style="background: #30363d;"></i> Baseline 10,000/day</span>
      </div>
    </div>
  </div>

  <div class="card">
    <div class="card-head">
      <div>
        <div class="card-title">Recent Activity</div>
        <div class="card-subtitle">Latest events across services</div>
      </div>
      <a href="/alerts.php" class="btn btn-sm btn-ghost">View alerts</a>
    </div>
    <ul class="activity-list">
      <li class="activity-item">
        <span class="avatar avatar-sm">JS</span>
        <div class="activity-body">
          <div class="activity-text"><span class="activity-user">jsmith</span> generated report RPT-2026-3814 (Q3 Sales Summary)</div>
          <div class="activity-time">Today, 14:02</div>
        </div>
      </li>
      <li class="activity-item">
        <span class="avatar avatar-sm">BT</span>
        <div class="activity-body">
          <div class="activity-text"><span class="activity-user">bthomas</span> updated alert routing for node-3</div>
          <div class="activity-time">Today, 13:47</div>
        </div>
      </li>
      <li class="activity-item">
        <span class="avatar avatar-sm">AD</span>
        <div class="activity-body">
          <div class="activity-text"><span class="activity-user">admin</span> deployed build 3842 to production</div>
          <div class="activity-time">Today, 11:20</div>
        </div>
      </li>
      <li class="activity-item">
        <span class="avatar avatar-sm">JS</span>
        <div class="activity-body">
          <div class="activity-text"><span class="activity-user">jsmith</span> exported report RPT-2026-3809 to CSV</div>
          <div class="activity-time">Yesterday, 16:35</div>
        </div>
      </li>
      <li class="activity-item">
        <span class="avatar avatar-sm">SC</span>
        <div class="activity-body">
          <div class="activity-text"><span class="activity-user">s.chen</span> ran weekly anomaly detection sweep</div>
          <div class="activity-time">Yesterday, 14:12</div>
        </div>
      </li>
      <li class="activity-item">
        <span class="avatar avatar-sm">MW</span>
        <div class="activity-body">
          <div class="activity-text"><span class="activity-user">m.webb</span> restarted service phantom-api (node-2)</div>
          <div class="activity-time">Yesterday, 09:58</div>
        </div>
      </li>
      <li class="activity-item">
        <span class="avatar avatar-sm">AD</span>
        <div class="activity-body">
          <div class="activity-text"><span class="activity-user">admin</span> rotated API credentials for ph-api-service</div>
          <div class="activity-time">2 days ago, 17:03</div>
        </div>
      </li>
    </ul>
  </div>
</div>

<div class="grid-2">
  <div class="card">
    <div class="card-head">
      <div>
        <div class="card-title">Quick Actions</div>
        <div class="card-subtitle">Common tasks</div>
      </div>
    </div>
    <div class="card-body">
      <div class="quick-actions">
        <a href="/reports.php" class="quick-action">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="20" x2="12" y2="10"></line><line x1="18" y1="20" x2="18" y2="4"></line><line x1="6" y1="20" x2="6" y2="16"></line></svg>
          Generate report
        </a>
        <a href="/team.php" class="quick-action">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
          Team directory
        </a>
        <a href="/alerts.php" class="quick-action">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path><path d="M13.73 21a2 2 0 0 1-3.46 0"></path></svg>
          Review alerts
        </a>
        <a href="/profile.php" class="quick-action">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
          My profile
        </a>
      </div>
    </div>
  </div>

  <div class="card">
    <div class="card-head">
      <div>
        <div class="card-title">Service Status</div>
        <div class="card-subtitle">Uptime monitor</div>
      </div>
    </div>
    <ul class="activity-list">
      <li class="activity-item">
        <span class="status-dot online"></span>
        <div class="activity-body">
          <div class="activity-text">phantom-api (prod)</div>
          <div class="activity-time">Operational · 99.99%</div>
        </div>
      </li>
      <li class="activity-item">
        <span class="status-dot online"></span>
        <div class="activity-body">
          <div class="activity-text">phantom-db (mongodb)</div>
          <div class="activity-time">Operational · 99.97%</div>
        </div>
      </li>
      <li class="activity-item">
        <span class="status-dot online"></span>
        <div class="activity-body">
          <div class="activity-text">reports-worker</div>
          <div class="activity-time">Operational · queue depth 0</div>
        </div>
      </li>
      <li class="activity-item">
        <span class="status-dot offline"></span>
        <div class="activity-body">
          <div class="activity-text">staging-frontend</div>
          <div class="activity-time">Decommissioned</div>
        </div>
      </li>
    </ul>
  </div>
</div>

<?php if ($isAdmin): ?>
<div class="card admin-status-card">
  <div class="card-head">
    <div>
      <div class="card-title">System Admin</div>
      <div class="card-subtitle">Administration quick glance</div>
    </div>
    <span class="badge badge-green">admin</span>
  </div>
  <div class="card-body">
    <div class="admin-status-row">
      <span class="status-dot online"></span>
      <span>System status: nominal</span>
    </div>
    <div class="admin-status-row">
      <span class="status-dot online"></span>
      <span>Uptime: 214 days · Last deploy: 2026-08-02 · Build: 3842</span>
    </div>
    <div style="margin-top: 14px;">
      <a class="btn btn-primary" href="/admin/index.php">Open Admin Panel</a>
    </div>
  </div>
</div>
<!-- [INTERNAL] build: FLAG{nosql_ate_my_login} -->
<?php endif; ?>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
