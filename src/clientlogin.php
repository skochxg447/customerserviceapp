<?php session_start(); // start the PHP session

$error = null;

if (isset($_POST['submit'])) { // check if the login form has been submitted

  // get the email and password from the form
  $email = $_POST['email'];
  $password = $_POST['password'];

  // connect to the SQLite database
  $db = new SQLite3('db/clientuser.db');

  // prepare a SQL statement to select the user with the given email
  $stmt = $db->prepare('SELECT * FROM users WHERE email = :email');

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
    header('Location: clientdashboard.php');
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
  <?= $error ?><br>
<?php endif; ?>
        <input type="submit" name="submit" value="Login" class="btn btn-primary">
      </form>
    </div>
    <a id="account" href="clientprofessionalcreateaccount.php">Create New Account</a>
  </div>
</body>
</html>


<!-- <!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="style.css">
    <title>CSA Client Login Page</title>
</head>
  <body>
    <div class="container">
    <h1>Client Login</h1><br>
    <div class="login">
    <form method="post" action="professionallogin.php">
      <div class="form-group">
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" class="form-control input-small" required>
      </div>
      <br>
      <div class="form-group">
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" class="form-control input-small" required>
      </div>
      <div>
        <br>
        <a href="index.php" class="btn btn-primary">Back</a>
        <input type="submit" value="Login" class="btn btn-primary">
        <a href="clientprofessionaledit.php">test link</a>
      </div>
    </form>
    <a id="account" href="clientprofessionalcreateaccount.php">Create New Account</a>
    </div>
  </body>
</html> -->
