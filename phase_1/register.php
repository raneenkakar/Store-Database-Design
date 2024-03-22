<?php

// Check if the form was submitted, otherwise redirect to the signup page
if (!isset($_POST["submit"])) {
    header("Location: ../signup.php");
    exit();
}

require("dbconnect.php");

// Retrieve user inputs from form
$username = $_POST["username"];
$password = $_POST["password"];
$cpassword = $_POST["cpassword"];
$firstName = $_POST["firstName"];
$lastName = $_POST["lastName"];
$email = $_POST["email"];

// Check if any of the input fields are empty
if (empty($username) || empty($password) || empty($cpassword) || empty($firstName) || empty($lastName) || empty($email)) {
    // Redirect to the signup page with an error message if fields are empty
    header("Location: ../signup.php?error=emptyfields");
    exit();
}

// Validate that the entered passwords match
if ($password != $cpassword) {
    // Redirect with an error if passwords do not match
    header("Location: ../signup.php?error=passwordmismatch");
    exit();
}

// Ensure the username is alphanumeric
if (!ctype_alnum($username)) {
    // Redirect with an error if the username is not alphanumeric
    header("Location: ../signup.php?error=alphanumericonly");
    exit();
}

// Check if password length is between 4 and 30 characters
if (strlen($password) < 4 || strlen($password) > 30) {
    // Redirect with an error if the password does not meet length requirements
    header("Location: ../signup.php?error=passwordlength");
    exit();
}

// Ensure both first and last names are alphabetic
if (!ctype_alpha($firstName) || !ctype_alpha($lastName)) {
    // Redirect with an error if names are not alphabetic
    header("Location: ../signup.php?error=lettersonly");
    exit();
}

// Validate email format
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    // Redirect with an error if email is invalid
    header("Location: ../signup.php?error=invalidemail");
    exit();
}

// Prepare SQL statement to check for existing username or email
$stmt = $conn->prepare("SELECT username FROM user WHERE username = ? OR email = ?");
$stmt->bind_param("ss", $username, $email);
$stmt->execute();
$result = $stmt->get_result();

// Check if the username or email already exists in the database
if ($result->num_rows > 0) {
    // Redirect with an error if username or email is already taken
    header("Location: ../signup.php?error=userexists");
    exit();
}

// Insert new user data into the database
$stmt2 = $conn->prepare("INSERT INTO user (username, password, firstName, lastName, email) VALUES (?, ?, ?, ?, ?)");
$stmt2->bind_param("sssss", $username, $password, $firstName, $lastName, $email);
$stmt2->execute();

// Redirect to the index page after successful registration
header("Location: ../index.php?error=none");
exit();
