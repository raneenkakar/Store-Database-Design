<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users With Only Poor Reviews</title>
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

    // Query to find users whose every review is "Poor"
    $sql = "SELECT r1.writtenBy, r1.score, r1.forItem
            FROM review r1
            LEFT JOIN review r2 ON r1.writtenBy = r2.writtenBy AND r2.score != 'Poor'
            WHERE r1.score = 'Poor' AND r2.score IS NULL
            GROUP BY r1.writtenBy";

    $result = mysqli_query($conn, $sql);

    if (!$result) {
        die("<p class='error-message'>Error executing query: " . mysqli_error($conn) . "</p>");
    }

    echo "
        <div class='content'>
            <h2>Users Who Posted Only 'Poor' Reviews</h2>
            <table>
                <tr>
                    <th>User</th>
                    <th>Score Given</th>
                    <th>Item Reviewed</th>
                </tr>";

    while ($row = mysqli_fetch_assoc($result)) {
        $stmt = $conn->prepare("SELECT title FROM item WHERE itemId = ?");
        $stmt->bind_param("i", $row['forItem']); // Ensure type safety with 'i' for integer
        $stmt->execute();
        $itemResult = $stmt->get_result();
        $item = mysqli_fetch_assoc($itemResult);
        
        echo "  <tr>
                    <td>" . htmlspecialchars($row["writtenBy"]) . "</td>
                    <td>" . htmlspecialchars($row["score"]) . "</td>
                    <td>" . htmlspecialchars($item['title']) . "</td>
                </tr>";
    }

    echo "</table></div>";

    mysqli_close($conn);
    ?>
</body>
</html>
