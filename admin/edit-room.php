<?php
include_once '../class-file/HallSeatDetails.php';
$seatDetails = new HallSeatDetails();

include_once '../class-file/SessionManager.php';
$session = new SessionManager();
if ($session->get('msg1')) {
    include_once '../popup-1.php';
    showPopup($session->get('msg1'));
    $session->delete('msg1');
}

// Handle seat deletion if requested
if (isset($_POST['deleteSeat'])) {
    $seatId = $_POST['seatId'];
    $seatDetails->loadBySeatId($seatId);
    // $seatDetails->delete(); // Uncomment or implement deletion logic as needed.
    echo "<script>window.location.reload();</script>";
    exit();
}

// Handle add seats operation
if (isset($_POST['addSeats'])) {
    $floorNo = intval($_POST['floorNo']);
    $roomNo = intval($_POST['roomNo']);
    $seatCount = intval($_POST['seatCount']);
    $seatDetails->addSeatsToRoom($floorNo, $roomNo, $seatCount);
    $session->set('msg1', 'New ' . $seatCount . ' seats added to Floor ' . $floorNo . ', Room ' . $roomNo . ' successfully!');
    echo "<script>
        window.location.href = '?floorNo=$floorNo&roomNo=$roomNo';
    </script>";
    exit();
}

$floorNo = isset($_POST['floorNo']) ? intval($_POST['floorNo']) : (isset($_GET['floorNo']) ? intval($_GET['floorNo']) : null);
$roomNo  = isset($_POST['roomNo'])  ? intval($_POST['roomNo'])  : (isset($_GET['roomNo'])  ? intval($_GET['roomNo'])  : null);

$seats = false;

