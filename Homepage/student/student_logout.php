<?php
// Start the session
session_start();

// Destroy all session variables
session_unset();

// Destroy the session itself
session_destroy();

// Redirect to the login page (or homepage)
header("Location: student_login.html"); // Change this to your actual login page
exit();
?>
