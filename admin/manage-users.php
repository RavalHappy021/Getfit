<?php
include('db.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Manage Users - GetFit Admin</title>
  <link rel="stylesheet" href="admin-style.css">
</head>
<body>

  <!-- SIDEBAR -->
  <aside class="sidebar">
    <div class="sidebar-header">
      <a href="../index.php" class="sidebar-brand">GetFit<span>.</span></a>
      <div class="sidebar-role"><span class="role-badge">ADMIN</span> Panel</div>
    </div>
    <nav class="nav-section">
      <div class="nav-label">Navigation</div>
      <a href="admin-dashboard.php" class="nav-item">
        <span class="nav-icon">📊</span> Dashboard
      </a>
      <a href="manage-users.php" class="nav-item active">
        <span class="nav-icon">👥</span> Manage Users
      </a>
      <a href="manage-content.php" class="nav-item">
        <span class="nav-icon">📝</span> Manage Content
      </a>
      <a href="view-reports.php" class="nav-item">
        <span class="nav-icon">📈</span> View Reports
      </a>
      <a href="contact.php" class="nav-item">
        <span class="nav-icon">✉️</span> Contact Messages
      </a>
    </nav>
    <div class="sidebar-footer">
      <a href="logout.php" class="btn-logout" id="admin-logout-btn">🚪 Logout</a>
    </div>
  </aside>

  <!-- MAIN CONTENT -->
  <main class="main-content">
    <div class="page-header">
      <div>
        <h1 class="page-title">Manage Users</h1>
        <p class="page-subtitle">View and manage all registered members</p>
      </div>
      <a href="admin-dashboard.php" class="btn btn-secondary-outline" id="back-dashboard-btn">← Dashboard</a>
    </div>

    <div class="admin-card">
      <div class="admin-card-header">
        <div class="admin-card-title">👥 Registered Users</div>
      </div>

      <?php
        $cursor = $db->users->find([], ['sort' => ['_id' => -1]]);
        $users = iterator_to_array($cursor);
        $count = count($users);
      ?>

      <div style="padding: 0 24px 10px; display:flex; align-items:center; gap:12px;">
        <span style="font-size:1.4rem; color:var(--text-3)">Total users: <strong style="color:var(--primary)"><?= $count ?></strong></span>
      </div>

      <?php if ($count > 0): ?>
        <table class="admin-table" style="margin: 0 24px 24px; width: calc(100% - 48px);">
          <thead>
            <tr>
              <th>#</th>
              <th>Username</th>
              <th>Email</th>
              <th>City</th>
              <th>Weight</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($users as $row): ?>
              <tr>
                <td><?= htmlspecialchars((string)$row['_id']) ?></td>
                <td><strong style="color:var(--text-1)"><?= htmlspecialchars($row['username']) ?></strong></td>
                <td><?= htmlspecialchars($row['email']) ?></td>
                <td><?= htmlspecialchars($row['city'] ?? '—') ?></td>
                <td><?= htmlspecialchars($row['weight'] ?? '—') ?> kg</td>
                <td>
                  <a href="delete-user.php?id=<?= (string)$row['_id'] ?>" class="btn btn-danger" id="delete-user-<?= (string)$row['_id'] ?>"
                     onclick="return confirm('Are you sure you want to delete this user? This cannot be undone.')">
                    🗑️ Delete
                  </a>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      <?php else: ?>
        <div class="empty-state">
          <div class="empty-state-icon">👥</div>
          <p class="empty-state-text">No users registered yet.</p>
        </div>
      <?php endif; ?>
    </div>
  </main>

  <footer class="admin-footer">
    <p>&copy; 2025 GetFit Admin Dashboard</p>
  </footer>

</body>
</html>
