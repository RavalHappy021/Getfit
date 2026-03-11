<?php
require_once '../config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $subject = trim($_POST['subject']);
    $message = trim($_POST['message']);

    // Validate fields
    if (!empty($name) && !empty($email) && !empty($subject) && !empty($message)) {
        try {
            $result = $db->contact_messages->insertOne([
                'name' => $name,
                'email' => $email,
                'subject' => $subject,
                'message' => $message,
                'created_at' => new MongoDB\BSON\UTCDateTime()
            ]);

            if ($result->getInsertedCount() === 1) {
                echo "Message sent successfully!";
            } else {
                echo "Error: Could not send message.";
            }
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    } else {
        echo "Please fill in all fields!";
    }
}
?>
