<?php
include_once 'class-file/SessionManager.php';
$session = SessionStatic::class;
$session::ensureSessionStarted();

include_once 'class-file/EmailSender.php';
if($session::get('user') != null) {
    echo '<script type="text/javascript">
            window.location.href = "index.php";
          </script>';
    exit;
}

include_once 'popup-1.php';
if ($session::get('msg1') !== null) {
    showPopup($session::get('msg1'));
    $session::delete('msg1');
}

if (isset($_POST['register_1'])) {
    include_once 'class-file/User.php';

    $user = new User();
    $user->email    = $_POST['email'];

    // checking for status 0, 1, 2, -1
    if ($user->isEmailAvailable($user->email, [0, 1, -1, 2], null)) {
        $session::storeObject('tempUserObj', $user);
        $session::set('step', 2);
        $otp = rand(1000, 9999);
        $session::set('otp', $otp);
        $emailSender = new EmailSender();
        $emailSender->sendMail($user->email, 'Password Reset OTP #' . $otp, "Dear User, <br><br>Your OTP is: <b>$otp</b><br><br>Thank you. <br>JUST MM Hall");
        echo "<script>window.location.href='reset-pass-2.php';</script>";
    } else {
        showPopup("No email found. Please try again.");
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>MM Hall</title>
    <!-- Only Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet">
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

            <!-- Registration Form -->
            <div class="container my-5">
                <div class="row justify-content-center">
                    <div class="col-md-6">

                        <?php
                        // Display any popup messages
                        if ($session::get('msg1') !== null) {
                            showPopup($session::get('msg1'));
                            $session::delete('msg1');
                        }
                        ?>

                        <div class="card shadow-sm">
                            <div class="card-header text-center">
                                <h4 class="mb-0">Provide Valid Information</h4>
                            </div>
                            <div class="card-body">
                                <h5 class="card-title text-center mb-4">Step 1</h5>
                                <form method="post" action="">
                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email</label>
                                        <input
                                            type="email"
                                            class="form-control"
                                            id="email"
                                            name="email"
                                            required>
                                    </div>
                                    <div class="d-grid">
                                        <button
                                            type="submit"
                                            class="btn btn-primary"
                                            name="register_1">
                                            Send OTP
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

        </div>

        <!-- Footer Section -->
        <footer class="bg-dark text-white mt-auto">
            <div class="container py-4 text-center">
                <p class="mb-0">&copy; 2025 MM Hall. All rights reserved.</p>
            </div>
        </footer>
    </div>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>