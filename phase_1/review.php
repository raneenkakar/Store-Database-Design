<?php
session_start();

// Redirect to login if user is not logged in
if (!isset($_SESSION["username"])) {
    header("Location: index.php?error=invalidsession");
    exit;
}

require("dbconnect.php");
$username = $_SESSION["username"];

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["itemId"], $_POST["remark"], $_POST["score"])) {
    $itemId = $_POST["itemId"];
    $remark = $_POST["remark"];
    $score = $_POST["score"];

    // Check for user's review count on the current day
    $query = "SELECT COUNT(*) FROM review WHERE writtenBy = ? AND DATE(reviewDate) = CURDATE()";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();

    if ($count >= 3) {
        header("Location: ../reviews.php?itemId=$itemId&error=reachedlimit");
        exit();
    }

    // Ensure user does not review their own item
    $query = "SELECT postedBy FROM item WHERE itemId = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $itemId);
    $stmt->execute();
    $stmt->bind_result($postedBy);
    $stmt->fetch();
    $stmt->close();

    if ($username == $postedBy) {
        header("Location: ../reviews.php?itemId=$itemId&error=sameuser");
        exit();
    }

    // Insert review into database
    $query = "INSERT INTO review (remark, score, writtenBy, forItem) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sssi", $remark, $score, $username, $itemId);

    if ($stmt->execute()) {
        header("Location: ../reviews.php?itemId=$itemId&error=none");
        exit();
    } else {
        echo "Error inserting data: " . $conn->error;
    }
} else {
    header("Location: ../home.php");
    exit();
}
?>
