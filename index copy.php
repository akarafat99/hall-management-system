<?php
include_once 'class-file/SessionManager.php';

$session = SessionStatic::class;
// $session::destroy();
include_once 'popup-1.php';
$session::get('msg1') ? showPopup($session::get('msg1')) : '';
$session::delete('msg1');

if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    include_once 'class-file/User.php';
    $user = new User();
    $user->email = $email;
    $user->password = $password;
    $userCheck = $user->checkUserEmailWithStatus($user->email, $user->password);

    if ($userCheck[0] == 1) {
        // echo 'all ok <br>';
        include_once 'popup-1.php';
        showPopup($userCheck[1]);
        $session::storeObject('user', $user);
    } else {
        include_once 'popup-1.php';
        showPopup($userCheck[1]);
    }
}

$alreadyLoggedIn = false;
if ($session::getObject('user') !== null) {
    $alreadyLoggedIn = true;
}


?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

</head>

<body data-spy="scroll" data-target=".site-navbar-target" data-offset="300">
    <!-- Include the navbar from a separate file -->
    <?php include_once 'student/navbar-student.php'; ?>


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
                                    <p class="hero-sub-text mb-4" data-aos="fade-up" data-aos-delay="200">
                                    Munshi Mohhamad Meherulla Hall.</p>

                                    <div class="button-wrapper" data-aos="fade-up" data-aos-delay="300">
                                        <a href="#" class="primary-button  py-3 px-5 text-uppercase">Get
                                            Started</a>
                                    </div>

                                </div>

                                <div class="col-lg-5 ml-auto" data-aos="fade-up" data-aos-delay="500">
                                    <?php if ($alreadyLoggedIn === false) { ?>
                                        <form action="" method="post" class="form-box" enctype="multipart/form-data">
                                            <h3 class="h4 text-black mb-4">Login</h3>
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

    <section class="section-notice">
        <div class="container">
            <div class="section-heading-wrapper section-notice-heading-wrapper" data-aos="fade-up" data-aos-delay="50">
                <div class="section-title-wrapper">
                    <h2>Latest Notice</h2>
                </div>
                <div class="button-icon-wrapper">
                    <a class="button-icon" href="notice.html">See More
                        <svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg"
                            xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 31.49 31.49"
                            xml:space="preserve" class="arrow-icon" style="enable-background:new 0 0 31.49 31.49;"
                            data-v-022ed2bf="">
                            <path d="M21.205,5.007c-0.429-0.444-1.143-0.444-1.587,0c-0.429,0.429-0.429,1.143,0,1.571l8.047,8.047H1.111
                            C0.492,14.626,0,15.118,0,15.737c0,0.619,0.492,1.127,1.111,1.127h26.554l-8.047,8.032c-0.429,0.444-0.429,1.159,0,1.587
                            c0.444,0.444,1.159,0.444,1.587,0l9.952-9.952c0.444-0.429,0.444-1.143,0-1.571L21.205,5.007z"
                                style="fill:#1E201D;"></path>
                        </svg>
                    </a>
                </div>
            </div>
            <div class="row justify-content-center" data-aos="fade-up" data-aos-delay="200">
                <!-- Single Card Notice -->
                <div class="col-lg-6">
                    <div class="notice-card">
                        <div class="notice-date-wrap">
                            <p class="notice-date">24 Oct 2024</p>
                            <svg data-v-abe9ddd2="" aria-hidden="true" focusable="false" data-prefix="fas"
                                data-icon="thumbtack" role="img" xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 384 512" class="fa-fw mr-2 svg-inline--fa fa-thumbtack fa-w-12 pin__icon"
                                style="max-width: 1.7rem;">
                                <path
                                    d="M298.028 214.267L285.793 96H328c13.255 0 24-10.745 24-24V24c0-13.255-10.745-24-24-24H56C42.745 0 32 10.745 32 24v48c0 13.255 10.745 24 24 24h42.207L85.972 214.267C37.465 236.82 0 277.261 0 328c0 13.255 10.745 24 24 24h136v104.007c0 1.242.289 2.467.845 3.578l24 48c2.941 5.882 11.364 5.893 14.311 0l24-48a8.008 8.008 0 0 0 .845-3.578V352h136c13.255 0 24-10.745 24-24-.001-51.183-37.983-91.42-85.973-113.733z">
                                </path>
                            </svg>
                        </div>
                        <div class="notice-text-wrap">
                            <p class="notice-text mb-4">
                                ফিস প্রদান সংক্রান্ত বিজ্ঞপ্তি 1 (Notification regarding Payment
                                of Fees 1) (সংশোধিত)
                            </p>
                        </div>
                        <div class="button-wrapper">
                            <a href="#" class="primary-button">Download</a>
                        </div>
                    </div>
                </div>
                <!-- Single Card Notice -->
                <!-- Single Card Notice -->
                <div class="col-lg-6">
                    <div class="notice-card">
                        <div class="notice-date-wrap">
                            <p class="notice-date">24 Oct 2024</p>
                            <svg data-v-abe9ddd2="" aria-hidden="true" focusable="false" data-prefix="fas"
                                data-icon="thumbtack" role="img" xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 384 512" class="fa-fw mr-2 svg-inline--fa fa-thumbtack fa-w-12 pin__icon"
                                style="max-width: 1.7rem;">
                                <path
                                    d="M298.028 214.267L285.793 96H328c13.255 0 24-10.745 24-24V24c0-13.255-10.745-24-24-24H56C42.745 0 32 10.745 32 24v48c0 13.255 10.745 24 24 24h42.207L85.972 214.267C37.465 236.82 0 277.261 0 328c0 13.255 10.745 24 24 24h136v104.007c0 1.242.289 2.467.845 3.578l24 48c2.941 5.882 11.364 5.893 14.311 0l24-48a8.008 8.008 0 0 0 .845-3.578V352h136c13.255 0 24-10.745 24-24-.001-51.183-37.983-91.42-85.973-113.733z">
                                </path>
                            </svg>
                        </div>
                        <div class="notice-text-wrap">
                            <p class="notice-text mb-4">
                            ফিস প্রদান সংক্রান্ত বিজ্ঞপ্তি 1 (Notification regarding Payment
                            of Fees 1)
                            </p>
                        </div>
                        <div class="button-wrapper">
                            <a href="#" class="primary-button">Download</a>
                        </div>
                    </div>
                </div>
                <!-- Single Card Notice -->
                <!-- Single Card Notice -->
                <div class="col-lg-6">
                    <div class="notice-card">
                        <div class="notice-date-wrap">
                            <p class="notice-date">24 Oct 2024</p>
                            <svg data-v-abe9ddd2="" aria-hidden="true" focusable="false" data-prefix="fas"
                                data-icon="thumbtack" role="img" xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 384 512" class="fa-fw mr-2 svg-inline--fa fa-thumbtack fa-w-12 pin__icon"
                                style="max-width: 1.7rem;">
                                <path
                                    d="M298.028 214.267L285.793 96H328c13.255 0 24-10.745 24-24V24c0-13.255-10.745-24-24-24H56C42.745 0 32 10.745 32 24v48c0 13.255 10.745 24 24 24h42.207L85.972 214.267C37.465 236.82 0 277.261 0 328c0 13.255 10.745 24 24 24h136v104.007c0 1.242.289 2.467.845 3.578l24 48c2.941 5.882 11.364 5.893 14.311 0l24-48a8.008 8.008 0 0 0 .845-3.578V352h136c13.255 0 24-10.745 24-24-.001-51.183-37.983-91.42-85.973-113.733z">
                                </path>
                            </svg>
                        </div>
                        <div class="notice-text-wrap">
                            <p class="notice-text mb-4">
                                স্নাতক পর্যায়ের সেমিস্টার ফিস প্রদান সংক্রান্ত বিজ্ঞপ্তি (Notification regarding Payment
                                of Undergraduate Semester Fees) (সংশোধিত)
                            </p>
                        </div>
                        <div class="button-wrapper">
                            <a href="#" class="primary-button">Download</a>
                        </div>
                    </div>
                </div>
                <!-- Single Card Notice -->
                <!-- Single Card Notice -->
                <div class="col-lg-6">
                    <div class="notice-card">
                        <div class="notice-date-wrap">
                            <p class="notice-date">24 Oct 2024</p>
                            <svg data-v-abe9ddd2="" aria-hidden="true" focusable="false" data-prefix="fas"
                                data-icon="thumbtack" role="img" xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 384 512" class="fa-fw mr-2 svg-inline--fa fa-thumbtack fa-w-12 pin__icon"
                                style="max-width: 1.7rem;">
                                <path
                                    d="M298.028 214.267L285.793 96H328c13.255 0 24-10.745 24-24V24c0-13.255-10.745-24-24-24H56C42.745 0 32 10.745 32 24v48c0 13.255 10.745 24 24 24h42.207L85.972 214.267C37.465 236.82 0 277.261 0 328c0 13.255 10.745 24 24 24h136v104.007c0 1.242.289 2.467.845 3.578l24 48c2.941 5.882 11.364 5.893 14.311 0l24-48a8.008 8.008 0 0 0 .845-3.578V352h136c13.255 0 24-10.745 24-24-.001-51.183-37.983-91.42-85.973-113.733z">
                                </path>
                            </svg>
                        </div>
                        <div class="notice-text-wrap">
                            <p class="notice-text mb-4">
                                স্নাতক পর্যায়ের সেমিস্টার ফিস প্রদান সংক্রান্ত বিজ্ঞপ্তি (Notification regarding Payment
                                of Undergraduate Semester Fees) (সংশোধিত)
                            </p>
                        </div>
                        <div class="button-wrapper">
                            <a href="#" class="primary-button">Download</a>
                        </div>
                    </div>
                </div>
                <!-- Single Card Notice -->
            </div>
        </div>
    </section>


    <section class="section-gallery">
        <div class="container">
            <div class="section-heading-wrapper section-gallery-heading-wrapper" data-aos="fade-up" data-aos-delay="50">
                <div class="section-title-wrapper gallery-heading-wrapper">
                    <h2 class="text-center">Our Hall Gallery</h2>
                    <p>Munshi Mohhamad Meherulla Hall</p>
                </div>
            </div>

            <!-- owl carousel -->
            <!-- <div class="sc-gallery style-01">
                <div class="gallery-slider owl-carousel owl-theme owl-loaded owl-drag">

                    <div class="owl-stage-outer">
                        <div class="owl-stage"
                            style="transform: translate3d(-1185px, 0px, 0px); transition: 0.75s; width: 3753px;">
                            <div class="owl-item cloned" style="width: 182.5px; margin-right: 15px;">
                                <div class="item">
                                    <div class="gallery">
                                        <div class="gallery">
                                            <a href="#gallery-2" class="btn-gallery"><img src="images/gallery/img-7.jpg"
                                                    alt=""></a>
                                            <div id="gallery-2" class="hidden">
                                                <a href="images/gallery/img-2.jpg"><img src="images/gallery/img-2.jpg"
                                                        alt=""></a>
                                                <a href="images/gallery/img-7.jpg"><img src="images/gallery/img-7.jpg"
                                                        alt=""></a>
                                                <a href="images/gallery/img-8.jpg"><img src="images/gallery/img-8.jpg"
                                                        alt=""></a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="content">
                                        <h4 class="title"><a href="blog-single-gallery.html">Restaurant</a></h4>
                                        <span class="count">3 photos</span>
                                    </div>
                                </div>
                            </div>
                            <div class="owl-item cloned" style="width: 182.5px; margin-right: 15px;">
                                <div class="item">
                                    <div class="gallery">
                                        <a href="#gallery-3" class="btn-gallery"><img src="images/gallery/img-9.jpg"
                                                alt=""></a>
                                        <div id="gallery-3" class="hidden">
                                            <a href="images/gallery/img-12.jpg"><img src="images/gallery/img-12.jpg"
                                                    alt=""></a>
                                            <a href="images/gallery/img-1.jpg"><img src="images/gallery/img-1.jpg"
                                                    alt=""></a>
                                            <a href="images/gallery/img-9.jpg"><img src="images/gallery/img-9.jpg"
                                                    alt=""></a>
                                        </div>
                                    </div>
                                    <div class="content">
                                        <h4 class="title"><a href="blog-single-gallery.html">Pool</a></h4>
                                        <span class="count">3 photos</span>
                                    </div>
                                </div>
                            </div>
                            <div class="owl-item cloned" style="width: 182.5px; margin-right: 15px;">
                                <div class="item">
                                    <div class="gallery">
                                        <a href="#gallery-4" class="btn-gallery"><img src="images/gallery/img-13.jpg"
                                                alt=""></a>
                                        <div id="gallery-4" class="hidden">
                                            <a href="images/gallery/img-14.jpg"><img src="images/gallery/img-14.jpg"
                                                    alt=""></a>
                                            <a href="images/gallery/img-7.jpg"><img src="images/gallery/img-7.jpg"
                                                    alt=""></a>
                                            <a href="images/gallery/img-13.jpg"><img src="images/gallery/img-13.jpg"
                                                    alt=""></a>
                                        </div>
                                    </div>
                                    <div class="content">
                                        <h4 class="title"><a href="blog-single-gallery.html">Activities</a></h4>
                                        <span class="count">3 photos</span>
                                    </div>
                                </div>
                            </div>
                            <div class="owl-item cloned" style="width: 182.5px; margin-right: 15px;">
                                <div class="item">
                                    <div class="gallery">
                                        <a href="#gallery-5" class="btn-gallery"><img src="images/gallery/img-1.jpg"
                                                alt=""></a>
                                        <div id="gallery-5" class="hidden">
                                            <a href="images/gallery/img-12.jpg"><img src="images/gallery/img-12.jpg"
                                                    alt=""></a>
                                            <a href="images/gallery/img-1.jpg"><img src="images/gallery/img-1.jpg"
                                                    alt=""></a>
                                            <a href="images/gallery/img-9.jpg"><img src="images/gallery/img-9.jpg"
                                                    alt=""></a>
                                        </div>
                                    </div>

                                    <div class="content">
                                        <h4 class="title"><a href="blog-single-gallery.html">Beach</a></h4>
                                        <span class="count">3 photos</span>
                                    </div>
                                </div>
                            </div>
                            <div class="owl-item cloned" style="width: 182.5px; margin-right: 15px;">
                                <div class="item">
                                    <div class="gallery">
                                        <a href="#gallery-7" class="btn-gallery"><img src="images/gallery/img-3.jpg"
                                                alt=""></a>
                                        <div id="gallery-6" class="hidden">
                                            <a href="images/gallery/img-4.jpg"><img src="images/gallery/img-4.jpg"
                                                    alt=""></a>
                                            <a href="images/gallery/img-7.jpg"><img src="images/gallery/img-7.jpg"
                                                    alt=""></a>
                                            <a href="images/gallery/img-3.jpg"><img src="images/gallery/img-3.jpg"
                                                    alt=""></a>
                                        </div>
                                    </div>
                                    <div class="content">
                                        <h4 class="title"><a href="blog-single-gallery.html">Spa</a></h4>
                                        <span class="count">3 photos</span>
                                    </div>
                                </div>
                            </div>
                            <div class="owl-item cloned" style="width: 182.5px; margin-right: 15px;">
                                <div class="item">
                                    <div class="gallery">
                                        <a href="#gallery-7" class="btn-gallery"><img src="images/gallery/img-14.jpg"
                                                alt=""></a>
                                        <div id="gallery-7" class="hidden">
                                            <a href="images/gallery/img-13.jpg"><img src="images/gallery/img-13.jpg"
                                                    alt=""></a>
                                            <a href="images/gallery/img-14.jpg"><img src="images/gallery/img-14.jpg"
                                                    alt=""></a>
                                            <a href="images/gallery/img-12.jpg"><img src="images/gallery/img-12.jpg"
                                                    alt=""></a>
                                        </div>
                                    </div>
                                    <div class="content">
                                        <h4 class="title"><a href="blog-single-gallery.html">Outdoor</a></h4>
                                        <span class="count">3 photos</span>
                                    </div>
                                </div>
                            </div>
                            <div class="owl-item active" style="width: 182.5px; margin-right: 15px;">
                                <div class="item">
                                    <div class="gallery">
                                        <a href="#gallery-1" class="btn-gallery"><img src="images/gallery/img-6.jpg"
                                                alt=""></a>
                                        <div id="gallery-1" class="hidden">
                                            <a href="images/gallery/img-10.jpg"><img src="images/gallery/img-10.jpg"
                                                    alt=""></a>
                                            <a href="images/gallery/img-6.jpg"><img src="images/gallery/img-6.jpg"
                                                    alt=""></a>
                                            <a href="images/gallery/img-11.jpg"><img src="images/gallery/img-11.jpg"
                                                    alt=""></a>
                                        </div>
                                    </div>
                                    <div class="content">
                                        <h4 class="title"><a href="blog-single-gallery.html">Rooms</a></h4>
                                        <span class="count">3 photos</span>
                                    </div>
                                </div>
                            </div>
                            <div class="owl-item active" style="width: 182.5px; margin-right: 15px;">
                                <div class="item">
                                    <div class="gallery">
                                        <div class="gallery">
                                            <a href="#gallery-2" class="btn-gallery"><img src="images/gallery/img-7.jpg"
                                                    alt=""></a>
                                            <div id="gallery-2" class="hidden">
                                                <a href="images/gallery/img-2.jpg"><img src="images/gallery/img-2.jpg"
                                                        alt=""></a>
                                                <a href="images/gallery/img-7.jpg"><img src="images/gallery/img-7.jpg"
                                                        alt=""></a>
                                                <a href="images/gallery/img-8.jpg"><img src="images/gallery/img-8.jpg"
                                                        alt=""></a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="content">
                                        <h4 class="title"><a href="blog-single-gallery.html">Restaurant</a></h4>
                                        <span class="count">3 photos</span>
                                    </div>
                                </div>
                            </div>
                            <div class="owl-item active" style="width: 182.5px; margin-right: 15px;">
                                <div class="item">
                                    <div class="gallery">
                                        <a href="#gallery-3" class="btn-gallery"><img src="images/gallery/img-9.jpg"
                                                alt=""></a>
                                        <div id="gallery-3" class="hidden">
                                            <a href="images/gallery/img-12.jpg"><img src="images/gallery/img-12.jpg"
                                                    alt=""></a>
                                            <a href="images/gallery/img-1.jpg"><img src="images/gallery/img-1.jpg"
                                                    alt=""></a>
                                            <a href="images/gallery/img-9.jpg"><img src="images/gallery/img-9.jpg"
                                                    alt=""></a>
                                        </div>
                                    </div>
                                    <div class="content">
                                        <h4 class="title"><a href="blog-single-gallery.html">Pool</a></h4>
                                        <span class="count">3 photos</span>
                                    </div>
                                </div>
                            </div>
                            <div class="owl-item active" style="width: 182.5px; margin-right: 15px;">
                                <div class="item">
                                    <div class="gallery">
                                        <a href="#gallery-4" class="btn-gallery"><img src="images/gallery/img-13.jpg"
                                                alt=""></a>
                                        <div id="gallery-4" class="hidden">
                                            <a href="images/gallery/img-14.jpg"><img src="images/gallery/img-14.jpg"
                                                    alt=""></a>
                                            <a href="images/gallery/img-7.jpg"><img src="images/gallery/img-7.jpg"
                                                    alt=""></a>
                                            <a href="images/gallery/img-13.jpg"><img src="images/gallery/img-13.jpg"
                                                    alt=""></a>
                                        </div>
                                    </div>
                                    <div class="content">
                                        <h4 class="title"><a href="blog-single-gallery.html">Activities</a></h4>
                                        <span class="count">3 photos</span>
                                    </div>
                                </div>
                            </div>
                            <div class="owl-item active" style="width: 182.5px; margin-right: 15px;">
                                <div class="item">
                                    <div class="gallery">
                                        <a href="#gallery-5" class="btn-gallery"><img src="images/gallery/img-1.jpg"
                                                alt=""></a>
                                        <div id="gallery-5" class="hidden">
                                            <a href="images/gallery/img-12.jpg"><img src="images/gallery/img-12.jpg"
                                                    alt=""></a>
                                            <a href="images/gallery/img-1.jpg"><img src="images/gallery/img-1.jpg"
                                                    alt=""></a>
                                            <a href="images/gallery/img-9.jpg"><img src="images/gallery/img-9.jpg"
                                                    alt=""></a>
                                        </div>
                                    </div>

                                    <div class="content">
                                        <h4 class="title"><a href="blog-single-gallery.html">Beach</a></h4>
                                        <span class="count">3 photos</span>
                                    </div>
                                </div>
                            </div>
                            <div class="owl-item active" style="width: 182.5px; margin-right: 15px;">
                                <div class="item">
                                    <div class="gallery">
                                        <a href="#gallery-7" class="btn-gallery"><img src="images/gallery/img-3.jpg"
                                                alt=""></a>
                                        <div id="gallery-6" class="hidden">
                                            <a href="images/gallery/img-4.jpg"><img src="images/gallery/img-4.jpg"
                                                    alt=""></a>
                                            <a href="images/gallery/img-7.jpg"><img src="images/gallery/img-7.jpg"
                                                    alt=""></a>
                                            <a href="images/gallery/img-3.jpg"><img src="images/gallery/img-3.jpg"
                                                    alt=""></a>
                                        </div>
                                    </div>
                                    <div class="content">
                                        <h4 class="title"><a href="blog-single-gallery.html">Spa</a></h4>
                                        <span class="count">3 photos</span>
                                    </div>
                                </div>
                            </div>
                            <div class="owl-item" style="width: 182.5px; margin-right: 15px;">
                                <div class="item">
                                    <div class="gallery">
                                        <a href="#gallery-7" class="btn-gallery"><img src="images/gallery/img-14.jpg"
                                                alt=""></a>
                                        <div id="gallery-7" class="hidden">
                                            <a href="images/gallery/img-13.jpg"><img src="images/gallery/img-13.jpg"
                                                    alt=""></a>
                                            <a href="images/gallery/img-14.jpg"><img src="images/gallery/img-14.jpg"
                                                    alt=""></a>
                                            <a href="images/gallery/img-12.jpg"><img src="images/gallery/img-12.jpg"
                                                    alt=""></a>
                                        </div>
                                    </div>
                                    <div class="content">
                                        <h4 class="title"><a href="blog-single-gallery.html">Outdoor</a></h4>
                                        <span class="count">3 photos</span>
                                    </div>
                                </div>
                            </div>
                            <div class="owl-item cloned" style="width: 182.5px; margin-right: 15px;">
                                <div class="item">
                                    <div class="gallery">
                                        <a href="#gallery-1" class="btn-gallery"><img src="images/gallery/img-6.jpg"
                                                alt=""></a>
                                        <div id="gallery-1" class="hidden">
                                            <a href="images/gallery/img-10.jpg"><img src="images/gallery/img-10.jpg"
                                                    alt=""></a>
                                            <a href="images/gallery/img-6.jpg"><img src="images/gallery/img-6.jpg"
                                                    alt=""></a>
                                            <a href="images/gallery/img-11.jpg"><img src="images/gallery/img-11.jpg"
                                                    alt=""></a>
                                        </div>
                                    </div>
                                    <div class="content">
                                        <h4 class="title"><a href="blog-single-gallery.html">Rooms</a></h4>
                                        <span class="count">3 photos</span>
                                    </div>
                                </div>
                            </div>
                            <div class="owl-item cloned" style="width: 182.5px; margin-right: 15px;">
                                <div class="item">
                                    <div class="gallery">
                                        <div class="gallery">
                                            <a href="#gallery-2" class="btn-gallery"><img src="images/gallery/img-7.jpg"
                                                    alt=""></a>
                                            <div id="gallery-2" class="hidden">
                                                <a href="images/gallery/img-2.jpg"><img src="images/gallery/img-2.jpg"
                                                        alt=""></a>
                                                <a href="images/gallery/img-7.jpg"><img src="images/gallery/img-7.jpg"
                                                        alt=""></a>
                                                <a href="images/gallery/img-8.jpg"><img src="images/gallery/img-8.jpg"
                                                        alt=""></a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="content">
                                        <h4 class="title"><a href="blog-single-gallery.html">Restaurant</a></h4>
                                        <span class="count">3 photos</span>
                                    </div>
                                </div>
                            </div>
                            <div class="owl-item cloned" style="width: 182.5px; margin-right: 15px;">
                                <div class="item">
                                    <div class="gallery">
                                        <a href="#gallery-3" class="btn-gallery"><img src="images/gallery/img-9.jpg"
                                                alt=""></a>
                                        <div id="gallery-3" class="hidden">
                                            <a href="images/gallery/img-12.jpg"><img src="images/gallery/img-12.jpg"
                                                    alt=""></a>
                                            <a href="images/gallery/img-1.jpg"><img src="images/gallery/img-1.jpg"
                                                    alt=""></a>
                                            <a href="images/gallery/img-9.jpg"><img src="images/gallery/img-9.jpg"
                                                    alt=""></a>
                                        </div>
                                    </div>
                                    <div class="content">
                                        <h4 class="title"><a href="blog-single-gallery.html">Pool</a></h4>
                                        <span class="count">3 photos</span>
                                    </div>
                                </div>
                            </div>
                            <div class="owl-item cloned" style="width: 182.5px; margin-right: 15px;">
                                <div class="item">
                                    <div class="gallery">
                                        <a href="#gallery-4" class="btn-gallery"><img src="images/gallery/img-13.jpg"
                                                alt=""></a>
                                        <div id="gallery-4" class="hidden">
                                            <a href="images/gallery/img-14.jpg"><img src="images/gallery/img-14.jpg"
                                                    alt=""></a>
                                            <a href="images/gallery/img-7.jpg"><img src="images/gallery/img-7.jpg"
                                                    alt=""></a>
                                            <a href="images/gallery/img-13.jpg"><img src="images/gallery/img-13.jpg"
                                                    alt=""></a>
                                        </div>
                                    </div>
                                    <div class="content">
                                        <h4 class="title"><a href="blog-single-gallery.html">Activities</a></h4>
                                        <span class="count">3 photos</span>
                                    </div>
                                </div>
                            </div>
                            <div class="owl-item cloned" style="width: 182.5px; margin-right: 15px;">
                                <div class="item">
                                    <div class="gallery">
                                        <a href="#gallery-5" class="btn-gallery"><img src="images/gallery/img-1.jpg"
                                                alt=""></a>
                                        <div id="gallery-5" class="hidden">
                                            <a href="images/gallery/img-12.jpg"><img src="images/gallery/img-12.jpg"
                                                    alt=""></a>
                                            <a href="images/gallery/img-1.jpg"><img src="images/gallery/img-1.jpg"
                                                    alt=""></a>
                                            <a href="images/gallery/img-9.jpg"><img src="images/gallery/img-9.jpg"
                                                    alt=""></a>
                                        </div>
                                    </div>

                                    <div class="content">
                                        <h4 class="title"><a href="blog-single-gallery.html">Beach</a></h4>
                                        <span class="count">3 photos</span>
                                    </div>
                                </div>
                            </div>
                            <div class="owl-item cloned" style="width: 182.5px; margin-right: 15px;">
                                <div class="item">
                                    <div class="gallery">
                                        <a href="#gallery-7" class="btn-gallery"><img src="images/gallery/img-3.jpg"
                                                alt=""></a>
                                        <div id="gallery-6" class="hidden">
                                            <a href="images/gallery/img-4.jpg"><img src="images/gallery/img-4.jpg"
                                                    alt=""></a>
                                            <a href="images/gallery/img-7.jpg"><img src="images/gallery/img-7.jpg"
                                                    alt=""></a>
                                            <a href="images/gallery/img-3.jpg"><img src="images/gallery/img-3.jpg"
                                                    alt=""></a>
                                        </div>
                                    </div>
                                    <div class="content">
                                        <h4 class="title"><a href="blog-single-gallery.html">Spa</a></h4>
                                        <span class="count">3 photos</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="owl-nav disabled">
                        <div class="owl-prev">prev</div>
                        <div class="owl-next">next</div>
                    </div>
                    <div class="owl-dots">
                        <div class="owl-dot active"><span></span></div>
                        <div class="owl-dot"><span></span></div>
                    </div>
                </div>
            </div> -->
            <!-- owl carousel -->
            
        </div>
    </section>

    <!-- Footer Section -->
    <footer class="footer-section ">
        <div class="container">
            <div class="row">
                <div class="col-md-3">
                    <div class="footer-logo-wrapper">
                        <h3>HMS</h3>
                        <p>Welcome to Munshi Mohammad Meherulla Hall</p>
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

</body>

</html>