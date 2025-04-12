<?php
include_once '../class-file/HallSeatAllocationEvent.php';
$hallSeatAllocationEvent = new HallSeatAllocationEvent();
// Get events for the desired statuses (update if additional statuses are needed)
$eventList = $hallSeatAllocationEvent->getByEventAndStatus(null, [1, 2, 3, 4]);
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

    /* Single column and justified details in the accordion */
    .accordion-details {
      text-align: justify;
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
                  <option value="Ongoing">Ongoing</option>
                  <option value="Completed">Completed</option>
                </select>
              </div>
            </div>
          </div>

          <div id="eventAccordion">
            <?php if ($eventList && is_array($eventList)): ?>
              <?php foreach ($eventList as $index => $event): ?>
                <?php
                // Use computed status text: if event['status'] is not 4 -> "Ongoing", else "Completed".
                $statusText = ($event['status'] != 4) ? 'Ongoing' : 'Completed';
                $statusBadgeClass = ($event['status'] != 4) ? 'bg-warning text-dark' : 'bg-success';
                ?>
                <!-- Update data-status to hold the computed status text -->
                <div class="card event-card" data-status="<?php echo $statusText; ?>">
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
                    <!-- Single column for details with justified text -->
                    <div class="card-body accordion-details">
                      <div class="col-md-12">
                        <p><span class="event-details-label">Title:</span> <span class="event-details-value"><?php echo htmlspecialchars($event['title']); ?></span></p>
                        <p><span class="event-details-label">Details:</span> <span class="event-details-value"><?php echo htmlspecialchars($event['details']); ?></span></p>
                        <p><span class="event-details-label">Application Start Date:</span> <span class="event-details-value"><?php echo htmlspecialchars($event['application_start_date']); ?></span></p>
                        <p><span class="event-details-label">Application End Date:</span> <span class="event-details-value"><?php echo htmlspecialchars($event['application_end_date']); ?></span></p>
                        <p><span class="event-details-label">Viva Notice Date:</span> <span class="event-details-value"><?php echo htmlspecialchars($event['viva_notice_date']); ?></span></p>
                        <p><span class="event-details-label">Seat Allotted Notice Date:</span> <span class="event-details-value"><?php echo htmlspecialchars($event['seat_allotment_result_notice_date']); ?></span></p>
                        <p><span class="event-details-label">Seat Confirm Deadline Date:</span> <span class="event-details-value"><?php echo htmlspecialchars($event['seat_confirm_deadline_date']); ?></span></p>
                        <p><span class="event-details-label">Modified:</span> <span class="event-details-value"><?php echo htmlspecialchars($event['modified']); ?></span></p>
                      </div>
                      <!-- Additional content (e.g., Priority List or Quota Table) can be placed below -->
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
