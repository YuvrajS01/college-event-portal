<?php
// Database connection settings
$host = 'localhost'; // Database host
$db_name = 'college_events'; // Database name
$username = 'root'; // Database username
$password = ''; // Database password

// Create a new MySQLi connection
$conn = new mysqli($host, $username, $password, $db_name);

// Check connection
if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}
?>
