<?php
session_start();
$loggedIn = isset($_SESSION['username']);
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>GetFit - Fitness & Nutrition</title>
  <meta name="description" content="GetFit - Your personal fitness and nutrition companion. Expert plans, AI chatbot, progress tracking.">

  <link rel="shortcut icon" href="./favicon.svg" type="image/svg+xml">
  <link rel="stylesheet" href="./assets/css/style.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

  <style>
    /* ===== FEATURES SECTION ===== */
    .features {
      background: var(--bg-1);
      padding-block: var(--section-padding);
    }

    .features-grid {
      display: grid;
      gap: 24px;
      grid-template-columns: 1fr;
      margin-top: 56px;
    }

    .feature-card {
      background: var(--bg-2);
      border: 1px solid var(--border-1);
      border-radius: var(--radius-lg);
      padding: 36px 28px;
      text-align: center;
      transition: all 0.3s ease;
      position: relative;
      overflow: hidden;
    }

    .feature-card::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      height: 3px;
      background: linear-gradient(90deg, var(--primary), var(--primary-light));
      opacity: 0;
      transition: opacity 0.3s ease;
    }

    .feature-card:hover {
      border-color: var(--border-3);
      transform: translateY(-6px);
      box-shadow: 0 20px 60px rgba(0,0,0,0.35);
    }

    .feature-card:hover::before { opacity: 1; }

    .feature-icon {
      width: 72px;
      height: 72px;
      background: linear-gradient(135deg, hsla(24,100%,50%,0.15), hsla(24,100%,50%,0.05));
      border: 1px solid hsla(24,100%,50%,0.25);
      border-radius: 20px;
      display: grid;
      place-items: center;
      margin: 0 auto 24px;
      font-size: 32px;
      transition: all 0.3s ease;
    }

    .feature-card:hover .feature-icon {
      background: linear-gradient(135deg, var(--primary), var(--primary-dark));
      border-color: var(--primary);
      transform: scale(1.1);
    }

    .feature-title {
      font-size: 1.8rem;
      font-weight: 700;
      color: var(--text-1);
      margin-bottom: 10px;
    }

    .feature-text {
      font-size: 1.4rem;
      color: var(--text-3);
      line-height: 1.7;
    }

    /* ===== STATS SECTION ===== */
    .stats {
      background: var(--bg-2);
      padding-block: var(--section-padding);
      border-top: 1px solid var(--border-1);
      border-bottom: 1px solid var(--border-1);
    }

    .stats-grid {
      display: grid;
      grid-template-columns: repeat(2, 1fr);
      gap: 30px;
    }

    .stat-item {
      text-align: center;
      padding: 20px;
    }

    .stat-number {
      font-size: 4rem;
      font-weight: 800;
      background: linear-gradient(135deg, var(--primary), var(--primary-light));
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
      line-height: 1;
      margin-bottom: 8px;
      font-family: var(--ff-outfit);
    }

    .stat-label {
      font-size: 1.4rem;
      color: var(--text-3);
      font-weight: 500;
    }

    /* ===== NEWSLETTER SECTION (standalone) ===== */
    .newsletter-section {
      background: var(--bg-1);
      padding-block: var(--section-padding);
    }

    .newsletter-card {
      background: linear-gradient(135deg, hsla(24,100%,50%,0.12), hsla(165,77%,35%,0.08));
      border: 1px solid hsla(24,100%,50%,0.2);
      border-radius: var(--radius-xl);
      padding: 60px 40px;
      text-align: center;
      position: relative;
      overflow: hidden;
    }

    .newsletter-card::before {
      content: '';
      position: absolute;
      top: -100px;
      right: -100px;
      width: 300px;
      height: 300px;
      background: radial-gradient(circle, hsla(24,100%,50%,0.15), transparent);
      pointer-events: none;
    }

    .newsletter-card h2 {
      font-size: 3.2rem;
      font-weight: 800;
      color: var(--text-1);
      margin-bottom: 12px;
    }

    .newsletter-card p {
      color: var(--text-2);
      font-size: 1.6rem;
      margin-bottom: 36px;
      max-width: 500px;
      margin-inline: auto;
    }

    .newsletter-form {
      display: flex;
      gap: 12px;
      max-width: 500px;
      margin-inline: auto;
      flex-wrap: wrap;
    }

    .newsletter-form input[type="email"] {
      flex: 1;
      min-width: 220px;
      padding: 15px 22px;
      background: var(--bg-2);
      border: 1px solid var(--border-2);
      border-radius: var(--radius-full);
      color: var(--text-1);
      font-size: 1.5rem;
      font-family: var(--ff-outfit);
      transition: border-color 0.2s;
    }

    .newsletter-form input:focus {
      outline: none;
      border-color: var(--primary);
    }

    .newsletter-form input::placeholder { color: var(--text-4); }

    .newsletter-form button {
      padding: 15px 32px;
      background: linear-gradient(135deg, var(--primary), var(--primary-dark));
      color: white;
      border-radius: var(--radius-full);
      font-size: 1.5rem;
      font-weight: 600;
      font-family: var(--ff-outfit);
      box-shadow: var(--shadow-primary);
      transition: all 0.25s ease;
      width: auto;
      white-space: nowrap;
    }

    .newsletter-form button:hover {
      transform: translateY(-2px);
      box-shadow: var(--shadow-primary-lg);
    }

    /* ===== CHATBOT SECTION ===== */
    .chatbot-section {
      background: var(--bg-2);
      padding-block: var(--section-padding);
    }

    /* ===== FOOTER SOCIAL ===== */
    .footer-social-wrapper {
      display: flex;
      align-items: center;
      gap: 14px;
      flex-wrap: wrap;
      margin-top: 24px;
    }

    .footer-social-title {
      font-size: 1.4rem;
      color: var(--text-3);
      font-weight: 600;
    }

    .footer-social-list {
      display: flex;
      gap: 10px;
    }

    @media (min-width: 768px) {
      .features-grid { grid-template-columns: repeat(3, 1fr); }
      .stats-grid { grid-template-columns: repeat(4, 1fr); }
    }
  </style>
