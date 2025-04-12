<?php
// Calculate the URL path for the current file's directory.
$navbarDir = str_replace($_SERVER['DOCUMENT_ROOT'], '', dirname(__FILE__)) . '/';

// if the logout button pressed
if (isset($_GET['logout'])) {
    // Include the SessionManager class from the correct directory
    include_once $_SERVER['DOCUMENT_ROOT'] . '/class-file/SessionManager.php';

    // Create an instance of SessionManager and destroy the session.
    $session = new Session();
    $session->destroy();

    // Redirect to the homepage using JavaScript.
    echo '<script type="text/javascript">
            window.location.href = "/index.php";
          </script>';
    exit;
}
?>

<div class="site-mobile-menu site-navbar-target">
    <div class="site-mobile-menu-header">
        <div class="site-mobile-menu-close mt-3">
            <span class="icon-close2 js-menu-toggle"></span>
        </div>
    </div>
    <div class="site-mobile-menu-body"></div>
</div>

<!-- navbar-student.php -->
<?php
// Dynamically determine the directory of this navbar file.
$navbarDir = str_replace($_SERVER['DOCUMENT_ROOT'], '', dirname(__FILE__)) . '/';
?>
<header class="site-navbar py-4 js-sticky-header site-navbar-target text-dark" role="banner">
    <div class="container-fluid">
        <div class="d-flex align-items-center">
            <!-- For links to pages in the root folder, use absolute paths -->
            <div class="site-logo mr-auto w-20"><a href="/index.php">MM HALL</a></div>
            <div class="mx-auto text-center">
                <nav class="site-navigation position-relative text-right" role="navigation">
                    <ul class="site-menu main-menu js-clone-nav mx-auto d-none d-lg-block m-0 p-0">
                        <li><a href="/index.php" class="nav-link">Home</a></li>
                        <!-- For pages within the same folder as the navbar, prepend $navbarDir -->
                        <li><a href="<?= $navbarDir ?>../notice.php" class="nav-link">Notices</a></li>
                        <li class="nav-item dropdown">
                            <a href="" class="nav-link dropdown-toggle" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Apply</a>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <!-- <a class="dropdown-item" href="<//?= $navbarDir ?>noc.php">Hall NOC</a> -->
                                <a class="dropdown-item" href="<?= $navbarDir ?>apply-seat-in-hall.php">Seat in hall</a>
                                <!-- <a class="dropdown-item" href="<//?= $navbarDir ?>seat-change.php">Seat change in the hall</a> -->
                            </div>
                        </li>
                        <li><a href="<?= $navbarDir ?>about.php" class="nav-link">About Us</a></li>
                        <li><a href="<?= $navbarDir ?>contact.php" class="nav-link">Contact Us</a></li>
                    </ul>
                </nav>
            </div>
            <div class="ml-auto w-20">
                <nav class="site-navigation position-relative text-right" role="navigation">
                    <ul class="site-menu main-menu site-menu-dark js-clone-nav mr-auto d-none d-lg-block m-0 p-0">
                        <li class="nav-item dropdown">
                            <a href="" class="primary-button dropdown-toggle" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Dashboard</a>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="<?= $navbarDir ?>profile.php">View Profile</a>
                                <a class="dropdown-item" href="<?= $navbarDir ?>applications.php">My Applications</a>
                                <a class="dropdown-item" href="?logout=1">Logout</a>
                            </div>
                        </li>
                    </ul>
                </nav>
                <a href="#" class="d-inline-block d-lg-none site-menu-toggle js-menu-toggle text-black float-right">
                    <span class="icon-menu h3"></span>
                </a>
            </div>
        </div>
    </div>
</header>