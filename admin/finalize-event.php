<?php
include_once '../class-file/SessionManager.php';
$session = SessionStatic::class;
include_once '../class-file/Auth.php';
auth('admin');

include_once '../class-file/User.php';
include_once '../class-file/UserDetails.php';
include_once '../class-file/FileManager.php';
include_once '../class-file/HallSeatApplication.php';
include_once '../class-file/HallSeatAllocationEvent.php';
include_once '../class-file/HallSeatDetails.php';
include_once '../class-file/Department.php';
include_once '../popup-1.php';

// Flash message
if ($session::get("msg1")) {
    showPopup($session::get("msg1"), 7000);
    $session::delete("msg1");
}

// Init
$hallSeatDetails     = new HallSeatDetails();
$hallSeatAllocationEvent = new HallSeatAllocationEvent();
$hallSeatApplication = new HallSeatApplication();
$department          = new Department();
$departmentList      = $department->getDepartments();
$user                = new User();
$userDetails         = new UserDetails();

if (isset($_POST['finalize_event'])) {
    $eventId = intval($_POST['eventId']);

    $allRemainingSeats = $hallSeatDetails->getAllSeatIdsByEventIdAndStatus($eventId, 2);

    // now reset *all* seats that were reserved by this event and still have status=2,
    // back to status=0 and reserved_by_event_id=0
    $affected = $hallSeatDetails->updateRowsByStatusAndEventIdAndLimit(
        /* currentStatus */      2,
        /* newStatus     */      0,
        /* eventId       */      $eventId,
        /* resetEventId  */      $eventId,
        /* limit         */      count($allRemainingSeats),
        /* order         */      'ASC'
    );

    if ($affected == false) {
        $session::set('msg1', "Failed to un-reserve seats.");
    } else {
        $hallSeatAllocationEvent->updateStatus($eventId, 5); // 5 = finalized
        $session::set('msg1', "$affected seats un-reserved successfully.");
    }

    // redirect back
    echo "<script>window.location.href='hall-seat-allocation-event-dashboard.php?eventId=$eventId';</script>";
    exit;
}


// Require eventId
if (!isset($_GET['eventId'])) {
    $session::set("msg1", "No event specified.");
    echo "<script>window.location.href='hall-seat-allocation-event-management.php';</script>";
    exit;
}

$eventId = $_GET['eventId'];

// Fetch only the relevant statuses
$allApplications = $hallSeatApplication
    ->getApplicationsByUserIdEventStatus(null, $eventId, [5, 6]);
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <title>MM HALL – Review Applications</title>

    <!-- 1) Bootstrap 5 CSS -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css"
        rel="stylesheet" />

    <!-- 2) DataTables + Bootstrap5 CSS -->
    <link
        href="https://cdn.datatables.net/1.13.5/css/dataTables.bootstrap5.min.css"
        rel="stylesheet" />

    <!-- Your custom styles -->
    <link href="../css2/sidebar-admin.css" rel="stylesheet" />

</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <!-- sidebar -->
            <?php include 'sidebar-admin.php'; ?>

            <main id="mainContent" class="col">
                <!-- mobile sidebar toggle -->
                <button
                    class="btn btn-dark d-lg-none mt-3 mb-3"
                    data-bs-toggle="collapse"
                    data-bs-target="#sidebarMenu">☰ Menu</button>

                <!-- back link -->
                <div class="mb-4 mt-4">
                    <a
                        href="hall-seat-allocation-event-dashboard.php?eventId=<?php echo  htmlspecialchars($eventId) ?>"
                        class="btn btn-outline-secondary">
                        ← Back
                    </a>
                </div>

                <?php
                // Count pending (5) vs confirmed (6)
                $pending   = 0;
                $confirmed = 0;
                foreach ($allApplications as $app) {
                    if ($app['status'] == 5) {
                        $pending++;
                    } elseif ($app['status'] == 6) {
                        $confirmed++;
                    }
                }
                ?>
                <!-- Finalize Form & Overview -->
                <div class="m-4 p-4 border rounded shadow-sm">
                    <div class="row mb-3 text-center">
                        <div class="col-md-6 mb-2">
                            <div class="card bg-success text-white">
                                <div class="card-body">
                                    <p class="mb-1">Confirmed Seats</p>
                                    <h3 class="mb-0"><?php echo $confirmed; ?></h3>
                                    <small><?php echo $confirmed; ?> student<?php echo $confirmed != 1 ? 's' : ''; ?> have confirmed their seat.</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-2">
                            <div class="card bg-warning text-dark">
                                <div class="card-body">
                                    <p class="mb-1">Unconfirmed Seats</p>
                                    <h3 class="mb-0"><?php echo $pending; ?></h3>
                                    <small><?php echo $pending; ?> student<?php echo $pending != 1 ? 's' : ''; ?> have allocated seat but have not yet confirmed.</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="alert alert-danger" role="alert">
                        <i class="fas fa-exclamation-triangle me-1"></i>
                        <strong>Caution:</strong> once you finalize, no further seat confirmations are possible.
                    </div>

                    <form method="post" action="finalize-event.php">
                        <input type="hidden" name="eventId" value="<?php echo htmlspecialchars($eventId); ?>">

                        <div class="mb-3">
                            <label for="confirmInput" class="form-label">
                                Type <code>FINALIZE EVENT</code> to enable:
                            </label>
                            <input
                                id="confirmInput"
                                name="confirmation_text"
                                type="text"
                                class="form-control"
                                placeholder="Type FINALIZE EVENT exactly"
                                autocomplete="off">
                        </div>

                        <div class="text-end">
                            <button
                                id="finalizeBtn"
                                type="submit"
                                name="finalize_event"
                                class="btn btn-lg btn-danger"
                                disabled>
                                <i class="fas fa-flag-checkered me-1"></i>
                                Finalize & Publish
                            </button>
                        </div>
                    </form>
                </div>

            </main>
        </div>
    </div>


    <script>
        (function() {
            const input = document.getElementById('confirmInput');
            const btn = document.getElementById('finalizeBtn');
            const required = 'FINALIZE EVENT';

            input.addEventListener('input', function() {
                // use == for comparison
                if (this.value.trim().toUpperCase() == required) {
                    btn.disabled = false;
                } else {
                    btn.disabled = true;
                }
            });
        })();
    </script>


</body>

</html>