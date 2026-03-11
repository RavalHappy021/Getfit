<?php
include('db.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>View Reports - GetFit Admin</title>
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
      <a href="manage-users.php" class="nav-item">
        <span class="nav-icon">👥</span> Manage Users
      </a>
      <a href="manage-content.php" class="nav-item">
        <span class="nav-icon">📝</span> Manage Content
      </a>
      <a href="view-reports.php" class="nav-item active">
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
        <h1 class="page-title">View Reports</h1>
        <p class="page-subtitle">Analytics, insights, and platform data</p>
      </div>
      <a href="admin-dashboard.php" class="btn btn-secondary-outline" id="back-dashboard-btn">← Dashboard</a>
    </div>

    <!-- Quick Stats from DB -->
    <div class="stat-cards-row">
      <?php
        $userCount = mysqli_fetch_row(mysqli_query($conn, "SELECT COUNT(*) FROM users"))[0] ?? 0;
        $goalCount = mysqli_fetch_row(mysqli_query($conn, "SELECT COUNT(*) FROM goals"))[0] ?? 0;
        $progressCount = mysqli_fetch_row(mysqli_query($conn, "SELECT COUNT(*) FROM progress"))[0] ?? 0;
      ?>
      <div class="stat-card">
        <span class="stat-card-icon">👥</span>
        <div class="stat-card-value"><?= $userCount ?></div>
        <div class="stat-card-label">Registered Users</div>
      </div>
      <div class="stat-card">
        <span class="stat-card-icon">🎯</span>
        <div class="stat-card-value"><?= $goalCount ?></div>
        <div class="stat-card-label">Total Goals Set</div>
      </div>
      <div class="stat-card">
        <span class="stat-card-icon">📊</span>
        <div class="stat-card-value"><?= $progressCount ?></div>
        <div class="stat-card-label">Progress Logs</div>
      </div>
    </div>

    <!-- Report Note -->
    <div class="admin-card">
      <div class="admin-card-header">
        <div class="admin-card-title">📈 Advanced Analytics</div>
      </div>
      <div class="admin-card-body">
        <div style="text-align:center; padding: 40px 20px;">
          <div style="font-size:60px; margin-bottom:16px;">🚀</div>
          <h2 style="font-size:2.2rem; color:var(--text-1); font-weight:700; margin-bottom:12px;">Full Analytics Coming Soon</h2>
          <p style="font-size:1.5rem; color:var(--text-3); max-width:500px; margin:0 auto; line-height:1.7;">
            Advanced charts, workout completion rates, user engagement metrics, and nutrition tracking insights are being developed. In the meantime, key platform stats are shown above.
          </p>
        </div>
      </div>
    </div>

    <!-- Recent Users -->
    <div class="admin-card">
      <div class="admin-card-header">
        <div class="admin-card-title">👥 Recently Joined Users</div>
        <a href="manage-users.php" class="btn btn-secondary-outline" id="view-all-users-btn" style="font-size:1.3rem;">View All →</a>
      </div>
      <?php
        $recentResult = mysqli_query($conn, "SELECT id, username, email, city FROM users ORDER BY id DESC LIMIT 5");
      ?>
      <?php if ($recentResult && mysqli_num_rows($recentResult) > 0): ?>
        <table class="admin-table" style="margin: 0 24px 24px; width: calc(100% - 48px);">
          <thead>
            <tr>
              <th>#</th>
              <th>Username</th>
              <th>Email</th>
              <th>City</th>
            </tr>
          </thead>
          <tbody>
            <?php while ($row = mysqli_fetch_assoc($recentResult)): ?>
              <tr>
                <td><?= $row['id'] ?></td>
                <td><strong style="color:var(--text-1)"><?= htmlspecialchars($row['username']) ?></strong></td>
                <td><?= htmlspecialchars($row['email']) ?></td>
                <td><?= htmlspecialchars($row['city'] ?? '—') ?></td>
              </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      <?php else: ?>
        <div class="empty-state">
          <div class="empty-state-icon">👥</div>
          <p class="empty-state-text">No users found.</p>
        </div>
      <?php endif; ?>
    </div>
  </main>

  <footer class="admin-footer">
    <p>&copy; 2025 GetFit Admin Dashboard</p>
  </footer>

</body>
</html>
