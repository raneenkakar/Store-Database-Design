<?php 
session_start();

// Ensure the user is logged in, redirect otherwise
if (!isset($_SESSION["username"])) {
    header("Location: index.php?error=invalidsession");
    exit();
}

require("phase_1/dbconnect.php"); // Make sure this path matches your project structure

?>

<!DOCTYPE html>
<html>
<head>    
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Items</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto">
</head>
<body>
    <div class="container-main">
        <!-- Navbar Section -->
        <div class="navbar">
            <a href="home.php" class="active">Search</a>
            <a href="postitem.php">Post Item</a>
            <a href="lists.php">Lists</a>
            <form action="phase_1/logout.php" method="post">
                <button type="submit" class="button-3">Sign Out</button>
            </form>
        </div>

        <div class="content">
            <h2>Find Items</h2>
            <form action="search.php" method="post" class="search-form">
                <input type="text" name="category" placeholder="Enter category (e.g., Electronics)" required>
                <button type="submit" class="button-3">Search</button>
            </form>

            <?php
               if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["category"])) {
                $category = trim($_POST["category"]); // Trim whitespace
                $searchPattern = "%" . implode('%', explode(',', $category)) . "%"; // Create a pattern for LIKE clause
                $stmt = $conn->prepare("SELECT * FROM item WHERE category LIKE ?");
                $stmt->bind_param("s", $searchPattern);
                $stmt->execute();
                $itemResult = $stmt->get_result();
            
                if ($itemResult->num_rows > 0) {
                    echo "<h3>Results:</h3><ul>";
                    while ($row = $itemResult->fetch_assoc()) {
                        $stmt2 = $conn->prepare("SELECT * FROM review WHERE forItem = ?");
                        $stmt2->bind_param("i", $row['itemId']);
                        $stmt2->execute();
                        $reviewResult = $stmt2->get_result();
                        $numReviews = $reviewResult->num_rows;
            
                        echo "<div class='item-container'>
                            <div class='left-column'><a href='reviews.php?itemId=" . $row['itemId'] . "'>" . $row['title'] . " (";
                        echo $numReviews > 0 ? $numReviews . " reviews)" : "No reviews)";
                        echo "</a> - " . $row['description'] . " ($" . $row['price'] . ")</div>";
            
                        echo "<div class='right-column'>
                            <a href='reviewitem.php?itemId=" . $row['itemId'] . "' class='button--11'>Write a review</a>
                            <a href='seller.php?postedBy=" . $row['postedBy'] . "' class='button--11'>View seller</a>
                            </div></div><hr>";
                    }
                    echo "</ul>";
                } else {
                    echo "<p>No items found in the category '$category'</p>";
                }
            }            
            ?>
        </div>
    </div>
</body>
</html>
