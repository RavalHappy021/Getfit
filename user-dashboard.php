<?php
session_start();
require_once 'config.php';

if (session_status() == PHP_SESSION_NONE) { session_start(); }

if (!isset($_SESSION['username'])) {
    header("Location: assets/signin.php");
    exit();
}

try {
    $username = $_SESSION['username'];
    $user = $db->users->findOne(['username' => $username]);
    
    if (!$user) {
        header("Location: assets/user-logout.php");
        exit();
    }
    
    $userId = $user['_id']; // This is a MongoDB\BSON\ObjectId

    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_goal'])) {
        $goal_description = $_POST['goal_description'];
        $target_weight = (float)$_POST['target_weight'];
        $target_date = $_POST['target_date'];
        $status = "In Progress";
        
        $result = $db->goals->insertOne([
            'user_id' => $userId,
            'goal_description' => $goal_description,
            'target_weight' => $target_weight,
            'target_date' => $target_date,
            'status' => $status,
            'created_at' => new MongoDB\BSON\UTCDateTime()
        ]);
        
        if ($result->getInsertedCount() === 1) {
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        } else {
            echo "<script>alert('Failed to add goal. Please try again.');</script>";
        }
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_progress'])) {
        $progress_date = $_POST['progress_date'];
        $weight = (float)$_POST['weight'];
        $waist = (float)$_POST['waist'];
        $chest = (float)$_POST['chest'];
        
        $result = $db->progress->insertOne([
            'user_id' => $userId,
            'date' => $progress_date,
            'weight' => $weight,
            'waist' => $waist,
            'chest' => $chest,
            'created_at' => new MongoDB\BSON\UTCDateTime()
        ]);
        
        if ($result->getInsertedCount() === 1) {
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        } else {
            echo "<script>alert('Failed to save progress.');</script>";
        }
    }

    if (isset($_POST['edit_goal'])) {
        $goal_id = $_POST['goal_id'];
        $goal_description = $_POST['goal_description'];
        $target_weight = (float)$_POST['target_weight'];
        $target_date = $_POST['target_date'];
        
        $result = $db->goals->updateOne(
            ['_id' => new MongoDB\BSON\ObjectId($goal_id), 'user_id' => $userId],
            ['$set' => [
                'goal_description' => $goal_description,
                'target_weight' => $target_weight,
                'target_date' => $target_date
            ]]
        );
        
        if ($result->getModifiedCount() === 1) {
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        }
    }

    if (isset($_GET['delete_goal'])) {
        $goal_id = $_GET['delete_goal'];
        $result = $db->goals->deleteOne(['_id' => new MongoDB\BSON\ObjectId($goal_id), 'user_id' => $userId]);
        
        if ($result->getDeletedCount() === 1) {
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        }
    }

    $today = date('l');
    $workouts = $db->workout_plans->find(['user_id' => $userId, 'day' => $today]);
    $diets = $db->diet_plans->find(['user_id' => $userId, 'day' => $today]);
    $progress = $db->progress->find(['user_id' => $userId], ['sort' => ['date' => -1], 'limit' => 5]);
    $goals = $db->goals->find(['user_id' => $userId]);
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
    // In production, log this and show a nicer error
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard - GetFit</title>
  <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
  <style>
    :root {
      --primary: hsl(24, 100%, 50%);
      --primary-light: hsl(30, 100%, 60%);
      --primary-dark: hsl(18, 100%, 40%);
      --primary-glow: hsla(24, 100%, 50%, 0.25);
      --accent: hsl(165, 77%, 35%);
      --bg-1: hsl(220, 20%, 8%);
      --bg-2: hsl(220, 18%, 11%);
      --bg-3: hsl(220, 16%, 15%);
      --bg-4: hsl(220, 14%, 20%);
      --border-1: hsla(220, 20%, 100%, 0.07);
      --border-2: hsla(220, 20%, 100%, 0.12);
      --text-1: hsl(0, 0%, 98%);
      --text-2: hsl(220, 10%, 75%);
      --text-3: hsl(220, 8%, 55%);
      --sidebar-w: 260px;
    }

    *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }

    html { font-size: 10px; scroll-behavior: smooth; }

    body {
      font-family: 'Outfit', sans-serif;
      background: var(--bg-1);
      color: var(--text-2);
      display: flex;
      min-height: 100vh;
    }

    /* ===== SIDEBAR ===== */
    .sidebar {
      width: var(--sidebar-w);
      background: var(--bg-2);
      border-right: 1px solid var(--border-1);
      position: fixed;
      top: 0;
      left: 0;
      height: 100vh;
      display: flex;
      flex-direction: column;
      z-index: 50;
      transition: transform 0.3s ease;
    }

    .sidebar-header {
      padding: 28px 24px;
      border-bottom: 1px solid var(--border-1);
    }

    .sidebar-logo {
      font-size: 2.8rem;
      font-weight: 800;
      color: var(--text-1);
      text-decoration: none;
      display: block;
      margin-bottom: 4px;
    }

    .sidebar-logo span { color: var(--primary); }

    .sidebar-tagline {
      font-size: 1.2rem;
      color: var(--text-3);
      font-weight: 500;
    }

    .nav-section {
      padding: 24px 16px 0;
    }

    .nav-section-label {
      font-size: 1.1rem;
      font-weight: 700;
      color: var(--text-3);
      text-transform: uppercase;
      letter-spacing: 2px;
      padding: 0 8px;
      margin-bottom: 8px;
    }

    .nav-item {
      display: flex;
      align-items: center;
      gap: 12px;
      padding: 12px 16px;
      border-radius: 10px;
      color: var(--text-3);
      text-decoration: none;
      font-size: 1.5rem;
      font-weight: 500;
      transition: all 0.2s ease;
      margin-bottom: 2px;
    }

    .nav-item:hover,
    .nav-item.active {
      background: hsla(24, 100%, 50%, 0.1);
      color: var(--primary);
    }

    .nav-item .nav-icon {
      font-size: 20px;
      width: 36px;
      height: 36px;
      display: grid;
      place-items: center;
      background: var(--bg-3);
      border-radius: 8px;
      transition: all 0.2s ease;
    }

    .nav-item:hover .nav-icon,
    .nav-item.active .nav-icon {
      background: linear-gradient(135deg, var(--primary), var(--primary-dark));
    }

    .sidebar-footer {
      margin-top: auto;
      padding: 20px 16px;
      border-top: 1px solid var(--border-1);
      display: flex;
      gap: 8px;
    }

    .sidebar-footer a {
      flex: 1;
      padding: 10px 12px;
      border-radius: 10px;
      text-align: center;
      font-size: 1.4rem;
      font-weight: 600;
      text-decoration: none;
      transition: all 0.2s ease;
    }

    .btn-home-dash {
      background: var(--bg-3);
      color: var(--text-2);
      border: 1px solid var(--border-2);
    }

    .btn-home-dash:hover {
      background: var(--bg-4);
      color: var(--primary);
    }

    .btn-logout-dash {
      background: linear-gradient(135deg, var(--primary), var(--primary-dark));
      color: white;
      box-shadow: 0 4px 15px var(--primary-glow);
    }

    .btn-logout-dash:hover {
      transform: translateY(-1px);
      box-shadow: 0 6px 20px var(--primary-glow);
    }

    /* ===== MAIN CONTENT ===== */
    .main-content {
      margin-left: var(--sidebar-w);
      flex: 1;
      padding: 32px;
      min-height: 100vh;
    }

    /* ===== PAGE HEADER ===== */
    .page-header {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-bottom: 32px;
      flex-wrap: wrap;
      gap: 16px;
    }

    .welcome-title {
      font-size: 3.0rem;
      font-weight: 800;
      color: var(--text-1);
    }

    .welcome-title span {
      background: linear-gradient(135deg, var(--primary), var(--primary-light));
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
    }

    .welcome-subtitle {
      font-size: 1.5rem;
      color: var(--text-3);
      margin-top: 4px;
    }

    .badge-today {
      background: hsla(24, 100%, 50%, 0.12);
      border: 1px solid hsla(24, 100%, 50%, 0.25);
      color: var(--primary);
      padding: 8px 18px;
      border-radius: 20px;
      font-size: 1.4rem;
      font-weight: 600;
    }

    /* ===== STATS CARDS ===== */
    .stats-row {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 16px;
      margin-bottom: 28px;
    }

    .stat-card {
      background: var(--bg-2);
      border: 1px solid var(--border-1);
      border-radius: 16px;
      padding: 24px;
      transition: all 0.25s ease;
      position: relative;
      overflow: hidden;
    }

    .stat-card::after {
      content: '';
      position: absolute;
      bottom: 0;
      left: 0;
      right: 0;
      height: 3px;
      background: linear-gradient(90deg, var(--primary), var(--primary-light));
      opacity: 0;
      transition: opacity 0.25s;
    }

    .stat-card:hover {
      border-color: var(--border-2);
      transform: translateY(-3px);
    }

    .stat-card:hover::after { opacity: 1; }

    .stat-icon {
      font-size: 28px;
      margin-bottom: 12px;
    }

    .stat-value {
      font-size: 2.8rem;
      font-weight: 800;
      color: var(--text-1);
      margin-bottom: 4px;
    }

    .stat-label {
      font-size: 1.3rem;
      color: var(--text-3);
      font-weight: 500;
    }

    /* ===== SECTION CARD ===== */
    .dash-card {
      background: var(--bg-2);
      border: 1px solid var(--border-1);
      border-radius: 16px;
      padding: 28px;
      margin-bottom: 24px;
    }

    .dash-card-header {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-bottom: 20px;
      padding-bottom: 16px;
      border-bottom: 1px solid var(--border-1);
    }

    .dash-card-title {
      font-size: 1.8rem;
      font-weight: 700;
      color: var(--text-1);
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .dash-card-title .icon {
      background: linear-gradient(135deg, var(--primary), var(--primary-dark));
      width: 36px;
      height: 36px;
      border-radius: 8px;
      display: grid;
      place-items: center;
      font-size: 18px;
    }

    /* ===== PROFILE INFO ===== */
    .profile-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
      gap: 16px;
    }

    .profile-item {
      background: var(--bg-3);
      border-radius: 12px;
      padding: 16px;
    }

    .profile-item-label {
      font-size: 1.2rem;
      color: var(--text-3);
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 1px;
      margin-bottom: 6px;
    }

    .profile-item-value {
      font-size: 1.7rem;
      font-weight: 700;
      color: var(--text-1);
    }

    /* ===== WORKOUT LIST ===== */
    .workout-item {
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 14px 16px;
      background: var(--bg-3);
      border-radius: 10px;
      margin-bottom: 8px;
      border: 1px solid var(--border-1);
    }

    .workout-name {
      font-size: 1.5rem;
      color: var(--text-2);
    }

    .workout-status {
      font-size: 1.2rem;
      font-weight: 600;
      padding: 4px 12px;
      border-radius: 20px;
    }

    .status-done {
      background: hsla(120, 60%, 40%, 0.15);
      color: hsl(120, 60%, 60%);
    }

    .status-pending {
      background: hsla(24, 100%, 50%, 0.12);
      color: var(--primary);
    }

    /* ===== DIET LIST ===== */
    .diet-item {
      display: flex;
      align-items: center;
      gap: 14px;
      padding: 14px 16px;
      background: var(--bg-3);
      border-radius: 10px;
      margin-bottom: 8px;
      border: 1px solid var(--border-1);
    }

    .diet-meal-time {
      font-size: 1.4rem;
      font-weight: 700;
      color: var(--primary);
      min-width: 80px;
    }

    .diet-food {
      font-size: 1.4rem;
      color: var(--text-2);
      flex: 1;
    }

    .diet-calories {
      font-size: 1.3rem;
      color: var(--text-3);
      font-weight: 600;
    }

    /* ===== PROGRESS TABLE ===== */
    .progress-table {
      width: 100%;
      border-collapse: collapse;
    }

    .progress-table th {
      text-align: left;
      font-size: 1.2rem;
      font-weight: 700;
      color: var(--text-3);
      text-transform: uppercase;
      letter-spacing: 1px;
      padding: 8px 12px;
      border-bottom: 1px solid var(--border-1);
    }

    .progress-table td {
      padding: 12px;
      font-size: 1.4rem;
      color: var(--text-2);
      border-bottom: 1px solid var(--border-1);
    }

    .progress-table tr:last-child td { border-bottom: none; }
    .progress-table tr:hover td { background: var(--bg-3); }

    /* ===== GOAL CARDS ===== */
    .goal-card {
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 16px;
      background: var(--bg-3);
      border-radius: 12px;
      margin-bottom: 10px;
      border: 1px solid var(--border-1);
      flex-wrap: wrap;
      gap: 12px;
    }

    .goal-info { flex: 1; }

    .goal-title {
      font-size: 1.5rem;
      font-weight: 600;
      color: var(--text-1);
      margin-bottom: 4px;
    }

    .goal-meta {
      font-size: 1.3rem;
      color: var(--text-3);
    }

    .goal-actions {
      display: flex;
      gap: 8px;
    }

    .goal-status-badge {
      padding: 4px 12px;
      border-radius: 20px;
      font-size: 1.2rem;
      font-weight: 600;
      background: hsla(24, 100%, 50%, 0.12);
      color: var(--primary);
    }

    /* ===== FORMS ===== */
    .dash-form { margin-top: 20px; }

    .dash-form-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 16px;
      margin-bottom: 16px;
    }

    .dash-form label {
      display: block;
      font-size: 1.3rem;
      font-weight: 500;
      color: var(--text-2);
      margin-bottom: 7px;
    }

    .dash-form input,
    .dash-form select {
      width: 100%;
      padding: 12px 16px;
      background: var(--bg-3);
      border: 1.5px solid var(--border-2);
      border-radius: 10px;
      color: var(--text-1);
      font-size: 1.4rem;
      font-family: 'Outfit', sans-serif;
      transition: border-color 0.2s;
    }

    .dash-form input:focus,
    .dash-form select:focus {
      outline: none;
      border-color: var(--primary);
      box-shadow: 0 0 0 3px var(--primary-glow);
    }

    .dash-form input::placeholder { color: hsl(220, 6%, 40%); }

    .btn-submit {
      padding: 12px 28px;
      background: linear-gradient(135deg, var(--primary), var(--primary-dark));
      color: white;
      border: none;
      border-radius: 10px;
      font-size: 1.5rem;
      font-weight: 600;
      font-family: 'Outfit', sans-serif;
      cursor: pointer;
      box-shadow: 0 4px 15px var(--primary-glow);
      transition: all 0.2s ease;
    }

    .btn-submit:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 20px var(--primary-glow);
    }

    .btn-sm {
      padding: 6px 14px;
      border-radius: 8px;
      font-size: 1.3rem;
      font-weight: 600;
      cursor: pointer;
      border: none;
      font-family: 'Outfit', sans-serif;
      transition: all 0.2s;
      text-decoration: none;
      display: inline-block;
    }

    .btn-edit {
      background: hsla(210, 100%, 56%, 0.15);
      color: hsl(210, 100%, 66%);
    }

    .btn-edit:hover { background: hsla(210, 100%, 56%, 0.25); }

    .btn-delete {
      background: hsla(0, 85%, 55%, 0.12);
      color: hsl(0, 85%, 65%);
    }

    .btn-delete:hover { background: hsla(0, 85%, 55%, 0.22); }

    .empty-state {
      text-align: center;
      padding: 32px;
      color: var(--text-3);
      font-size: 1.5rem;
    }

    .empty-state .icon { font-size: 40px; margin-bottom: 10px; }

    @media (max-width: 768px) {
      .main-content {
        margin-left: 0;
        padding: 20px;
      }
      .sidebar {
        transform: translateX(-100%);
      }
    }
  </style>
