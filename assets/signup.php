<?php
include 'signup_db_connect.php';

$errors = "";
$age = $city = $weight = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $age = (int)$_POST['age'];
    $city = $_POST['city'];
    $weight = (float)$_POST['weight'];

    if ($password !== $confirm_password) {
        $errors = "Passwords do not match!";
    } elseif (strlen($password) < 6) {
        $errors = "Password must be at least 6 characters!";
    } else {
        try {
            // Check if user already exists
            $existingUser = $db->users->findOne([
                '$or' => [
                    ['email' => $email],
                    ['username' => $username]
                ]
            ]);

            if ($existingUser) {
                $errors = "Email or Username already exists!";
            } else {
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $result = $db->users->insertOne([
                    'email' => $email,
                    'username' => $username,
                    'password' => $hashed_password,
                    'age' => $age,
                    'city' => $city,
                    'weight' => $weight,
                    'role' => 'user',
                    'created_at' => new MongoDB\BSON\UTCDateTime()
                ]);

                if ($result->getInsertedCount() === 1) {
                    echo "<script>alert('Registration Successful! You can now log in.'); window.location.href = 'signin.php';</script>";
                } else {
                    $errors = "Error: Could not register user.";
                }
            }
        } catch (Exception $e) {
            $errors = "Error: " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sign Up - GetFit</title>
  <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
  <style>
    :root {
      --primary: hsl(24, 100%, 50%);
      --primary-dark: hsl(18, 100%, 40%);
      --primary-glow: hsla(24, 100%, 50%, 0.3);
      --bg-1: hsl(220, 20%, 8%);
      --bg-2: hsl(220, 18%, 11%);
      --bg-3: hsl(220, 16%, 15%);
      --border-1: hsla(220, 20%, 100%, 0.08);
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
      padding: 24px 20px;
      position: relative;
      overflow-x: hidden;
    }

    body::before {
      content: '';
      position: fixed;
      width: 500px;
      height: 500px;
      background: radial-gradient(circle, hsla(24,100%,50%,0.10), transparent);
      top: -200px;
      right: -100px;
      border-radius: 50%;
      pointer-events: none;
      animation: float 8s ease-in-out infinite;
    }

    body::after {
      content: '';
      position: fixed;
      width: 400px;
      height: 400px;
      background: radial-gradient(circle, hsla(165,77%,35%,0.08), transparent);
      bottom: -150px;
      left: -100px;
      border-radius: 50%;
      pointer-events: none;
      animation: float 10s ease-in-out infinite reverse;
    }

    @keyframes float {
      0%, 100% { transform: translateY(0) scale(1); }
      50% { transform: translateY(-30px) scale(1.05); }
    }

    .auth-wrapper {
      width: 100%;
      max-width: 520px;
      position: relative;
      z-index: 1;
    }

    .logo {
      text-align: center;
      margin-bottom: 28px;
    }

    .logo a {
      font-size: 3.2rem;
      font-weight: 800;
      color: var(--text-1);
      text-decoration: none;
    }

    .logo a span { color: var(--primary); }

    .auth-card {
      background: var(--bg-2);
      border: 1px solid var(--border-2);
      border-radius: 24px;
      padding: 40px;
      box-shadow: 0 25px 80px rgba(0,0,0,0.5);
      position: relative;
      overflow: hidden;
    }

    .auth-card::before {
      content: '';
      position: absolute;
      top: 0; left: 0; right: 0;
      height: 3px;
      background: linear-gradient(90deg, var(--primary), hsl(30,100%,60%), hsl(165,77%,35%));
    }

    .auth-title {
      font-size: 2.4rem;
      font-weight: 700;
      color: var(--text-1);
      margin-bottom: 4px;
    }

    .auth-subtitle {
      font-size: 1.4rem;
      color: var(--text-3);
      margin-bottom: 30px;
    }

    .form-row {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 16px;
    }

    .form-group {
      margin-bottom: 18px;
    }

    .form-group label {
      display: block;
      font-size: 1.3rem;
      font-weight: 500;
      color: var(--text-2);
      margin-bottom: 7px;
    }

    .input-wrap { position: relative; }

    .input-icon {
      position: absolute;
      left: 14px;
      top: 50%;
      transform: translateY(-50%);
      font-size: 16px;
      color: var(--text-4);
      pointer-events: none;
    }

    .form-group input {
      width: 100%;
      padding: 13px 14px 13px 42px;
      background: var(--bg-3);
      border: 1.5px solid var(--border-2);
      border-radius: 10px;
      color: var(--text-1);
      font-size: 1.4rem;
      font-family: 'Outfit', sans-serif;
      transition: border-color 0.2s, box-shadow 0.2s;
    }

    .form-group input:focus {
      outline: none;
      border-color: var(--primary);
      box-shadow: 0 0 0 3px var(--primary-glow);
    }

    .form-group input::placeholder { color: var(--text-4); }

    .submit-btn {
      width: 100%;
      padding: 15px;
      background: linear-gradient(135deg, var(--primary), var(--primary-dark));
      color: white;
      border: none;
      border-radius: 12px;
      font-size: 1.6rem;
      font-weight: 600;
      font-family: 'Outfit', sans-serif;
      cursor: pointer;
      box-shadow: 0 4px 20px hsla(24, 100%, 50%, 0.4);
      transition: all 0.25s ease;
      margin-top: 8px;
    }

    .submit-btn:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 30px hsla(24, 100%, 50%, 0.5);
    }

    .error-msg {
      background: hsla(0, 85%, 55%, 0.12);
      border: 1px solid hsla(0, 85%, 55%, 0.3);
      color: hsl(0, 85%, 65%);
      border-radius: 10px;
      padding: 12px 16px;
      font-size: 1.4rem;
      margin-bottom: 20px;
    }

    .auth-footer {
      text-align: center;
      margin-top: 20px;
    }

    .auth-footer p {
      font-size: 1.4rem;
      color: var(--text-3);
    }

    .auth-footer a {
      color: var(--primary);
      text-decoration: none;
      font-weight: 600;
    }

    .auth-footer a:hover { text-decoration: underline; }

    @media (max-width: 480px) {
      .form-row { grid-template-columns: 1fr; }
      .auth-card { padding: 30px 24px; }
    }
  </style>
</head>
<body>
  <div class="auth-wrapper">
    <div class="logo">
      <a href="../index.php">GetFit<span>.</span></a>
    </div>

    <div class="auth-card">
      <h2 class="auth-title">Create your account</h2>
      <p class="auth-subtitle">Join thousands transforming their lives with GetFit</p>

      <?php if ($errors): ?>
        <div class="error-msg">⚠️ <?= htmlspecialchars($errors) ?></div>
      <?php endif; ?>

      <form action="" method="POST">
        <div class="form-row">
          <div class="form-group">
            <label for="username">Username</label>
            <div class="input-wrap">
              <span class="input-icon">👤</span>
              <input type="text" name="username" id="username" placeholder="your_name" required>
            </div>
          </div>
          <div class="form-group">
            <label for="email">Email</label>
            <div class="input-wrap">
              <span class="input-icon">✉️</span>
              <input type="email" name="email" id="email" placeholder="you@example.com" required>
            </div>
          </div>
        </div>

        <div class="form-row">
          <div class="form-group">
            <label for="password">Password</label>
            <div class="input-wrap">
              <span class="input-icon">🔒</span>
              <input type="password" name="password" id="password" placeholder="Min. 6 chars" required>
            </div>
          </div>
          <div class="form-group">
            <label for="confirm_password">Confirm Password</label>
            <div class="input-wrap">
              <span class="input-icon">🔑</span>
              <input type="password" name="confirm_password" id="confirm_password" placeholder="Repeat password" required>
            </div>
          </div>
        </div>

        <div class="form-row">
          <div class="form-group">
            <label for="age">Age</label>
            <div class="input-wrap">
              <span class="input-icon">🎂</span>
              <input type="number" name="age" id="age" placeholder="25" min="10" max="100" required>
            </div>
          </div>
          <div class="form-group">
            <label for="city">City</label>
            <div class="input-wrap">
              <span class="input-icon">📍</span>
              <input type="text" name="city" id="city" placeholder="Mumbai" required>
            </div>
          </div>
        </div>

        <div class="form-group">
          <label for="weight">Current Weight (kg)</label>
          <div class="input-wrap">
            <span class="input-icon">⚖️</span>
            <input type="number" name="weight" id="weight" placeholder="70" min="30" max="300" step="0.1" required>
          </div>
        </div>

        <button type="submit" class="submit-btn" id="signup-submit-btn">Create Account →</button>
      </form>

      <div class="auth-footer">
        <p>Already have an account? <a href="signin.php" id="goto-signin">Sign in here</a></p>
      </div>
    </div>
  </div>
</body>
</html>
