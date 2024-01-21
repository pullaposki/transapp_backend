<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header("Access-Control-Allow-Headers: X-Requested-With, Content-Type");

include 'db_connection.php';

$employeeId = $_GET['id'];

$stmt = $connection->prepare("SELECT * FROM employees WHERE id = ?");
$stmt->bind_param("i", $employeeId);

$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $employee = $result->fetch_assoc();
    echo json_encode(['status' => 'success', 'message' => $employee]);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Employee not found']);
}

$stmt->close();
$connection->close();