<?php

include_once 'db_conn.php';

$database = new Database();
$db = $database->getConnection();

// Check if the connection is successful
if (!$db) {
   echo "<script>console.error('Error connecting to the database.');</script>";
   exit();
}

// Create tables if they don't exist
try {
   $query = "
        CREATE TABLE IF NOT EXISTS categories (
            category_id INT PRIMARY KEY AUTO_INCREMENT,
            category_name VARCHAR(50) NOT NULL,
            category_description TEXT
        );

        CREATE TABLE IF NOT EXISTS products (
            product_id INT PRIMARY KEY AUTO_INCREMENT,
            product_name VARCHAR(255) NOT NULL,
            price DECIMAL(10, 2) NOT NULL,
            description TEXT,
            category_id INT,
            FOREIGN KEY (category_id) REFERENCES categories(category_id)
        );

        CREATE TABLE IF NOT EXISTS users (
            user_id INT PRIMARY KEY AUTO_INCREMENT,
            username VARCHAR(50) NOT NULL,
            password VARCHAR(255) NOT NULL,
            email VARCHAR(100),
            address TEXT
        );

        CREATE TABLE IF NOT EXISTS orders (
            order_id INT PRIMARY KEY AUTO_INCREMENT,
            user_id INT,
            order_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            total_price DECIMAL(10, 2) NOT NULL,
            FOREIGN KEY (user_id) REFERENCES users(user_id)
        );

        CREATE TABLE IF NOT EXISTS order_items (
            order_item_id INT PRIMARY KEY AUTO_INCREMENT,
            order_id INT,
            product_id INT,
            quantity INT NOT NULL,
            subtotal DECIMAL(10, 2) NOT NULL,
            FOREIGN KEY (order_id) REFERENCES orders(order_id),
            FOREIGN KEY (product_id) REFERENCES products(product_id)
        );
    ";

   $db->exec($query);
   echo "<script>console.log('Database initialization successful.');</script>";
} catch (PDOException $exception) {
   echo "<script>console.error('Error initializing database: " . $exception->getMessage() . "');</script>";
   exit();
}

