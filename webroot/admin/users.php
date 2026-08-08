<?php
$pageTitle = 'User Management';
require_once __DIR__ . '/../includes/auth.php';
require_admin();
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../includes/header.php';

$users = $collection->find();
$users = iterator_to_array($users);

$roleBadge = [
    'admin' => 'badge-blue',
    'user' => 'badge-grey',
];
?>

<div class="card">
  <div class="toolbar">
    <div class="search-wrap">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
      <input class="search-input" type="text" placeholder="Search users...">
    </div>
    <button class="btn btn-primary" type="button" style="margin-left: auto;" onclick="openModal('addUserModal')">Add User</button>
  </div>

  <div class="table-wrap" style="border: none; border-radius: 0; max-height: 540px;">
    <table class="table">
      <thead>
        <tr>
          <th>Username</th>
          <th>Role</th>
          <th>Created</th>
          <th>Last Login</th>
          <th>Status</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($users as $u): ?>
        <tr>
          <td>
            <span class="avatar avatar-sm" style="display: inline-flex; vertical-align: middle; margin-right: 8px;"><?php echo htmlspecialchars(user_initials(isset($u['display_name']) ? $u['display_name'] : $u['username'])); ?></span>
            <?php echo htmlspecialchars($u['username']); ?>
          </td>
          <td><span class="badge <?php echo isset($roleBadge[$u['role']]) ? $roleBadge[$u['role']] : 'badge-grey'; ?>"><?php echo htmlspecialchars($u['role']); ?></span></td>
          <td><?php echo htmlspecialchars(isset($u['created_at']) ? $u['created_at'] : '—'); ?></td>
          <td><?php echo htmlspecialchars(isset($u['last_login']) ? $u['last_login'] : '—'); ?></td>
          <td>
            <?php if (isset($u['status']) && $u['status'] === 'disabled'): ?>
            <span class="badge badge-red">disabled</span>
            <?php else: ?>
            <span class="badge badge-green">active</span>
            <?php endif; ?>
          </td>
          <td>
            <div class="cell-actions">
              <button class="btn btn-sm" type="button">Edit</button>
              <button class="btn btn-sm btn-danger" type="button">Disable</button>
            </div>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>

  <div class="pagination">
    <span class="page-info">Showing all <?php echo count($users); ?> accounts</span>
  </div>
</div>

<div class="modal-backdrop" id="addUserModal">
  <div class="modal">
    <div class="modal-head">
      <div class="modal-title">Add New User</div>
      <button class="modal-close" type="button" onclick="closeModal('addUserModal')">&times;</button>
    </div>
    <form method="post" action="/admin/users.php">
      <div class="form-row">
        <div class="form-group">
          <label for="nu_first">First name</label>
          <input class="form-control" type="text" id="nu_first" name="first_name">
        </div>
        <div class="form-group">
          <label for="nu_last">Last name</label>
          <input class="form-control" type="text" id="nu_last" name="last_name">
        </div>
      </div>
      <div class="form-row">
        <div class="form-group">
          <label for="nu_user">Username</label>
          <input class="form-control" type="text" id="nu_user" name="username">
        </div>
        <div class="form-group">
          <label for="nu_email">Email</label>
          <input class="form-control" type="email" id="nu_email" name="email">
        </div>
      </div>
      <div class="form-row">
        <div class="form-group">
          <label for="nu_pass">Temporary password</label>
          <input class="form-control" type="password" id="nu_pass" name="password">
        </div>
        <div class="form-group">
          <label for="nu_role">Role</label>
          <select class="form-control" id="nu_role" name="role">
            <option>user</option>
            <option>admin</option>
          </select>
        </div>
      </div>
      <div class="modal-actions">
        <button class="btn" type="button" onclick="closeModal('addUserModal')">Cancel</button>
        <button class="btn btn-primary" type="submit">Create user</button>
      </div>
    </form>
  </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
