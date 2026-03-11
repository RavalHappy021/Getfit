# GetFit - Fitness & Nutrition Management System

GetFit is a modern, premium web application designed to help users track their fitness goals, manage workout and diet plans, and monitor their physical progress. It features a sleek dark-themed UI with glassmorphism elements and a comprehensive admin panel for user and content management.

## 🚀 Key Features

### User Features
- **Personalized Dashboard**: View your current stats, goals, and daily plans at a glance.
- **Goal Tracking**: Add, edit, and track your fitness milestones.
- **Progress Logs**: Record weight and body measurements regularly to see your transformation.
- **Workout & Diet Plans**: Access structured plans to stay on track with your fitness journey.
- **AI Chatbot**: Get quick answers and assistance through the integrated chatbot.

### Admin Features
- **User Management**: Monitor registered users and manage accounts.
- **Platform Analytics**: View statistics on total users, goals set, and progress logs recorded.
- **Content Management**: Update website content directly from the admin dashboard.
- **Contact Support**: View and respond to user inquiries submitted via the contact form.

## 🛠️ Tech Stack
- **Frontend**: HTML5, Vanilla CSS (Modern UI/UX), JavaScript
- **Backend**: PHP (7.4+)
- **Database**: MySQL (MariaDB)
- **Icons**: Ionicons

## 📦 Local Setup Instructions

### Prerequisites
- [XAMPP](https://www.apachefriends.org/index.html) installed.
- MySQL and Apache services running.

### Installation
1.  **Clone the Repository**:
    ```bash
    git clone https://github.com/RavalHappy021/Getfit.git
    ```
2.  **Move to htdocs**: Place the project folder in your `C:\xampp\htdocs\` directory.
3.  **Database Setup**:
    - Open **phpMyAdmin** ([http://localhost/phpmyadmin/](http://localhost/phpmyadmin/)).
    - Create a new database named `getfit_db`.
    - Import the `setup_database.sql` file provided in the repository.
4.  **Database Configuration**:
    - Ensure your database credentials in `admin/db.php` match your local XAMPP setup (default: user `root`, no password).

### Accessing the App
- **User Frontend**: [http://localhost/getfit/index.php](http://localhost/getfit/index.php)
- **Admin Portal**: [http://localhost/getfit/admin/admin-login.php](http://localhost/getfit/admin/admin-login.php)

## 🔒 Security Note
This project uses `password_hash()` and `password_verify()` for secure user authentication. Database configuration files are excluded from version control via `.gitignore` to protect sensitive information.

---
*Created by [RavalHappy021](https://github.com/RavalHappy021)*
