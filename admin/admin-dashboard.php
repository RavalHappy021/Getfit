<?php
require_once '../config.php';

// Fetch stats
$totalUsers = iterator_count($db->users->find());
$totalContent = iterator_count($db->content->find());
$totalMessages = iterator_count($db->contact_messages->find());
$totalGoals = iterator_count($db->goals->find());
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard - GetFit</title>
  <link rel="stylesheet" href="admin-style.css">
</head>
<body>

  <!-- SIDEBAR -->
  <aside class="sidebar">
    <div class="sidebar-header">
      <a href="../index.php" class="sidebar-brand">GetFit<span>.</span></a>
      <div class="sidebar-role">
        <span class="role-badge">ADMIN</span> Panel
      </div>
    </div>

    <nav class="nav-section">
      <div class="nav-label">Navigation</div>
      <a href="admin-dashboard.php" class="nav-item active">
        <span class="nav-icon">📊</span> Dashboard
      </a>
      <a href="manage-users.php" class="nav-item">
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
        <h1 class="page-title">Admin Dashboard</h1>
        <p class="page-subtitle">Welcome back! Here's what's happening with GetFit.</p>
      </div>
      <button class="dark-mode-toggle" onclick="toggleDarkMode()" id="dark-mode-btn">
        🌙 Toggle Dark Mode
      </button>
    </div>

    <!-- Stat Cards -->
    <div class="stat-cards-row">
      <div class="stat-card">
        <span class="stat-card-icon">👥</span>
        <div class="stat-card-value"><?= $totalUsers ?></div>
        <div class="stat-card-label">Total Users</div>
      </div>
      <div class="stat-card">
        <span class="stat-card-icon">📝</span>
        <div class="stat-card-value"><?= $totalContent ?></div>
        <div class="stat-card-label">Content Items</div>
      </div>
      <div class="stat-card">
        <span class="stat-card-icon">✉️</span>
        <div class="stat-card-value"><?= $totalMessages ?></div>
        <div class="stat-card-label">Messages</div>
      </div>
      <div class="stat-card">
        <span class="stat-card-icon">🎯</span>
        <div class="stat-card-value"><?= $totalGoals ?></div>
        <div class="stat-card-label">Active Goals</div>
      </div>
    </div>

    <!-- Quick Links -->
    <div class="admin-card">
      <div class="admin-card-header">
        <div class="admin-card-title">⚡ Quick Actions</div>
      </div>
      <div class="admin-card-body">
        <div class="quick-links">
          <a href="manage-users.php" class="quick-link-card" id="quick-users">
            <div class="quick-link-icon">👥</div>
            <div class="quick-link-title">Manage Users</div>
            <div class="quick-link-desc">View, manage and remove users</div>
          </a>
          <a href="manage-content.php" class="quick-link-card" id="quick-content">
            <div class="quick-link-icon">📝</div>
            <div class="quick-link-title">Manage Content</div>
            <div class="quick-link-desc">Update website content</div>
          </a>
          <a href="view-reports.php" class="quick-link-card" id="quick-reports">
            <div class="quick-link-icon">📈</div>
            <div class="quick-link-title">View Reports</div>
            <div class="quick-link-desc">Analytics and platform data</div>
          </a>
          <a href="contact.php" class="quick-link-card" id="quick-contact">
            <div class="quick-link-icon">✉️</div>
            <div class="quick-link-title">Contact Messages</div>
            <div class="quick-link-desc">Read user inquiries</div>
          </a>
        </div>
      </div>
    </div>

    <!-- About GetFit -->
    <div class="admin-card">
      <div class="admin-card-header">
        <div class="admin-card-title">ℹ️ About GetFit Platform</div>
      </div>
      <div class="admin-card-body">
        <p style="font-size:1.5rem; color:var(--text-2); line-height:1.7;">
          GetFit is a comprehensive fitness and nutrition platform offering personalized workout plans, diet guidance, AI-powered assistance, and progress tracking. As an admin, you can manage all users, update platform content, and view analytical reports.
        </p>
      </div>
    </div>
  </main>

  <footer class="admin-footer">
    <p>&copy; 2025 GetFit Admin Dashboard &mdash; All rights reserved.</p>
  </footer>

  <script>
    function toggleDarkMode() {
      document.body.classList.toggle('light-mode');
      const btn = document.getElementById('dark-mode-btn');
      btn.textContent = document.body.classList.contains('light-mode') ? '🌙 Dark Mode' : '☀️ Light Mode';
    }
  </script>
</body>
</html>
