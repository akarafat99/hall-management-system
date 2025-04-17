<?php
include_once 'class-file/SessionManager.php';
$session = SessionStatic::class;

if ($session::get('step') !== 2) {
    $session::set('msg1', 'Please complete the <b>step 1</b> first.');
    echo $session::get('msg1');
    echo '<script> window.location.href = "registration-step-1.php";</script>';
    exit();
}

if ($session::get('step') == 2) {
}

if (isset($_POST['register_2'])) {
    $otp = $session::get('otp');

    if ($_POST['otp'] != $otp) {
        include_once 'popup-1.php';
        showPopup('OTP does not match. Please try again');
    } else {
        echo "OTP Matched";
        $session::delete('otp');
        $session::set('msg1', 'OTP Matched');
        $session::set('step', 3);
        // Redirect to the next step
        echo '<script> window.location.href = "registration-step-3.php";</script>';
        exit();
    }
}

if (isset($_POST['resendotp']) && $session::get('step') == 2) {
    // Generate a new OTP.
    $otp = rand(1000, 9999);
    $session::set('otp', $otp);
}

?>




<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Only Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet">


    <title>JUST Hall</title>


</head>

<body>
    
    <div class="d-flex flex-column min-vh-100">
        <div class="flex-grow-1">
            <!-- Navbar Section Start -->
            <?php include_once 'student/navbar-student-2.php'; ?>
            <!-- Navbar Section End  -->

            <!-- Registration Form -->
            <div class="container my-5">
                <div class="row justify-content-center">
                    <div class="col-md-6">
                        <div class="card shadow-sm">
                            <div class="card-header text-center">
                                <h4 class="mb-0">Verify Email</h4>
                            </div>
                            <div class="card-body">
                                <p class="text-center mb-4">Enter the 4‑digit OTP we sent you</p>

                                <form method="post" action="">
                                    <div class="mb-3">
                                        <label for="otp" class="form-label">
                                            OTP
                                            <span class="fw-bold"><?php echo $session::get('otp'); ?></span>
                                        </label>
                                        <input
                                            type="text"
                                            class="form-control"
                                            id="otp"
                                            name="otp"
                                            required>
                                    </div>
                                    <div class="d-grid mb-3">
                                        <button
                                            type="submit"
                                            name="register_2"
                                            class="btn btn-primary">
                                            Submit
                                        </button>
                                    </div>
                                </form>

                                <form method="post" action="">
                                    <div class="d-grid">
                                        <button
                                            type="submit"
                                            name="resendotp"
                                            id="resendOTP"
                                            class="btn btn-link"
                                            disabled>
                                            Resend OTP (<span id="countdown">30</span>)
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <!-- Registration Form End -->
        </div>

        <!-- Footer -->
        <footer class="bg-dark text-white text-center py-3 mt-auto">
            <div class="container">
                <p class="mb-0">&copy; <?php echo date('Y'); ?> JUST Credit by Arafat &amp; Shakil</p>
            </div>
        </footer>
    </div>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var resendBtn = document.getElementById('resendOTP');
            var countdownEl = document.getElementById('countdown');
            var counter = 30;

            var timer = setInterval(function() {
                counter--;
                if (counter > 0) {
                    countdownEl.textContent = counter;
                } else {
                    clearInterval(timer);
                    resendBtn.disabled = false;
                    resendBtn.textContent = 'Resend OTP';
                }
            }, 1000);
        });
    </script>
</body>

</html>