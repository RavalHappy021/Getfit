<?php
session_start();
require_once 'config.php';

$username = $_POST['username'];
$password = $_POST['password'];

try {
    $user = $db->users->findOne(['username' => $username]);

    if ($user) {
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = (string)$user['_id'];
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
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>
