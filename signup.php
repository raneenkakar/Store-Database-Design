<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;700&display=swap">
</head>

<body>
    <div class="container-login">
        <div class="content">
            <h2>Create Your Account</h2>
            <?php
               if (isset($_GET["error"])) {
                $errorMsgs = [
                    "emptyfields" => "Please fill in all the fields.",
                    "passwordmismatch" => "Oops! Your passwords didn't match.",
                    "duplicateuser" => "This username is already taken, try another.",
                    "duplicateemail" => "An account with this email already exists.",
                    "duplicateboth" => "Username and email are both taken. Please choose new ones.",
                    "passwordlength" => "Password length should be between 3 and 20 characters.",
                    "alphanumericonly" => "Username should be alphanumeric.",
                    "lettersonly" => "Names should contain only letters.",
                    "emailvalidation" => "Enter a valid email address, please."
                ];
                echo "<p class='error-message'>" . $errorMsgs[$_GET["error"]] . "</p>";
            }
            ?>
            <form action="phase_1/register.php" method="post" class="signup-form">
                <input type="text" name="username" placeholder="Username">
                <input type="password" name="password" placeholder="Password">
                <input type="password" name="cpassword" placeholder="Confirm Password">
                <input type="text" name="firstName" placeholder="First Name">
                <input type="text" name="lastName" placeholder="Last Name">
                <input type="email" name="email" placeholder="Email">
                <button type="submit" name="submit" class="register-button">Sign Up</button>
            </form>
            <p class="login-prompt">Already registered? <a href="index.php" class="login-link">Log In Here</a></p>
        </div>
    </div>
</body>
</html>
