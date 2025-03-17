<?php
// Calculate the relative directory path to the current file
$sidebarDir = str_replace($_SERVER['DOCUMENT_ROOT'], '', dirname(__FILE__)) . '/';
?>

<div id="layoutSidenav_nav">
    <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
        <div class="sb-sidenav-menu">
            <div class="nav">
                <!-- Sidebar Heading -->
                <div class="sb-sidenav-menu-heading">Pages</div>

                <!-- Dashboard Link -->
                <a class="nav-link" href="<?= $sidebarDir ?>dashboard.php">
                    <div class="sb-nav-link-icon"><i class="fa-solid fa-house"></i></div>
                    Dashboard
                </a>

                <!-- User Review Link -->
                <a class="nav-link" href="<?= $sidebarDir ?>pending-registration.php">
                    <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                    Users Review
                </a>

                <!-- Manage User Link -->
                <a class="nav-link" href="<?= $sidebarDir ?>manage-user.php">
                    <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                    Manage User
                </a>

                <!-- Profile update request -->
                <a class="nav-link" href="<?= $sidebarDir ?>update-profile-requests.php">
                    <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                    Profile Update Requests
                </a>

                <hr>
                
                <!-- Seat Management Link -->
                <a class="nav-link" href="<?= $sidebarDir ?>seat-management.php">
                    <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                    Seat Management
                </a>

                <!-- Create Event Link -->
                <a class="nav-link" href="<?= $sidebarDir ?>create-hall-seat-allocation-event.php">
                    <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                    Create Hall Seat Allocation Event
                </a>

                <!-- Event Management Link -->
                <a class="nav-link" href="<?= $sidebarDir ?>hall-seat-allocation-event-management.php">
                    <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                    Hall Seat Allocation Event Management
                </a>

                <hr>




                <!-- Pending Hall Seat Link -->
                <a class="nav-link" href="<?= $sidebarDir ?>pending-hall-seat.html">
                    <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                    Pending Hall Seat
                </a>


                <!-- Seat Confirmation Link -->
                <a class="nav-link" href="<?= $sidebarDir ?>seat-confirmation.html">
                    <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                    Seat Confirmation
                </a>

                <!-- Seat Management Link -->
                <a class="nav-link" href="<?= $sidebarDir ?>seat-management.html">
                    <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                    Seat Management
                </a>
            </div>
        </div>

        <!-- Sidebar Footer with Logged-in User Information -->
        <div class="sb-sidenav-footer">
            <div class="small">Logged in as: Admin</div>
        </div>
    </nav>
</div>
