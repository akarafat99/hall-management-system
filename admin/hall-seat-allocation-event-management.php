<?php
include_once '../class-file/SessionManager.php';
$session = SessionStatic::class;

include_once '../class-file/Auth.php';
auth('admin');

include_once '../popup-1.php';
if ($session::get("msg1")) {
  showPopup($session::get("msg1"));
  $session::delete("msg1");
}

include_once '../class-file/HallSeatAllocationEvent.php';
include_once '../class-file/Department.php';

$department      = new Department();
$departmentList  = $department->getDepartments();
$yearSemesterCodes = $department->getYearSemesterCodes();
$hallSeatEvent   = new HallSeatAllocationEvent();
$eventList       = $hallSeatEvent->getByEventAndStatus(null, [1, 2, 3, 4, 5]);

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Event Management – Dashboard</title>

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <!-- simplePagination JS CSS (uses Bootstrap pagination) -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/simplePagination.js/1.6/simplePagination.css" />
  <!-- Sidebar CSS -->
  <link href="../css2/sidebar-admin.css" rel="stylesheet" />
</head>
<body>
  <div class="container-fluid">
    <div class="row">
      <!-- Sidebar Menu -->
      <?php include 'sidebar-admin.php'; ?>

      <!-- Main Content Area -->
      <main id="mainContent" class="col">
        <!-- Toggle button for sidebar on small screens -->
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

        <div class="p-4">
          <h1>Hall Seat Allocation Event Management</h1>

          <!-- Search by Event ID -->
          <div class="mb-4">
            <div class="input-group">
              <span class="input-group-text"><i class="fas fa-search"></i></span>
              <input
                type="text"
                id="searchEventId"
                class="form-control"
                placeholder="Search by Event ID">
            </div>
          </div>

          <!-- Event List Container -->
          <div id="events-list">
            <div class="accordion list" id="eventAccordion">
              <?php foreach ($eventList as $event):
                $eid   = (int)$event['event_id'];
                $title = htmlspecialchars($event['title']);
                $badge = ($event['status'] != 5)
                  ? '<span class="badge bg-warning text-dark ms-2">Ongoing</span>'
                  : '<span class="badge bg-success ms-2">Completed</span>';
                
                
                $semList  = explode(',', $event['semester_priority']);
                $quota    = explode(',', $event['seat_distribution_quota']);
              ?>
                <div class="accordion-item event-card list-item" data-eid="<?= $eid ?>">
                  <h2 class="accordion-header" id="heading<?= $eid ?>">
                    <div class="d-flex w-100 align-items-center">
                      <button class="accordion-button collapsed flex-grow-1"
                        type="button"
                        data-bs-toggle="collapse"
                        data-bs-target="#collapse<?= $eid ?>"
                        aria-expanded="false"
                        aria-controls="collapse<?= $eid ?>">
                        <span>#<?= $eid ?> – <?= $title ?></span>
                        <?= $badge ?>
                      </button>
                      <a href="hall-seat-allocation-event-dashboard.php?eventId=<?= $eid ?>"
                        class="btn btn-sm btn-primary ms-2">Dashboard</a>
                    </div>
                  </h2>
                  <div id="collapse<?= $eid ?>" class="accordion-collapse collapse"
                    aria-labelledby="heading<?= $eid ?>"
                    data-bs-parent="#eventAccordion">
                    <div class="accordion-body">
                      <p><strong>Details:</strong> <?= nl2br(htmlspecialchars($event['details'])) ?></p>
                      <p><strong>Application:</strong> <?= htmlspecialchars($event['application_start_date']) ?> – <?= htmlspecialchars($event['application_end_date']) ?></p>
                      <p><strong>Viva Notice:</strong> <?= htmlspecialchars($event['viva_notice_date']) ?></p>
                      <hr>
                      <!-- 4) Scoring Factors as badges -->
                <div class="mb-4">
                  <h6>Scoring Factors</h6>
                  <div class="alert alert-info p-2 mb-3 small">
                    These factors are used to calculate an applicant's score based on their distance from campus, academic results, and father's income.
                    Each value affects the final ranking and can go up to 5 decimal places (e.g., <code>0.12345</code>).
                  </div>
                  <div class="d-flex flex-wrap">
                    <?php
                    $scoreLabels = ['Distance', 'Result', "Father's Income"];
                    foreach (explode(',', $event['scoring_factor']) as $i => $value):
                    ?>
                      <span class="badge bg-info text-dark me-2 mb-2">
                        <?php echo htmlspecialchars($scoreLabels[$i] ?? "Factor " . ($i + 1)); ?>: <?php echo htmlspecialchars($value); ?>
                      </span>
                    <?php endforeach; ?>
                  </div>
                </div>
                      <p class="mb-2"><strong>Semester Priority</strong></p>
                      <ul class="list-group mb-4">
                        <?php foreach ($semList as $i => $num): ?>
                          <li class="list-group-item d-flex justify-content-between">
                            <span><strong>#<?= $i + 1 ?></strong> <?= htmlspecialchars($yearSemesterCodes[$num]) ?></span>
                            <i class="fas fa-arrows-alt-v text-secondary"></i>
                          </li>
                        <?php endforeach; ?>
                      </ul>
                      <p class="mb-2"><strong>Seat Quota by Department</strong></p>
                      <div class="table-responsive">
                        <table class="table table-bordered align-middle mb-0">
                          <thead class="table-light">
                            <tr>
                              <th class="text-center" style="width:80px;">Dept ID</th>
                              <th>Department Name</th>
                              <th class="text-end" style="width:120px;">Seats</th>
                            </tr>
                          </thead>
                          <tbody>
                            <?php foreach ($quota as $pair): list($did, $cnt) = explode('=>', $pair);
                              $dn = ''; foreach ($departmentList as $d) if ($d['department_id'] == $did) { $dn = $d['department_name']; break; } ?>
                              <tr>
                                <td class="text-center"><?= $did ?></td>
                                <td><?= htmlspecialchars($dn) ?></td>
                                <td class="text-end"><?= intval($cnt) ?></td>
                              </tr>
                            <?php endforeach; ?>
                          </tbody>
                        </table>
                      </div>
                    </div>
                  </div>
                </div>
              <?php endforeach; ?>
            </div>

            <!-- Pagination controls -->
            <div id="pagination-container" class="d-flex justify-content-center mt-4"></div>
          </div>

      </main>
    </div>
  </div>

  <!-- Scripts -->
    <!-- Scripts -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/simplePagination.js/1.6/jquery.simplePagination.min.js"></script>
  <script>
    $(function() {
      var allItems = $('.event-card');
      var currentItems = allItems;
      var perPage = 5;
      var pagination = $('#pagination-container');

      function renderPage(pageNumber) {
        var start = (pageNumber - 1) * perPage;
        var end = start + perPage;
        allItems.hide();
        currentItems.hide().slice(start, end).show();
      }

      // Initialize pagination
      pagination.pagination({
        items: currentItems.length,
        itemsOnPage: perPage,
        cssStyle: 'light-theme',
        onPageClick: function(pageNumber) {
          renderPage(pageNumber);
        }
      });

      // Show first page
      renderPage(1);

      // Search filter
      $('#searchEventId').on('input', function() {
        var q = $(this).val().trim();
        if (q === '') {
          currentItems = allItems;
        } else {
          currentItems = allItems.filter(function() {
            return $(this).data('eid').toString().indexOf(q) !== -1;
          });
        }
        pagination.pagination('updateItems', currentItems.length);
        pagination.pagination('selectPage', 1);
        renderPage(1);
      });
    });
  </script>
</body>
</html>
