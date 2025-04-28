<?php
include_once '../class-file/SessionManager.php';
$session = SessionStatic::class;
include_once '../class-file/Auth.php';
auth('admin'); 

include_once '../popup-1.php';
if ($session::get("msg1")) {
  showPopup($session::get("msg1"));
  $session::delete("msg1");
}

include_once '../class-file/HallSeatAllocationEvent.php';
include_once '../class-file/Department.php';
$event = new HallSeatAllocationEvent();

if (isset($_GET['eventId'])) {
  $eventId = $_GET['eventId'];
  $event->event_id = $eventId;
  $event->load();
}

$department      = new Department();
$departmentList  = $department->getDepartments();
$yearSemesterCodes = $department->getYearSemesterCodes();
$hallSeatEvent   = new HallSeatAllocationEvent();

// Define status meanings with instructions for the admin.
$statusMeanings = [
  1 => "Application collection completed. Instruction: Publish the viva schedule and result notice date.",
  2 => "Viva sessions are underway. Instruction: Publish the viva results.",
  3 => "Viva results have been reviewed and published. Instruction: Review and publish the seat allocation result.",
  4 => "All processes completed. Goto Seat Confirmation To Confirm Student Seats.",
  5 => "Event is finalized. No further actions are possible."
];

?>



<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <title>Event Management - Dashboard</title>

  <link
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css"
    rel="stylesheet" />
  <!-- for sidebar -->
  <link href="../css2/sidebar-admin.css" rel="stylesheet" />

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

        <!-- Section 1: Event Summary -->
        <div class="container py-4">
          <?php if ($event->event_id): ?>
            <div class="card shadow-sm mb-4">
              <!-- Header: ID, Title & Status Badge -->
              <div class="card-header">
                <div class="d-flex align-items-center">
                  <h4 class="mb-0">#<?php echo $event->event_id; ?> – <?php echo htmlspecialchars($event->title); ?></h4>
                  <span class="badge <?php echo $event->status != 4 ? 'bg-warning text-dark' : 'bg-success'; ?> ms-3">
                    <?php echo $event->status != 5 ? 'Ongoing' : 'Completed'; ?>
                  </span>
                </div>
              </div>

              <!-- Body: Description & Status Text -->
              <div class="card-body">
                <h5 class="card-title">Description</h5>
                <p class="card-text"><?php echo nl2br(htmlspecialchars($event->details)); ?></p>

                <hr>

                <p>
                  <strong>Status:</strong>
                  <?php echo $statusMeanings[$event->status]; ?>
                </p>

                <?php if ($event->status == 4) { ?>
                  <hr>
                  <div class="mb-4">
                    <a
                      href="view-result.php?eventId=<?php echo $event->event_id; ?>&amp;action=view"
                      class="btn btn-info">
                      <i class="fas fa-eye me-1"></i>
                      View Results And Details
                    </a>
                  </div>
                <?php } ?>
              </div>

              <!-- Footer: Back Button -->
              <div class="card-footer text-end">
                <a href="hall-seat-allocation-event-management.php" class="btn btn-secondary">
                  ← Back to Events
                </a>
              </div>
            </div>
          <?php else: ?>
            <div class="alert alert-warning">
              Event not found.
            </div>
          <?php endif; ?>
        </div>

        <!-- Edit Core Data Warning Panel -->
        <div class="p-4 mb-4 bg-warning bg-opacity-10 rounded d-flex flex-column flex-md-row align-items-center">
          <div class="d-flex align-items-center mb-3 mb-md-0">
            <i class="fas fa-exclamation-triangle fa-2x text-warning me-3"></i>
            <div>
              <h6 class="mb-1 fw-bold text-warning">Caution</h6>
              <p class="mb-0">Editing data (Application start / end dates, title, quotas, etc.) will affect all downstream steps. Proceed with care.</p>
            </div>
          </div>
          <a
            href="create-hall-seat-allocation-event.php?editEventid=<?php echo $event->event_id; ?>"
            class="btn btn-lg btn-warning ms-md-auto">
            <i class="fas fa-edit me-2"></i>
            Edit Event
          </a>
        </div>


        <!-- Section 2: Event All Details -->
        <div class="accordion mb-4" id="eventDetailsAccordion">
          <div class="accordion-item">
            <h2 class="accordion-header" id="headingEvent">
              <button
                class="accordion-button collapsed"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#collapseEvent"
                aria-expanded="false"
                aria-controls="collapseEvent">
                View All Details
              </button>
            </h2>
            <div
              id="collapseEvent"
              class="accordion-collapse collapse"
              aria-labelledby="headingEvent"
              data-bs-parent="#eventDetailsAccordion">
              <div class="accordion-body">

                <!-- 1) Dates row -->
                <div class="row g-3 mb-4">
                  <div class="col-md-4">
                    <div class="card bg-light h-100">
                      <div class="card-body p-3">
                        <h6 class="card-title">Application Start</h6>
                        <p class="card-text mb-0">
                          <?php echo ($event->application_start_date == '0000-00-00' || $event->application_start_date == '')
                            ? 'Not yet published'
                            : htmlspecialchars($event->application_start_date); ?>
                        </p>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="card bg-light h-100">
                      <div class="card-body p-3">
                        <h6 class="card-title">Application End</h6>
                        <p class="card-text mb-0">
                          <?php echo ($event->application_end_date == '0000-00-00' || $event->application_end_date == '')
                            ? 'Not yet published'
                            : htmlspecialchars($event->application_end_date); ?>
                        </p>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="card bg-light h-100">
                      <div class="card-body p-3">
                        <h6 class="card-title">Viva Notice Date</h6>
                        <p class="card-text mb-0">
                          <?php echo ($event->viva_notice_date == '0000-00-00' || $event->viva_notice_date == '')
                            ? 'Not yet published'
                            : htmlspecialchars($event->viva_notice_date); ?>
                        </p>
                      </div>
                    </div>
                  </div>
                </div>


                <!-- 2) Viva details -->
                <div class="row g-3 mb-4">
                  <div class="col-md-6">
                    <div class="card h-100">
                      <div class="card-body p-3">
                        <h6 class="card-title">Viva Dates</h6>
                        <p class="card-text small text-muted mb-0">
                          <?php echo htmlspecialchars($event->viva_date_list); ?>
                        </p>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="card h-100">
                      <div class="card-body p-3">
                        <h6 class="card-title">Viva Student Count</h6>
                        <p class="card-text mb-0">
                          <?php echo htmlspecialchars($event->viva_student_count); ?>
                        </p>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- 3) Result notice & confirmation -->
                <div class="row g-3 mb-4">
                  <div class="col-md-6">
                    <div class="card bg-light h-100">
                      <div class="card-body p-3">
                        <h6 class="card-title">Result Notice Date</h6>
                        <p class="card-text mb-1">
                          <?php echo ($event->seat_allotment_result_notice_date == '0000-00-00' || $event->seat_allotment_result_notice_date == '')
                            ? 'Not yet published'
                            : htmlspecialchars($event->seat_allotment_result_notice_date); ?>
                        </p>
                        <p class="card-text small fst-italic">
                          <?php echo nl2br(htmlspecialchars($event->seat_allotment_result_notice_text)); ?>
                        </p>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="card h-100">
                      <div class="card-body p-3">
                        <h6 class="card-title">Seat Confirmation Deadline</h6>
                        <p class="card-text mb-0">
                          <?php echo ($event->seat_confirm_deadline_date == '0000-00-00' || $event->seat_confirm_deadline_date == '')
                            ? 'Not yet published'
                            : htmlspecialchars($event->seat_confirm_deadline_date); ?>
                        </p>
                      </div>
                    </div>
                  </div>
                </div>


                <!-- 4) Scoring Factors as badges -->
                <div class="mb-4">
                  <h6>Scoring Factors</h6>
                  <div class="alert alert-info p-2 mb-3 small">
                    These factors are used to calculate an applicant's score based on their distance from campus, academic results, and father's income.
                    Each value affects the final ranking and can go up to 5 decimal places (e.g., <code>0.12345</code>).
                  </div>
                  <div class="d-flex flex-wrap">
                    <?php
                    $scoreLabels = ['Distance', 'Result', "Father's Income"];
                    foreach (explode(',', $event->scoring_factor) as $i => $value):
                    ?>
                      <span class="badge bg-info text-dark me-2 mb-2">
                        <?php echo htmlspecialchars($scoreLabels[$i] ?? "Factor " . ($i + 1)); ?>: <?php echo htmlspecialchars($value); ?>
                      </span>
                    <?php endforeach; ?>
                  </div>
                </div>


                <!-- Semester Priority -->
                <div class="mb-4">
                  <h6>Semester Priority</h6>
                  <div class="d-flex flex-wrap">
                    <?php foreach (explode(',', $event->semester_priority) as $i => $num): ?>
                      <span class="badge bg-secondary me-2 mb-2">
                        #<?php echo $i + 1; ?> <?php echo $yearSemesterCodes[$num]; ?>
                      </span>
                    <?php endforeach; ?>
                  </div>
                </div>

                <!-- 5) Seat quota table -->
                <div class="mb-4">
                  <h6>Seat Distribution Quota</h6>
                  <div class="alert alert-secondary p-2 mb-3 small">
                    This table shows how the available seats are distributed among departments. The total number of allocated seats is displayed below.
                  </div>

                  <?php
                  $totalSeatCount = 0;
                  $seatRows = [];
                  foreach (explode(',', $event->seat_distribution_quota) as $pair) {
                    list($did, $cnt) = explode('=>', $pair);
                    $dn = '';
                    foreach ($departmentList as $d) {
                      if ($d['department_id'] == $did) {
                        $dn = $d['department_name'];
                        break;
                      }
                    }
                    $cnt = (int)$cnt;
                    $seatRows[] = ['id' => $did, 'name' => $dn, 'count' => $cnt];
                    $totalSeatCount += $cnt;
                  }
                  ?>

                  <div class="table-responsive mb-2">
                    <table class="table table-sm table-bordered mb-0">
                      <thead class="table-light">
                        <tr>
                          <th class="text-center">Dept ID</th>
                          <th>Department Name</th>
                          <th class="text-end">Seats</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php foreach ($seatRows as $row): ?>
                          <tr>
                            <td class="text-center"><?= $row['id'] ?></td>
                            <td><?= htmlspecialchars($row['name']) ?></td>
                            <td class="text-end"><?= $row['count'] ?></td>
                          </tr>
                        <?php endforeach; ?>
                      </tbody>
                    </table>
                  </div>

                  <div class="fw-semibold">
                    ✅ <span class="text-primary">Total Allocated Seats:</span> <?= $totalSeatCount ?>
                  </div>
                </div>


                <!-- 6) Timestamps -->
                <div class="text-muted small">
                  Created: <?php echo htmlspecialchars($event->created); ?><br>
                  Last Modified: <?php echo htmlspecialchars($event->modified); ?>
                </div>

              </div>

            </div>
          </div>
        </div>

        <!-- INGOING PARTS START -->
        <?php if ($event->status < 5): ?>

          <!-- Section 3: Viva generate form -->
          <?php if ($event->status >= 1): ?>
            <div class="accordion mb-4" id="actionAccordion">
              <div class="accordion-item">
                <h2 class="accordion-header" id="headingPublish">
                  <button
                    class="accordion-button collapsed"
                    type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#collapsePublish"
                    aria-expanded="false"
                    aria-controls="collapsePublish">
                    Publish Viva Schedule & Result Notice
                    <strong> | Must publish on <?php echo htmlspecialchars($event->viva_notice_date); ?></strong>
                  </button>
                </h2>
                <div
                  id="collapsePublish"
                  class="accordion-collapse collapse"
                  aria-labelledby="headingPublish"
                  data-bs-parent="#actionAccordion">
                  <div class="accordion-body">
                    <p>
                      Now that application collection is complete, you should publish the viva dates and result notice date.
                    </p>

                    <?php if ($event->status == 1): ?>
                      <a
                        href="viva-generation-result-date.php?eventId=<?php echo $event->event_id; ?>"
                        class="btn btn-primary">
                        Proceed to Schedule & Notice
                      </a>
                    <?php elseif ($event->status == 2 || $event->status == 3): ?>
                      <a
                        href="viva-generation-result-date.php?eventId=<?php echo $event->event_id; ?>&amp;action=reschedule"
                        class="btn btn-warning">
                        Proceed to View & Reschedule
                      </a>
                    <?php else: ?>
                      <div class="alert alert-warning mt-4" role="alert">
                        <strong>✅ Viva Schedule &amp; Result Notice Published. You Can Not Change Them Now.</strong>
                      </div>
                    <?php endif; ?>

                  </div>
                </div>
              </div>
            </div>
          <?php endif; ?>

          <!-- Section 4: Viva taking and publish viva result -->
          <?php if ($event->status >= 2): ?>
            <div class="accordion mb-4" id="vivaAccordion">
              <div class="accordion-item">
                <h2 class="accordion-header" id="headingViva">
                  <button
                    class="accordion-button collapsed"
                    type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#collapseViva"
                    aria-expanded="false"
                    aria-controls="collapseViva">
                    Conduct Viva &amp; Publish Viva Results. <strong>Must publish on <?php echo htmlspecialchars($event->seat_allotment_result_notice_date); ?></strong>
                  </button>
                </h2>
                <div
                  id="collapseViva"
                  class="accordion-collapse collapse"
                  aria-labelledby="headingViva"
                  data-bs-parent="#vivaAccordion">
                  <div class="accordion-body">
                    <p><strong>Scheduled Viva Dates:</strong><br>
                      <?php echo nl2br(htmlspecialchars($event->viva_date_list)); ?>
                    </p>

                    <div class="d-flex flex-wrap gap-2">
                      <?php if ($event->status == 2): ?>
                        <a
                          href="take-viva.php?eventId=<?php echo $event->event_id; ?>"
                          class="btn btn-success">
                          <i class="fas fa-vial me-1"></i>
                          Take Viva
                        </a>
                      <?php endif; ?>
                    </div>

                    <?php if ($event->status == 2): ?>
                      <!-- Warning before the sensitive action -->
                      <div class="alert alert-warning mt-4" role="alert">
                        <strong>⚠️ Warning:</strong> Finalizing and publishing viva results will apply major, irreversible changes.
                      </div>

                      <!-- Separate finalize button -->
                      <div class="mt-2">
                        <a
                          href="publish-viva-result.php?eventId=<?php echo $event->event_id; ?>"
                          class="btn btn-danger btn-lg">
                          <i class="fas fa-flag-checkered me-1"></i>
                          Finalize &amp; Publish Viva Results
                        </a>
                      </div>
                    <?php elseif ($event->status == 3): ?>
                      <!-- Finalize and republish button -->
                      <div class="mt-2">
                        <a
                          href="republish-viva-result.php?eventId=<?php echo $event->event_id; ?>&amp;action=republish"
                          class="btn btn-danger btn-lg">
                          <i class="fas fa-flag-checkered me-1"></i>
                          Finalize &amp; Republish Viva Results
                        </a>
                      </div>
                    <?php else: ?>
                      <!-- Show that the result is already published -->
                      <div class="alert alert-warning mt-4" role="alert">
                        <strong>✅ Viva and Seat Allocation Results Published. You Can Not Change Them Now.</strong>
                      </div>
                    <?php endif; ?>

                  </div>
                </div>
              </div>
            </div>
          <?php endif; ?>


          <!-- Section 5: Publish seat allocation result -->
          <?php if ($event->status >= 3): ?>
            <div class="accordion mb-4" id="seatAccordion">
              <div class="accordion-item">
                <h2 class="accordion-header" id="headingSeat">
                  <button
                    class="accordion-button collapsed"
                    type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#collapseSeat"
                    aria-expanded="false"
                    aria-controls="collapseSeat">
                    Publish Seat Allocation Result. <strong>Must publish on <?php echo htmlspecialchars($event->seat_allotment_result_notice_date); ?></strong>
                  </button>
                </h2>

                <div
                  id="collapseSeat"
                  class="accordion-collapse collapse"
                  aria-labelledby="headingSeat"
                  data-bs-parent="#seatAccordion">
                  <div class="accordion-body">
                    <p><strong>Generate, View &amp; Publish Seat Allocation Result:</strong><br>
                      <?php echo htmlspecialchars($event->seat_allotment_result_notice_date); ?>
                    </p>
                    <div class="d-flex flex-wrap gap-2">
                      <!-- Primary action: publish the result -->
                      <?php if ($event->status == 3): ?>
                        <a
                          href="publish-seat-allocation.php?eventId=<?php echo $event->event_id; ?>"
                          class="btn btn-success">
                          <i class="fas fa-check-circle me-1"></i>
                          Generate &amp; Publish Seat Allocation Result
                        </a>

                      <?php else: ?>
                        <!-- Show the text of already published seat allocation result -->
                        <div class="alert alert-warning mt-2" role="alert">
                          <strong>✅ Seat Allocation Result Published. You Can Not Change It Now.</strong>
                        </div>
                      <?php endif; ?>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          <?php endif; ?>

          <!-- Section 6 : Seat Confirmation Section -->
          <?php if ($event->status == 4): ?>
            <div class="accordion mb-4" id="confirmationAccordion">
              <div class="accordion-item">
                <h2 class="accordion-header" id="headingConfirmation">
                  <button
                    class="accordion-button collapsed"
                    type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#collapseConfirmation"
                    aria-expanded="false"
                    aria-controls="collapseConfirmation">
                    Seat Confirmation | <strong>Must confirm within <?php echo htmlspecialchars($event->seat_confirm_deadline_date); ?></strong>
                  </button>
                </h2>
                <div
                  id="collapseConfirmation"
                  class="accordion-collapse collapse"
                  aria-labelledby="headingConfirmation"
                  data-bs-parent="#confirmationAccordion">
                  <div class="accordion-body">
                    <p><strong>Seat Confirmation Deadline:</strong><br>
                      <?php echo htmlspecialchars($event->seat_confirm_deadline_date); ?>
                    </p>
                    <div class="d-flex flex-wrap gap-2">
                      <!-- Primary action: publish the result -->
                      <?php if ($event->status == 4): ?>
                        <a
                          href="view-confirm-seat.php?eventId=<?php echo $event->event_id; ?>&amp;action=view"
                          class="btn btn-info">
                          <i class="fas fa-eye me-1"></i>
                          Manage &amp; Confirm Seat
                        </a>
                      <?php else: ?>
                        <!-- Show the text of already published seat allocation result -->
                        <div class="alert alert-warning mt-2" role="alert">
                          <strong>✅ Seat Allocation Result Published. You Can Not Change It Now.</strong>
                        </div>
                      <?php endif; ?>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          <?php endif; ?>


          <!-- Section 7: Finalize event by freeing seats with warning -->
          <?php if ($event->status == 4): ?>
            <div class="accordion mb-4" id="finalizeAccordion">
              <div class="accordion-item">
                <h2 class="accordion-header" id="headingFinalize">
                  <button
                    class="accordion-button collapsed"
                    type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#collapseFinalize"
                    aria-expanded="false"
                    aria-controls="collapseFinalize">
                    Finalize Event &amp; Free Remaining Seats
                  </button>
                </h2>
                <div
                  id="collapseFinalize"
                  class="accordion-collapse collapse"
                  aria-labelledby="headingFinalize"
                  data-bs-parent="#finalizeAccordion">
                  <div class="accordion-body">
                    <p><strong>Finalizing the event will free up any remaining seats for future events.</strong></p>
                    <p>This action is irreversible. Proceed with caution.</p>
                    <div class="d-flex flex-wrap gap-2">
                      <a
                        href="finalize-event.php?eventId=<?php echo $event->event_id; ?>"
                        class="btn btn-danger">
                        <i class="fas fa-flag-checkered me-1"></i>
                        Finalize Event
                      </a>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          <?php endif; ?>
          <!-- INGOING PARTS END -->

        <?php else: ?>
          <div class="alert alert-secondary mb-4" role="alert">
            <strong>✅ Event is already finalized. No further actions are possible.</strong>
          </div>
          <!-- View all details and results link -->
          <div class="mb-4">
            <a
              href="view-result.php?eventId=<?php echo $event->event_id; ?>&amp;action=view"
              class="btn btn-info">
              <i class="fas fa-eye me-1"></i>
              View Results and overview
            </a>
          </div>
        <?php endif; ?>

      </main>
    </div>
  </div>

  <!-- Bootstrap Bundle with Popper -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>


</body>

</html>