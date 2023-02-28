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

if (isset($_POST['addclient'])) {
    
    header('Location: addclient.php');
    return;
}

$db = new SQLite3('clientlist.db');
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
    <title>CSA Client Search</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Client Search</h1>
        <form method="get">
            <input type="text" name="search" placeholder="Search for clients..." class="form-control" id="search">
            <input type="submit" value="Search" class="btn btn-primary">
        </form>
        <p>
        <?php if (isset($result) && $result !== null && $result->numColumns() > 0): ?>
            <h2>Search Results</h2>
            <table class="result-table">
                <thead>
                    <tr>
                        <th colspan="3">Name</th>
                        <th colspan="3">Email</th>
                        <th colspan="3">Phone</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetchArray()): ?>
                        
                          <tr>
                            <td colspan="3"><?php echo $row['name']; ?></td>
                            <td colspan="3"><?php echo $row['email']; ?></td>
                            <td colspan="3"><?php echo $row['phone']; ?></td>
                          
                            <td><?php echo $row['time_before_greeting']; ?></td>
                            <td><?php echo $row['server_formality']; ?></td>
                            <td><?php echo $row['jokes']; ?></td>
                            <td><?php echo $row['server_frequency']; ?>%</td>
                            <td><a href="edit.php?id=<?php echo $row['id']; ?>" class="btn btn-secondary">Edit</a><a href="delete.php?id=<?php echo $row['id']; ?>" class="btn">Delete</a><br></td>
                          </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php endif; ?>
        </p>
        <form method="post"><br>
            <input type="submit" name="logout" value="Logout" class="btn btn-primary">
            <input type="submit" name="addclient" value="Add Client" class="btn btn-primary">
        </form>
    </div>
</body>
</html>
