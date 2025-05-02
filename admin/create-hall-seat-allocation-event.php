<?php
include_once '../class-file/SessionManager.php';
$session = SessionStatic::class;
$session::ensureSessionStarted();

include_once '../class-file/Auth.php';
auth('admin');

include_once '../popup-1.php';
if ($session::get("msg1")) {
    showPopup($session::get("msg1"));
    $session::delete("msg1");
}

include_once '../class-file/HallSeatAllocationEvent.php';
include_once '../class-file/HallSeatDetails.php';
include_once '../class-file/Department.php';

// Load active departments
$department     = new Department();
$departmentList = $department->getDepartments(null, 1);
$yearSemesterCode = $department->getYearSemesterCodes();

// Load seat‑detail helper & total seats
$hallSeatDetails     = new HallSeatDetails();
$totalAvailableSeats = $hallSeatDetails->countSeatsByStatus(0);

// Distribution defaults
$hallSeatAllocationEvent = new HallSeatAllocationEvent();
$deptDistribution         = $hallSeatAllocationEvent->distributeSeatsByDeptTotalMinOne();

$distanceCofactor = $resultCofactor = $incomeCofactor = '';

// Are we editing?
$isEdit = isset($_GET['editEventid']) && $_GET['editEventid'];
if ($isEdit) {
    $editId = (int)$_GET['editEventid'];
    $hallSeatAllocationEvent->event_id = $editId;
    $hallSeatAllocationEvent->load();

    // Scoring factors
    $factors = explode(',', $hallSeatAllocationEvent->scoring_factor);
    $distanceCofactor = $factors[0] ?? '';
    $resultCofactor   = $factors[1] ?? '';
    $incomeCofactor   = $factors[2] ?? '';

    // explode existing quota into array
    $quotaValuesByDept = [];
    foreach (explode(',', $hallSeatAllocationEvent->seat_distribution_quota) as $pair) {
        list($did, $cnt) = explode('=>', $pair);
        $quotaValuesByDept[$did] = (int)$cnt;
    }
}

