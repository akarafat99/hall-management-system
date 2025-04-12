<?php
include_once '../class-file/HallSeatAllocationEvent.php';

$hallSeatAllocationEvent = new HallSeatAllocationEvent();

// Using getByEventAndStatus() to fetch active events with status 1,2,3.
$getActiveEvents = $hallSeatAllocationEvent->getByEventAndStatus(null, [1, 2, 3]);

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
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    
    <link href="https://fonts.googleapis.com/css?family=Muli:300,400,700,900" rel="stylesheet">
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
        integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>Seat In Hall | JUST Hall</title>
</head>

<body data-bs-spy="scroll" data-bs-target=".site-navbar-target" data-bs-offset="300">

    <!-- Notice Banner Section Start -->
    <section class="notice-hero">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="notice-hero-text text-center">
                        <h1>Apply for seat in hall</h1>
                    </div>
                </div>
            </div>
        </div>
        <span class="notice-hero-overlay"></span>
    </section>
    <!-- Notice Banner Section End -->

    <!-- Active Events Accordion -->
    <div class="container my-4">
        <h2 class="mb-4">Active Events</h2>
        <div class="accordion" id="eventAccordion">
            <?php if ($getActiveEvents && is_array($getActiveEvents) && count($getActiveEvents) > 0): ?>
                <?php $event = new HallSeatAllocationEvent(); ?>
                <?php foreach ($getActiveEvents as $e): ?>
                    <?php 
                        $event->setProperties($e); // Assuming setProperties() sets the properties of the event object.
                        $index = $event->event_id; // Assuming event_id is unique for each event.
                    ?>
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="heading<?php echo $index; ?>">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?php echo $index; ?>" aria-expanded="false" aria-controls="collapse<?php echo $index; ?>">
                                <?php
                                $eventId = isset($event->event_id) ? $event->event_id : "not yet published";
                                $title   = (!empty($event->title)) ? $event->title : "not yet published";
                                echo "Event #{$eventId} - " . htmlspecialchars($title);
                                ?>
                            </button>
                        </h2>
                        <div id="collapse<?php echo $index; ?>" class="accordion-collapse collapse" aria-labelledby="heading<?php echo $index; ?>" data-bs-parent="#eventAccordion">
                            <div class="accordion-body">
                                <table class="table table-bordered">
                                    <tbody>
                                        <tr>
                                            <th>Details</th>
                                            <td><?php echo (!empty($event->details)) ? htmlspecialchars($event->details) : "not yet published"; ?></td>
                                        </tr>
                                        <tr>
                                            <th>Status</th>
                                            <td>
                                                <?php
                                                $statusVal = isset($event->status) && trim($event->status) !== "" ? $event->status : "not yet published";
                                                $statusMeaning = (isset($statusMeanings[$event->status]) && $event->status != "") ? $statusMeanings[$event->status] : "not yet published";
                                                echo htmlspecialchars($statusMeaning);
                                                ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Application Start Date</th>
                                            <td><?php echo (!empty($event->application_start_date)) ? htmlspecialchars($event->application_start_date) : "not yet published"; ?></td>
                                        </tr>
                                        <tr>
                                            <th>Application End Date</th>
                                            <td><?php echo (!empty($event->application_end_date)) ? htmlspecialchars($event->application_end_date) : "not yet published"; ?></td>
                                        </tr>
                                        <tr>
                                            <th>Viva Notice Date</th>
                                            <td><?php echo (!empty($event->viva_notice_date)) ? htmlspecialchars($event->viva_notice_date) : "not yet published"; ?></td>
                                        </tr>
                                        <tr>
                                            <th>Viva Date List</th>
                                            <td><?php echo (!empty($event->viva_date_list)) ? htmlspecialchars($event->viva_date_list) : "not yet published"; ?></td>
                                        </tr>
                                        <tr>
                                            <th>Viva Student Count</th>
                                            <td><?php echo (!empty($event->viva_student_count)) ? htmlspecialchars($event->viva_student_count) : "not yet published"; ?></td>
                                        </tr>
                                        <tr>
                                            <th>Seat Allotment Result Notice Date</th>
                                            <td><?php echo (!empty($event->seat_allotment_result_notice_date)) ? htmlspecialchars($event->seat_allotment_result_notice_date) : "not yet published"; ?></td>
                                        </tr>
                                        <tr>
                                            <th>Seat Allotment Result Notice Text</th>
                                            <td><?php echo (!empty($event->seat_allotment_result_notice_text)) ? htmlspecialchars($event->seat_allotment_result_notice_text) : "not yet published"; ?></td>
                                        </tr>
                                        <tr>
                                            <th>Seat Confirm Deadline Date</th>
                                            <td><?php echo (!empty($event->seat_confirm_deadline_date)) ? htmlspecialchars($event->seat_confirm_deadline_date) : "not yet published"; ?></td>
                                        </tr>
                                        <?php
                                        include_once '../class-file/PriorityList.php';
                                        $priorityMapping = getPriorityList();

                                        $priorityOutput = "not yet published";
                                        if (!empty($event->priority_list)) {
                                            $priorityKeys = array_map('trim', explode(',', $event->priority_list));
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
                                                    if (!empty($event->seat_distribution_quota)) {
                                                        $quotaArray = array_map('trim', explode(',', $event->seat_distribution_quota));
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
                                            <td><?php echo (!empty($event->created)) ? htmlspecialchars($event->created) : "not yet published"; ?></td>
                                        </tr>
                                        <tr>
                                            <th>Modified On</th>
                                            <td><?php echo (!empty($event->modified)) ? htmlspecialchars($event->modified) : "not yet published"; ?></td>
                                        </tr>
                                    </tbody>
                                </table>
                                <p><i>*Hall authority can change any information at any time</i></p>
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

    <script src="../js/bootstrap.bundle.min.js"></script>
    
</body>

</html>
