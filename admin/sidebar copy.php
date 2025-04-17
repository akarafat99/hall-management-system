<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Responsive Sidebar with Animation and Blur</title>
    <!-- Bootstrap CSS -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css"
        rel="stylesheet" />


    <!-- for sidebar -->
    <link href="../css2/sidebar-admin.css" rel="stylesheet" />
</head>

<body>
    <div class="container-fluid">
        <div class="row">
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
                        <button class="btn btn-danger w-100">Logout</button>
                    </div>
                </div>
            </nav>

            <!-- Main Content Area -->
            <main id="mainContent" class="col">
                <!-- Toggle button for sidebar on small screens -->
                <button
                    class="btn btn-dark d-lg-none mt-3 mb-3"
                    type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#sidebarMenu"
                    aria-controls="sidebarMenu"
                    aria-expanded="false"
                    aria-label="Toggle navigation">
                    ☰ Menu
                </button>
                <div class="p-3" style="height: 100vh; overflow-y: auto;">
                    <h1>Welcome to the Main Content</h1>
                    <p>
                        This is the main section of the page. With one unified sidebar markup,
                        navigation links, header, and logout button are consistent across all screen sizes.
                    </p>
                    <p>
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse varius enim in eros elementum tristique.
                        Duis cursus, mi quis viverra ornare, eros dolor interdum nulla.
                    </p>
                    <p>Additional content here to demonstrate scrolling...</p>
                </div>
            </main>
        </div>
    </div>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>