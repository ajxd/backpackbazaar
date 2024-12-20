<?php

$currentPage = basename($_SERVER['PHP_SELF']);
$isUserLoggedIn = isset($_SESSION['user_id']); // Check if the user is logged in

// Function to display either "Login" or "Logout" based on the user's login status
function displayLoginLogoutLink() {
    if (isset($_SESSION['user_id'])) {
        echo '<a class="nav-link navigation me-3" href="logout.php">Logout</a>';
    } else {
        echo '<a class="nav-link navigation me-3" href="login.php">Login</a>';
    }
}

?>
<link rel="stylesheet" href="../assets/styles/style.css">
<nav class="navbar bg-white navbar-expand-lg">
    <div class="container">
        <a class="navbar-brand fs-3 fw-semibold" href="index.php">
            <img src="../assets/logo/BagpackBazar.png" alt="logo" class="me-2" width="100">
        </a>
        <div class="navbar-nav navigation fw-semibold">
            <a class="nav-link navigation me-3 <?php echo ($currentPage == 'index.php') ? 'active' : ''; ?>" href="index.php">Home</a>
            <a class="nav-link navigation me-3 <?php echo ($currentPage == 'product.php') ? 'active' : ''; ?>" href="product.php">Products</a>
            <a class="nav-link navigation me-3 <?php echo ($currentPage == 'cart.php') ? 'active' : ''; ?>" href="cart.php">Cart</a>
            <a class="nav-link navigation me-3 <?php echo ($currentPage == 'checkout.php') ? 'active' : ''; ?>" href="checkout.php">Checkout</a>
            
            <?php if ($isUserLoggedIn) : ?>
                <!-- Display "Logout" if the user is logged in -->
                <?php displayLoginLogoutLink(); ?>
            <?php else : ?>
                <!-- Display "Registration" and "Login" if the user is not logged in -->
                <a class="nav-link navigation me-3 <?php echo ($currentPage == 'register.php') ? 'active' : ''; ?>" href="register.php">Registration</a>
                <a class="nav-link navigation me-3 <?php echo ($currentPage == 'login.php') ? 'active' : ''; ?>" href="login.php">Login</a>
            <?php endif; ?>
        </div>
    </div>
</nav>
