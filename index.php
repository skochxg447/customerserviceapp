<?php

// If the user requested logout go back to index.php
if ( isset($_POST['logout']) ) {
    
        // Unset all session variables
    $_SESSION = array();

    // Destroy the session
    session_destroy();

    header('Location: index.php');
    return;
}
?>


<!DOCTYPE html>
<html>
<head>
<title>Customer Service App</title>
<?php require_once "bootstrap.php"; ?>
</head>
<body>
<div class="container">
<h1>Welcome to CSA</h1>
<p><strong>Note:</strong> This app is still in development
</p>
<p>
<a href="#">I'm a Client</a> | <a href="login.php">I'm a Professional</a>
</p>
</div>
</body>

