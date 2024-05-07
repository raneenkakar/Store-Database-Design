<?php
// Database connection
// This is my phpmyadmin username and password, you will need to change it to your own
// Change database name to your own database name, make sure to download XAMPP Control Panel and start Apache and MySQL
// In order to open phpmyadmin, go to your browser and type in localhost/phpmyadmin
$servername = "localhost";
$username = "root";
$password = "Aileen";
$dbname = "comp440";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Checking  if the connection was successful or not, if not, it will exit the script with an error message.
if ($conn->connect_error) {
    exit("Database connection failed: " . $conn->connect_error);
  }
