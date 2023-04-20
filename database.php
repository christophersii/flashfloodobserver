<?php
class Database {
    // Specify your own database credentials here
    private $host = "us-cdbr-east-06.cleardb.net"
    private $dbname = "heroku_3442ee38bf9fb24";
    private $username = "b005c8a97ae61d";
    private $password = "04eca8ce";
    public $conn;

    // Get the database connection
    public function getConnection() {
        $this->conn = null;

        try {
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->dbname, $this->username, $this->password);
            $this->conn->exec("set names utf8");
        } catch(PDOException $exception) {
            echo "Connection error: " . $exception->getMessage();
        }

        return $this->conn;
    }
}
?>
