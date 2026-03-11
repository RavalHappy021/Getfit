<?php
$conn = new mysqli("localhost", "root", "", "getfit_db");

$email = $_POST['email'];
$sql = "INSERT INTO newsletter (email) VALUES ('$email')";

if ($conn->query($sql) === TRUE) {
    echo "Subscribed successfully!";
} else {
    echo "Error: " . $conn->error;
}

$conn->close();
?>
