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


// Require eventId
if (!isset($_GET['eventId'])) {
    $session::set("msg1", "No event specified.");
    echo "<script>window.location.href='hall-seat-allocation-event-management.php';</script>";
    exit;
}

if (isset($_POST['confirm_seat'])) {
    $applicationId = $_POST['application_id'];
    $eventId       = $_POST['event_id'];

    $hallSeatApplication->application_id = $applicationId;
    $hallSeatApplication->load();

    $hallSeatDetails->loadBySeatId($hallSeatApplication->allotted_seat_id);
    $hallSeatDetails->status = 1; // Seat status: 1 = Allocated
    $hallSeatDetails->user_id = $hallSeatApplication->user_id;
    $hallSeatDetails->update();

    $hallSeatApplication->updateStatus($applicationId, 6); // Confirmed status
    $session::set("msg1", "Seat confirmed successfully. Application ID: $applicationId");
    echo "<script>window.location.href='view-confirm-seat.php?eventId=$eventId';</script>";
    exit;
}
if (isset($_POST['undo_confirm_seat'])) {
    $applicationId = $_POST['application_id'];
    $eventId       = $_POST['event_id'];

    $hallSeatApplication->application_id = $applicationId;
    $hallSeatApplication->load();

    $hallSeatDetails->loadBySeatId($hallSeatApplication->allotted_seat_id);
    $hallSeatDetails->status = 2;
    $hallSeatDetails->user_id = 0;
    $hallSeatDetails->update();

    $hallSeatApplication->updateStatus($applicationId, 5); // Pending status
    $session::set("msg1", "Seat confirmation undone successfully. Application ID: $applicationId");
    echo "<script>window.location.href='view-confirm-seat.php?eventId=$eventId';</script>";
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

                <div class="px-4">
                    <h3 class="text-center my-4">Confirm Seat</h3>

                    <!-- Count pending (5) vs confirmed (6) -->
                    <?php
                    $pendingCount   = 0;
                    $confirmedCount = 0;
                    foreach ($allApplications as $app) {
                        if ($app['status'] == 5) {
                            $pendingCount++;
                        } elseif ($app['status'] == 6) {
                            $confirmedCount++;
                        }
                    }
                    ?>
                    <div class="row mb-4">
                        <div class="col-sm-6 mb-3">
                            <div class="card text-dark bg-warning h-100">
                                <div class="card-body">
                                    <h5 class="card-title">Pending Confirmations</h5>
                                    <p class="card-text display-6"><?php echo $pendingCount; ?></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 mb-3">
                            <div class="card text-white bg-success h-100">
                                <div class="card-body">
                                    <h5 class="card-title">Confirmed Seats</h5>
                                    <p class="card-text display-6"><?php echo $confirmedCount; ?></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Form to confirm or undo confirmation of seats. -->
                    <div class="table-responsive mb-4"><!-- mb-4 on the wrapper -->
                        <table
                            id="applicationsTable"
                            class="table table-striped table-bordered mb-4"
                            style="width:100%">
                            <thead class="table-light">
                                <tr>
                                    <th>Application ID</th>
                                    <th>Name</th>
                                    <th>Student ID</th>
                                    <th>Allotted Seat</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($allApplications as $app):
                                    $hallSeatApplication->setProperties($app);
                                    $id     = $hallSeatApplication->application_id;
                                    $status = $hallSeatApplication->status;
                                    $userDetails->getByDetailsId($hallSeatApplication->user_details_id);
                                    $user->user_id = $userDetails->user_id;
                                    $user->load();

                                    $hallSeatDetails->loadBySeatId($hallSeatApplication->allotted_seat_id);
                                ?>
                                    <tr>
                                        <td><?php echo  htmlspecialchars($id) ?></td>
                                        <td><?php echo  htmlspecialchars($userDetails->full_name) ?></td>
                                        <td><?php echo  htmlspecialchars($userDetails->student_id) ?></td>
                                        <td>
                                            <p>Floor: <?php echo  htmlspecialchars($hallSeatDetails->floor_no) ?></p>
                                            <p>Room: <?php echo  htmlspecialchars($hallSeatDetails->room_no) ?></p>
                                            <p>Seat ID (Number): <?php echo  htmlspecialchars($hallSeatDetails->seat_id) ?></p>
                                        </td>
                                        <td>
                                            <?php
                                            if ($status == 5) {
                                                echo '<span class="badge bg-warning">Pending</span>';
                                            } elseif ($status == 6) {
                                                echo '<span class="badge bg-success">Confirmed</span>';
                                            } else {
                                                echo '<span class="badge bg-secondary">Unknown</span>';
                                            }
                                            ?>
                                        </td>

                                        <td>
                                            <form method="post" class="d-inline">
                                                <input type="hidden" name="application_id" value="<?php echo  $id ?>" />
                                                <input type="hidden" name="event_id" value="<?php echo  htmlspecialchars($eventId) ?>" />

                                                <?php if ($status == 5): ?>
                                                    <button name="confirm_seat" class="btn btn-sm btn-success me-1">Confirm seat</button>
                                                <?php elseif ($status == 6): ?>
                                                    <button name="undo_confirm_seat" class="btn btn-sm btn-danger me-1">Undo confirmation</button>
                                                <?php endif; ?>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div><!-- end of table-responsive mb-4 -->
                </div>


            </main>
        </div>
    </div>

    <!-- 3) jQuery (required by DataTables) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- 4) DataTables core -->
    <script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>

    <!-- 5) DataTables Bootstrap5 integration -->
    <script src="https://cdn.datatables.net/1.13.5/js/dataTables.bootstrap5.min.js"></script>

    <!-- 6) Bootstrap Bundle (optional) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- 7) Initialize DataTables -->
    <script>
        $(document).ready(function() {
            $('#applicationsTable').DataTable({
                paging: true,
                ordering: true,
                info: true,
                searching: true,
                lengthMenu: [
                    [10, 25, 50, 100, 200],
                    [10, 25, 50, 100, 200]
                ],
                pageLength: 100,
                columnDefs: [{
                        type: 'num',
                        targets: 0
                    }, // force numeric sort on Application ID
                    {
                        orderable: false,
                        targets: 4
                    } // disable sorting on Action column
                ]
            });
        });
    </script>
</body>

</html>