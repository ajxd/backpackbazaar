<?php
class Login {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function loginUser($email, $password) {
        try {
            // Fetch user data based on the provided email
            $query = "SELECT user_id, username, password FROM users WHERE email = :email";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":email", $email);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user) {
                // Verify the password
                if (password_verify($password, $user['password'])) {
                    return $user; // Login successful
                } else {
                    return false; // Incorrect password
                }
            } else {
                return false; // User not found
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false; // Login failed
        }
    }
}
?>
