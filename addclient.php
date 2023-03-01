<?php
session_start(); // Start the session

// Check if the user is not logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to the login page
    header("Location: login.php");
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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the data from the form
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $time_before_greeting = isset($_POST['time_before_greeting']) ? $_POST['time_before_greeting'] : 0;
    $server_formality = isset($_POST['server_formality']) ? $_POST['server_formality'] : 0;
    $jokes = isset($_POST['jokes']) ? $_POST['jokes'] : -1;
    $server_frequency = isset($_POST['server_frequency']) ? $_POST['server_frequency'] : 0;

    // Open SQLite database connection
    $db = new SQLite3('clientlist.db');

    // Create the clients table if it doesn't exist yet
    $db->exec('CREATE TABLE IF NOT EXISTS clients (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        name TEXT NOT NULL,
        email TEXT NOT NULL,
        phone TEXT NOT NULL,
        time_before_greeting INTEGER NOT NULL,
        server_formality INTEGER NOT NULL,
        jokes INTEGER NOT NULL,
        server_frequency INTEGER NOT NULL
    )');

    // Insert the data into the database
    $stmt = $db->prepare('INSERT INTO clients (name, email, phone, time_before_greeting, server_formality, jokes, server_frequency) VALUES (:name, :email, :phone, :time_before_greeting, :server_formality, :jokes, :server_frequency)');
    $stmt->bindValue(':name', $name, SQLITE3_TEXT);
    $stmt->bindValue(':email', $email, SQLITE3_TEXT);
    $stmt->bindValue(':phone', $phone, SQLITE3_TEXT);
    $stmt->bindValue(':time_before_greeting', $time_before_greeting, SQLITE3_INTEGER);
    $stmt->bindValue(':server_formality', $server_formality, SQLITE3_INTEGER);
    $stmt->bindValue(':jokes', $jokes, SQLITE3_INTEGER);
    $stmt->bindValue(':server_frequency', $server_frequency, SQLITE3_INTEGER);
    $stmt->execute();

    // Redirect the user to the client list page
    header("Location: search.php?search=$name");
    return;
}
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
            <h1>Add Client</h1>
        </div>
        <form method="post">
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" name="name" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="phone">Phone:</label>
                <input type="text" name="phone" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="time_before_greeting">Time Before Greeting:</label>
                <input type="number" name="time_before_greeting" min="0" max="10" class="form-control">
            </div>
            <div class="form-group">
                <label for="server-formality">Server Formality:</label>
                <select name="server_formality" class="form-control">
                    <option value="0">--Please Select--</option>
                    <option value="1">Very Casual</option>
                    <option value="2">Casual</option>
                    <option value="3">Formal</option>
                    <option value="4">Very Formal</option>
                </select>
            </div>
            <div class="form-group">
                <label for="jokes">Jokes:</label>
                <div class="radio">
                    <label>
                        <input type="radio" name="jokes" value="0">No
                    </label>
                </div>
                <div class="radio">
                    <label>
                        <input type="radio" name="jokes" value="1">Yes
                    </label>
                </div>
            </div>
            <div class="form-group">
                <label for="server_frequency">Server Frequency: (how often the server should stop by)</label>
                <input type="range" class="form-control-range" name="server_frequency" min="1" max="100" value="50" class="form-control">
            </div>
            <input type="submit" value="Submit" class="btn btn-primary">
            <a href="search.php" class="btn btn-primary">Cancel</a>
        </form>
    </div>
</body>
</html>

