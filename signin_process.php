<?php
session_start();
$conn = new mysqli("localhost", "root", "", "getfit_db");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$username = $_POST['username'];
$password = $_POST['password'];

$sql = "SELECT * FROM users WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();

    if (password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];

        if ($user['role'] === 'admin') {
            header("Location: admin/admin-dashboard.php");
        } else {
            header("Location: user-dashboard.php");
        }
        exit();
    } else {
        echo "Invalid password.";
    }
} else {
    echo "No such user found.";
}

$conn->close();
?>
