<?php
session_start();

// If user is not logged in, redirect to index.php to log in
if (!isset($_SESSION["username"])) {
    header("Location: index.php?error=invalidsession");
    exit();
}

require("phase_1/dbconnect.php");

// Initialize $seller variable to prevent undefined variable errors
$seller = '';

if (isset($_GET["postedBy"])) {
    $seller = $_GET["postedBy"];
} elseif (isset($_GET['seller'])) {
    $seller = $_GET['seller'];
}

// If $seller is still empty, redirect or handle the error appropriately
if (empty($seller)) {
    echo "Seller information is required.";
    exit(); // Or redirect to a different page
}

?>

<!DOCTYPE html>
<html>
<head>    
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seller Page</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto">
</head>
<body>
    <div class="container-main">
        <div class="navbar">
            <a href="home.php">Search</a>
            <a href="postitem.php">Post</a>
            <a class="active" href="seller.php">Seller</a>
            <a href="lists.php">Lists</a>
            <form action="logout.php" method="post">
                <button type="submit" class="button-3">Log out</button>                
            </form>
        </div>

        <div class="content">
            <h2>Seller: <?php echo htmlspecialchars($seller); ?></h2>
            <?php
                $stmt = $conn->prepare("SELECT * FROM item WHERE postedBy = ?");
                $stmt->bind_param("s", $seller);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<div class='item'>";
                        echo "<h3>" . htmlspecialchars($row['title']) . "</h3>";
                        echo "<p>" . htmlspecialchars($row['description']) . "</p>";
                        echo "<p>Price: $" . htmlspecialchars($row['price']) . "</p>";
                        echo "</div>";
                    }
                } else {
                    echo "<p>No items found for this seller.</p>";
                }
            ?>
        </div>
    </div>
</body>
</html>
