<?php
$servername = "localhost";
$username = "limited_user"; // Use a non-admin user
$password = "limited_password";
$dbname = "pwd_mgr";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>