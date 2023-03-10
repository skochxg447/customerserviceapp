<?php

// If the user requested logout go back to ../../index.php
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
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Customer Service App</title>
    <link href="../bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="../style.css">
</head>
<body>
    <div class="col-12 col-lg-3"></div>
    <div class="container jumbotron col-12 col-lg-6">
        <h1>Welcome to CSA</h1>
        <br>
        <a href="client/login.php" class="btn btn-primary">I'm a Client</a> <a href="professional/login.php" class="btn btn-primary">I'm a Professional
        </a>

      </div>
    </div>
<div class="col-12 col-lg-3"></div>
</div>
</body>

