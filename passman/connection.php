<?php
$servername = "localhost";
$db_username = "limited_user"; // Use a non-admin user
$db_password = "limited_password";
$dbname = "pwd_mgr";
$port = 3301;

// Create connection
$conn = new mysqli($servername, $db_username, $db_password, $dbname, $port);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>