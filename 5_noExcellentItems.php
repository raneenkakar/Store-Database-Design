<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users Without 3 or More Excellent Reviews</title>
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

    // Query to find users whose items never gained 3 or more excellent reviews
    $sql = " SELECT i1.postedBy
        FROM item i1
        LEFT JOIN review r ON i1.itemId = r.forItem
        WHERE i1.postedBy NOT IN (
            SELECT i2.postedBy
            FROM item i2
            JOIN review r2 ON i2.itemId = r2.forItem
            WHERE r2.score = 'Excellent'
            GROUP BY i2.postedBy
            HAVING COUNT(CASE WHEN r2.score = 'Excellent' THEN 1 END) >= 3
        )
        GROUP BY i1.postedBy
    ";

    $result = mysqli_query($conn, $sql);
    if (!$result) {
        die("Database query failed: " . mysqli_error($conn));
    }

   echo "
     <div class='content'>
      <div class='list-container'>
        <h2>Users Whose Items Never Gained 3 or More Excellent Reviews</h2>
        <table>
          <tr>
          <th>User</th>
          </tr>
   ";

   while ($row = mysqli_fetch_assoc($result)) {
   echo "
       <tr><td>" . htmlspecialchars($row["postedBy"]) . "</td></tr>";
   }

   echo "
       </div>
      </table>
     </div>
   ";

    mysqli_close($conn);
    ?>
</body>
</html>