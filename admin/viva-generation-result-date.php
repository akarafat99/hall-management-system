<?php
include_once '../class-file/HallSeatApplication.php';
$hallSeatApplication = new HallSeatApplication();

if (isset($_GET['eventId'])) {
    $eventId = $_GET['eventId'];
} else {
    echo "<script>window.location.href='hall-seat-allocation-event-management.php';</script>";
    exit;
}

// In this example, totalApplications is set to 100.
$totalApplications = 100;

// If the form has been submitted via POST, capture the submitted values.
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $savedVivaDates = isset($_POST['viva_date_day']) ? $_POST['viva_date_day'] : array();
    $savedStudents  = isset($_POST['students_day']) ? $_POST['students_day'] : array();
    $savedResultDate = isset($_POST['result_date']) ? $_POST['result_date'] : '';
    $savedResultNotice = isset($_POST['result_notice']) ? $_POST['result_notice'] : '';
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
<body class="sb-nav-fixed">
  <div id="layoutSidenav">
    <?php include 'admin-sidebar.php'; ?>
    <div id="layoutSidenav_content">
      <main>
        <div class="container-fluid px-4">
          <!-- Card Start: Form to enter Viva & Result Details -->
          <div class="card mb-4">
            <div class="card-header">
              <h5>Publish Seat Allotment Result</h5>
            </div>
            <div class="card-body">
              <!-- Set action="" to post to the same page -->
              <form id="seatAllotmentForm" action="" method="post">
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
                
                <!-- Result Publication Date Section (2nd Part) -->
                <div class="mb-3">
                  <label for="resultDate" class="form-label">Result Publication Date:</label>
                  <input type="date" id="resultDate" name="result_date" class="form-control" required>
                </div>
                
                <!-- Notice Section (3rd Part) -->
                <div class="mb-3">
                  <label for="resultNotice" class="form-label">Notice for Viva & Result:</label>
                  <textarea id="resultNotice" name="result_notice" class="form-control" rows="3" placeholder="Enter notice regarding viva and result publication..." required></textarea>
                </div>
                
                <button type="submit" id="submitBtn" class="btn btn-primary">Submit</button>
              </form>
            </div>
          </div>
          <!-- End Card -->
          
          <!-- Card Start: Display Saved Data (only shown after submit) -->
          <?php if ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
          <div class="card mb-4">
            <div class="card-header">
              <h5>Saved Viva & Result Details</h5>
            </div>
            <div class="card-body">
              <p><strong>Viva Dates:</strong></p>
              <ul>
                <?php
                  if (!empty($savedVivaDates)) {
                    foreach ($savedVivaDates as $date) {
                      echo "<li>" . htmlspecialchars($date) . "</li>";
                    }
                  } else {
                    echo "<li>None</li>";
                  }
                ?>
              </ul>
              <p><strong>Student Counts:</strong></p>
              <ul>
                <?php
                  if (!empty($savedStudents)) {
                    foreach ($savedStudents as $count) {
                      echo "<li>" . htmlspecialchars($count) . "</li>";
                    }
                  } else {
                    echo "<li>None</li>";
                  }
                ?>
              </ul>
              <p><strong>Result Publication Date:</strong> <?php echo htmlspecialchars($savedResultDate); ?></p>
              <p><strong>Notice:</strong> <?php echo htmlspecialchars($savedResultNotice); ?></p>
            </div>
          </div>
          <?php endif; ?>
          <!-- End Card -->
          
        </div>
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
  <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
  
  <script>
    $(document).ready(function() {
      // Total applications count from PHP.
      var totalApplications = <?php echo $totalApplications; ?>;
      
      // On page load, if no dynamic fields exist, show total 0.
      $('#studentsTotal').text('Total Students Assigned: 0 / ' + totalApplications);
      
      // Helper: get today's date in Asia/Dhaka timezone (formatted as YYYY-MM-DD).
      function getDhakaToday() {
        var dhakaDate = new Date(new Date().toLocaleString("en-US", { timeZone: "Asia/Dhaka" }));
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
        } else {
          $('#resultDate').attr('min', todayDhaka);
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
            var defaultDate = new Date(new Date().toLocaleString("en-US", { timeZone: "Asia/Dhaka" }));
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
          // Append only the Redistribute Equally button.
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
      
      // On page load, recalc student total (shows 0 if no viva fields exist).
      recalcStudentTotal();
    });
  </script>
</body>
</html>
