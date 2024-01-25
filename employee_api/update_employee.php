<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT');
header("Access-Control-Allow-Headers: X-Requested-With, Content-Type");

include '../db/DbConnector.php';

class EmployeeUpdater {
    private $connection;

    public function __construct($connection) {
        $this->connection = $connection;
    }

    public function update($employeeData) {
        try {
            if (empty($employeeData)) throw new Exception("No data provided");

            $employeeId = $employeeData["employeeId"];
            $first_name = $employeeData["employeeName"];

            $query = "UPDATE employees SET first_name = ? WHERE id = ?";
            $stmt = $this->connection->prepare($query);
            $stmt->bind_param("si", $first_name, $employeeId);
            $result = $stmt->execute();
            $stmt->close();

            if (!$result) throw new Exception("Failed to update employee");

            return ["status" => "success", "message" => "Employee updated successfully"];
        } catch (Exception $e) {
            return ["status" => "error", "message" => $e->getMessage()];
        } finally {
            $this->connection->close();
        }
    }
}

$connector = new DbConnector();
$connection = $connector->connect();
$updater = new EmployeeUpdater($connection);
$employeeData = json_decode(file_get_contents('php://input'), true);
$response = $updater->update($employeeData);
echo json_encode($response);