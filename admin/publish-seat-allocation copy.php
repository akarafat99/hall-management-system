<?php
include_once '../class-file/SessionManager.php';
$session = SessionStatic::class;

include_once '../class-file/HallSeatApplication.php';
include_once '../class-file/Department.php';
include_once '../class-file/HallSeatAllocationEvent.php';

include_once '../popup-1.php';
if ($session::get("msg1")) {
    showPopup($session::get("msg1"));
    $session::delete("msg1");
}

$eventId = 2;
if (isset($_POST['eventId']) || isset($_GET['eventId'])) {
    $eventId = $_POST['eventId'] ?? $_GET['eventId'];
}

if ($eventId == -1) {
    $session::set("msg1", "Please select an event to publish the seat allocation.");
    echo "<script>window.location.href = 'hall-seat-allocation-event-management.php';</script>";
    exit;
}

$department      = new Department();
$departmentList  = $department->getDepartments();
$yearSemesterCodes = $department->getYearSemesterCodes();

$hallSeatApp     = new HallSeatApplication();
$deptWise        = $hallSeatApp->getDeptWiseUserDetailsByEvent($eventId);

// 2) Build the flat metrics map
$allMetrics      = $hallSeatApp->mapApplicationMetrics($deptWise, $eventId);

// 3) Run the shortlisting algorithm
$shortlist       = $hallSeatApp->shortlisting($allMetrics, $eventId);

$deptWise = $shortlist['deptWise'];
$appIds  = $shortlist['appIds'];

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

    <!-- Bootstrap & FontAwesome -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" rel="stylesheet" />
    <!-- Sidebar CSS -->
    <link href="../css2/sidebar-admin.css" rel="stylesheet" />
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <?php include 'sidebar-admin.php'; ?>
            <main id="mainContent" class="col">
                <button
                    class="btn btn-dark d-lg-none mt-3 mb-3"
                    type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#sidebarMenu"
                    aria-controls="sidebarMenu"
                    aria-expanded="false"
                    aria-label="Toggle navigation">
                    ☰ Menu
                </button>

                <?php foreach ($deptWise as $deptId => $applications): ?>
                    <?php if (empty($applications)) continue; ?>
                    <div class="card mb-4">
                        <div class="card-header bg-light">
                            <strong>Department: <?php echo $deptId ?></strong>
                            <strong>Department: <?php echo $departmentList[$deptId-1]['department_name'] ?></strong>
                            &mdash; <?php echo count($applications); ?> seat(s)
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-sm table-striped table-bordered mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>App ID</th>
                                            <th>Semester</th>
                                            <th>Details ID</th>
                                            <th>Division</th>
                                            <th>District</th>
                                            <th>Distance</th>
                                            <th>Result</th>
                                            <th>Father's Monthly Income</th>
                                            <th>Score</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($applications as $appId): ?>
                                            <?php $m = $allMetrics[$appId]; ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($appId); ?></td>
                                                <td><?php echo $yearSemesterCodes[$m[1]] ?></td>
                                                <td><?php echo htmlspecialchars($m[2]); ?></td>
                                                <td><?php echo htmlspecialchars($m[3]); ?></td>
                                                <td><?php echo htmlspecialchars($m[4]); ?></td>
                                                <td><?php echo htmlspecialchars($m[5]); ?></td>
                                                <td><?php echo htmlspecialchars($m[6]); ?></td>
                                                <td><?php echo htmlspecialchars($m[7]); ?></td>
                                                <td><?php echo htmlspecialchars($m[8]); ?></td>
                                                <td>
                                                    <?php if (isset($appIds[$appId])): ?>
                                                        <span class="badge bg-success">Allotted</span>
                                                    <?php else: ?>
                                                        <span class="badge bg-secondary">Pending</span>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>



            </main>
        </div>
    </div>

    <!-- Bootstrap JS & Sortable.js -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>