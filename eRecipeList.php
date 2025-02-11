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
    <title>Recipe List - Tasty Trio</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
        }
        .container {
            max-width: 1200px;
        }
        .filter-card {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .recipe-card {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease-in-out;
        }
        .recipe-card:hover {
            transform: translateY(-5px);
        }
        .recipe-card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }
        .recipe-card .card-body {
            padding: 15px;
        }
        .recipe-title {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 5px;
            color: #333;
        }
        .add-recipe-btn {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background: #ff4500;
            color: white;
            padding: 12px 20px;
            font-size: 18px;
            font-weight: bold;
            border-radius: 50px;
            transition: 0.3s ease;
            box-shadow: 0 5px 15px rgba(255, 69, 0, 0.3);
            text-decoration: none;
        }
        .add-recipe-btn:hover {
            background: #e03e00;
            transform: translateY(-3px);
        }
    </style>
</head>
<body>

<?php include('header.php'); ?>

<!-- Add Recipe Button -->
<?php if ($isLoggedIn): ?>
    <a href="addRecipe.php" class="add-recipe-btn"><i class="fas fa-plus-circle"></i> Add Recipe</a>
<?php endif; ?>

<div class="container mt-4">
    <div class="row">
        <!-- Sidebar Filters -->
        <aside class="col-md-3">
            <div class="filter-card">
                <form method="POST">
                    <h5>Filter by Meal Type</h5>
                    <?php while ($type = $typeResult->fetch_assoc()): ?>
                        <div>
                            <input type="checkbox" name="mealType[]" value="<?= $type['typeID'] ?>">
                            <label><?= htmlspecialchars($type['mealType']) ?></label>
                        </div>
                    <?php endwhile; ?>
                    <h5 class="mt-3">Filter by Difficulty</h5>
                    <?php while ($diff = $diffResult->fetch_assoc()): ?>
                        <div>
                            <input type="checkbox" name="mealDiff[]" value="<?= $diff['diffID'] ?>">
                            <label><?= htmlspecialchars($diff['mealDiff']) ?></label>
                        </div>
                    <?php endwhile; ?>
                    <button type="submit" class="btn btn-primary mt-3 w-100">Apply Filters / Refresh </button>
                </form>
            </div>
        </aside>

        <!-- Recipe List -->
        <section class="col-md-9">
            <div class="row">
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <div class="col-md-4 mb-4">
                            <div class="recipe-card">
                                <img src="<?= htmlspecialchars($row['recipeImg']) ?>" alt="<?= htmlspecialchars($row['recipeName']) ?>">
                                <div class="card-body">
                                    <h5 class="recipe-title"><?= htmlspecialchars($row['recipeName']) ?></h5>
                                    <p class="text-muted">By <?= htmlspecialchars($row['creator']) ?> • <?= htmlspecialchars($row['mealDiff']) ?></p>
                                    <a href="user_recipe_details.php?id=<?= $row['recipeID'] ?>" class="btn btn-info btn-sm">View Recipe</a>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p class="text-center">No recipes found.</p>
                <?php endif; ?>
            </div>
        </section>
    </div>
</div>

</body>
</html>
