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

      <!-- TODO: 1 — Advanced “Contact Us” Section -->
      <!-- TODO: 1 — Advanced “Contact Us” Section -->
<div class="container py-5">
  <!-- Section Header -->
  <div class="text-center mb-5">
    <h2 class="display-6 fw-bold">Contact MM Hall Office</h2>
    <p class="text-muted">We’re here to help – reach out by phone, email, or visit us in person.</p>
  </div>

  <div class="row g-4">
    <!-- Contact Details Card -->
    <div class="col-lg-6">
      <div class="card border-start border-4 border-primary shadow-sm h-100">
        <div class="card-body">
          <h5 class="fw-semibold text-primary mb-3">
            <i class="fas fa-map-marker-alt me-2"></i>Location
          </h5>
          <p>
            Ground Floor, Munshi Mohammad Meherulla Hall<br>
            Jashore University of Science and Technology<br>
            Jashore-7408, Khulna, Bangladesh
          </p>

          <hr>

          <h5 class="fw-semibold text-primary mb-3">
            <i class="fas fa-phone-alt me-2"></i>Phone
          </h5>
          <p>
            <a href="tel:+880123456789" class="text-decoration-none">
              +880 1234 567 89
            </a>
          </p>

          <hr>

          <h5 class="fw-semibold text-primary mb-3">
            <i class="fas fa-envelope me-2"></i>Email
          </h5>
          <p>
            <a href="mailto:mmhall@just.edu.bd" class="text-decoration-none">
              mmhall@just.edu.bd
            </a>
          </p>

          <hr>

          <h5 class="fw-semibold text-primary mb-3">
            <i class="fas fa-clock me-2"></i>Office Hours
          </h5>
          <p>
            Sat – Wed: 9:00 AM – 5:00 PM
          </p>
        </div>
      </div>
    </div>

    <!-- Map Embed -->
    <div class="col-lg-6">
      <div class="ratio ratio-16x9 shadow-sm rounded">
        <iframe
          src="https://www.google.com/maps/embed?pb=!1m26!1m12!1m3!1d3613.142080202048!2d89.12364239268909!3d23.235028281434317!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!4m11!3e6!4m3!3m2!1d23.233646999999998!2d89.1255231!4m5!1s0x39ff1857827d6cb7%3A0xecab69d917b1a29b!2s1%20Z7031%2C%207408!3m2!1d23.233434499999998!2d89.1254175!5e1!3m2!1sen!2sbd!4v1745875193534!5m2!1sen!2sbd"
          style="border:0;"
          allowfullscreen
          loading="lazy"
          referrerpolicy="no-referrer-when-downgrade">
        </iframe>
      </div>
    </div>
  </div>
</div>

      <!-- End of Contact Us Section -->


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
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>