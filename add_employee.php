<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header("Access-Control-Allow-Headers: X-Requested-With, Content-Type");

include 'db_connection.php';
$connector = new DbConnector();
$connection = $connector->connect();

try {   
    // Get the employee data from the POST request
    $employeeData = json_decode(file_get_contents('php://input'), true);
    if (empty($employeeData)) return;
    $first_name = $employeeData["employeeName"];

    // Create the SQL query
    $query = "INSERT INTO employees(first_name) VALUES (?)";

    // Prepare the SQL statement
    $stmt = $connection->prepare($query);

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
        throw new Exception("Failed to execute statement: " . $stmt->error);
    }
} catch (Exception $err) {
    echo json_encode(["status"=> "error","message"=> $err->getMessage()]);
} finally {
    $connection->close();
}






