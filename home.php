<?php 
session_start();

// Redirect to login page if user is not logged in
if (!isset($_SESSION["firstName"])) {
    header("Location: login.php");
    exit();
}

require("phase_1/dbconnect.php");

// Fetch categories from the database
$categoryQuery = $conn->prepare("SELECT category FROM itemCategory ORDER BY category ASC");
$categoryQuery->execute();
$categoryResult = $categoryQuery->get_result();
$categories = [];
while ($category = $categoryResult->fetch_assoc()) {
    $categories[] = $category['category'];
}

// Convert categories to JSON format
$jsonCategories = json_encode($categories);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search for an Item</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto">
    <script>
        // Pass PHP array to JavaScript
        var categories = <?php echo $jsonCategories; ?>;
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-ui/1.12.1/jquery-ui.min.js"></script>
</head>
<body>
    <div class="container-main">
        <div class="navbar">
            <a href="home.php">Search</a>
            <a href="postitem.php">Post</a>
            <a href="lists.php">Lists</a>
            <form action="phase_1/logout.php" method="post">
                <button type="submit" class="button-3">Sign Out</button>                
            </form>
        </div>
        <div class="content">
            <p>Logged in as <strong><?php echo $_SESSION['firstName']; ?></strong></p>
            <h2>Search for an item</h2>
            <hr>
            <div class="search-form">
                <form action="search.php" method="post">
                    <input type="text" id="category" name="category" placeholder="Type a category" required>
                    <button type="submit" name="submit" style="width:50px; font-size: 14px;">🔎</button>
                </form>
            </div>
            <br>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            // Apply jQuery UI Autocomplete to the input field
            $("#category").autocomplete({
                source: categories,
                minLength: 1 // Trigger autocomplete with 1 character
            });
        });
    </script>
</body>
</html>

