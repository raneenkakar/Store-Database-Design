<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome Aboard!</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <header class="header">
            <h1>🌟 Hello, <?php echo htmlspecialchars($_SESSION["firstName"]); ?>! 🌟</h1>
        </header>
        <main class="main-content">
            <p>Glad to see you in our community! You've successfully signed in.</p>
            <form action="phase_1/logout.php" method="post">
                <button type="submit" class="btn-logout">Sign Out</button>
            </form>
        </main>
    </div>
</body>
</html>
