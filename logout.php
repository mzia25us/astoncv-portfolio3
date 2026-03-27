<?php
session_start();
include 'csrf.php';

//Ensure the request method is POST to prevent direct URL access. 
if ($_SERVER['REQUEST_METHOD'] !== "POST") {
    header("Location: index.php");
    exit();
}

//Retrieve the CSRF token submitted from the form.
$csrf_token = $_POST['csrf_token'] ?? '';

//Verify the CSRF token submitted from the form.
if (!verify_csrf_token($csrf_token)) {
    die("Invalid request.");
}

//Clear all session variables and destroy the session to log user out. 
session_unset();
session_destroy();

//Redirect to the homepage after logout. 
header("Location: index.php");
exit();
?>
