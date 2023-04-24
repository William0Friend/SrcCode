<?php
// Include the database configuration file
include_once 'config.php';

// Define the SQL query to retrieve all code items
$sql = "SELECT * FROM Code";

// Execute the query and retrieve the results
$result = $conn->query($sql);

// Convert the results to an array of objects
$code_items = array();
while ($row = $result->fetch_object()) {
    $code_items[] = $row;
}

// Return the code items as JSON
echo json_encode($code_items);
?>