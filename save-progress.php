<?php
session_start();
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT id, password FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();

        // Password verify (if hashed)
        if (password_verify($password, $row['password'])) {
            $_SESSION['user_id'] = $row['id']; // 🔴 Yeh line honi chahiye
            header("Location: user-dashboard.php");
            exit();
        } else {
            echo "Incorrect password.";
        }
    } else {
        echo "User not found.";
    }
}
?>


<form action="save-progress.php" method="POST">
    <label>Date:</label>
    <input type="date" name="date" required><br>

    <label>Weight (kg):</label>
    <input type="number" step="0.1" name="weight" required><br>

    <label>Waist (in):</label>
    <input type="number" step="0.1" name="waist" required><br>

    <label>Chest (in):</label>
    <input type="number" step="0.1" name="chest" required><br>

    <input type="submit" value="Save Progress">
</form>
