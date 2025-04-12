<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Responsive Sidebar with Small Logout Button</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- for sidebar and phone menu -->
  <link href="../css2/custom1.css" rel="stylesheet">
</head>
<body>
  <div class="container-fluid">
    <!-- Use min-vh-100 to force full viewport height -->
    <div class="row min-vh-100">
      <!-- Mobile: Offcanvas sidebar triggered by hamburger menu -->
      <div class="d-md-none">
        <button class="btn btn-light my-3" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileSidebar" aria-controls="mobileSidebar">
          &#9776; Menu
        </button>
      </div>
      <!-- Offcanvas sidebar for mobile devices -->
      <div class="offcanvas offcanvas-start d-md-none" tabindex="-1" id="mobileSidebar" aria-labelledby="mobileSidebarLabel">
        <div class="offcanvas-header">
          <h5 class="offcanvas-title" id="mobileSidebarLabel">Website Name</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <!-- Use flex column to push logout button to the bottom -->
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
            <!-- Logout button with small size and vertical margin -->
            <a class="btn btn-danger btn-sm w-100 my-2" href="#">Logout</a>
          </div>
        </div>
      </div>

      <!-- Desktop: Static sidebar visible on larger screens -->
      <!-- Reduced width by using col-md-2 -->
      <nav class="col-md-2 col-lg-2 d-none d-md-block sidebar">
        <!-- Use flex column and full height (h-100) to push the logout button down -->
        <div class="d-flex flex-column h-100 pt-3">
          <div>
            <h4 class="mb-4">Website Name</h4>
            <div class="btn-group-vertical w-100">
              <a class="btn" href="#">Home</a>
              <a class="btn" href="#">About</a>
              <a class="btn" href="#">Services</a>
              <a class="btn" href="#">Contact</a>
            </div>
          </div>
          <div class="mt-auto">
            <!-- Logout button with small size and vertical margin -->
            <a class="btn btn-danger btn-sm w-100 my-2" href="#">Logout</a>
          </div>
        </div>
      </nav>

      <!-- Main Content Area -->
      <main class="col-md-10 ms-sm-auto col-lg-10 px-md-4">
        <div class="pt-3 pb-2 mb-3 border-bottom">
          <h1>Main Content Area</h1>
        </div>
        <p>
          Your main body content goes here. Replace this text with your actual content, images, or additional Bootstrap components as needed.
        </p>
      </main>
    </div>
  </div>

  <!-- Bootstrap Bundle with Popper -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <!-- for the sidebar and phone menu -->
  <script src="../js2/custom1.js"></script>

</body>
</html>
