<?php
session_start();
include 'db.php'; // adjust as needed to include your DB connection

// Assuming user is already logged in and user_id is in session
$userId = $_SESSION['user_id'] ?? null;
if (!$userId) {
    header("Location: update_progress.php"); // Redirect to login if not logged in
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_progress'])) {
    $progress_date = $_POST['progress_date'];
    $weight = $_POST['weight'];
    $waist = $_POST['waist'];
    $chest = $_POST['chest'];

    $conn = new mysqli("localhost", "root", "", "getfit"); // Or use your existing connection file

    $insert = $conn->prepare("INSERT INTO progress (user_id, date, weight, waist, chest) VALUES (?, ?, ?, ?, ?)");
    $insert->bind_param("isdss", $userId, $progress_date, $weight, $waist, $chest);
    $insert->execute();

    header("Location: dashboard.php"); // Redirect back to dashboard
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Update Progress</title>
</head>
<body>
    <h2>Update Your Recent Progress</h2>
    <form method="POST" action="">
        <p><label>Date:</label><br><input type="date" name="progress_date" required></p>
        <p><label>Weight (kg):</label><br><input type="number" step="0.1" name="weight" required></p>
        <p><label>Waist (inches):</label><br><input type="number" step="0.1" name="waist" required></p>
        <p><label>Chest (inches):</label><br><input type="number" step="0.1" name="chest" required></p>
        <input type="submit" name="add_progress" value="Save Progress"
               style="background-color:#28a745; color:white; padding:8px 15px; border:none; border-radius:5px; cursor:pointer;">
    </form>
</body>
</html>
