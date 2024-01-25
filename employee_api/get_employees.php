<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');
header("Access-Control-Allow-Headers: X-Requested-With, Content-Type");

include '../db/DbConnector.php';

class EmployeesGetter
{
    private $connection;

    public function __construct($connection)
    {
        $this->connection = $connection;
    }

    public function getAll()
    {
        try {
            $query = "SELECT * FROM Employees";
            $result = $this->connection->query($query);

            // Check if there are results
            if ($result->num_rows > 0) {
                // Create an array to hold the data
                $employees = [];

                // Fetch the data into the array
                while ($row = $result->fetch_assoc()) {
                    $employees[] = $row;
                }

                // Return the data
                return $employees;
            } else {
                return "No results found";
            }
        } catch (Exception $e) {
            return ["status" => "error", "message" => $e->getMessage()];
        } finally {
            $this->connection->close();
        }
    }
}

$connector = new DbConnector();
$connection = $connector->connect();
$fetcher = new EmployeesGetter($connection);
$response = $fetcher->getAll();
echo is_array($response) ? json_encode($response) : $response;