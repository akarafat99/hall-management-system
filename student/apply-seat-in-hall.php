<?php
include_once '../class-file/SessionManager.php';
$session = SessionStatic::class;

include_once '../class-file/HallSeatAllocationEvent.php';
include_once '../class-file/Department.php';
include_once '../popup-1.php';

if ($session::get('msg1') !== null) {
    showPopup($session::get('msg1'));
    $session::delete('msg1');
}

$hallSeatAllocationEvent = new HallSeatAllocationEvent();
$department = new Department();
$departmentList = $department->getDepartments();
$yearSemesterCodes = $department->getYearSemesterCodes();

// Using getByEventAndStatus() to fetch active events with status 1,2,3.
$getActiveEvents = $hallSeatAllocationEvent->getByEventAndStatus(null, [1, 2, 3, 4, 5, 6], "application_end_date", "DESC");

// Define status meanings.
$statusMeanings = [
    1 => "Application collection completed. Upcoming: Publish the viva schedule and result notice date.",
    2 => "Viva sessions are underway. Upcoming: Publish the viva results.",
    3 => "Viva results have been reviewed and published. Upcoming: Publish the seat allocation result",
    4 => "Seat allocation results have been published. Upcoming: Seat Confirmation Open.",
    5 => "Event has been finalized."
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
            if ($session::get('user') != null) {
                include_once 'navbar-student-1.php';
            } else {
                include_once 'navbar-student-2.php';
            }
            ?>
            <!-- Navbar Section End -->

            <!-- Search START -->
            <!-- TODO: add an Event-ID filter -->
            <h3 class="text-center my-4">Hall Seat Allocation Events</h3>
            <p class="text-center">Find the latest updates on hall seat allocation events below.</p>
            <form class="d-flex justify-content-center my-4">
                <div class="input-group input-group-lg w-75 shadow-sm">
                    <span class="input-group-text bg-white border-0">
                        <i class="fas fa-search text-secondary"></i>
                    </span>
                    <input
                        type="text"
                        id="filterEventId"
                        class="form-control border-0 rounded-pill ps-2"
                        placeholder="Filter by Event ID…"
                        aria-label="Filter by Event ID">
                    <button
                        class="btn btn-outline-secondary border-0"
                        type="button"
                        id="clearFilter">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
            </form>

            <!-- Search END -->



            <div class="container my-5">
                <!-- Accordion component -->
                <div class="accordion" id="accordionExample">
                    <?php if ($getActiveEvents && is_array($getActiveEvents) && count($getActiveEvents) > 0): ?>
                        <?php foreach ($getActiveEvents as $e): ?>
                            <?php
                            $hallSeatAllocationEvent->setProperties($e); // sets the event properties
                            $index = $hallSeatAllocationEvent->event_id; // unique identifier for each event
                            $isApplicationClosed = $hallSeatAllocationEvent->isApplicationClosed($hallSeatAllocationEvent->application_end_date);
                            $isProcessing = ($hallSeatAllocationEvent->status == 5) ? false : true; // Check if the event is in processing status
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
                                        <span class="badge bg-<?php echo $isProcessing ? 'warning' : 'success'; ?> ms-2">
                                            <?php echo $isProcessing ? 'Ongoing' : 'Ended'; ?>
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
                                                        if ($hallSeatAllocationEvent->status == 4) {
                                                            echo 'Check Your <i>My Hall Seat Application</i> section for details.';
                                                        }
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
                                                    <th>Seat Allotment Result Notice</th>
                                                    <td><?php echo (!empty($hallSeatAllocationEvent->seat_allotment_result_notice_text)) ? htmlspecialchars($hallSeatAllocationEvent->seat_allotment_result_notice_text) : "not yet published"; ?></td>
                                                </tr>
                                                <tr>
                                                    <th>Seat Allotment Result Notice Date</th>
                                                    <td>
                                                        <?php
                                                        // If the date is the MySQL “zero date” or empty, show the placeholder
                                                        if (
                                                            empty($hallSeatAllocationEvent->seat_allotment_result_notice_date)
                                                            || $hallSeatAllocationEvent->seat_allotment_result_notice_date === '0000-00-00'
                                                        ) {
                                                            echo "not yet published";
                                                        } else {
                                                            // Otherwise show the date (you can reformat it if you like)
                                                            echo date(
                                                                'd M Y',
                                                                strtotime($hallSeatAllocationEvent->seat_allotment_result_notice_date)
                                                            );
                                                        }
                                                        ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>Seat Confirm Deadline Date</th>
                                                    <td>
                                                        <?php
                                                        // If the date is the MySQL “zero date” or empty, show the placeholder
                                                        if (
                                                            empty($hallSeatAllocationEvent->seat_confirm_deadline_date)
                                                            || $hallSeatAllocationEvent->seat_confirm_deadline_date === '0000-00-00'
                                                        ) {
                                                            echo "not yet published";
                                                        } else {
                                                            // Otherwise show the date (you can reformat it if you like)
                                                            echo date(
                                                                'd M Y',
                                                                strtotime($hallSeatAllocationEvent->seat_confirm_deadline_date)
                                                            );
                                                        }
                                                        ?>
                                                    </td>

                                                </tr>
                                                <tr>
                                                    <th>Scoring Factors</th>
                                                    <td>
                                                        <div class="mb-4">
                                                            <div class="alert alert-info p-2 mb-3 small">
                                                                These factors are used to calculate an applicant's score based on their distance from campus, academic results, and father's income.
                                                                Each value affects the final ranking and can go up to 5 decimal places (e.g., <code>0.12345</code>).
                                                            </div>
                                                            <div class="d-flex flex-wrap">
                                                                <?php
                                                                $scoreLabels = array('Distance', 'Result', "Father's Income");
                                                                foreach (explode(',', $hallSeatAllocationEvent->scoring_factor) as $i => $value) {
                                                                    echo '<span class="badge bg-info text-dark me-2 mb-2">'
                                                                        . htmlspecialchars($scoreLabels[$i] ?? 'Factor ' . ($i + 1), ENT_QUOTES)
                                                                        . ': ' . htmlspecialchars($value, ENT_QUOTES)
                                                                        . '</span>';
                                                                }
                                                                ?>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>


                                                <tr>
                                                    <th>Semester Priority</th>
                                                    <td>
                                                        <?php if (!empty($hallSeatAllocationEvent->semester_priority)): ?>
                                                            <?php
                                                            $keys = explode(',', $hallSeatAllocationEvent->semester_priority);
                                                            foreach ($keys as $i => $k) {
                                                                echo ($i + 1) . ' – ' . $yearSemesterCodes[$k] . '<br>';
                                                            }
                                                            ?>
                                                        <?php else: ?>
                                                            not yet published
                                                        <?php endif; ?>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <th>Department Quotas</th>
                                                    <td>
                                                        <?php if (!empty($hallSeatAllocationEvent->seat_distribution_quota)): ?>
                                                            <?php
                                                            foreach (explode(',', $hallSeatAllocationEvent->seat_distribution_quota) as $pair) {
                                                                list($did, $cnt) = explode('=>', $pair);
                                                                // find dept name
                                                                $name = $did;
                                                                foreach ($departmentList as $d) {
                                                                    if ($d['department_id'] == $did) {
                                                                        $name = htmlspecialchars($d['department_name']);
                                                                        break;
                                                                    }
                                                                }
                                                                echo $name . ' – ' . intval($cnt) . '<br>';
                                                            }
                                                            ?>
                                                        <?php else: ?>
                                                            not yet published
                                                        <?php endif; ?>
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

    <!-- Search for Event ID: -->
    <script>
        document.getElementById('filterEventId').addEventListener('input', function() {
            const term = this.value.trim();
            document.querySelectorAll('.accordion-item').forEach(item => {
                // grab the “Event #X” from the header button text
                const btnText = item.querySelector('.accordion-button').textContent;
                const match = btnText.match(/Event\s+#(\d+)/);
                const id = match ? match[1] : '';
                // show if no filter or if the ID includes the term
                item.style.display = (!term || id.includes(term)) ? '' : 'none';
            });
        });

        document.getElementById('clearFilter').addEventListener('click', () => {
            const input = document.getElementById('filterEventId');
            input.value = '';
            input.dispatchEvent(new Event('input'));
            input.focus();
        });
    </script>

</body>

</html>