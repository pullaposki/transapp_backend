<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header("Access-Control-Allow-Headers: X-Requested-With, Content-Type");

// connect to db
include 'db_connection.php';

// Create the SQL query
$sql = "SELECT * FROM Employees";

// Execute the query
$result = $connection->query($sql);

// Check if there are results
if ($result->num_rows > 0) {
    // Create an array to hold the data
    $employees = [];

    // Fetch the data into the array
    while($row = $result->fetch_assoc()) {
        $employees[] = $row;
    }

    // Return the data as a JSON response
    echo json_encode($employees);
} else {
    echo "No results found";
}

// Close the database connection
$connection->close();