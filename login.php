<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header("Access-Control-Allow-Headers: X-Requested-With, Content-Type");

$_POST = json_decode(file_get_contents('php://input'), true);

if (!isset($_POST)) {
    echo "post not found";
    return;
}

$username = $_POST['username'];
$password = $_POST['password'];

if ($username === 'admin' && $password === 'password') {
    echo json_encode(['status' => 'success']);
} else {
    echo json_encode(['status' => 'error']);
}