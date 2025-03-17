<?php
include_once '../class-file/HallSeatDetails.php';

if (isset($_GET['floorNo'])) {
    $floorNo = $_GET['floorNo'];
} else {
    $floorNo = 0;
}

if (isset($_POST['releaseSeat'])) {
    $seatId = $_POST['seatId'];
    $seatDetails = new HallSeatDetails();
    $seatDetails->loadBySeatId($seatId);
    $seatDetails->status = 0;
    $seatDetails->user_id = 0;
    $seatDetails->update();

    include_once '../popup-1.php';
    showPopup("Seat released successfully! Seat ID: $seatId", 7000);
    echo "<script> window.location.href.reload(); </script>";
    exit();
}

$seatDetails = new HallSeatDetails();
$allSeats = $seatDetails->getRowsByFloorNo($floorNo);
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
                                        <th>Seat No</th>
                                        <th>User ID</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if ($allSeats) {
                                        foreach ($allSeats as $seat) {
                                            $status = $seat['status'] == 1 ? 'Occupied' : 'Available';
                                    ?>
                                            <tr>
                                                <td><?php echo $seat['seat_id']; ?></td>
                                                <td><?php echo $seat['room_no']; ?></td>
                                                <td><?php echo $seat['seat_no']; ?></td>
                                                <td><?php echo $seat['user_id']; ?></td>
                                                <td><?php echo $status; ?></td>
                                                <td>
                                                    <?php if ($seat['status'] == 1) { ?>
                                                        <form method="POST" action="">
                                                            <input type="hidden" name="seatId" value="<?php echo $seat['seat_id']; ?>">
                                                            <button type="submit" name="releaseSeat" value="<?php echo $seat['seat_id']; ?>" class="btn btn-danger">Release user</button>
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