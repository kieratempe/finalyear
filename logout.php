<?php
// Start session
session_start();

// Clear all session variables
session_unset();

// Destroy the session
session_destroy();

// Redirect to login page
header("Location: login.html");
exit;
?>
