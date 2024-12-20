<?php
// Your existing database connection and session handling
include_once '../server/db_conn.php';
include_once '../server/db_init.php';
include_once '../models/Order.php';
include_once '../models/Product.php';

$product = new Product($db);

// Assuming your Order model is instantiated like this
$order = new Order($db);

// Initialize the cart session variable if it's not set
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Function to get product details by ID
function getProductById($db, $productId)
{
    $product = new Product($db);
    return $product->getProductById($productId);
}

// Retrieve product IDs from the session
$cartProductIds = $_SESSION['cart'];

// Retrieve product details based on product IDs
$cartProducts = [];
foreach ($cartProductIds as $productId) {
    $productDetails = getProductById($db, $productId);
    if ($productDetails) {
        $cartProducts[] = $productDetails;
    }
}

// Calculate total price and tax
$totalPrice = array_sum(array_column($cartProducts, 'price'));
$taxRate = 0.05;
$taxAmount = $totalPrice * $taxRate;
$totalAmount = $totalPrice + $taxAmount;

// Handling form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['payment'])) {
    // Check if the user is logged in
    if (!isset($_SESSION['user_id'])) {
        // Redirect to the login page if not logged in
        header("Location: login.php");
        exit;
    }

    // Get the user ID from the session
    $userId = $_SESSION['user_id'];

    // Check if the cart is not empty
    if (!empty($cartProductIds)) {
        // Create a comma-separated string of product IDs for storage in the database
        $productIdsString = implode(',', $cartProductIds);

        // Store order details in the database
        $orderId = $order->createOrder($totalAmount, $taxAmount, $productIdsString);

        // Clear the cart after successful payment
        $_SESSION['cart'] = [];

        // Redirect to a thank you page or order confirmation page
        header("Location: thank_you.php?order_id=$orderId");
        exit;
    } else {
        // Redirect to a page indicating that the cart is empty
        header("Location: cart_empty.php");
        exit;
    }
}


?>

<!DOCTYPE html>
<html lang="en">
<?php include './layouts/header.php' ?>

<body>
    <?php include './layouts/navbar.php' ?>
    <div class="container">

        <div class="row">
            <a href="product.php" class="my-3 btn btn-white text-capitalize w-25">
                <i class="bi bi-arrow-left pe-3"></i>
                Continue Shopping
            </a>
        </div>
        <div class="row justify-content-center my-4">
            <div class="col-8">
                <div class="card mb-4">
                    <div class="card-header">
                        <span class="float-start text-uppercase fs-5 fw-semibold">
                            Your items
                        </span>
                        <i class="bi bi-basket-fill float-end text-success-emphasis h4 ms-2"></i>
                    </div>
                    <div class="card-body">
                        <?php foreach ($cartProducts as $product) : ?>
                            <div class="row cart-div">
                                <div class="col-2 h-100">
                                    <!-- Image -->
                                    <img src="https://dummyimage.com/600x700/dee2e6/6c757d.jpg" class="object-fit-cover w-100 h-100" alt="<?php echo $product['product_name'] ?>" />
                                    <!-- Image -->
                                </div>
                                <div class="col-3">
                                    <!-- Data -->
                                    <p class="text-uppercase fw-bold mb-1"><?php echo $product['product_name'] ?></p>
                                    <div class="text-start text-justify">
                                        <p class="text-start text-justify" style="display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden;">
                                            <?php echo $product['description'] ?>
                                        </p>
                                    </div>
                                </div>
                                <!-- Quantity -->
                                <div class="col-2 text-center">
                                    <span class="text-uppercase h6">Price</span>
                                    <!-- Price -->
                                    <p class="mt-3 fw-bold"><?php echo $product['price'] ?></p>
                                    <!-- Price -->
                                </div>
                                <div class="col-3 text-center">
                                    <span class="text-uppercase h6">Quantity</span>
                                    <div class="input-group w-75 mt-3 ms-4">
                                        1
                                    </div>
                                </div>
                                <!-- Quantity -->
                                <div class="col-2 text-center">
                                    <span class="text-uppercase h6">SubTotal</span>
                                    <!-- Price -->
                                    <p class="mt-3 fw-bold"><?php echo $product['price'] ?></p>
                                    <!-- Price -->
                                </div>
                            </div>
                            <!-- Single item -->
                            <hr class="my-4" />
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <div class="col-4">
                <div class="card mb-4 text-uppercase">
                    <div class="card-header py-3">
                        <h5 class="mb-0 text-uppercase">order Summary</h5>
                    </div>
                    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
                        <div class="card-body">
                            <div class="input-group mt-3 mb-4">
                                <input type="text" class="form-control text-uppercase fs-6" placeholder="Enter promocode here" aria-label="Recipient's username" aria-describedby="basic-addon2">
                                <span class="input-group-text" id="basic-addon2">Apply</span>
                            </div>
                            <div class="list-group-item d-flex justify-content-between align-items-center border-0 px-0 pb-0 mb-4">
                                subtotal
                                <span>$ <?php echo $totalPrice; ?></span>
                            </div>
                            <div class="list-group-item d-flex justify-content-between align-items-center border-0 px-0 pb-0 mb-4">
                                Delivery fee
                                <span>$ 9.99</span>
                            </div>
                            <div class="list-group-item d-flex justify-content-between align-items-center px-0 border-0 pb-0 mb-4">
                                tax services and other fees (5%)
                                <span>$ <?php echo $taxAmount; ?></span>
                            </div>
                            <div class="list-group-item d-flex justify-content-between fw-bold align-items-center border-0 px-0 mb-4">
                                <div>you pay</div>
                                <span>$ <?php echo $totalAmount; ?></span>
                            </div>
                            <button type="submit" class="btn text-white w-100 my-3 the-bg" name="payment">
                                Make Payment
                            </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    </div>
    <?php include 'layouts/footer.php' ?>
</body>

</html>