<?php

class PDOHandler
{
    private $servername = "localhost";
    private $username = "root";
    private $password = "Welcome@123";
    private $conn = null;
    private $db_name = null;

    public function __construct()
    {
        try {
            $this->conn = new PDO("mysql:host=$this->servername", $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
    }

    public function createDatabase($db_name)
    {
        try {
            $sql = "CREATE DATABASE $db_name";
            $this->conn->exec($sql);
            $this->db_name = $db_name;  // Set the active database name
            return "Database '$db_name' created successfully.";
        } catch (PDOException $e) {
            return "Error creating database: " . $e->getMessage();
        }
    }

    public function selectDatabase($db_name)
    {
        try {
            $this->conn->exec("USE $db_name");
            $this->db_name = $db_name;
            return true;
        } catch (PDOException $e) {
            return "Error selecting database: " . $e->getMessage();
        }
    }

    public function createTable($table_name, $columns)
    {
        
        if (!$this->db_name) {
            return "No database selected.";
        }
    
        if (stripos($columns, 'id') === false) {
            $columns = "id INT AUTO_INCREMENT PRIMARY KEY, " . $columns;
        }
    
        try {
            $sql = "CREATE TABLE $table_name ($columns)";
            $this->conn->exec($sql);
            return "Table '$table_name' created successfully.";
        } catch (PDOException $e) {
            return "Error creating table: " . $e->getMessage();
        }
    }


     // Insert data into table
     public function insertData($table_name, $data)
     {
 
         // Generate the column names and placeholders dynamically
         $columns = implode(", ", array_keys($data));
         $placeholders = ":" . implode(", :", array_keys($data));
 
         // Prepare the SQL insert statement
         $sql = "INSERT INTO $table_name ($columns) VALUES ($placeholders)";
 
         try {
             $stmt = $this->conn->prepare($sql);
 
             // Bind the values to the placeholders
             foreach ($data as $column => $value) {
                 $stmt->bindParam(":$column", $value);
             }
 
             // Execute the statement
             $stmt->execute();
             return "Data inserted successfully!";
         } catch (PDOException $e) {
             return "Error inserting data: " . $e->getMessage();
         }
     }
 
    

    public function __destruct()
    {
        $this->conn = null;
    }
}
