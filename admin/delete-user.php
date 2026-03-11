<?php
include('db.php');

if (isset($_GET['id'])) {
    $user_id = $_GET['id'];

    // Step 1: Delete related rows from dependent tables
    $tables_to_clean = ['progress', 'goals', 'diet_plans']; // Add more if needed

    foreach ($tables_to_clean as $table) {
        $sql = "DELETE FROM $table WHERE user_id = $user_id";
        mysqli_query($conn, $sql);
    }

    // Step 2: Now delete the user
    $delete_user = "DELETE FROM users WHERE id = $user_id";
    if (mysqli_query($conn, $delete_user)) {
        header("Location: manage-users.php");
        exit();
    } else {
        echo "Error deleting user: " . mysqli_error($conn);
    }
} else {
    echo "No user ID provided.";
}
?>
