<?php
// used to get mysql databse connection
class Database{

    // specify your own database credentials
    private $host = "ip:port";
    private $db_name = "db-name";
    private $username = "username";
    private $password = "password";
    public $conn;

    // get the database connection
    public function getConnection(){

        $this->conn = null;

        try{
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name .";charset=utf8", $this->username, $this->password);
        }catch(PDOException $exception){
            echo "Connection error: " . $exception->getMessage();
        }

        return $this->conn;
    }
}
?>