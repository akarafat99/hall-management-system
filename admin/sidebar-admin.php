<!-- Combined Sidebar Markup: togglable on small screens using collapse -->
<nav id="sidebarMenu" class="col-lg-2 collapse bg-dark text-white">
    <div class="sidebar-container">
        <!-- Header with close button for phone view -->
        <div
            class="px-3 py-3 flex-shrink-0 border-bottom border-secondary d-flex justify-content-between align-items-center">
            <h3 class="mb-0">My Sidebar</h3>
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
                    <a class="nav-link text-white" href="#link1">Dashboard</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="#link2">Profile</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="#link3">Settings</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="#link4">Messages</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="#link5">Reports</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="#link6">Analytics</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="#link7">Help</a>
                </li>
                <!-- Additional links to demonstrate scrolling -->
                <li class="nav-item">
                    <a class="nav-link text-white" href="#link8">Link 8</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="#link9">Link 9</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="#link10">Link 10</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="#link11">Link 11</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="#link12">Link 12</a>
                </li>
            </ul>
        </div>
        <!-- Footer (Fixed) with full-width button -->
        <div class="py-3 flex-shrink-0 border-top border-secondary">
            <p > <i>Logged in as admin </i></p>
            <button class="btn btn-danger w-100">Logout</button>
        </div>
    </div>
</nav>