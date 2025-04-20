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
                    <a class="nav-link text-white" href="dashboard.php">
                        Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <!-- User Review Link -->
                    <a class="nav-link text-white" href="pending-registration.php">
                        Users Review
                    </a>
                </li>
                <li class="nav-item">
                    <!-- Manage User Link -->
                    <a class="nav-link text-white" href="manage-user.php">
                        Manage User
                    </a>
                </li>
                <li class="nav-item">
                    <!-- Profile update request -->
                    <a class="nav-link text-white" href="update-profile-requests.php">
                        Profile Update Requests
                    </a>
                </li>

                <hr>

                <li class="nav-item">
                    <!-- Manage Departments -->
                    <a class="nav-link text-white" href="manage-departments.php">
                        Manage Departments
                    </a>
                </li>
                <li class="nav-item">
                    <!-- Seat Management Link -->
                    <a class="nav-link text-white" href="seat-management.php">
                        Seat Management
                    </a>
                </li>
                <li class="nav-item">
                    <!-- Create Event Link -->
                    <a class="nav-link text-white" href="create-hall-seat-allocation-event.php">
                        Create Hall Seat Allocation Event
                    </a>
                </li>
                <!-- Additional links to demonstrate scrolling -->
                <li class="nav-item">
                    <!-- Event Management Link -->
                    <a class="nav-link text-white" href="hall-seat-allocation-event-management.php">
                        Hall Seat Allocation Event Management
                    </a>
                </li>

            </ul>
        </div>
        <!-- Footer (Fixed) with full-width button -->
        <div class="py-3 flex-shrink-0 border-top border-secondary">
            <p> <i>Logged in as admin </i></p>
            <button class="btn btn-danger w-100">Logout</button>
        </div>
    </div>
</nav>