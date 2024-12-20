<!DOCTYPE html>
<html lang="en">
<?php include './layouts/header.php' ?>
<?php
  include_once '../server/db_conn.php';
  include_once '../server/db_init.php';
  include_once '../models/UserRegiser.php';
  
  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userModel = new User($db);

    // Validate user input
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $password = htmlspecialchars($_POST['password']);

    // You may want to add more validation here

    // Prepare user data for registration
    $userData = [
        'name' => $name,
        'email' => $email,
        'password' => $password,
    ];

    // Register the user
    if ($userModel->registerUser($userData)) {
        echo "Registration successful. Redirecting to login page...";
        header("Location: login.php"); // Redirect to login page
        exit();
    } else {
        echo "Registration failed. Please try again.";
    }
}
?>
<body>
  <?php include './layouts/navbar.php' ?>
  <section class="h-100 m-5">
    <div class="container h-100">
      <div class="row justify-content-sm-center h-100">
        <div class="col-xxl-4 col-xl-5 col-lg-5 col-md-7 col-sm-9">
          <div class="card shadow">
            <div class="card-body p-5">
              <h1 class="fs-4 card-title fw-bold mb-4">Register</h1>
              <form method="POST" class="needs-validation" novalidate="" autocomplete="off">
                <div class="mb-3">
                  <label class="mb-2 text-muted" for="name">Name</label>
                  <input id="name" type="text" class="form-control" name="name" value="" required autofocus>
                  <div class="invalid-feedback">
                    Name is required
                  </div>
                </div>

                <div class="mb-3">
                  <label class="mb-2 text-muted" for="email">E-Mail Address</label>
                  <input id="email" type="email" class="form-control" name="email" value="" required>
                  <div class="invalid-feedback">
                    Email is invalid
                  </div>
                </div>

                <div class="mb-3">
                  <label class="mb-2 text-muted" for="password">Password</label>
                  <input id="password" type="password" class="form-control" name="password" required>
                  <div class="invalid-feedback">
                    Password is required
                  </div>
                </div>

                <p class="form-text text-muted mb-3">
                  By registering you agree with our terms and condition.
                </p>

                <div class="align-items-center d-flex">
                  <button type="submit" class="btn w-100 text-white ms-auto" style="background-color: #ee4623">
                    Register
                  </button>
                </div>
              </form>
            </div>
            <div class="card-footer py-3 border-0">
              <div class="text-center">
                Already have an account? <a href="login.php" class="text-dark">Login</a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</body>

</html>