// Insert dummy data if not already present
try {

   // insert categories
   $checkCategoriesQuery = "SELECT COUNT(*) FROM categories";
   $stmtCategories = $db->query($checkCategoriesQuery);
   $rowCountCategories = $stmtCategories->fetchColumn();

   if ($rowCountCategories == 0) {
      // Insert dummy categories
      $insertCategoriesQuery = "
            INSERT INTO categories (category_name, category_description)
            VALUES   
               ('Daypack', 'Designed for short trips and daily use. Compact and lightweight. Typically has one or two main compartments.'),
               ('Hiking Backpack', 'Built for outdoor activities, with features like hydration compatibility and multiple pockets. Often includes straps to carry hiking poles or other gear.'),
               ('Laptop Backpack', 'Padded compartment to protect a laptop. Usually has additional pockets for accessories and organization.'),
               ('Travel Backpack', 'Designed for longer trips, with ample space for clothes and personal items. Often includes a laptop compartment and multiple organizational pockets.'),
               ('School Backpack', 'Designed for students, with compartments for books, notebooks, and other school supplies. May have a laptop sleeve and smaller pockets for pens and accessories.'),
               ('Tactical Backpack', 'Durable and rugged design for military or outdoor use. Often includes MOLLE (Modular Lightweight Load-carrying Equipment) webbing for customization.'),
               ('Rolling Backpack', 'Equipped with wheels and a retractable handle for easy rolling. Provides the option to carry on the back or roll like a suitcase.'),
               ('Camera Backpack', 'Specifically designed for carrying camera equipment. Padded compartments and dividers to protect cameras, lenses, and accessories.'),
               ('Hydration Pack', 'Compact backpack designed to hold a water reservoir (bladder) for hands-free hydration during activities like hiking or cycling.'),
               ('Fashion Backpack', 'Stylish and trendy designs for everyday use. Focus on aesthetics with various materials, colors, and patterns.'),
               ('Cycling Backpack', 'Designed for cyclists, often with features like a helmet attachment and reflective elements. Streamlined shape for aerodynamics.'),
               ('Ultralight Backpack', 'Emphasizes minimal weight for backpackers looking to reduce their overall load. Stripped-down design with lightweight materials.'),
               ('Convertible Backpack', 'Can be transformed into different forms, such as a messenger bag or tote. Offers versatility for various occasions.'),
               ('Drawstring Backpack', 'Simple design with a drawstring closure, often used for carrying light items. Casual and easy to use.'),
               ('Military Rucksack', 'Larger, heavy-duty backpack used by the military for carrying a soldier\'s gear. Durable and may have multiple compartments for organization.');
            ";

      $db->exec($insertCategoriesQuery);
      echo "<script>console.log('Dummy categories inserted successfully.');</script>";
   } else {
      echo "<script>console.log('Dummy categories already exist, skipping insertion.');</script>";
   }

   // insert products
   $checkProductsQuery = "SELECT COUNT(*) FROM products";
   $stmtProducts = $db->query($checkProductsQuery);
   $rowCountProducts = $stmtProducts->fetchColumn();

   if ($rowCountProducts == 0) {
      // Insert dummy products
      $insertProductsQuery = "
            INSERT INTO products (product_name, price, description, category_id)
            VALUES
               ('Urban Explorer Backpack', 59.99, 'A versatile daypack with multiple compartments for urban adventures.', 1),
               ('Outdoor Enthusiast Pack', 69.99, 'Water-resistant daypack with padded shoulder straps for hiking.', 1),
               ('Everyday Essentials Backpack', 49.99, 'Compact daypack designed for daily use.', 1),
               ('Trailblazer Hiking Backpack', 89.99, 'Durable hiking backpack with adjustable straps for trailblazing.', 2),
               ('Summit Seeker Pack', 99.99, 'Lightweight hiking backpack with hydration compatibility for mountain treks.', 2),
               ('Adventure Pro Hiker', 79.99, 'Hiking backpack with multiple pockets and rain cover for outdoor adventures.', 2),
               ('Tech Titan Laptop Backpack', 54.99, 'Stylish laptop backpack with padded sleeve for 15-inch laptops.', 3),
               ('Executive Traveler Pack', 49.99, 'Durable laptop backpack with multiple organizational pockets for business travelers.', 3),
               ('Secure Tech Companion', 59.99, 'Anti-theft laptop backpack with USB charging port for tech-savvy professionals.', 3),
               ('Jetsetter\'s Delight', 79.99, 'Spacious travel backpack with a dedicated laptop compartment for jetsetters.', 4),
               ('Convertible Travel Companion', 89.99, 'Convertible travel backpack with multiple carry options for versatile travelers.', 4),
               ('Weekend Wanderlust Pack', 69.99, 'Lightweight travel backpack for extended weekend trips.', 4),
               ('Academic Achiever Backpack', 39.99, 'Classic school backpack with multiple compartments for academic achievers.', 5),
               ('Stylish Scholar Pack', 49.99, 'Stylish school backpack with a laptop sleeve for fashion-forward students.', 5),
               ('Budget-Friendly Book Bag', 29.99, 'Affordable school backpack for students on a budget.', 5),
               ('Strategic Operator Pack', 109.99, 'Heavy-duty tactical backpack with MOLLE webbing for strategic operators.', 6),
               ('Commander\'s Choice Backpack', 119.99, 'Military-style tactical backpack with hydration compatibility for commanders.', 6),
               ('Rugged Outdoor Warrior', 99.99, 'Rugged tactical backpack for outdoor warriors.', 6),
               ('Rolling Explorer Pack', 129.99, 'Rolling backpack with a retractable handle and multiple compartments for explorers.', 7),
               ('Convertible Travel Roller', 139.99, 'Convertible rolling backpack with detachable wheels for versatile travelers.', 7),
               ('Durable Expedition Roller', 119.99, 'Durable rolling backpack for travel expeditions.', 7),
               ('Photographer\'s Choice', 69.99, 'Padded camera backpack with customizable dividers for photographers.', 8),
               ('Waterproof Camera Carryall', 79.99, 'Waterproof camera backpack with external tripod holder for outdoor shoots.', 8),
               ('Compact Camera Companion', 59.99, 'Compact camera backpack with quick-access compartments for on-the-go photographers.', 8),
               ('Active Hydration Companion', 34.99, 'Hydration pack with a 2-liter water reservoir for active individuals.', 9),
               ('Runner\'s Hydration Kit', 39.99, 'Compact hydration pack with adjustable straps for runners.', 9),
               ('Hands-Free Hiker\'s Hydration', 29.99, 'Hydration pack with a bite valve for hands-free drinking during hikes.', 9),
               ('Trendsetter\'s Choice', 49.99, 'Trendy fashion backpack with unique patterns for trendsetters.', 10),
               ('Luxury Leather Elegance', 59.99, 'Stylish leather fashion backpack with gold accents for a touch of luxury.', 10),
               ('Casual Chic Canvas Companion', 39.99, 'Casual canvas fashion backpack for everyday use with a chic touch.', 10),
               ('Cyclist\'s Delight Pack', 54.99, 'Lightweight cycling backpack with a hydration system for cycling enthusiasts.', 11),
               ('AeroAdvantage Cycling Gear', 64.99, 'Aerodynamic cycling backpack with reflective elements for safety.', 11),
               ('Compact Commuter\'s Choice', 44.99, 'Compact cycling backpack with a helmet attachment for daily commuters.', 11),
               ('Minimalist Explorer Pack', 79.99, 'Minimalist ultralight backpack for backpackers seeking simplicity.', 12),
               ('Featherweight Hiker\'s Companion', 89.99, 'Ultralight hiking backpack with a focus on weight reduction for avid hikers.', 12),
               ('Durable Lightweight Explorer', 69.99, 'Durable and lightweight backpack for outdoor adventurers who prioritize weight.', 12),
               ('Versatile Transformer Pack', 59.99, 'Versatile convertible backpack that transforms into a messenger bag for various occasions.', 13),
               ('Convertible Tote Elegance', 69.99, 'Convertible tote backpack with multiple carry options for a touch of elegance.', 13),
               ('Stylish Convertible Companion', 49.99, 'Stylish convertible backpack for various occasions with a touch of sophistication.', 13),
               ('Gym-Ready Drawstring Companion', 19.99, 'Simple drawstring backpack for gym-goers and casual outings.', 14),
               ('Colorful Casual Daypack', 24.99, 'Colorful drawstring backpack with a front pocket for a casual day out.', 14),
               ('Affordable Lightweight Sack', 14.99, 'Affordable and lightweight drawstring backpack for those on the go.', 14),
               ('Tactical Field Commander', 119.99, 'Large military rucksack with multiple compartments for field commanders.', 15),
               ('Camouflage Combat Ready Pack', 129.99, 'Heavy-duty camo military rucksack for combat-ready missions.', 15),
               ('Rugged Outdoor Warrior Rucksack', 109.99, 'Durable and rugged military-style rucksack for outdoor warriors.', 15);
      ";

      $db->exec($insertProductsQuery);
      echo "<script>console.log('Dummy products inserted successfully.');</script>";
   } else {
      echo "<script>console.log('Dummy products already exist, skipping insertion.');</script>";
   }

   // Insert dummy users
   $checkUsersQuery = "SELECT COUNT(*) FROM users";
   $stmtUsers = $db->query($checkUsersQuery);
   $rowCountUsers = $stmtUsers->fetchColumn();

   if ($rowCountUsers == 0) {
      // Insert dummy users
      $insertUsersQuery = "
            INSERT INTO users (username, password, email, address)
            VALUES
               ('drshty', 'drshty_123', 'drshty.patel@gmail.com', '123 Main St, Cityville'),
               ('jane_smith', 'hashed_password_jane', 'jane.smith@example.com', '456 Oak St, Townsville'),
               ('alex_jackson', 'hashed_password_alex', 'alex.jackson@example.com', '789 Pine St, Villagetown'),
               ('sara_wilson', 'hashed_password_sara', 'sara.wilson@example.com', '101 Elm St, Hamletsville'),
               ('mike_jenkins', 'hashed_password_mike', 'mike.jenkins@example.com', '222 Maple St, Countryside');
        ";

      $db->exec($insertUsersQuery);
      echo "<script>console.log('Dummy users inserted successfully.');</script>";
   } else {
      echo "<script>console.log('Dummy users already exist, skipping insertion.');</script>";
   }

   // Insert dummy orders
   $checkOrdersQuery = "SELECT COUNT(*) FROM orders";
   $stmtOrders = $db->query($checkOrdersQuery);
   $rowCountOrders = $stmtOrders->fetchColumn();

   if ($rowCountOrders == 0) {
      // Insert dummy orders
      $insertOrdersQuery = "
            INSERT INTO orders (user_id, total_price)
            VALUES
            (1, 89.97),
            (2, 69.98);
        ";

      $db->exec($insertOrdersQuery);
      echo "<script>console.log('Dummy orders inserted successfully.');</script>";
   } else {
      echo "<script>console.log('Dummy orders already exist, skipping insertion.');</script>";
   }

   // Insert dummy order items
   $checkOrderItemsQuery = "SELECT COUNT(*) FROM order_items";
   $stmtOrderItems = $db->query($checkOrderItemsQuery);
   $rowCountOrderItems = $stmtOrderItems->fetchColumn();

   if ($rowCountOrderItems == 0) {
      // Insert dummy order items
      $insertOrderItemsQuery = "
            INSERT INTO order_items (order_id, product_id, quantity, subtotal)
            VALUES
            (1, 1, 2, 99.98),
            (2, 2, 1, 39.99),
            (2, 3, 3, 89.97);
        ";

      $db->exec($insertOrderItemsQuery);
      echo "<script>console.log('Dummy order items inserted successfully.');</script>";
   } else {
      echo "<script>console.log('Dummy order items already exist, skipping insertion.');</script>";
   }
} catch (PDOException $exception) {
   echo "<script>console.error('Error inserting dummy data: " . $exception->getMessage() . "');</script>";
   exit();
}