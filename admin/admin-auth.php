<?php
session_start();
include('db.php'); // Now includes main config.php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    try {
        // Check in admins table (collection)
        $admin = $db->admins->findOne([
            'username' => $username,
            'password' => $password // Note: In a real app, this should be hashed.
        ]);

        if ($admin) {
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_username'] = $username;
            header("Location: admin-dashboard.php");
            exit();
        } else {
            echo "<script>alert('Invalid Credentials!'); window.location.href='admin-login.php';</script>";
        }
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>
