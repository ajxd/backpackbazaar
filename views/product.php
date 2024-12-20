<?php
include_once '../server/db_conn.php';
include_once '../server/db_init.php';
include_once '../models/Product.php';

// Filter products based on category
$categoryFilter = isset($_GET['category']) ? $_GET['category'] : 'all';

$product = new Product($db);
$categories = $product->getCategories();

if ($categoryFilter === 'all') {
  $products = $product->getAllProducts();
} else {
  $products = $product->getProductsByCategory($categoryFilter);
}

?>

<!DOCTYPE html>
<html lang="en">
<?php include './layouts/header.php' ?>

<body>
  <?php include './layouts/navbar.php' ?>
  <div class="container">
    <section class="py-5">
      <div class="container row px-4 px-lg-5 mt-5">
        <div class="col-4">
          <h2 class="mb-5">Products</h2>
        </div>
        <div class="col-8 px-5">
          <!-- Filter by category -->
          <form id="filterForm" method="GET" action="" class="d-flex">
            <label for="category" class="fw-bold pt-1 px-3 w-50">Filter by Category:</label>
            <select class="form-select" aria-label="Categories" name="category" id="category">
              <?php
              foreach ($categories as $category) {
                $categoryId = $category['category_id'];
                $categoryName = $category['category_name'];
                echo "<option value=\"$categoryId\"";
                echo ($categoryFilter == $categoryId || ($categoryFilter === 'all' && $categoryId == 100)) ? ' selected' : '';
                echo ">$categoryName</option>";
              }
              ?>
            </select>
            <button type="submit" class="btn btn-dark mt-auto ms-3 w-25">Filter</button>
          </form>
        </div>


        <!-- Featured products heading -->
        <div class="row gx-4 gx-lg-5 row-cols-2 row-cols-md-3 row-cols-xl-4 justify-content-center">
          <?php foreach ($products as $product) : ?>
            <div class="col mb-5">
              <div class="card">
                <!-- Sale badge-->
                <div class="badge bg-dark text-white position-absolute" style="top: 0.5rem; right: 0.5rem">Sale</div>
                <!-- Product image-->
                <img class="card-img-top" src="https://dummyimage.com/450x300/dee2e6/6c757d.jpg" alt="..." />
                <!-- Product details-->
                <div class="card-body p-4">
                  <div class="text-center">
                    <!-- Product name-->
                    <h5 class="fw-bolder"><?php echo $product['product_name']; ?></h5>
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
                    <?php echo $product['price']; ?>
                  </div>
                </div>
                <!-- Product actions-->
                <form action="product_item.php" method="GET">
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
  </div>
  <?php include 'layouts/footer.php' ?>
</body>

</html>