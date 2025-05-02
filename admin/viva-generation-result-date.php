<?php
include_once '../class-file/SessionManager.php';
$session = SessionStatic::class;
include_once '../class-file/Auth.php';
auth('admin'); // Check if the user is authenticated as an admin

include_once '../class-file/HallSeatAllocationEvent.php';
include_once '../class-file/HallSeatApplication.php';
include_once '../popup-1.php';

if ($session::get('msg1')) {
  showPopup($session::get('msg1'));
  $session::delete('msg1');
}

$hallSeatAllocationEvent = new HallSeatAllocationEvent();
$hallSeatApplication = new HallSeatApplication();

$alreadyPublished = false;
if (isset($_GET['eventId'])) {
  $eventId = $_GET['eventId'];
  $hallSeatAllocationEvent->getByEventAndStatus($eventId);
  if ($hallSeatAllocationEvent->status >= 2) {
    $alreadyPublished = true;
  }
} else {
  echo "<script>window.location.href='hall-seat-allocation-event-management.php';</script>";
  exit;
}

$allApplicationIds = $hallSeatApplication->getApplicationIdsByEventAndStatus($eventId, null, "created", "ASC");
$totalApplications = count($allApplicationIds);

// If the form has been submitted via POST, capture the submitted values.
if (isset($_POST['submitViva'])) {
  $eventId = $_POST['event_id'];
  $savedVivaDates = isset($_POST['viva_date_day']) ? $_POST['viva_date_day'] : array();
  $savedStudents  = isset($_POST['students_day']) ? $_POST['students_day'] : array();
  $savedResultDate = isset($_POST['result_date']) ? $_POST['result_date'] : null;
  $savedResultNotice = isset($_POST['result_notice']) ? $_POST['result_notice'] : null;
  $savedSeatConfirmDeadline = isset($_POST['seat_confirm_deadline']) ? $_POST['seat_confirm_deadline'] : null;

  $hallSeatAllocationEvent->getByEventAndStatus($eventId);
  $hallSeatAllocationEvent->viva_date_list = implode(',', $savedVivaDates);
  $hallSeatAllocationEvent->viva_student_count = implode(',', $savedStudents);
  $hallSeatAllocationEvent->seat_allotment_result_notice_date = $savedResultDate;
  $hallSeatAllocationEvent->seat_allotment_result_notice_text = $savedResultNotice;
  $hallSeatAllocationEvent->seat_confirm_deadline_date = $savedSeatConfirmDeadline;
  $hallSeatAllocationEvent->status = ($hallSeatAllocationEvent->status == 1) ? 2 : $hallSeatAllocationEvent->status; // Update status to 2 if it was 1.
  $hallSeatAllocationEvent->update();

  $hallSeatApplication->updateVivaDetailsByStudentCount($allApplicationIds, $savedVivaDates, $savedStudents);

  $session::set('msg1', 'Viva dates and result publication date have been successfully saved.');
  echo "<script>window.location.href='viva-generation-result-date.php?eventId=$eventId';</script>";
  exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <title>MM Hall</title>

  <link
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css"
    rel="stylesheet" />
  <!-- for sidebar -->
  <link href="../css2/sidebar-admin.css" rel="stylesheet" />


  <style>
    /* Card header styling */
    .card-header {
      background-color: #f8f9fa;
      padding: 15px;
      cursor: pointer;
    }

    .card-header h5,
    .card-header strong {
      margin: 0;
      font-size: 1.1rem;
    }

    .card-body {
      background: #fff;
      padding: 20px;
    }

    /* Custom message card style */
    .message-card {
      border: 1px solid #ddd;
      border-radius: 4px;
      padding: 10px;
      box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
      margin-top: 10px;
      margin-bottom: 10px;
      background-color: #f8f9fa;
      font-size: 14px;
    }

    .message-card.error {
      border-color: #dc3545;
      background-color: #f8d7da;
      color: #721c24;
    }

    .message-card.info {
      border-color: #17a2b8;
      background-color: #d1ecf1;
      color: #0c5460;
    }
  </style>
</head>

<body>
  <div class="container-fluid">
    <div class="row">
      <!-- Sidebar Menu -->
      <?php include 'sidebar-admin.php'; ?>

      <!-- Main Content Area -->
      <main id="mainContent" class="col">
        <!-- Toggle button for sidebar on small screens -->
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

        <!-- Back to event dashboard -->
        <div class="p-4">
          <a href="hall-seat-allocation-event-dashboard.php?eventId=<?php echo $eventId; ?>" class="btn btn-secondary mb-3">Back to Event Dashboard</a>
        </div>

        <div class="p-4">
          <h1>Publish Viva Dates and Result Publication Date With Seat Confirmation Deadline</h1>
          <p>Total Applications: <strong><?php echo $totalApplications; ?></strong></p>
        </div>

        <div class="container-fluid px-4">
          <!-- Card Start: Display Saved Data (shown after submit or if already published) -->
          <?php if ($_SERVER['REQUEST_METHOD'] === 'POST' || $alreadyPublished):
            // If form was submitted, use posted values; otherwise, load from the event.
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
              $displayVivaDates = isset($savedVivaDates) ? $savedVivaDates : array();
              $displayStudents  = isset($savedStudents) ? $savedStudents : array();
              $displayResultDate = isset($savedResultDate) ? $savedResultDate : '';
              $displayResultNotice = isset($savedResultNotice) ? $savedResultNotice : '';
              $displaySeatConfirmDeadline = isset($savedSeatConfirmDeadline) ? $savedSeatConfirmDeadline : '';
            } else {
              // Retrieve from the $hallSeatAllocationEvent object.
              $displayVivaDates = !empty($hallSeatAllocationEvent->viva_date_list) ? explode(',', $hallSeatAllocationEvent->viva_date_list) : array();
              $displayStudents  = !empty($hallSeatAllocationEvent->viva_student_count) ? explode(',', $hallSeatAllocationEvent->viva_student_count) : array();
              $displayResultDate = $hallSeatAllocationEvent->seat_allotment_result_notice_date;
              $displayResultNotice = $hallSeatAllocationEvent->seat_allotment_result_notice_text;
              $displaySeatConfirmDeadline = $hallSeatAllocationEvent->seat_confirm_deadline_date;
            }
          ?>
            <div class="card mb-4">
              <div class="card-header">
                <h5>Currently Viva & Result Details</h5>
              </div>
              <div class="card-body">
                <p><strong>Viva Details:</strong></p>
                <ul>
                  <?php
                  if (!empty($displayVivaDates)) {
                    foreach ($displayVivaDates as $i => $date) {
                      $count = isset($displayStudents[$i]) ? $displayStudents[$i] : 'Not specified';
                      echo "<li>Date " . ($i + 1) . " (" . htmlspecialchars($date) . "): " . htmlspecialchars($count) . " student" . ((intval($count) === 1) ? "" : "s") . " will take part in viva</li>";
                    }
                  } else {
                    echo "<li>None</li>";
                  }
                  ?>
                </ul>
                <p><strong>Result Publication Date:</strong> <?php echo htmlspecialchars($displayResultDate); ?></p>
                <p><strong>Seat Confirmation Deadline Date:</strong> <?php echo htmlspecialchars($displaySeatConfirmDeadline); ?></p>
                <p><strong>Notice:</strong> <?php echo htmlspecialchars($displayResultNotice); ?></p>
              </div>
            </div>
          <?php endif; ?>
          <!-- End Card -->

          <!-- Card Start: Form to enter Viva & Result Details -->
           <?php if ($hallSeatAllocationEvent->status <=3): ?>
          <div class="card mb-4">
            <div class="card-header">
              <h5><?php echo $alreadyPublished ? "Update" : "Enter"; ?> Viva & Result Details</h5>
            </div>
            <div class="card-body">
              <!-- Set action="" to post to the same page -->
              <form id="seatAllotmentForm" action="" method="post" enctype="multipart/form-data">
                <!-- Hidden field for eventId -->
                <input type="hidden" name="event_id" value="<?php echo htmlspecialchars($eventId); ?>" />

                <!-- Viva Details Section (1st Part) -->
                <div class="mb-3">
                  <label for="vivaDays" class="form-label">Number of Viva Days:</label>
                  <input type="number" id="vivaDays" name="viva_days" class="form-control" min="1" placeholder="Enter number of viva days" required>
                </div>
                <!-- Dynamic Viva Fields will be generated here -->
                <div id="vivaDatePickers"></div>
                <!-- Warning for date validation -->
                <div id="dateWarning" class="message-card error" style="display:none;"></div>
                <!-- Display of Student Total will appear here -->
                <div id="studentsTotal" class="message-card info"></div>

                <!-- Viva Result Publication Date Section (2nd Part) -->
                <div class="mb-3">
                  <label for="resultDate" class="form-label">Result Publication Date:</label>
                  <input type="date" id="resultDate" name="result_date" class="form-control" required>
                </div>

                <!-- Seat Confirmation Deadline Date Section -->
                <div class="mb-3">
                  <label for="seatConfirmDeadline" class="form-label">Seat Confirmation Deadline Date:</label>
                  <input type="date" id="seatConfirmDeadline" name="seat_confirm_deadline" class="form-control" required>
                </div>

                <!-- Notice Section (3rd Part) -->
                <div class="mb-3">
                  <label for="resultNotice" class="form-label">Notice for Viva & Result:</label>
                  <input id="resultNotice" name="result_notice" class="form-control" placeholder="Enter notice regarding viva and result publication..." required>
                </div>

                <button type="submit" id="submitBtn" name="submitViva" class="btn btn-primary">Submit</button>
              </form>
            </div>
          </div>
          <!-- End Card -->
        <?php else: ?>
          <div class="alert alert-info" role="alert">
            The viva result and the seat allocation result have already been published. You cannot change the dates or notice anymore.
          </div>
        <?php endif; ?>


        </div>
      </main>
    </div>
  </div>

  <!-- Bootstrap Bundle with Popper -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>


  <!-- JavaScript Libraries -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js" crossorigin="anonymous"></script>
  <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>

  <script>
    $(document).ready(function() {
      // Total applications count from PHP.
      var totalApplications = <?php echo $totalApplications; ?>;

      // On page load, if no dynamic fields exist, show total 0.
      $('#studentsTotal').text('Total Students Assigned: 0 / ' + totalApplications);

      // Helper: get today's date in Asia/Dhaka timezone (formatted as YYYY-MM-DD).
      function getDhakaToday() {
        var dhakaDate = new Date(new Date().toLocaleString("en-US", {
          timeZone: "Asia/Dhaka"
        }));
        return dhakaDate.toISOString().split("T")[0];
      }

      var todayDhaka = getDhakaToday();

      // Distribute student counts equally among viva days.
      function distributeEqually(numDays) {
        var base = Math.floor(totalApplications / numDays);
        var remainder = totalApplications % numDays;
        $('.student-count').each(function(index) {
          var studentCount = base + ((index < remainder) ? 1 : 0);
          $(this).val(studentCount);
        });
        recalcStudentTotal();
      }

      // Update min attributes for all viva date inputs (set each to at least today).
      // Also update the result date's min as one day after the last viva date.
      function updateVivaMinAttributes() {
        $('.viva-date').each(function() {
          $(this).attr('min', todayDhaka);
        });
        var vivaDates = $('.viva-date');
        var lastViva = vivaDates.last().val();
        if (lastViva) {
          $('#resultDate').attr('min', addOneDay(lastViva));

          // ** new: enforce today-only on seat confirmation **
          $('#seatConfirmDeadline').attr('min', addOneDay(lastViva)) // can't pick before today
        } else {
          $('#resultDate').attr('min', todayDhaka);
          $('#seatConfirmDeadline').attr('min', todayDhaka);
        }
      }

      // Helper: add one day to a date string (YYYY-MM-DD).
      function addOneDay(dateString) {
        var dateObj = new Date(dateString);
        dateObj.setDate(dateObj.getDate() + 1);
        var month = '' + (dateObj.getMonth() + 1);
        var day = '' + dateObj.getDate();
        var year = dateObj.getFullYear();
        if (month.length < 2) month = '0' + month;
        if (day.length < 2) day = '0' + day;
        return [year, month, day].join('-');
      }

      // Validate that each viva date is filled and that each is later than its previous date.
      function validateVivaDates() {
        var valid = true;
        var warningMsg = "";
        var prevDate = null;
        $('.viva-date').each(function() {
          var currentVal = $(this).val();
          if (!currentVal) {
            valid = false;
            warningMsg = "Please fill in all viva dates.";
            return false; // break loop
          }
          if (prevDate !== null) {
            if (new Date(currentVal) <= new Date(prevDate)) {
              valid = false;
              warningMsg = "Each viva date must be later than its previous date.";
              return false; // break loop
            }
          }
          prevDate = currentVal;
        });
        if (!valid) {
          $('#dateWarning').text(warningMsg).show();
        } else {
          $('#dateWarning').hide();
        }
        return valid;
      }

      // Recalculate the total of student counts.
      function recalcStudentTotal() {
        var sum = 0;
        $('.student-count').each(function() {
          var val = parseInt($(this).val());
          if (!isNaN(val)) {
            sum += val;
          }
        });
        $('#studentsTotal').text('Total Students Assigned: ' + sum + ' / ' + totalApplications);
        if (sum !== totalApplications) {
          $('#studentsTotal').css('color', '#dc3545');
          $('#submitBtn').prop('disabled', true);
        } else {
          $('#studentsTotal').css('color', '#28a745');
          $('#submitBtn').prop('disabled', false);
        }
      }

      // When the number of viva days is entered, generate dynamic fields.
      $('#vivaDays').on('input', function() {
        var numDays = parseInt($(this).val());
        var vivaContainer = $('#vivaDatePickers');
        vivaContainer.empty();
        if (numDays > 0) {
          for (var i = 1; i <= numDays; i++) {
            var row = $('<div class="row mb-3"></div>');
            var colDate = $('<div class="col-md-6"></div>');
            var labelDate = $('<label class="form-label"></label>').text('Viva Date for Day ' + i + ':');
            var inputDate = $('<input type="date" name="viva_date_day[]" class="form-control viva-date" required>');
            // Prefill default: today's date plus (i - 1) days (in Dhaka time)
            var defaultDate = new Date(new Date().toLocaleString("en-US", {
              timeZone: "Asia/Dhaka"
            }));
            defaultDate.setDate(defaultDate.getDate() + (i - 1));
            inputDate.val(defaultDate.toISOString().split("T")[0]);
            colDate.append(labelDate).append(inputDate);

            var colStudent = $('<div class="col-md-6"></div>');
            var labelStudent = $('<label class="form-label"></label>').text('Number of Students for Day ' + i + ':');
            var inputStudent = $('<input type="number" name="students_day[]" class="form-control student-count" min="0" required>');
            colStudent.append(labelStudent).append(inputStudent);

            row.append(colDate).append(colStudent);
            vivaContainer.append(row);
          }
          // Append the Redistribute Equally button.
          var redistributeBtn = $('<button type="button" id="redistributeBtn" class="btn btn-secondary btn-sm" style="margin-bottom:15px;">Redistribute Equally</button>');
          vivaContainer.append(redistributeBtn);

          distributeEqually(numDays);
          updateVivaMinAttributes();
        }
        validateVivaDates();
        recalcStudentTotal();
      });

      // When any viva date changes, revalidate and update result date min.
      $(document).on('change', '.viva-date', function() {
        updateVivaMinAttributes();
        validateVivaDates();
      });

      // When any student count input changes, recalc total.
      $(document).on('input', '.student-count', function() {
        recalcStudentTotal();
      });

      // Handler for the Redistribute Equally button.
      $(document).on('click', '#redistributeBtn', function() {
        var numDays = parseInt($('#vivaDays').val());
        if (numDays > 0) {
          distributeEqually(numDays);
        }
      });

      // Before form submission, ensure validations pass.
      $('#seatAllotmentForm').on('submit', function(e) {
        if (!validateVivaDates()) {
          e.preventDefault();
          alert("Please ensure all viva dates are filled and each is later than the previous date.");
        }
      });

      // On page load, recalc student total.
      recalcStudentTotal();
    });
  </script>
</body>

</html>