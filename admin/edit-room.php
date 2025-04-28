<?php
include_once '../class-file/SessionManager.php';
$session = SessionStatic::class;
include_once '../class-file/Auth.php';
auth('admin');

if ($session::get('msg1')) {
    include_once '../popup-1.php';
    showPopup($session::get('msg1'));
    $session::delete('msg1');
}

include_once '../class-file/HallSeatDetails.php';
$seatDetails = new HallSeatDetails();

/* ------------- status‑change handlers ------------- */
if (!empty($_POST['seatId'])) {
    $seatId = (int) $_POST['seatId'];
    if (isset($_POST['releaseSeat'])) {
        $seatDetails->updateStatus($seatId, 0);
        $session::set('msg1', "Seat $seatId released.");
    } elseif (isset($_POST['availableSeat'])) {
        $seatDetails->updateStatus($seatId, 0);
        $session::set('msg1', "Seat $seatId available.");
    } elseif (isset($_POST['unavailableSeat'])) {
        $seatDetails->updateStatus($seatId, 3);
        $session::set('msg1', "Seat $seatId unavailable.");
    }

    $f = (int) $_POST['floorNo'];
    $r = (int) $_POST['roomNo'];
    echo "<script>location.href='?floorNo={$f}&roomNo={$r}';</script>";
    exit;
}

/* ------------- add seats ------------- */
if (isset($_POST['addSeats'])) {
    $f = (int) $_POST['floorNo'];
    $r = (int) $_POST['roomNo'];
    $c = (int) $_POST['seatCount'];
    $seatDetails->addSeatsToRoom($f, $r, $c);
    $session::set('msg1', "$c seat(s) added to Floor $f, Room $r.");
    echo "<script>location.href='?floorNo={$f}&roomNo={$r}';</script>";
    exit;
}

/* ------------- fetch context ------------- */
$floorNo = $_GET['floorNo'] ?? $_POST['floorNo'] ?? null;
$roomNo  = $_GET['roomNo']  ?? $_POST['roomNo']  ?? null;
$seats   = ($floorNo !== null && $roomNo !== null)
    ? $seatDetails->getRowsByFloorRoomStatus($floorNo, $roomNo, [0, 1, 2, 3])
    : false;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Room <?php echo $roomNo; ?> – Floor <?php echo $floorNo; ?></title>

    <!-- Bootstrap + Font Awesome -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" rel="stylesheet" />
    <!-- Simple‑Datatables for quick sort/search (optional) -->
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />

    <link href="../css2/sidebar-admin.css" rel="stylesheet" />
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <?php include 'sidebar-admin.php'; ?>

            <main id="mainContent" class="col">

                <button class="btn btn-dark d-lg-none my-3" type="button"
                    data-bs-toggle="collapse" data-bs-target="#sidebarMenu">☰ Menu</button>

                <div class="px-4 py-3">
                    <?php if ($floorNo !== null): ?>
                        <a href="floorwise.php?floorNo=<?php echo $floorNo; ?>" class="btn btn-secondary mb-3">
                            <i class="fas fa-arrow-left me-1"></i>Back to Floor
                        </a>
                    <?php endif; ?>

                    <?php if ($seats): ?>
                        <h2>Floor <?php echo $floorNo; ?> – Room <?php echo $roomNo; ?></h2>

                        <div class="card shadow-sm mb-4">
                            <div class="card-body p-0">
                                <table id="seatTable" class="table table-bordered table-striped mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Seat ID</th>
                                            <th>User ID</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($seats as $seat):
                                            $statusTxt = ['Available', 'Occupied', 'Reserved', 'Unavailable'][$seat['status']] ?? 'Unknown'; ?>
                                            <tr>
                                                <td><?= $seat['seat_id'] ?></td>
                                                <td><?= $seat['user_id'] ?></td>
                                                <td><?= $statusTxt ?></td>
                                                <td>
                                                    <?php if ($seat['status'] == 0): ?>
                                                        <form method="POST" class="d-inline">
                                                            <input type="hidden" name="floorNo" value="<?= $floorNo ?>">
                                                            <input type="hidden" name="roomNo" value="<?= $roomNo  ?>">
                                                            <input type="hidden" name="seatId" value="<?= $seat['seat_id'] ?>">
                                                            <button name="unavailableSeat" class="btn btn-warning btn-sm">
                                                                <i class="fas fa-ban me-1"></i>Unavailable
                                                            </button>
                                                        </form>
                                                    <?php elseif ($seat['status'] == 1): ?>
                                                        <form method="POST" class="d-inline">
                                                            <input type="hidden" name="floorNo" value="<?= $floorNo ?>">
                                                            <input type="hidden" name="roomNo" value="<?= $roomNo  ?>">
                                                            <input type="hidden" name="seatId" value="<?= $seat['seat_id'] ?>">
                                                            <button name="releaseSeat" class="btn btn-info btn-sm">
                                                                <i class="fas fa-undo me-1"></i>Release
                                                            </button>
                                                        </form>
                                                    <?php elseif ($seat['status'] == 3): ?>
                                                        <form method="POST" class="d-inline">
                                                            <input type="hidden" name="floorNo" value="<?= $floorNo ?>">
                                                            <input type="hidden" name="roomNo" value="<?= $roomNo  ?>">
                                                            <input type="hidden" name="seatId" value="<?= $seat['seat_id'] ?>">
                                                            <button name="availableSeat" class="btn btn-success btn-sm">
                                                                <i class="fas fa-check me-1"></i>Available
                                                            </button>
                                                        </form>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Add seats -->
                        <div class="card shadow-sm">
                            <div class="card-header">Add Seats</div>
                            <div class="card-body">
                                <form class="row g-2" method="POST">
                                    <input type="hidden" name="floorNo" value="<?= $floorNo ?>">
                                    <input type="hidden" name="roomNo" value="<?= $roomNo  ?>">
                                    <div class="col-md-4">
                                        <input type="number" min="1" name="seatCount"
                                            class="form-control" placeholder="How many?" required>
                                    </div>
                                    <div class="col-auto">
                                        <button name="addSeats" class="btn btn-success">
                                            <i class="fas fa-plus me-1"></i>Add
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    <?php elseif ($floorNo !== null && $roomNo !== null): ?>
                        <div class="alert alert-danger mt-4">
                            Room <?= $roomNo ?> not found on Floor <?= $floorNo ?>.
                        </div>
                    <?php endif; ?>
                </div>
            </main>
        </div>
    </div>

    <!-- Bootstrap bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- simple‑datatables -->
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => new simpleDatatables.DataTable('#seatTable'));
    </script>
</body>

</html>