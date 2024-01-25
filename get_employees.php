<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');
header("Access-Control-Allow-Headers: X-Requested-With, Content-Type");

include 'db_connection.php';
$connector = new DbConnector();
$connection = $connector->connect();

try {
    $query = "SELECT * FROM Employees";
    $result = $connection->query($query);
    
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
} catch (Exception $e) {
    echo json_encode(["status"=> "error","message"=> $err->getMessage()]);
} finally {
    $connection->close();
}

