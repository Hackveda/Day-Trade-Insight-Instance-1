<?php
session_start();

// Check if the user is logged in (optional)
if (isset($_SESSION['broker'])) {
    // Perform any additional tasks before logout (if needed)

    // Destroy the session data
    session_destroy();

    // Redirect to the login page (change "login.php" to your actual login page)
    header("Location: index.php");
    exit();
} else {
    // Redirect to the login page if the user is not logged in
    header("Location: index.php");
    exit();
}
?>
