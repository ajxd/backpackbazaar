<?php


// Include necessary files and initialize the database
include_once '../server/db_conn.php';
include_once '../server/db_init.php';
include_once '../models/Product.php';

// Create an instance of the Product class
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
        <h2 class="mb-5 text-capitalize">Thanks for shopping with us, <?php echo $_SESSION['username']; ?>!</h2>
        <form action="index.php" method="post">
          <button type="submit" class="btn btn-outline-dark mt-auto">
            Go back to product page ?
          </button>
        </form>
      </div>
    </section>
    <form action="generate_invoice.php" method="post">
      <button type="submit" class="btn btn-primary">Download Invoice</button>
    </form>
  </div>
  <?php include 'layouts/footer.php' ?>
</body>

</html>