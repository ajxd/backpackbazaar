<?php
include_once '../server/db_conn.php';
include_once '../server/db_init.php';
include_once '../models/Product.php';

$product = new Product($db);
$products = $product->getFeaturedProducts();
?>

<!DOCTYPE html>
<html lang="en">
<?php include './layouts/header.php' ?>

<body>
  <?php include './layouts/navbar.php' ?>
  <div class="container">
    <section class="py-5">
      <div class="container px-4 px-lg-5 mt-5">
        <h2 class="mb-5">Featured products</h2>
        <div class="row gx-4 gx-lg-5 row-cols-2 row-cols-md-3 row-cols-xl-4 justify-content-center">
          <?php foreach ($products as $product) : ?>
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