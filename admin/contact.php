<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name    = isset($_POST['txtName'])    ? htmlspecialchars($_POST['txtName'])    : '';
    $email   = isset($_POST['txtEmail'])   ? htmlspecialchars($_POST['txtEmail'])   : '';
    $subject = isset($_POST['txtsubject']) ? htmlspecialchars($_POST['txtsubject']) : '';
    $message = isset($_POST['txtMessage']) ? htmlspecialchars($_POST['txtMessage']) : '';

    include 'db.php';

    try {
        $result = $db->contact_messages->insertOne([
            'name' => $name,
            'email' => $email,
            'subject' => $subject,
            'message' => $message,
            'created_at' => new MongoDB\BSON\UTCDateTime()
        ]);

        if ($result->getInsertedCount() === 1) {
            $to      = $email;
            $headers = "From: no-reply@getfit.com";
            $body    = "Hello $name,\n\nThank you for contacting us. We received your message:\n\n$message\n\nWe'll get back to you soon.\n\n- GetFit Team";
            @mail($to, $subject, $body, $headers);

            echo "<script>alert('Message sent successfully! We\\'ll get back to you soon.'); window.location.href = '../index.php';</script>";
            exit();
        } else {
            echo "<script>alert('Error sending message. Please try again.');</script>";
        }
    } catch (Exception $e) {
        echo "<script>alert('Error: " . addslashes($e->getMessage()) . "');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Contact Us - GetFit</title>
  <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
  <style>
    :root {
      --primary: hsl(24, 100%, 50%);
      --primary-dark: hsl(18, 100%, 40%);
      --primary-glow: hsla(24, 100%, 50%, 0.3);
      --primary-light: hsl(30, 100%, 60%);
      --bg-1: hsl(220, 20%, 8%);
      --bg-2: hsl(220, 18%, 11%);
      --bg-3: hsl(220, 16%, 15%);
      --bg-4: hsl(220, 14%, 20%);
      --border-1: hsla(220, 20%, 100%, 0.07);
      --border-2: hsla(220, 20%, 100%, 0.13);
      --text-1: hsl(0, 0%, 98%);
      --text-2: hsl(220, 10%, 75%);
      --text-3: hsl(220, 8%, 55%);
      --text-4: hsl(220, 6%, 40%);
    }

    *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }
    html { font-size: 10px; scroll-behavior: smooth; }

    body {
      font-family: 'Outfit', sans-serif;
      background: var(--bg-1);
      color: var(--text-2);
      min-height: 100vh;
    }

    /* HEADER */
    .header {
      position: fixed;
      top: 0; left: 0; right: 0;
      padding: 16px 0;
      z-index: 100;
      background: hsla(220, 20%, 8%, 0.90);
      backdrop-filter: blur(20px);
      border-bottom: 1px solid var(--border-1);
    }

    .header-inner {
      max-width: 1100px;
      margin: 0 auto;
      padding: 0 24px;
      display: flex;
      align-items: center;
      justify-content: space-between;
    }

    .logo {
      font-size: 2.8rem;
      font-weight: 800;
      color: var(--text-1);
      text-decoration: none;
    }

    .logo span { color: var(--primary); }

    .header-nav { display: flex; gap: 8px; }

    .header-nav a {
      padding: 8px 18px;
      border-radius: 20px;
      font-size: 1.4rem;
      font-weight: 600;
      text-decoration: none;
      transition: all 0.2s;
    }

    .nav-home {
      color: var(--text-2);
      border: 1px solid var(--border-2);
    }

    .nav-home:hover { color: var(--primary); border-color: var(--primary); }

    /* HERO SECTION */
    .contact-hero {
      padding: 160px 24px 80px;
      text-align: center;
      position: relative;
      overflow: hidden;
    }

    .contact-hero::before {
      content: '';
      position: absolute;
      width: 600px; height: 600px;
      background: radial-gradient(circle, hsla(24,100%,50%,0.08), transparent);
      top: -200px; left: 50%; transform: translateX(-50%);
      pointer-events: none;
    }

    .contact-badge {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      background: hsla(24,100%,50%,0.12);
      border: 1px solid hsla(24,100%,50%,0.25);
      color: var(--primary);
      padding: 7px 20px;
      border-radius: 20px;
      font-size: 1.3rem;
      font-weight: 700;
      margin-bottom: 24px;
      text-transform: uppercase;
      letter-spacing: 2px;
    }

    .contact-title {
      font-size: clamp(3.2rem, 5vw, 5.5rem);
      font-weight: 800;
      color: var(--text-1);
      margin-bottom: 16px;
      line-height: 1.1;
    }

    .contact-subtitle {
      font-size: 1.7rem;
      color: var(--text-3);
      max-width: 550px;
      margin: 0 auto;
      line-height: 1.7;
    }

    /* CONTACT GRID */
    .contact-section {
      padding: 0 24px 80px;
    }

    .contact-grid {
      max-width: 1000px;
      margin: 0 auto;
      display: grid;
      grid-template-columns: 1fr 340px;
      gap: 32px;
    }

    /* FORM */
    .form-card {
      background: var(--bg-2);
      border: 1px solid var(--border-2);
      border-radius: 24px;
      padding: 44px 40px;
      position: relative;
      overflow: hidden;
    }

    .form-card::before {
      content: '';
      position: absolute;
      top: 0; left: 0; right: 0;
      height: 3px;
      background: linear-gradient(90deg, var(--primary), var(--primary-light));
    }

    .form-card h2 {
      font-size: 2.4rem;
      font-weight: 700;
      color: var(--text-1);
      margin-bottom: 28px;
    }

    .form-row {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 16px;
    }

    .form-group {
      margin-bottom: 20px;
    }

    .form-group label {
      display: block;
      font-size: 1.3rem;
      font-weight: 600;
      color: var(--text-2);
      margin-bottom: 8px;
    }

    .input-wrap { position: relative; }

    .input-icon {
      position: absolute;
      left: 14px;
      top: 50%;
      transform: translateY(-50%);
      font-size: 16px;
      pointer-events: none;
      color: var(--text-4);
    }

    .form-group input,
    .form-group textarea {
      width: 100%;
      padding: 13px 14px 13px 42px;
      background: var(--bg-3);
      border: 1.5px solid var(--border-2);
      border-radius: 12px;
      color: var(--text-1);
      font-size: 1.5rem;
      font-family: 'Outfit', sans-serif;
      transition: border-color 0.2s, box-shadow 0.2s;
    }

    .form-group textarea {
      padding-left: 16px;
      resize: vertical;
      min-height: 130px;
    }

    .form-group input:focus,
    .form-group textarea:focus {
      outline: none;
      border-color: var(--primary);
      box-shadow: 0 0 0 3px var(--primary-glow);
    }

    .form-group input::placeholder,
    .form-group textarea::placeholder { color: var(--text-4); }

    .submit-btn {
      width: 100%;
      padding: 16px;
      background: linear-gradient(135deg, var(--primary), var(--primary-dark));
      color: white;
      border: none;
      border-radius: 14px;
      font-size: 1.7rem;
      font-weight: 700;
      font-family: 'Outfit', sans-serif;
      cursor: pointer;
      box-shadow: 0 6px 24px var(--primary-glow);
      transition: all 0.25s;
    }

    .submit-btn:hover {
      transform: translateY(-2px);
      box-shadow: 0 10px 30px var(--primary-glow);
    }

    /* INFO SIDEBAR */
    .info-sidebar { display: flex; flex-direction: column; gap: 20px; }

    .info-card {
      background: var(--bg-2);
      border: 1px solid var(--border-1);
      border-radius: 20px;
      padding: 28px 24px;
      transition: all 0.25s;
    }

    .info-card:hover {
      border-color: var(--border-2);
      transform: translateY(-3px);
      box-shadow: 0 10px 30px rgba(0,0,0,0.3);
    }

    .info-icon {
      font-size: 36px;
      margin-bottom: 14px;
      display: block;
    }

    .info-title {
      font-size: 1.7rem;
      font-weight: 700;
      color: var(--text-1);
      margin-bottom: 8px;
    }

    .info-text {
      font-size: 1.4rem;
      color: var(--text-3);
      line-height: 1.6;
    }

    .social-links {
      display: flex;
      gap: 10px;
      margin-top: 12px;
      flex-wrap: wrap;
    }

    .social-btn {
      width: 40px;
      height: 40px;
      display: grid;
      place-items: center;
      background: var(--bg-4);
      border-radius: 10px;
      font-size: 18px;
      text-decoration: none;
      transition: all 0.2s;
      border: 1px solid var(--border-2);
    }

    .social-btn:hover {
      background: var(--primary);
      transform: translateY(-3px);
    }

    /* FOOTER */
    .footer {
      background: var(--bg-2);
      border-top: 1px solid var(--border-1);
      padding: 24px;
      text-align: center;
      font-size: 1.4rem;
      color: var(--text-3);
    }

    .footer a { color: var(--primary); text-decoration: none; }

    @media (max-width: 768px) {
      .contact-grid { grid-template-columns: 1fr; }
      .form-row { grid-template-columns: 1fr; }
      .form-card { padding: 30px 24px; }
    }
  </style>
