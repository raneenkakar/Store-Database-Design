<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Most Expensive Items</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto">
</head>
<body>
    <?php   
    require("phase_1/dbconnect.php");

    // Most expensive items in each category
    $sql = "SELECT category, title, price 
            FROM item 
            WHERE (category, price) IN 
                (SELECT category, MAX(price) 
                FROM item GROUP BY category)";

    $result = mysqli_query($conn, $sql);

    echo " <div class='content'>
            <h2>Most Expensive Items in Each Category</h2><br>
            <table>
                <tr class='table-header'>
                    <th>Category</th>
                    <th>Item</th>
                    <th>Price</th>
                </tr>";

    while ($row = mysqli_fetch_assoc($result)) {
        echo "      <tr class='table-row'>
                        <td>".$row['category']."</td>
                        <td>".$row['title']."</td>
                        <td>".$row['price']."</td>
                    </tr>";
    }
    echo "
        </table>
        </div>";

    mysqli_close($conn);
    ?>
</body>
</html>
