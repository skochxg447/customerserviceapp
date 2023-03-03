<?php
session_start(); // start the PHP session

// check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// connect to the SQLite database
$db = new SQLite3('clientuser.db');

// prepare a SQL statement to select the user with the given id
$stmt = $db->prepare('SELECT * FROM users WHERE id = :id');

// bind the parameters to the statement
$stmt->bindParam(':id', $_SESSION['user_id']);

// execute the statement
$result = $stmt->execute();

// fetch the user's information
$user = $result->fetchArray(SQLITE3_ASSOC);

// check if user data was found
if (!$user) {
  header('Location: login.php');
  exit();
}

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
    <title>CSA Add Client</title>
    <?php require_once "bootstrap.php"; ?>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
   <div class="container">
   <div class="page-header">
      <h1>Welcome <?= ucwords($name) ?></h1>
   </div>
   <div class="container">
      <form method="post">
         <input type="hidden" name="user_id" value="<?php echo $_SESSION['user_id']; ?>">
         <div class="form-group">
            <label for="name">Name:</label>
            <input type="text" class="form-control input-small" id="name" name="name" value="<?php echo $user['name']; ?>" required>
         </div>
         <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" class="form-control input-small" id="email" name="email" value="<?php echo $user['email']; ?>" required>
         </div>
         <div class="form-group">
            <label for="phone">Phone:</label>
            <input type="text" class="form-control input-small" id="phone" name="phone" value="<?= isset($user['phone']) ? $user['phone'] : '' ?>" required>
         </div>
         <div class="form-group">
            <label for="time_before_greeting">Time before greeting (in minutes):</label>
            <input type="number" class="form-control input-small" id="time_before_greeting" name="time_before_greeting" min="0" max="10"value="<?php echo $user['time_before_greeting']; ?>">
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
         <button type="submit" class="btn btn-primary" name="save">Save</button>
      </form>
   </div>
</body>
</html>