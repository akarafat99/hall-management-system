<?php
include_once 'class-file/HallSeatAllocationEvent.php';



?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">


    <link href="https://fonts.googleapis.com/css?family=Muli:300,400,700,900" rel="stylesheet">
    <link rel="stylesheet" href="../fonts/icomoon/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/bootstrap.min.css">
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

    <title>Seat In Hall | JUST Hall</title>


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

        .modal-title {
            color: black;
            font-size: 1.5rem;
            font-weight: 700;
        }

        .form-info-title {
            color: #201e1f;
            font-weight: 600;
        }

        .modal-dialog {
            max-width: 700px;
        }

        .details-btn {
            font-weight: 600;
            padding: 8px 24px;
            line-height: 1;
            box-shadow: none !important;
        }

        .profile-info-flex {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1rem;
        }

        .profile-wrap {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            overflow: hidden;
        }
    </style>

</head>

<body data-spy="scroll" data-target=".site-navbar-target" data-offset="300">
    <!-- Include the navbar from a separate file -->
    <?php include_once 'navbar-student.php'; ?>

    <!-- notice Banner Section Start -->
    <section class="notice-hero">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="notice-hero-text text-center">
                        <h1>Seat In Hall</h1>
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
                            <h4>Apply for Seat In Hall</h4>
                        </div>

                        <div class="form-block">
                            <!-- Details button with text -->
                            <div class="noc-consideration-text-wrap myt-3 mb-5">
                                <p>I am submitting my application for a seat with my
                                    <span> <button type="submit" class="primary-button details-btn"
                                            data-bs-toggle="modal" data-bs-target="#exampleModal"
                                            style="cursor: pointer;">Details</button></span>
                                    I would sincerely appreciate your consideration and approval of my request.
                                </p>
                                <!-- Modal -->
                                <div class="modal fade" id="exampleModal" tabindex="-1"
                                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">MY Information</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <!-- Modal All Contents Start-->
                                                <div>
                                                    <div class="profile-info-flex">
                                                        <div class="profile-wrap">
                                                            <img src="../images/avatar4.png" alt="User Image"
                                                                class="img-fluid">
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-lg-4">
                                                            <p><strong>Name:</strong> Arafat</p>
                                                        </div>
                                                        <div class="col-lg-4">
                                                            <p><strong>Email:</strong> abc@gmail.com</p>
                                                        </div>
                                                        <div class="col-lg-4">
                                                            <p><strong>Zilla:</strong> Dhaka</p>
                                                        </div>
                                                    </div>

                                                    <div class="row pt-4">
                                                        <div class="col-lg-4">
                                                            <p><strong>Gender:</strong> Male</p>
                                                        </div>
                                                        <div class="col-lg-4">
                                                            <p><strong>Contact No:</strong> 012382917</p>
                                                        </div>
                                                        <div class="col-lg-4">
                                                            <p><strong>Zilla:</strong> Dhaka</p>
                                                        </div>
                                                    </div>

                                                    <div class="row pt-4">
                                                        <div class="col-lg-4">
                                                            <p><strong>Session:</strong> 2020-21</p>
                                                        </div>
                                                        <div class="col-lg-4">
                                                            <p><strong>Year:</strong> 1st</p>
                                                        </div>
                                                        <div class="col-lg-4">
                                                            <p><strong>Semester:</strong> 2nd</p>
                                                        </div>
                                                    </div>

                                                    <div class="row pt-4">
                                                        <div class="col-lg-4">
                                                            <p><strong>Last Semester CGPA:</strong> 3.00</p>
                                                        </div>
                                                        <div class="col-lg-4">
                                                            <p><strong>University Merit:</strong> 100</p>
                                                        </div>
                                                        <div class="col-lg-4">
                                                            <p><strong>Semester:</strong> 2nd</p>
                                                        </div>
                                                    </div>

                                                    <div class="row pt-4">
                                                        <div class="col-lg-4">
                                                            <p><strong>Permanent Address:</strong> Khulna</p>
                                                        </div>
                                                        <div class="col-lg-4">
                                                            <p><strong>Present Address:</strong> Jashore</p>
                                                        </div>
                                                        <div class="col-lg-4">
                                                            <p><strong>All Document:</strong> <a href="#">Download</a>
                                                            </p>
                                                        </div>
                                                    </div>

                                                    <div class="text-center mt-5 mb-4">
                                                        <h5 class="form-info-title">Father's Information</h5>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-lg-4">
                                                            <p><strong>Father Name:</strong> Hello</p>
                                                        </div>
                                                        <div class="col-lg-4">
                                                            <p><strong>Father's Contact No:</strong> 012321</p>
                                                        </div>
                                                        <div class="col-lg-4">
                                                            <p><strong>Father's Profession:</strong> Teacher</p>
                                                        </div>
                                                    </div>

                                                    <div class="row mt-3">
                                                        <div class="col-lg-12">
                                                            <p><strong>Father Monthly Income:</strong> 12000</p>
                                                        </div>
                                                    </div>

                                                    <div class="text-center mt-5 mb-4">
                                                        <h5 class="form-info-title">Guardian's Information</h5>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-lg-4">
                                                            <p><strong>Guardian's Name:</strong> Hello</p>
                                                        </div>
                                                        <div class="col-lg-4">
                                                            <p><strong>Guardian's Contact No:</strong> 012321</p>
                                                        </div>
                                                        <div class="col-lg-4">
                                                            <p><strong>Guardian's Address:</strong> Teacher</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- Modal All Contents End-->
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">Close</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Modal -->
                            </div>
                            <!-- Details button with text -->
                            <form>
                                <div class="mb-3">
                                    <label for="nocOpinion" class="form-label">Why you I applying for seat in hall,
                                        please let us
                                        know your opinion.</label>
                                    <textarea class="form-control" placeholder="Write Message" id="nocOpinion"
                                        name="nocOpinion" rows="2" required></textarea>
                                </div>
                                <div class="text-center">
                                    <button type="submit" class="primary-button"
                                        style="cursor: pointer;">Submit</button>
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
                            <script>document.write(new Date().getFullYear());</script> JUST Credit <i class="icon-heart"
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
    <script src="../https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

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