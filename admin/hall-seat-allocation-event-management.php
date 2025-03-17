<?php
include_once '../class-file/HallSeatAllocationEvent.php';
$hallSeatAllocationEvent = new HallSeatAllocationEvent();
// Get events with status 0 or 1
$eventList = $hallSeatAllocationEvent->getByEventAndStatus(null, [0, 1]);
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <meta name="description" content="" />
  <meta name="author" content="" />

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
    integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />
  <link href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css" rel="stylesheet" />
  <link href="../css/Dashboard/dashboard.css" rel="stylesheet" />
  <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>

  <title>Event Management - Dashboard</title>

  <style>
    .profile-info-flex .label {
      font-weight: 500;
      color: black;
    }

    .profile-info-flex .data {
      color: black;
    }

    .accordion-item {
      border: none;
    }

    .accordion-item p {
      margin: 0;
    }

    .faq-heading {
      background: #f8f9fa;
      padding: 10px;
      border-radius: 10px;
      margin-bottom: 10px;
    }

    .faq-heading p {
      font-weight: 600;
      margin: 0;
      color: black;
    }

    .faq-item {
      border: 1px solid #e5e5e5 !important;
      border-radius: 5px;
      margin-bottom: 5px;
      padding: 10px;
    }

    .glossy-card {
      background: linear-gradient(135deg, #ffffff, #f8f9fa);
      border-radius: 8px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
      padding: 20px;
      margin-bottom: 20px;
    }
  </style>
</head>

<body class="sb-nav-fixed">
  <div id="layoutSidenav">
    <?php include 'admin-sidebar.php'; ?>
    <div id="layoutSidenav_content">
      <main>
        <div class="container-fluid px-4">
          <div class="card__wrapper">
            <div class="card__title-wrap mb-20">
              <h3 class="table__heading-title">Hall Seat Allocation Event Management</h3>
            </div>

            <!-- Search Bar and Status Filter -->
            <div class="row mb-4">
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

            <div class="accordion" id="faqAccordion">
              <div class="faq-heading">
                <div class="row">
                  <div class="col-lg-2">
                    <p>Event ID</p>
                  </div>
                  <div class="col-lg-2">
                    <p>Date of Creation</p>
                  </div>
                  <div class="col-lg-2">
                    <p>Status</p>
                  </div>
                  <div class="col-lg-2">
                    <p>Event Details</p>
                  </div>
                  <div class="col-lg-4">
                    <p>Action</p>
                  </div>
                </div>
              </div>
              <?php if ($eventList && is_array($eventList)): ?>
                <?php foreach ($eventList as $index => $event): ?>
                  <!-- Add a data-status attribute to each accordion item -->
                  <div class="accordion-item faq-item" data-status="<?php echo htmlspecialchars($event['status']); ?>">
                    <div class="row">
                      <div class="col-lg-2 d-flex align-items-center">
                        <p><?php echo htmlspecialchars($event['event_id']); ?></p>
                      </div>
                      <div class="col-lg-2 d-flex align-items-center">
                        <p><?php echo htmlspecialchars($event['created']); ?></p>
                      </div>
                      <div class="col-lg-2 d-flex align-items-center">
                        <?php if ($event['status'] == 0): ?>
                          <span class="badge bg-warning text-dark">Ongoing</span>
                        <?php else: ?>
                          <span class="badge bg-success">Completed</span>
                        <?php endif; ?>
                      </div>
                      <div class="col-lg-2 d-flex align-items-center">
                        <button class="btn btn-primary" data-bs-toggle="collapse" data-bs-target="#collapse<?php echo $index; ?>">Details</button>
                      </div>
                      <div class="col-lg-4 d-flex align-items-center flex-wrap">
                        <div class="d-flex align-items-center flex-wrap gap-3">
                          <?php if ($event['status'] == 0): ?>
                            <a href="manage-hall-seat-allocation-event.php?manageEvent=<?php echo $event['event_id']; ?>" class="btn btn-primary">Manage</a>
                            <a href="create-hall-seat-allocation-event.php?editEvent=<?php echo $event['event_id']; ?>" class="btn btn-success">Edit</a>
                          <?php endif; ?>
                        </div>
                      </div>
                    </div>
                    <div id="collapse<?php echo $index; ?>" class="accordion-collapse collapse" aria-labelledby="heading<?php echo $index; ?>" data-bs-parent="#faqAccordion">
                      <div class="accordion-body glossy-card">
                        <p><strong>Title:</strong> <?php echo htmlspecialchars($event['title']); ?></p>
                        <p><strong>Details:</strong> <?php echo htmlspecialchars($event['details']); ?></p>
                        <p><strong>Application Start Date:</strong> <?php echo htmlspecialchars($event['application_start_date']); ?></p>
                        <p><strong>Application End Date:</strong> <?php echo htmlspecialchars($event['application_end_date']); ?></p>
                        <p><strong>Viva Notice Date:</strong> <?php echo htmlspecialchars($event['viva_notice_date']); ?></p>
                        <p><strong>Seat Allotted Notice Date:</strong> <?php echo htmlspecialchars($event['seat_allotted_notice_date']); ?></p>
                        <p><strong>Seat Confirm Deadline Date:</strong> <?php echo htmlspecialchars($event['seat_confirm_deadline_date']); ?></p>

                        <!-- Priority List -->
                        <p><strong>Priority List:</strong></p>
                        <?php
                          $priorityString = $event['priority_list']; // e.g. "zilla,cgpaMerit,fatherIncome"
                          $priorityArray = array_map('trim', explode(",", $priorityString));
                          $priorityMapping = array(
                              'fatherIncome' => 'Father Income',
                              'cgpaMerit'    => 'CGPA - Merit List',
                              'zilla'        => 'Zilla'
                          );
                          $desiredOrder = array('fatherIncome', 'cgpaMerit', 'zilla');
                          $orderedPriorities = array();
                          foreach ($desiredOrder as $key) {
                              if (in_array($key, $priorityArray)) {
                                  $orderedPriorities[] = $key;
                              }
                          }
                          if (empty($orderedPriorities)) {
                              $orderedPriorities = $priorityArray;
                          }
                          $priorityLabels = array("1st priority:", "2nd priority:", "3rd priority:", "4th priority:", "5th priority:");
                          echo '<ul id="priorityList" class="list-group mb-4">';
                          foreach ($orderedPriorities as $index => $priority) {
                              $label = isset($priorityLabels[$index]) ? $priorityLabels[$index] : (($index + 1) . "th priority:");
                              $displayText = isset($priorityMapping[$priority]) ? $priorityMapping[$priority] : ucfirst($priority);
                              echo '<li class="list-group-item" data-value="' . htmlspecialchars($priority) . '">';
                              echo $label . " " . $displayText;
                              echo '</li>';
                          }
                          echo '</ul>';
                        ?>

                        <!-- Seat Distribution Quota -->
                        <p><strong>Seat Distribution Quota:</strong></p>
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
                          if (count($quotaArray) > 0) {
                              echo '<ul class="list-group">';
                              foreach ($quotaArray as $index => $quota) {
                                  echo '<li class="list-group-item"><strong>' . $quotaLabels[$index] . ':</strong> ' . htmlspecialchars(trim($quota)) . '</li>';
                              }
                              echo '</ul>';
                          }
                        ?>
                        <p><strong>Modified:</strong> <?php echo htmlspecialchars($event['modified']); ?></p>
                      </div>
                      <div class="col-lg-12 d-flex align-items-center justify-content-center">
                        <button class="btn btn-danger" data-bs-toggle="collapse" data-bs-target="#collapse<?php echo $index; ?>">Close</button>
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

  <script src="https://code.jquery.com/jquery-3.6.0.min.js" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
  <script src="script.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
  <script src="assets/demo/chart-area-demo.js"></script>
  <script src="assets/demo/chart-bar-demo.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
  <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>

  <script>
    $(document).ready(function () {
      $('#userTable').DataTable(); // Initialize DataTables on #userTable
    });
    window.addEventListener('DOMContentLoaded', event => {
      const sidebarToggle = document.body.querySelector('#sidebarToggle');
      if (sidebarToggle) {
        sidebarToggle.addEventListener('click', event => {
          event.preventDefault();
          document.body.classList.toggle('sb-sidenav-toggled');
          localStorage.setItem('sb|sidebar-toggle', document.body.classList.contains('sb-sidenav-toggled'));
        });
      }
    });
  </script>

  <!-- Search, Status Filter and Pagination for Accordion Items -->
  <script>
    $(document).ready(function () {
      const itemsPerPage = 10;
      const $items = $(".faq-item");
      let filteredItems = $items; // Global variable to hold filtered items

      function filterItems() {
        const query = $("#searchInput").val().toLowerCase();
        const statusFilter = $("#statusFilter").val();
        filteredItems = $items.filter(function () {
          // Assume the Event ID is in the first <p> and status is in data-status attribute.
          const eventId = $(this).find("p").first().text().trim().toLowerCase();
          const itemStatus = $(this).data("status").toString();
          let matchId = eventId.indexOf(query) !== -1;
          let matchStatus = (statusFilter === "") || (itemStatus === statusFilter);
          return matchId && matchStatus;
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

      $("#pagination").on("click", ".page-btn", function () {
        $("#pagination .page-btn").removeClass("active");
        $(this).addClass("active");
        const page = $(this).data("page");
        showPage(page);
      });

      $("#searchInput, #statusFilter").on("keyup change", function () {
        filterItems();
        paginateItems();
      });

      paginateItems();
    });
  </script>

  <!-- Update Priority List hidden field -->
  <script>
    function updatePriorityList() {
      let priorities = $("#priorityList").children().map(function () {
        return $(this).data('value');
      }).get().join(',');
      $("#priorityListInput").val(priorities);
    }
    $(function () {
      $("#priorityList").sortable({
        update: function (event, ui) {
          updatePriorityList();
        }
      });
      $("#priorityList").disableSelection();
      updatePriorityList();
    });
  </script>

  <!-- Set date picker min attribute to today -->
  <script>
    document.addEventListener("DOMContentLoaded", function () {
      const today = new Date();
      const dd = String(today.getDate()).padStart(2, '0');
      const mm = String(today.getMonth() + 1).padStart(2, '0');
      const yyyy = today.getFullYear();
      const todayFormatted = `${yyyy}-${mm}-${dd}`;
      document.querySelectorAll('input[type="date"]').forEach(function (dateInput) {
        dateInput.min = todayFormatted;
      });
    });
  </script>
</body>
</html>
