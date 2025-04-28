<?php
include_once '../class-file/SessionManager.php';
$session = SessionStatic::class;
include_once '../class-file/Auth.php';
auth('admin');

include_once '../class-file/HallSeatDetails.php';
include_once '../popup-1.php';
if($session::get('msg1')) {
    showPopup($session::get('msg1'));
    $session::delete('msg1');
}

$seatDetails    = new HallSeatDetails();
$floorAvailable = false;

if (isset($_GET['floorNo'])) {
    $floorNo        = (int) $_GET['floorNo'];
    $floorAvailable = $seatDetails->isFloorExist($floorNo);
} else {
    $floorNo = -1;
}

/* status‑change handlers */
if (!empty($_POST['seatId'])) {
    $seatId = (int) $_POST['seatId'];

    if (isset($_POST['releaseSeat'])) {       
        $seatDetails->updateStatus($seatId, 0); // Available
        $session::set('msg1', 'Seat released successfully!');
    }
    if (isset($_POST['unavailableSeat'])) {
        $seatDetails->updateStatus($seatId, 3); // Unavailable
        $session::set('msg1', 'Seat made unavailable successfully!');
    }
    if (isset($_POST['availableSeat'])) {
        $seatDetails->updateStatus($seatId, 0); // Available
        $session::set('msg1', 'Seat made available successfully!');
    }

    echo "<script>window.location.href='floorwise.php?floorNo=$floorNo';</script>";
    exit;
}

$allSeats = $seatDetails->getRowsByFloorNo($floorNo, [0, 1, 2, 3]);    // 0 avail, 1 occ, 2 res, 3 unavail
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Seat Management – Floor <?php echo $floorNo; ?></title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <!-- Font Awesome (icons) -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" rel="stylesheet" />
    <!-- simple-datatables -->
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    <!-- Custom sidebar (optional) -->
    <link href="../css2/sidebar-admin.css" rel="stylesheet" />
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <?php include 'sidebar-admin.php'; ?>

            <main id="mainContent" class="col">
                <!-- sidebar toggle for mobile -->
                <button class="btn btn-dark d-lg-none my-3" type="button"
                    data-bs-toggle="collapse" data-bs-target="#sidebarMenu">
                    ☰ Menu
                </button>

                <?php if ($floorAvailable) { ?>
                    <div class="px-4 py-3">

                        <h1 class="text-center mb-4">Floor‑wise Details (Floor: <?php echo $floorNo; ?>)</h1>

                        <!-- Edit / Add Room controls -->
                        <div class="row g-3 mb-4">
                            <!-- Edit Room -->
                            <div class="col-md-6">
                                <div class="accordion" id="editRoomAcc">
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="editRoomHead">
                                            <button class="accordion-button collapsed" type="button"
                                                data-bs-toggle="collapse" data-bs-target="#editRoomBody"
                                                aria-expanded="false" aria-controls="editRoomBody">
                                                <i class="fas fa-edit me-2"></i>Edit Room
                                            </button>
                                        </h2>
                                        <div id="editRoomBody" class="accordion-collapse collapse"
                                            data-bs-parent="#editRoomAcc">
                                            <div class="accordion-body">
                                                <form class="row gy-2 gx-3 align-items-center" method="POST" action="edit-room.php">
                                                    <input type="hidden" name="floorNo" value="<?php echo $floorNo; ?>">
                                                    <div class="col flex-grow-1">
                                                        <input type="number" name="roomNo" class="form-control form-control-sm"
                                                            placeholder="Room No" required>
                                                    </div>
                                                    <div class="col-auto">
                                                        <button class="btn btn-warning btn-sm" type="submit">
                                                            <i class="fas fa-check me-1"></i>Proceed To Edit
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Add Room (simplified) -->
                            <div class="col-md-6">
                                <a
                                    href="create-floor-room-seat.php?floorNo=<?php echo $floorNo; ?>"
                                    class="btn btn-success w-100">
                                    <i class="fas fa-plus me-2"></i>Add Room(s)
                                </a>
                            </div>


                            <!-- Seat table -->
                            <div class="card shadow-sm">
                                <div class="card-body p-0">
                                    <table id="seatTable" class="table table-bordered table-striped mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Seat ID</th>
                                                <th>Room No</th>
                                                <th>User ID</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($allSeats as $seat) {
                                                $statusText = ['Available', 'Occupied', 'Reserved', 'Unavailable'][$seat['status']] ?? 'Unknown';
                                            ?>
                                                <tr>
                                                    <td><?php echo $seat['seat_id']; ?></td>
                                                    <td><?php echo $seat['room_no']; ?></td>
                                                    <td><?php echo $seat['user_id']; ?></td>
                                                    <td><?php echo $statusText; ?></td>
                                                    <td>
                                                        <?php if ($seat['status'] == 0) { ?>
                                                            <form method="POST" class="d-inline">
                                                                <input type="hidden" name="seatId" value="<?php echo $seat['seat_id']; ?>">
                                                                <button class="btn btn-warning btn-sm" name="unavailableSeat">
                                                                    <i class="fas fa-ban me-1"></i>Make Unavailable
                                                                </button>
                                                            </form>
                                                        <?php } elseif ($seat['status'] == 3) { ?>
                                                            <form method="POST" class="d-inline">
                                                                <input type="hidden" name="seatId" value="<?php echo $seat['seat_id']; ?>">
                                                                <button class="btn btn-success btn-sm" name="availableSeat">
                                                                    <i class="fas fa-check me-1"></i>Make Available
                                                                </button>
                                                            </form>
                                                        <?php } ?>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div><!-- /.px-4 -->
                    <?php } else { ?>
                        <div class="px-4 py-5 text-center">
                            <h2>No Floor Found</h2>
                            <p>Please create a floor first.</p>
                        </div>
                    <?php } ?>
            </main>
        </div>
    </div>

    <!-- Bootstrap bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- simple-datatables -->
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            new simpleDatatables.DataTable('#seatTable');
        });
    </script>
</body>

</html>