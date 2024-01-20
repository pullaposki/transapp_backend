<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header("Access-Control-Allow-Headers: X-Requested-With, Content-Type");

// connect to db
include 'db_connection.php';

// Get the employee data from the POST request
$employeeData = json_decode(file_get_contents('php://input'), true);
if (empty($employeeData)) return;
$first_name = $employeeData["employeeName"];

// Create the SQL query
$sql = "INSERT INTO employees(first_name) VALUES (?)";

// Prepare the SQL statement
$stmt = $connection->prepare($sql);

// Bind the employee data to the SQL statement
$stmt->bind_param("s", $first_name);

// Execute the query
if ($stmt->execute()) {
   $response = array(
    "status" => "success",
    "message" => "Employee added successfully"
);
echo json_encode($response);
} else {
    echo "Error: " . $stmt->error;
}

// Close the database connection
$connection->close();