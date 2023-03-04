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
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Customer Service App</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
<div class="container jumbotron login-page">
<h1>Welcome to CSA</h1><br>

<a href="clientlogin.php" class="btn btn-primary">I'm a Client</a> <a href="professionallogin.php" class="btn btn-primary">I'm a Professional</a>

</div>
</body>

