<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header("Access-Control-Allow-Headers: X-Requested-With, Content-Type");

include '../db/DbConnector.php';

$connector = new DbConnector();
$connection = $connector->connect();

$_POST = json_decode(file_get_contents('php://input'), true);

// if (json_last_error() !== JSON_ERROR_NONE) {
//   http_response_code(400);
//   echo json_encode(['status' => 'error', 'message' => 'Invalid JSON']);
//   return;
// }

if (!isset($_POST)) {
  echo json_encode(['status' => 'post not found', 'messagge' => 'ei postia perkele']);
  return;
}

$username = $_POST['username'];
$password = $_POST['password'];
$first_name = $_POST['firstName'];
$last_name = $_POST['lastName'];
$start_date = time();

echo json_encode(['uname' => $username, 'pword' => $password, 'fname' => $first_name, 'lname' => $last_name, 'stime' => $start_date]);

$hashed_password = password_hash($password, PASSWORD_DEFAULT);

$query = 'INSERT INTO employees (username, password, first_name, last_name, start_date) VALUES (?, ?, ?, ?, ?)';
$stmt = $connection->prepare($query);

$stmt->bind_param('ssssi', $username, $hashed_password, $first_name, $last_name, $start_date);

if ($stmt->execute()) {
  http_response_code(200);
  echo json_encode([
    "status" => "success",
    "message" => "Register successful"
  ]);
} else {
  http_response_code(500);
  echo json_encode([
    "status" => "fail",
    "message" => "Register failed",
    "error" => $stmt->error
  ]);
}