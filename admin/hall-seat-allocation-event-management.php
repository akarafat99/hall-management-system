<?php
include_once '../class-file/HallSeatAllocationEvent.php';
$hallSeatAllocationEvent = new HallSeatAllocationEvent();
// Get events with status 0 (Ongoing) or 1 (Completed)
$eventList = $hallSeatAllocationEvent->getByEventAndStatus(null, [0, 1]);
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
    /* General card styling for event details */
    .event-card {
      border: none;
      border-radius: 10px;
      margin-bottom: 20px;
      overflow: hidden;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .event-card .card-header {
      background-color: #f8f9fa;
      padding: 15px;
      cursor: pointer;
    }

    .event-card .card-header h5 {
      margin: 0;
      font-size: 1.1rem;
    }

    .event-card .card-header .badge {
      font-size: 0.9rem;
      margin-right: 5px;
    }

    .event-card .card-body {
      background: #fff;
      padding: 20px;
    }

    .event-details-label {
      font-weight: 600;
      color: #333;
    }

    .event-details-value {
      color: #555;
    }

    /* Priority badges */
    .priority-badge {
      margin-right: 5px;
      font-size: 0.85rem;
    }

    /* Seat quota list styling */
    .quota-list .list-group-item {
      border: none;
      padding: 5px 0;
      font-size: 0.9rem;
    }

    /* Search and filter styling */
    .filter-group input,
    .filter-group select {
      border-radius: 5px;
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
            <h3 class="mb-3">Hall Seat Allocation Event Management</h3>
            <!-- Search Bar and Status Filter -->
            <div class="row filter-group mb-4">
              <div class="col-md-4">
                <input type="text" id="searchInput" class="form-control" placeholder="Search by Event ID">
              </div>
              <div class="col-md-4">
                <select id="statusFilter" class="form-control">
                  <option value="">All Status</option>
                  <option value="0">Ongoing</option>
                  <option value="1">Completed</option>
                </select>
              </div>
            </div>
          </div>

          <div id="eventAccordion">
            <?php if ($eventList && is_array($eventList)): ?>
              <?php foreach ($eventList as $index => $event): ?>
                <?php
                $statusText = ($event['status'] == 0) ? 'Ongoing' : 'Completed';
                $statusBadgeClass = ($event['status'] == 0) ? 'bg-warning text-dark' : 'bg-success';
                ?>
                <div class="card event-card" data-status="<?php echo htmlspecialchars($event['status']); ?>">
                  <div class="card-header" id="heading<?php echo $index; ?>">
                    <div class="row align-items-center">
                      <div class="col-md-2">
                        <h5>Event ID: <?php echo htmlspecialchars($event['event_id']); ?></h5>
                      </div>
                      <div class="col-md-3">
                        <small class="text-muted">Created on: <?php echo htmlspecialchars($event['created']); ?></small>
                      </div>
                      <div class="col-md-2">
                        <span class="badge <?php echo $statusBadgeClass; ?>"><?php echo $statusText; ?></span>
                      </div>
                      <div class="col-md-3 text-end">
                        <a href="hall-seat-allocation-event-dashboard.php?eventId=<?php echo $event['event_id']; ?>" class="btn btn-sm btn-primary me-1">Manage</a>
                        <?php if ($event['status'] < 3): ?>
                          <a href="create-hall-seat-allocation-event.php?editEvent=<?php echo $event['event_id']; ?>" class="btn btn-sm btn-success">Edit</a>
                        <?php endif; ?>
                      </div>
                      <div class="col-md-2 text-end">
                        <button class="btn btn-sm btn-outline-primary" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?php echo $index; ?>" aria-expanded="false" aria-controls="collapse<?php echo $index; ?>">
                          View Details <i class="fas fa-chevron-down"></i>
                        </button>
                      </div>
                    </div>
                  </div>
                  <div id="collapse<?php echo $index; ?>" class="collapse" aria-labelledby="heading<?php echo $index; ?>" data-bs-parent="#eventAccordion">
                    <div class="card-body">
                      <div class="row mb-3">
                        <div class="col-md-6">
                          <p><span class="event-details-label">Title:</span> <span class="event-details-value"><?php echo htmlspecialchars($event['title']); ?></span></p>
                          <p><span class="event-details-label">Details:</span> <span class="event-details-value"><?php echo htmlspecialchars($event['details']); ?></span></p>
                          <p><span class="event-details-label">Application Start Date:</span> <span class="event-details-value"><?php echo htmlspecialchars($event['application_start_date']); ?></span></p>
                          <p><span class="event-details-label">Application End Date:</span> <span class="event-details-value"><?php echo htmlspecialchars($event['application_end_date']); ?></span></p>
                        </div>
                        <div class="col-md-6">
                          <p><span class="event-details-label">Viva Notice Date:</span> <span class="event-details-value"><?php echo htmlspecialchars($event['viva_notice_date']); ?></span></p>
                          <p><span class="event-details-label">Seat Allotted Notice Date:</span> <span class="event-details-value"><?php echo htmlspecialchars($event['seat_allotment_result_notice_date']); ?></span></p>
                          <p><span class="event-details-label">Seat Confirm Deadline Date:</span> <span class="event-details-value"><?php echo htmlspecialchars($event['seat_confirm_deadline_date']); ?></span></p>
                          <p><span class="event-details-label">Modified:</span> <span class="event-details-value"><?php echo htmlspecialchars($event['modified']); ?></span></p>
                        </div>
                      </div>

                      <?php include_once '../class-file/PriorityList.php'; ?>
<div class="mb-3">
  <div class="card shadow-sm">
    <div class="card-header bg-primary text-white">
      <h5 class="card-title mb-0">
        Priority List <small class="text-light">(Lower level means higher priority)</small>
      </h5>
    </div>
    <div class="card-body">
      <div class="row mb-3 fw-bold">
        <div class="col-6">Priority Level</div>
        <div class="col-6">Priority Name</div>
      </div>
      <?php
        $priorityString = $event['priority_list'];
        $priorityArray = array_map('trim', explode(",", $priorityString));
        $priorityMapping = getPriorityList();
      ?>
      <?php foreach ($priorityArray as $index => $priority): 
        $displayText = isset($priorityMapping[$priority]) ? $priorityMapping[$priority] : ucfirst($priority);
      ?>
        <div class="row align-items-center mb-2">
          <div class="col-6">
            <div class="p-2 bg-light rounded text-center">
              <?php echo ($index + 1); ?>
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



                      <!-- Seat Distribution Quota -->
                      <div class="mb-3">
                        <p class="event-details-label mb-1">Seat Distribution Quota:</p>
                        <?php
                        $quotaArray = explode(",", $event['seat_distribution_quota']);
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
                                <th>Program & Semester</th>
                                <th>Quota</th>
                              </tr>
                            </thead>
                            <tbody>
                              <?php foreach ($quotaArray as $index => $quota): ?>
                                <tr>
                                  <td><?php echo $quotaLabels[$index]; ?></td>
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
              <?php endforeach; ?>
            <?php else: ?>
              <p>No events found.</p>
            <?php endif; ?>
          </div>
          <!-- Pagination Control -->
          <div id="pagination" class="d-flex justify-content-center mt-3"></div>
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

  <!-- Search, Filter and Pagination Scripts -->
  <script>
    $(document).ready(function() {
      const itemsPerPage = 10;
      const $items = $(".event-card");
      let filteredItems = $items;

      function filterItems() {
        const query = $("#searchInput").val().toLowerCase();
        const statusFilter = $("#statusFilter").val();
        filteredItems = $items.filter(function() {
          const eventId = $(this).find(".card-header h5").text().toLowerCase();
          const itemStatus = $(this).data("status").toString();
          return eventId.indexOf(query) !== -1 && (statusFilter === "" || itemStatus === statusFilter);
        });
        $items.hide();
        filteredItems.show();
      }

      function paginateItems() {
        const totalItems = filteredItems.length;
        const totalPages = Math.ceil(totalItems / itemsPerPage);
        $("#pagination").empty();
        if (totalPages > 1) {
          for (let i = 1; i <= totalPages; i++) {
            $("#pagination").append(`<button class="btn btn-sm btn-outline-primary mx-1 page-btn" data-page="${i}">${i}</button>`);
          }
          $("#pagination .page-btn").first().addClass("active");
          showPage(1);
        } else {
          filteredItems.show();
        }
      }

      function showPage(page) {
        filteredItems.hide();
        const start = (page - 1) * itemsPerPage;
        const end = start + itemsPerPage;
        filteredItems.slice(start, end).show();
      }

      $("#pagination").on("click", ".page-btn", function() {
        $("#pagination .page-btn").removeClass("active");
        $(this).addClass("active");
        showPage($(this).data("page"));
      });

      $("#searchInput, #statusFilter").on("keyup change", function() {
        filterItems();
        paginateItems();
      });

      paginateItems();
    });
  </script>
</body>

</html>