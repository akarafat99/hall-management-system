<?php
include_once 'class-file/SessionManager.php';
$session = new SessionManager();

if ($session->get('step2') !== null) {
    $session->delete('step2');
    $sUser = $session->getObject('user');
    echo 'hello 1 <br>';
    include_once 'class-file/User.php';
    $user = new User();
    $session->copyProperties($sUser, $user);
    // Generate a new OTP.
    $otp = rand(1000, 9999);
    $session->set('otp', $otp);
}

if (isset($_POST['register_2'])) {
    include_once 'class-file/User.php';

    $user = $session->getObject('user');
    $otp = $session->get('otp');

    if ($_POST['otp'] != $otp) {
        echo "Invalid OTP";
    } else {
        echo "OTP Matched";
        $session->delete('otp');
        $session->set('msg', 'OTP Matched');
        echo '<script> window.location.href = "registration-step-3.php";</script>';
        exit();
    }
}

if (isset($_POST['resendotp'])) {
    include_once 'class-file/User.php';

    // Retrieve the user object from session.
    $user = $session->getObject('user');

    // Generate a new OTP.
    $otp = rand(1000, 9999);
    $session->set('otp', $otp);
}

?>




<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">


    <link href="https://fonts.googleapis.com/css?family=Muli:300,400,700,900" rel="stylesheet">
    <link rel="stylesheet" href="fonts/icomoon/style.css">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/jquery-ui.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/magnific-popup.min.css">
    <link rel="stylesheet" href="css/owl.carousel.min.css">
    <link rel="stylesheet" href="css/owl.theme.default.min.css">
    <link rel="stylesheet" href="css/owl.theme.default.min.css">

    <link rel="stylesheet" href="css/jquery.fancybox.min.css">

    <link rel="stylesheet" href="css/bootstrap-datepicker.css">

    <link rel="stylesheet" href="fonts/flaticon/font/flaticon.css">

    <link rel="stylesheet" href="css/aos.css">

    <link rel="stylesheet" href="css/style.css">

    <title>JUST Hall</title>


    <style>
        .card-header {
            background-color: #f4e90a;
            color: #201e1f;
        }

        .form-block {
            padding: 2rem;
            box-shadow: rgba(149, 157, 165, 0.2) 0px 8px 24px;
            border: none;

        }

        .form-info-title {
            color: #201e1f;
            font-weight: 600;
        }
    </style>

</head>

