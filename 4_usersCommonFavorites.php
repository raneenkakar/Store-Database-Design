<?php 
session_start();
if (!isset($_SESSION["username"])){
    header("Location: index.php?error=invalidsession");
    exit();
}

require("phase_1/dbconnect.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>    
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background: #f4f4f4;
            margin: 0;
            padding: 0;
            color: #333;
        }
        .content {
            background-color: #f0faff; 
            padding: 30px; 
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 70%; 
            max-width: 800px; 
            margin: 30px auto; 
            font-size: 16px; 
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            text-align: center;
            padding: 8px;
            border-bottom: 1px solid #ccc;
        }
        th {
            background-color: white;
        }
    </style>
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
          <div class='list-container'>
              <h3>List the other users who are favorited by both users X, and Y.</h3>
              <h2></h2>
              <?php
                $category1 = $_POST['category1'];
                $category2 = $_POST['category2'];
                $stmt = $conn->prepare("SELECT X.buyer AS buyer1, Y.buyer AS buyer2, X.seller
                FROM favorite AS X, favorite AS Y 
                WHERE X.buyer = ? AND Y.buyer = ?
                AND X.buyer <> Y.buyer 
                AND X.seller = Y.seller");
                $stmt->bind_param("ss", $category1, $category2);
                $stmt->execute();
                $result = $stmt->get_result();
              ?>
             <table>
                <tr>
                    <th>Buyer 1</th>
                    <th>Buyer 2</th>
                    <th>Seller</th>
                </tr>
                <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                    <tr>
                        <td><?php echo $row["buyer1"]; ?></td>
                        <td><?php echo $row["buyer2"]; ?></td>
                        <td><?php echo $row["seller"]; ?></td>
                    </tr>
                <?php } ?>
             </table>
            </div>
        </div>
    </div>
</body>
</html>