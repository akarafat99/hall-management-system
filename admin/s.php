<?php
include_once '../class-file/HallSeatAllocationEvent.php';
include_once '../class-file/HallSeatDetails.php';
include_once '../class-file/PriorityList.php';

// Fetch the priority list from PriorityList.php
$priorityMapping = getPriorityList();  // This returns an associative array, e.g., [1 => "District", 2 => "Academic Result", 3 => "Father's Monthly Income"]

$hallSeatDetails = new HallSeatDetails();
$totalAvailableSeats = $hallSeatDetails->countSeatsByStatus(0);

$hallSeatAllocationEvent = new HallSeatAllocationEvent();

$message = "";
$isEditMode = false;
$editEventId = "";


$quotaValues = array_fill(0, 12, ""); // initialize 12 empty values

// Check if we are in edit mode
if (isset($_GET['editEvent']) && !empty($_GET['editEvent'])) {
    $editEventId = intval($_GET['editEvent']);
    $hallSeatAllocationEvent->event_id = $editEventId;
    $quotaValues = explode(",", $hallSeatAllocationEvent->seat_distribution_quota);
    // Load the event details for editing
    if ($hallSeatAllocationEvent->load() !== false) {
        $isEditMode = true;
    } else {
        $message = "Could not load event with ID: " . $editEventId;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Gathering priority list values from the form submission
    $priorityList   = isset($_POST['priorityList']) ? trim($_POST['priorityList']) : "";
    // Build the seat distribution quota as a comma-separated string from the 12 seat quota inputs.
    $quotaValues = array(
        $_POST['bsc11'],
        $_POST['bsc12'],
        $_POST['bsc21'],
        $_POST['bsc22'],
        $_POST['bsc31'],
        $_POST['bsc32'],
        $_POST['bsc41'],
        $_POST['bsc42'],
        $_POST['msc11'],
        $_POST['msc12'],
        $_POST['msc21'],
        $_POST['msc22']
    );
    $seat_distribution_quota = implode(",", $quotaValues);

    $hallSeatAllocationEvent->title = $_POST['eventTitle'];
    $hallSeatAllocationEvent->details = $_POST['eventdetails'];
    $hallSeatAllocationEvent->application_start_date = $_POST['startDate'];
    $hallSeatAllocationEvent->application_end_date = $_POST['endDate'];
    $hallSeatAllocationEvent->viva_notice_date = $_POST['vivaNoticeDate'];
    $hallSeatAllocationEvent->priority_list = $_POST['priorityList'];
    $hallSeatAllocationEvent->seat_distribution_quota = $seat_distribution_quota;
    $hallSeatAllocationEvent->status = 1;

    // Check if we're in edit mode by verifying the hidden field.
    if (isset($_POST['editEventId']) && !empty($_POST['editEventId'])) {
        $hallSeatAllocationEvent->event_id = intval($_POST['editEventId']);
    }

    // If editing, update the event; otherwise, insert a new record.
    if (isset($_POST['editEventId']) && !empty($_POST['editEventId'])) {
        $result = $hallSeatAllocationEvent->update();
    } else {
        // $result = $hallSeatAllocationEvent->insert();
        $result = 1;
    }
    if ($result === 1 || $result === true) {
        $message = "Event saved successfully with ID: " . $hallSeatAllocationEvent->event_id;
    } else {
        $message = "Error saving event.";
    }

    include_once '../popup-1.php';
    showPopup($message);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
        integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="../css/Dashboard/dashboard.css" rel="stylesheet" />
    <title>Event Create - Dashboard</title>
    <style>
        .form-info-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: #333;
        }

        .primary-button {
            background-color: #007bff;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 10rem;
            font-weight: 600;
            cursor: pointer;
        }
    </style>
</head>

<body class="sb-nav-fixed">
    <div id="layoutSidenav">
        <?php include 'admin-sidebar.php'; ?>
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">
                    <?php if ($isEditMode): ?>
                        <h3>Edit Hall Seat Allocation Event (ID: <?php echo htmlspecialchars($editEventId); ?>)</h3>
                    <?php else: ?>
                        <h3>Create Hall Seat Allocation Event</h3>
                    <?php endif; ?>
                    <div class="card shadow mb-4">
                        <div class="card-body">
                            <?php if (!empty($message)) { ?>
                                <div class="alert <?php echo ($result === 1 || $result === true) ? 'alert-success' : 'alert-danger'; ?>" role="alert">
                                    <?php echo $message; ?>
                                </div>
                            <?php } ?>
                            <!-- Form submission uses POST method and posts to the same page -->
                            <form method="post" action="">
                                <!-- Hidden field for edit mode -->
                                <?php if ($isEditMode): ?>
                                    <input type="hidden" name="editEventId" value="<?php echo $hallSeatAllocationEvent->event_id; ?>">
                                <?php endif; ?>

                                <div class="mb-3">
                                    <label for="eventTitle" class="form-label">Event Title</label>
                                    <input type="text" class="form-control" id="eventTitle" name="eventTitle" required value="<?php echo $hallSeatAllocationEvent->title; ?>">
                                </div>
                                <div class="mb-3">
                                    <label for="eventdetails" class="form-label">Event Details</label>
                                    <textarea class="form-control" id="eventdetails" name="eventdetails" rows="3" required><?php echo $hallSeatAllocationEvent->details; ?></textarea>
                                </div>
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label for="startDate" class="form-label">Application Start Date</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-calendar-day"></i></span>
                                            <input type="date" class="form-control" id="startDate" name="startDate" required value="<?php echo $hallSeatAllocationEvent->application_start_date; ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="endDate" class="form-label">Application End Date</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-calendar-check"></i></span>
                                            <input type="date" class="form-control" id="endDate" name="endDate" required value="<?php echo $hallSeatAllocationEvent->application_end_date; ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="vivaNoticeDate" class="form-label">
                                            Viva Notice Date
                                            <small class="text-muted" data-bs-toggle="tooltip" data-bs-placement="top" title="On that date the viva date will be announced">
                                                <i class="fas fa-info-circle"></i>
                                            </small>
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-bell"></i></span>
                                            <input type="date" class="form-control" id="vivaNoticeDate" name="vivaNoticeDate" required value="<?php echo $hallSeatAllocationEvent->viva_notice_date; ?>">
                                        </div>
                                    </div>
                                </div>

                                <!-- Priority List Section -->
                                <div class="text-center my-5">
                                    <h5 class="form-info-title">Priority List</h5>
                                </div>
                                <ul id="priorityList" class="list-group mb-4">
                                    <?php
                                    // Loop through the priority mapping and display each item
                                    foreach ($priorityMapping as $index => $priority) {
                                        echo '<li class="list-group-item" data-value="' . $index . '">' . $priority . '</li>';
                                    }
                                    ?>
                                </ul>

                                <!-- Hidden input for Priority List -->
                                <input type="hidden" id="priorityListInput" name="priorityList" value="<?php echo htmlspecialchars($priorityList); ?>">


                                <!-- Seat Quota Section Header -->
                                <div class="text-center my-5">
                                    <h5 class="form-info-title">Seat Quota</h5>
                                </div>

                                <!-- Summary and Redistribution Section -->
                                <div class="text-center my-3">
                                    <div id="quotaSummary">
                                        <strong>Total Available Seats:</strong> <span id="totalAvailable"><?php echo $totalAvailableSeats; ?></span>
                                        &nbsp; | &nbsp;
                                        <strong>Total Selected Seats:</strong> <span id="totalSelected">0</span>
                                    </div>
                                    <div id="exceedInfo" class="mt-2 text-danger"></div>
                                    <button type="button" class="btn btn-secondary mt-2" id="redistributeBtn">Redistribute Equally</button>
                                </div>

                                <!-- Warning message when total quota exceeds available seats -->
                                <div id="quotaWarning" class="alert alert-danger d-none">
                                    The total seat quota exceeds the available seats.
                                </div>

                                <div class="row">
                                    <?php
                                    // If editing, try to pre-fill quota values if available.
                                    $quotaVals = $isEditMode && !empty($quotaValues) ? $quotaValues : array_fill(0, 12, "");
                                    echo 1 . "<br>";
                                    ?>
                                    <div class="col-md-6 mb-3">
                                        <label for="bsc11" class="form-label">B. Sc. First year First Semester</label>
                                        <input type="number" class="form-control seat-quota" id="bsc11" name="bsc11" required value="<?php echo htmlspecialchars($quotaVals[0]); ?>">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="bsc12" class="form-label">B. Sc. First year Second Semester</label>
                                        <input type="number" class="form-control seat-quota" id="bsc12" name="bsc12" required value="<?php echo htmlspecialchars($quotaVals[1]); ?>">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="bsc21" class="form-label">B. Sc. Second year First Semester</label>
                                        <input type="number" class="form-control seat-quota" id="bsc21" name="bsc21" required value="<?php echo htmlspecialchars($quotaVals[2]); ?>">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="bsc22" class="form-label">B. Sc. Second year Second Semester</label>
                                        <input type="number" class="form-control seat-quota" id="bsc22" name="bsc22" required value="<?php echo htmlspecialchars($quotaVals[3]); ?>">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="bsc31" class="form-label">B. Sc. Third year First Semester</label>
                                        <input type="number" class="form-control seat-quota" id="bsc31" name="bsc31" required value="<?php echo htmlspecialchars($quotaVals[4]); ?>">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="bsc32" class="form-label">B. Sc. Third year Second Semester</label>
                                        <input type="number" class="form-control seat-quota" id="bsc32" name="bsc32" required value="<?php echo htmlspecialchars($quotaVals[5]); ?>">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="bsc41" class="form-label">B. Sc. Fourth year First Semester</label>
                                        <input type="number" class="form-control seat-quota" id="bsc41" name="bsc41" required value="<?php echo htmlspecialchars($quotaVals[6]); ?>">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="bsc42" class="form-label">B. Sc. Fourth year Second Semester</label>
                                        <input type="number" class="form-control seat-quota" id="bsc42" name="bsc42" required value="<?php echo htmlspecialchars($quotaVals[7]); ?>">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="msc11" class="form-label">M.Sc. First year First Semester</label>
                                        <input type="number" class="form-control seat-quota" id="msc11" name="msc11" required value="<?php echo htmlspecialchars($quotaVals[8]); ?>">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="msc12" class="form-label">M.Sc. First year Second Semester</label>
                                        <input type="number" class="form-control seat-quota" id="msc12" name="msc12" required value="<?php echo htmlspecialchars($quotaVals[9]); ?>">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="msc21" class="form-label">M.Sc. Second year First Semester</label>
                                        <input type="number" class="form-control seat-quota" id="msc21" name="msc21" required value="<?php echo htmlspecialchars($quotaVals[10]); ?>">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="msc22" class="form-label">M.Sc. Second year Second Semester</label>
                                        <input type="number" class="form-control seat-quota" id="msc22" name="msc22" required value="<?php echo htmlspecialchars($quotaVals[11]); ?>">
                                    </div>
                                </div>

                                <div class="text-center">
                                    <button type="submit" id="submitBtn" class="btn btn-primary"><?php echo $isEditMode ? 'Edit Event' : 'Submit'; ?></button>
                                </div>
                            </form>

                            <?php if ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
                                <div class="card mt-4">
                                    <div class="card-header">
                                        <h5>Submitted Data</h5>
                                    </div>
                                    <div class="card-body">
                                        <table class="table table-bordered">
                                            <tr>
                                                <th>Field</th>
                                                <th>Value</th>
                                            </tr>
                                            <tr>
                                                <td>Event Title</td>
                                                <td><?php echo htmlspecialchars($title); ?></td>
                                            </tr>
                                            <tr>
                                                <td>Event Details</td>
                                                <td><?php echo htmlspecialchars($details); ?></td>
                                            </tr>
                                            <tr>
                                                <td>Start Date</td>
                                                <td><?php echo htmlspecialchars($startDate); ?></td>
                                            </tr>
                                            <tr>
                                                <td>End Date</td>
                                                <td><?php echo htmlspecialchars($endDate); ?></td>
                                            </tr>
                                            <tr>
                                                <td>Viva Notice Date</td>
                                                <td><?php echo htmlspecialchars($vivaNoticeDate); ?></td>
                                            </tr>
                                            <tr>
                                                <td>Priority List</td>
                                                <td><?php echo htmlspecialchars($priorityList); ?></td>
                                            </tr>
                                            <tr>
                                                <td>BSc11</td>
                                                <td><?php echo htmlspecialchars($_POST['bsc11']); ?></td>
                                            </tr>
                                            <tr>
                                                <td>BSc12</td>
                                                <td><?php echo htmlspecialchars($_POST['bsc12']); ?></td>
                                            </tr>
                                            <tr>
                                                <td>BSc21</td>
                                                <td><?php echo htmlspecialchars($_POST['bsc21']); ?></td>
                                            </tr>
                                            <tr>
                                                <td>BSc22</td>
                                                <td><?php echo htmlspecialchars($_POST['bsc22']); ?></td>
                                            </tr>
                                            <tr>
                                                <td>BSc31</td>
                                                <td><?php echo htmlspecialchars($_POST['bsc31']); ?></td>
                                            </tr>
                                            <tr>
                                                <td>BSc32</td>
                                                <td><?php echo htmlspecialchars($_POST['bsc32']); ?></td>
                                            </tr>
                                            <tr>
                                                <td>BSc41</td>
                                                <td><?php echo htmlspecialchars($_POST['bsc41']); ?></td>
                                            </tr>
                                            <tr>
                                                <td>BSc42</td>
                                                <td><?php echo htmlspecialchars($_POST['bsc42']); ?></td>
                                            </tr>
                                            <tr>
                                                <td>MSc11</td>
                                                <td><?php echo htmlspecialchars($_POST['msc11']); ?></td>
                                            </tr>
                                            <tr>
                                                <td>MSc12</td>
                                                <td><?php echo htmlspecialchars($_POST['msc12']); ?></td>
                                            </tr>
                                            <tr>
                                                <td>MSc21</td>
                                                <td><?php echo htmlspecialchars($_POST['msc21']); ?></td>
                                            </tr>
                                            <tr>
                                                <td>MSc22</td>
                                                <td><?php echo htmlspecialchars($_POST['msc22']); ?></td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </main>
            <footer class="py-4 bg-light mt-auto">
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
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="script.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
    <script src="assets/demo/chart-area-demo.js"></script>
    <script src="assets/demo/chart-bar-demo.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
 

    <!-- Update Priority List hidden field -->
    <script>
        function updatePriorityList() {
            let priorities = $("#priorityList").children().map(function() {
                return $(this).data('value');
            }).get().join(',');
            $("#priorityListInput").val(priorities);
        }
        $(function() {
            $("#priorityList").sortable({
                update: function(event, ui) {
                    updatePriorityList();
                }
            });
            $("#priorityList").disableSelection();
            updatePriorityList();
        });
    </script>

    <!-- Set the minimum date for all date input fields to today's date -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const today = new Date();
            const dd = String(today.getDate()).padStart(2, '0');
            const mm = String(today.getMonth() + 1).padStart(2, '0');
            const yyyy = today.getFullYear();
            const todayFormatted = `${yyyy}-${mm}-${dd}`;
            document.querySelectorAll('input[type="date"]').forEach(function(dateInput) {
                dateInput.min = todayFormatted;
            });
        });
    </script>
</body>

</html>