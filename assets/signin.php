<?php
ob_start();
session_start();
require_once __DIR__ . '/signup_db_connect.php';
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    try {
        $user = $db->users->findOne(['email' => $email]);

        if ($user) {
            if (password_verify($password, $user['password'])) {
                $_SESSION['username'] = $user['username'];
                // $_SESSION['role'] = $user['role']; // Optional but good for consistency
                header("Location: ../user-dashboard.php");
                exit();
            } else {
                $error = "Incorrect password. Please try again.";
            }
        } else {
            $error = "No account found with this email.";
        }
    } catch (Exception $e) {
        $error = "Database error: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sign In - GetFit</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
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
      padding: 20px;
      position: relative;
      overflow: hidden;
    }

    /* Animated background orbs */
    body::before, body::after {
      content: '';
      position: fixed;
      border-radius: 50%;
      pointer-events: none;
    }

    body::before {
      width: 500px;
      height: 500px;
      background: radial-gradient(circle, hsla(24,100%,50%,0.12), transparent);
      top: -200px;
      right: -100px;
      animation: float 8s ease-in-out infinite;
    }

    body::after {
      width: 400px;
      height: 400px;
      background: radial-gradient(circle, hsla(165,77%,35%,0.10), transparent);
      bottom: -150px;
      left: -100px;
      animation: float 10s ease-in-out infinite reverse;
    }

    @keyframes float {
      0%, 100% { transform: translateY(0) scale(1); }
      50% { transform: translateY(-30px) scale(1.05); }
    }

    .auth-wrapper {
      width: 100%;
      max-width: 440px;
      position: relative;
      z-index: 1;
    }

    .logo {
      text-align: center;
      margin-bottom: 32px;
    }

    .logo a {
      font-size: 3.2rem;
      font-weight: 800;
      color: var(--text-1);
      text-decoration: none;
      letter-spacing: -0.5px;
    }

    .logo a span {
      color: var(--primary);
    }

    .auth-card {
      background: var(--bg-2);
      border: 1px solid var(--border-2);
      border-radius: 24px;
      padding: 44px 40px;
      box-shadow: 0 25px 80px rgba(0,0,0,0.5);
      position: relative;
      overflow: hidden;
    }

    .auth-card::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      height: 3px;
      background: linear-gradient(90deg, var(--primary), hsl(30,100%,60%), hsl(165,77%,35%));
    }

    .auth-title {
      font-size: 2.6rem;
      font-weight: 700;
      color: var(--text-1);
      margin-bottom: 6px;
    }

    .auth-subtitle {
      font-size: 1.5rem;
      color: var(--text-3);
      margin-bottom: 36px;
    }

    .form-group {
      margin-bottom: 20px;
    }

    .form-group label {
      display: block;
      font-size: 1.4rem;
      font-weight: 500;
      color: var(--text-2);
      margin-bottom: 8px;
    }

    .input-wrap {
      position: relative;
    }

    .input-icon {
      position: absolute;
      left: 16px;
      top: 50%;
      transform: translateY(-50%);
      font-size: 18px;
      color: var(--text-4);
      pointer-events: none;
    }

    .form-group input {
      width: 100%;
      padding: 14px 16px 14px 46px;
      background: var(--bg-3);
      border: 1.5px solid var(--border-2);
      border-radius: 12px;
      color: var(--text-1);
      font-size: 1.5rem;
      font-family: 'Outfit', sans-serif;
      transition: border-color 0.2s, box-shadow 0.2s;
    }

    .form-group input:focus {
      outline: none;
      border-color: var(--primary);
      box-shadow: 0 0 0 4px var(--primary-glow);
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

    .submit-btn:active { transform: translateY(0); }

    .error-msg {
      background: hsla(0, 85%, 55%, 0.12);
      border: 1px solid hsla(0, 85%, 55%, 0.3);
      color: hsl(0, 85%, 65%);
      border-radius: 10px;
      padding: 12px 16px;
      font-size: 1.4rem;
      margin-bottom: 20px;
      display: flex;
      align-items: center;
      gap: 8px;
    }

    .auth-footer {
      text-align: center;
      margin-top: 24px;
    }

    .auth-footer p {
      font-size: 1.4rem;
      color: var(--text-3);
    }

    .auth-footer a {
      color: var(--primary);
      text-decoration: none;
      font-weight: 600;
      transition: opacity 0.2s;
    }

    .auth-footer a:hover { opacity: 0.8; }

    .divider {
      display: flex;
      align-items: center;
      gap: 12px;
      margin: 24px 0;
    }

    .divider::before, .divider::after {
      content: '';
      flex: 1;
      height: 1px;
      background: var(--border-1);
    }

    .divider span {
      font-size: 1.3rem;
      color: var(--text-4);
    }
  </style>
</head>
<body>
  <div class="auth-wrapper">
    <div class="logo">
      <a href="../index.php">GetFit<span>.</span></a>
    </div>

    <div class="auth-card">
      <h2 class="auth-title">Welcome back</h2>
      <p class="auth-subtitle">Sign in to continue your fitness journey</p>

      <?php if ($error): ?>
        <div class="error-msg">
          ⚠️ <?= htmlspecialchars($error) ?>
        </div>
      <?php endif; ?>

      <form method="POST" action="">
        <div class="form-group">
          <label for="email">Email Address</label>
          <div class="input-wrap">
            <span class="input-icon">✉️</span>
            <input type="email" name="email" id="email" placeholder="you@example.com" required autocomplete="email">
          </div>
        </div>

        <div class="form-group">
          <label for="password">Password</label>
          <div class="input-wrap">
            <span class="input-icon">🔒</span>
            <input type="password" name="password" id="password" placeholder="Enter your password" required autocomplete="current-password">
          </div>
        </div>

        <button type="submit" class="submit-btn" id="signin-submit-btn">Sign In →</button>
      </form>

      <div class="divider"><span>Don't have an account?</span></div>

      <div class="auth-footer">
        <p>New to GetFit? <a href="signup.php" id="goto-signup">Create a free account</a></p>
      </div>
    </div>
  </div>
</body>
</html>