<body data-spy="scroll" data-target=".site-navbar-target" data-offset="300">

    <div class="site-mobile-menu site-navbar-target">
        <div class="site-mobile-menu-header">
            <div class="site-mobile-menu-close mt-3">
                <span class="icon-close2 js-menu-toggle"></span>
            </div>
        </div>
        <div class="site-mobile-menu-body"></div>
    </div>

    <header class="site-navbar py-4 js-sticky-header site-navbar-target" role="banner">

        <div class="container-fluid">
            <div class="d-flex align-items-center">
                <div class="site-logo mr-auto w-25"><a href="index.html">MM HALL</a></div>

                <div class="mx-auto text-center">
                    <nav class="site-navigation position-relative text-right" role="navigation">
                        <ul class="site-menu main-menu js-clone-nav mx-auto d-none d-lg-block  m-0 p-0">
                            <li><a href="index.html" class="nav-link">Home</a></li>
                            <li><a href="notice.html" class="nav-link">Notices</a></li>
                            <li class="nav-item dropdown"><a href="" class="nav-link dropdown-toggle"
                                    id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true"
                                    aria-expanded="false">Apply</a>

                                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="noc.html">Hall NOC</a>
                                    <a class="dropdown-item" href="seat-in-hall.html">Seat in hall</a>
                                    <a class="dropdown-item" href="#">Seat change in the hall</a>
                                </div>
                            </li>
                            <li><a href="#" class="nav-link">About Us</a></li>
                            <li><a href="#" class="nav-link">Contact Us</a></li>
                        </ul>
                    </nav>
                </div>

                <div class="ml-auto w-25">
                    <nav class="site-navigation position-relative text-right" role="navigation">
                        <ul class="site-menu main-menu site-menu-dark js-clone-nav mr-auto d-none d-lg-block m-0 p-0">
                            <!-- <li class="cta"> <a href="#" class="primary-button py-2 px-4">Dashboard</a></li> -->
                            <li class="nav-item dropdown"><a href="" class="nav-link primary-button dropdown-toggle"
                                    id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true"
                                    aria-expanded="false">Dashboard</a>

                                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="Admin Dashboard/Dashbaord.html">View Profile</a>
                                    <a class="dropdown-item" href="#">My Applications</a>
                                    <a class="dropdown-item" href="#">JUST Wallet</a>
                                </div>
                            </li>
                        </ul>
                    </nav>
                    <a href="#"
                        class="d-inline-block d-lg-none site-menu-toggle js-menu-toggle text-black float-right"><span
                            class="icon-menu h3"></span></a>
                </div>
            </div>
        </div>

    </header>


    <!-- notice Banner Section Start -->
    <section class="notice-hero">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="notice-hero-text text-center">
                        <h1>Sign Up</h1>
                        <p><a href="index.html">Home</a> <span class="mx-2">/</span> <strong>registration</strong></p>
                    </div>
                </div>
            </div>
        </div>
        <span class="notice-hero-overlay"></span>
    </section>
    <!-- notice Banner Section End -->

    <!-- Registration Section Start -->
    <section class="auth-section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-10">
                    <div class="contact-form-wrapper">
                        <div class="card-header text-center">
                            <h4>Provide Each Input Valid Information</h4>
                        </div>
                        <div class="form-block">
                            <form method="post" action="" enctype="multipart/form-data">
                                <div class="text-center mb-5">
                                    <h5 class="form-info-title">Verify email</h5>
                                </div>
                                <div class="row">
                                    <div class="col-md-12 mb-3">
                                        <label for="email" class="form-label">OTP <?php echo $session->get('otp'); ?></label>
                                        <input type="text" class="form-control" id="email" name="otp" required>
                                    </div>
                                </div>

                                <div class="text-center">
                                    <button type="submit" class="primary-button"
                                        style="cursor: pointer;" name="register_2">Submit</button>
                                </div>
                            </form>
                            <form method="post" action="" enctype="multipart/form-data">
                                <div class="text-center">
                                    <button type="submit" class="primary-button" name="resendotp" id="resendOTP" disabled>Resend OTP (30)</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Registration Section End -->


    <!-- Footer Section -->
    <footer class="footer-section ">
        <div class="container">
            <div class="row">
                <div class="col-md-3">
                    <div class="footer-logo-wrapper">
                        <h3>HMS</h3>
                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit.</p>
                    </div>
                </div>

                <div class="col-md-3 ml-auto">
                    <div class="footer-link-col">
                        <h3>Links</h3>
                        <ul class="list-unstyled footer-links">
                            <li><a href="#">Home</a></li>
                            <li><a href="#">Courses</a></li>
                            <li><a href="#">Programs</a></li>
                            <li><a href="#">Teachers</a></li>
                        </ul>
                    </div>
                </div>

                <div class="col-md-3 ml-auto">
                    <div class="footer-link-col">
                        <h3>Links</h3>
                        <ul class="list-unstyled footer-links">
                            <li><a href="#">Home</a></li>
                            <li><a href="#">Courses</a></li>
                            <li><a href="#">Programs</a></li>
                            <li><a href="#">Teachers</a></li>
                        </ul>
                    </div>
                </div>

                <div class="col-md-3 ml-auto">
                    <div class="footer-link-col">
                        <h3>Social Media</h3>
                        <ul class="list-unstyled footer-social-links">
                            <li><a href="#"><i class="fa-brands fa-facebook"></i></a></li>
                            <li><a href="#"><i class="fa-brands fa-linkedin"></i></a></li>
                            <li><a href="#"><i class="fa-brands fa-twitter"></i></a></li>
                        </ul>
                    </div>
                </div>

            </div>

            <div class="row pt-5 mt-5 text-center">
                <div class="col-md-12">
                    <div class="border-top pt-5">
                        <p class="footer-copyright-text">
                            <!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
                            Copyright &copy;
                            <script>
                                document.write(new Date().getFullYear());
                            </script> JUST Credit <i class="icon-heart"
                                aria-hidden="true"></i> by <a href="#" target="_blank">Arafat &
                                Shakil</a>
                        </p>
                    </div>
                </div>

            </div>
        </div>
    </footer>

    <script src="js/jquery-3.3.1.min.js"></script>
    <script src="js/jquery-migrate-3.0.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/jquery.magnific-popup.min.js"></script>
    <script src="js/jquery-ui.js"></script>
    <script src="js/popper.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/owl.carousel.min.js"></script>
    <script src="js/jquery.stellar.min.js"></script>
    <script src="js/jquery.countdown.min.js"></script>
    <script src="js/bootstrap-datepicker.min.js"></script>
    <script src="js/jquery.easing.1.3.js"></script>
    <script src="js/aos.js"></script>
    <script src="js/jquery.fancybox.min.js"></script>
    <script src="js/jquery.sticky.js"></script>


    <script src="js/main.js"></script>


    <!-- JavaScript to Handle Visibility -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const yearDropdown = document.getElementById("year");
            const semesterDropdown = document.getElementById("semester");
            const universityMeritField = document.getElementById("university-merit-field");
            const cgpaField = document.getElementById("cgpa-field");

            function updateVisibility() {
                const year = yearDropdown.value;
                const semester = semesterDropdown.value;

                // Show University Merit only if it's First Year & First Semester
                if ((year === "1_bsc" || year === "1_msc") && semester === "1") {
                    universityMeritField.style.display = "block";
                    cgpaField.style.display = "none";
                } else {
                    universityMeritField.style.display = "none";
                    cgpaField.style.display = "block";
                }
            }

            yearDropdown.addEventListener("change", updateVisibility);
            semesterDropdown.addEventListener("change", updateVisibility);

            updateVisibility(); // Ensure correct display on page load
        });
    </script>


</body>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        var resendBtn = document.getElementById('resendOTP');
        var countdown = 10; // seconds

        var interval = setInterval(function() {
            countdown--;
            if (countdown > 0) {
                resendBtn.textContent = 'Resend OTP (' + countdown + ')';
            } else {
                resendBtn.textContent = 'Resend OTP';
                resendBtn.disabled = false;
                clearInterval(interval);
            }
        }, 1000);
    });
</script>


</html>