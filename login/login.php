<?php
session_start();

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header("Access-Control-Allow-Headers: X-Requested-With, Content-Type");

include './db/DbConnector.php';

$connector = new DbConnector();
$connection = $connector->connect();

$_POST = json_decode(file_get_contents('php://input'), true);

if (!isset($_POST)) {
    echo json_encode(['status' => 'post not found']);
    return;
}

$username = $_POST['username'];
$password = $_POST['password'];

$stmt = $connection->prepare('SELECT * FROM employees WHERE username = ?');
$stmt->bind_param('s', $username);
$stmt->execute();
$result = $stmt->get_result()->fetch_assoc();

if (passwordOK($password, $result) && is_array($result)) {
    $_SESSION['user_id'] = $result['id'];
    echo json_encode(['status' => 'success']);
} else {
    echo json_encode(['status' => 'error']);
}


// if ($username === 'admin' && $password === 'password') {
//     echo json_encode(['status' => 'success']);
// } else {
//     echo json_encode(['status' => 'error']);
// }

function passwordOK($result, $password): bool
{
    if ($result && password_verify($password, $result['password']))
        return true;

    return false;
}