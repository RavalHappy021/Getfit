<?php
include('db.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Manage Content - GetFit Admin</title>
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
      <a href="manage-content.php" class="nav-item active">
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
        <h1 class="page-title">Manage Content</h1>
        <p class="page-subtitle">Update and publish platform content</p>
      </div>
      <a href="admin-dashboard.php" class="btn btn-secondary-outline" id="back-dashboard-btn">← Dashboard</a>
    </div>

    <div class="admin-card">
      <div class="admin-card-header">
        <div class="admin-card-title">📝 Content Editor</div>
      </div>
      <div class="admin-card-body">
        <p style="font-size:1.5rem; color:var(--text-3); margin-bottom:24px;">
          Use the editor below to add or update website content. Changes will be published immediately.
        </p>
        <form action="save-content.php" method="post" class="admin-form" id="content-form">
          <label for="content">Content</label>
          <textarea name="content" id="content" rows="10" placeholder="Enter your content here..." required></textarea>
          <button type="submit" class="btn btn-primary" id="save-content-btn">💾 Save Content</button>
        </form>
      </div>
    </div>

    <!-- Tips Card -->
    <div class="admin-card">
      <div class="admin-card-header">
        <div class="admin-card-title">💡 Content Tips</div>
      </div>
      <div class="admin-card-body">
        <ul style="font-size:1.5rem; color:var(--text-3); line-height:2; padding-left:20px; list-style: disc;">
          <li>Write clear, engaging content for your fitness audience</li>
          <li>Include actionable tips and specific workout instructions</li>
          <li>Keep nutrition information backed by research</li>
          <li>Update content regularly to keep users engaged</li>
        </ul>
      </div>
    </div>
  </main>

  <footer class="admin-footer">
    <p>&copy; 2025 GetFit Admin Dashboard</p>
  </footer>

</body>
</html>
