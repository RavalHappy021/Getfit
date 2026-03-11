<?php
include('db.php');

try {
    if (isset($_GET['id'])) {
        $user_id = new MongoDB\BSON\ObjectId($_GET['id']);

        // Step 1: Delete related documents from dependent collections
        $collections_to_clean = ['progress', 'goals', 'diet_plans', 'workout_plans'];

        foreach ($collections_to_clean as $collection) {
            $db->$collection->deleteMany(['user_id' => $user_id]);
        }

        // Step 2: Now delete the user
        $result = $db->users->deleteOne(['_id' => $user_id]);

        if ($result->getDeletedCount() === 1) {
            header("Location: manage-users.php");
            exit();
        } else {
            echo "Error: Could not delete user.";
        }
    } else {
        echo "No user ID provided.";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>
