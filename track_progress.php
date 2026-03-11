<?php
session_start();
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user = $_POST["user"];
    $weight = (float)$_POST["weight"];
    $body_fat = (float)$_POST["body_fat"];
    $date_logged = date('Y-m-d');

    try {
        $result = $db->progress->insertOne([
            'user' => $user, // Legacy field
            'user_id' => $_SESSION['user_id'] ? new MongoDB\BSON\ObjectId($_SESSION['user_id']) : null,
            'weight' => $weight,
            'body_fat' => $body_fat,
            'date' => $date_logged,
            'created_at' => new MongoDB\BSON\UTCDateTime()
        ]);

        if ($result->getInsertedCount() === 1) {
            echo "Progress recorded!";
        } else {
            echo "Error: Could not record progress.";
        }
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>
