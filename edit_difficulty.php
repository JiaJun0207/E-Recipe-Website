<?php
include 'db.php';

if (isset($_GET['edit'])) {
    $diffID = $_GET['edit'];
    $query = "SELECT * FROM meal_difficulty WHERE diffID = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $diffID);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_assoc();
}

// Update difficulty level
if (isset($_POST['update'])) {
    $diffID = $_POST['diffID'];
    $mealDiff = $_POST['mealDiff'];

    $updateQuery = "UPDATE meal_difficulty SET mealDiff = ? WHERE diffID = ?";
    $stmt = $conn->prepare($updateQuery);
    $stmt->bind_param("si", $mealDiff, $diffID);

    if ($stmt->execute()) {
        header("Location: manage_difficulty.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Difficulty</title>
</head>
<body>
    <h2>Edit Difficulty Level</h2>
    <form action="edit_difficulty.php" method="post">
        <input type="hidden" name="diffID" value="<?= $data['diffID'] ?>">
        <input type="text" name="mealDiff" value="<?= $data['mealDiff'] ?>" required>
        <button type="submit" name="update">Update</button>
    </form>
</body>
</html>
