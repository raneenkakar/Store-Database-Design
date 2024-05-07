<?php
session_start();

// Redirect to login page if user is not logged in
if (!isset($_SESSION["firstName"])) {
    header("Location: login.php");
    exit();
}

// Including the database connection file from the phase_1 directory
require("phase_1/dbconnect.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search for an Item</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto">
</head>
<body>
    <div class="container-main">
        <div class="navbar">
            <a href="home.php">Search</a>
            <a href="postitem.php">Post</a>
            <a href="lists.php">Lists</a>
            <form action="phase_1/logout.php" method="post">
                <button type="submit" class= "button-3">Sign Out</button>                
            </form>
        </div>
        <div class="content">
            <p>
                Logged in as
                <strong>
                    <?php echo $_SESSION['firstName']; ?>
                </strong>
            </p>
            <h2>Search for an item</h2>
            <hr>
            
            <div class="search-form">
                <form action="search.php" method="post">
                    <select id="category" name="category" required>
                        <option value="" disabled selected>Select a category</option>
                        <?php 
                        // Fetch categories from the database for the dropdown
                        $categoryQuery = $conn->prepare("SELECT category FROM itemCategory ORDER BY category ASC");
                        $categoryQuery->execute();
                        $categoryResult = $categoryQuery->get_result();
                        while ($category = $categoryResult->fetch_assoc()) {
                            echo "<option value='" . htmlspecialchars($category['category']) . "'>" . htmlspecialchars($category['category']) . "</option>";
                        }
                        ?>
                    </select>
                    <button type="submit" name="submit" style="width:50px; font-size: 14px;">🔎 </button>
                </form>
            </div>
            <br>
        </main>
    </div>
</body>
