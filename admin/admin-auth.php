<?php
session_start();
include('db.php'); // Your database connection file

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // Check in admins table
    $query = "SELECT * FROM admins WHERE username = '$username' AND password = '$password'";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) === 1) {
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_username'] = $username;
        header("Location: admin-dashboard.php");
        exit();
    } else {
        echo "<script>alert('Invalid Credentials!'); window.location.href='admin-login.php';</script>";
    }
}
?>
