<?php
$host = "127.0.0.1"; // Using 127.0.0.1 explicitly with the port number
$username = "root";
$password = ""; // Default XAMPP password is empty
$dbname = "food_rescue_db";
$port = 3307; // <--- Added your new MySQL port here!

$conn = new mysqli($host, $username, $password, $dbname, $port);

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}
?>