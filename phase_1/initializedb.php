<?php

require("dbconnect.php");

$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Drop the 'user' table if it exists
$sql = "DROP TABLE IF EXISTS user;";
if (!$conn->query($sql)) {
    die("Error dropping user table: " . $conn->error);
}

// Create the 'user' table
$sql = "CREATE TABLE user (
    username VARCHAR(255) PRIMARY KEY,
    password VARCHAR(255) NOT NULL,
    firstName TEXT NOT NULL,
    lastName TEXT NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE
);";
if (!$conn->query($sql)) {
    die("Error creating user table: " . $conn->error);
}

// User data to be inserted
$users = [
    ["username1", "pass1", "FirstName1", "LastName1", "email1@example.com"],
    ["username2", "pass2", "FirstName2", "LastName2", "email2@example.com"],
    ["username3", "pass3", "FirstName3", "LastName3", "email3@example.com"],
    ["username4", "pass4", "FirstName4", "LastName4", "email4@example.com"],
    ["username5", "pass5", "FirstName5", "LastName5", "email5@example.com"]
];

// Loop through each user in the $users array
foreach ($users as $user) {
    // Prepare an SQL statement for insertion into the 'user' table.
    // The '?' placeholders will be replaced with actual values in a secure way.
    $sql = $conn->prepare("INSERT INTO user (username, password, firstName, lastName, email) VALUES (?, ?, ?, ?, ?)");

    // Bind the actual values to the '?' placeholders in the SQL statement.
    // 'sssss' indicates that all the placeholders are strings.
    // $user[0], $user[1], ..., $user[4] are the values for each user,
    // corresponding to username, password, firstName, lastName, and email, respectively.
    $sql->bind_param("sssss", $user[0], $user[1], $user[2], $user[3], $user[4]);

    // Execute the prepared statement.
    // If execution is not successful, it will enter the 'if' block.
    if (!$sql->execute()) {
        // Output the error message 
        echo "Error: " . $conn->error;
        // Close the database connection
        $conn->close();
        exit();
    }
}

// Close the connection
$conn->close();

// Redirect to the index page
header("Location: ../index.php?error=initsuccess");
exit();
