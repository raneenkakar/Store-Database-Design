<?php
session_start();

// Check if the user is logged in; if not, redirect to the login page
if (!isset($_SESSION["username"])) {
    header("Location: index.php?error=invalidsession");
    exit();
}

require("procedures/dbconnect.php");

// Initialize variables for selected users
$user_x = isset($_POST['user_x']) ? $_POST['user_x'] : null;
$user_y = isset($_POST['user_y']) ? $_POST['user_y'] : null;

$result = null;

// If both user X and user Y are provided, execute the query to find common favorites
if ($user_x && $user_y) {
    $stmt = $conn->prepare("
        SELECT seller
        FROM favorite
        WHERE buyer = ? OR buyer = ?
        GROUP BY seller
        HAVING COUNT(DISTINCT buyer) = 2
    ");
    $stmt->bind_param("ss", $user_x, $user_y);
    $stmt->execute();
    $result = $stmt->get_result();
}

// Retrieve list of all users for dropdown selection
$user_query = $conn->query("SELECT DISTINCT buyer FROM favorite");
$users = $user_query->fetch_all(MYSQLI_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>    
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Common Favorites of Users X and Y</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto">
</head>
<body>
    <div class="container-main">
        <div class="navbar">
            <a href="home.php">Search</a>
            <a href="postitem.php">Post</a>
            <a class="active" href="lists.php">Lists</a>
            <form action="procedures/logout.php" method="post">
                <button type="submit" class="button-3">Log out</button>                
            </form>
        </div>

        <div class="content">
            <h2>Find Users Favorited by Both User X and User Y</h2>

            <div class="search-form">
                <form action="lists.php" method="post">
                    <label for="user_x">Select User X:</label>
                    <select id="user_x" name="user_x" required>
                        <option value="" disabled selected>Select User X</option>
                        <?php foreach ($users as $user): ?>
                            <option value="<?php echo htmlspecialchars($user['buyer'], ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars($user['buyer'], ENT_QUOTES, 'UTF-8'); ?></option>
                        <?php endforeach; ?>
                    </select>

                    <label for="user_y">Select User Y:</label>
                    <select id="user_y" name="user_y" required>
                        <option value="" disabled selected>Select User Y</option>
                        <?php foreach ($users as $user): ?>
                            <option value="<?php echo htmlspecialchars($user['buyer'], ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars($user['buyer'], ENT_QUOTES, 'UTF-8'); ?></option>
                        <?php endforeach; ?>
                    </select>

                    <button type="submit" class="button">Find Common Favorites</button>
                </form>
            </div>

            <div class="list-container">
                <?php if ($result && $result->num_rows > 0): ?>
                    <h3>Users Favorited by Both <?php echo htmlspecialchars($user_x, ENT_QUOTES, 'UTF-8'); ?> and <?php echo htmlspecialchars($user_y, ENT_QUOTES, 'UTF-8'); ?></h3>
                    <table>
                        <thead>
                            <tr>
                                <th>Seller</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row["seller"], ENT_QUOTES, 'UTF-8'); ?></td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p>No common favorites found for <?php echo htmlspecialchars($user_x, ENT_QUOTES, 'UTF-8'); ?> and <?php echo htmlspecialchars($user_y, ENT_QUOTES, 'UTF-8'); ?>.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>
