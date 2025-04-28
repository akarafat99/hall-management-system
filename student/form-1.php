<?php
include_once '../class-file/SessionManager.php';
$session = SessionStatic::class;

include_once '../class-file/User.php';
include_once '../class-file/UserDetails.php';
include_once '../class-file/HallSeatAllocationEvent.php';
include_once '../class-file/HallSeatApplication.php';
include_once '../class-file/HallSeatDetails.php';

if ($session::get('user') == null) {
    $session::set('msg1', 'Please login  first and then apply for a seat in the hall.');
    echo "<script>window.location.href='../login.php';</script>";
    exit;
}



$sUser = $session::getObject('userObj');
$user = new User();
$session::copyProperties($sUser, $user);
$user->load();
$userDetails = new UserDetails();
$userDetails->getUsers($user->user_id, null, 1);

$hallSeatDetails = new HallSeatDetails();
$hallSeatAllocationEvent = new HallSeatAllocationEvent();
$hallSeatApplication = new HallSeatApplication();

$isResident = $hallSeatDetails->isResident($user->user_id, 1);
if($isResident) {
    $session::set('msg1', 'You are already a resident. You cannot apply for a seat in the hall.');
    echo "<script>window.location.href='apply-seat-in-hall.php';</script>";
    exit;
}

$eventId = -1;
$alreadyApplied = false;

if (isset($_POST['apply'])) {
    $eventId = $_POST['event_id'];
    $hallSeatAllocationEvent->getByEventAndStatus($eventId);
    $alreadyApplied = $hallSeatApplication->isAppliedByUserIdEventStatus($user->user_id, $eventId);
}

if (isset($_POST['submitApplication'])) {
    $eventId = $_POST['event_id'];
    $hallSeatAllocationEvent->getByEventAndStatus($eventId);

    $hallSeatApplication->user_id = $user->user_id;
    $hallSeatApplication->user_details_id = $userDetails->details_id;
    $hallSeatApplication->event_id = $eventId;
    $hallSeatApplication->status = 1; // Assuming 1 is for "applied"

    $isApplicationClosed = $hallSeatAllocationEvent->isApplicationClosed($hallSeatAllocationEvent->application_end_date);
    if ($isApplicationClosed) {
        $session::set('msg1', 'Application for this event is closed.');
    } else {
        $result = $hallSeatApplication->insert();
        if ($result) {
            $session::set('msg1', 'Application submitted successfully.');
        } else {
            $session::set('msg1', 'Failed to submit application. Please try again.');
        }
    }
    echo "<script>window.location.href='apply-seat-in-hall.php';</script>";
    exit;
}


if($eventId == -1) {
    $session::set('msg1', 'Please select an event to apply for a seat in the hall.');
    echo "<script>window.location.href='apply-seat-in-hall.php';</script>";
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bootstrap Accordion Sample</title>

    <!-- For navbar -->
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Fonts: Roboto for Material Design look -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">


    <!-- for accordion -->
    <!-- Bootstrap CSS from CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Fonts: Roboto for Material Design look -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <title>MM Hall</title>

    <style>
        /* This rule justifies the text in the accordion body */
        .accordion-body {
            text-align: justify;
        }
    </style>

</head>

<body>
    <!-- Parent container with flex and min-vh-100 -->
    <div class="d-flex flex-column min-vh-100">
        <!-- First parent div for all main content including the navbar -->
        <div class="flex-grow-1">
            <!-- navbar section start -->
            <?php
            if ($session::get('user') != null) {
                include_once 'navbar-student-1.php';
            } else {
                include_once 'navbar-student-2.php';
            }
            ?>
            <!-- Navbar Section End -->

            <!-- Form 1: Apply for a seat in the hall -->
            <?php if ($alreadyApplied): ?>
                <div class="container my-5">
                    <div class="row justify-content-center">
                        <div class="col-md-8">
                            <div class="card border-warning shadow">
                                <div class="card-header bg-warning text-white">
                                    <h4 class="mb-0">Notice</h4>
                                </div>
                                <div class="card-body">
                                    <h5 class="card-title">Application Already Submitted</h5>
                                    <p class="card-text">
                                        Our records indicate that you have already applied for a seat in this hall. If you think this is a mistake or need further assistance, please contact the administration office.
                                    </p>
                                    <a href="../contact-us.php" class="btn btn-outline-warning">Contact Support</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <div class="container my-5">
                    <div class="row justify-content-center">
                        <div class="col-md-8 col-lg-6">
                            <div class="card shadow-sm">
                                <div class="card-header bg-primary text-white">
                                    <h5 class="mb-0">Apply for a Seat in Hall</h5>
                                </div>
                                <div class="card-body">
                                    <p class="mb-4">
                                        Event Name: <?php echo $hallSeatAllocationEvent->title; ?>
                                    </p>
                                    <p class="mb-4">
                                        <i>To apply for a seat in hall using your profile, please review your profile details before submitting your application.</i>
                                    </p>
                                    <div class="mb-3 d-flex align-items-center">
                                        <span class="me-3">Your Profile:</span>
                                        <form action="view-profile.php" method="post" enctype="multipart/form-data" target="_blank">
                                            <input type="hidden" name="viewEditRequest" value="<?php echo $userDetails->details_id; ?>">
                                            <button type="submit" class="btn btn-outline-info">View Profile</button>
                                        </form>
                                    </div>
                                    <form action="" method="post" enctype="multipart/form-data">
                                        <div class="mb-3">
                                            <input type="hidden" name="event_id" value="<?php echo $hallSeatAllocationEvent->event_id; ?>">
                                            <div class="d-grid">
                                                <button type="submit" name="submitApplication" class="btn btn-success">Submit Application</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Form 1 End -->


        </div>
    </div>

    <!-- Second parent div for the footer -->
    <footer class="bg-dark text-white mt-auto">
        <div class="container py-4 text-center">
            <p class="mb-0">&copy; 2025 MM Hall. All rights reserved.</p>
        </div>
    </footer>

    <!-- Bootstrap JS and dependencies from CDN -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>