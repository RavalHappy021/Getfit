<?php
require_once '../config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $content = $_POST['content'];

    try {
        $result = $db->content->insertOne([
            'content' => $content,
            'created_at' => new MongoDB\BSON\UTCDateTime()
        ]);

        if ($result->getInsertedCount() === 1) {
            echo "Content saved successfully.";
        } else {
            echo "Error: Could not save content.";
        }
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>
