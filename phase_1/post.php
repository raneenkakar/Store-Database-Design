<?php
session_start();

// If user is not logged in, redirect to the login page
if (!isset($_SESSION["username"])) {
    header("Location: index.php?error=invalidsession");
    exit();
}

require("dbconnect.php");
$username = $_SESSION["username"];

// Check if the user has already posted 2 items today
$stmt = $conn->prepare("SELECT COUNT(*) AS count FROM item WHERE DATE(postDate) = CURDATE() AND postedBy = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

if ($row['count'] >= 2) {
    echo "You have already posted 2 items today. Please try again tomorrow.";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST["title"];
    $description = $_POST["description"];
    $categoryString = $_POST["category"];  // Use the entire category string
    $price = $_POST["price"];

    // Start transaction to handle insertion of new category string
    $conn->begin_transaction();

    try {
        // Check if this category string already exists
        $cat_stmt = $conn->prepare("SELECT category FROM itemCategory WHERE category = ?");
        $cat_stmt->bind_param("s", $categoryString);
        $cat_stmt->execute();
        $cat_result = $cat_stmt->get_result();
        if ($cat_result->num_rows == 0) {
            // Insert new category string
            $cat_insert_stmt = $conn->prepare("INSERT INTO itemCategory (category) VALUES (?)");
            $cat_insert_stmt->bind_param("s", $categoryString);
            $cat_insert_stmt->execute();
        }

        // Insert the item
        $item_stmt = $conn->prepare("INSERT INTO item (title, description, category, price, postedBy) VALUES (?, ?, ?, ?, ?)");
        $item_stmt->bind_param("sssss", $title, $description, $categoryString, $price, $username);
        $item_stmt->execute();

        $conn->commit();
        echo "Item posted successfully!";
    } catch (Exception $e) {
        $conn->rollback();
        echo "Error posting item: " . $conn->error;
    }
}
?>