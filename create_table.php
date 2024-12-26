<?php
session_start();

if (!isset($_SESSION['db_name'])) {
    echo "No database selected. Please create a database first.";
    echo "<br><a href='index.php'>Create a Database</a>";
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $table_name = $_POST['table_name'];
    $num_columns = $_POST['num_columns'];

    $_SESSION['table_name'] = $table_name;
    $_SESSION['num_columns'] = $num_columns;

    header("Location: create_column_details.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create a Table</title>
</head>
<body>
    <h2>Create a Table in Database: <?php echo $_SESSION['db_name']; ?></h2>
    <form action="create_table.php" method="POST">
        <label for="table_name">Table Name:</label>
        <input type="text" id="table_name" name="table_name" required><br>

        <label for="num_columns">Number of Columns:</label>
        <input type="number" id="num_columns" name="num_columns" required min="1"><br>

        <button type="submit">Next</button>
    </form>
</body>
</html>
