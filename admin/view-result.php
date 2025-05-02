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
$hallSeatDetails          = new HallSeatDetails();
$hallSeatAllocationEvent  = new HallSeatAllocationEvent();
$hallSeatApplication      = new HallSeatApplication();
$department               = new Department();
$departmentList           = $department->getDepartments();
$user                     = new User();
$userDetails              = new UserDetails();

// Require eventId
if (!isset($_GET['eventId'])) {
    $session::set("msg1", "No event specified.");
    echo "<script>window.location.href='hall-seat-allocation-event-management.php';</script>";
    exit;
}
$eventId = intval($_GET['eventId']);

// status codes lookup
$statusLabels = [
    1  => 'Applied',
    2  => 'Passed & Verified',
    3  => 'Failed Viva',
    -4  => 'Absent in Viva',
    4  => 'Passed Viva (No Seat)',
    5  => 'Seat Allotted But Not Confirmed',
    6  => 'Seat Confirmed'
];

// Fetch all statuses
$allApplications = $hallSeatApplication
    ->getApplicationsByUserIdEventStatus(
        null,
        $eventId,
        [1, 2, 3, -4, 4, 5, 6]  // <-- all codes
    );

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>MM HALL </title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <!-- DataTables CSS -->
    <link href="https://cdn.datatables.net/1.13.5/css/dataTables.bootstrap5.min.css" rel="stylesheet" />
    <!-- Custom styles -->
    <link href="../css2/sidebar-admin.css" rel="stylesheet" />
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <?php include 'sidebar-admin.php'; ?>

            <main id="mainContent" class="col">
                <!-- Mobile sidebar toggle -->
                <button class="btn btn-dark d-lg-none mt-3 mb-3"
                    data-bs-toggle="collapse" data-bs-target="#sidebarMenu">
                    ☰ Menu
                </button>

                <!-- Back link -->
                <div class="mb-4 mt-4">
                    <a href="hall-seat-allocation-event-dashboard.php?eventId=<?php echo $eventId; ?>"
                        class="btn btn-outline-secondary">← Back</a>
                </div>

                <div class="px-4">
                    <h3 class="text-center my-4">Event Result</h3>

                    <!-- Status cards -->
                    <?php
                    // Only these statuses in the overview:
                    $summaryStatuses = [
                        3  => 'Failed Viva',
                        -4  => 'Missed Viva',
                        4  => 'Passed Viva (No Seat)',   // ← updated
                        5  => 'Seat Allotted But Not Confirmed',
                        6  => 'Seat Confirmed'
                    ];

                    // Count each status
                    $counts = [];
                    foreach ($allApplications as $app) {
                        $counts[$app['status']] = ($counts[$app['status']] ?? 0) + 1;
                    }
                    ?>
                    <div class="row gx-3 gy-4 mb-4">
  <?php foreach ($summaryStatuses as $code => $label):
    $count  = $counts[$code] ?? 0;

    // pick a border color based on status code
    switch ($code) {
      case 3:    // Failed Viva
      case -4:   // Missed Viva
        $border = 'border-danger';
        break;
      case 4:    // Passed Viva (No Seat)
        $border = 'border-warning';
        break;
      case 5:    // Seat Allotted
        $border = 'border-info';
        break;
      case 6:    // Seat Confirmed
        $border = 'border-success';
        break;
      default:
        $border = 'border-secondary';
    }
  ?>
    <div class="col-sm-6 col-md-4 col-lg-2">
      <div class="card h-100 <?php echo $border; ?>">
        <div class="card-body text-center">
          <h6 class="card-title"><?php echo htmlspecialchars($label); ?></h6>
          <p class="card-text display-6"><?php echo $count; ?></p>
        </div>
      </div>
    </div>
  <?php endforeach; ?>
</div>


                    <!-- Applications table -->
                    <div class="table-responsive mb-4">
                        <table id="applicationsTable"
                            class="table table-striped table-bordered"
                            style="width:100%">
                            <thead class="table-light">
                                <tr>
                                    <th>Application ID</th>
                                    <th>Name</th>
                                    <th>Student ID</th>
                                    <th>Allotted Seat</th>
                                    <th>Status</th>
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

                                    if($hallSeatApplication->allotted_seat_id >0) {
                                    $hallSeatDetails->loadBySeatId($hallSeatApplication->allotted_seat_id);
                                    }
                                ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($id); ?></td>
                                        <td><?php echo htmlspecialchars($userDetails->full_name); ?></td>
                                        <td><?php echo htmlspecialchars($userDetails->student_id); ?></td>
                                        <td>
                                            <?php if ($hallSeatApplication->allotted_seat_id > 0): ?>
                                            <p class="mb-1"><strong>Floor:</strong>
                                                <?php echo htmlspecialchars($hallSeatDetails->floor_no); ?></p>
                                            <p class="mb-1"><strong>Room:</strong>
                                                <?php echo htmlspecialchars($hallSeatDetails->room_no); ?></p>
                                            <p class="mb-0"><strong>Seat No:</strong>
                                                <?php echo htmlspecialchars($hallSeatDetails->seat_id); ?></p>
                                            <?php else: ?>
                                            <p class="mb-0">Not Allotted</p>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <span class="badge 
                      <?php
                                    switch ($status) {
                                        case 2:
                                        case 6:
                                            echo 'bg-success';
                                            break;
                                        case 3:
                                        case -4:
                                            echo 'bg-danger';
                                            break;
                                        case 5:
                                            echo 'bg-info';
                                            break;
                                        default:
                                            echo 'bg-warning';
                                    }
                        ?>">
                                                <?php echo htmlspecialchars($statusLabels[$status] ?? 'Unknown'); ?>
                                            </span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- jQuery, Bootstrap & DataTables JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.5/js/dataTables.bootstrap5.min.js"></script>
    <script>
        $(function() {
            $('#applicationsTable').DataTable({
                paging: true,
                ordering: true,
                info: true,
                searching: true,
                order: [
                    [0, 'asc']
                ],
                columnDefs: [{
                    type: 'num',
                    targets: 0
                }]
            });
        });
    </script>
</body>

</html>