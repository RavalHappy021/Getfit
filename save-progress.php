<?php
session_start();
require_once __DIR__ . '/config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $date = $_POST['date'] ?? date('Y-m-d');
    $weight = (float)($_POST['weight'] ?? 0);
    $waist = (float)($_POST['waist'] ?? 0);
    $chest = (float)($_POST['chest'] ?? 0);

    try {
        $user = $db->users->findOne(['email' => $email]);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = (string)$user['_id'];
            $_SESSION['username'] = $user['username'];

            // Log the progress
            $db->progress->insertOne([
                'user_id' => $user['_id'],
                'date' => $date,
                'weight' => $weight,
                'waist' => $waist,
                'chest' => $chest,
                'created_at' => new MongoDB\BSON\UTCDateTime()
            ]);

            header("Location: user-dashboard.php");
            exit();
        } else {
            echo "Invalid credentials.";
        }
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>


<form action="save-progress.php" method="POST">
    <label>Date:</label>
    <input type="date" name="date" required><br>

    <label>Weight (kg):</label>
    <input type="number" step="0.1" name="weight" required><br>

    <label>Waist (in):</label>
    <input type="number" step="0.1" name="waist" required><br>

    <label>Chest (in):</label>
    <input type="number" step="0.1" name="chest" required><br>

    <input type="submit" value="Save Progress">
</form>
