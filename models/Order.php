<?php

class Order
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function createOrder($totalAmount, $taxAmount, $productIds)
    {
        $user_id = $_SESSION['user_id'];
        // Perform the necessary database insertions for order details
        // Adjust this based on your actual database structure
        $query = "INSERT INTO orders (total_price, tax_amount, product_ids, user_id) VALUES (:total, :tax, :products, $user_id)";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':total', $totalAmount);
        $stmt->bindParam(':tax', $taxAmount);
        $stmt->bindParam(':products', $productIds);
        $stmt->execute();

        // Return the order ID or any relevant information
        return $this->db->lastInsertId();
    }

}

?>
