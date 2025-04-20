<?php
// include_once '../class-file/HallSeatAllocationEvent.php';
include_once '../class-file/HallSeatDetails.php';
include_once '../class-file/PriorityList.php';
include_once '../class-file/Department.php';

// Fetch priority criteria
$priorityMapping     = getPriorityList();
// Load active departments
$department          = new Department();
$departmentList      = $department->getDepartments(null, 1);
// Load seat‑detail helper
$hallSeatDetails     = new HallSeatDetails();
$totalAvailableSeats = $hallSeatDetails->countSeatsByStatus(0);

// $hallSeatAllocationEvent = new HallSeatAllocationEvent();
//$deptDistribution = $hallSeatAllocationEvent->distributeSeatsByDepartmentRatio();

// Semester labels
$labels = [
    'bsc11' => 'B. Sc. 1st Year 1st Sem',
    'bsc12' => 'B. Sc. 1st Year 2nd Sem',
    'bsc21' => 'B. Sc. 2nd Year 1st Sem',
    'bsc22' => 'B. Sc. 2nd Year 2nd Sem',
    'bsc31' => 'B. Sc. 3rd Year 1st Sem',
    'bsc32' => 'B. Sc. 3rd Year 2nd Sem',
    'bsc41' => 'B. Sc. 4th Year 1st Sem',
    'bsc42' => 'B. Sc. 4th Year 2nd Sem',
    'msc11' => 'M. Sc. 1st Year 1st Sem',
    'msc12' => 'M. Sc. 1st Year 2nd Sem',
    'msc21' => 'M. Sc. 2nd Year 1st Sem',
    'msc22' => 'M. Sc. 2nd Year 2nd Sem'
];

