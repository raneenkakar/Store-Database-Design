<?php
session_start();

// Redirect non-logged in users to the login page
if (!isset($_SESSION["username"])){
    header("Location: index.php?error=invalidsession");
    exit();
}

require("phase_1/dbconnect.php");

$username = $_SESSION["username"];

// Display an error message if the user has reached the post limit
if (isset($_GET['error']) && $_GET['error'] == 'reachedlimit') {
    $error_message = "You have already posted 3 items today. Please try again tomorrow.";
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Post an Item</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container-main">
        <div class="navbar">
            <a href="home.php">Search</a>
            <a class="active" href="postitem.php">Post</a>
            <a href="lists.php">Lists</a>
            <form action="phase_1/logout.php" method="post">
                <button type="submit" class="button-3">Sign Out</button>                
            </form>
        </div>

        <div class="content">
            <h2>Post an Item</h2>
            <?php if (!empty($error_message)) {
                echo "<p class='errormsg'>$error_message</p>";
            } ?>

            <form action="phase_1/post.php" method="post">
                <label for="title">Title:</label>
                <input type="text" id="title" name="title" required><br><br>

                <label for="description">Description:</label>
                <textarea id="description" name="description" required></textarea><br><br>

                <label for="category">Category:</label>
                <input type="text" id="category" name="category" placeholder="e.g., Electronics, Apple, iPhone" required><br><br>

                <label for="price">Price ($):</label>
                <input type="number" id="price" name="price" step="0.01" required><br><br>

                <button type="submit" class="button">Post Item</button>
            </form>
        </div>
    </div>
</body>
</html>