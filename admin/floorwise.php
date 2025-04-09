<?php
include_once '../class-file/HallSeatDetails.php';

$seatDetails = new HallSeatDetails();

$floorAvailable = false;
if (isset($_GET['floorNo'])) {
    $floorNo = $_GET['floorNo'];
    $floorAvailable = $seatDetails->isFloorExist($floorNo);
} else {
    $floorNo = -1; // Default value if floorNo is not set
}

if (isset($_POST['releaseSeat'])) {
    $seatId = $_POST['seatId'];
    $seatDetails->updateStatus($seatId, 0); // 0 means available

    include_once '../popup-1.php';
    showPopup("Seat released successfully! Seat ID: $seatId", 7000);
    echo "<script> window.location.href.reload(); </script>";
}

if (isset($_POST['unavailableSeat'])) {
    $seatId = $_POST['seatId'];
    $seatDetails->updateStatus($seatId, 3); // 3 means unavailable temporarily

    include_once '../popup-1.php';
    showPopup("Seat made unavailable successfully! Seat ID: $seatId", 7000);
    echo "<script> window.location.href.reload(); </script>";
}

if (isset($_POST['availableSeat'])) {
    $seatId = $_POST['seatId'];
    $seatDetails->updateStatus($seatId, 0); // 0 means available

    include_once '../popup-1.php';
    showPopup("Seat made available successfully! Seat ID: $seatId", 7000);
    echo "<script> window.location.href.reload(); </script>";
}

$allSeats = $seatDetails->getRowsByFloorNo($floorNo, [0, 1, 2, 3]); // 0 means available, 1 means occupied, 2 means reserved, 3 means unavailable temporarily
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
    <script src="https://use.fontawesome.com/releases/6.3.0/js/all.js" crossorigin="anonymous"></script>
    <title>MM HALL - Dashboard</title>
</head>

<body class="sb-nav-fixed">
    <nav class="sb-topnav navbar navbar-expand dashboard-nav py-4">
        <a class="navbar-brand ps-3" href="../index.html">HMS</a>
        <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!"><i
                class="fas fa-bars"></i></button>
    </nav>

    <div id="layoutSidenav">
        <?php include 'admin-sidebar.php'; ?>

        <div id="layoutSidenav_content">
            <main>
                <?php if ($floorAvailable == true) { ?>
                <div class="container-fluid px-4">
                    <h1 class="mt-4 text-center">Floor-wise details (Floor : <?php echo $floorNo; ?>)</h1>

                    <!-- Seat Management Table -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-table me-1"></i>
                            Seat Management Table
                        </div>
                        <div class="card-header">
                            <form method="POST" action="edit-room.php" class="row g-3 align-items-center">
                                <input type="hidden" name="floorNo" value="<?php echo $floorNo; ?>">
                                <div class="col-auto">
                                    <label for="roomNo" class="col-form-label">Room No:</label>
                                </div>
                                <div class="col-auto">
                                    <input type="number" name="roomNo" id="roomNo" class="form-control" required>
                                </div>
                                <div class="col-auto">
                                    <button type="submit" class="btn btn-warning">Edit Room</button>
                                </div>
                            </form>
                        </div>
                        <div class="card-header mt-3">
                            <a class="btn btn-success" href="create-floor-room-seat.php?floorNo=<?php echo $floorNo; ?>">Add
                                Room(s)</a>
                        </div>


                        <div class="card-body">
                            <table id="seatManagementTable" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Seat ID</th>
                                        <th>Room No</th>
                                        <th>User ID</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if ($allSeats) {
                                        foreach ($allSeats as $seat) {
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
                                                <td><?php echo $seat['room_no']; ?></td>
                                                <td><?php echo $seat['user_id']; ?></td>
                                                <td><?php echo $status; ?></td>
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
                                    <?php
                                        }
                                    }
                                    ?>
                                </tbody>

                            </table>
                        </div>
                    </div>
                </div>
                <?php } else { ?>
                <div class="container-fluid px-4">
                    <h1 class="mt-4 text-center">No Floor Found</h1>
                    <p class="text-center">Please create a floor first.</p>
                </div>
                <?php } ?>
            </main>
            <footer class="py-4 dashboard-copyright-footer mt-auto">
                <div class="container-fluid px-4">
                    <div class="d-flex align-items-center justify-content-between small">
                        <div class="text-muted">Copyright &copy; Just 2024</div>
                    </div>
                </div>
            </footer>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
        crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js"
        crossorigin="anonymous"></script>
    <script src="../js/datatables-simple-demo.js"></script>

    <script>
        window.addEventListener('DOMContentLoaded', event => {
            const table = new simpleDatatables.DataTable("#seatManagementTable");

            window.releaseSeat = function(seatId) {
                if (confirm("Are you sure you want to release this seat?")) {
                    const rows = document.querySelectorAll('#seatManagementTable tbody tr');
                    rows.forEach(row => {
                        const cell = row.querySelector('td');
                        if (cell && cell.textContent == seatId) {
                            row.querySelector('td:nth-child(5)').textContent = 'Available';
                            row.querySelector('button').classList.replace('btn-danger', 'btn-success');
                            row.querySelector('button').textContent = 'Occupied';
                            alert('Seat released successfully!');
                        }
                    });
                }
            };
        });
    </script>
</body>

</html>