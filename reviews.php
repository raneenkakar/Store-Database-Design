<?php 
session_start();

// Redirect non-logged in users
if (!isset($_SESSION["username"])){
    header("Location: index.php?error=invalidsession");
    exit();
}

require("phase_1/dbconnect.php"); // Adjust the path as necessary

if (!isset($_GET["itemId"])) {
    header("Location: home.php");
    exit();
}

if (isset($_GET["itemId"])){ 
    $itemId = $_GET["itemId"];
}
?>

<!DOCTYPE html>
<html>
<head>    
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Item Reviews</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto">
</head>
<body>
    <div class="container-main">
        <div class="navbar">
        <a class="active" href="home.php">Search</a>
            <a href="home.php">Search</a>
            <a href="phase_1/postitem.php">Post</a>
            <a href="lists.php">Lists</a>
            <form action="phase_1/logout.php" method="post">
                <button type="submit" class="button-3">Sign Out</button>                
            </form>
        </div>
        
        <div class="content">
            <div class="search-results" >
            <?php
                if (isset($_GET["error"])) {
                    if($_GET["error"] == "none"){
                        echo "<p class='errormsg'>New review was posted successfully!</p>";
                    } elseif($_GET["error"] == "reachedlimit"){
                        echo "<p class='errormsg'>Unable to review item. You reached the limit of 3 reviews per day.</p>";
                    } elseif($_GET["error"] == "sameuser"){
                        echo "<p class='errormsg'>Unable to review your own listing.</p>";
                    } elseif($_GET["error"] == "alreadyreviewed"){
                        echo "<p class='errormsg'>You have already reviewed this item.</p>";
                    }
                }

                $stmt = $conn->prepare("SELECT * FROM item WHERE itemId = ?");
                $stmt->bind_param("s", $itemId);
                $stmt->execute();
                $itemResult = $stmt->get_result();
                $itemRow = mysqli_fetch_assoc($itemResult);

                $stmt2 = $conn->prepare("SELECT * FROM review WHERE forItem = ? ORDER BY reviewDate DESC");                                   
                $stmt2->bind_param("s", $itemRow['itemId']);
                $stmt2->execute();
                $reviewResult = $stmt2->get_result();
                $numReviews = mysqli_num_rows($reviewResult);

                

                echo "<h2>".$itemRow['title']." (" . $numReviews . " review" . ($numReviews == 1 ? "" : "s") . ")</h2>
                      <p>".$itemRow['description']."</p>
                      <p>Price: $".$itemRow['price']."</p>
                      <p>Category: ".$itemRow['category']."</p>
                      <p>Posted by: ".$itemRow['postedBy']." on ".date('F d, Y', strtotime($itemRow['postDate']))."</p>
                      <p><a href='reviewitem.php?itemId=".$itemId."' class='button'style='display:inline-block'>Write a review</a>
                      <a href='seller.php?postedBy=".$itemRow['postedBy']."' class='button' style='display:inline-block'>View seller</a></p>";
            ?>
        </div>

            <div class="review-container">
                <?php
                    if ($numReviews > 0) {
                        while ($reviewRow = $reviewResult->fetch_assoc()) {
                            echo "<div class='item-container'>
                                  <h2>".$reviewRow['writtenBy']." on ".date('F d, Y', strtotime($reviewRow['reviewDate']))."</h2>
                                  <p>Score: ".$reviewRow['score']."</p>
                                  <p><i>'".$reviewRow['remark']."'</i></p>
                                  </div><hr>";
                        }
                    } else {
                        echo "<h3>Be the first to review this item!</h3>";
                    }
                ?>
            </div>
        </div>    
    </div>
</body>
</html>
