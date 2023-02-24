<?php
session_start(); // Start the session

// Check if the user is already logged in
if (isset($_SESSION['user_id'])) {
    // Redirect to the dashboard page
    header("Location: search.php");
    exit();
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the form data
    $email = $_POST["email"];
    $password = $_POST["password"];

    // Connect to the database
    $db = new SQLite3('professionaluser.db');

    // Prepare the SQL statement to select the user with the given email
    $stmt = $db->prepare("SELECT * FROM users WHERE email = :email");

    // Bind the email parameter to the SQL statement
    $stmt->bindValue(':email', $email, SQLITE3_TEXT);

    // Execute the SQL statement
    $result = $stmt->execute();

    // Check if the user exists and the password matches
    if ($row = $result->fetchArray()) {
        // Verify the password
        if (password_verify($password, $row['password'])) {
            // Authentication successful, store the user ID in the session
            $_SESSION['user_id'] = $row['id'];
            // Redirect to the dashboard page
            header("Location: search.php");
            exit();
        } else {
            // Invalid password, set an error message
            $passwordErr = "Invalid password";
        }
    } else {
        // User not found, set an error message
        $emailErr = "User not found";
    }

    // Close the database connection
    $db->close();
}

// Fall through into the View
?>
<!DOCTYPE html>
<html>
<head>
    <?php require_once "bootstrap.php"; ?>
    <title>CSA Login Page</title>
</head>
  <body>
    <div class="container">
    <h2>Login</h2>
    <form method="post" action="login.php">
      <label for="email">Email:</label>
      <input type="email" id="email" name="email" required><br><br>
      <label for="password">Password:</label>
      <input type="password" id="password" name="password" required><br><br>
      <?php if (!empty($emailErr)) { ?>
        <p style="color: red;"><?php echo $emailErr; ?></p>
      <?php } ?>
      <?php if (!empty($passwordErr)) { ?>
        <p style="color: red;"><?php echo $passwordErr; ?></p>
      <?php } ?>
      <a href="index.php" class="btn btn-primary">Back</a>
      <input type="submit" value="Login" class="btn btn-primary">
    </form><br>
    <a href="account.php">Create New Account</a>
    </div>
  </body>
</html>
