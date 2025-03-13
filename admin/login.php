<?php
include_once '../class-file/SessionManager.php';

$session = new SessionManager();
include_once '../popup-1.php';
$session->get('msg1') ? showPopup($session->get('msg1')) : '';
$session->delete('msg1');
// $session->destroy();

if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    include_once '../class-file/User.php';
    $user = new User();
    $user->email = $email;
    $user->password = $password;
    $userCheck = $user->checkUserEmailWithStatus($user->email, $user->password, "admin");

    if ($userCheck[0] == "1") {
        // echo 'all ok <br>';
        include_once '../popup-1.php';
        showPopup($userCheck[1]);
        $session->storeObject('admin_user', $user);
        echo '<script>window.location.href = "dashboard.php";</script>';
    } elseif ($userCheck[0] == "10") {
        include_once '../popup-1.php';
        showPopup($userCheck[1]);
    } else {
        include_once '../popup-1.php';
        showPopup($userCheck[1]);
    }
}

$alreadyLoggedIn = false;
if ($session->getObject('admin_user') !== null) {
    $alreadyLoggedIn = true;
}


?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">


    <link href="https://fonts.googleapis.com/css?family=Muli:300,400,700,900" rel="stylesheet">
    <link rel="stylesheet" href="../fonts/icomoon/style.css">
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
        integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="../css/jquery-ui.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/magnific-popup.min.css">
    <link rel="stylesheet" href="../css/owl.carousel.min.css">
    <link rel="stylesheet" href="../css/owl.theme.default.min.css">
    <link rel="stylesheet" href="../css/owl.theme.default.min.css">

    <link rel="stylesheet" href="../css/jquery.fancybox.min.css">

    <link rel="stylesheet" href="../css/bootstrap-datepicker.css">

    <link rel="stylesheet" href="../fonts/flaticon/font/flaticon.css">

    <link rel="stylesheet" href="../css/aos.css">

    <link rel="stylesheet" href="../css/style.css">

    <title>JUST Hall</title>

</head>

<body data-spy="scroll" data-target=".site-navbar-target" data-offset="300">

    <div class="site-wrap">
        <div class="intro-section" id="home-section">
            <div class="slide-1" style="background-image: url('images/hero-bannar.jpg');"
                data-stellar-background-ratio="0.5">
                <div class="container">
                    <div class="row align-items-center">
                        <div class="col-12">
                            <div class="row align-items-center">
                                <div class="col-lg-6 mb-4">
                                    <h1 class="hero-title" data-aos="fade-up" data-aos-delay="100">MM Hall</h1>
                                    <p class="hero-sub-text mb-4" data-aos="fade-up" data-aos-delay="200">Lorem ipsum
                                        dolor sit amet
                                        consectetur adipisicing elit. Maxime ipsa nulla sed quis rerum amet natus quas
                                        necessitatibus.</p>

                                    <div class="button-wrapper" data-aos="fade-up" data-aos-delay="300">
                                        <a href="#" class="primary-button  py-3 px-5 text-uppercase">Get
                                            Started</a>
                                    </div>

                                </div>

                                <div class="col-lg-5 ml-auto" data-aos="fade-up" data-aos-delay="500">
                                    <?php if ($alreadyLoggedIn === false) { ?>
                                        <form action="" method="post" class="form-box" enctype="multipart/form-data">
                                            <h3 class="h4 text-black mb-4">Admin Login</h3>
                                            <div class="form-group">
                                                <input type="text" class="form-control" name="email" placeholder="Email Addresss">
                                            </div>
                                            <div class="form-group">
                                                <input type="password" class="form-control" name="password" placeholder="Password">
                                            </div>
                                            <div class="form-group">
                                                <div class="button-wrapper">
                                                    <button type="submit" name="login" class="primary-button">Login</button>
                                                </div>
                                            </div>
                                            <!-- dont have an account please register -->
                                            <div class="">
                                                <p>Don't have an account? <a href="registration-step-1.php"
                                                        style="color:#f4e90a">Register</a></p>
                                            </div>
                                        </form>
                                    <?php } else { ?>
                                        <h3 class="h4 form-box text-black mb-4">Welcome to Munshi Mohammad Meherulla Hall</h3>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

    </div>

    
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

    <script src="../js/jquery-3.3.1.min.js"></script>
    <script src="../js/jquery-migrate-3.0.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/jquery.magnific-popup.min.js"></script>
    <script src="../js/jquery-ui.js"></script>
    <script src="../js/popper.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
    <script src="../js/owl.carousel.min.js"></script>
    <script src="../js/jquery.stellar.min.js"></script>
    <script src="../js/jquery.countdown.min.js"></script>
    <script src="../js/bootstrap-datepicker.min.js"></script>
    <script src="../js/jquery.easing.1.3.js"></script>
    <script src="../js/aos.js"></script>
    <script src="../js/jquery.fancybox.min.js"></script>
    <script src="../js/jquery.sticky.js"></script>


    <script src="../js/main.js"></script>

</body>

</html>