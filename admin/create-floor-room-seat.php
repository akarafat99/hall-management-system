<?php

include_once '../class-file/SessionManager.php';
$session = SessionStatic::class;

include_once '../class-file/Auth.php';
auth('admin');

include_once '../class-file/HallSeatDetails.php';
$seatDetails = new HallSeatDetails();

// Handle “Create Floor” submission
if (isset($_POST['createFloor'])) {
    $floorNo      = (int)$_POST['floorNo'];
    $numRooms     = (int)$_POST['numRooms'];
    $roomWiseSeats = [];

    for ($i = 1; $i <= $numRooms; $i++) {
        $roomWiseSeats[] = (int)($_POST["seats{$i}"] ?? 0);
    }
    $startRoomNo = $seatDetails->getHighestRoomNoByFloor($floorNo) + 1;
    $seatDetails->createMultipleSeatsOptimized($floorNo, $startRoomNo, $roomWiseSeats);

    $session::set(
        'msg1',
        "Floor {$floorNo} with {$numRooms} room(s) and " . array_sum($roomWiseSeats) . " seat(s) created!"
    );
    echo '<script>location.href="seat-management.php";</script>';
    exit;
}

// Handle “Add Room” submission
if (isset($_POST['addRoom'])) {
    $floorNo       = (int)($_POST['floorNo']);
    $numRooms      = (int)$_POST['numRooms'];
    $roomWiseSeats = [];

    for ($i = 1; $i <= $numRooms; $i++) {
        $roomWiseSeats[] = (int)($_POST["seats{$i}"] ?? 0);
    }
    $startRoomNo = $seatDetails->getHighestRoomNoByFloor($floorNo) + 1;
    $seatDetails->createMultipleSeatsOptimized($floorNo, $startRoomNo, $roomWiseSeats);

    $session::set(
        'msg1',
        "Added {$numRooms} room(s) to Floor {$floorNo} with " . array_sum($roomWiseSeats) . " seat(s)!"
    );
    echo '<script>location.href="seat-management.php";</script>';
    exit;
}

// Decide which form to show
$isCreateFloor  = !isset($_GET['floorNo']);
if ($isCreateFloor) {
    $newFloorNo    = $seatDetails->getMaxFloorNo() + 1;
    $roomStartNo   = 1;
} else {
    $existingFloorNo = (int)$_GET['floorNo'];
    $roomStartNo     = $seatDetails->getHighestRoomNoByFloor($existingFloorNo) + 1;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>Seat Management</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" />

    <!-- Sidebar styles (if needed) -->
    <link href="../css2/sidebar-admin.css" rel="stylesheet" />

</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <?php include 'sidebar-admin.php'; ?>

            <main id="mainContent" class="col px-4 py-3">
                <button class="btn btn-dark d-lg-none mb-3"
                    data-bs-toggle="collapse" data-bs-target="#sidebarMenu">
                    ☰ Menu
                </button>

                <h3 class="mb-4 mt-4">
                    <?= $isCreateFloor
                        ? 'Create Floor, Rooms & Seats'
                        : "Add Room(s) to Floor {$existingFloorNo}" ?>
                </h3>

                <?php if (! $isCreateFloor): ?>
                    <a href="floorwise.php?floorNo=<?= $existingFloorNo ?>" class="btn btn-secondary mb-4"
                        class="btn btn-secondary mb-3">
                        <i class="fas fa-arrow-left me-1"></i>Back to Floors
                    </a>
                <?php endif; ?>

                <div class="card mb-4">
                    <div class="card-body">
                        <?php if ($isCreateFloor): ?>
                            <form id="roomForm" method="POST">
                                <input type="hidden" name="floorNo" value="<?= $newFloorNo ?>">

                                <div class="mb-3">
                                    <label class="form-label">Floor No</label>
                                    <p class="form-control-plaintext"><?= $newFloorNo ?></p>
                                </div>
                                <div class="mb-3">
                                    <label for="numRooms" class="form-label">Number of Rooms</label>
                                    <input type="number" id="numRooms" name="numRooms" class="form-control" min="1" required>
                                </div>
                                <div id="roomInputContainer"></div>

                                <button type="submit" name="createFloor" class="btn btn-primary">
                                    Submit
                                </button>
                            </form>
                        <?php else: ?>
                            <form id="addRoomForm" method="POST">
                                <input type="hidden" name="floorNo" value="<?= $existingFloorNo ?>">

                                <div class="mb-3">
                                    <label class="form-label">Floor No</label>
                                    <p class="form-control-plaintext"><?= $existingFloorNo ?></p>
                                </div>
                                <div class="mb-3">
                                    <label for="numRoomsAdd" class="form-label">Number of Additional Rooms</label>
                                    <input type="number" id="numRoomsAdd" name="numRooms" class="form-control" min="1" required>
                                </div>
                                <div id="addRoomInputContainer"></div>

                                <button type="submit" name="addRoom" class="btn btn-primary">
                                    Add Room(s)
                                </button>
                            </form>
                        <?php endif; ?>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery (for dynamic inputs) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        (function() {
            const start = <?= $roomStartNo ?>;

            $('#numRooms').on('input', function() {
                const n = +this.value;
                const cont = $('#roomInputContainer').empty();
                for (let i = 1; i <= n; i++) {
                    const roomNo = start + i - 1;
                    cont.append(`
          <div class="row mb-3">
            <div class="col-md-6">
              <label class="form-label">Room ${roomNo}</label>
            </div>
            <div class="col-md-6">
              <label for="seats${i}" class="form-label">Seats</label>
              <input type="number" id="seats${i}" name="seats${i}"
                     class="form-control" min="1" value="4" required>
            </div>
          </div>
        `);
                }
            });

            $('#numRoomsAdd').on('input', function() {
                const n = +this.value;
                const cont = $('#addRoomInputContainer').empty();
                for (let i = 1; i <= n; i++) {
                    const roomNo = start + i - 1;
                    cont.append(`
          <div class="row mb-3">
            <div class="col-md-6">
              <label class="form-label">Room ${roomNo}</label>
            </div>
            <div class="col-md-6">
              <label for="seats${i}" class="form-label">Seats</label>
              <input type="number" id="seats${i}" name="seats${i}"
                     class="form-control" min="1" value="4" required>
            </div>
          </div>
        `);
                }
            });
        })();
    </script>
</body>

</html>