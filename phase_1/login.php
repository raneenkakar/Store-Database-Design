<?php

require("dbconnect.php"); // Including the database connection file

// Retrieve username and password from POST request
$userInputName = $_POST["username"];
$userInputPassword = $_POST["password"];

// Redirect if username or password is missing
if (empty($userInputName) || empty($userInputPassword)) {
    header("Location: ../index.php?error=emptyfields");
    exit();
}

// Prepare a query to select user data
// Using prepared statements to prevent SQL injection
$query = $conn->prepare("SELECT username, password, firstName FROM user WHERE username = ?");
$query->bind_param("s", $userInputName); // Binding parameters to prevent SQL injection
$query->execute();
$userData = $query->get_result();

// Check if the username is found in the database
if ($userData->num_rows == 0) {
    header("Location: ../index.php?error=usernotfound");
    exit();
}

// Fetch the user data
$userDetails = $userData->fetch_assoc();

// Verify the password
if ($userDetails["password"] != $userInputPassword) {
    header("Location: ../index.php?error=passwordwrong");
    exit();
}

// Start session and set session variables if login is successful
session_start();
$_SESSION["username"] = $userDetails["username"];
$_SESSION["firstName"] = $userDetails["firstName"];

// Redirect to the home page
header("Location: ../home.php");
exit();

