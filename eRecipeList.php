<?php
// Start the session
session_start();
include 'db.php';

// Initialize variables for user data
$userImg = 'uploads/default.png';
$userName = 'Guest';
$isLoggedIn = false;
if (isset($_SESSION['userID'])) {
    $isLoggedIn = true;
    $userID = $_SESSION['userID'];
    $query = "SELECT userImg, userName FROM registered_user WHERE userID = ?";
    $stmt = $conn->prepare($query);
    if ($stmt) {
        $stmt->bind_param("i", $userID);
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                $userData = $result->fetch_assoc();
                $userImg = $userData['userImg'];
                $userName = $userData['userName'];
            }
        }
        $stmt->close();
    }
}

// Fetch meal types
$typeQuery = "SELECT * FROM meal_type";
$typeResult = $conn->query($typeQuery);

// Fetch meal difficulties
$diffQuery = "SELECT * FROM meal_difficulty";
$diffResult = $conn->query($diffQuery);

// Fetch recipes based on filters
$whereClauses = ["r.recipeStatus = 'approved'"];
$params = [];
$paramTypes = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!empty($_POST['mealType'])) {
        $placeholders = implode(",", array_fill(0, count($_POST['mealType']), "?"));
        $whereClauses[] = "r.typeID IN ($placeholders)";
        $params = array_merge($params, $_POST['mealType']);
        $paramTypes .= str_repeat("i", count($_POST['mealType']));
    }
    if (!empty($_POST['mealDiff'])) {
        $placeholders = implode(",", array_fill(0, count($_POST['mealDiff']), "?"));
        $whereClauses[] = "r.diffID IN ($placeholders)";
        $params = array_merge($params, $_POST['mealDiff']);
        $paramTypes .= str_repeat("i", count($_POST['mealDiff']));
    }
}
$query = "SELECT r.recipeID, r.recipeImg, r.recipeName, r.recipeDate, u.userName AS creator, d.mealDiff 
          FROM recipe r
          JOIN registered_user u ON r.userID = u.userID
          JOIN meal_difficulty d ON r.diffID = d.diffID
          WHERE " . implode(" AND ", $whereClauses) . " ORDER BY r.recipeDate DESC";
$stmt = $conn->prepare($query);
if (!empty($params)) {
    $stmt->bind_param($paramTypes, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tasty Trio Recipe</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<style>
            .add-recipe-btn {
            display: block;
            width: fit-content;
            margin: 20px auto;
            padding: 10px 20px;
            background-color: #ff4500;
            color: white;
            font-size: 16px;
            font-weight: 600;
            border: none;
            border-radius: 15px;
            cursor: pointer;
            text-decoration: none;
            transition: background 0.3s;
        }

        .add-recipe-btn:hover {
            background-color: #e03e00;
            transform: translateY(-1px);
        }
</style>
</head>
<body>
<?php include('header.php'); ?>

<!-- Add Recipe Button -->
<?php if ($isLoggedIn): ?>
    <a href="addRecipe.php" class="add-recipe-btn">+ Add Recipe</a>
<?php endif; ?>
<div class="container mt-4">
    <div class="row">
        <!-- Sidebar -->
        <aside class="col-md-3">
            <form method="POST">
                <h5>Filter by Meal Type</h5>
                <?php while ($type = $typeResult->fetch_assoc()): ?>
                    <div>
                        <input type="checkbox" name="mealType[]" value="<?= $type['typeID'] ?>">
                        <label><?= htmlspecialchars($type['mealType']) ?></label>
                    </div>
                <?php endwhile; ?>
                <h5>Filter by Difficulty</h5>
                <?php while ($diff = $diffResult->fetch_assoc()): ?>
                    <div>
                        <input type="checkbox" name="mealDiff[]" value="<?= $diff['diffID'] ?>">
                        <label><?= htmlspecialchars($diff['mealDiff']) ?></label>
                    </div>
                <?php endwhile; ?>
                <button type="submit" class="btn btn-primary mt-2">Filter / Refresh </button>
            </form>
        </aside>
        <!-- Recipe List -->
        <section class="col-md-9">
            <div class="row">
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <div class="col-md-4">
                            <div class="card mb-4">
                                <img src="<?= htmlspecialchars($row['recipeImg']) ?>" class="card-img-top" alt="<?= htmlspecialchars($row['recipeName']) ?>">
                                <div class="card-body">
                                    <h5 class="card-title"> <?= htmlspecialchars($row['recipeName']) ?> </h5>
                                    <p class="card-text">Created by: <?= htmlspecialchars($row['creator']) ?> &bullet; <?= htmlspecialchars($row['mealDiff']) ?></p>
                                    <p class="card-text">Added on: <?= date("d M Y, H:i", strtotime($row['recipeDate'])) ?></p>
                                    <a href="user_recipe_details.php?id=<?= $row['recipeID'] ?>" class="btn btn-sm btn-info">View Recipe</a>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p>No recipes found.</p>
                <?php endif; ?>
            </div>
        </section>
    </div>
</div>
</body>
</html>
