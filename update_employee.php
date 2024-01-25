<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT');
header("Access-Control-Allow-Headers: X-Requested-With, Content-Type");

include 'db_connection.php';
$connector = new DbConnector();
$connection = $connector->connect();

try {
    $employeeData = json_decode(file_get_contents('php://input'), true);

    if (empty($employeeData)) throw new Exception("No data provided");

    $employeeId = $employeeData["employeeId"];
    $first_name = $employeeData["employeeName"];

    $query = "UPDATE employees SET first_name = ? WHERE id = ?";
    $stmt = $connection->prepare($query);
    $stmt->bind_param("si", $first_name, $employeeId);
    $result = $stmt->execute();
    $stmt->close();

    if (!$result) throw new Exception("Failed to update employee");

    echo json_encode(["status" => "success", "message" => "Employee updated successfully"]);
} catch (Exception $e) {
    echo json_encode(["status" => "error", "message" => $e->getMessage()]);
} finally {
    $connection->close();
}