<?php
include_once '../class-file/HallSeatDetails.php';
$seatDetails = new HallSeatDetails();

// --- Process form submissions ---

// Create Floor Process
if (isset($_POST['createFloor'])) {
    $roomWiseSeats = [];
    $floorNo = $_POST['floorNo'];
    $numRooms = $_POST['numRooms'];

    // Collect seat count for each room
    for ($i = 1; $i <= $numRooms; $i++) {
        if (isset($_POST['seats' . $i])) {
            $roomWiseSeats[] = $_POST['seats' . $i];
        }
    }
    // Determine starting room number for this floor
    $startingRoomNo = $seatDetails->getHighestRoomNoByFloor($floorNo) + 1;
    $seatDetails->createMultipleSeatsOptimized($floorNo, $startingRoomNo, $roomWiseSeats);

    include_once '../class-file/SessionManager.php';
    $session = new SessionManager();
    $session->set(
        'msg1',
        'Floor no ' . $floorNo . ' with ' . $numRooms . ' room(s) and ' . array_sum($roomWiseSeats) . ' seat(s) created successfully!'
    );
    echo '<script>window.location.href="seat-management.php";</script>';
    exit();
}

// Add Room Process
if (isset($_POST['addRoom'])) {
    // Retrieve floor number from GET or hidden field
    $floorNo = isset($_GET['floorNo']) ? $_GET['floorNo'] : $_POST['floorNo'];
    $roomWiseSeats = [];
    $numRooms = $_POST['numRooms'];

    // Collect new room seat counts
    for ($i = 1; $i <= $numRooms; $i++) {
        if (isset($_POST['seats' . $i])) {
            $roomWiseSeats[] = $_POST['seats' . $i];
            // echo $_POST['seats' . $i] . ' in a room <br>';
        }
    }
    // Get the next available room number for the floor
    $startingRoomNo = $seatDetails->getHighestRoomNoByFloor($floorNo) + 1;
    // echo $startingRoomNo . ' START room <br>';
    $seatDetails->createMultipleSeatsOptimized($floorNo, $startingRoomNo, $roomWiseSeats);

    include_once '../class-file/SessionManager.php';
    $session = new SessionManager();
    $session->set(
        'msg1',
        'Added ' . $numRooms . ' new room(s) to floor ' . $floorNo . ' with a total of ' . array_sum($roomWiseSeats) . ' seat(s) successfully!'
    );
    echo '<script>window.location.href="seat-management.php";</script>';
    exit();
}

// --- Determine which form to display ---

// If GET parameter "floorNo" is not set, we are creating a new floor.
$isCreateFloor = !isset($_GET['floorNo']);

if ($isCreateFloor) {
    // For a new floor, floor number is the current max plus 1.
    $totalFloors = $seatDetails->getMaxFloorNo();
    $newFloorNo = $totalFloors + 1;
} else {
    // For adding a room, use the provided floor number.
    $existingFloorNo = $_GET['floorNo'];
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Seat Management - Dashboard</title>
    <!-- CSS links -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
        integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css" rel="stylesheet" />
    <link href="../css/Dashboard/dashboard.css" rel="stylesheet" />
    <style>
        .input-group {
            margin-top: 20px;
        }

        .form-label {
            font-weight: 600;
            color: #333;
        }

        .form-control {
            border-radius: 10px;
            padding: 10px;
            font-size: 16px;
        }

        .form-control-plaintext {
            padding: 10px;
        }

        .row.input-group {
            margin-bottom: 15px;
        }
    </style>
</head>

<body class="sb-nav-fixed">
    <nav class="sb-topnav navbar navbar-expand dashboard-nav py-4">
        <a class="navbar-brand ps-3" href="../index.html">HMS</a>
        <button class="btn btn-link btn-sm me-4" id="sidebarToggle"><i class="fas fa-bars"></i></button>
    </nav>
    <div id="layoutSidenav">
        <?php include '../admin/admin-sidebar.php'; ?>
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">
                    <div class="card__wrapper">
                        <?php if ($isCreateFloor): ?>
                            <!-- Create Floor Form -->
                            <div class="card__title-wrap mb-20">
                                <h3>Create Floor, Rooms &amp; Seats</h3>
                            </div>
                            <form id="roomForm" action="" method="POST">
                                <input type="hidden" name="floorNo" value="<?php echo $newFloorNo; ?>" />
                                <div class="input-group">
                                    <h3>Floor no: <?php echo $newFloorNo; ?></h3>
                                </div>
                                <div class="input-group">
                                    <label for="numRooms" class="form-label">Number of Rooms on this Floor:</label>
                                    <input type="number" id="numRooms" name="numRooms" min="1" class="form-control" required />
                                </div>
                                <!-- Container for dynamic room seat inputs -->
                                <div id="roomInputContainer"></div>
                                <div class="text-center mt-4">
                                    <button type="submit" class="btn btn-primary" name="createFloor">Submit</button>
                                </div>
                            </form>
                        <?php else: ?>
                            <!-- Add Room Form -->
                            <div class="card__title-wrap mb-20">
                                <h3>Add Room(s) to Floor <?php echo $existingFloorNo; ?></h3>
                            </div>
                            <form id="addRoomForm" action="" method="POST">
                                <input type="hidden" name="floorNo" value="<?php echo $existingFloorNo; ?>" />
                                <div class="input-group">
                                    <h3>Floor no: <?php echo $existingFloorNo; ?></h3>
                                </div>
                                <div class="input-group">
                                    <label for="numRoomsAdd" class="form-label">Number of Additional Rooms:</label>
                                    <input type="number" id="numRoomsAdd" name="numRooms" min="1" class="form-control" required />
                                </div>
                                <!-- Container for dynamic add-room inputs -->
                                <div id="addRoomInputContainer"></div>
                                <div class="text-center mt-4">
                                    <button type="submit" class="btn btn-primary" name="addRoom">Add Room(s)</button>
                                </div>
                            </form>
                        <?php endif; ?>
                    </div>
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
    <!-- JavaScript links -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script>
        // For "Create Floor" dynamic inputs
        $('#numRooms').on('input', function() {
            const numRooms = $(this).val();
            const container = $('#roomInputContainer');
            container.empty();
            if (numRooms > 0) {
                for (let i = 1; i <= numRooms; i++) {
                    container.append(`
                        <div class="row input-group mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Room no:</label>
                                <p class="form-control-plaintext">Room ${i}</p>
                            </div>
                            <div class="col-md-6">
                                <label for="seats${i}" class="form-label">Total Seats:</label>
                                <input type="number" class="form-control" id="seats${i}" name="seats${i}" min="1" value="4" required />
                            </div>
                        </div>
                    `);
                }
            }
        });

        // For "Add Room" dynamic inputs
        $('#numRoomsAdd').on('input', function() {
            const numRooms = $(this).val();
            const container = $('#addRoomInputContainer');
            container.empty();
            if (numRooms > 0) {
                for (let i = 1; i <= numRooms; i++) {
                    container.append(`
                        <div class="row input-group mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Room no:</label>
                                <p class="form-control-plaintext">Room ${i}</p>
                            </div>
                            <div class="col-md-6">
                                <label for="seats${i}" class="form-label">Total Seats:</label>
                                <input type="number" class="form-control" id="seats${i}" name="seats${i}" min="1" value="4" required />
                            </div>
                        </div>
                    `);
                }
            }
        });
    </script>
</body>

</html>