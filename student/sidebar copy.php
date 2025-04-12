<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Fixed Sidebar with Scrollable Main Content</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    html, body {
      height: 100%;
      margin: 0;
    }
    body {
      overflow-x: hidden;
    }
    /* Mobile offcanvas sidebar styling */
    .offcanvas {
      background-color: #000;
      color: #fff;
    }
    .offcanvas a {
      color: #fff;
    }
    /* Fixed sidebar styling for desktop (md and up) */
    @media (min-width: 768px) {
      .sidebar {
        position: fixed;
        top: 0;
        left: 0;
        bottom: 0;
        width: 200px;
        overflow-y: auto;
        background-color: #000;
        color: #fff;
        padding-top: 1rem;
      }
      /* Offset main content according to the sidebar width */
      .main-content {
        margin-left: 200px;
      }
    }
    /* Common sidebar styling */
    .sidebar h4, .offcanvas h5 {
      text-align: left;
      margin-left: 1rem;
    }
    .btn-group-vertical > .btn {
      border-radius: 0;
      text-align: left;
      padding-left: 1rem;
      transition: none; /* Animation is handled via JavaScript */
    }
  </style>
</head>
<body>
  <div class="container-fluid">
    <div class="row min-vh-100">
      <!-- Mobile: Offcanvas sidebar triggered by hamburger menu -->
      <div class="d-md-none">
        <button class="btn btn-light my-3" type="button" data-bs-toggle="offcanvas"
          data-bs-target="#mobileSidebar" aria-controls="mobileSidebar">
          &#9776; Menu
        </button>
      </div>
      <!-- Mobile offcanvas sidebar -->
      <div class="offcanvas offcanvas-start d-md-none" tabindex="-1" id="mobileSidebar" 
           aria-labelledby="mobileSidebarLabel">
        <div class="offcanvas-header">
          <h5 class="offcanvas-title" id="mobileSidebarLabel">Website Name</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" 
                  aria-label="Close"></button>
        </div>
        <div class="offcanvas-body d-flex flex-column">
          <div>
            <div class="btn-group-vertical w-100">
              <a class="btn" href="#">Home</a>
              <a class="btn" href="#">About</a>
              <a class="btn" href="#">Services</a>
              <a class="btn" href="#">Contact</a>
            </div>
          </div>
          <div class="mt-auto">
            <a class="btn btn-danger btn-sm w-100 my-2" href="#">Logout</a>
          </div>
        </div>
      </div>
      <!-- Desktop Fixed Sidebar -->
      <nav class="sidebar d-none d-md-block">
        <h4 class="mb-4">Website Name</h4>
        <div class="btn-group-vertical w-100">
          <a class="btn" href="#">Home</a>
          <a class="btn" href="#">About</a>
          <a class="btn" href="#">Services</a>
          <a class="btn" href="#">Contact</a>
        </div>
        <div class="mt-auto">
          <a class="btn btn-danger btn-sm w-100 my-2" href="#">Logout</a>
        </div>
      </nav>
      <!-- Main Content Area -->
      <main class="main-content col-12 col-md-10 px-md-4">
        <div class="pt-3 pb-2 mb-3 border-bottom">
          <h1>Main Content Area</h1>
        </div>
        <p>
          Your main body content goes here. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum imperdiet dui et tortor efficitur, eu commodo ante sollicitudin. Suspendisse potenti. Curabitur vulputate orci ac sapien dignissim, vel fringilla nibh viverra. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas.
        </p>
        <p>
          Mauris tincidunt tincidunt leo, et luctus sapien interdum sed. Integer non pharetra leo, at volutpat nisi. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia curae; Cras gravida libero id orci fermentum, non convallis nisi scelerisque.
        </p>
        <p>
          Donec luctus velit risus, vel fermentum sapien posuere nec. Suspendisse potenti. Ut eget tellus ac tortor varius cursus. Sed nec malesuada erat. Praesent tristique nisi lorem, in efficitur tellus consequat in.
        </p>
        <!-- Add more content as needed to see the scrolling effect -->
      </main>
    </div>
  </div>
  <!-- Bootstrap Bundle with Popper -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    // JavaScript hover animation for navigation links
    const navLinks = document.querySelectorAll('.sidebar a, .offcanvas a');
    navLinks.forEach(link => {
      link.addEventListener('mouseover', () => {
        link.animate([
          { transform: 'scale(1)' },
          { transform: 'scale(1.1)' }
        ], {
          duration: 200,
          fill: 'forwards'
        });
      });
      link.addEventListener('mouseout', () => {
        link.animate([
          { transform: 'scale(1.1)' },
          { transform: 'scale(1)' }
        ], {
          duration: 200,
          fill: 'forwards'
        });
      });
    });
  </script>
</body>
</html>
