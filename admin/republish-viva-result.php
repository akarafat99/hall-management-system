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
    $hallSeatAllocationEvent->updateStatus($eventId, 3);

    $session::set("msg1", "Viva results re-published successfully.");
    echo "<script>window.location.href='hall-seat-allocation-event-dashboard.php?eventId={$eventId}';</script>";
    exit;
}

// Handle Approve/Reject
if (isset($_POST['approve'])) {
    $id     = $_POST['application_id'];
    $evt    = $_POST['event_id'];
    $result = $hallSeatApplication->updateStatus($id, 2);
    $session::set(
        "msg1",
        $result
            ? "Application #{$id} approved."
            : "Failed to approve #{$id}."
    );
    echo "<script>window.location.href='republish-viva-result.php?eventId={$evt}';</script>";
    exit;
}
if (isset($_POST['reject'])) {
    $id     = $_POST['application_id'];
    $evt    = $_POST['event_id'];
    $result = $hallSeatApplication->updateStatus($id, 3);
    $session::set(
        "msg1",
        $result
            ? "Application #{$id} rejected."
            : "Failed to reject #{$id}."
    );
    echo "<script>window.location.href='republish-viva-result.php?eventId={$evt}';</script>";
    exit;
}

// Require eventId
if (!isset($_GET['eventId'])) {
    $session::set("msg1", "No event specified.");
    echo "<script>window.location.href='hall-seat-allocation-event-management.php';</script>";
    exit;
}

$eventId = $_GET['eventId'];

// Fetch statuses 2, 3, -4 (Accepted, Rejected, Not Appeared)
$allApplications = $hallSeatApplication
    ->getApplicationsByUserIdEventStatus(null, $eventId, [2, 3, -4]);

// Compute counts
$totalApplications = count($allApplications);
$totalAccepted     = 0;
$totalDeclined     = 0;
$totalNotAppeared  = 0;
foreach ($allApplications as $app) {
    $hallSeatApplication->setProperties($app);
    switch ($hallSeatApplication->status) {
        case 2:
            $totalAccepted++;
            break;
        case 3:
            $totalDeclined++;
            break;
        case -4:
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

    <title>MM HALL</title>

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
                    <h3 class="text-center my-4">Republish Viva Results</h3>


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

                            <div class="d-flex flex-column align-items-end mt-4">
                                <div class="alert alert-warning w-100 mb-2" role="alert">
                                    ⚠️ After you click “Finalize &amp; Re-Publish Results,” you may see changes in hall seat allocation result generation due to the changes in the viva results.
                                </div>
                                <form method="post" action="">
                                    <input type="hidden" name="event_id" value="<?php echo htmlspecialchars($eventId); ?>">
                                    <button
                                        type="submit"
                                        name="finalize"
                                        class="btn btn-lg btn-outline-danger">
                                        Finalize &amp; Re-Publish Results
                                    </button>
                                </form>
                            </div>

                        </div>
                    </div>


                    <!-- 1) Custom App/Student ID filter -->
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-search"></i></span>
                                <input
                                    type="text"
                                    id="searchAppOrStudent"
                                    class="form-control"
                                    placeholder="Filter by Application ID">
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
                                            <?php if ($status == -4): ?>
                                                Not appeared<br>
                                                <small class="text-muted">(will be rejected)</small>
                                            <?php elseif ($status == 2): ?>
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

                                                <?php if ($status == -4): ?>
                                                    <button name="approve" class="btn btn-sm btn-success me-1">Approve</button>
                                                    <button name="reject" class="btn btn-sm btn-danger">Reject</button>
                                                <?php elseif ($status == 2): ?>
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
            var table = $('#applicationsTable').DataTable({
                // keep default search box
                dom: 'lfrtip',
                paging: true,
                ordering: true,
                info: true,
                lengthMenu: [
                    [10, 25, 50, 100, 200],
                    [10, 25, 50, 100, 200]
                ],
                pageLength: 100,
                columnDefs: [{
                        type: 'num',
                        targets: 0
                    },
                    {
                        orderable: false,
                        targets: 5
                    }
                ]
            });

            // 1) Style the default search input: add margin and set width
            $('.dataTables_filter input')
                .attr('placeholder', 'Search by anything')
                .css({
                    width: '250px',
                    'margin-left': '1rem',
                    'margin-right': '1rem',
                    'margin-bottom': '1rem',
                    'margin-top': '0.5rem'
                });

            // 2) Numeric‐only App ID filter
            $.fn.dataTable.ext.search.push(function(settings, data) {
                if (settings.nTable.id !== 'applicationsTable') return true;

                var term = $('#searchAppOrStudent').val().trim();
                if (!term) return true; // no filter

                var termNum = parseInt(term, 10);
                var appIdNum = parseInt(data[0], 10);

                // only show exact numeric matches
                return !isNaN(termNum) && termNum === appIdNum;
            });

            // 3) Redraw on App/Student ID input change
            $('#searchAppOrStudent').on('keyup change clear', function() {
                table.draw();
            });
        });
    </script>
</body>

</html>