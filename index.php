<?php
session_start();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to Our Portal</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;700&display=swap">
</head>

<body>
    <div class="container">
        <div class="login-box">
            <h1>Greetings!</h1>
            <div class="message-area">
                <?php
                    if (isset($_GET["error"])) {
                        $errorMessages = [
                            "none" => "Successfully registered! Welcome aboard.",
                            "emptyfields" => "Oops! Don't leave any fields empty.",
                            "usernotfound" => "Username not recognized. Try again.",
                            "passwordwrong" => "Incorrect password. Give it another shot.",
                            "invalidsession" => "You need to log in to view this page.",
                            "loggedout" => "You've logged out successfully. See you next time!",
                            "initsuccess" => "Database initialized! All systems go."
                        ];
                        echo "<p class='error-message'>" . $errorMessages[$_GET["error"]] . "</p>";
                    }
                ?>
            </div>
            <form action="phase_1/login.php" method="post" class="login-form">
                <input type="text" name="username" placeholder="Username">
                <input type="password" name="password" placeholder="Password">
                <button type="submit" class="login-button">Log In</button>
            </form>
            <p class="signup-prompt">New here? <a href="signup.php" class="signup-link">Sign up now!</a></p>
            <form action="phase_1/initializedb.php" method="post">
                <button type="submit" class="init-db-button">Initialize Database</button>
            </form>
        </div>
    </div>
</body>
</html>
