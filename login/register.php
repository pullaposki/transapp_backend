<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header("Access-Control-Allow-Headers: X-Requested-With, Content-Type");

error_log(print_r($_SERVER, true));
error_log(print_r($_POST, true));

include '../db/DbConnector.php';

$connector = new DbConnector();
$connection = $connector->connect();

$data = json_decode(file_get_contents('php://input'), true);
$response = [];

if (json_last_error() !== JSON_ERROR_NONE) {
  $response = ['status' => 'error', 'message' => 'Invalid JSON: ' . json_last_error_msg()];
  echo json_encode($response);
  $connection->close();
  return;
}

if (dataCheckNotPassed($data)) {
  $response = ['status' => 'error', 'message' => 'Missing required fields'];
  echo json_encode($response);
  $connection->close();
  return;
}

$username = $data['username'];
$password = $data['password'];
$first_name = $data['firstName'];
$last_name = $data['lastName'];
$start_date = time();

$hashed_password = password_hash($password, PASSWORD_DEFAULT);

$query = 'INSERT INTO employees (username, password, first_name, last_name, start_date) VALUES (?, ?, ?, ?, ?)';
$stmt = $connection->prepare($query);
if ($stmt === false) {
  $response = ['status' => 'error', 'message' => 'Failed to prepare statement: ' . $connection->error];
  echo json_encode($response);
  $connection->close();
  return;
}

$stmt->bind_param('ssssi', $username, $hashed_password, $first_name, $last_name, $start_date);

if ($stmt->execute()) {
  $response = ['status' => 'success', 'uname' => $username, 'pword' => $password, 'fname' => $first_name, 'lname' => $last_name, 'stime' => $start_date];
} else {
  $response = ['status' => 'error', 'message' => 'Failed to execute statement: ' . $connection->error];
}
$connection->close();
echo json_encode($response);

function dataCheckNotPassed($data): bool
{
  if (!isset($data) || !isset($data['username']) || !isset($data['password']) || !isset($data['firstName']) || !isset($data['lastName'])) {
    return true;
  }
  return false;
}