<?php
session_start();

// Include the PDOHandler class
require_once 'pdo.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $db_name = $_POST['db_name'];

    // Create an instance of the PDOHandler class
    $pdo = new PDOHandler();

    // Create the database
    $result = $pdo->createDatabase($db_name);
    if (strpos($result, 'successfully') !== false) {
        $_SESSION['db_name'] = $db_name;
        echo $result;
        echo "<br><a href='create_table.php'>Create a Table in this Database</a>";
    } else {
        echo $result;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create a New Database</title>
</head>
<body>
    <h2>Create a New Database in PHPMyAdmin</h2>
    <form action="index.php" method="POST">
        <label for="db_name">Database Name:</label>
        <input type="text" id="db_name" name="db_name" required>
        <button type="submit">Create Database</button>
    </form>
</body>
</html>
