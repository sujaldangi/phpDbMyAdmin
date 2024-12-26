<?php
session_start();

// Include the PDOHandler class
require_once 'pdo.php';

// Initialize the PDOHandler class
$pdoHandler = new PDOHandler();
$pdoHandler->selectDatabase($_SESSION['db_name']);
// Retrieve table name and column details from session
$table_name = $_SESSION['table_name'];
$formattedColumns = $_SESSION['formattedColumns'];

echo "<h2>Insert Data into Table '$table_name'</h2>";
echo "<form method='post' action='add_value_to_table.php'>";  // The form will post to itself

// Generate form fields based on columns
foreach ($formattedColumns as $column) {
    $column_name = $column[0];
    $column_type = $column[1];
    
    // Based on column type, generate appropriate form field
    if (stripos($column_type, 'VARCHAR') !== false) {
        // For VARCHAR, use text input
        echo "<label for='$column_name'>$column_name: </label>";
        echo "<input type='text' name='$column_name' id='$column_name' required><br><br>";
    } elseif (stripos($column_type, 'INT') !== false) {
        // For INT, use number input
        echo "<label for='$column_name'>$column_name: </label>";
        echo "<input type='number' name='$column_name' id='$column_name' required><br><br>";
    } 
    else{
        echo "<label for='$column_name'>$column_name: </label>";
        echo "<input type='text' name='$column_name' id='$column_name' required><br><br>";
    }
}

echo "<input type='submit' value='Submit'>";
echo "</form>";

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $form_data = [];
    foreach ($formattedColumns as $column) {
        $column_name = $column[0];
        if (isset($_POST[$column_name])) {
            $form_data[$column_name] = $_POST[$column_name];
        }
    }
    
    $insert_result = $pdoHandler->insertData($table_name, $form_data);
    echo $insert_result;  // Output the result (success or error)
}
?>
