<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $mealDiff = $_POST['mealDiff'];

    $sql = "INSERT INTO meal_difficulty (mealDiff) VALUES (?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $mealDiff);

    if ($stmt->execute()) {
        header("Location: manage_difficulty.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>
