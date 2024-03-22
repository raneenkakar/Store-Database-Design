<?php

// Start the session
session_start();

// Redirect to signup page if the form wasn't submitted
if (!isset($_POST["submit"])) {
    header("Location: ../signup.php");
    exit();
}

// Include database connection
require("dbconnect.php");

// Retrieve user input from form
$username = $_POST["username"];
$password = $_POST["password"];
$cpassword = $_POST["cpassword"];
$firstName = $_POST["firstName"];
$lastName = $_POST["lastName"];
$email = $_POST["email"];

// Check for empty fields
if (empty($username) || empty($password) || empty($cpassword) || empty($firstName) || empty($lastName) || empty($email)) {
    header("Location: ../signup.php?error=emptyfields");
    exit();
}

// Verify passwords match
if ($password != $cpassword) {
    header("Location: ../signup.php?error=passwordmismatch");
    exit();
}

// Validate alphanumeric username
if (!ctype_alnum($username)) {
    header("Location: ../signup.php?error=alphanumericonly");
    exit();
}

// Validate password length
if (strlen($password) < 4 || strlen($password) > 30) {
    header("Location: ../signup.php?error=passwordlength");
    exit();
}

// Validate alphabetic first and last names
if (!ctype_alpha($firstName) || !ctype_alpha($lastName)) {
    header("Location: ../signup.php?error=lettersonly");
    exit();
}

// Validate email format
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header("Location: ../signup.php?error=invalidemail");
    exit();
}

// Prepare SQL statement to check for existing username or email
$stmt = $conn->prepare("SELECT username, email FROM user WHERE username = ? OR email = ?");
$stmt->bind_param("ss", $username, $email);
$stmt->execute();
$result = $stmt->get_result();

// Check if username or email is already taken
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        if ($row['username'] == $username && $row['email'] == $email) {
            // Redirect if both username and email are taken
            header("Location: ../signup.php?error=duplicateboth");
            exit();
        } elseif ($row['username'] == $username) {
            // Redirect if username is taken
            header("Location: ../signup.php?error=duplicateuser");
            exit();
        } elseif ($row['email'] == $email) {
            // Redirect if email is taken
            header("Location: ../signup.php?error=duplicateemail");
            exit();
        }
    }
}

// Insert user data into database
$stmt2 = $conn->prepare("INSERT INTO user (username, password, firstName, lastName, email) VALUES (?, ?, ?, ?, ?)");
$stmt2->bind_param("sssss", $username, $password, $firstName, $lastName, $email);
$stmt2->execute();

// Redirect to index page after successful registration
header("Location: ../index.php?error=none");
exit();