<?php

include_once '../server/db_conn.php';
include_once '../server/db_init.php';
include_once '../models/Product.php';

// Function to get product details by ID
function getProductById($db, $productId)
{
    $product = new Product($db);
    return $product->getProductById($productId);
}

// Initialize the cart session variable if it's not set
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Retrieve product IDs from the session
$cartProductIds = $_SESSION['cart'];

// Retrieve product details based on product IDs
$cartProducts = [];
foreach ($cartProductIds as $productId) {
    $productDetails = getProductById($db, $productId);
    if ($productDetails) {
        $cartProducts[] = $productDetails;
    } else {
        // Debugging: Output the product ID that caused an issue
        echo "Product details not found for ID: $productId";
    }
}

// Handle item removal
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['remove_product_id'])) {
    $removeProductId = $_POST['remove_product_id'];

    // Find and remove the product ID from the cart session
    $cartProductIds = $_SESSION['cart'];
    $index = array_search($removeProductId, $cartProductIds);

    if ($index !== false) {
        unset($cartProductIds[$index]);
        $_SESSION['cart'] = array_values($cartProductIds); // Reset array keys
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<?php include './layouts/header.php' ?>

<body>
    <?php include './layouts/navbar.php' ?>

    <div class="container">
        <?php if (!empty($cartProducts)) : ?>
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
                                        <!-- Remove From Cart Button -->
                                        <div class="d-flex">
                                            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                                                <input type="hidden" name="remove_product_id" value="<?php echo $product['product_id']; ?>">
                                                <button type="submit" class="shadow btn btn-primary btn-sm me-2" data-mdb-toggle="tooltip" title="Remove item" name>
                                                    <i class="bi bi-trash-fill px-2"></i>
                                                </button>
                                            </form>
                                            <form action="#" method="POST">
                                                <button type="submit" class="shadow btn btn-danger btn-sm mb-2" data-mdb-toggle="tooltip" title="Move to the wish list">
                                                    <i class="bi bi-heart-fill px-2"></i>
                                                </button>
                                            </form>
                                        </div>
                                        <!-- Add to Favorite Button -->
                                        <!-- Data -->
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
            </div>
        <?php else : ?>
            <div class="my-4 justify-content-center text-center">
                <img src="../assets/images/empty_cart.png" alt="Empty Cart" class="w-25">
                <p class="text-capitalize text-muted h1">Your cart is empty</p>
                <p class="text-capitalize text-muted">looks like you have not added anything to your cart. go ahead &
                    explore top delicious categories</p>
                <div class="mb-5 justify-content-center">
                    <a href="product.php" class="btn btn-dark text-capitalize text-center w-50">Continue
                        shopping</a>
                </div>
            </div>
        <?php endif; ?>
    </div>
    </div>
    <x-scripts />
</body> 
</body>

</html>