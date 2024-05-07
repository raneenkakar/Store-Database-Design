<?php
session_start();

// Redirect to the login page if the user isn't logged in
if (!isset($_SESSION["username"])) {
    header("Location: index.php?error=invalidsession");
    exit();
}

require("phase_1/dbconnect.php");

// Retrieve selected user from POST data
$selected_user = isset($_POST['selected_user']) ? $_POST['selected_user'] : '';

// Define SQL query to fetch items posted by user X with only "Excellent" or "Good" comments
$sql = "
SELECT i.title AS itemTitle, r.score AS commentScore
FROM item i
JOIN review r ON i.itemId = r.forItem
WHERE i.postedBy = ?
  AND r.score IN ('Excellent', 'Good')
  AND NOT EXISTS (
    SELECT 1
    FROM review r2
    WHERE r2.forItem = i.itemId
      AND r2.score NOT IN ('Excellent', 'Good')
  )
GROUP BY i.itemId, r.score
";

// Prepare the SQL statement
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $selected_user);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Items with 'Excellent' or 'Good' Reviews</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto">
</head>
<body>
    <div class="container-main">
        <div class="navbar">
            <a href="home.php">Search</a>
            <a href="postitem.php">Post</a>
            <a href="lists.php">Lists</a>
            <form action="phase_1/logout.php" method="post">
                <button type="submit" class="button-3">Log out</button>
            </form>
        </div>

        <div class="content">
            <h2>Items Posted by <?php echo htmlspecialchars($selected_user, ENT_QUOTES, 'UTF-8'); ?> with Only 'Excellent' or 'Good' Reviews</h2>

            <div class="list-container">
                <?php if ($result->num_rows > 0): ?>
                    <table>
                        <thead>
                            <tr>
                                <th>Item</th>
                                <th>Comment Score</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row['itemTitle'], ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td><?php echo htmlspecialchars($row['commentScore'], ENT_QUOTES, 'UTF-8'); ?></td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p>No items found with only 'Excellent' or 'Good' comments.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>
