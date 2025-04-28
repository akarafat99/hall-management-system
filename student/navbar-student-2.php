<?php


// Original code: removes the document root from the current file's directory.
$navbarDir = str_replace($_SERVER['DOCUMENT_ROOT'], '', dirname(__FILE__));

// Replace any backslashes with forward slashes
$navbarDir = str_replace('\\', '/', $navbarDir);

// Append a trailing slash
$navbarDir .= '/';

// echo $navbarDir; // Outputs: /subfolder/
?>

<!-- Navbar Start -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
    <div class="container-fluid">
      <!-- Seg 1: Brand -->
      <a class="navbar-brand text-white" href="#">MM Hall</a>
      <!-- Toggler for mobile view -->
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
              data-bs-target="#navbarContent" aria-controls="navbarContent"
              aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <!-- Navbar content -->
      <div class="collapse navbar-collapse" id="navbarContent">
        <!-- Seg 2: Center aligned navigation links -->
        <ul class="navbar-nav mx-auto">
          <li class="nav-item">
            <a class="nav-link text-white" href="<?= $navbarDir ?>../index.php">Home</a>
          </li>
          <li class="nav-item">
            <a class="nav-link text-white" href="<?= $navbarDir ?>../notice.php">Notices</a>
          </li>
          <!-- Apply Dropdown -->
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle text-white" href="#" id="applyDropdown" role="button"
               data-bs-toggle="dropdown" aria-expanded="false">
              Apply
            </a>
            <ul class="dropdown-menu dropdown-menu-dark" aria-labelledby="applyDropdown">
              <li><a class="dropdown-item text-white" href="<?= $navbarDir ?>apply-seat-in-hall.php">Seat in Hall</a></li>
            </ul>
          </li>
          <li class="nav-item">
            <a class="nav-link text-white" href="<?= $navbarDir ?>../about.php">About Us</a>
          </li>
          <li class="nav-item">
            <a class="nav-link text-white" href="<?= $navbarDir ?>../contact-us.php">Contact Us</a>
          </li>
        </ul>
        <!-- Seg 3: Account dropdown --> 
        <ul class="navbar-nav">
          <li class="nav-item">
            <a class="nav-link text-white" href="<?= $navbarDir ?>../login.php">Login</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>

<!-- Navbar End -->