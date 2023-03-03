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

if (isset($_POST['addclient'])) {
    
    header('Location: professionaladdclient.php');
    return;
}

$db = new SQLite3('db/clientlist.db');

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

// Check if delete form was submitted
if (isset($_POST['delete'])) {
    $id = $_POST['id'];
    $stmt = $db->prepare('DELETE FROM clients WHERE id=:id');
    $stmt->bindValue(':id', $id, SQLITE3_INTEGER);
    
    // Execute SQL statement
    $result = $stmt->execute();

    // Redirect to the search page with success message
    header("Location: professionalsearch.php?success=Client+deleted");
    exit();
}

// Check if search form was submitted
if (isset($_GET['search'])) {
    $search_term = $_GET['search'];
    
    // Prepare SQL statement to search for clients
    $stmt = $db->prepare("SELECT * FROM clients WHERE name LIKE :search_term OR email LIKE :search_term OR phone LIKE :search_term");
    $stmt->bindValue(':search_term', "%$search_term%", SQLITE3_TEXT);

    
    // Execute SQL statement
    $result = $stmt->execute();
} else {
    $result = null;
}

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CSA Client Search</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Client Search</h1>
        <form method="get">
            <input type="text" name="search" placeholder="Search for clients..." class="form-control input-small search" id="search">
            <input type="submit" value="Search" class="btn btn-primary">
        </form>
        <p>
            <?php if (isset($result) && $result !== null && $result->numColumns() > 0): 'No Results Found' ?>
                <h2>Search Results</h2>
                <div class="result-table-container">
                <table class="result-table">
                    <thead>
                        <tr>
                            <th onclick="sortTable(0)">Name</th>
                            <th onclick="sortTable(1)">Email</th>
                            <th onclick="sortTable(2)">Phone</th>
                            <th onclick="sortTable(3)">Time Before Greeting</th>
                            <th onclick="sortTable(4)">Server Formality</th>
                            <th onclick="sortTable(5)">Jokes</th>
                            <th onclick="sortTable(6)">Server Frequency</th>
                            <td></td>
                            <td></td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $result->fetchArray()): ?>
                            <tr>
                                <td><?php echo $row['name']; ?></td>
                                <td><?php echo $row['email']; ?></td>
                                <td><?php echo $row['phone']; ?></td>
                                <td><?php echo $row['time_before_greeting'] > 0 ? $row['time_before_greeting'] . 'min' : '-'; ?></td>
                                <td><?php echo $row['server_formality'] == 1 ? "Very Casual" : ($row['server_formality'] == 2 ? "Casual" : ($row['server_formality'] == 3 ? "Formal" : ($row['server_formality'] == 4 ? "Very Formal" : "-"))); ?></td>
                                <td><?php echo $row['jokes'] == 0 ? "No Jokes" : ($row['jokes'] == 1 ? "Jokes" : "-"); ?></td>
                                <td><?php echo $row['server_frequency']; ?>%</td>
                                <td><a href="professionaledit.php?id=<?php echo $row['id']; ?>" class="btn btn-secondary">Edit</a></td>
                                <td>
                                    <form method="POST" onsubmit="return confirm('Are you sure you want to delete this client?');">
                                        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                        <input type="submit" name="delete" value="Delete" class="btn btn-danger">
                                    </form>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
                </div>
            <?php endif; ?>
            <script src="server.js"></script>
        </p>
        <form method="post"><br>
            <input type="submit" name="logout" value="Logout" class="btn btn-primary">
            <input type="submit" name="addclient" value="Add Client" class="btn btn-primary">
        </form>
    </div>
</body>
</html>