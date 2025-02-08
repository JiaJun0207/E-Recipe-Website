<?php
include 'db.php';

if (isset($_GET['edit'])) {
    $typeID = $_GET['edit'];
    $query = "SELECT * FROM meal_type WHERE typeID = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $typeID);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_assoc();
}

// Update meal type
if (isset($_POST['update'])) {
    $typeID = $_POST['typeID'];
    $mealType = $_POST['mealType'];

    $updateQuery = "UPDATE meal_type SET mealType = ? WHERE typeID = ?";
    $stmt = $conn->prepare($updateQuery);
    $stmt->bind_param("si", $mealType, $typeID);

    if ($stmt->execute()) {
        header("Location: manage_meal_type.php");
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
    <title>Edit Meal Type</title>
</head>
<body>
    <h2>Edit Meal Type</h2>
    <form action="edit_meal_type.php" method="post">
        <input type="hidden" name="typeID" value="<?= $data['typeID'] ?>">
        <input type="text" name="mealType" value="<?= $data['mealType'] ?>" required>
        <button type="submit" name="update">Update</button>
    </form>
</body>
</html>
