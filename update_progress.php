<?php
session_start();
require_once 'config.php';

$userId = $_SESSION['user_id'] ?? null;
if (!$userId) {
    header("Location: assets/signin.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_progress'])) {
    $progress_date = $_POST['progress_date'];
    $weight = (float)$_POST['weight'];
    $waist = (float)$_POST['waist'];
    $chest = (float)$_POST['chest'];

    try {
        $result = $db->progress->insertOne([
            'user_id' => new MongoDB\BSON\ObjectId($userId),
            'date' => $progress_date,
            'weight' => $weight,
            'waist' => $waist,
            'chest' => $chest,
            'created_at' => new MongoDB\BSON\UTCDateTime()
        ]);

        header("Location: user-dashboard.php");
        exit;
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Update Progress</title>
</head>
<body>
    <h2>Update Your Recent Progress</h2>
    <form method="POST" action="">
        <p><label>Date:</label><br><input type="date" name="progress_date" required></p>
        <p><label>Weight (kg):</label><br><input type="number" step="0.1" name="weight" required></p>
        <p><label>Waist (inches):</label><br><input type="number" step="0.1" name="waist" required></p>
        <p><label>Chest (inches):</label><br><input type="number" step="0.1" name="chest" required></p>
        <input type="submit" name="add_progress" value="Save Progress"
               style="background-color:#28a745; color:white; padding:8px 15px; border:none; border-radius:5px; cursor:pointer;">
    </form>
</body>
</html>
