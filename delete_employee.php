<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: DELETE');
header("Access-Control-Allow-Headers: X-Requested-With, Content-Type");

include 'db_connection.php';

try {
    $employeeData = json_decode(file_get_contents('php://input'), true);

    if (empty($employeeData)) throw new Exception("No data provided");

    $employeeId = $employeeData["employeeId"];

    $query = "DELETE FROM employees WHERE id = ?";
    $stmt = $connection->prepare($query);
    $stmt->bind_param("i", $employeeId);
    $result = $stmt->execute();
    $stmt->close();

    if (!$result) throw new Exception("Failed to delete employee");

    echo json_encode(["status" => "success", "message" => "Employee deleted successfully"]);
} catch (Exception $e) {
    echo json_encode(["status" => "error", "message" => $e->getMessage()]);
} finally {
    $connection->close();
}