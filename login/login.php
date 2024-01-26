<?php
session_start();

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header("Access-Control-Allow-Headers: X-Requested-With, Content-Type");

include './db/DbConnector.php';

$connector = new DbConnector();
$connection = $connector->connect();

$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data) || !isset($data['username']) || !isset($data['password'])) {
    echo json_encode(['status' => 'error', 'message' => 'Missing required fields']);
    return;
}

$username = $data['username'];
$password = $data['password'];

$stmt = $connection->prepare('SELECT * FROM employees WHERE username = ?');
if ($stmt === false) {
    echo json_encode(['status' => 'error', 'message' => 'Failed to prepare statement: ' . $connection->error]);
    return;
}

$stmt->bind_param('s', $username);

if (!$stmt->execute()) {
    echo json_encode(['status' => 'error', 'message' => 'Failed to execute statement: ' . $stmt->error]);
    return;
}

$result = $stmt->get_result()->fetch_assoc();

if ($result && passwordOK($password, $result)) {
    $_SESSION['user_id'] = $result['id'];
    echo json_encode(['status' => 'success']);
} else {
    echo json_encode(['status' => 'error']);
}

function passwordOK($password, $result): bool
{
    return password_verify($password, $result['password']);
}