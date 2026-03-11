<?php
include('db.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $content = mysqli_real_escape_string($conn, $_POST['content']);

    $sql = "INSERT INTO content (content) VALUES ('$content')";

    if (mysqli_query($conn, $sql)) {
        echo "Content saved successfully.";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>
