<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Top Item Posters on a Specific Date</title>
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

    // Specific date hardcoded
    $specificDate = '2024-04-01';
    $sql = "SELECT postedBy, COUNT(*) AS num_items FROM item
        WHERE postDate = ?
        GROUP BY postedBy
        ORDER BY num_items DESC"; 

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $specificDate);
    $stmt->execute();
    $result = $stmt->get_result();

    if (!$result) {
       echo "<p class='error-message'>Error executing query: " . $conn->error . "</p>";
       exit();
    } 

   ?>
    <div class='content'>
        <h2>Users who posted the most items on <?= htmlspecialchars($specificDate) ?></h2>
        <table>
            <tr>
                <th>User</th>
                <th>Number of Items Posted</th>
            </tr>
            <?php
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                          <td>" . htmlspecialchars($row["postedBy"]) . "</td>
                          <td>" . htmlspecialchars($row["num_items"]) . "</td>
                      </tr>";
            }
            ?>
        </table>
    </div>
    <?php mysqli_close($conn); ?>
</body>
</html>
