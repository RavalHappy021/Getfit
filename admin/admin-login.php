<?php
session_start();
if (isset($_SESSION['admin_logged_in'])) {
    header("Location: admin-dashboard.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Login - GetFit</title>
  <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
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
      width: 500px;
      height: 500px;
      background: radial-gradient(circle, hsla(24,100%,50%,0.10), transparent);
      top: -200px; right: -100px;
      border-radius: 50%;
      pointer-events: none;
      animation: float 8s ease-in-out infinite;
    }

    @keyframes float {
      0%, 100% { transform: translateY(0); }
      50% { transform: translateY(-30px); }
    }

    .auth-wrapper {
      width: 100%;
      max-width: 420px;
      position: relative;
      z-index: 1;
    }

    .logo {
      text-align: center;
      margin-bottom: 28px;
    }

    .logo-text {
      font-size: 3rem;
      font-weight: 800;
      color: var(--text-1);
    }

    .logo-text span { color: var(--primary); }

    .logo-subtitle {
      font-size: 1.3rem;
      color: var(--text-3);
      margin-top: 4px;
    }

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
      background: linear-gradient(90deg, var(--primary), hsl(30,100%,60%));
    }

    .admin-badge {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      background: hsla(24,100%,50%,0.12);
      border: 1px solid hsla(24,100%,50%,0.25);
      color: var(--primary);
      padding: 6px 16px;
      border-radius: 20px;
      font-size: 1.3rem;
      font-weight: 700;
      margin-bottom: 20px;
    }

    h2 {
      font-size: 2.4rem;
      font-weight: 700;
      color: var(--text-1);
      margin-bottom: 4px;
    }

    .subtitle {
      font-size: 1.4rem;
      color: var(--text-3);
      margin-bottom: 28px;
    }

    .form-group { margin-bottom: 18px; }

    label {
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
      pointer-events: none;
    }

    input {
      width: 100%;
      padding: 13px 14px 13px 42px;
      background: var(--bg-3);
      border: 1.5px solid var(--border-2);
      border-radius: 10px;
      color: var(--text-1);
      font-size: 1.5rem;
      font-family: 'Outfit', sans-serif;
      transition: border-color 0.2s, box-shadow 0.2s;
    }

    input:focus {
      outline: none;
      border-color: var(--primary);
      box-shadow: 0 0 0 3px var(--primary-glow);
    }

    input::placeholder { color: hsl(220,6%,40%); }

    .submit-btn {
      width: 100%;
      padding: 15px;
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
      box-shadow: 0 8px 30px var(--primary-glow);
    }

    .back-link {
      display: block;
      text-align: center;
      margin-top: 20px;
      font-size: 1.4rem;
      color: var(--text-3);
      text-decoration: none;
      transition: color 0.2s;
    }

    .back-link:hover { color: var(--primary); }
  </style>
</head>
<body>
  <div class="auth-wrapper">
    <div class="logo">
      <div class="logo-text">GetFit<span>.</span></div>
      <div class="logo-subtitle">Administration Portal</div>
    </div>

    <div class="auth-card">
      <div class="admin-badge">🔐 Admin Access</div>
      <h2>Admin Login</h2>
      <p class="subtitle">Restricted area — authorized personnel only</p>

      <form action="admin-auth.php" method="POST">
        <div class="form-group">
          <label for="username">Username</label>
          <div class="input-wrap">
            <span class="input-icon">👤</span>
            <input type="text" name="username" id="username" placeholder="admin" required autocomplete="username">
          </div>
        </div>
        <div class="form-group">
          <label for="password">Password</label>
          <div class="input-wrap">
            <span class="input-icon">🔒</span>
            <input type="password" name="password" id="password" placeholder="••••••••" required autocomplete="current-password">
          </div>
        </div>
        <button type="submit" class="submit-btn" id="admin-login-btn">Login to Dashboard →</button>
      </form>
    </div>

    <a href="../index.php" class="back-link">← Back to main site</a>
  </div>
</body>
</html>
