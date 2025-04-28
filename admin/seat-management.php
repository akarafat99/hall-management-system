<?php
include_once '../class-file/SessionManager.php';
$session = SessionStatic::class;

include_once '../class-file/Auth.php';
auth('admin');

if ($session::get('msg1')) {
    include_once '../popup-1.php';
    showPopup($session::get('msg1'));
    $session::delete('msg1');
}

include_once '../class-file/HallSeatDetails.php';
$seatDetails = new HallSeatDetails();
$totalFloors = $seatDetails->getMaxFloorNo();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <title>Seat Management – Dashboard</title>

  <!-- Bootstrap 5 CSS -->
  <link
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css"
    rel="stylesheet" />
  <!-- DataTables Bootstrap 5 CSS -->
  <link
    href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css"
    rel="stylesheet" />
  <!-- Sidebar styles -->
  <link href="../css2/sidebar-admin.css" rel="stylesheet"/>
</head>

<body>
  <div class="container-fluid">
    <div class="row">
      <!-- Sidebar -->
      <?php include 'sidebar-admin.php'; ?>

      <!-- Main Content -->
      <main id="mainContent" class="col">
        <!-- Toggle for small screens -->
        <button
          class="btn btn-dark d-lg-none my-3"
          type="button"
          data-bs-toggle="collapse"
          data-bs-target="#sidebarMenu"
          aria-controls="sidebarMenu"
          aria-expanded="false"
          aria-label="Toggle navigation">
          ☰ Menu
        </button>

        <div class="px-4 py-3 mt-4">
          <!-- Header & Create button -->
          <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="h2">Seat Management</h2>
            <a
              href="create-floor-room-seat.php"
              class="btn btn-success btn-md rounded-pill px-4 fw-semibold">
              <i class="fas fa-plus me-2"></i>
              Create Floor, Room &amp; Seat
            </a>
          </div>

          <!-- Custom search bar (search by floor no) -->
          <div class="row mb-3">
            <div class="col-md-4">
              <input
                type="text"
                id="floorSearch"
                class="form-control"
                placeholder="Search by floor no">
            </div>
          </div>

          <!-- Table card -->
          <div class="card shadow-sm">
            <div class="card-body">
              <div class="table-responsive">
                <table
                  id="userTable"
                  class="table table-striped table-hover table-bordered align-middle">
                  <thead class="table-light">
                    <tr>
                      <th scope="col">Floor No</th>
                      <th scope="col">View Room &amp; Seat</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php for ($floor = 0; $floor <= $totalFloors; $floor++) { ?>
                    <tr>
                      <td><?php echo $floor; ?></td>
                      <td>
                        <a
                          href="floorwise.php?floorNo=<?php echo $floor; ?>"
                          class="btn btn-outline-primary btn-sm rounded-pill px-3">
                          <i class="fas fa-eye me-1"></i>
                          View
                        </a>
                      </td>
                    </tr>
                    <?php } ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>

        </div>
      </main>
    </div>
  </div>

  <!-- jQuery & Bootstrap JS -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
    crossorigin="anonymous"></script>
  <!-- DataTables JS (for pagination & ordering only) -->
  <script
    src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
  <script
    src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>

  <script>
    $(document).ready(function () {
      // initialize DataTables without its search box
      $('#userTable').DataTable({
        lengthChange: false,
        searching: false,
        pageLength: 10,
        ordering: true,
        columnDefs: [{ orderable: false, targets: 1 }]
      });

      // filter rows by floor no
      $('#floorSearch').on('keyup', function() {
        var val = $(this).val().trim();
        $('#userTable tbody tr').each(function() {
          var floor = $(this).find('td:first').text().trim();
          $(this).toggle(floor.indexOf(val) !== -1);
        });
      });
    });
  </script>
</body>
</html>
