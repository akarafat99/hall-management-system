<?php
include_once '../class-file/SessionManager.php';
$session = SessionStatic::class;
include_once '../class-file/Auth.php';
auth('admin'); 

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

if (isset($_POST['publishResult'])) {
    $eventId = $_POST['eventId'] ?? $_GET['eventId'];
    $event = new HallSeatAllocationEvent();
    $event->event_id = $eventId;
    $event->load();

    $hallSeatApp = new HallSeatApplication();
    // 1) Get Raw Data
    $deptWise        = $hallSeatApp->getDeptWiseUserDetailsByEvent($eventId);
    // 2) Build the flat metrics map
    $allApplication      = $hallSeatApp->mapApplicationMetrics($deptWise, $eventId);
    // 3) Run the shortlisting algorithm
    $shortlist       = $hallSeatApp->shortlisting($allApplication, $eventId);
    $deptWise = $shortlist['deptWise'];
    $appIds  = $shortlist['appIds'];

    // Debug 1 Start
    // $submittedMap = $_POST['appIds'] ?? [];
    // foreach ($submittedMap as $appId => $seatId) {
    //     echo "App ID: $appId with seat ID: $seatId<br>";
    // }
    // echo "<br>";
    // Debug 1 End

    // 1) build the map
    $publishMap = $hallSeatApp->buildPublishMap($allApplication, $_POST['appIds'] ?? []);
    // foreach ($publishMap as $appId => $data) {
    //     echo "App ID: $appId with seat ID: {$data['allotted_seat_id']} , {$data['status']}, {$data['user_id']}<br>";
    // }

    // exit;

    // 2) bulk‐persist it
    $res = $hallSeatApp->bulkUpdateApplications($publishMap);

    if ($res == true) {
        $event->updateStatus($eventId, 4);
        $session::set("msg1", "Seat allocation result published successfully.");
        echo "<script>window.location.href = 'hall-seat-allocation-event-dashboard.php?eventId=$eventId';</script>";
        exit;
    } else {
        $session::set("msg1", "Failed to publish the seat allocation result. Please try again.");
        echo "<script>window.location.href = 'hall-seat-allocation-event-dashboard.php?eventId=$eventId';</script>";
        exit;
    }
}


// Initially load all the data
$event = new HallSeatAllocationEvent();
$event->event_id = $eventId;
$event->load();

$department      = new Department();
$departmentList  = $department->getDepartments();
$yearSemesterCodes = $department->getYearSemesterCodes();

$hallSeatApp     = new HallSeatApplication();
$deptWise        = $hallSeatApp->getDeptWiseUserDetailsByEvent($eventId);

// 2) Build the flat metrics map
$allApplication      = $hallSeatApp->mapApplicationMetrics($deptWise, $eventId);

// 3) Run the shortlisting algorithm
$shortlist       = $hallSeatApp->shortlisting($allApplication, $eventId);

$deptWise = $shortlist['deptWise'];
$appIds  = $shortlist['appIds'];


