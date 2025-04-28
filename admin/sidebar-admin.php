<?php
include_once '../class-file/SessionManager.php';
$session = SessionStatic::class;

// Dynamically determine the directory of this navbar file.
$navbarDir = str_replace($_SERVER['DOCUMENT_ROOT'], '', dirname(__FILE__)) . '/';

?>


<!-- Combined Sidebar Markup: togglable on small screens using collapse -->
<nav id="sidebarMenu" class="col-lg-2 collapse bg-dark text-white">
    <div class="sidebar-container">
        <!-- Header with close button for phone view -->
        <div
            class="px-3 py-3 flex-shrink-0 border-bottom border-secondary d-flex justify-content-between align-items-center">
            
            <small class="text-muted">Hall Management System</small>
            <!-- Close button (visible on small screens only) -->
            <button
                type="button"
                class="btn-close btn-close-white d-lg-none"
                data-bs-toggle="collapse"
                data-bs-target="#sidebarMenu"
                aria-label="Close"></button>
        </div>
        <!-- Links (Scrollable) -->
        <div class="sidebar-links flex-grow-1 py-2">
            <ul class="nav flex-column">
                <li class="nav-item">
                    <!-- Dashboard Link -->
                    <a class="nav-link text-white" href="<?php $navbarDir; ?>profile.php">
                        My Profile
                    </a>
                </li>
                <li class="nav-item">
                    <!-- Change Pass -->
                    <a class="nav-link text-white" href="<?php $navbarDir; ?>change-pass-1.php">
                        Change Password
                    </a>
                </li>
                
                <hr>
                
                <?php if ($session::get('admin') == 'super-admin') { ?>
                <li class="nav-item">
                    <!-- Review Admin Registration Link -->
                    <a class="nav-link text-white" href="<?php $navbarDir; ?>pending-admin-registration.php">
                        Review Admin Registration
                    </a>
                </li>
                <li class="nav-item">
                    <!-- Manage Admin Link -->
                    <a class="nav-link text-white" href="<?php $navbarDir; ?>manage-admin.php">
                        Manage Admin
                    </a>
                </li>

                <hr>
                <?php } ?>

                <li class="nav-item">
                    <!-- Dashboard Link -->
                    <a class="nav-link text-white" href="<?php $navbarDir; ?>dashboard.php">
                        Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <!-- User Review Link -->
                    <a class="nav-link text-white" href="<?php $navbarDir; ?>pending-registration.php">
                        Users Review
                    </a>
                </li>
                <li class="nav-item">
                    <!-- Manage User Link -->
                    <a class="nav-link text-white" href="<?php $navbarDir; ?>manage-user.php">
                        Manage User
                    </a>
                </li>
                <li class="nav-item">
                    <!-- Profile update request -->
                    <a class="nav-link text-white" href="<?php $navbarDir; ?>update-profile-requests.php">
                        Profile Update Requests
                    </a>
                </li>

                <hr>

                <li class="nav-item">
                    <!-- Manage Departments -->
                    <a class="nav-link text-white" href="<?php $navbarDir; ?>manage-departments.php">
                        Manage Departments
                    </a>
                </li>
                
                <li class="nav-item">
                    <!-- Manage Notices -->
                    <a class="nav-link text-white" href="<?php $navbarDir; ?>manage-notice.php">
                        Manage Notices
                    </a>
                </li>
                
                <li class="nav-item">
                    <!-- Seat Management Link -->
                    <a class="nav-link text-white" href="<?php $navbarDir; ?>seat-management.php">
                        Seat Management
                    </a>
                </li>
                <li class="nav-item">
                    <!-- Seat Cancel -->
                    <a class="nav-link text-white" href="<?php $navbarDir; ?>seat-cancel.php">
                        Seat Cancel Form
                    </a>
                </li>
                <li class="nav-item">
                    <!-- Create Event Link -->
                    <a class="nav-link text-white" href="<?php $navbarDir; ?>create-hall-seat-allocation-event.php">
                        Create Hall Seat Allocation Event
                    </a>
                </li>
                <!-- Additional links to demonstrate scrolling -->
                <li class="nav-item">
                    <!-- Event Management Link -->
                    <a class="nav-link text-white" href="<?php $navbarDir; ?>hall-seat-allocation-event-management.php">
                        Hall Seat Allocation Event Management
                    </a>
                </li>

            </ul>
        </div>
        <!-- Footer (Fixed) with full-width button -->
        <div class="py-3 flex-shrink-0 border-top border-secondary">
            <p> <i>Logged in as 
                <?php echo $session::get('admin'); ?>
        </i></p>
            <a class="btn btn-danger w-100" href="<?php $navbarDir; ?>logout.php" role="button">Logout</a>
        </div>
    </div>
</nav>