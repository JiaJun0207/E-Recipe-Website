<?php
// Start the session
session_start();

// Include your database connection file
include 'db.php';

// Initialize variables for user data
$userImg = 'uploads/default.png'; // Default profile image
$userName = 'Guest';

// Check if user is logged in
$isLoggedIn = false;
if (isset($_SESSION['userID'])) {
    $isLoggedIn = true;
    $userID = $_SESSION['userID'];

    // Fetch user data from the database
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

// Check if search query is set
$searchQuery = "";
if (isset($_GET['query'])) {
    $searchQuery = trim($_GET['query']);

    // Fetch recipes based on search query (case-insensitive)
    $sql = "SELECT r.recipeID, r.recipeImg, r.recipeName, d.mealDiff, u.userName, 
            (SELECT COUNT(*) FROM favorite f WHERE f.recipeID = r.recipeID AND f.userID = ?) AS isFavorited 
            FROM recipe r
            JOIN registered_user u ON r.userID = u.userID
            JOIN meal_difficulty d ON r.diffID = d.diffID
            WHERE r.recipeStatus = 'approved' 
            AND r.recipeName LIKE ?";

    $stmt = $conn->prepare($sql);
    $searchPattern = "%" . $searchQuery . "%";
    $stmt->bind_param("is", $userID, $searchPattern);
    $stmt->execute();
    $result = $stmt->get_result();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results - Tasty Trio Recipe</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f9f9f9;
        }
        .search-container {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .search-box {
            display: flex;
            align-items: center;
            background: #f0e6fa;
            border-radius: 25px;
            padding: 10px 20px;
            width: 50%;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .search-box i {
            color: #8a2be2;
            font-size: 18px;
        }
        .search-box input {
            border: none;
            background: transparent;
            outline: none;
            font-size: 18px;
            margin-left: 10px;
            width: 100%;
        }
        .recipes-container {
            max-width: 1200px;
            margin: auto;
            padding: 20px;
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
        }
        .recipe-card {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
            width: 280px;
            text-decoration: none;
            color: inherit;
        }
        .recipe-card:hover {
            transform: translateY(-5px);
        }
        .recipe-card img {
            width: 100%;
            height: 180px;
            object-fit: cover;
        }
        .recipe-content {
            padding: 15px;
        }
        .recipe-title {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 5px;
        }
        .recipe-title a {
            color: inherit !important;  /* Use the parent text color */
            text-decoration: none !important;  /* Remove underline */
            font-weight: bold; /* Keep it noticeable */
        }

        .recipe-title a:hover {
            color: #D81B60 !important; /* Slightly different color on hover */
            text-decoration: none !important;
        }

        .recipe-meta {
            font-size: 14px;
            color: gray;
        }
        .fav-icon {
            color: #E75480;
            float: right;
        }
        .fav-icon-gray {
            color: #ddd;
        }
        /* Remove background and border from favorite button */
        .fav-btn {
            background: none;
            border: none;
            padding: 0;
            cursor: pointer;
        }

        /* Adjust icon size and positioning */
        .fav-btn i {
            font-size: 20px; /* Adjust size */
            transition: color 0.3s ease;
        }
    </style>
</head>
<body>

    <?php include('header.php'); ?>

    <!-- Search Bar -->
    <div class="search-container">
        <div class="search-box">
            <i class="fas fa-search"></i>
            <input type="text" value="<?php echo htmlspecialchars($searchQuery); ?>" placeholder="Search recipes..." onkeydown="if(event.key === 'Enter') searchRecipe()">
        </div>
    </div>

    <!-- Recipe List -->
    <section class="recipes-container">
    <?php if ($result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
        <div class="recipe-card">
            <!-- Clickable Recipe Image -->
            <a href="user_recipe_details.php?id=<?= $row['recipeID'] ?>">
                <img src="<?= htmlspecialchars($row['recipeImg']) ?>" alt="<?= htmlspecialchars($row['recipeName']) ?>">
            </a>
            <div class="recipe-content">
                <!-- Recipe Title (Clickable, but Styled as Normal Text) -->
                <h3 class="recipe-title">
                    <a href="user_recipe_details.php?id=<?= $row['recipeID'] ?>">
                        <?= htmlspecialchars($row['recipeName']) ?>
                    </a>
                </h3>
                <p class="recipe-meta"><?= htmlspecialchars($row['userName']) ?> • <?= htmlspecialchars($row['mealDiff']) ?></p>

                <!-- Favorite Button -->
                <button type="button" class="fav-btn" data-recipe-id="<?= $row['recipeID'] ?>">
                    <i class="fas fa-heart <?= $row['isFavorited'] ? 'fav-icon' : 'fav-icon-gray' ?>"></i>
                </button>
            </div>
        </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p style="text-align: center; font-size: 18px; color: #888; width: 100%;">No recipes found.</p>
    <?php endif; ?>
</section>
    <div class="toast-container position-fixed bottom-0 end-0 p-3">
    <div id="favoriteToast" class="toast align-items-center text-white bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body" id="toastMessageContent"></div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>
</div>
    <script>
        function searchRecipe() {
            let query = document.querySelector('.search-box input').value.trim();
            if (query.length > 0) {
                window.location.href = 'searchrecipe.php?query=' + encodeURIComponent(query);
            }
        }
        $(document).ready(function () {
        $(".fav-btn").click(function () {
            let btn = $(this);
            let recipeID = btn.data("recipe-id");

            $.ajax({
                url: "toggle_favorite.php",
                type: "POST",
                data: { recipeID: recipeID },
                dataType: "json",
                success: function (response) {
                    if (response.status === "added") {
                        btn.find("i").removeClass("fav-icon-gray").addClass("fav-icon");
                    } else if (response.status === "removed") {
                        btn.find("i").removeClass("fav-icon").addClass("fav-icon-gray");
                    }

                    // Show toast notification
                    $("#toastMessageContent").text(response.message);
                    $("#favoriteToast").removeClass("bg-success bg-danger").addClass(response.toastClass);
                    var toast = new bootstrap.Toast(document.getElementById("favoriteToast"));
                    toast.show();
                }
            });
        });
    });
    </script>

</body>
</html>
