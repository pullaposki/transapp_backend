<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header("Access-Control-Allow-Headers: X-Requested-With, Content-Type");

include '../db/DbConnector.php';

class employerGetter
{
    private $connection;

    public function __construct($connection)
    {
        $this->connection = $connection;
    }

    public function getOne($employeeData)
    {
        try {
            $employeeId = $_GET['id'];

            $stmt = $this->connection->prepare("SELECT * FROM employees WHERE id = ?");
            $stmt->bind_param("i", $employeeId);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $employee = $result->fetch_assoc();
                return ['status' => 'success', 'message' => $employee];
            } else {
                return ['status' => 'error', 'message' => 'Employee not found'];
            }
        } catch (Exception $e) {
            return ["status" => "error", "message" => $e->getMessage()];
        } finally {
            $stmt->close();
            $this->connection->close();
        }
    }
}

$connector = new DbConnector();
$connection = $connector->connect();
$getter = new employerGetter($connection);
$employeeData = json_decode(file_get_contents('php://input', true));
$response = $getter->getOne($employeeData);
echo json_encode($response);


