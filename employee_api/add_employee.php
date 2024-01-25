<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header("Access-Control-Allow-Headers: X-Requested-With, Content-Type");

include '../db/DbConnector.php';

class EmployeeAdder
{
    private $connection;

    public function __construct($connection)
    {
        $this->connection = $connection;
    }

    public function add($employeeData)
    {
        try {
            if (empty($employeeData))
                return;
            $first_name = $employeeData["employeeName"];

            // Create the SQL query
            $query = "INSERT INTO employees(first_name) VALUES (?)";

            // Prepare the SQL statement
            $stmt = $this->connection->prepare($query);

            // Bind the employee data to the SQL statement
            $stmt->bind_param("s", $first_name);

            // Execute the query
            if ($stmt->execute()) {
                return [
                    "status" => "success",
                    "message" => "Employee added successfully"
                ];
            } else {
                throw new Exception("Failed to execute statement: " . $stmt->error);
            }
        } catch (Exception $err) {
            return ["status" => "error", "message" => $err->getMessage()];
        } finally {
            $this->connection->close();
        }
    }
}

$connector = new DbConnector();
$connection = $connector->connect();
$adder = new EmployeeAdder($connection);
$employeeData = json_decode(file_get_contents('php://input'), true);
$response = $adder->add($employeeData);
echo json_encode($response);