?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>All Applications</title>

    <!-- Bootstrap 5 CSS & DataTables CSS -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css"
        rel="stylesheet" />
    <link
        href="https://cdn.datatables.net/1.13.5/css/dataTables.bootstrap5.min.css"
        rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
        rel="stylesheet" />
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

                <!-- Title -->
                <div class="p-4">
                    <h1>Publish Result</h1>
                    <p class="text-muted">
                        <i class="fas fa-info-circle me-1"></i>
                        You are about to publish the seat allocation result for the event <strong><?php echo htmlspecialchars($event->title); ?></strong>.
                    </p>
                </div>

                <!-- TODO 1: Publish form with warning and forward $appIds -->
                <div class="m-4 p-4 border rounded shadow-sm">

                    <!-- Warning alert -->
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Warning:</strong> Once you publish the results, this action <u>cannot</u> be undone.
                    </div>

                    <form method="post" action="">
                        <input type="hidden" name="eventId" value="<?php echo htmlspecialchars($eventId); ?>">

                        <!-- forward each application => seat ID -->
                        <?php foreach ($appIds as $applicationId => $seatId): ?>
                            <input
                                type="hidden"
                                name="appIds[<?php echo htmlspecialchars($applicationId); ?>]"
                                value="<?php echo htmlspecialchars($seatId); ?>">
                        <?php endforeach; ?>

                        <div class="mb-3">
                            <label class="form-label">Result Publication Date</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-calendar-check"></i></span>
                                <input
                                    type="text"
                                    readonly
                                    class="form-control"
                                    value="<?php echo htmlspecialchars($event->seat_allotment_result_notice_date); ?>">
                            </div>
                            <small class="text-muted">
                                <i class="fas fa-info-circle me-1"></i>
                                Admin must publish the result on this date.
                            </small>
                        </div>

                        <div class="text-end">
                            <button
                                type="submit"
                                name="publishResult"
                                class="btn btn-danger">
                                <i class="fas fa-flag-checkered me-1"></i>
                                Publish Result
                            </button>
                        </div>
                    </form>
                </div>




                <!-- Seat Allocation Overview Department-wise -->
                <?php
                // parse the seat_distribution_quota into deptId => quota
                $deptQuota = [];
                foreach (explode(',', $event->seat_distribution_quota) as $pair) {
                    list($d, $cnt) = explode('=>', $pair);
                    $deptQuota[(int)$d] = (int)$cnt;
                }
                ?>
                <div class="m-4">
                    <h5>Department Allocation Overview</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Department</th>
                                    <th class="text-end">Quota</th>
                                    <th class="text-end">Allotted</th>
                                    <th class="text-end">Available</th>
                                    <th class="text-end">% Allotted</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($deptQuota as $did => $quota):
                                    // compute allocated and available
                                    $allocated = isset($deptWise[$did]) ? count($deptWise[$did]) : 0;
                                    $available = $quota - $allocated;
                                    $percent   = $quota ? round($allocated / $quota * 100) : 0;
                                    $dname     = htmlspecialchars($departmentList[$did - 1]['department_name'] ?? $did);
                                ?>
                                    <tr>
                                        <td><?php echo $dname; ?></td>
                                        <td class="text-end"><?php echo $quota; ?></td>
                                        <td class="text-end text-success"><?php echo $allocated; ?></td>
                                        <td class="text-end text-secondary"><?php echo $available; ?></td>
                                        <td class="text-end"><?php echo $percent; ?>%</td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>


                <!-- All Applications Table -->
                <div class="card mb-4">
                    <div class="card-header bg-light">
                        <strong>All applications which have status passed in viva</strong>
                    </div>
                    <div class="card-body">

                        <!-- Application ID filter -->
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                                    <input
                                        type="number"
                                        id="searchAppID"
                                        class="form-control"
                                        placeholder="Search by Application ID"
                                        min="0">
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive">
  <table
    id="applicationsTable"
    class="table table-striped table-bordered"
    style="width:100%">
    <thead class="table-light">
      <tr>
        <th>App ID</th>
        <th>Department</th>
        <th>Semester</th>
        <th>Profile</th>
        <th>Division</th>
        <th>District</th>
        <th>Distance</th>
        <th>Result</th>
        <th>Income</th>
        <th>Score</th>
        <th>Status</th>
        <th>Allotted Seat ID</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($allApplication as $appId => $m): 
        // unpack metrics
        $deptId     = $m[0];
        $semCode    = $m[1];
        $detailsId  = $m[3];
        $division   = $m[4];
        $district   = $m[5];
        $distance   = $m[6];
        $result     = $m[7];
        $income     = $m[8];
        $score      = $m[9];
        $isAllotted = isset($appIds[$appId]);
        $seatId     = $isAllotted ? $appIds[$appId] : null;
      ?>
      <tr>
        <td><?php echo htmlspecialchars($appId); ?></td>
        <td><?php echo htmlspecialchars($departmentList[$deptId-1]['department_name']); ?></td>
        <td><?php echo htmlspecialchars($yearSemesterCodes[$semCode]); ?></td>
        <td>
          <a
            href="view-profile.php?userDetailsId=<?php echo htmlspecialchars($detailsId); ?>"
            class="btn btn-sm btn-outline-info">
            <i class="fas fa-user"></i> View
          </a>
        </td>
        <td><?php echo htmlspecialchars($division); ?></td>
        <td><?php echo htmlspecialchars($district); ?></td>
        <td><?php echo htmlspecialchars($distance); ?></td>
        <td><?php echo htmlspecialchars($result); ?></td>
        <td><?php echo htmlspecialchars($income); ?></td>
        <td><?php echo htmlspecialchars($score); ?></td>
        <td>
          <?php if ($isAllotted): ?>
            <span class="badge bg-success">Allotted</span>
          <?php else: ?>
            <span class="badge bg-secondary">Not Allotted</span>
          <?php endif; ?>
        </td>
        <td>
          <?php if ($isAllotted): ?>
            <span class="badge bg-primary"><?php echo htmlspecialchars($seatId); ?></span>
          <?php else: ?>
            <span class="text-muted">—</span>
          <?php endif; ?>
        </td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>

                    </div>
                </div>

            </main>
        </div>
    </div>

    <!-- jQuery, Bootstrap & DataTables JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js">
    </script>
    <script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.5/js/dataTables.bootstrap5.min.js"></script>

    <script>
        $(document).ready(function() {
            var table = $('#applicationsTable').DataTable({
                dom: 'lrtip', // hide the default search box
                paging: true,
                ordering: true,
                info: true,
                order: [
                    [0, 'asc']
                ], // default sort by App ID
                columnDefs: [{
                    type: 'num',
                    targets: 0
                }, {
                    orderable: false,
                    targets: 3 // disable sorting on Profile column
                }]
            });

            // Custom numeric filter on column 0 (App ID)
            $('#searchAppID').on('input', function() {
                table
                    .column(0)
                    .search(this.value || '', false, false)
                    .draw();
            });
        });
    </script>
</body>

</html>