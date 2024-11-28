<?php
$host = 'localhost'; // or your host
$username = 'root'; // database username
$password = ''; // database password
$dbname = 'ecommerce_db'; // your database name

// Create connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
