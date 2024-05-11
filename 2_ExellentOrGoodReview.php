<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Items With Only Excellent or Good Reviews</title>
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
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    // Redirect to login page if the user is not logged in
    if (!isset($_SESSION["username"])) {
        header("Location: index.php?error=invalidsession");
        exit();
    }

    require("phase_1/dbconnect.php");

    // Query to find items that have only "Excellent" or "Good" reviews and no "Bad" or "Fair" reviews
    $sql = "SELECT item.itemId, item.title, COUNT(review.score) as ReviewCount
            FROM item
            JOIN review ON item.itemId = review.forItem
            GROUP BY item.itemId
            HAVING SUM(review.score NOT IN ('Excellent', 'Good')) = 0";

    $result = mysqli_query($conn, $sql);

    if (!$result) {
        die("<p class='error-message'>Error executing query: " . mysqli_error($conn) . "</p>");
    }

    echo "
        <div class='content'>
            <h2>Items With Only 'Excellent' or 'Good' Reviews</h2>
            <table>
                <tr>
                    <th>Item ID</th>
                    <th>Item Title</th>
                    <th>Review Count</th>
                </tr>";

    while ($row = mysqli_fetch_assoc($result)) {
        echo "  <tr>
                    <td>" . htmlspecialchars($row["itemId"]) . "</td>
                    <td>" . htmlspecialchars($row["title"]) . "</td>
                    <td>" . htmlspecialchars($row["ReviewCount"]) . "</td>
                </tr>";
    }

    echo "</table></div>";

    mysqli_close($conn);
    ?>
</body>
</html>
