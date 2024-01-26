<?php
session_start();

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header("Access-Control-Allow-Headers: X-Requested-With, Content-Type");

include '../db/DbConnector.php';

$connector = new DbConnector();
$connection = $connector->connect();

$data = json_decode(file_get_contents('php://input'), true);
$response = [];

if (!isset($data) || !isset($data['username']) || !isset($data['password'])) {
    $response = ['status' => 'error', 'message' => 'Missing required fields'];
    echo json_encode($response);
    $connection->close();
    return;
}

$username = $data['username'];
$password = $data['password'];

$stmt = $connection->prepare('SELECT * FROM employees WHERE username = ?');
if ($stmt === false) {
    $response = ['status' => 'error', 'message' => 'Failed to prepare statement: ' . $connection->error];
    echo json_encode($response);
    $connection->close();
    return;
}

$stmt->bind_param('s', $username);

if (!$stmt->execute()) {
    $response = ['status' => 'error', 'message' => 'Failed to execute statement: ' . $stmt->error];
    echo json_encode($response);
    $connection->close();
    return;
}

$result = $stmt->get_result()->fetch_assoc();

if ($result && passwordOK($password, $result)) {
    $_SESSION['user_id'] = $result['id'];
    $response = ['status' => 'success'];
} else {
    $response = ['status' => 'error', 'message' => 'Authentication failed.'];
}

echo json_encode($response);
$connection->close();

function passwordOK($password, $result): bool
{
    return password_verify($password, $result['password']);
}