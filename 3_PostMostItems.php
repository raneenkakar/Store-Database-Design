<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Top Item Posters</title>
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
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (!isset($_SESSION["username"])) {
        header("Location: index.php?error=invalidsession");
        exit();
    }

    require("phase_1/dbconnect.php");

    $sql = "SELECT u.username, COUNT(*) AS num_items FROM user u
            INNER JOIN item i ON u.username = i.postedBy
            WHERE i.postDate >= '2022-05-01'
            GROUP BY u.username
            HAVING COUNT(*) = (
                SELECT MAX(item_count) FROM (
                    SELECT postedBy, COUNT(*) AS item_count
                    FROM item
                    WHERE postDate >= '2022-05-01'
                    GROUP BY postedBy
                ) subquery
            )";

    $result = mysqli_query($conn, $sql);

    if (!$result) {
        echo "<p class='error-message'>Error executing query: " . mysqli_error($conn) . "</p>";
        exit;
    }
    ?>
    <div class='content'>
        <h2>Users who posted the most number of items</h2>
        <table>
            <tr>
                <th>User</th>
                <th>Number of Items Posted</th>
            </tr>
            <?php
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>
                          <td>" . htmlspecialchars($row["username"]) . "</td>
                          <td>" . htmlspecialchars($row["num_items"]) . "</td>
                      </tr>";
            }
            ?>
        </table>
    </div>
    <?php mysqli_close($conn); ?>
</body>
</html>
