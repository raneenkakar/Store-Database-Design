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
                        <option value="2">2- List all the items posted by user X, such that all the comments are "Excellent" or "good"  </option>
                        <option value="3">3- List the users who posted the most number of items on a specific date like 4/1/2024; </option>
                        <option value="4">4- List the other users who are favorited by both users X, and Y." </option>
                        <option value="5">5- Display all the users who never posted any "excellent" items</option>  
                        <option value="6">6- Users who posted some reviews, but each of them is "poor" </option>
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
                             include("3_PostMostItems.php"); 
                            break;
                        case "4":   
                            echo "
                            <div class='list-container'>
                                <h3>Sellers who are favorited by a pair of users</h3>
                                    <div class='forms' style='text-align: center;'>
                                        <form action='4_usersCommonFavorites.php' method='post'>
                                            <select id='category1' name='category1' required>
                                                <option value='' disabled selected>Select a user</option>
                                                <option value='Ali1'>Ali1</option>
                                                <option value='Alice6'>Alice6</option>
                                                <option value='Jane4'>Jane4</option>
                                                <option value='Mathew5'>Mathew5</option>
                                                <option value='Raneen2'>Raneen2</option>
                                                <option value='Sheema3'>Sheema3</option>
                                            </select>
                                            <select id='category2' name='category2' required>
                                                <option value='' disabled selected>Select a user</option>
                                                <option value='Ali1'>Ali1</option>
                                                <option value='Alice6'>Alice6</option>
                                                <option value='Jane4'>Jane4</option>
                                                <option value='Mathew5'>Mathew5</option>
                                                <option value='Raneen2'>Raneen2</option>
                                                <option value='Sheema3'>Sheema3</option>
                                            </select>
                                            <button type='submit' name='submit' style='width:50px; font-size: 14px;'>🔎</button>
                                        </form>
                                    </div>
                            </div>";
                                break; 
                        case "5": 
                                include("5_noExcellentItems.php");
                                break;        
                        case "6": 
                                include("6_PoorReviewsOnly.php"); 
                               break;
                    }
                }
                
                ?>

            </div>
        </div>
    </div>
</body>
</html>
