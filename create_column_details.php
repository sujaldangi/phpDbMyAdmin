<?php
session_start();

if (!isset($_SESSION['db_name']) || !isset($_SESSION['table_name']) || !isset($_SESSION['num_columns'])) {
    echo "No valid session data found. Please go back and create a table.";
    exit;
}

$num_columns = $_SESSION['num_columns'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require_once 'pdo.php';

    $table_name = $_SESSION['table_name'];
    $columns = [];

    for ($i = 0; $i < $num_columns; $i++) {
        $column_name = $_POST["column_name_$i"];
        $column_type = $_POST["column_type_$i"];
        $length = $_POST["length_$i"];
        $is_null = isset($_POST["nullable_$i"]) ? 'NULL' : 'NOT NULL';
        $index = $_POST["index_$i"];

        $columns[] = "$column_name $column_type($length) $is_null $index";
    }
    $formattedColumns = array();
    foreach ($columns as $column) {
        // Split the column definition by spaces and trim any extra spaces
        $parts = preg_split('/\s+/', trim($column));
    
        // Extract the column name and the type (ignoring the size and constraints)
        $columnName = $parts[0];
        $columnType = strtok($parts[1], '('); // Extract the type before the parentheses
    
        // Store the result in the formatted array
        $formattedColumns[] = array($columnName, $columnType);
    }
    $_SESSION['formattedColumns'] = $formattedColumns;
    // echo var_dump($formattedColumns);
    $pdo = new PDOHandler();
    if ($pdo->selectDatabase($_SESSION['db_name'])) {
        $columns_sql = implode(", ", $columns);
        $result = $pdo->createTable($table_name, $columns_sql);
        echo $result;
        header("Location: add_value_to_table.php");
        exit();
    } else {
        echo "Error selecting database.";
    }

    // Clear session data after table creation
    unset($_SESSION['num_columns']);
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Column Details</title>
</head>
<body>
    <h2>Define Columns for Table: <?php echo $_SESSION['table_name']; ?></h2>
    <form action="create_column_details.php" method="POST">
        <?php for ($i = 0; $i < $num_columns; $i++): ?>
        <fieldset>
            <legend>Column <?php echo $i + 1; ?></legend>
            <label for="column_name_<?php echo $i; ?>">Column Name:</label>
            <input type="text" id="column_name_<?php echo $i; ?>" name="column_name_<?php echo $i; ?>" required><br>

            <label for="column_type_<?php echo $i; ?>">Column Type:</label>
            <select id="column_type_<?php echo $i; ?>" name="column_type_<?php echo $i; ?>" required>
                <option value="INT">INT</option>
                <option value="VARCHAR">VARCHAR</option>
                <option value="TEXT">TEXT</option>
                <option value="DATE">DATE</option>
            </select><br>

            <label for="length_<?php echo $i; ?>">Length:</label>
            <input type="number" id="length_<?php echo $i; ?>" name="length_<?php echo $i; ?>" required><br>

            <label for="nullable_<?php echo $i; ?>">Nullable:</label>
            <input type="checkbox" id="nullable_<?php echo $i; ?>" name="nullable_<?php echo $i; ?>"><br>

            <label for="index_<?php echo $i; ?>">Index Type:</label>
            <select id="index_<?php echo $i; ?>" name="index_<?php echo $i; ?>" >
                <option value="">None</option>
                <option value="PRIMARY KEY">PRIMARY KEY</option>
                <option value="UNIQUE">UNIQUE</option>
            </select><br>
        </fieldset>
        <?php endfor; ?>

        <button type="submit">Create Table</button>
    </form>
</body>
</html>
