<?php

session_start();

// Remove all session data
$_SESSION = [];

// Destroy the session
session_destroy();

// Go back to login page
header('Location: ../../login-signup/login.php');
exit;