<?php
// Start the session to clear the database name
session_start();
session_unset();  // Clear all session variables
session_destroy();  // Destroy the session

echo "Session cleared. Please go back to create a new database.";
echo "<br><a href='index.html'>Go Back</a>";
?>
