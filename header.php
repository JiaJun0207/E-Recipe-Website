<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Header</title>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
        }
        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 40px;
            background-color: white;
        }
        .logo-title {
            display: flex;
            align-items: center;
        }
        .logo-title img {
            height: 50px;
            margin-right: 10px;
        }
        .logo-title a{
            text-decoration: none;
        }
        .logo-title h1 {
            font-size: 24px;
            font-weight: 600;
            margin: 0;
            color: #E75480;
        }
        .logo-title h1:hover{
            color: #E75480;
            text-decoration: underline;
        }
        header input {
            width: 50%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .navbar {
            display: flex;
            gap: 20px;
        }
        .navbar a {
            text-decoration: none;
            color: black;
            font-weight: 500;
            padding: 5px 10px;
            transition: color 0.3s ease, border-bottom 0.3s ease;
        }
        .navbar a:hover {
            color: #D81B60;
            text-decoration: underline;
        }
        .recipes {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            padding: 20px 40px;
        }
        .recipe-card {
            background-color: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }
        .recipe-card:hover {
            transform: translateY(-5px);
        }
        .recipe-card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }
        .recipe-content {
            padding: 20px;
        }
        .recipe-title {
            font-size: 18px;
            font-weight: 600;
            margin: 0;
        }
        .recipe-meta {
            font-size: 14px;
            color: gray;
        }
        .favorite-icon {
            float: right;
            font-size: 18px;
            color: #ffd700;
            cursor: pointer;
        }
        .profile-dropdown {
            position: relative;
            display: inline-block;
        }
        .profile-dropdown img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover; /* Ensures the image maintains its aspect ratio */
            cursor: pointer;
        }
        .dropdown-menu {
            min-width: 180px;
            right: 0;
            left: auto;
        }
        .dropdown-menu a {
            display: flex;
            align-items: center;
            padding: 10px;
            text-decoration: none;
        }
        .dropdown-menu a i {
            margin-right: 8px;
        }
    </style>
</head>
<body>
<header>
    <div class="logo-title">
        <img src="assets/pic/TastyTrioLogo.png" alt="Logo">
        <!-- <h1>Tasty Trio Recipe</h1>  -->
        <a href="home.php"><h1>Tasty Trio Recipe</h1></a>
    </div>
    <input type="text" placeholder="What you want to cook today?" style="font-family: 'Poppins', sans-serif;">
    <nav class="navbar">
        <a href="eRecipeList.php"> All Recipes</a>
        <!-- <a href="#">Categories</a>
        <a href="#">Favourite</a> -->
        <!-- <a href="about-us.php">About Us</a> -->
    </nav>

    <!-- Profile Dropdown -->
    <div class="profile-dropdown">
        <button class="btn btn-light dropdown-toggle d-flex align-items-center" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
            <img src="<?php echo htmlspecialchars($userImg); ?>" alt="Profile">
            <span class="ms-2"><?php echo htmlspecialchars($userName); ?></span>
        </button>
        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
            <li><a class="dropdown-item" href="profile-dashboard.php"><i class="fas fa-user"></i> Profile</a></li>
            <li><a class="dropdown-item text-danger" href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul>
    </div>
</header>
</body>
</html>