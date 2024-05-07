<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users Without 3 or More Excellent Reviews</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto">
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
        <h2>Users Whose Items Never Gained 3 or More Excellent Reviews</h2>
        <table>
          <tr class='table-header'>
          <th>User</th>
          </tr>
   ";

   while ($row = mysqli_fetch_assoc($result)) {
   echo "
       <tr class='table-row'><td>" . htmlspecialchars($row["postedBy"]) . "</td></tr>";
   }

   echo "
      </table>
      </div>
   ";

    mysqli_close($conn);
    ?>
</body>
</html>