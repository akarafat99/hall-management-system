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

if (isset($_POST['seatCancel'])) {
  echo 1;
  $userId = $_POST['user_id'];
  $seatId = $_POST['seat_id'];

  $isResident = $seatDetails->isResident($userId, 1);
  if ($isResident) {
    $seatDetails->loadBySeatId($seatId);
    if ($seatDetails->user_id == $userId) {
      $seatDetails->status = 0; // 0 for available
      $seatDetails->reserved_by_event_id = 0; // 0 for not reserved by any event
      $seatDetails->user_id = 0;

      $seatDetails->update();
      $session::set('msg1', 'Seat cancelled successfully!');
      echo '<script type="text/javascript">window.location.href = "seat-cancel.php";</script>';
      exit;
    } else {
      $session::set('msg1', 'Seat ID does not belong to the provided User ID.');
      echo '<script type="text/javascript">window.location.href = "seat-cancel.php";</script>';
      exit;
    }
  } else {
    $session::set('msg1', 'User ID does not exist or is not a resident.');
    echo '<script type="text/javascript">window.location.href = "seat-cancel.php";</script>';
    exit;
  }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <title>MM Hall</title>

  <!-- Bootstrap 5 CSS -->
  <link
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css"
    rel="stylesheet" />
  <!-- DataTables Bootstrap 5 CSS -->
  <link
    href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css"
    rel="stylesheet" />
  <!-- Sidebar styles -->
  <link href="../css2/sidebar-admin.css" rel="stylesheet" />
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

        <!-- TODO -->
        <div class="container py-5">
          <div class="row justify-content-center">
            <div class="col-lg-6">
              <div class="card shadow-sm border-0">
                <div class="card-header bg-danger text-white">
                  <i class="fas fa-exclamation-triangle me-2"></i> Seat Cancellation
                </div>
                <div class="card-body">
                  <form id="seatActionForm" action="" method="post">
                    <!-- User ID -->
                    <div class="mb-4">
                      <label for="userId" class="form-label">User ID</label>
                      <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                        <input
                          type="text"
                          id="userId"
                          name="user_id"
                          class="form-control"
                          placeholder="Enter User ID"
                          required>
                      </div>
                    </div>

                    <!-- Seat ID -->
                    <div class="mb-4">
                      <label for="seatId" class="form-label">Seat ID</label>
                      <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-hashtag"></i></span>
                        <input
                          type="text"
                          id="seatId"
                          name="seat_id"
                          class="form-control"
                          placeholder="Enter Seat ID"
                          required>
                      </div>
                    </div>

                    <!-- Confirmation Text -->
                    <div class="mb-3">
                      <label for="confirmInput" class="form-label">Confirmation Text</label>
                      <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-keyboard"></i></span>
                        <input
                          type="text"
                          id="confirmInput"
                          class="form-control"
                          placeholder=""
                          required
                          onpaste="return false"
                          oncopy="return false"
                          oncut="return false"
                          oncontextmenu="return false">
                      </div>
                      <div class="form-text">
                        Please type <code id="confirmInstruction"></code> exactly to enable the button.
                      </div>
                    </div>

                    <!-- Irreversible Warning -->
                    <div class="alert alert-warning d-flex align-items-center mt-4">
                      <i class="fas fa-exclamation-circle flex-shrink-0 me-2"></i>
                      <div>
                        <strong>Warning:</strong> This action is <strong>irreversible</strong>. Please double-check before submitting.
                      </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="d-grid mt-3">
                      <button
                        type="submit"
                        class="btn btn-danger btn-lg fw-semibold"
                        id="submitBtn"
                        name="seatCancel"
                        disabled>
                        <i class="fas fa-check-circle me-1"></i> Confirm Cancellation
                      </button>
                    </div>
                  </form>
                </div>
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

  <script>
    $(function() {
      // generate and display the required string
      const code = String(Math.floor(Math.random() * 900000) + 100000);
      const requiredText = `CONFIRM SEAT CANCEL ${code}`;
      $('#confirmInput').attr('placeholder', requiredText);
      $('#confirmInstruction').text(requiredText);

      // block keyboard shortcuts (Ctrl+V/C/X/A) and right-click menu
      $('#confirmInput').on('keydown paste copy cut contextmenu', function(e) {
        // if it's a keydown with Ctrl+V/C/X/A
        if (e.type === 'keydown' && e.ctrlKey) {
          const forbidden = ['v', 'c', 'x', 'a'];
          if (forbidden.includes(e.key.toLowerCase())) {
            e.preventDefault();
          }
        }
        // block all non-keydown clipboard or contextmenu events
        if (e.type !== 'keydown') {
          e.preventDefault();
        }
      });

      // enable submit only on exact match
      $('#confirmInput').on('input', function() {
        $('#submitBtn').prop('disabled', $(this).val().trim() !== requiredText);
      });
    });
  </script>

</body>

</html>