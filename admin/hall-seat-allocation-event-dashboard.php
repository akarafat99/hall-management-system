<?php
include_once '../class-file/HallSeatAllocationEvent.php';
$event = new HallSeatAllocationEvent();

if (isset($_GET['eventId'])) {
  $eventId = $_GET['eventId'];
  $event->event_id = $eventId;
  $event->load();
}

// Map the status value to corresponding text and badge classes.
switch ($event->status) {
  case -1:
    $statusText = "Deleted";
    $statusBadgeClass = "bg-danger";
    break;
  case 0:
    $statusText = "Only Created";
    $statusBadgeClass = "bg-warning text-dark";
    break;
  case 1:
    $statusText = "Stage 1 Done (Application Collect + Viva Notice Date Set)";
    $statusBadgeClass = "bg-info";
    break;
  case 2:
    $statusText = "Stage 2 Done (Viva Date Generated & Result Set)";
    $statusBadgeClass = "bg-primary";
    break;
  case 3:
    $statusText = "Stage 3 Done (Completed Seat Allotment from Hall Auth Side)";
    $statusBadgeClass = "bg-success";
    break;
  default:
    $statusText = "Unknown Status";
    $statusBadgeClass = "bg-secondary";
    break;
}

// Determine which card corresponding to the event status will be expanded.
$phase2Expanded = ($event->status == 1 || $event->status == 2) ? "show" : "";
$phase3Expanded = ($event->status == 3) ? "show" : "";
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <title>Event Management - Dashboard</title>

  <!-- CSS Libraries -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
    crossorigin="anonymous" referrerpolicy="no-referrer" />
  <link href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css" rel="stylesheet" />
  <link href="../css/Dashboard/dashboard.css" rel="stylesheet" />

  <style>
    /* Card header styling */
    .card-header {
      background-color: #f8f9fa;
      padding: 15px;
      cursor: pointer;
    }

    .card-header h5,
    .card-header strong {
      margin: 0;
      font-size: 1.1rem;
    }

    .card-body {
      background: #fff;
      padding: 20px;
    }
    
    /* Optional: rotate the chevron icon when shown */
    .collapse.show ~ .card-header .fa-chevron-down {
      transform: rotate(180deg);
    }
  </style>
</head>

