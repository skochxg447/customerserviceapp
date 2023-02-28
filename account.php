<?php
// define variables and set to empty values
$nameErr = $emailErr = $passwordErr = "";
$name = $email = $password = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST["name"])) {
        $nameErr = "Name is required";
    } else {
        $name = test_input($_POST["name"]);
        // check if name only contains letters and whitespace
        if (!preg_match("/^[a-zA-Z ]*$/",$name)) {
            $nameErr = "Only letters and white space allowed";
        }
    }

    if (empty($_POST["email"])) {
        $emailErr = "Email is required";
    } else {
        $email = test_input($_POST["email"]);
        // check if email address is well-formed
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $emailErr = "Invalid email format";
        }
    }

    if (empty($_POST["password"])) {
        $passwordErr = "Password is required";
    } else {
        $password = test_input($_POST["password"]);
        // check if password is strong enough
        if (strlen($password) < 8) {
            $passwordErr = "Password should be at least 8 characters long";
        }
    }

    if ($nameErr == "" && $emailErr == "" && $passwordErr == "") {
        // Insert data into database
        $db = new SQLite3('professionaluser.db');

        // Create users table if not exists
        $db->exec("CREATE TABLE IF NOT EXISTS users (
            id INTEGER PRIMARY KEY,
            name TEXT,
            email TEXT,
            password TEXT
        )");

        // Check if email already exists in the database
        $sql = "SELECT * FROM users WHERE email = '$email'";
        $result = $db->query($sql);
        if ($result->fetchArray()) {
            $emailErr = "Email already exists";
        } else {
            // Hash password before storing
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            $sql = "INSERT INTO users (name, email, password)
            VALUES ('$name', '$email', '$hashed_password')";

            if ($db->exec($sql)) {
                echo "<div class='container'>New record created successfully</div>";
            } else {
                echo "Error: " . $sql . "<br>" . $db->lastErrorMsg();
            }
        }

        $db->close();
    }
}

function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CSA New Account</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <div class="container">
    <h2>Create Account</h2>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
      <p>
        <label for="email">Name:</label>
        <input type="email" id="name" name="name" class="form-control" required><br><br>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" class="form-control" required><br><br>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" class="form-control" required><br><br>
      </p>
        <br><br>
      <a href="login.php" class="btn btn-primary">Back</a>
      <input type="submit" value="Submit" class="btn btn-primary">
    </form>
    </div>

</body>
</html>