<?php
session_start(); // Start the session

// Check if the user is not logged in
if (!isset($_SESSION['professional_id'])) {
    // Redirect to the login page
    header("Location: professionallogin.php");
    exit();
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

$db = new SQLite3('db/clientlist.db');
$client_id = $_GET['id'];

// If the form was submitted, update the client record
if (isset($_POST['save'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $time_before_greeting = $_POST['time_before_greeting'];
    $server_formality = $_POST['server_formality'];
    $jokes = $_POST['jokes'];
    $server_frequency = $_POST['server_frequency'];

    // Prepare SQL statement to update client record
    $stmt = $db->prepare("UPDATE clients SET name = :name, email = :email, phone = :phone, time_before_greeting = :time_before_greeting, server_formality = :server_formality, jokes = :jokes, server_frequency = :server_frequency WHERE id = :client_id");
    $stmt->bindValue(':name', $name, SQLITE3_TEXT);
    $stmt->bindValue(':email', $email, SQLITE3_TEXT);
    $stmt->bindValue(':phone', $phone, SQLITE3_TEXT);
    $stmt->bindValue(':time_before_greeting', $time_before_greeting, SQLITE3_INTEGER);
    $stmt->bindValue(':server_formality', $server_formality, SQLITE3_INTEGER);
    $stmt->bindValue(':jokes', $jokes, SQLITE3_INTEGER);
    $stmt->bindValue(':server_frequency', $server_frequency, SQLITE3_INTEGER);
    $stmt->bindValue(':client_id', $client_id, SQLITE3_INTEGER);
    
    // Execute SQL statement
    $result = $stmt->execute();

    // Redirect to search page
    header("Location: professionalsearch.php?search=$name");
    return;
}

// Prepare SQL statement to select client record
$stmt = $db->prepare("SELECT * FROM clients WHERE id = :client_id");
$stmt->bindValue(':client_id', $client_id, SQLITE3_INTEGER);

// Execute SQL statement
$result = $stmt->execute();

// Fetch client record
$client = $result->fetchArray();

?>


<!DOCTYPE html>
<html>
<head>
    <?php require_once "bootstrap.php"; ?>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="style.css">
    <title>Edit Client</title>
</head>
<body>
    <div class="container">
        <h1>Edit Client</h1>
        <form method="post">
            <input type="hidden" name="client_id" value="<?php echo $client_id; ?>">
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" class="form-control input-small" id="name" name="name" value="<?php echo $client['name']; ?>" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" class="form-control input-small" id="email" name="email" value="<?php echo $client['email']; ?>" required>
            </div>
            <div class="form-group">
                <label for="phone">Phone:</label>
                <input type="text" class="form-control input-small" id="phone" name="phone" value="<?php echo $client['phone']; ?>" required>
            </div>
            <div class="form-group">
                <label for="time_before_greeting">Time before greeting (in minutes):</label>
                <input type="number" class="form-control input-small" id="time_before_greeting" name="time_before_greeting" min="0" max="10"value="<?php echo $client['time_before_greeting']; ?>">
            </div>
            <div class="form-group">
                <label for="server_formality">Server formality:</label>
                <select class="form-control input-small" id="server_formality" name="server_formality">
                    <option value="0" <?php if ($client['server_formality'] == 0) echo "selected"; ?>>--Please Select--</option>
                    <option value="1" <?php if ($client['server_formality'] == 1) echo "selected"; ?>>Very Casual</option>
                    <option value="2" <?php if ($client['server_formality'] == 2) echo "selected"; ?>>Casual</option>
                    <option value="3" <?php if ($client['server_formality'] == 3) echo "selected"; ?>>Formal</option>
                    <option value="4" <?php if ($client['server_formality'] == 4) echo "selected"; ?>>Very Formal</option>
                </select>
            </div>
            <div class="form-group">
                <label for="jokes">Jokes:</label>
                <div class="radio">
                    <label>
                        <input type="radio" name="jokes" value="0" <?php if ($client['jokes'] == 0) echo "checked";?>>No
                    </label>
                </div>
                <div class="radio">
                    <label>
                        <input type="radio" name="jokes" value="1" <?php if ($client['jokes'] == 1) echo "checked";?>>Yes
                    </label>
                </div>
            </div>
            <div class="form-group">
                <label for="server_frequency">Server frequency: how often the server should stop by</label>
                <input type="range" class="form-control-range input-small" id="server_frequency" name="server_frequency" min="0" max="100" value="<?php echo $client['server_frequency']; ?>">
            </div>
            <button type="submit" class="btn btn-primary" name="save">Save</button>
            <a href="professionalsearch.php" class="btn btn-primary">Cancel</a>
        </form>
    </div>
</body>
</html>