</head>

<body id="top">

  <!-- HEADER -->
  <header class="header" data-header>
    <div class="container">
      <a href="./index.php" class="logo">
        GetFit<span class="span">.</span>
      </a>

      <nav class="navbar" data-navbar>
        <button class="nav-toggle-btn" aria-label="close menu" data-nav-toggler>
          <ion-icon name="close-outline" aria-hidden="true"></ion-icon>
        </button>

        <ul class="navbar-list">
          <li class="navbar-item">
            <a href="./index.php" class="navbar-link" data-nav-link>Home</a>
          </li>
          <li class="navbar-item">
            <a href="about.html" class="navbar-link" data-nav-link>About</a>
          </li>
          <li class="navbar-item">
            <a href="course.html" class="navbar-link" data-nav-link>Courses</a>
          </li>
          <li class="navbar-item">
            <a href="blog.html" class="navbar-link" data-nav-link>Blog</a>
          </li>
          <li class="navbar-item">
            <a href="./admin/contact.php" class="navbar-link" data-nav-link>Contact</a>
          </li>
        </ul>
      </nav>

      <div class="header-actions">
        <?php if ($loggedIn): ?>
          <a href="./user-dashboard.php" class="btn btn-secondary" id="dashboard-btn">Dashboard</a>
          <a href="./assets/user-logout.php" class="btn btn-primary" id="logout-btn">Logout</a>
        <?php else: ?>
          <a href="./assets/signin.php" class="btn btn-secondary" id="signin-btn">Sign In</a>
          <a href="./assets/signup.php" class="btn btn-primary" id="signup-btn">Get Started</a>
        <?php endif; ?>
      </div>

      <button class="nav-toggle-btn" aria-label="open menu" data-nav-toggler>
        <ion-icon name="menu-outline" aria-hidden="true"></ion-icon>
      </button>

      <div class="overlay" data-nav-toggler data-overlay></div>
    </div>
  </header>


  <main>
    <article>

      <!-- HERO -->
      <section class="section hero" aria-label="hero" id="home" data-section
        style="background-image: url('./assets/images/hero-banner.jpg')">
        <div class="container">
          <p class="hero-subtitle">Fitness &amp; Nutrition</p>

          <h1 class="h1 hero-title">
            Your journey to a <span class="gradient-text">stronger body</span> starts here
          </h1>

          <p class="hero-text">
            Expert-designed workout plans, personalized diet programs, and AI-powered guidance — everything you need to transform your life.
          </p>

          <div class="hero-actions">
            <a href="./assets/signup.php" class="btn btn-primary" id="hero-start-btn">
              <ion-icon name="rocket-outline" aria-hidden="true"></ion-icon>
              Start for Free
            </a>
            <a href="course.html" class="btn btn-secondary" id="hero-courses-btn">
              Explore Courses
            </a>
          </div>

          <!-- Social Links -->
          <div class="social-wrapper">
            <p class="social-title">Follow us:</p>
            <ul class="social-list">
              <li>
                <a href="#" class="social-link" aria-label="Facebook">
                  <ion-icon name="logo-facebook"></ion-icon>
                </a>
              </li>
              <li>
                <a href="#" class="social-link" aria-label="Twitter">
                  <ion-icon name="logo-twitter"></ion-icon>
                </a>
              </li>
              <li>
                <a href="#" class="social-link" aria-label="LinkedIn">
                  <ion-icon name="logo-linkedin"></ion-icon>
                </a>
              </li>
              <li>
                <a href="#" class="social-link" aria-label="Instagram">
                  <ion-icon name="logo-instagram"></ion-icon>
                </a>
              </li>
            </ul>
          </div>
        </div>
      </section>

      <!-- STATS -->
      <section class="stats" aria-label="statistics">
        <div class="container">
          <div class="stats-grid">
            <div class="stat-item">
              <div class="stat-number">10K+</div>
              <div class="stat-label">Active Members</div>
            </div>
            <div class="stat-item">
              <div class="stat-number">500+</div>
              <div class="stat-label">Workout Plans</div>
            </div>
            <div class="stat-item">
              <div class="stat-number">200+</div>
              <div class="stat-label">Diet Programs</div>
            </div>
            <div class="stat-item">
              <div class="stat-number">98%</div>
              <div class="stat-label">Satisfaction Rate</div>
            </div>
          </div>
        </div>
      </section>

      <!-- FEATURES -->
      <section class="features section" aria-label="features" data-section>
        <div class="container">
          <p class="section-subtitle">Why GetFit?</p>
          <h2 class="h2 section-title">Everything you need to succeed</h2>
          <p class="section-text">A complete fitness ecosystem designed to help you reach your goals faster and smarter.</p>

          <div class="features-grid">
            <div class="feature-card">
              <div class="feature-icon">🏋️</div>
              <h3 class="feature-title">Workout Plans</h3>
              <p class="feature-text">Customized workout programs designed by experts, tailored to your fitness level and goals.</p>
            </div>
            <div class="feature-card">
              <div class="feature-icon">🥗</div>
              <h3 class="feature-title">Diet & Nutrition</h3>
              <p class="feature-text">Personalized meal plans and nutrition guidance to fuel your body and support your fitness journey.</p>
            </div>
            <div class="feature-card">
              <div class="feature-icon">🤖</div>
              <h3 class="feature-title">AI Assistant</h3>
              <p class="feature-text">Get instant answers to your fitness and nutrition questions with our intelligent AI chatbot.</p>
            </div>
            <div class="feature-card">
              <div class="feature-icon">📊</div>
              <h3 class="feature-title">Progress Tracking</h3>
              <p class="feature-text">Monitor your weight, measurements, and workout progress to stay motivated and on track.</p>
            </div>
            <div class="feature-card">
              <div class="feature-icon">🎯</div>
              <h3 class="feature-title">Goal Setting</h3>
              <p class="feature-text">Set meaningful fitness goals and receive timely reminders and milestone celebrations.</p>
            </div>
            <div class="feature-card">
              <div class="feature-icon">👥</div>
              <h3 class="feature-title">Community</h3>
              <p class="feature-text">Join a supportive community of fitness enthusiasts and stay motivated together.</p>
            </div>
          </div>
        </div>
      </section>

      <!-- WORKOUT & DIET PLANS CTA -->
      <section class="newsletter-section section" aria-label="plans" id="plans" data-section>
        <div class="container">
          <div class="newsletter-card">
            <p class="section-subtitle" style="justify-content: center;">Get Started Today</p>
            <h2>Workout &amp; Diet Plans</h2>
            <p>Get personalized workout and diet plans based on your goals. Join thousands of members transforming their lives.</p>
            <div style="display:flex; gap:16px; justify-content:center; flex-wrap:wrap;">
              <a href="./assets/signup.php" class="btn btn-primary" id="plans-join-btn">
                <ion-icon name="fitness-outline" aria-hidden="true"></ion-icon>
                Start Your Journey
              </a>
              <a href="course.html" class="btn btn-secondary" id="plans-courses-btn">View All Courses</a>
            </div>
          </div>
        </div>
      </section>

      <!-- NEWSLETTER -->
      <section class="newsletter-section section" aria-label="newsletter" id="newsletter" data-section style="background: var(--bg-2); padding-block: 60px;">
        <div class="container">
          <div class="newsletter-card">
            <p class="section-subtitle" style="justify-content: center;">Stay Updated</p>
            <h2>Subscribe to Our Newsletter</h2>
            <p>Get the latest fitness tips, nutrition advice, and exclusive content delivered straight to your inbox.</p>
            <form class="newsletter-form" id="subscribe-form">
              <input type="email" id="email" name="email" placeholder="Enter your email address" required>
              <button type="submit" id="subscribe-btn">Subscribe</button>
            </form>
          </div>
        </div>
      </section>

      <!-- AI CHATBOT -->
      <section class="chatbot-section section" aria-label="ai chatbot" data-section>
        <div class="container">
          <p class="section-subtitle">AI Powered</p>
          <h2 class="h2 section-title">Ask Your Fitness AI</h2>
          <p class="section-text">Get instant, expert answers to your fitness and nutrition questions anytime.</p>

          <div id="chatbot-container">
            <div class="chatbot-header">
              <i>🤖</i>
              <div>
                <div class="chatbot-title">GetFit AI Assistant</div>
                <div class="chatbot-subtitle">Your 24/7 fitness & nutrition guide</div>
              </div>
            </div>

            <div id="chat-area">
              <div id="messages">
                <div style="color: var(--text-3); font-style: italic;">Hi! I'm your GetFit AI assistant. Ask me anything about workouts, nutrition, or fitness goals! 💪</div>
              </div>
            </div>

            <input type="text" id="userInput" placeholder="Ask about workouts, diet, fitness tips..." />
            <button onclick="sendMessage()" class="btn btn-primary" id="chatbot-send-btn" style="width: 100%; justify-content: center;">
              <ion-icon name="send-outline" aria-hidden="true"></ion-icon>
              <span>Send Message</span>
            </button>
          </div>
        </div>
      </section>

    </article>
  </main>


  <!-- FOOTER -->
  <footer class="footer">
    <div class="footer-bottom">
      <div class="container">
        <p class="copyright">
          &copy; 2025 GetFit &mdash; Made with ❤️ by
          <a href="#" class="copyright-link">Happy Raval</a>
        </p>

        <ul class="footer-bottom-list">
          <li class="footer-bottom-item">
            <a href="#" class="footer-bottom-link">Terms of Service</a>
          </li>
          <li class="footer-bottom-item">
            <a href="#" class="footer-bottom-link">Privacy Policy</a>
          </li>
          <li class="footer-bottom-item">
            <a href="#" class="footer-bottom-link">Security</a>
          </li>
        </ul>
      </div>
    </div>
  </footer>

  <!-- BACK TO TOP -->
  <a href="#top" class="back-top-btn" aria-label="back to top" data-back-top-btn>
    <ion-icon name="chevron-up" aria-hidden="true"></ion-icon>
  </a>

  <script src="./assets/js/script.js" defer></script>

  <script>
    // Subscribe form
    const subscribeForm = document.getElementById("subscribe-form");
    if (subscribeForm) {
      subscribeForm.addEventListener("submit", function(event) {
        event.preventDefault();
        let formData = new FormData(this);
        fetch('subscribe.php', { method: 'POST', body: formData })
          .then(response => response.text())
          .then(data => {
            alert(data);
            this.reset();
          });
      });
    }

    // Chatbot
    function sendMessage() {
      const userInput = document.getElementById('userInput');
      const messages = document.getElementById('messages');
      const text = userInput.value.trim();

      if (!text) return;

      messages.innerHTML += `<div style="background:var(--bg-4);padding:10px 14px;border-radius:10px;margin-bottom:8px;"><strong style="color:var(--primary)">You:</strong> <span style="color:var(--text-2)">${text}</span></div>`;

      fetch('chatbot-response.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'message=' + encodeURIComponent(text)
      })
      .then(response => response.text())
      .then(data => {
        messages.innerHTML += `<div style="background:linear-gradient(135deg,hsla(24,100%,50%,0.1),hsla(24,100%,50%,0.05));border:1px solid hsla(24,100%,50%,0.15);padding:10px 14px;border-radius:10px;margin-bottom:8px;"><strong style="color:var(--accent)">AI:</strong> <span style="color:var(--text-2)">${data}</span></div>`;
        userInput.value = '';
        const chatArea = document.getElementById('chat-area');
        chatArea.scrollTop = chatArea.scrollHeight;
      });
    }

    document.getElementById("userInput").addEventListener("keypress", function(event) {
      if (event.key === "Enter") {
        event.preventDefault();
        sendMessage();
      }
    });
  </script>

  <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
  <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>

</body>
</html>
