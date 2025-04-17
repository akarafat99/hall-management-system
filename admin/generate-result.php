<?php
include_once '../class-file/HallSeatAllocationEvent.php';
include_once '../class-file/HallSeatApplication.php';
include_once '../class-file/UserDetails.php';
include_once '../class-file/Division.php';
include_once '../class-file/PriorityList.php';

$eventId = $_GET['eventId'] ?? 0;
if ($eventId) {
    $hallSeatAllocationEvent = new HallSeatAllocationEvent();
    $hallSeatAllocationEvent->getByEventAndStatus($eventId, 3);

    $hallSeatApplication = new HallSeatApplication();
    $allApplications = $hallSeatApplication->getApplicationsByUserIdEventStatus(null, $eventId, 2);

    $priorityList = getPriorityList();
    $districtValue = getDivisions();

    $user_details_ids = [];

    foreach ($allApplications as $application) {
        $user_details_ids[] = $application['user_details_id'];
        echo "User details ID: " . $application['user_details_id'] . "<br>";
    }

    $userDetails = new UserDetails();
    $requiredData = $userDetails->getUserSummaryByIds($user_details_ids);

    // Define the priority order array.
    // Priority 2: Academic Result
    // Priority 3: Father's Monthly Income
    // Priority 1: District value (looked up from $districtValue)
    $priority_order = [2, 3, 1];

    $finalSummary = [];

    // Loop through each user details record in $requiredData.
    foreach ($requiredData as $record) {
        $detailsId = $record['details_id'];
        $values = [];

        // Loop through the defined priority order
        foreach ($priority_order as $priority) {
            if ($priority == 1) {
                // Priority 1: District value.
                // Lookup the district distance using the division and district names
                $divisionName = $record['division'];
                $districtName = $record['district'];
                $districtDistance = (isset($districtValue[$divisionName]) && isset($districtValue[$divisionName][$districtName]))
                    ? $districtValue[$divisionName][$districtName]
                    : null;
                $values[] = $districtDistance;
            } elseif ($priority == 2) {
                // Priority 2: Academic Result (from 'academic_result')
                $values[] = $record['last_semester_cgpa_or_merit'];
            } elseif ($priority == 3) {
                // Priority 3: Father's Monthly Income
                $values[] = $record['father_monthly_income'];
            }
        }

        // Set the final summary value indexed by the details ID.
        $finalSummary[$detailsId] = $values;
    }

    

    // Output the final summary array
    print_r($finalSummary);
}

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


</head>

<body class="sb-nav-fixed">
    <div id="layoutSidenav">
        <?php // include 'admin-sidebar.php'; 
        ?>
        <div id="layoutSidenav_content">
            <main>


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