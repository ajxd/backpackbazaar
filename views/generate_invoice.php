<?php
include_once '../fpdf186/fpdf.php';
include_once '../server/db_conn.php';
include_once '../server/db_init.php';
include_once '../models/Product.php';

$database = new Database();
$db = $database->getConnection();

// Retrieve the order ID (assuming it's passed via a GET parameter or a session variable)
$order_id = $_GET['order_id'] ?? $_SESSION['order_id'];  // Adjust as needed

// Fetch order details
$orderQuery = "SELECT * FROM orders WHERE order_id = ?";
$stmtOrder = $db->prepare($orderQuery);
$stmtOrder->execute([$order_id]);
$order = $stmtOrder->fetch(PDO::FETCH_ASSOC);

// Fetch order items
$itemsQuery = "SELECT * FROM order_items WHERE order_id = ?";
$stmtItems = $db->prepare($itemsQuery);
$stmtItems->execute([$order_id]);
$items = $stmtItems->fetchAll(PDO::FETCH_ASSOC);

// Create PDF
$pdf = new FPDF();
$pdf->AddPage();

// Add title
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(40, 10, 'Invoice for Order #' . $order_id);
$pdf->Ln(20);

// Add Order Info
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(40, 10, 'Order Date: ' . $order['order_date']);
$pdf->Ln(10);
$pdf->Cell(40, 10, 'Total Price: $' . $order['total_price']);
$pdf->Ln(10);
$pdf->Cell(40, 10, 'Tax Amount: $' . $order['tax_amount']);
$pdf->Ln(20);

// Add column headers for Items
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(70, 10, 'Item', 1);
$pdf->Cell(30, 10, 'Quantity', 1);
$pdf->Cell(40, 10, 'Subtotal', 1);
$pdf->Ln();

// Reset font for Items
$pdf->SetFont('Arial', '', 12);

// Display each item
foreach ($items as $item) {
    // Assuming you have a function or a way to get the product name from product_id
    $productName = getProductFromId($item['product_id'], $db); // Implement this function
    $pdf->Cell(70, 10, $productName, 1);
    $pdf->Cell(30, 10, $item['quantity'], 1);
    $pdf->Cell(40, 10, '$' . $item['subtotal'], 1);
    $pdf->Ln();
}

// Output the PDF
$pdf->Output();

// Function to get product name from product_id
function getProductFromId($productId, $db) {
    // Fetch product details
    $query = "SELECT product_name FROM products WHERE product_id = ?";
    $stmt = $db->prepare($query);
    $stmt->execute([$productId]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);
    return $product['product_name'] ?? 'Unknown Product';
}
?>