// If both floorNo and roomNo are provided, fetch seats for that room.
if ($floorNo !== null && $roomNo !== null) {
    $seats = $seatDetails->getRowsByFloorRoomStatus($floorNo, $roomNo, null);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
        integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    <link href="../css/Dashboard/dashboard.css" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>

    <title>MM HALL - Dashboard</title>
</head>

<body class="sb-nav-fixed">
    <nav class="sb-topnav navbar navbar-expand dashboard-nav py-4">
        <!-- Navbar Brand-->
        <a class="navbar-brand ps-3" href="../index.html">HMS</a>
        <!-- Sidebar Toggle-->
        <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!">
            <i class="fas fa-bars"></i>
        </button>
        <!-- Navbar Search-->
        <form class="d-none d-md-inline-block form-inline ms-auto me-0 me-md-3 my-2 my-md-0"></form>
        <!-- Navbar-->
        <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button"
                    data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-user fa-fw"></i>
                </a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                    <li><a class="dropdown-item" href="#!">Profile</a></li>
                    <li><a class="dropdown-item" href="#!">Activity Log</a></li>
                    <li>
                        <hr class="dropdown-divider" />
                    </li>
                    <li><a class="dropdown-item" href="#!">Logout</a></li>
                </ul>
            </li>
        </ul>
    </nav>
    <div id="layoutSidenav">
        <!-- Sidebar -->
        <?php include 'admin-sidebar.php'; ?>

        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">
                    <!-- If floor number is not set, prompt for it; if set, prompt for room number -->
                    <?php if ($floorNo === null): ?>
                        <form method="POST" action="">
                            <div class="form-group">
                                <label for="floorNo">Enter Floor Number:</label>
                                <input type="number" name="floorNo" id="floorNo" class="form-control" required>
                            </div>
                            <button type="submit" class="btn btn-primary mt-2">Next</button>
                        </form>
                    <?php elseif ($roomNo === null): ?>
                        <form method="POST" action="">
                            <input type="hidden" name="floorNo" value="<?php echo $floorNo; ?>">
                            <div class="form-group">
                                <label for="roomNo">Enter Room Number:</label>
                                <input type="number" name="roomNo" id="roomNo" class="form-control" required>
                            </div>
                            <button type="submit" class="btn btn-primary mt-2">Get Seats</button>
                        </form>
                    <?php endif; ?>

                    <!-- Display the seats table if available -->
                    <?php if ($seats !== false): ?>
                        <h2 class="mt-4">Seats for Floor <?php echo $floorNo; ?>, Room <?php echo $roomNo; ?></h2>
                        <div class="card shadow mb-4">
                            <div class="card-body">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>Seat ID</th>
                                            <th>Seat No</th>
                                            <th>User ID</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($seats as $seat): ?>
                                            <tr>
                                                <td><?php echo $seat['seat_id']; ?></td>
                                                <td><?php echo $seat['seat_no']; ?></td>
                                                <td><?php echo $seat['user_id']; ?></td>
                                                <td><?php echo ($seat['status'] == 0) ? 'Available' : 'Occupied'; ?></td>
                                                <td>
                                                    <?php if ($seat['status'] == 0): ?>
                                                        <!-- Delete form for available seat -->
                                                        <form method="POST" action="delete-seat.php"
                                                            onsubmit="return confirm('Are you sure you want to delete this seat?');">
                                                            <input type="hidden" name="seatId" value="<?php echo $seat['seat_id']; ?>">
                                                            <button type="submit" name="deleteSeat" class="btn btn-danger btn-sm">
                                                                <i class="fas fa-trash-alt"></i> Delete
                                                            </button>
                                                        </form>
                                                    <?php else: ?>
                                                        <span class="text-muted">N/A</span>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    <?php elseif ($floorNo !== null && $roomNo !== null): ?>
                        <p class="mt-4">No seats found for Floor <?php echo $floorNo; ?>, Room <?php echo $roomNo; ?>.</p>
                    <?php endif; ?>

                    <!-- Add More Seats Section -->
                    <?php if ($floorNo !== null && $roomNo !== null): ?>
                        <div class="card shadow mt-4">
                            <div class="card-header">
                                <h5 class="mb-0">Add More Seats</h5>
                            </div>
                            <div class="card-body">
                                <form method="POST" action="">
                                    <input type="hidden" name="floorNo" value="<?php echo $floorNo; ?>">
                                    <input type="hidden" name="roomNo" value="<?php echo $roomNo; ?>">
                                    <div class="mb-3">
                                        <label for="seatCount" class="form-label">Number of New Seats to Add:</label>
                                        <input type="number" class="form-control" name="seatCount" id="seatCount" required>
                                    </div>
                                    <button type="submit" name="addSeats" class="btn btn-success">
                                        <i class="fas fa-plus"></i> Add Seats
                                    </button>
                                </form>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </main>
            <footer class="py-4 dashboard-copyright-footer mt-auto">
                <div class="container-fluid px-4">
                    <div class="d-flex align-items-center justify-content-between small">
                        <div class="text-muted">Copyright &copy; Just 2024</div>
                        <div>
                            <a href="#">Privacy Policy</a>
                            &middot;
                            <a href="#">Terms &amp; Conditions</a>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>

    <!-- JavaScript dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
        crossorigin="anonymous"></script>
    <script src="script.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js"
        crossorigin="anonymous"></script>
    <script src="assets/demo/chart-area-demo.js"></script>
    <script src="assets/demo/chart-bar-demo.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js"
        crossorigin="anonymous"></script>
    <script src="../js/datatables-simple-demo.js"></script>
    <script>
        window.addEventListener('DOMContentLoaded', event => {
            const sidebarToggle = document.body.querySelector('#sidebarToggle');
            if (sidebarToggle) {
                sidebarToggle.addEventListener('click', event => {
                    event.preventDefault();
                    document.body.classList.toggle('sb-sidenav-toggled');
                    localStorage.setItem('sb|sidebar-toggle', document.body.classList.contains('sb-sidenav-toggled'));
                });
            }
        });
    </script>
</body>

</html>
