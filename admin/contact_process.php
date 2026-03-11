<?php
$conn = new mysqli("localhost", "root", "", "getfit_db");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $subject = trim($_POST['subject']);
    $message = trim($_POST['message']);

    // Validate fields
    if (!empty($name) && !empty($email) && !empty($subject) && !empty($message)) {
        $stmt = $conn->prepare("INSERT INTO contact_messages (name, email, subject, message) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $name, $email, $subject, $message);
        $stmt->execute();
        $stmt->close();
        echo "Message sent successfully!";
    } else {
        echo "Please fill in all fields!";
    }
}
$conn->close();
?>
