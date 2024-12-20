<?php
class User {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function registerUser($userData) {
        try {
            // Hash the password before storing it in the database
            $hashedPassword = password_hash($userData['password'], PASSWORD_DEFAULT);

            $query = "INSERT INTO users (username, password, email, address) VALUES (:username, :password, :email, :address)";
            $stmt = $this->conn->prepare($query);

            // Bind parameters
            $stmt->bindParam(":username", $userData['name']);
            $stmt->bindParam(":password", $hashedPassword);
            $stmt->bindParam(":email", $userData['email']);
            $stmt->bindParam(":address", $userData['address']);

            // Execute query
            if ($stmt->execute()) {
                return true; // User registered successfully
            } else {
                return false; // Registration failed
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false; // Registration failed
        }
    }
}
?>
