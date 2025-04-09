<?php
include_once '../class-file/SessionManager.php';
$session = SessionStatic::class;
if ($session::get('msg1')) {
    include_once '../popup-1.php';
    showPopup($session::get('msg1'));
    $session::delete('msg1');
}


include_once '../class-file/HallSeatDetails.php';
$seatDetails = new HallSeatDetails();

if (isset($_POST['releaseSeat'])) {
    $seatId = $_POST['seatId'];
    $seatDetails = new HallSeatDetails();
    $seatDetails->updateStatus($seatId, 0); // 0 means available

    include_once '../popup-1.php';
    showPopup("Seat released successfully! Seat ID: $seatId", 7000);
    // echo "<script> window.location.href.reload(); </script>";
    echo "<script>
        window.location.href = '?floorNo=$floorNo&roomNo=$roomNo';
    </script>";
    exit();
}

if (isset($_POST['unavailableSeat'])) {
    $seatId = $_POST['seatId'];
    $seatDetails = new HallSeatDetails();
    $seatDetails->updateStatus($seatId, 3); // 3 means unavailable temporarily

    include_once '../popup-1.php';
    showPopup("Seat made unavailable successfully! Seat ID: $seatId", 7000);
    // echo "<script> window.location.href.reload(); </script>";
    echo "<script>
        window.location.href = '?floorNo=$floorNo&roomNo=$roomNo';
    </script>";
    exit();
}

if (isset($_POST['availableSeat'])) {
    $seatId = $_POST['seatId'];
    $seatDetails = new HallSeatDetails();
    $seatDetails->updateStatus($seatId, 0); // 0 means available

    include_once '../popup-1.php';
    showPopup("Seat made available successfully! Seat ID: $seatId", 7000);
    // echo "<script> window.location.href.reload(); </script>";
    echo "<script>
        window.location.href = '?floorNo=$floorNo&roomNo=$roomNo';
    </script>";
    exit();
}


// Handle add seats operation
if (isset($_POST['addSeats'])) {
    $floorNo = intval($_POST['floorNo']);
    $roomNo = intval($_POST['roomNo']);
    $seatCount = intval($_POST['seatCount']);
    $seatDetails->addSeatsToRoom($floorNo, $roomNo, $seatCount);
    $session::set('msg1', 'New ' . $seatCount . ' seats added to Floor ' . $floorNo . ', Room ' . $roomNo . ' successfully!');
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
    $seats = $seatDetails->getRowsByFloorRoomStatus($floorNo, $roomNo, [0, 1, 2, 3]);
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

    <!-- for no seat found part -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />

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
                    <!-- Navigation button to floorwise view -->
                    <?php if ($floorNo !== null): ?>
                        <div class="mb-3">
                            <a href="floorwise.php?floorNo=<?php echo $floorNo; ?>" class="btn btn-secondary btn-view">
                                <i class="fas fa-dashboard"></i> Goto Floorwise Details Page
                            </a>
                        </div>
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
                                            <th>User ID</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($seats as $seat):
                                            $status = '';
                                            // Determine the status based on the value of 'status' field
                                            if ($seat['status'] == 0) {
                                                $status = 'Available';
                                            } elseif ($seat['status'] == 1) {
                                                $status = 'Occupied';
                                            } elseif ($seat['status'] == 2) {
                                                $status = 'Reserved';
                                            } elseif ($seat['status'] == 3) {
                                                $status = 'Unavailable temporarily';
                                            } else {
                                                $status = 'Unknown status';
                                            }

                                        ?>

                                            <tr>
                                                <td><?php echo $seat['seat_id']; ?></td>
                                                <td><?php echo $seat['user_id']; ?></td>
                                                <td><?php echo $status ?></td>
                                                <td>
                                                    <!-- Button designs for each status -->
                                                    <?php if ($seat['status'] == 0) { ?>
                                                        <div class="btn-group" role="group">
                                                            <form method="POST" action="" class="d-inline me-1">
                                                                <input type="hidden" name="seatId" value="<?php echo $seat['seat_id']; ?>">
                                                                <button type="submit" name="unavailableSeat" value="<?php echo $seat['seat_id']; ?>" class="btn btn-warning btn-sm">
                                                                    <i class="fas fa-ban"></i> Make Unavailable
                                                                </button>
                                                            </form>
                                                            <form method="POST" action="" class="d-inline">
                                                                <input type="hidden" name="seatId" value="<?php echo $seat['seat_id']; ?>">
                                                                <button type="submit" name="deleteSeat" value="<?php echo $seat['seat_id']; ?>" class="btn btn-danger btn-sm">
                                                                    <i class="fas fa-trash"></i> Delete
                                                                </button>
                                                            </form>
                                                        </div>
                                                    <?php } elseif ($seat['status'] == 1) { ?>
                                                        <form method="POST" action="" class="d-inline">
                                                            <input type="hidden" name="seatId" value="<?php echo $seat['seat_id']; ?>">
                                                            <button type="submit" name="releaseSeat" value="<?php echo $seat['seat_id']; ?>" class="btn btn-info btn-sm">
                                                                <i class="fas fa-undo"></i> Release
                                                            </button>
                                                        </form>
                                                    <?php } elseif ($seat['status'] == 3) { ?>
                                                        <form method="POST" action="" class="d-inline">
                                                            <input type="hidden" name="seatId" value="<?php echo $seat['seat_id']; ?>">
                                                            <button type="submit" name="availableSeat" value="<?php echo $seat['seat_id']; ?>" class="btn btn-success btn-sm">
                                                                <i class="fas fa-check"></i> Make Available
                                                            </button>
                                                        </form>
                                                    <?php } ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Add More Seats Section -->
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
                    <?php elseif ($floorNo !== null && $roomNo !== null): ?>
                        <div class="card shadow my-4 animate__animated animate__fadeInDown">
                            <div class="card-body text-center">
                                <!-- Animated motion icon -->
                                <i class="fas fa-exclamation-triangle animate__animated animate__bounce animate__infinite text-warning" style="font-size: 4rem;"></i>
                                <h2 class="display-4 text-danger mt-3">Room Not Found</h2>
                                <p class="lead">
                                    We couldn't locate Room <?php echo $roomNo; ?> on Floor <?php echo $floorNo; ?>.
                                    Please verify your input or return to the floor overview page.
                                </p>
                                <a href="floorwise.php?floorNo=<?php echo $floorNo; ?>" class="btn btn-primary btn-lg mt-3">
                                    <i class="fas fa-arrow-left"></i> Back to Floor Overview
                                </a>
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