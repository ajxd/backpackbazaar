<?php

include_once '../server/db_conn.php';
include_once '../server/db_init.php';
include_once '../models/Product.php';

//get product id from query parameter
$product_id = isset($_GET['id']) ? $_GET['id'] : "";

$product = new Product($db);

// Handle form submission to add the product to the cart
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
    $product_id_to_add = isset($_POST['product_id']) ? $_POST['product_id'] : "";

    // Add the product ID to the session (you can modify this according to your cart structure)
    if ($product_id_to_add) {
        $_SESSION['cart'][] = $product_id_to_add;
        echo "Product added to cart: $product_id_to_add";
        echo "<pre>";
        print_r($_SESSION);
        echo "</pre>";
    }
}

// Check if product ID is provided
if ($product_id) {

    // Get product details
    $product_details = $product->getProductById($product_id);

    // Check if product exists
    if ($product_details) {
        // Extract product details
        $product_name = $product_details['product_name'];
        $description = $product_details['description'];
        $price = $product_details['price'];
    } else {
        // Product not found
        echo "Product not found!";
    }
} else {
    // Redirect to home page if no product ID is provided
    header("Location: index.php");
    exit;
}

// get related products and display them
$related_products = $product->getProductsByCategory($product_details['category_id']);
?>

<!DOCTYPE html>
<html lang="en">
<?php include './layouts/header.php' ?>

<body>
  <?php include './layouts/navbar.php' ?>
  <!-- Product section-->
  <section class="py-5">
    <div class="container px-4 px-lg-5 my-5">
      <div class="row gx-4 gx-lg-5 align-items-center">
        <div class="col-md-6"><img class="card-img-top mb-5 mb-md-0" src="https://dummyimage.com/600x700/dee2e6/6c757d.jpg" alt="..." /></div>
        <div class="col-md-6">
          <div class="small mb-1">SKU: BGB- <?php echo $product_id ?></div>
          <h1 class="display-5 fw-bolder"><?php echo $product_name ?></h1>
          <div class="fs-5 mb-5">
            <span class="text-decoration-line-through">$200.00</span>
            <span><?php echo $price ?></span>
          </div>
          <p class="lead"><?php echo $description ?></p>
          <div class="d-flex">
            <input class="form-control text-center me-3" id="inputQuantity" type="num" value="1" style="max-width: 3rem; height: 2.4rem;" />
            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
              <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
              <div class="card-footer p-4 pt-0 border-top-0 bg-transparent">
                <div class="text-center">
                  <button type="submit" class="btn btn-outline-dark mt-auto" name="add_to_cart">
                    <i class="bi-cart-fill me-1"></i>
                    Add to cart
                  </button>
                </div>
              </div>
            </form>

          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- Related items section-->
  <section class="py-5 bg-light">
    <div class="container px-4 px-lg-5 mt-5">
      <h2 class="fw-bolder mb-4">Related products</h2>
      <div class="row gx-4 gx-lg-5 row-cols-2 row-cols-md-3 row-cols-xl-4 justify-content-center">
        <?php foreach ($related_products as $product) : ?>
          <div class="col mb-5">
            <div class="card h-100">
              <!-- Sale badge-->
              <div class="badge bg-dark text-white position-absolute" style="top: 0.5rem; right: 0.5rem">Sale</div>
              <!-- Product image-->
              <img class="card-img-top" src="https://dummyimage.com/450x300/dee2e6/6c757d.jpg" alt="..." />
              <!-- Product details-->
              <div class="card-body p-4">
                <div class="text-center">
                  <!-- Product name-->
                  <h5 class="fw-bolder"><?php echo $product['product_name'] ?></h5>
                  <!-- Product reviews-->
                  <div class="d-flex justify-content-center small text-warning mb-2">
                    <div class="bi-star-fill"></div>
                    <div class="bi-star-fill"></div>
                    <div class="bi-star-fill"></div>
                    <div class="bi-star-fill"></div>
                    <div class="bi-star-fill"></div>
                  </div>
                  <!-- Product price-->
                  <span class="text-muted text-decoration-line-through">$200.00</span>
                  <?php echo $product['price'] ?>
                </div>
              </div>
              <!-- Product actions-->
              <form action="product.php" method="GET">
                <input type="hidden" name="id" value="<?php echo $product['product_id']; ?>">
                <div class="card-footer p-4 pt-0 border-top-0 bg-transparent">
                  <div class="text-center">
                    <button type="submit" class="btn btn-outline-dark mt-auto">
                      View Details
                    </button>
                  </div>
                </div>
              </form>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>
  <?php include 'layouts/footer.php' ?>
</body>
</html>
