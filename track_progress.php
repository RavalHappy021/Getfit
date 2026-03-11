<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user = $_POST["user"];
    $weight = $_POST["weight"];
    $body_fat = $_POST["body_fat"];
    $date_logged = date('Y-m-d');

    $stmt = $conn->prepare("INSERT INTO progress_tracker (user, weight, body_fat, date_logged) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sdds", $user, $weight, $body_fat, $date_logged);

    if ($stmt->execute()) {
        echo "Progress recorded!";
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>
