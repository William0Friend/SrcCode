<?php
// db_connection.php

$servername = "localhost";
$username = "root";
$password = "N0th1ng4u";
$dbname = "srccode0";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
echo '<div>Connected successfully</div>';
