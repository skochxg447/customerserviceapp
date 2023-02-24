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
    <?php require_once "bootstrap.php"; ?>
</head>
<body>
    <div class="container">
        <h1>Client Search</h1>
        <form method="get">
            <input type="text" name="search" placeholder="Search for clients...">
            <input type="submit" value="Search" class="btn btn-primary">
        </form>
        <?php if (isset($result) && $result !== null && $result->numColumns() > 0): ?>
            <h2>Search Results</h2>
            <table class="table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetchArray()): ?>
                        <tr>
                            <td><?php echo $row['name']; ?></td>
                            <td><?php echo $row['email']; ?></td>
                            <td><?php echo $row['phone']; ?></td>
                            <td><a href="edit.php?id=<?php echo $row['id']; ?>" class="btn btn-secondary">Edit</a></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php endif; ?>
        <form method="post"><br>
            <input type="submit" name="logout" value="Logout" class="btn btn-primary">
            <input type="submit" name="addclient" value="Add Client" class="btn btn-primary">
        </form>
    </div>
</body>
</html>
