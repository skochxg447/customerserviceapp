<?php
session_start(); // Start the session

// Check if the user is not logged in
if (!isset($_SESSION['professional_id'])) {
    // Redirect to the login page
    header("Location: login.php");
    exit();
}

// If the user requested logout go back to ../../index.php
if (isset($_POST['logout'])) {
    // Unset all session variables
    session_unset();

    // Destroy the session
    session_destroy();

    header('Location: ../../index.php');
    return;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the data from the form
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $time_before_greeting = isset($_POST['time_before_greeting']) ? $_POST['time_before_greeting'] : 0;
    $server_formality = isset($_POST['server_formality']) ? $_POST['server_formality'] : 0;
    $jokes = isset($_POST['jokes']) ? $_POST['jokes'] : 0;
    $server_frequency = isset($_POST['server_frequency']) ? $_POST['server_frequency'] : 0;

    // Open SQLite database connection
    $db = new SQLite3('../db/client_list.db');

    // Create the clients table if it doesn't exist yet
    $db->exec('CREATE TABLE IF NOT EXISTS client_info (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        name TEXT,
        email TEXT,
        phone TEXT,
        time_before_greeting INTEGER,
        server_formality INTEGER,
        jokes INTEGER,
        server_frequency INTEGER
    )');

    // Insert the data into the database
    $stmt = $db->prepare('INSERT INTO client_info (name, email, phone, time_before_greeting, server_formality, jokes, server_frequency) VALUES (:name, :email, :phone, :time_before_greeting, :server_formality, :jokes, :server_frequency)');
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
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CSA Add Client</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="../style.css">
</head>
<body>
    <div class="container">
        <div class="page-header">
            <h1>Add Client</h1>
        </div>
        <form method="post">
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" name="name" class="form-control input-small" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" name="email" class="form-control input-small" required>
            </div>
            <div class="form-group">
                <label for="phone">Phone:</label>
                <input type="text" name="phone" class="form-control input-small" required>
            </div>
            <div class="form-group">
                <label for="time_before_greeting">Time Before Greeting (in minutes):</label>
                <input type="number" name="time_before_greeting" min="0" max="10" class="form-control input-small">
            </div>
            <div class="form-group">
                <label for="server-formality">Server Formality:</label>
                <select name="server_formality" class="form-control input-small">
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
                <label for="server_frequency">Server Frequency:</label>
                <input type="range" class="form-control-range input-small" name="server_frequency" min="1" max="200" value="100" class="form-control">
            </div>
            <input type="submit" value="Submit" class="btn btn-primary">
            <a href="search.php" class="btn btn-primary">Cancel</a>
        </form>
    </div>
</body>
</html>

