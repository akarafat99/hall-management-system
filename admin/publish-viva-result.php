<?php
include_once '../class-file/SessionManager.php';
$session = SessionStatic::class;
include_once '../class-file/Auth.php';
auth('admin'); // Check if the user is authenticated as an admin

include_once '../class-file/User.php';
include_once '../class-file/UserDetails.php';
include_once '../class-file/FileManager.php';
include_once '../class-file/HallSeatApplication.php';
include_once '../class-file/HallSeatAllocationEvent.php';
include_once '../class-file/Department.php';
include_once '../popup-1.php';

// Flash message
if ($session::get("msg1")) {
    showPopup($session::get("msg1"));
    $session::delete("msg1");
}

// Init
$hallSeatAllocationEvent = new HallSeatAllocationEvent();
$hallSeatApplication = new HallSeatApplication();
$department          = new Department();
$departmentList      = $department->getDepartments();
$user                = new User();
$userDetails         = new UserDetails();

// finalize and publish results
if (isset($_POST['finalize'])) {
    $eventId = $_POST['event_id'];
    $hallSeatApplication->updateStatusForEvent($eventId, -2, 2);
    $hallSeatApplication->updateStatusForEvent($eventId, -3, 3);
    $hallSeatApplication->updateStatusForEvent($eventId, 1, -4);

    $hallSeatAllocationEvent->updateStatus($eventId, 3);

    $session::set("msg1", "Viva results published successfully.");
    echo "<script>window.location.href='hall-seat-allocation-event-dashboard.php?eventId={$eventId}';</script>";
    exit;
}

// Handle Approve/Reject
if (isset($_POST['approve'])) {
    $id     = $_POST['application_id'];
    $evt    = $_POST['event_id'];
    $result = $hallSeatApplication->updateStatus($id, -2);
    $session::set(
        "msg1",
        $result
            ? "Application #{$id} approved."
            : "Failed to approve #{$id}."
    );
    echo "<script>window.location.href='publish-viva-result.php?eventId={$evt}';</script>";
    exit;
}
if (isset($_POST['reject'])) {
    $id     = $_POST['application_id'];
    $evt    = $_POST['event_id'];
    $result = $hallSeatApplication->updateStatus($id, -3);
    $session::set(
        "msg1",
        $result
            ? "Application #{$id} rejected."
            : "Failed to reject #{$id}."
    );
    echo "<script>window.location.href='publish-viva-result.php?eventId={$evt}';</script>";
    exit;
}

// Require eventId
if (!isset($_GET['eventId'])) {
    $session::set("msg1", "No event specified.");
    echo "<script>window.location.href='hall-seat-allocation-event-management.php';</script>";
    exit;
}

$eventId = $_GET['eventId'];

// Fetch statuses 1, -2, -3
$allApplications = $hallSeatApplication
    ->getApplicationsByUserIdEventStatus(null, $eventId, [1, -2, -3]);

// Compute counts
$totalApplications = count($allApplications);
$totalAccepted     = 0;
$totalDeclined     = 0;
$totalNotAppeared  = 0;
foreach ($allApplications as $app) {
    $hallSeatApplication->setProperties($app);
    switch ($hallSeatApplication->status) {
        case -2:
            $totalAccepted++;
            break;
        case -3:
            $totalDeclined++;
            break;
        case  1:
            $totalNotAppeared++;
            break;
    }
}
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
                    <h3 class="text-center my-4">Review User Applications</h3>


                    <div class="card mb-4">
                        <div class="card-body">
                            <div class="row text-center gx-3 gy-4">
                                <div class="col-6 col-md-3">
                                    <div class="p-3 bg-primary text-white rounded">
                                        <h6 class="mb-1">Total Applications</h6>
                                        <span class="h2"><?= $totalApplications ?></span>
                                    </div>
                                </div>
                                <div class="col-6 col-md-3">
                                    <div class="p-3 bg-success text-white rounded">
                                        <h6 class="mb-1">Accepted</h6>
                                        <span class="h2"><?= $totalAccepted ?></span>
                                    </div>
                                </div>
                                <div class="col-6 col-md-3">
                                    <div class="p-3 bg-danger text-white rounded">
                                        <h6 class="mb-1">Declined</h6>
                                        <span class="h2"><?= $totalDeclined ?></span>
                                    </div>
                                </div>
                                <div class="col-6 col-md-3">
                                    <div class="p-3 bg-warning text-dark rounded">
                                        <h6 class="mb-1">Not Appeared</h6>
                                        <span class="h2"><?= $totalNotAppeared ?></span>
                                    </div>
                                </div>
                            </div>

                            <!-- Info text -->
                            <p class="mt-3 mb-4 text-center text-muted small">
                                Note: Applicants marked “Not Appeared” will be automatically rejected when you finalize.
                            </p>

                            <div class="d-flex justify-content-end mt-4">
                                <form method="post" action="">
                                    <input type="hidden" name="event_id" value="<?= htmlspecialchars($eventId) ?>">
                                    <button
                                        type="submit"
                                        name="finalize"
                                        class="btn btn-lg btn-outline-success">
                                        Finalize &amp; Publish Results
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>



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
                                    <th>Status</th>
                                    <th>Profile</th>
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
                                ?>
                                    <tr>
                                        <td><?php echo  htmlspecialchars($id) ?></td>
                                        <td><?php echo  htmlspecialchars($userDetails->full_name) ?></td>
                                        <td><?php echo  htmlspecialchars($userDetails->student_id) ?></td>
                                        <td>
                                            <?php if ($status == 1): ?>
                                                Not appeared<br>
                                                <small class="text-muted">(will be rejected)</small>
                                            <?php elseif ($status == -2): ?>
                                                <span class="text-success">Accepted</span>
                                            <?php else: ?>
                                                <span class="text-danger">Rejected</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <a href="view-profile.php?userDetailsId=<?php echo htmlspecialchars($userDetails->details_id) ?>"
                                                class="btn btn-sm btn-outline-primary">View Profile</a>
                                        </td>
                                        <td>
                                            <form method="post" class="d-inline">
                                                <input type="hidden" name="application_id" value="<?php echo  $id ?>" />
                                                <input type="hidden" name="event_id" value="<?php echo  htmlspecialchars($eventId) ?>" />

                                                <?php if ($status == 1): ?>
                                                    <button name="approve" class="btn btn-sm btn-success me-1">Approve</button>
                                                    <button name="reject" class="btn btn-sm btn-danger">Reject</button>
                                                <?php elseif ($status == -2): ?>
                                                    <button name="reject" class="btn btn-sm btn-danger">Reject</button>
                                                <?php else: ?>
                                                    <button name="approve" class="btn btn-sm btn-success">Approve</button>
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