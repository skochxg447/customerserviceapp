<?php
session_start(); // start the PHP session

// check if the user is logged in
if (!isset($_SESSION['client_id'])) {
    header('Location: client_login.php');
    exit();
}

$success = null;

// If the form was submitted, update the client record
if (isset($_POST['save'])) {
    // get form data
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $time_before_greeting = $_POST['time_before_greeting'];
    $server_formality = $_POST['server_formality'];
    $jokes = $_POST['jokes'];
    $server_frequency = $_POST['server_frequency'];

    $db = new SQLite3('db/client_user.db');

    // Prepare SQL statement to update client record
    $stmt = $db->prepare("UPDATE client_users SET phone = :phone, time_before_greeting = :time_before_greeting, server_formality = :server_formality, jokes = :jokes, server_frequency = :server_frequency WHERE id = :id");
    $stmt->bindValue(':name', $name, SQLITE3_TEXT);
    $stmt->bindValue(':email', $email, SQLITE3_TEXT);
    $stmt->bindValue(':phone', $phone, SQLITE3_TEXT);
    $stmt->bindValue(':time_before_greeting', $time_before_greeting, SQLITE3_INTEGER);
    $stmt->bindValue(':server_formality', $server_formality, SQLITE3_INTEGER);
    $stmt->bindValue(':jokes', $jokes, SQLITE3_INTEGER);
    $stmt->bindValue(':server_frequency', $server_frequency, SQLITE3_INTEGER);
    $stmt->bindValue(':id', $_SESSION['client_id'], SQLITE3_INTEGER);
    
    // Execute SQL statement
    $result = $stmt->execute();
    $success = 'Update successful';

}


// If the user requested logout go back to index.php
if (isset($_POST['logout'])) {
    
    // Unset all session variables
    session_unset();

    // Destroy the session
    session_destroy();

    header('Location: index.php');
    return;
}

// connect to the SQLite database
$db = new SQLite3('db/client_user.db');

// prepare a SQL statement to select the user with the given id
$stmt = $db->prepare('SELECT * FROM client_users WHERE id = :id');

// bind the parameters to the statement
$stmt->bindParam(':id', $_SESSION['client_id']);

// execute the statement
$result = $stmt->execute();

// fetch the user's information
$user = $result->fetchArray(SQLITE3_ASSOC);

// assign user data to variables
$name = isset($user['name']) ? $user['name'] : '';
$email = isset($user['email']) ? $user['email'] : '';
$phone = isset($user['phone']) ? $user['phone'] : '';
$time_before_greeting = isset($user['time_before_greeting']) ? $user['time_before_greeting'] : '';
$server_formality = isset($user['server_formality']) ? $user['server_formality'] : '';
$jokes = isset($user['jokes']) ? $user['jokes'] : '';
$server_frequency = isset($user['server_frequency']) ? $user['server_frequency'] : '';


?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CSA Dashboard</title>
    <?php require_once "bootstrap.php"; ?>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
   <div class="container">
   <div class="page-header">
      <h1>Welcome <?=ucwords($name)?></h1>
   </div>
   <?php if ($success != null): ?>
     <div><?= $success?></div>
   <?php endif; ?>
   <div class="container">
      <form method="post">
         <input type="hidden" name="client_id" value="<?php echo $_SESSION['client_id']; ?>">
         <div class="form-group">
            <label for="name">Name:</label>
            <input type="text" class="form-control input-small" id="name" name="name" value="<?= isset($user['name']) ? $user['name'] : '' ?>" required>
         </div>
         <div class="form-group">
            <label for="email">Email:</label>
            <input type="text" class="form-control input-small" id="email" name="email" value="<?= isset($user['email']) ? $user['email'] : '' ?>" required>
         </div>
         <div class="form-group">
            <label for="phone">Phone:</label>
            <input type="text" class="form-control input-small" id="phone" name="phone" value="<?= isset($user['phone']) ? $user['phone'] : '' ?>" required>
         </div>
         <div class="form-group">
            <label for="time_before_greeting">Time before greeting (in minutes):</label>
            <input type="number" class="form-control input-small" id="time_before_greeting" name="time_before_greeting" min="0" max="10" value="<?php echo isset($time_before_greeting) ? $time_before_greeting : ''; ?>">
         </div>
         <div class="form-group">
            <label for="server_formality">Server formality:</label>
            <select class="form-control input-small" id="server_formality" name="server_formality">
               <option value="0" <?php if (isset($user['server_formality']) == 0) echo "selected"; ?>>--Please Select--</option>
               <option value="1" <?php if (isset($user['server_formality']) == 1) echo "selected"; ?>>Very Casual</option>
               <option value="2" <?php if (isset($user['server_formality']) == 2) echo "selected"; ?>>Casual</option>
               <option value="3" <?php if (isset($user['server_formality']) == 3) echo "selected"; ?>>Formal</option>
               <option value="4" <?php if (isset($user['server_formality']) == 4) echo "selected"; ?>>Very Formal</option>
            </select>
         </div>
         <div class="form-group">
            <label for="jokes">Jokes:</label>
            <div class="radio">
               <label>
               <input type="radio" name="jokes" value="0" <?php if (isset($user['jokes']) && $user['jokes'] == 0) echo "checked"; ?>>No
               </label>
            </div>
            <div class="radio">
               <label>
               <input type="radio" name="jokes" value="1" <?php if (isset($user['jokes']) && $user['jokes'] == 1) echo "checked"; ?>>Yes
               </label>
            </div>
         </div>
         <div class="form-group">
            <label for="server_frequency">Server frequency: how often the server should stop by</label>
            <input type="range" class="form-control-range input-small" id="server_frequency" name="server_frequency" min="0" max="100" value="<?php echo $user['server_frequency']; ?>">
         </div>
         <input type="submit" name="save" value="Save" class="btn btn-primary">
         <input type="submit" name="logout" value="Logout" class="btn btn-primary">
      </form>
   </div>
</body>
</html>