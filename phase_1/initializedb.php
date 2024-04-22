<?php

// Include the database connection file
require ("dbconnect.php");

// Create a new connection to the database
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Temporarily disable foreign key checks
$conn->query("SET FOREIGN_KEY_CHECKS = 0;");

$tables = ["review", "favorite", "item", "user", "itemCategory"];
foreach ($tables as $table) {
    $sql = "DROP TABLE IF EXISTS $table;";
    if ($conn->query($sql) === false) {
        echo "Error dropping $table table: " . $conn->error;
    }
}

// Re-enable foreign key checks
$conn->query("SET FOREIGN_KEY_CHECKS = 1;");

// SQL statement to create the 'user' table
$userTable = "CREATE TABLE IF NOT EXISTS user (
    username VARCHAR(255) PRIMARY KEY,
    password VARCHAR(255) NOT NULL,
    firstName TEXT NOT NULL,
    lastName TEXT NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE);";

// Execute the SQL statement and check if it was successful
if ($conn->query($userTable) === false) {
    // If there was an error, exit the script and output the error
    exit("Error creating user table: " . $conn->error);
}

// SQL statement to create the 'itemCategory' table
$itemCategoryTable = "CREATE TABLE IF NOT EXISTS itemCategory (
    category VARCHAR(64) NOT NULL PRIMARY KEY);";

// Execute the SQL statement and check if it was successful
if ($conn->query($itemCategoryTable) === false) {
    // If there was an error, exit the script and output the error
    exit("Error creating itemCategory table: " . $conn->error);
}

// SQL statement to create the 'item' table
$itemTable = "CREATE TABLE IF NOT EXISTS item (
    itemId INT(10) AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(64) NOT NULL,
    description TEXT(255),
    category VARCHAR(64) NOT NULL,
    price DECIMAL(10,2),
    postDate DATE NOT NULL DEFAULT (CURRENT_DATE),
    postedBy VARCHAR(255) NOT NULL,
    FOREIGN KEY (postedBy) REFERENCES user(username),
    FOREIGN KEY (category) REFERENCES itemCategory(category));";

// Execute the SQL statement and check if it was successful
if ($conn->query($itemTable) === false) {
    // If there was an error, exit the script and output the error
    exit("Error creating item table: " . $conn->error);
}

// SQL statement to create the 'favorite' table
$favoriteTable = "CREATE TABLE IF NOT EXISTS favorite (
    buyer VARCHAR(255) NOT NULL,
    seller VARCHAR(255) NOT NULL,
    FOREIGN KEY (buyer) REFERENCES user(username),
    FOREIGN KEY (seller) REFERENCES user(username));";

// Execute the SQL statement and check if it was successful
if ($conn->query($favoriteTable) === false) {
    // If there was an error, exit the script and output the error
    exit("Error creating favorite table: " . $conn->error);
}

// SQL statement to create the 'review' table
$reviewTable = "CREATE TABLE IF NOT EXISTS review (
    remark TEXT NOT NULL,
    score VARCHAR(64) NOT NULL,
    reviewDate DATE NOT NULL,
    writtenBy VARCHAR(255) NOT NULL,
    forItem INT(10) NOT NULL,
    FOREIGN KEY (writtenBy) REFERENCES user(username),
    FOREIGN KEY (forItem) REFERENCES item(itemId));";

// Execute the SQL statement and check if it was successful
if ($conn->query($reviewTable) === false) {
    // If there was an error, exit the script and output the error
    exit("Error creating review table: " . $conn->error);
}

// Array of SQL queries to insert data into the tables
$queries = array(
    // Insert users into the 'user' table
    "INSERT INTO user(username, password, firstName, lastName, email) VALUES
        ('Ali1', 'password1', 'Ali', 'Smith', 'ali.one@example.com'),
        ('Raneen2', 'password2', 'Raneen', 'Johnson', 'raneen.two@example.com'),
        ('Sheema3', 'password3', 'Sheema', 'Williams', 'sheema.three@example.com'),
        ('Jane4', 'password4', 'Jane', 'Brown', 'jane.four@example.com'),
        ('Mathew5', 'password5', 'Mathew', 'Jones', 'mathew.five@example.com'),
        ('Alice6', 'password6', 'Alice', 'Davis', 'alice.six@example.com')",

    // Insert categories into the 'itemCategory' table
    "INSERT INTO itemCategory(category) VALUES
        ('Electronics'),
        ('Furniture'),
        ('Sporting Goods')",

    // Insert items into the 'item' table
    "INSERT INTO item(title, description, category, price, postDate, postedBy) VALUES
        ('iPhone 15 Pro', 'Latest iPhone with improved camera and battery life', 'Electronics', '500.00', CURDATE(), 'Ali1'),
        ('AirPods Pro', 'High-quality wireless earbuds with active noise cancellation', 'Electronics', '249.00', CURDATE(), 'Raneen2'),
        ('iPad', 'Powerful tablet with a vibrant display and long battery life', 'Electronics', '329.00', CURDATE(), 'Sheema3'),
        ('Coffee Table', 'Modern coffee table made from solid wood', 'Furniture', '150.00', CURDATE(), 'Jane4'),
        ('Mountain Bike', 'Mountain bike with 21 speeds and dual suspension', 'Sporting Goods', '350.00', CURDATE(), 'Mathew5'),
        ('OLED TV', '55 inch OLED TV with 4K resolution and high dynamic range', 'Electronics', '1200.00', CURDATE(), 'Alice6')",

    // Insert reviews into the 'review' table
    "INSERT INTO review(remark, score, reviewDate, writtenBy, forItem) VALUES
        ('Great phone. The battery lasts all day!', 'Excellent', '2024-01-15', 'Jane4', '1'),
        ('These headphones are fantastic! It blocks out all noise.', 'Excellent', '2024-02-10', 'Ali1', '2'),
        ('It is easy to use and looks great on my kitchen counters.', 'Excellent', '2024-03-05', 'Ali1', '3'),
        ('Amazing quality leather!', 'Excellent', '2024-01-25', 'Jane4', '6'),
        ('Perfect for every day because it goes with anything.', 'Excellent', '2024-02-20', 'Sheema3', '6'),
        ('Too loud and I wish the bowl was bigger.', 'Poor', '2024-03-15', 'Sheema3', '3'), 
        ('Love it! I received many compliments.', 'Excellent', '2024-01-30', 'Mathew5', '6'),
        ('Broke after only 2 months. Avoid!', 'Poor', '2024-02-25', 'Mathew5', '4'),
        ('The seat is so uncomfortable and needs more cushion.', 'Poor', '2024-03-20', 'Raneen2', '4'),
        ('The face was cracked when I received it', 'Poor', '2024-04-04', 'Raneen2', '5')"
);

// Loop through each query in the array
foreach ($queries as $query) {
    // Execute the query and check if it was successful
    if ($conn->query($query) === false) {
        // If there was an error, exit the script and output the error
        exit("Error executing query: " . $conn->error);
    }
}

// Redirect to the index page with a success message
header("Location: ../index.php?error=initsuccess");
exit();
?>
