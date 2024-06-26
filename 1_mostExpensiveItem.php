<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Most Expensive Items</title>
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
    <?php   
    require("phase_1/dbconnect.php");

    // Most expensive items in each category
    $sql = "SELECT i.category, i.title, i.price
            FROM item i
            JOIN (SELECT category, MAX(price) as max_price
               FROM item
               GROUP BY category) /*Group items by category to find the maximum price in each one*/
               as max_prices  /*Created a temporary table named 'max_prices' containing max prices per category*/ 
            ON i.category = max_prices.category AND i.price = max_prices.max_price"; /*Join the original table with 'max_prices' on matching category and price*/

    $result = mysqli_query($conn, $sql);

    echo " <div class='content'>
            <h2>Most Expensive Items in Each Category</h2><br>
            <table>
                <tr>
                    <th>Category</th>
                    <th>Item</th>
                    <th>Price</th>
                </tr>";

    while ($row = mysqli_fetch_assoc($result)) {
        echo "      <tr>
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