<body class="sb-nav-fixed">
  <div id="layoutSidenav">
    <?php include 'admin-sidebar.php'; ?>
    <div id="layoutSidenav_content">
      <main>
        <div class="container-fluid px-4">
          <div class="mb-4">
            <h3 class="mb-3">Hall Seat Allocation Event Dashboard</h3>
          </div>

          <!-- Card 1: Event Information -->
          <!-- This card is expanded by default -->
          <div class="card mb-4">
            <div class="card-header" data-bs-toggle="collapse" data-bs-target="#eventInfoCollapse" aria-expanded="true" aria-controls="eventInfoCollapse">
              <strong>Event Information</strong>
              <span class="float-end"><i class="fas fa-chevron-down"></i></span>
            </div>
            <div id="eventInfoCollapse" class="collapse show">
              <div class="card-body">
                <p><strong>Event ID:</strong> <?php echo $event->event_id; ?></p>
                <p><strong>Title:</strong> <?php echo htmlspecialchars($event->title); ?></p>
                <p><strong>Description:</strong> <?php echo htmlspecialchars($event->details); ?></p>
                <!-- Display the status badge -->
                <p><strong>Status:</strong> <span class="badge <?php echo $statusBadgeClass; ?>"><?php echo $statusText; ?></span></p>
              </div>
            </div>
          </div>

          <!-- Card 2: Phase 1 - Event Dates, Priority List, and Seat Quota -->
          <!-- This card is minimized (collapsed) by default -->
          <div class="card mb-4">
            <div class="card-header" data-bs-toggle="collapse" data-bs-target="#phase1Collapse" aria-expanded="false" aria-controls="phase1Collapse">
              <strong>Phase 1: Event Dates and Details</strong>
              <span class="float-end"><i class="fas fa-chevron-down"></i></span>
            </div>
            <div id="phase1Collapse" class="collapse">
              <div class="card-body">
                <!-- Basic Dates -->
                <p><strong>Application Start Date:</strong> <?php echo htmlspecialchars($event->application_start_date); ?></p>
                <p><strong>Application End Date:</strong> <?php echo htmlspecialchars($event->application_end_date); ?></p>
                <p><strong>Viva Notice Date:</strong> <?php echo htmlspecialchars($event->viva_notice_date); ?></p>
                <p><strong>Created:</strong> <?php echo htmlspecialchars($event->created); ?></p>
                <p><strong>Modified:</strong> <?php echo htmlspecialchars($event->modified); ?></p>
                
                <!-- Priority List and Seat Distribution Quota (moved inside Phase 1) -->
                <?php include_once '../class-file/PriorityList.php'; ?>
                <div class="mb-4">
                  <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white" data-bs-toggle="collapse" data-bs-target="#priorityCollapse" aria-expanded="false" aria-controls="priorityCollapse">
                      <strong>Priority List <small class="text-light">(Lower level means higher priority)</small></strong>
                      <span class="float-end"><i class="fas fa-chevron-down"></i></span>
                    </div>
                    <div id="priorityCollapse" class="collapse">
                      <div class="card-body">
                        <div class="row mb-3 fw-bold">
                          <div class="col-6">Priority Level</div>
                          <div class="col-6">Priority Name</div>
                        </div>
                        <?php
                        $priorityString = $event->priority_list;
                        $priorityArray = array_map('trim', explode(",", $priorityString));
                        $priorityMapping = getPriorityList();
                        ?>
                        <?php foreach ($priorityArray as $idx => $priority):
                          $displayText = isset($priorityMapping[$priority]) ? $priorityMapping[$priority] : ucfirst($priority);
                        ?>
                          <div class="row align-items-center mb-2">
                            <div class="col-6">
                              <div class="p-2 bg-light rounded text-center">
                                <?php echo ($idx + 1); ?>
                              </div>
                            </div>
                            <div class="col-6">
                              <div class="p-2 bg-light rounded text-center">
                                <?php echo htmlspecialchars($displayText); ?>
                              </div>
                            </div>
                          </div>
                        <?php endforeach; ?>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="mb-4">
                  <p class="event-details-label mb-1"><strong>Seat Distribution Quota:</strong></p>
                  <?php
                  $quotaArray = explode(",", $event->seat_distribution_quota);
                  $quotaLabels = array(
                    "B.Sc. First Year First Semester",
                    "B.Sc. First Year Second Semester",
                    "B.Sc. Second Year First Semester",
                    "B.Sc. Second Year Second Semester",
                    "B.Sc. Third Year First Semester",
                    "B.Sc. Third Year Second Semester",
                    "B.Sc. Fourth Year First Semester",
                    "B.Sc. Fourth Year Second Semester",
                    "M.Sc. First Year First Semester",
                    "M.Sc. First Year Second Semester",
                    "M.Sc. Second Year First Semester",
                    "M.Sc. Second Year Second Semester"
                  );
                  ?>
                  <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                      <thead class="table-light">
                        <tr>
                          <th>Program &amp; Semester</th>
                          <th>Quota</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php foreach ($quotaArray as $idx => $quota): ?>
                          <tr>
                            <td><?php echo $quotaLabels[$idx]; ?></td>
                            <td>
                              <span class="badge bg-success"><?php echo htmlspecialchars(trim($quota)); ?></span>
                            </td>
                          </tr>
                        <?php endforeach; ?>
                      </tbody>
                    </table>
                  </div>
                </div>

              </div>
            </div>
          </div>

          <!-- Card 3: Phase 2 - Viva and Result Publication -->
          <!-- This card is expanded by default only when the event status is 1 or 2; otherwise it remains collapsed -->
          <div class="card mb-4">
            <div class="card-header" data-bs-toggle="collapse" data-bs-target="#phase2Collapse" aria-expanded="<?php echo ($phase2Expanded ? 'true' : 'false'); ?>" aria-controls="phase2Collapse">
              <strong>Phase 2: Viva and Result Publication</strong>
              <span class="float-end"><i class="fas fa-chevron-down"></i></span>
            </div>
            <div id="phase2Collapse" class="collapse <?php echo $phase2Expanded; ?>">
              <div class="card-body">
                <?php if ($event->status == 1): ?>
                  <!-- For status 1: Generate viva date and publish result date/notice -->
                  <a href="viva-generation.php?eventId=<?php echo $event->event_id; ?>" class="btn btn-primary mb-2">
                    Generate Viva Date &amp; Publish Result Date/Notice
                  </a>
                <?php elseif ($event->status == 2): ?>
                  <!-- For status 2: Take viva and show extra details -->
                  <a href="take-viva.php?eventId=<?php echo $event->event_id; ?>" class="btn btn-primary mb-2">
                    Take the Viva of Students
                  </a>
                  <hr>
                  <p><strong>Seat Allotment Result Notice Date:</strong> <?php echo htmlspecialchars($event->seat_allotment_result_notice_date); ?></p>
                  <p><strong>Seat Allotment Result Notice Details:</strong> <?php echo htmlspecialchars($event->seat_allotment_result_notice_text); ?></p>
                  <div class="mt-3">
                    <a href="viva-date-list.php?eventId=<?php echo $event->event_id; ?>" class="btn btn-info mr-2 mb-2">
                      See the Viva Date List
                    </a>
                    <a href="viva-result-edit.php?eventId=<?php echo $event->event_id; ?>" class="btn btn-danger mb-2">
                      Review &amp; Finalize &amp; Publish Viva Result
                    </a>
                  </div>
                <?php else: ?>
                  <p>No Phase 2 actions available for the current status.</p>
                <?php endif; ?>
              </div>
            </div>
          </div>

          <!-- Card 4: Phase 3 - Result Generation (Rendered only if event status is 3) -->
          <div class="card mb-4">
            <div class="card-header" data-bs-toggle="collapse" data-bs-target="#phase3Collapse" aria-expanded="<?php echo ($phase3Expanded ? 'true' : 'false'); ?>" aria-controls="phase3Collapse">
              <strong>Phase 3: Result Generation</strong>
              <span class="float-end"><i class="fas fa-chevron-down"></i></span>
            </div>
            <div id="phase3Collapse" class="collapse <?php echo $phase3Expanded; ?>">
              <div class="card-body">
                <?php if ($event->status == 3): ?>
                <a href="generate-result.php?eventId=<?php echo $event->event_id; ?>" class="btn btn-success">
                  Generate Result and Publish
                </a>
                <?php else: ?>
                  <p>Completion of Phase 2 actions are required before proceeding to Phase 3.</p>
                <?php endif; ?>
              </div>
            </div>
          </div>

        </div>
      </main>
      <footer class="py-4 dashboard-copyright-footer mt-auto">
        <div class="container-fluid px-4">
          <div class="d-flex align-items-center justify-content-between small">
            <div class="text-muted">&copy; Just 2024</div>
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

  <!-- JavaScript Libraries -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
  <script src="script.js"></script>
  <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
</body>
</html>
