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
    $sql = "SELECT r.recipeID, r.recipeImg, r.recipeName, r.recipeDate, u.userName 
            FROM recipe r
            JOIN registered_user u ON r.userID = u.userID
            WHERE r.recipeStatus = 'approved' 
            AND r.recipeName LIKE ?";

    $stmt = $conn->prepare($sql);
    $searchPattern = "%" . $searchQuery . "%";
    $stmt->bind_param("s", $searchPattern);
    $stmt->execute();
    $result = $stmt->get_result();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
    <?php include('header.php'); ?>

    <h2>Search Results for "<?php echo htmlspecialchars($searchQuery); ?>"</h2>

    <section class="recipes">
        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="recipe-card">
                    <a href="user_recipe_details.php?id=<?= $row['recipeID'] ?>" style="text-decoration: none; color: inherit;">
                        <img src="<?= htmlspecialchars($row['recipeImg']) ?>" alt="<?= htmlspecialchars($row['recipeName']) ?>">
                        <div class="recipe-content">
                            <h3 class="recipe-title"> <?= htmlspecialchars($row['recipeName']) ?> </h3>
                            <p class="recipe-meta">By <?= htmlspecialchars($row['userName']) ?> &bullet; <?= date("F j, Y", strtotime($row['recipeDate'])) ?></p>
                        </div>
                    </a>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No recipes found.</p>
        <?php endif; ?>
    </section>
</body>
</html>