</head>
<body>

  <!-- SIDEBAR -->
  <aside class="sidebar">
    <div class="sidebar-header">
      <a href="./index.php" class="sidebar-logo">GetFit<span>.</span></a>
      <div class="sidebar-tagline">Your fitness companion</div>
    </div>

    <nav class="nav-section">
      <div class="nav-section-label">Navigation</div>
      <a href="#profile" class="nav-item active">
        <span class="nav-icon">👤</span> Profile
      </a>
      <a href="#workout" class="nav-item">
        <span class="nav-icon">🏋️</span> Today's Workout
      </a>
      <a href="#diet" class="nav-item">
        <span class="nav-icon">🥗</span> Diet Plan
      </a>
      <a href="#progress" class="nav-item">
        <span class="nav-icon">📊</span> Progress
      </a>
      <a href="#goals" class="nav-item">
        <span class="nav-icon">🎯</span> Goals
      </a>
    </nav>

    <div class="sidebar-footer">
      <a href="./index.php" class="btn-home-dash">🏠 Home</a>
      <a href="assets/user-logout.php" class="btn-logout-dash">Logout</a>
    </div>
  </aside>

  <!-- MAIN CONTENT -->
  <main class="main-content">

    <!-- Page Header -->
    <div class="page-header">
      <div>
        <h1 class="welcome-title">Welcome, <span><?= htmlspecialchars($user['username']) ?></span>!</h1>
        <p class="welcome-subtitle">Here's an overview of your fitness journey</p>
      </div>
      <div class="badge-today">📅 <?= $today ?></div>
    </div>

    <!-- Stats Row -->
    <div class="stats-row">
      <div class="stat-card">
        <div class="stat-icon">⚖️</div>
        <div class="stat-value"><?= htmlspecialchars($user['weight']) ?> <small style="font-size:1.6rem;color:var(--text-3)">kg</small></div>
        <div class="stat-label">Current Weight</div>
      </div>
      <div class="stat-card">
        <div class="stat-icon">🎂</div>
        <div class="stat-value"><?= htmlspecialchars($user['age']) ?></div>
        <div class="stat-label">Age</div>
      </div>
      <div class="stat-card">
        <div class="stat-icon">📍</div>
        <div class="stat-value" style="font-size:2rem"><?= htmlspecialchars($user['city']) ?></div>
        <div class="stat-label">City</div>
      </div>
      <div class="stat-card">
        <div class="stat-icon">🎯</div>
        <div class="stat-value"><?= iterator_count($db->goals->find(['user_id' => $userId])) ?></div>
        <div class="stat-label">Active Goals</div>
      </div>
    </div>

    <!-- PROFILE -->
    <div class="dash-card" id="profile">
      <div class="dash-card-header">
        <h2 class="dash-card-title"><span class="icon">👤</span> Your Profile</h2>
      </div>
      <div class="profile-grid">
        <div class="profile-item">
          <div class="profile-item-label">Username</div>
          <div class="profile-item-value"><?= htmlspecialchars($user['username']) ?></div>
        </div>
        <div class="profile-item">
          <div class="profile-item-label">Age</div>
          <div class="profile-item-value"><?= htmlspecialchars($user['age']) ?> years</div>
        </div>
        <div class="profile-item">
          <div class="profile-item-label">City</div>
          <div class="profile-item-value"><?= htmlspecialchars($user['city']) ?></div>
        </div>
        <div class="profile-item">
          <div class="profile-item-label">Weight</div>
          <div class="profile-item-value"><?= htmlspecialchars($user['weight']) ?> kg</div>
        </div>
      </div>
    </div>

    <!-- TODAY'S WORKOUT -->
    <div class="dash-card" id="workout">
      <div class="dash-card-header">
        <h2 class="dash-card-title"><span class="icon">🏋️</span> Today's Workout</h2>
      </div>
      <?php 
      $workoutsArray = iterator_to_array($workouts);
      if (count($workoutsArray) > 0): ?>
        <?php foreach ($workoutsArray as $w): ?>
          <div class="workout-item">
            <span class="workout-name"><?= htmlspecialchars($w['workout']) ?></span>
            <span class="workout-status <?= $w['completed'] ? 'status-done' : 'status-pending' ?>">
              <?= $w['completed'] ? '✓ Done' : '⏳ Pending' ?>
            </span>
          </div>
        <?php endforeach; ?>
      <?php else: ?>
        <div class="empty-state">
          <div class="icon">😴</div>
          <p>No workout scheduled for today. Rest day!</p>
        </div>
      <?php endif; ?>
    </div>

    <!-- DIET PLAN -->
    <div class="dash-card" id="diet">
      <div class="dash-card-header">
        <h2 class="dash-card-title"><span class="icon">🥗</span> Diet Plan for Today</h2>
      </div>
      <?php 
      $dietsArray = iterator_to_array($diets);
      if (count($dietsArray) > 0): ?>
        <?php foreach ($dietsArray as $d): ?>
          <div class="diet-item">
            <span class="diet-meal-time"><?= htmlspecialchars($d['meal_time']) ?></span>
            <span class="diet-food"><?= htmlspecialchars($d['food_items']) ?></span>
            <span class="diet-calories"><?= htmlspecialchars($d['calories']) ?> kcal</span>
          </div>
        <?php endforeach; ?>
      <?php else: ?>
        <div class="empty-state">
          <div class="icon">🍽️</div>
          <p>No diet plan found for today.</p>
        </div>
      <?php endif; ?>
    </div>

    <!-- PROGRESS -->
    <div class="dash-card" id="progress">
      <div class="dash-card-header">
        <h2 class="dash-card-title"><span class="icon">📊</span> Recent Progress</h2>
      </div>
      <?php 
      $progressArray = iterator_to_array($progress);
      if (count($progressArray) > 0): ?>
        <table class="progress-table">
          <thead>
            <tr>
              <th>Date</th>
              <th>Weight (kg)</th>
              <th>Waist (in)</th>
              <th>Chest (in)</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($progressArray as $p): ?>
              <tr>
                <td><?= htmlspecialchars($p['date']) ?></td>
                <td><?= htmlspecialchars($p['weight']) ?> kg</td>
                <td><?= htmlspecialchars($p['waist']) ?> in</td>
                <td><?= htmlspecialchars($p['chest']) ?> in</td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      <?php else: ?>
        <div class="empty-state">
          <div class="icon">📈</div>
          <p>No progress recorded yet. Log your first entry!</p>
        </div>
      <?php endif; ?>

      <!-- Add Progress Form -->
      <div style="margin-top:24px;padding-top:20px;border-top:1px solid var(--border-1);">
        <h3 style="font-size:1.6rem;color:var(--text-1);margin-bottom:16px;">Log New Progress</h3>
        <form action="" method="POST" class="dash-form">
          <div class="dash-form-grid">
            <div>
              <label for="progress_date">Date</label>
              <input type="date" name="progress_date" id="progress_date" required value="<?= date('Y-m-d') ?>">
            </div>
            <div>
              <label for="prog_weight">Weight (kg)</label>
              <input type="number" name="weight" id="prog_weight" step="0.1" placeholder="70.5" required>
            </div>
            <div>
              <label for="waist">Waist (in)</label>
              <input type="number" name="waist" id="waist" step="0.1" placeholder="32" required>
            </div>
            <div>
              <label for="chest">Chest (in)</label>
              <input type="number" name="chest" id="chest" step="0.1" placeholder="38" required>
            </div>
          </div>
          <button type="submit" name="add_progress" class="btn-submit" id="log-progress-btn">+ Log Progress</button>
        </form>
      </div>
    </div>

    <!-- GOALS -->
    <div class="dash-card" id="goals">
      <div class="dash-card-header">
        <h2 class="dash-card-title"><span class="icon">🎯</span> Fitness Goals</h2>
      </div>

      <?php 
      $goalsArray = iterator_to_array($goals);
      if (count($goalsArray) > 0): ?>
        <?php foreach ($goalsArray as $g): ?>
          <div class="goal-card">
            <div class="goal-info">
              <div class="goal-title"><?= htmlspecialchars($g['goal_description']) ?></div>
              <div class="goal-meta">Target: <?= htmlspecialchars($g['target_weight']) ?>kg by <?= htmlspecialchars($g['target_date']) ?></div>
            </div>
            <span class="goal-status-badge"><?= htmlspecialchars($g['status']) ?></span>
            <div class="goal-actions">
              <a href="edit-goal.php?goal_id=<?= (string)$g['_id'] ?>" class="btn-sm btn-edit" id="edit-goal-<?= (string)$g['_id'] ?>">✏️ Edit</a>
              <a href="?delete_goal=<?= (string)$g['_id'] ?>" class="btn-sm btn-delete" onclick="return confirm('Delete this goal?')" id="delete-goal-<?= (string)$g['_id'] ?>">🗑️ Delete</a>
            </div>
          </div>
        <?php endforeach; ?>
      <?php else: ?>
        <div class="empty-state">
          <div class="icon">🎯</div>
          <p>No goals set yet. Add your first fitness goal!</p>
        </div>
      <?php endif; ?>

      <!-- Add Goal Form -->
      <div style="margin-top:24px;padding-top:20px;border-top:1px solid var(--border-1);">
        <h3 style="font-size:1.6rem;color:var(--text-1);margin-bottom:16px;">Set a New Goal</h3>
        <form action="" method="POST" class="dash-form">
          <div class="dash-form-grid">
            <div>
              <label for="goal_description">Goal Description</label>
              <input type="text" name="goal_description" id="goal_description" placeholder="e.g. Lose 5kg" required>
            </div>
            <div>
              <label for="target_weight">Target Weight (kg)</label>
              <input type="number" name="target_weight" id="target_weight" step="0.1" placeholder="65" required>
            </div>
            <div>
              <label for="target_date">Target Date</label>
              <input type="date" name="target_date" id="target_date" required>
            </div>
          </div>
          <button type="submit" name="add_goal" class="btn-submit" id="add-goal-btn">+ Add Goal</button>
        </form>
      </div>
    </div>

  </main>

  <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
  <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>

  <script>
    // Highlight active nav based on scroll
    const sections = document.querySelectorAll('.dash-card');
    const navItems = document.querySelectorAll('.nav-item');

    const observer = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          navItems.forEach(n => n.classList.remove('active'));
          const id = entry.target.id;
          const link = document.querySelector(`.nav-item[href="#${id}"]`);
          if (link) link.classList.add('active');
        }
      });
    }, { threshold: 0.5 });

    sections.forEach(s => observer.observe(s));
  </script>
</body>
</html>
