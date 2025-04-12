<?php
include_once 'class-file/SessionManager.php';
$session = SessionStatic::class;

// $session::destroy();
include_once 'popup-1.php';
$session::get('msg1') ? showPopup($session::get('msg1')) : '';
$session::delete('msg1');

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Google Fonts: Roboto for Material Design look -->
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
  <title>MM Hall</title>
</head>

<body>
  <!-- Parent container with flex and min-vh-100 -->
  <div class="d-flex flex-column min-vh-100">
    <!-- First parent div for all main content including the navbar -->
    <div class="flex-grow-1">
      
      <!-- Navbar include -->
      <?php 
      if($session::get('user') !== null) {
        include_once 'student/navbar-student-1.php';
      } else {
        include_once 'student/navbar-student-2.php';
      }
      ?>

      <!-- TODO 1: Welcome Section -->
      <div class="container mt-4">
        <div class="text-center">
          <h1 class="display-4">Welcome to MM Hall</h1>
          <p class="lead">Experience excellence and innovation at our community.</p>
        </div>
      </div>

      <!-- TODO 2: Notices and Image Grid -->
      <div class="container mt-5">
        <div class="row align-items-center">
          <!-- Left Column: Notices -->
          <div class="col-md-6">
            <h2 class="display-5">Notices</h2>
            <p class="lead">Please check back regularly for any updates and important announcements regarding campus activities, events, and more.</p>
          </div>
          <!-- Right Column: Image -->
          <div class="col-md-6">
            <img src="https://via.placeholder.com/500x300" alt="Notices Image" class="img-fluid">
          </div>
        </div>
      </div>
    </div>

    <!-- Second parent div for the footer -->
    <footer class="bg-dark text-white mt-auto">
      <div class="container py-4 text-center">
        <p class="mb-0">&copy; 2025 MM Hall. All rights reserved.</p>
      </div>
    </footer>
  </div>

  <!-- Bootstrap JS and dependencies -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
