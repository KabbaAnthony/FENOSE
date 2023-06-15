<?php
session_start(); // Start the session

// Destroy all session data
session_destroy();

// Redirect the user to the login page or any other appropriate page
header("Location: signin.php"); // Replace "login.php" with the desired destination
exit();
?>
