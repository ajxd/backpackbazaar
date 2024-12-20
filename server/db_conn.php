<?php
session_start(); // Start the session

class Database
{
    private $host = "127.0.0.1";
    private $username = "root";
    private $password = "";
    private $db_name = "bagstore";
    public $conn;

    // Get the database connection
    public function getConnection()
    {
        $this->conn = null;
        try {
            // Create a PDO instance without specifying the database name
            $this->conn = new PDO(
                "mysql:host={$this->host}",
                $this->username,
                $this->password
            );
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Check if the database exists, if not, create it
            $query = "CREATE DATABASE IF NOT EXISTS {$this->db_name}";
            $this->conn->exec($query);

            // Switch to the created or existing database
            $this->conn->exec("USE {$this->db_name}");
        } catch (PDOException $exception) {
            echo "Connection error: " . $exception->getMessage();
            exit();
        }

        return $this->conn;
    }
}
