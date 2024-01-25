<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: DELETE');
header("Access-Control-Allow-Headers: X-Requested-With, Content-Type");

include '../db/DbConnector.php';

class EmployeeDeleter
{
    private $connection;

    public function __construct($connection)
    {
        $this->connection = $connection;
    }

    public function delete($employeeData)
    {
        try {
            if (empty($employeeData))
                throw new Exception("No data provided");

            $employeeId = $employeeData["employeeId"];

            $query = "DELETE FROM employees WHERE id = ?";
            $stmt = $this->connection->prepare($query);
            $stmt->bind_param("i", $employeeId);
            $result = $stmt->execute();
            $stmt->close();

            if (!$result)
                throw new Exception("Failed to delete employee");

            return ["status" => "success", "message" => "Employee deleted successfully"];
        } catch (Exception $e) {
            return ["status" => "error", "message" => $e->getMessage()];
        } finally {
            $this->connection->close();
        }
    }
}

$connector = new DbConnector();
$connection = $connector->connect();
$deleter = new EmployeeDeleter($connection);
$employeeData = json_decode(file_get_contents('php://input'), true);
$response = $deleter->delete($employeeData);
echo json_encode($response);
