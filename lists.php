<?php 
session_start();

if (!isset($_SESSION["username"])){
    header("Location: index.php?error=invalidsession");
    exit();
}
require("phase_1/dbconnect.php");
?>

<!DOCTYPE html>
<html>
<head>    
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Search Results </title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto">
</head>
<body>
    <div class="container-main">
        <div class="navbar">
            <a href="home.php">Search</a>
            <a href="postitem.php">Post</a>
            <a class="active" href="lists.php">Lists</a>
            <form action="phase_1/logout.php" method="post">
                <button type="submit" class="button-3">Log out</button>                
            </form>
        </div>
        <div class="content">
            <h2>Lists of items and users by special cases</h2>
            <div class="search-form" >
                <form action="lists.php" method="post">
                    <select id="case" name="case" required>
                        <option value="" disabled selected>Select a special case</option>
                        <option value="1">1- Most expensive items in each category</option>
                        <option value="2">2-Sellers who are favorited by two users </option>
                        <option value="3">3-Users who posted the most items </option>
                        <option value="4">4-Items posted by user X with only "excellent" or "good" reviews" </option>
                        <option value="5">5- Users whose items never gained 3 or more excellent reviews</option>  
                        <option value="6">6-Users who posted some reviews, but each of them is "poor" </option>
                        
                       



                    </select>
                    <button type="submit" name="submit" class="button" style="width:50px; font-size: 14px; ">🔎</button>
                </form>
            </div>
            </div>

            <div class="search-results">
            <?php 
                if (isset($_POST['submit'])) {
                    $case = $_POST['case'];
                
                    switch ($case) {
                        case "1":   
                            include("1_mostExpensiveItem.php");
                            break;
                        case "2":   
                                include("2_ExellentOrGoodReview.php");
                                break;      
                        case "3": 
                             include("3_PostMostItems.php"); // Assuming the new script is saved as `specificDateItems.php`
                            break;
                        case "4":   
                                include("4_usersCommonFavorites.php");
                                break; 
                        case "5": 
                                include("5_noExcellentItems.php");
                                break;
                        case "6": 
                                include("6_PoorReviewsOnly.php"); // Assuming the new script is saved as `specificDateItems.php`
                               break;
                        
                         
                    

                    }
                }
                
                ?>

            </div>
        </div>
    </div>
</body>
</html>