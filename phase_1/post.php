<?php
session_start();

// If user is not logged in, redirect to index.php to log in
if (!isset($_SESSION["username"])){
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

if ($row['count'] >= 3) {
    echo "You have already posted 3 items today. Please try again tomorrow.";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST["title"];
    $description = $_POST["description"];
    $category = $_POST["category"];
    $price = $_POST["price"];

    $stmt = $conn->prepare("INSERT INTO item (title, description, category, price, postedBy) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $title, $description, $category, $price, $username);

    if ($stmt->execute()) {
        echo "Item posted successfully!";
    } else {
        echo "Error posting item: " . $conn->error;
    }
}

?>
