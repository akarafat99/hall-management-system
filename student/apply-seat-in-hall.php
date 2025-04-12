<?php
include_once '../class-file/SessionManager.php';
$session = SessionStatic::class;
include_once '../class-file/HallSeatAllocationEvent.php';
include_once '../popup-1.php';

if ($session::get('msg1') !== null) {
    showPopup($session::get('msg1'));
    $session::delete('msg1');
}

$hallSeatAllocationEvent = new HallSeatAllocationEvent();

// Using getByEventAndStatus() to fetch active events with status 1,2,3.
$getActiveEvents = $hallSeatAllocationEvent->getByEventAndStatus(null, [1, 2, 3], "application_end_date", "DESC");

// Define status meanings.
$statusMeanings = [
    1 => "Application collection completed. Upcoming: Publish the viva schedule and result notice date.",
    2 => "Viva sessions are underway. Upcoming: Publish the viva results.",
    3 => "Viva results have been reviewed and published. Upcoming: Set the deadline for seat confirmations.",
    4 => "All processes completed. Final lists—including viva results and confirmed seat allocations—are now available."
];

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
            if ($session::get('user') !== null) {
                include_once 'navbar-student-1.php';
            } else {
                include_once 'navbar-student-2.php';
            }
            ?>
            <!-- Navbar Section End -->

            <div class="container my-5">
                <!-- Accordion component -->
                <div class="accordion" id="accordionExample">
                    <?php if ($getActiveEvents && is_array($getActiveEvents) && count($getActiveEvents) > 0): ?>
                        <?php foreach ($getActiveEvents as $e): ?>
                            <?php
                            $hallSeatAllocationEvent->setProperties($e); // sets the event properties
                            $index = $hallSeatAllocationEvent->event_id; // unique identifier for each event
                            $isApplicationClosed = $hallSeatAllocationEvent->isApplicationClosed($hallSeatAllocationEvent->application_end_date);
                            ?>
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="heading<?php echo $index; ?>">
                                    <button class="accordion-button collapsed" type="button"
                                        data-bs-toggle="collapse"
                                        data-bs-target="#collapse<?php echo $index; ?>"
                                        aria-expanded="false"
                                        aria-controls="collapse<?php echo $index; ?>">
                                        <?php
                                        $eventId = isset($hallSeatAllocationEvent->event_id) ? $hallSeatAllocationEvent->event_id : "not yet published";
                                        $title   = (!empty($hallSeatAllocationEvent->title)) ? $hallSeatAllocationEvent->title : "not yet published";
                                        echo "Event #{$eventId} - " . htmlspecialchars($title);

                                        ?>
                                        <span class="badge bg-<?php echo $isApplicationClosed ? 'danger' : 'success'; ?> ms-2">
                                            <?php echo $isApplicationClosed ? 'Application Closed' : 'Application Open'; ?>
                                        </span>
                                    </button>
                                </h2>
                                <!-- Note: data-bs-parent is omitted to allow the active panel to collapse on click -->
                                <div id="collapse<?php echo $index; ?>" class="accordion-collapse collapse" aria-labelledby="heading<?php echo $index; ?>">
                                    <div class="accordion-body">
                                        <table class="table table-bordered">
                                            <tbody>
                                                <tr>
                                                    <th>Details</th>
                                                    <td><?php echo (!empty($hallSeatAllocationEvent->details)) ? htmlspecialchars($hallSeatAllocationEvent->details) : "not yet published"; ?></td>
                                                </tr>
                                                <tr>
                                                    <th>Status</th>
                                                    <td>
                                                        <?php
                                                        $statusVal = isset($hallSeatAllocationEvent->status) && trim($hallSeatAllocationEvent->status) !== "" ? $hallSeatAllocationEvent->status : "not yet published";
                                                        $statusMeaning = (isset($statusMeanings[$hallSeatAllocationEvent->status]) && $hallSeatAllocationEvent->status != "") ? $statusMeanings[$hallSeatAllocationEvent->status] : "not yet published";
                                                        echo htmlspecialchars($statusMeaning);
                                                        ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>Application Start Date</th>
                                                    <td><?php echo (!empty($hallSeatAllocationEvent->application_start_date)) ? htmlspecialchars($hallSeatAllocationEvent->application_start_date) : "not yet published"; ?></td>
                                                </tr>
                                                <tr>
                                                    <th>Application End Date</th>
                                                    <td><?php echo (!empty($hallSeatAllocationEvent->application_end_date)) ? htmlspecialchars($hallSeatAllocationEvent->application_end_date) : "not yet published"; ?></td>
                                                </tr>
                                                <tr>
                                                    <th>Viva Notice Date</th>
                                                    <td><?php echo (!empty($hallSeatAllocationEvent->viva_notice_date)) ? htmlspecialchars($hallSeatAllocationEvent->viva_notice_date) : "not yet published"; ?></td>
                                                </tr>
                                                <tr>
                                                    <th>Viva Date List</th>
                                                    <td><?php echo (!empty($hallSeatAllocationEvent->viva_date_list)) ? htmlspecialchars($hallSeatAllocationEvent->viva_date_list) : "not yet published"; ?></td>
                                                </tr>
                                                <tr>
                                                    <th>Viva Student Count</th>
                                                    <td><?php echo (!empty($hallSeatAllocationEvent->viva_student_count)) ? htmlspecialchars($hallSeatAllocationEvent->viva_student_count) : "not yet published"; ?></td>
                                                </tr>
                                                <tr>
                                                    <th>Seat Allotment Result Notice Date</th>
                                                    <td><?php echo (!empty($hallSeatAllocationEvent->seat_allotment_result_notice_date)) ? htmlspecialchars($hallSeatAllocationEvent->seat_allotment_result_notice_date) : "not yet published"; ?></td>
                                                </tr>
                                                <tr>
                                                    <th>Seat Allotment Result Notice Text</th>
                                                    <td><?php echo (!empty($hallSeatAllocationEvent->seat_allotment_result_notice_text)) ? htmlspecialchars($hallSeatAllocationEvent->seat_allotment_result_notice_text) : "not yet published"; ?></td>
                                                </tr>
                                                <tr>
                                                    <th>Seat Confirm Deadline Date</th>
                                                    <td><?php echo (!empty($hallSeatAllocationEvent->seat_confirm_deadline_date)) ? htmlspecialchars($hallSeatAllocationEvent->seat_confirm_deadline_date) : "not yet published"; ?></td>
                                                </tr>
                                                <?php
                                                include_once '../class-file/PriorityList.php';
                                                $priorityMapping = getPriorityList();

                                                $priorityOutput = "not yet published";
                                                if (!empty($hallSeatAllocationEvent->priority_list)) {
                                                    $priorityKeys = array_map('trim', explode(',', $hallSeatAllocationEvent->priority_list));
                                                    $priorityOutput = '';
                                                    foreach ($priorityKeys as $index => $key) {
                                                        $text = isset($priorityMapping[$key]) ? $priorityMapping[$key] : htmlspecialchars($key);
                                                        $priorityOutput .= ($index + 1) . " - " . $text . "<br>";
                                                    }
                                                }
                                                ?>
                                                <tr>
                                                    <th>Priority List</th>
                                                    <td>
                                                        <small>Note: Lower value means higher priority.</small>
                                                        <br>
                                                        <?php echo $priorityOutput; ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>Seat Distribution Quota</th>
                                                    <td>
                                                        <?php
                                                        if (!empty($hallSeatAllocationEvent->seat_distribution_quota)) {
                                                            $quotaArray = array_map('trim', explode(',', $hallSeatAllocationEvent->seat_distribution_quota));
                                                            $quotaLabels = [
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
                                                            ];
                                                            foreach ($quotaLabels as $i => $label) {
                                                                $quotaValue = isset($quotaArray[$i]) ? htmlspecialchars($quotaArray[$i]) : "N/A";
                                                                echo $label . " : " . $quotaValue . "<br>";
                                                            }
                                                        } else {
                                                            echo "not yet published";
                                                        }
                                                        ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>Created On</th>
                                                    <td><?php echo (!empty($hallSeatAllocationEvent->created)) ? htmlspecialchars($hallSeatAllocationEvent->created) : "not yet published"; ?></td>
                                                </tr>
                                                <tr>
                                                    <th>Modified On</th>
                                                    <td><?php echo (!empty($hallSeatAllocationEvent->modified)) ? htmlspecialchars($hallSeatAllocationEvent->modified) : "not yet published"; ?></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        <p><i>*Hall authority can change any information at any time</i></p>

                                        <!-- Apply seat in hall form button -->
                                        <?php if (!$isApplicationClosed): ?>
                                            <form action="form-1.php" method="post">
                                                <input type="hidden" name="event_id" value="<?php echo htmlspecialchars($hallSeatAllocationEvent->event_id); ?>">
                                                <button type="submit" name="apply" class="btn btn-primary">Apply for Seat in Hall</button>
                                            </form>
                                        <?php else: ?>
                                            <p class="text-danger">Application is closed for this event.</p>
                                        <?php endif; ?>

                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="alert alert-info" role="alert">
                            No active events found.
                        </div>
                    <?php endif; ?>
                </div>
            </div>


        </div>

        <!-- Second parent div for the footer -->
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