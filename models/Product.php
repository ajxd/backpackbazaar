<?php 
class Product {
    private $db;
    public function __construct($db) {
        $this->db = $db;
    }
    public function getAllProducts() {
        try {
            $query = "SELECT * FROM products";
            $stmt = $this->db->prepare($query);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // Handle the exception, log it, or display an error message
            return [];
        }
    }

    public function getProductsByCategory($categoryId) {
        try {
            echo `$categoryId`;
            $query = "SELECT * FROM products WHERE category_id = :category";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':category', $categoryId);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // Handle the exception, log it, or display an error message
            return [];
        }
    }
    
    //get recently added products as featured products
    public function getFeaturedProducts() {
        try {
            $query = "SELECT * FROM products ORDER BY product_id DESC LIMIT 4";
            $stmt = $this->db->prepare($query);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // Handle the exception, log it, or display an error message
            return [];
        }
    }

    public function getProductById($productId) {
        try {
            $query = "SELECT * FROM products WHERE product_id = :id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $productId, PDO::PARAM_INT);
            $stmt->execute();
    
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
            return $result;
        } catch (PDOException $e) {
            // Handle the exception, log it, or display an error message
            return false;
        }
    }

    public function getCategories() {
        try {
        $query = "SELECT category_id, category_name, category_description FROM categories";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $result = $stmt->fetchALL(PDO::FETCH_ASSOC);
        array_unshift($result, ['category_id' => 'all', 'category_name' => 'All', 'category_description' => 'All Categories']);
        return $result;
    } catch (PDOException $e) {
        // Handle the exception, log it, or display an error message
        return false;
    }
    }
    
    public function createProduct($data) {
        // Implement the logic to insert a new product into the database
        // $data should be an associative array with product details
        // try {
        //     $query = "INSERT INTO products (name, price, description, category, stock_quantity) 
        //               VALUES (:name, :price, :description, :category, :stock_quantity)";
        //     $stmt = $this->db->prepare($query);
        //     $stmt->bindParam(':name', $data['name']);
        //     $stmt->bindParam(':price', $data['price']);
        //     $stmt->bindParam(':description', $data['description']);
        //     $stmt->bindParam(':category', $data['category']);
        //     $stmt->bindParam(':stock_quantity', $data['stock_quantity']);
            
        //     return $stmt->execute();
        // } catch (PDOException $e) {
        //     // Handle the exception, log it, or display an error message
        //     return false;
        // }
    }
    public function updateProduct($productId, $data) {
        // Implement the logic to update a product in the database
        // $data should be an associative array with updated product details
        // try {
        //     $query = "UPDATE products 
        //               SET name = :name, price = :price, description = :description, 
        //                   category = :category, stock_quantity = :stock_quantity
        //               WHERE id = :id";
        //     $stmt = $this->db->prepare($query);
        //     $stmt->bindParam(':name', $data['name']);
        //     $stmt->bindParam(':price', $data['price']);
        //     $stmt->bindParam(':description', $data['description']);
        //     $stmt->bindParam(':category', $data['category']);
        //     $stmt->bindParam(':stock_quantity', $data['stock_quantity']);
        //     $stmt->bindParam(':id', $productId, PDO::PARAM_INT);

        //     return $stmt->execute();
        // } catch (PDOException $e) {
        //     // Handle the exception, log it, or display an error message
        //     return false;
        // }
    }
    public function deleteProduct($productId) {
        // Implement the logic to delete a product from the database
        // try {
        //     $query = "DELETE FROM products WHERE id = :id";
        //     $stmt = $this->db->prepare($query);
        //     $stmt->bindParam(':id', $productId, PDO::PARAM_INT);

        //     return $stmt->execute();
        // } catch (PDOException $e) {
        //     // Handle the exception, log it, or display an error message
        //     return false;
        // }
    }
}
?>