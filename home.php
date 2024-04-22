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
    <title>Welcome Aboard!</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto">
</head>
<body>
    <div class="container">
        <header class="header">
            <h1>Hello,  <?php echo $_SESSION["firstName"]?>! </h1>
        </header>
        <main class="main-content">
            <p>Glad to see you in our community! You've successfully signed in.</p>
            <form action="phase_1/logout.php" method="post">
                <button type="submit" class="btn-logout">Sign Out</button>
            </form>
            <hr>
            <h2>Search for an item</h2>
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
                    <button type="submit" name="submit" class="button">🔎</button>
                </form>
            </div>
            <br>
            <h2>Search for a seller</h2>
            <div class="search-form">
                <form action="seller.php" method="get">
                    <input type="text" name="seller" placeholder="Enter username">
                    <button type="submit" name="submit" class="button">🔎</button>
                </form>
            </div>
        </main>
    </div>
</body>
</html>
