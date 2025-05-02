<?php
include_once '../class-file/SessionManager.php';
$session = SessionStatic::class;

include_once '../class-file/User.php';
include_once '../class-file/UserDetails.php';
include_once '../class-file/HallSeatAllocationEvent.php';
include_once '../class-file/HallSeatApplication.php';
include_once '../class-file/HallSeatDetails.php';
include_once '../class-file/Auth.php';
auth('user');

$sUser = $session::getObject('userObj');
$user = new User();
$session::copyProperties($sUser, $user);
$user->load();
$userDetails = new UserDetails();
$userDetails->getUsers($user->user_id, null, 1);

$hallSeatDetails = new HallSeatDetails();
$isResident = $hallSeatDetails->isResident($userDetails->user_id, 1);

if($isResident) {
    $hallSeatDetails->loadByUserId($userDetails->user_id, 1);
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>MM Hall</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Fonts: Roboto -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        /* Justify accordion body text */
        .accordion-body {
            text-align: justify;
        }
    </style>
</head>

<body>
    <!-- Parent container with flex and min-vh-100 -->
    <div class="d-flex flex-column min-vh-100">
        <!-- Main content including navbar -->
        <div class="flex-grow-1">
            <?php
            if ($session::get('user') !== null) {
                include_once 'navbar-student-1.php';
            } else {
                include_once 'navbar-student-2.php';
            }
            ?>
            
            <div class="container my-5">
  <?php if ($isResident): ?>
    <div class="card mb-4 border-start border-5 border-success shadow-sm">
      <div class="card-body">
        <div class="d-flex align-items-start">
          <div class="me-4">
            <span class="display-3 text-success"><i class="fas fa-bed"></i></span>
          </div>
          <div class="flex-fill">
            <h4 class="card-title">Assigned Hall Seat</h4>
            <div class="row gy-2">
              <div class="col-sm-6">
                <p class="mb-1"><strong>Seat ID:</strong> <?php echo  htmlspecialchars($hallSeatDetails->seat_id) ?></p>
                <p class="mb-1"><strong>Floor No:</strong> <?php echo  htmlspecialchars($hallSeatDetails->floor_no) ?></p>
                <p class="mb-1"><strong>Room No:</strong> <?php echo  htmlspecialchars($hallSeatDetails->room_no) ?></p>
              </div>
              <div class="col-sm-6">
                <p class="mb-1"><strong>Allotted From Event ID:</strong> <?php echo  htmlspecialchars($hallSeatDetails->reserved_by_event_id) ?></p>
              </div>
            </div>
            <small class="text-muted d-block mt-3">
              Created: <?php echo  htmlspecialchars($hallSeatDetails->created) ?> |
              Modified: <?php echo  htmlspecialchars($hallSeatDetails->modified) ?>
            </small>
          </div>
        </div>
      </div>
    </div>
  <?php else: ?>
    <div class="card mb-4 border-start border-5 border-danger shadow-sm text-center">
      <div class="card-body">
        <span class="display-3 text-danger"><i class="fas fa-exclamation-triangle"></i></span>
        <h4 class="card-title mt-3">No Hall Seat Assigned</h4>
        <p class="card-text">You don't have a seat yet. Reserve your spot now!</p>
        <a href="apply-seat-in-hall.php" class="btn btn-danger">
          <i class="fas fa-file-signature me-1"></i> Apply for Seat
        </a>
      </div>
    </div>
  <?php endif; ?>
</div>



        </div>
        <!-- Footer -->
        <footer class="bg-dark text-white mt-auto">
            <div class="container py-4 text-center">
                <p class="mb-0">&copy; 2025 MM Hall. All rights reserved.</p>
            </div>
        </footer>
    </div>

    <!-- Bootstrap JS and dependencies from CDN -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>