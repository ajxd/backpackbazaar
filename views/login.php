<!DOCTYPE html>
<html lang="en">
<?php include './layouts/header.php' ?>
<body>
  <?php include './layouts/navbar.php' ?>
  <?php
	include_once '../server/db_conn.php';
	include_once '../server/db_init.php';
	include_once '../models/UserLogin.php';

	// Check if the form is submitted
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		$loginModel = new Login($db);

		// Validate user input
		$email = htmlspecialchars($_POST['email']);
		$password = htmlspecialchars($_POST['password']);

		// You may want to add more validation here

		// Login the user
		$user = $loginModel->loginUser($email, $password);

		if ($user) {
			// Start a session and store user information
			session_start();
			$_SESSION['user_id'] = $user['user_id'];
			$_SESSION['username'] = $user['username'];

			// Redirect to a welcome page or dashboard
			header("Location: welcome.php");
			exit();
		} else {
			$errorMessage = "Login failed. Please check your email and password.";
		}
	}
?>

<section class="h-100 m-5">
    <div class="container h-100">
        <div class="row justify-content-sm-center h-100">
            <div class="col-xxl-4 col-xl-5 col-lg-5 col-md-7 col-sm-9">
                <div class="card shadow">
                    <div class="card-body p-5">
                        <h1 class="fs-4 card-title fw-bold mb-4">Login</h1>
                        <?php if (isset($errorMessage)) : ?>
                            <div class="alert alert-danger mb-4">
                                <?php echo $errorMessage; ?>
                            </div>
                        <?php endif; ?>
                        <form method="POST" class="needs-validation" novalidate="" autocomplete="off">
                            <div class="mb-3">
                                <label class="mb-2 text-muted" for="email">E-Mail Address</label>
                                <input id="email" type="email" class="form-control" name="email" value="" required autofocus>
                                <div class="invalid-feedback">
                                    Email is invalid
                                </div>
                            </div>

                            <div class="mb-3">
                                <div class="mb-2 w-100">
                                    <label class="text-muted" for="password">Password</label>
                                    <a href="#" class="float-end text-decoration-none">
                                        Forgot Password?
                                    </a>
                                </div>
                                <input id="password" type="password" class="form-control" name="password" required>
                                <div class="invalid-feedback">
                                    Password is required
                                </div>
                            </div>

                            <div class="d-flex mt-5 align-items-center">
                                <div class="form-check">
                                    <input type="checkbox" name="remember" id="remember" class="form-check-input">
                                    <label for="remember" class="form-check-label">Remember Me</label>
                                </div>
                                <button type="submit" class="btn ms-auto text-white w-50" style="background-color: #ee4623">
                                    Login
                                </button>
                            </div>
                        </form>
                    </div>
                    <div class="card-footer py-3 mt-3 border-0">
                        <div class="text-center">
                            Don't have an account? <a href="register.php" class="text-dark">Create One</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
</body>
</html>
