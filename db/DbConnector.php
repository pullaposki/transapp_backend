<?php 

class DbConnector {
    private $dbhost = "localhost";
    private $dbuser = "root";
    private $dbpass = "";
    private $dbname = "transapp";

    public function connect() {
        // Create connection
        $connection = mysqli_connect($this->dbhost, $this->dbuser, $this->dbpass, $this->dbname);

        // Check connection
        if (!$connection) {
            die("Connection failed: " . mysqli_connect_error());
        }

        return $connection;
    }
}