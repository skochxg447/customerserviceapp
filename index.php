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
<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
<div class="container jumbotron">
<h1>Welcome to CSA</h1><br>

<a href="#" class="btn btn-primary">I'm a Client</a> <a href="login.php" class="btn btn-primary">I'm a Professional</a>

</div>
</body>