</head>
<body>

  <!-- HEADER -->
  <header class="header">
    <div class="header-inner">
      <a href="../index.php" class="logo">GetFit<span>.</span></a>
      <div class="header-nav">
        <a href="../index.php" class="nav-home" id="contact-home-link">← Home</a>
      </div>
    </div>
  </header>

  <!-- HERO -->
  <section class="contact-hero">
    <div class="contact-badge">✉️ Get In Touch</div>
    <h1 class="contact-title">Contact Us</h1>
    <p class="contact-subtitle">We'd love to hear from you! Send us a message and we'll respond as soon as possible.</p>
  </section>

  <!-- CONTACT SECTION -->
  <section class="contact-section">
    <div class="contact-grid">

      <!-- FORM -->
      <div class="form-card">
        <h2>Send a Message</h2>
        <form name="frmContact" method="post" action="contact.php" id="contact-form">

          <div class="form-row">
            <div class="form-group">
              <label for="txtName">Your Name</label>
              <div class="input-wrap">
                <span class="input-icon">👤</span>
                <input type="text" name="txtName" id="txtName" placeholder="John Doe" required>
              </div>
            </div>
            <div class="form-group">
              <label for="txtEmail">Email Address</label>
              <div class="input-wrap">
                <span class="input-icon">✉️</span>
                <input type="text" name="txtEmail" id="txtEmail" placeholder="you@example.com" required>
              </div>
            </div>
          </div>

          <div class="form-group">
            <label for="txtsubject">Subject</label>
            <div class="input-wrap">
              <span class="input-icon">📌</span>
              <input type="text" name="txtsubject" id="txtsubject" placeholder="How can we help?" required>
            </div>
          </div>

          <div class="form-group">
            <label for="txtMessage">Message</label>
            <textarea name="txtMessage" id="txtMessage" placeholder="Tell us about your question or feedback..." required></textarea>
          </div>

          <button type="submit" name="Submit" id="contact-submit-btn" class="submit-btn">
            🚀 Send Message
          </button>
        </form>
      </div>

      <!-- INFO SIDEBAR -->
      <div class="info-sidebar">
        <div class="info-card">
          <span class="info-icon">📍</span>
          <div class="info-title">Location</div>
          <p class="info-text">Gujarat, India<br>Available online worldwide</p>
        </div>

        <div class="info-card">
          <span class="info-icon">⏰</span>
          <div class="info-title">Response Time</div>
          <p class="info-text">We typically respond within 24 hours on weekdays.</p>
        </div>

        <div class="info-card">
          <span class="info-icon">🌐</span>
          <div class="info-title">Follow Us</div>
          <p class="info-text">Stay connected on social media</p>
          <div class="social-links">
            <a href="#" class="social-btn" aria-label="Facebook">📘</a>
            <a href="#" class="social-btn" aria-label="Twitter">🐦</a>
            <a href="#" class="social-btn" aria-label="Instagram">📸</a>
            <a href="#" class="social-btn" aria-label="LinkedIn">💼</a>
          </div>
        </div>

        <div class="info-card">
          <span class="info-icon">💪</span>
          <div class="info-title">GetFit Team</div>
          <p class="info-text">Your fitness journey is our mission. We're here to help every step of the way.</p>
        </div>
      </div>

    </div>
  </section>

  <!-- FOOTER -->
  <footer class="footer">
    <p>&copy; 2025 GetFit &mdash; Made with ❤️ by <a href="#">Happy Raval</a></p>
  </footer>

</body>
</html>
