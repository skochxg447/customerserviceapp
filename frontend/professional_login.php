<?php
session_start(); // Start the session

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the form data
    $email = $_POST["email"];
    $password = $_POST["password"];

    // Connect to the database
    $db = new SQLite3('db/professional_user.db');

    // Create the professional_users table if it doesn't exist
    $db->exec('CREATE TABLE IF NOT EXISTS professional_users (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        name TEXT,
        email TEXT UNIQUE,
        password TEXT
    )');

    // Prepare the SQL statement to select the user with the given email
    $stmt = $db->prepare("SELECT * FROM professional_users WHERE email = :email");

    // Bind the email parameter to the SQL statement
    $stmt->bindValue(':email', $email, SQLITE3_TEXT);

    // Execute the SQL statement
    $result = $stmt->execute();

    // Check if the user exists and the password matches
    if ($row = $result->fetchArray()) {
        // Verify the password
        if (password_verify($password, $row['password'])) {
            // Authentication successful, store the user ID in the session
            $_SESSION['professional_id'] = $row['id'];
            
            // Redirect to the dashboard page
            header("Location: professional_search.php");
            exit();
        } else {
            // Invalid password, set an error message
            $error= "Invalid email or password";
        }
    } else {
        // User not found, set an error message
        $error = "Invalid email or password";
    }

    // Close the database connection
    $db->close();
}

// Fall through into the View
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="style.css">
    <title>CSA Professional Login Page</title>
</head>
  <body>
    <div class="container">
    <h1>Professional Login</h1><br>
    <div class="login">
    <form method="post" action="professional_login.php">
      <div class="form-group">
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" class="form-control input-small" required>
      </div>
      <br>
      <div class="form-group">
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" class="form-control input-small" required>
      </div>
    <?php if ($error != null): ?>
      <br><div style='color: red;'><?= $error?></div>
    <?php endif; ?>
      <div>
        <br>
        <a href="index.php" class="btn btn-primary">Back</a>
        <input type="submit" value="Login" class="btn btn-primary">
      </div>
    </form>
    <a id="account" href="professional_create_account.php">Create New Account</a>
    </div>
  </body>
</html>
