<?php

// Initialize the session
session_start();

// Clear all session variables
session_unset();

// Destroy the session
session_destroy();

// Redirect to the index page with a logout indication
header("Location: ../index.php?error=loggedout");
exit();

?>