// Prepare empty quotas (for create vs. edit)
$quotaValuesByDept = [];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Create Hall Seat Allocation Event</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" rel="stylesheet" />
    <!-- Sidebar CSS -->
    <link href="../css2/sidebar-admin.css" rel="stylesheet" />
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <?php include 'sidebar-admin.php'; ?>

            <main id="mainContent" class="col">
                <!-- Toggle button for small screens -->
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

                <div class="container-fluid px-4">
                    <!-- Page Header -->
                    <h3 class="mt-4 mb-4">Create Hall Seat Allocation Event</h3>

                    <!-- Form Card -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-calendar-alt me-2"></i>Event Information
                        </div>
                        <div class="card-body">
                            <form method="post" class="needs-validation" novalidate>

                                <!-- Event Title (full width) -->
                                <div class="mb-3">
                                    <label for="eventTitle" class="form-label">Event Title</label>
                                    <input type="text"
                                        class="form-control"
                                        id="eventTitle"
                                        name="eventTitle"
                                        placeholder="Enter event title"
                                        required>
                                    <div class="invalid-feedback">Please enter an event title.</div>
                                </div>

                                <!-- Event Details (full width) -->
                                <div class="mb-4">
                                    <label for="eventdetails" class="form-label">Event Details</label>
                                    <textarea class="form-control"
                                        id="eventdetails"
                                        name="eventdetails"
                                        rows="3"
                                        placeholder="Enter event details"
                                        required></textarea>
                                    <div class="invalid-feedback">Please provide event details.</div>
                                </div>

                                <!-- Dates -->
                                <div class="row g-3 mb-4">
                                    <?php
                                    $dates = [
                                        ['startDate', 'Start Date', 'fa-calendar-day'],
                                        ['endDate', 'End Date', 'fa-calendar-check'],
                                        ['vivaNoticeDate', 'Viva Notice Date', 'fa-bell']
                                    ];
                                    foreach ($dates as $d):
                                    ?>
                                        <div class="col-md-4">
                                            <label for="<?php echo $d[0]; ?>" class="form-label"><?php echo $d[1]; ?></label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="fas <?php echo $d[2]; ?>"></i></span>
                                                <input type="date"
                                                    class="form-control"
                                                    id="<?php echo $d[0]; ?>"
                                                    name="<?php echo $d[0]; ?>"
                                                    required>
                                            </div>
                                            <div class="invalid-feedback">Select a <?php echo strtolower($d[1]); ?>.</div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>

                                <!-- Priority List -->
                                <div class="mb-4">
                                    <label class="form-label fw-semibold">Priority List</label>
                                    <div class="form-text mb-1">
                                        Press and hold the <i class="fas fa-grip-lines"></i> icon to drag items into your preferred order.
                                    </div>
                                    <div class="form-text mb-3 text-muted">
                                        Priority decreases from top to bottom.
                                    </div>
                                    <ul id="priorityList" class="list-group mb-2">
                                        <?php foreach ($priorityMapping as $key => $txt): ?>
                                            <li class="list-group-item d-flex align-items-center" data-value="<?php echo $key; ?>">
                                                <i class="fas fa-grip-lines me-3 text-muted"></i>
                                                <?php echo htmlspecialchars($txt); ?>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                    <input type="hidden" id="priorityListInput" name="priorityList">
                                </div>


                                <!-- Seat Quota by Department -->
                                <div class="accordion mb-4" id="departmentAccordion">
                                    <label class="form-label fw-semibold">Seat Quota for Departments</label>
                                    <div class="form-text mb-3 text-muted">
                                        Enter the number of seats to be allocated for each department.
                                    <?php foreach ($departmentList as $dept): ?>
                                        <div class="accordion-item">
                                            <h2 class="accordion-header" id="headingDept<?php echo $dept['department_id']; ?>">
                                                <button class="accordion-button collapsed"
                                                    type="button"
                                                    data-bs-toggle="collapse"
                                                    data-bs-target="#collapseDept<?php echo $dept['department_id']; ?>"
                                                    aria-expanded="false"
                                                    aria-controls="collapseDept<?php echo $dept['department_id']; ?>">
                                                    #<?php echo $dept['department_id']; ?> – <?php echo htmlspecialchars($dept['department_name']); ?>
                                                </button>
                                            </h2>
                                            <div id="collapseDept<?php echo $dept['department_id']; ?>"
                                                class="accordion-collapse collapse"
                                                aria-labelledby="headingDept<?php echo $dept['department_id']; ?>"
                                                data-bs-parent="#departmentAccordion">
                                                <div class="accordion-body">
                                                    <h6 class="fw-semibold mb-3">
                                                        Seat Quota for <?php echo htmlspecialchars($dept['department_name']); ?>
                                                    </h6>
                                                    <div class="row g-3">
                                                        <?php foreach ($labels as $field => $label): ?>
                                                            <div class="col-md-6">
                                                                <label for="quota_<?php echo $dept['department_id'] . '_' . $field; ?>" class="form-label">
                                                                    <?php echo $label; ?>
                                                                </label>
                                                                <input type="number"
                                                                    class="form-control"
                                                                    id="quota_<?php echo $dept['department_id'] . '_' . $field; ?>"
                                                                    name="quota[<?php echo $dept['department_id']; ?>][<?php echo $field; ?>]"
                                                                    required>
                                                                <div class="invalid-feedback">Enter a valid number.</div>
                                                            </div>
                                                        <?php endforeach; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>

                                <!-- Submit Button Only -->
                                <div class="text-end">
                                    <button type="submit" class="btn btn-primary">
                                        Create Event
                                    </button>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Sortable.js for priority drag/drop -->
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
    <script>
  // Set min date on startDate, endDate, vivaNoticeDate to today in Asia/Dhaka
  (function() {
    const dhakaString = new Date().toLocaleString("en-US", { timeZone: "Asia/Dhaka" });
    const dhakaDate   = new Date(dhakaString);

    const yyyy = dhakaDate.getFullYear();
    const mm   = String(dhakaDate.getMonth() + 1).padStart(2, "0");
    const dd   = String(dhakaDate.getDate()).padStart(2, "0");
    const minDate = `${yyyy}-${mm}-${dd}`;

    ["startDate", "endDate", "vivaNoticeDate"].forEach(id => {
      const el = document.getElementById(id);
      if (el) el.min = minDate;
    });
  })();

  // Bootstrap validation
  (function() {
    'use strict';
    const forms = document.querySelectorAll('.needs-validation');
    Array.from(forms).forEach(form => {
      form.addEventListener('submit', event => {
        if (!form.checkValidity()) {
          event.preventDefault();
          event.stopPropagation();
        }
        form.classList.add('was-validated');
      }, false);
    });
  })();

  // Initialize Sortable on priorityList
  new Sortable(document.getElementById('priorityList'), {
    animation: 150,
    handle: '.fa-grip-lines',
    onSort: () => {
      const vals = Array.from(document.querySelectorAll('#priorityList li'))
        .map(li => li.dataset.value)
        .join(',');
      document.getElementById('priorityListInput').value = vals;
    }
  });
</script>

</body>

</html>