// Handle form submit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Map fields
    $hallSeatAllocationEvent->title                  = $_POST['eventTitle'];
    $hallSeatAllocationEvent->details                = $_POST['eventDetails'];
    $hallSeatAllocationEvent->application_start_date = $_POST['startDate'];
    $hallSeatAllocationEvent->application_end_date   = $_POST['endDate'];
    $hallSeatAllocationEvent->viva_notice_date       = $_POST['vivaNoticeDate'];
    $factors = [
        $_POST['distanceCofactor'],
        $_POST['resultCofactor'],
        $_POST['incomeCofactor']
    ];
    $hallSeatAllocationEvent->scoring_factor = implode(',', $factors);
    $hallSeatAllocationEvent->semester_priority      = $_POST['semesterOrder'];

    // Build seat_distribution_quota
    $totalSelectedSeats = 0;
    $pairs = [];
    foreach ($_POST['deptSeats'] as $did => $cnt) {
        $pairs[] = $did . '=>' . intval($cnt);
        $totalSelectedSeats += intval($cnt);
    }
    $hallSeatAllocationEvent->seat_distribution_quota = implode(',', $pairs);

    if (isset($_POST['editEvent'])) {
        // Update existing
        $success = $hallSeatAllocationEvent->update();
        $previousTotalSeatQuota = $hallSeatDetails->countRowsByEventId($hallSeatAllocationEvent->event_id);
        $hallSeatDetails->updateReservedSeatsBasedOnDelta($previousTotalSeatQuota, $totalSelectedSeats, $hallSeatAllocationEvent->event_id);
        $session::set('msg1', $success
            ? "Event #{$hallSeatAllocationEvent->event_id} updated successfully."
            : "Failed to update event.");

        $newId = $hallSeatAllocationEvent->event_id;
    } else {
        // Create new
        $newId = $hallSeatAllocationEvent->insert();
        $hallSeatDetails->updateRowsByStatusAndEventIdAndLimit(0, 2, $newId, null, $totalSelectedSeats);
        $session::set('msg1', $newId
            ? "Event created successfully! Event ID: $newId"
            : "Failed to create event.");
    }

    echo '<script>window.location="hall-seat-allocation-event-dashboard.php?eventId=' . $newId . '";</script>';
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title><?php echo $isEdit ? 'Edit' : 'Create'; ?> Hall Seat Allocation Event</title>

    <!-- Bootstrap & FontAwesome -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" rel="stylesheet" />
    <!-- Sidebar CSS -->
    <link href="../css2/sidebar-admin.css" rel="stylesheet" />
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <?php include 'sidebar-admin.php'; ?>
            <main id="mainContent" class="col">
                <button
                    class="btn btn-dark d-lg-none mt-3 mb-3"
                    type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#sidebarMenu"
                    aria-controls="sidebarMenu"
                    aria-expanded="false"
                    aria-label="Toggle navigation">
                    ☰ Menu
                </button>
                <div class="container-fluid px-4">
                    <h3 class="mt-4 mb-4">
                        <?php echo $isEdit ? 'Edit' : 'Create'; ?> Hall Seat Allocation Event
                    </h3>
                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-calendar-alt me-2"></i>
                            Event Information
                        </div>
                        <div class="card-body">
                            <form method="post" class="needs-validation" novalidate>
                                <!-- Event Title -->
                                <div class="mb-3">
                                    <label for="eventTitle" class="form-label">Event Title</label>
                                    <input
                                        type="text"
                                        class="form-control"
                                        id="eventTitle"
                                        name="eventTitle"
                                        placeholder="Enter event title"
                                        required
                                        value="<?php echo $isEdit ? htmlspecialchars($hallSeatAllocationEvent->title) : ''; ?>">
                                    <div class="invalid-feedback">Please enter an event title.</div>
                                </div>

                                <!-- Event Details -->
                                <div class="mb-4">
                                    <label for="eventdetails" class="form-label">Event Details</label>
                                    <input
                                        class="form-control"
                                        id="eventdetails"
                                        name="eventDetails"
                                        rows="3"
                                        type="text"
                                        placeholder="Enter event details"
                                        value="<?php echo $isEdit ? htmlspecialchars($hallSeatAllocationEvent->details) : ''; ?>"
                                        required>
                                    <div class="invalid-feedback">Please provide event details.</div>
                                </div>

                                <!-- Dates -->
                                <div class="row g-3 mb-4">
                                    <!-- Start Date -->
                                    <div class="col-md-4">
                                        <label for="startDate" class="form-label">Start Date</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-calendar-day"></i></span>
                                            <input
                                                type="date"
                                                class="form-control"
                                                id="startDate"
                                                name="startDate"
                                                required
                                                value="<?php echo $isEdit
                                                            ? htmlspecialchars($hallSeatAllocationEvent->application_start_date)
                                                            : ''; ?>">
                                        </div>
                                        <div class="invalid-feedback">Select a start date.</div>
                                    </div>

                                    <!-- End Date -->
                                    <div class="col-md-4">
                                        <label for="endDate" class="form-label">End Date</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-calendar-check"></i></span>
                                            <input
                                                type="date"
                                                class="form-control"
                                                id="endDate"
                                                name="endDate"
                                                required
                                                value="<?php echo $isEdit
                                                            ? htmlspecialchars($hallSeatAllocationEvent->application_end_date)
                                                            : ''; ?>">
                                        </div>
                                        <div class="invalid-feedback">Select an end date.</div>
                                    </div>

                                    <!-- Viva Notice Date -->
                                    <div class="col-md-4">
                                        <label for="vivaNoticeDate" class="form-label">Viva Notice Date</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-bell"></i></span>
                                            <input
                                                type="date"
                                                class="form-control"
                                                id="vivaNoticeDate"
                                                name="vivaNoticeDate"
                                                required
                                                value="<?php echo $isEdit
                                                            ? htmlspecialchars($hallSeatAllocationEvent->viva_notice_date)
                                                            : ''; ?>">
                                        </div>
                                        <div class="invalid-feedback">Select a viva notice date.</div>
                                    </div>
                                </div>


                                <!-- Three Cofactor Inputs for the scoring -->
                                <!-- Three Cofactor Inputs for the scoring -->
                                <div class="mb-4 p-3 border rounded bg-light shadow-sm">
                                    <label class="form-label fw-semibold fs-5">Scoring Factors</label>
                                    <div class="form-text text-muted mb-3">
                                        These factors are used to calculate applicant scores based on distance, academic result, and father's income.
                                        You can input values up to <strong>5 decimal places</strong> (e.g., <code>0.12345</code>).
                                    </div>

                                    <!-- Always‐visible example panel -->
                                    <div class="card card-body bg-white border">
                                        <strong>Example:</strong>
                                        <ul class="mt-2 mb-2">
                                            <li><strong>Distance</strong>: 12 km</li>
                                            <li><strong>CGPA</strong>: 3.90</li>
                                            <li><strong>Father’s Monthly Income</strong>: 18,000 Tk</li>
                                            <li><strong>Semester Code</strong>: 3 (Spring 2024)</li>
                                        </ul>
                                        <strong>Chosen Cofactors:</strong>
                                        <ul class="mb-2">
                                            <li>Distance factor: <code>0.10</code></li>
                                            <li>Result factor: <code>0.50</code></li>
                                            <li>Income factor: <code>0.0001</code></li>
                                        </ul>
                                        <p class="mb-0">
                                            → <em>Score</em> =
                                            <code>0.10×12 + 0.50×3.90 + 0.0001×18,000 = 1.20 + 1.95 + 1.80 = <strong>4.95</strong></code>
                                        </p>
                                    </div>
                                </div>



                                <div class="row mb-4">
                                    <div class="col-md-4">
                                        <label for="distanceCofactor" class="form-label">Distance Cofactor</label>
                                        <input
                                            type="number"
                                            step="0.00001"
                                            class="form-control"
                                            id="distanceCofactor"
                                            name="distanceCofactor"
                                            placeholder="Enter distance cofactor"
                                            required
                                            value="<?= htmlspecialchars($distanceCofactor) ?>">
                                        <div class="invalid-feedback">Please enter the distance cofactor.</div>
                                    </div>

                                    <div class="col-md-4">
                                        <label for="resultCofactor" class="form-label">Result Cofactor</label>
                                        <input
                                            type="number"
                                            step="0.00001"
                                            class="form-control"
                                            id="resultCofactor"
                                            name="resultCofactor"
                                            placeholder="Enter result cofactor"
                                            required
                                            value="<?= htmlspecialchars($resultCofactor) ?>">
                                        <div class="invalid-feedback">Please enter the result cofactor.</div>
                                    </div>

                                    <div class="col-md-4">
                                        <label for="incomeCofactor" class="form-label">Father's Income Cofactor</label>
                                        <input
                                            type="number"
                                            step="0.00001"
                                            class="form-control"
                                            id="incomeCofactor"
                                            name="incomeCofactor"
                                            placeholder="Enter father's income cofactor"
                                            required
                                            value="<?= htmlspecialchars($incomeCofactor) ?>">
                                        <div class="invalid-feedback">Please enter the father's monthly income cofactor.</div>
                                    </div>
                                </div>

                                <!-- Semester Priority -->
                                <?php
                                $savedSemesters = $isEdit && $hallSeatAllocationEvent->semester_priority
                                    ? explode(',', $hallSeatAllocationEvent->semester_priority)
                                    : [];

                                // All possible semester keys
                                $allSemesterKeys = array_keys($yearSemesterCode);

                                // Anything not yet in the saved order
                                $remainingSemesters = array_diff($allSemesterKeys, $savedSemesters);

                                // Final render order: saved first, then the rest
                                $renderSemesterKeys = array_merge($savedSemesters, $remainingSemesters);
                                ?>
                                <!-- Semester Priority -->
                                <!-- Separate Info Box -->

                                <div class="mb-4">
                                    <label class="form-label fw-semibold">Semester Priority</label>
                                    <div class="form-text mb-1">
                                        Press and hold <i class="fas fa-grip-lines"></i> to reorder.
                                    </div>
                                    <div class="mb-3 p-3 border rounded bg-light">
                                        <i class="fas fa-info-circle text-info me-2"></i>
                                        <span class="small text-muted">
                                            Drag each semester into the order you want seats allocated.
                                            The semester at the top of the list will have its students assigned seats first,
                                            then the next one, and so on.
                                            For example, if you put “3rd Year” above “2nd Year,”
                                            all eligible 3rd-year students will get seats before any 2nd-year students.
                                        </span>
                                    </div>

                                    <ul id="semesterList" class="list-group mb-2">
                                        <?php foreach ($renderSemesterKeys as $key): ?>
                                            <li class="list-group-item d-flex align-items-center" data-value="<?php echo $key; ?>">
                                                <i class="fas fa-grip-lines me-3 text-muted" style="cursor:move;"></i>
                                                <?php echo htmlspecialchars($yearSemesterCode[$key]); ?>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                    <input
                                        type="hidden"
                                        id="semesterOrder"
                                        name="semesterOrder"
                                        value="<?= implode(',', $renderSemesterKeys) ?>">
                                </div>


                                <!-- Seat Distribution by Department -->
                                <?php
                                $originalTotal = array_sum($deptDistribution);
                                $jsonDefaults  = json_encode($deptDistribution);
                                if ($isEdit) {
                                    $originalTotal = array_sum($quotaValuesByDept) + $totalAvailableSeats;
                                    $jsonDefaults  = json_encode($quotaValuesByDept);
                                }
                                ?>
                                <div class="mb-4">
                                    <label class="form-label fw-semibold">Seat Distribution by Department</label>
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <button id="resetDistribution" type="button" class="btn btn-sm btn-secondary">
                                            <i class="fas fa-undo me-1"></i> Reset To Default Distribution
                                        </button>
                                        <div id="distroWarning" class="text-danger fw-semibold" style="display:none;"></div>
                                    </div>
                                    <div class="mb-2 d-flex justify-content-between">
                                        <div><strong>Total available seats:</strong> <?= $originalTotal ?></div>
                                        <div><strong>Selected seats:</strong> <span id="selectedSeats">0</span></div>
                                    </div>

                                    <div class="table-responsive shadow-sm rounded">
                                        <table class="table table-striped table-bordered align-middle mb-0">
                                            <thead class="table-light">
                                                <tr>
                                                    <th class="text-center">Dept ID</th>
                                                    <th>Department Name</th>
                                                    <th class="text-end">Seats</th>
                                                    <th class="text-end">%</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($departmentList as $dept):
                                                    $did   = $dept['department_id'];
                                                    // choose edit value or default distribution
                                                    $count = $isEdit
                                                        ? ($quotaValuesByDept[$did] ?? 0)
                                                        : ($deptDistribution[$did] ?? 0);
                                                ?>
                                                    <tr data-did="<?php echo $did; ?>">
                                                        <td class="text-center"><?php echo $did; ?></td>
                                                        <td><?php echo htmlspecialchars($dept['department_name']); ?></td>
                                                        <td>
                                                            <input
                                                                type="number"
                                                                name="deptSeats[<?php echo $did; ?>]"
                                                                class="form-control form-control-sm text-end distro-input"
                                                                min="0"
                                                                value="<?php echo $count; ?>"
                                                                data-default="<?php echo $count; ?>"
                                                                required>
                                                        </td>
                                                        <td class="text-end percent-cell">0%</td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <!-- Submit -->
                                <div class="text-end">
                                    <?php if ($isEdit): ?>
                                        <button type="submit" name="editEvent" class="btn btn-primary">
                                            <i class="fas fa-save me-1"></i> Update Event
                                        </button>
                                    <?php else: ?>
                                        <button type="submit" name="createEvent" class="btn btn-success">
                                            <i class="fas fa-plus-circle me-1"></i> Create Event
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- Bootstrap JS & Sortable.js -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var isEdit = <?php echo $isEdit ? 'true' : 'false'; ?>;
            if (!isEdit) {
                var now = new Date();
                var utcMs = now.getTime() + now.getTimezoneOffset() * 60000;
                var dhaka = new Date(utcMs + 6 * 3600000);
                var yyyy = dhaka.getFullYear();
                var mm = String(dhaka.getMonth() + 1).padStart(2, '0');
                var dd = String(dhaka.getDate()).padStart(2, '0');
                var minDate = yyyy + '-' + mm + '-' + dd;
                ['startDate', 'endDate', 'vivaNoticeDate'].forEach(function(id) {
                    var el = document.getElementById(id);
                    if (el) el.setAttribute('min', minDate);
                });
            }
        });

        new Sortable(document.getElementById('semesterList'), {
            animation: 150,
            handle: '.fa-grip-lines',
            onSort: () => {
                const vals = Array.from(document.querySelectorAll('#semesterList li'))
                    .map(li => li.dataset.value)
                    .join(',');
                document.getElementById('semesterOrder').value = vals;
            }
        });

        // Seat distribution recalc
        (function() {
            const defaults = <?php echo $jsonDefaults; ?>;
            const originalTotal = <?php echo $originalTotal; ?>;
            const inputs = document.querySelectorAll('.distro-input');
            const percells = document.querySelectorAll('.percent-cell');
            const warn = document.getElementById('distroWarning');
            const resetBtn = document.getElementById('resetDistribution');
            const createBtn = document.querySelector('button[name="createEvent"]');
            const editBtn = document.querySelector('button[name="editEvent"]');

            function recalc() {
                let sum = 0;
                inputs.forEach(i => sum += parseInt(i.value, 10) || 0);

                // warning if over
                if (sum > originalTotal) {
                    const over = sum - originalTotal;
                    warn.textContent = `🚨 Exceeded by ${over} seat${over>1?'s':''}!`;
                    warn.style.display = '';
                } else {
                    warn.style.display = 'none';
                }

                // update selected seats display
                document.getElementById('selectedSeats').textContent = sum;

                // update percentages
                inputs.forEach((i, idx) => {
                    const v = parseInt(i.value, 10) || 0;
                    const p = sum ? (v / sum * 100).toFixed(1) : 0;
                    percells[idx].textContent = p + '%';
                });

                // disable if sum === 0 OR sum > originalTotal
                const shouldDisable = (sum === 0 || sum > originalTotal);
                if (createBtn) createBtn.disabled = shouldDisable;
                if (editBtn) editBtn.disabled = shouldDisable;
            }

            inputs.forEach(i => i.addEventListener('input', recalc));
            resetBtn.addEventListener('click', () => {
                inputs.forEach(i => {
                    const did = i.closest('tr').dataset.did;
                    i.value = defaults[did] || 0;
                });
                recalc();
            });

            // initial calculation
            document.addEventListener('DOMContentLoaded', recalc);
        })();
    </script>
</body>

</html>