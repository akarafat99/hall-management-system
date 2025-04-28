<?php
include_once 'class-file/SessionManager.php';
$session = SessionStatic::class;
$session::ensureSessionStarted();

include_once 'popup-1.php';
if ($session::get('msg1') != null) {
    showPopup($session::get('msg1'));
    $session::delete('msg1');
}

if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    include_once 'class-file/User.php';
    $user = new User();
    $user->email = $email;
    $user->password = $password;
    $userCheck = $user->checkUserEmailWithStatus($user->email, $user->password, "user");

    if ($userCheck[0] == 1) {
        include_once 'popup-1.php';
        $session::storeObject('userObj', $user);
        $session::set('user', 'user');
        $session::set('msg1', $userCheck[1]);
    } else {
        include_once 'popup-1.php';
        showPopup($userCheck[1]);

        if($userCheck[0] == -1) {
            $user->user_id = (int)$userCheck[2];
            $user->updateStatus($user->user_id, -2);
        }
    }
}

if ($session::get('user') != null) {
    echo '<script type="text/javascript">
            window.location.href = "index.php";
          </script>';
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>MM Hall</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Fonts: Roboto for Material Design look -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
</head>

<body>
    <!-- Parent container with flex and min-vh-100 -->
    <div class="d-flex flex-column min-vh-100">
        <!-- Main content area -->
        <div class="flex-grow-1">
            <!-- Navbar Section Start -->
            <?php
            if ($session::get('user') !== null) {
                include_once 'student/navbar-student-1.php';
            } else {
                include_once 'student/navbar-student-2.php';
            }
            ?>
            <!-- Navbar Section End -->

            <!-- Login Section Start -->
            <section class="auth-section mt-5">
                <div class="container">
                    <div class="row justify-content-center">
                        <!-- Adjusted column width for responsiveness -->
                        <div class="col-md-6">
                            <div class="card shadow-sm border-0">
                                <div class="card-header bg-primary text-white text-center">
                                    <h4 class="mb-0">Login</h4>
                                </div>
                                <div class="card-body">
                                    <form method="post" action="" enctype="multipart/form-data">
                                        <div class="mb-3">
                                            <label for="email" class="form-label">Email</label>
                                            <input type="email" class="form-control" id="email" name="email" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="password" class="form-label">Password</label>
                                            <input type="password" class="form-control" id="password" name="password" required>
                                        </div>
                                        <!-- Forgot password link -->
                                        <div class="mb-3 text-end">
                                            <a href="reset-pass-1.php" class="small text-decoration-none">Forgot Password?</a>
                                        </div>
                                        <div class="text-center">
                                            <button type="submit" name="login" class="btn btn-primary px-4">Login</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <!-- Sign Up Section Start -->
                            <div class="card mt-4 shadow-sm border-0">
                                <div class="card-body text-center">
                                    <p class="mb-3">Don't have an account?</p>
                                    <a href="registration-step-1.php" class="btn btn-success px-4">Sign Up</a>
                                </div>
                            </div>
                            <!-- Sign Up Section End -->
                        </div>
                    </div>
                </div>
            </section>
            <!-- Login Section End -->
        </div>

        <!-- Footer Section -->
        <footer class="bg-dark text-white mt-auto">
            <div class="container py-4 text-center">
                <p class="mb-0">&copy; 2025 MM Hall. All rights reserved.</p>
            </div>
        </footer>
    </div>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
