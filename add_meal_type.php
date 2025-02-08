<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $mealType = $_POST['mealType'];

    $sql = "INSERT INTO meal_type (mealType) VALUES (?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $mealType);

    if ($stmt->execute()) {
        header("Location: manage_meal_type.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>
