<?php session_start(); // start the PHP session

$error = null;

if (isset($_POST['submit'])) { // check if the login form has been submitted

  // get the email and password from the form
  $email = $_POST['email'];
  $password = $_POST['password'];

  // connect to the SQLite database
  $db = new SQLite3('db/client_user.db');  

  // Create users table if not exists
  $db->exec("CREATE TABLE IF NOT EXISTS client_users (
      id INTEGER PRIMARY KEY,
      name TEXT,
      email TEXT,
      password TEXT,
      phone TEXT,
      time_before_greeting INTEGER,
      server_formality INTEGER,
      jokes INTEGER,
      server_frequency INTEGER

      )");

  // prepare a SQL statement to select the user with the given email
  $stmt = $db->prepare('SELECT * FROM client_users WHERE email = :email');

  // bind the parameters to the statement
  $stmt->bindParam(':email', $email);

  // execute the statement
  $result = $stmt->execute();

  // fetch the first row of the result set
  $user = $result->fetchArray(SQLITE3_ASSOC);

  if ($user && password_verify($password, $user['password'])) { // if a user was found with the given email and verified the password

    // set session variables for the user
    $_SESSION['client_id'] = $user['id'];

    // redirect to the clientprofessionaledit.php page
    header('Location: client_dashboard.php');
    exit();

  } else { // if no user was found with the given email or password did not verify

    // display an error message
    $error = 'Invalid email or password';

  }

}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Client Login</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
  <div class="container">
    <h1>Client Login</h1>
    <div class="login">
      <form method="post">
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" name="email" id="email" class="form-control input-small" required>
        </div>
        <br>
        <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" name="password" id="password" class="form-control input-small" required>
        </div>
        <br>
<?php if ($error != null): ?>
  <div style='color: red;'><?= $error?></div><br>
<?php endif; ?>
        <a href="index.php" class="btn btn-primary">Back</a>
        <input type="submit" name="submit" value="Login" class="btn btn-primary">
      </form>
    </div>
    <a id="account" href="client_create_account.php">Create New Account</a>
  </div>
</body>
</html>