<?php
session_start();
$conn = new mysqli("localhost", "root", "", "getfit_db");

if ($conn->connect_error) { die("Connection failed: " . $conn->connect_error); }

if (!isset($_SESSION['username'])) {
    header("Location: assets/signin.php");
    exit();
}

if (isset($_GET['goal_id'])) {
    $goal_id = intval($_GET['goal_id']);
    $goalQuery = $conn->prepare("SELECT * FROM goals WHERE id = ?");
    $goalQuery->bind_param("i", $goal_id);
    $goalQuery->execute();
    $goalResult = $goalQuery->get_result();
    $goal = $goalResult->fetch_assoc();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit_goal'])) {
    $goal_description = $_POST['goal_description'];
    $target_weight    = $_POST['target_weight'];
    $target_date      = $_POST['target_date'];
    $goal_id          = intval($_POST['goal_id']);

    $editGoal = $conn->prepare("UPDATE goals SET goal_description = ?, target_weight = ?, target_date = ? WHERE id = ?");
    $editGoal->bind_param("sdsi", $goal_description, $target_weight, $target_date, $goal_id);

    if ($editGoal->execute()) {
        header("Location: user-dashboard.php");
        exit();
    } else {
        echo "<script>alert('Failed to update goal. Please try again.');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edit Goal - GetFit</title>
  <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <style>
    :root {
      --primary: hsl(24, 100%, 50%);
      --primary-dark: hsl(18, 100%, 40%);
      --primary-glow: hsla(24, 100%, 50%, 0.3);
      --bg-1: hsl(220, 20%, 8%);
      --bg-2: hsl(220, 18%, 11%);
      --bg-3: hsl(220, 16%, 15%);
      --border-2: hsla(220, 20%, 100%, 0.14);
      --text-1: hsl(0, 0%, 98%);
      --text-2: hsl(220, 10%, 75%);
      --text-3: hsl(220, 8%, 55%);
      --text-4: hsl(220, 6%, 40%);
    }

    *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }

    body {
      font-family: 'Outfit', sans-serif;
      background: var(--bg-1);
      min-height: 100vh;
      display: grid;
      place-items: center;
      padding: 24px;
      position: relative;
      overflow: hidden;
    }

    body::before {
      content: '';
      position: fixed;
      width: 400px; height: 400px;
      background: radial-gradient(circle, hsla(24,100%,50%,0.08), transparent);
      top: -150px; right: -100px;
      border-radius: 50%;
      pointer-events: none;
    }

    .wrapper {
      width: 100%;
      max-width: 480px;
      position: relative;
      z-index: 1;
    }

    .logo {
      text-align: center;
      margin-bottom: 24px;
    }

    .logo a {
      font-size: 2.8rem;
      font-weight: 800;
      color: hsl(0,0%,98%);
      text-decoration: none;
    }

    .logo a span { color: var(--primary); }

    .card {
      background: var(--bg-2);
      border: 1px solid var(--border-2);
      border-radius: 24px;
      padding: 40px;
      box-shadow: 0 25px 80px rgba(0,0,0,0.5);
      position: relative;
      overflow: hidden;
    }

    .card::before {
      content: '';
      position: absolute;
      top: 0; left: 0; right: 0;
      height: 3px;
      background: linear-gradient(90deg, var(--primary), hsl(30,100%,60%));
    }

    h2 {
      font-size: 2.4rem;
      font-weight: 700;
      color: hsl(0,0%,98%);
      margin-bottom: 6px;
    }

    .subtitle {
      font-size: 1.4rem;
      color: var(--text-3);
      margin-bottom: 28px;
    }

    .form-group { margin-bottom: 20px; }

    label {
      display: block;
      font-size: 1.3rem;
      font-weight: 500;
      color: var(--text-2);
      margin-bottom: 8px;
    }

    input {
      width: 100%;
      padding: 13px 16px;
      background: var(--bg-3);
      border: 1.5px solid var(--border-2);
      border-radius: 10px;
      color: hsl(0,0%,98%);
      font-size: 1.5rem;
      font-family: 'Outfit', sans-serif;
      transition: border-color 0.2s, box-shadow 0.2s;
    }

    input:focus {
      outline: none;
      border-color: var(--primary);
      box-shadow: 0 0 0 3px var(--primary-glow);
    }

    .submit-btn {
      width: 100%;
      padding: 14px;
      background: linear-gradient(135deg, var(--primary), var(--primary-dark));
      color: white;
      border: none;
      border-radius: 12px;
      font-size: 1.6rem;
      font-weight: 700;
      font-family: 'Outfit', sans-serif;
      cursor: pointer;
      box-shadow: 0 4px 20px var(--primary-glow);
      transition: all 0.25s;
      margin-top: 8px;
    }

    .submit-btn:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 28px var(--primary-glow);
    }

    .back-link {
      display: block;
      text-align: center;
      margin-top: 20px;
      font-size: 1.4rem;
      color: var(--text-3);
      text-decoration: none;
    }

    .back-link:hover { color: var(--primary); }
  </style>
</head>
<body>
  <div class="wrapper">
    <div class="logo">
      <a href="index.php">GetFit<span>.</span></a>
    </div>

    <div class="card">
      <h2>✏️ Edit Goal</h2>
      <p class="subtitle">Update your fitness goal details below</p>

      <form action="" method="POST">
        <input type="hidden" name="goal_id" value="<?= htmlspecialchars($goal['id']) ?>">

        <div class="form-group">
          <label for="goal_description">Goal Description</label>
          <input type="text" name="goal_description" id="goal_description"
                 value="<?= htmlspecialchars($goal['goal_description']) ?>" required
                 placeholder="e.g. Lose 5kg in 2 months">
        </div>

        <div class="form-group">
          <label for="target_weight">Target Weight (kg)</label>
          <input type="number" name="target_weight" id="target_weight" step="0.1"
                 value="<?= htmlspecialchars($goal['target_weight']) ?>" required
                 placeholder="65">
        </div>

        <div class="form-group">
          <label for="target_date">Target Date</label>
          <input type="date" name="target_date" id="target_date"
                 value="<?= htmlspecialchars($goal['target_date']) ?>" required>
        </div>

        <button type="submit" name="edit_goal" class="submit-btn" id="update-goal-btn">
          ✅ Update Goal
        </button>
      </form>
    </div>

    <a href="user-dashboard.php" class="back-link" id="back-to-dashboard">← Back to Dashboard</a>
  </div>
</body>
</html>
