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
    // Load the event details for editing
    if ($hallSeatAllocationEvent->load() !== false) {
        $quotaValues = explode(",", $hallSeatAllocationEvent->seat_distribution_quota);
        $totalReservedSeats = array_sum($quotaValues);
        $totalAvailableSeats += $totalReservedSeats;
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
        //Update the previous and new total seat quota in the hall seat details table.
        $previousTotalSeatQuota = $hallSeatDetails->countRowsByEventId($hallSeatAllocationEvent->event_id);
        $newTotalSeatQuota = array_sum($quotaValues);
        $hallSeatDetails->updateReservedSeatsBasedOnDelta($previousTotalSeatQuota, $newTotalSeatQuota, $hallSeatAllocationEvent->event_id);

        $result = $hallSeatAllocationEvent->update();
    } else {
        $result = $hallSeatAllocationEvent->insert();
        // Calculate the total seat quota from the submitted quota values.
        $totalSeatQuota = array_sum($quotaValues);

        // Update hall seat details: update rows with status 0 to status 2,
        // and assign the reserved_by_event_id using the event id,
        // but limit the number of affected rows to the total seat quota.
        $result = $hallSeatDetails->updateRowsByStatusAndEventIdAndLimit(0, 2, $hallSeatAllocationEvent->event_id, null, $totalSeatQuota);
    }
    if ($result === 1 || $result === true || $result >= 0) {
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

        /* New: Set the cursor for priority list items to a move icon */
        #priorityList li {
            cursor: move;
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
                            <form method="post">
                                <?php if ($isEditMode): ?>
                                    <input type="hidden" name="editEventId" value="<?php echo $editEventId; ?>">
                                <?php endif; ?>

                                <!-- Title & Details -->
                                <div class="mb-3">
                                    <label class="form-label">Event Title</label>
                                    <input type="text" name="eventTitle" class="form-control" required
                                        value="<?php echo htmlspecialchars($hallSeatAllocationEvent->title); ?>">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Event Details</label>
                                    <input name="eventdetails" class="form-control" rows="3"
                                        value="<?php echo htmlspecialchars($hallSeatAllocationEvent->details); ?>" required>
                                </div>

                                <!-- Dates -->
                                <div class="row mb-4">
                                    <?php
                                    $dates = [
                                        ['startDate', 'Application Start Date', 'fa-calendar-day', $hallSeatAllocationEvent->application_start_date],
                                        ['endDate', 'Application End Date', 'fa-calendar-check', $hallSeatAllocationEvent->application_end_date],
                                        ['vivaNoticeDate', 'Viva Notice Date', 'fa-bell', $hallSeatAllocationEvent->viva_notice_date],
                                    ];
                                    foreach ($dates as [$name, $label, $icon, $val]) {
                                    ?>
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label"><?php echo $label; ?></label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="fas <?php echo $icon; ?>"></i></span>
                                                <input type="date" name="<?php echo $name; ?>" class="form-control" required
                                                    value="<?php echo htmlspecialchars($val); ?>">
                                            </div>
                                        </div>
                                    <?php } ?>
                                </div>

                                <!-- Priority List -->
                                <div class="text-center my-4">
                                    <h5 class="form-info-title">Priority List</h5>
                                </div>
                                <ul id="priorityList" class="list-group mb-3">
                                    <?php
                                    // If in edit mode and a stored priority order exists, use it:
                                    if ($isEditMode && !empty($hallSeatAllocationEvent->priority_list)) {
                                        // The stored priority_list is assumed to be a comma-separated string (e.g., "3,1,2")
                                        $priorityOrder = explode(",", $hallSeatAllocationEvent->priority_list);
                                        foreach ($priorityOrder as $key) {
                                            // Only show the item if it exists in the mapping.
                                            if (isset($priorityMapping[$key])) {
                                                echo '<li class="list-group-item" data-value="' . htmlspecialchars($key) . '">' . htmlspecialchars($priorityMapping[$key]) . '</li>';
                                            }
                                        }
                                    } else {
                                        // New event: Show the default order based on getPriorityList() output.
                                        foreach ($priorityMapping as $idx => $txt) {
                                            echo '<li class="list-group-item" data-value="' . htmlspecialchars($idx) . '">' . htmlspecialchars($txt) . '</li>';
                                        }
                                    }
                                    ?>
                                </ul>
                                <input type="hidden" id="priorityListInput" name="priorityList" value="">

                                <!-- Seat Quota -->
                                <div class="text-center my-4">
                                    <h5 class="form-info-title">Seat Quota</h5>
                                </div>
                                <div class="text-center mb-3">
                                    <strong>Total Available Seats:</strong>
                                    <span id="totalAvailable"><?php echo $totalAvailableSeats; ?></span>
                                    &nbsp;|&nbsp;
                                    <strong>Total Selected Seats:</strong>
                                    <span id="totalSelected">0</span>
                                    <div id="exceedInfo" class="mt-2 text-danger"></div>
                                    <button type="button" id="redistributeBtn" class="btn btn-secondary mt-2">
                                        Redistribute Equally
                                    </button>
                                </div>
                                <div id="quotaWarning" class="alert alert-danger d-none">
                                    The total seat quota exceeds the available seats.
                                </div>

                                <div class="row">
                                    <?php
                                    $labels = [
                                        'bsc11' => 'B. Sc. 1st Year 1st Sem',
                                        'bsc12' => 'B. Sc. 1st Year 2nd Sem',
                                        'bsc21' => 'B. Sc. 2nd Year 1st Sem',
                                        'bsc22' => 'B. Sc. 2nd Year 2nd Sem',
                                        'bsc31' => 'B. Sc. 3rd Year 1st Sem',
                                        'bsc32' => 'B. Sc. 3rd Year 2nd Sem',
                                        'bsc41' => 'B. Sc. 4th Year 1st Sem',
                                        'bsc42' => 'B. Sc. 4th Year 2nd Sem',
                                        'msc11' => 'M. Sc. 1st Year 1st Sem',
                                        'msc12' => 'M. Sc. 1st Year 2nd Sem',
                                        'msc21' => 'M. Sc. 2nd Year 1st Sem',
                                        'msc22' => 'M. Sc. 2nd Year 2nd Sem',
                                    ];
                                    $i = 0;
                                    foreach ($labels as $field => $label):
                                    ?>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label" for="<?php echo $field; ?>"><?php echo $label; ?></label>
                                            <input type="number" class="form-control seat-quota" id="<?php echo $field; ?>"
                                                name="<?php echo $field; ?>" required
                                                value="<?php echo htmlspecialchars($quotaValues[$i++]); ?>">
                                        </div>
                                    <?php endforeach; ?>
                                </div>

                                <div class="text-center mt-4">
                                    <button type="submit" id="submitBtn" class="btn btn-primary">
                                        <?php echo $isEditMode ? 'Update Event' : 'Create Event'; ?>
                                    </button>
                                    <div id="submitWarning" class="text-danger mt-2 d-none">
                                        Quota exceeds available seats. Please adjust before submitting.
                                    </div>
                                </div>

                            </form>

                        </div>
                    </div>


                </div>
            </main>


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

    <script>
(function($, document) {
    // Get current time in Asia/Dhaka as a locale string and then create a Date object from it.
    const dhakaTimeString = new Date().toLocaleString("en-US", { timeZone: "Asia/Dhaka" });
    const dhakaDate = new Date(dhakaTimeString);

    const dd = String(dhakaDate.getDate()).padStart(2, '0');
    const mm = String(dhakaDate.getMonth() + 1).padStart(2, '0');
    const yyyy = dhakaDate.getFullYear();
    const minD = `${yyyy}-${mm}-${dd}`;

    // Set today's date as the minimum for all date fields.
    document.querySelectorAll('input[type="date"]').forEach(el => el.min = minD);

    // If in edit mode, override the min attribute for Application Start, End, and Viva Notice Dates.
    <?php if ($isEditMode): ?>
        const minStartDate = '<?php echo $hallSeatAllocationEvent->application_start_date; ?>';
        document.querySelector('input[name="startDate"]').min = minStartDate;
        document.querySelector('input[name="endDate"]').min = minStartDate;
        document.querySelector('input[name="vivaNoticeDate"]').min = minStartDate;
    <?php endif; ?>

    // Priority list initialization.
    function updatePriorityList() {
        const vals = $('#priorityList').children()
            .map((_, li) => $(li).data('value'))
            .get()
            .join(',');
        $('#priorityListInput').val(vals);
    }
    $('#priorityList')
        .sortable({
            update: updatePriorityList
        })
        .disableSelection();
    updatePriorityList();

    // Seat quota logic.
    const totalAvailable = parseInt($('#totalAvailable').text(), 10) || 0;
    const inputs = $('.seat-quota').toArray();
    const submitBtn = $('#submitBtn');
    const submitWarning = $('#submitWarning');

    function recalc() {
        const sum = inputs.reduce((s, el) => s + (parseInt(el.value, 10) || 0), 0);
        $('#totalSelected').text(sum);

        if (sum > totalAvailable) {
            $('#quotaWarning').removeClass('d-none');
            $('#exceedInfo').text(`Exceeded by ${sum - totalAvailable} seat(s).`);
            submitBtn.prop('disabled', true);
            submitWarning.removeClass('d-none');
        } else {
            $('#quotaWarning').addClass('d-none');
            $('#exceedInfo').text('');
            submitBtn.prop('disabled', false);
            submitWarning.addClass('d-none');
        }
    }

    $('#redistributeBtn').on('click', () => {
        const n = inputs.length;
        const base = Math.floor(totalAvailable / n);
        let rem = totalAvailable - base * n;
        inputs.forEach((el, i) => el.value = base + (i < rem ? 1 : 0));
        recalc();
    });

    $('.seat-quota').on('input', recalc);
    recalc();

    <?php if (!$isEditMode): ?>
        $('#redistributeBtn').trigger('click');
    <?php endif; ?>

})(jQuery, document);
</script>




</body>

</html>