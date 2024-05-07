<?php
require("phase_1/dbconnect.php");

// Initialize variables for selected users
$user_x = isset($_POST['user_x']) ? $_POST['user_x'] : null;
$user_y = isset($_POST['user_y']) ? $_POST['user_y'] : null;

$result = null;
$common_favorites = [];

// If both User X and User Y are provided, execute the query to find common favorites
if ($user_x && $user_y) {
    $stmt = $conn->prepare("
        SELECT seller 
        FROM favorite 
        WHERE buyer = ? 
        INTERSECT 
        SELECT seller 
        FROM favorite 
        WHERE buyer = ?
    "); // This part finds common favorites
    $stmt->bind_param("ss", $user_x, $user_y); 
    $stmt->execute(); 
    $result = $stmt->get_result(); 

    // Fetch common favorites into an array for use in the dropdown
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $common_favorites[] = $row["seller"]; // This represents the common favorite users, also referred to as "user C"
        }
    }
}

// Fetch the list of distinct users for the dropdown
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
    <div class="content">
        <h2>Find Users Favorited by Both User X and User Y</h2>

        <div class="search-form">
            <form action="lists.php" method="post">
                <!-- Dropdown to select User X -->
                <label for="user_x">Select User X:</label>
                <select id="user_x" name="user_x" required>
                    <option value="" disabled selected>Select User X</option>
                    <?php foreach ($users as $user): ?>
                        <option value="<?php echo htmlspecialchars($user['buyer'], ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars($user['buyer'], ENT_QUOTES, 'UTF-8'); ?></option>
                    <?php endforeach; ?>
                </select>

                <!-- Dropdown to select User Y -->
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

        <!-- If there are common favorites, show them in a dropdown and a table -->
        <div class="list-container">
            <?php if ($common_favorites): ?>
                <!-- This section is where the common favorites are displayed -->
                <h3>Users Favorited by Both <?php echo htmlspecialchars($user_x, ENT_QUOTES, 'UTF-8'); ?> and <?php echo htmlspecialchars($user_y, ENT_QUOTES, 'UTF-8'); ?></h3>
                <!-- New dropdown menu with common favorites  -->
                <label for="common_users">Select a Common Favorite:</label>
                <select id="common_users" name="common_users">
                    <option value="" disabled selected>Select Common Favorite</option>
                    <?php foreach ($common_favorites as $common_user): ?>
                        <option value="<?php echo htmlspecialchars($common_user, ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars($common_user, ENT_QUOTES, 'UTF-8'); ?></option>
                    <?php endforeach; ?>
                </select>

               
                <table>
                    <thead>
                        <tr>
                            <th>Seller</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($common_favorites as $common_user): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($common_user, ENT_QUOTES, 'UTF-8'); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <!-- Message if no common favorites are found -->
                <p>No common favorites found for <?php echo htmlspecialchars($user_x, ENT_QUOTES, 'UTF-8'); ?> and <?php echo htmlspecialchars($user_y, ENT_QUOTES, 'UTF-8'); ?>.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
