<?php
include_once 'class-file/SessionManager.php';
$session = SessionStatic::class;
$session::ensureSessionStarted();

include_once 'popup-1.php';
if ($session::get('msg1') != null) {
    showPopup($session::get('msg1'));
    $session::delete('msg1');
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
            if ($session::get('user') != null) {
                include_once 'student/navbar-student-1.php';
            } else {
                include_once 'student/navbar-student-2.php';
            }
            ?>
            <!-- Navbar Section End -->

            <!-- TODO: 1 — Simple About Us for MM Hall -->
            <div class="container py-5">
                <!-- About Text -->
                <h2 class="mb-3">About MM Hall</h2>
                <p>MM Hall is a vibrant residential community at XYZ University, providing students with a safe, supportive environment. Established in 2010, our hall offers comfortable accommodations and a range of amenities designed to enhance student life and foster academic success.</p>

                <!-- Facilities List -->
                <h4 class="mt-5 mb-3">Facilities</h4>
                <ul class="list-group list-group-flush mb-5">
                    <li class="list-group-item"><i class="fas fa-bed me-2 text-primary"></i>Single &amp; shared rooms</li>
                    <li class="list-group-item"><i class="fas fa-wifi me-2 text-primary"></i>High‑speed Wi‑Fi</li>
                    <li class="list-group-item"><i class="fas fa-utensils me-2 text-primary"></i>On‑site dining hall</li>
                    <li class="list-group-item"><i class="fas fa-dumbbell me-2 text-primary"></i>Fitness center</li>
                    <li class="list-group-item"><i class="fas fa-book me-2 text-primary"></i>Study lounges</li>
                </ul>

                <!-- Photo Gallery -->
                <h4 class="mb-3">Gallery</h4>
                <div class="row g-3 mb-5">
                    <div class="col-6 col-md-3">
                        <img src="https://via.placeholder.com/300x200" class="img-fluid rounded" alt="Hall exterior">
                    </div>
                    <div class="col-6 col-md-3">
                        <img src="https://via.placeholder.com/300x200" class="img-fluid rounded" alt="Common lounge">
                    </div>
                    <div class="col-6 col-md-3">
                        <img src="https://via.placeholder.com/300x200" class="img-fluid rounded" alt="Dining area">
                    </div>
                    <div class="col-6 col-md-3">
                        <img src="https://via.placeholder.com/300x200" class="img-fluid rounded" alt="Study room">
                    </div>
                </div>

                <!-- Location -->
                <h4 class="mb-3">Location</h4>
                <p>First Floor, North Wing, MM Hall Building<br>XYZ University Campus, Cityville</p>
                <div class="ratio ratio-16x9">
                    <iframe
                        src="https://maps.google.com/maps?q=XYZ%20University%20Cityville&t=&z=15&ie=UTF8&iwloc=&output=embed"
                        loading="lazy"
                        allowfullscreen>
                    </iframe>
                </div>
            </div>

            <!-- End of About Section -->


        </div> <!-- End of Main Content Area -->

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