<?php
include_once '../class-file/SessionManager.php';
$session = SessionStatic::class;
include_once '../class-file/Auth.php';
auth('admin'); // Check if the user is authenticated as an admin

include_once '../class-file/User.php';
include_once '../class-file/UserDetails.php';
include_once '../class-file/FileManager.php';
include_once '../class-file/HallSeatApplication.php';
include_once '../class-file/Department.php';
include_once '../popup-1.php';

if ($session::get("msg1")) {
    showPopup($session::get("msg1"));
    $session::delete("msg1");
}

// Load all applications for the given event (status 1: pending/approved as required)
$hallSeatApplication = new HallSeatApplication();
$department = new Department();
$departmentList = $department->getDepartments();
$yearSemesterCodes = $department->getYearSemesterCodes();

$user = new User();
$userDetails = new UserDetails();
$file = new FileManager();
$file2 = new FileManager();

if (isset($_POST['approve'])) {
    $applicationId = $_POST['application_id'];
    $eventId = $_POST['event_id'];
    $result = $hallSeatApplication->updateStatus($applicationId, -2);
    if ($result) {
        $session::set("msg1", "Application approved successfully. Application ID: $applicationId");
    } else {
        $session::set("msg1", "Failed to approve application. Application ID: $applicationId");
    }
    echo "<script>window.location.href='take-viva.php?eventId=$eventId';</script>";
    exit;
} elseif (isset($_POST['reject'])) {
    $applicationId = $_POST['application_id'];
    $eventId = $_POST['event_id'];
    $result = $hallSeatApplication->updateStatus($applicationId, -3);
    if ($result) {
        $session::set("msg1", "Application rejected successfully. Application ID: $applicationId");
    } else {
        $session::set("msg1", "Failed to reject application. Application ID: $applicationId");
    }
    echo "<script>window.location.href='take-viva.php?eventId=$eventId';</script>";
    exit;
}

$eventId = null; // Initialize eventId to null
if (isset($_GET['eventId'])) {
    $eventId = $_GET['eventId'];
} else {
    $session::set("msg1", "Event ID not provided.");
    echo "<script>window.location.href='hall-seat-allocation-event-management.php';</script>";
    exit;
}

// Get applications for the given event.
$allApplications = $hallSeatApplication->getApplicationsByUserIdEventStatus(null, $eventId, [1]);
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

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css" rel="stylesheet" />
    <link href="../css/Dashboard/dashboard.css" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <style>
        /* Enhanced Search Field Styling */
        #searchContainer {
            margin: 20px 0;
        }

        #searchContainer .form-control {
            max-width: 250px;
        }

        /* Accordion styling */
        .accordion-item.faq-item {
            border: 1px solid #e5e5e5;
            border-radius: 5px;
            margin-bottom: 15px;
            background-color: #fff;
        }

        .card-header.custom-header {
            background-color: #f8f9fa;
            padding: 15px 20px;
            cursor: pointer;
            border-bottom: 1px solid #e5e5e5;
        }

        .card-header.custom-header .header-info {
            flex: 1;
        }

        .card-header.custom-header .action-buttons button {
            margin-left: 5px;
        }

        .accordion-body {
            padding: 25px 20px;
            /* Increased padding for better spacing */
        }

        /* Pagination styling using Bootstrap's pagination */
        #paginationContainer {
            margin-top: 20px;
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

                <!-- back to event dashboard -->
                <div class="mb-4 mt-4">
                    <a href="hall-seat-allocation-event-dashboard.php?eventId=<?= htmlspecialchars($eventId) ?>"
                        class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-1"></i>
                        Back to Event Dashboard
                    </a>
                </div>

                <main>
                    <div class="container-fluid px-4">
                        <h3 class="text-center mt-4 mb-4">Viva (Review User Applications)</h3>

                        <!-- Enhanced Search Options inside a Card -->
                        <div id="searchContainer" class="container mb-4">
                            <div class="card shadow-sm">
                                <div class="card-header bg-primary text-white">
                                    <h4 class="mb-0">Search</h4>
                                </div>
                                <div class="card-body">
                                    <div class="row g-3">
                                        <!-- Row 1: Application ID and User ID -->
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="searchApplicationId" class="form-label">Application ID</label>
                                                <input type="text" id="searchApplicationId" class="form-control" placeholder="Enter Application ID">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="searchUserId" class="form-label">User ID</label>
                                                <input type="text" id="searchUserId" class="form-control" placeholder="Enter User ID">
                                            </div>
                                        </div>
                                        <!-- Row 2: Student ID and Email -->
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="searchStudentId" class="form-label">Student ID</label>
                                                <input type="text" id="searchStudentId" class="form-control" placeholder="Enter Student ID">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="searchEmail" class="form-label">Email</label>
                                                <input type="text" id="searchEmail" class="form-control" placeholder="Enter Email">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Accordion with User List -->
                        <div class="accordion" id="faqAccordion">
                            <?php
                            if ($allApplications && count($allApplications) > 0) {
                                foreach ($allApplications as $app) {
                                    $hallSeatApplication->setProperties($app);
                                    $applicationId = $hallSeatApplication->application_id;

                                    // Load user details by details id.
                                    $userDetails->getByDetailsId($hallSeatApplication->user_details_id);
                                    $user->user_id = $userDetails->user_id;
                                    $user->load();

                                    $file->loadByFileId($userDetails->profile_picture_id);
                                    $file2->loadByFileId($userDetails->document_id);

                                    $collapseId = "collapse{$applicationId}";
                            ?>
                                    <div class="accordion-item faq-item" data-applicationid="<?php echo $applicationId; ?>"
                                        data-userid="<?php echo $userDetails->user_id; ?>"
                                        data-studentid="<?php echo $userDetails->student_id; ?>"
                                        data-email="<?php echo htmlspecialchars($user->email); ?>">
                                        <!-- Header: First row -->
                                        <div class="row p-3">
                                            <div class="col-lg-3 d-flex align-items-center">
                                                <p class="mb-0"><strong>Application ID:</strong> <?php echo $applicationId; ?></p>
                                            </div>
                                            <div class="col-lg-3 d-flex align-items-center">
                                                <p class="mb-0"><strong>Student ID:</strong> <?php echo $userDetails->student_id; ?></p>
                                            </div>
                                            <div class="col-lg-3 d-flex align-items-center">
                                                <p class="mb-0"><strong>Viva Date:</strong> <?php echo $hallSeatApplication->viva_date ? htmlspecialchars($hallSeatApplication->viva_date) : 'Not Set'; ?></p>
                                            </div>
                                            <div class="col-lg-3 d-flex align-items-center">
                                                <button class="btn btn-primary btn-sm" data-bs-toggle="collapse" data-bs-target="#<?php echo $collapseId; ?>">Details</button>
                                            </div>
                                        </div>
                                        <!-- Header: Second row with action buttons -->
                                        <div class="row ps-3 pe-3 pb-3">
                                            <div class="col-lg-12 d-flex justify-content-end">
                                                <form action="" method="post" enctype="multipart/form-data">
                                                    <input type="hidden" name="application_id" value="<?php echo $applicationId; ?>">
                                                    <input type="hidden" name="event_id" value="<?php echo $eventId; ?>">
                                                    <button type="submit" name="approve" class="btn btn-success btn-sm me-2">Approve</button>
                                                    <button type="submit" name="reject" class="btn btn-danger btn-sm">Reject</button>
                                                </form>
                                            </div>
                                        </div>
                                        <!-- Accordion Body -->
                                        <div id="<?php echo $collapseId; ?>" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                            <div class="accordion-body p-3">
                                                <!-- Row 1: Profile Image (Large Size) -->
                                                <div class="row mb-3">
                                                    <div class="col-md-12 text-center">
                                                        <div class="profile-wrap mb-3">
                                                            <img src="../uploads1/<?php echo $file->file_new_name; ?>" alt="User Image" class="img-fluid rounded" style="width: 400px; height: 400px; object-fit: cover;">
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Part 1: Personal & Location Details -->
                                                <div class="row mb-4">
                                                    <div class="col-md-6">
                                                        <p class="mb-1"><strong>Name:</strong> <?= htmlspecialchars($userDetails->full_name) ?></p>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <p class="mb-1"><strong>Email:</strong> <?= htmlspecialchars($user->email) ?></p>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <p class="mb-1"><strong>Gender:</strong> <?= isset($userDetails->gender) ? htmlspecialchars($userDetails->gender) : 'N/A' ?></p>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <p class="mb-1"><strong>Contact No:</strong> <?= isset($userDetails->contact_no) ? htmlspecialchars($userDetails->contact_no) : 'N/A' ?></p>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <p class="mb-1"><strong>District:</strong> <?= isset($userDetails->district) ? htmlspecialchars($userDetails->district) : 'N/A' ?></p>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <p class="mb-1"><strong>Division:</strong> <?= isset($userDetails->division) ? htmlspecialchars($userDetails->division) : 'N/A' ?></p>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <p class="mb-1"><strong>Permanent Address:</strong> <?= isset($userDetails->permanent_address) ? htmlspecialchars($userDetails->permanent_address) : 'N/A' ?></p>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <p class="mb-1"><strong>Present Address:</strong> <?= isset($userDetails->present_address) ? htmlspecialchars($userDetails->present_address) : 'N/A' ?></p>
                                                    </div>
                                                </div>

                                                <hr>

                                                <!-- Part 2: Academic Details -->
                                                <div class="row mb-4">
                                                    <div class="col-md-6">
                                                        <h6 class="mb-3">Academic Details</h6>
                                                        <p class="mb-1"><strong>Session:</strong> <?= isset($userDetails->session) ? htmlspecialchars($userDetails->session) : 'N/A' ?></p>
                                                        <p class="mb-1"><strong>Academic Status:</strong> <?= isset($userDetails->year_semester_code) ? htmlspecialchars($yearSemesterCodes[$userDetails->year_semester_code]) : 'N/A' ?></p>
                                                        <p class="mb-1"><strong>Student ID:</strong> <?= htmlspecialchars($userDetails->student_id) ?></p>
                                                        <p class="mb-1"><strong>Last Semester CGPA/Merit:</strong> <?= isset($userDetails->last_semester_cgpa_or_merit) ? htmlspecialchars($userDetails->last_semester_cgpa_or_merit) : 'N/A' ?></p>
                                                        <p class="mb-1"><strong>Department:</strong>
                                                            <?php
                                                            $deptName = 'N/A';
                                                            if (isset($userDetails->department_id)) {
                                                                foreach ($departmentList as $d) {
                                                                    if ($d['department_id'] == $userDetails->department_id) {
                                                                        $deptName = htmlspecialchars($d['department_name']);
                                                                        break;
                                                                    }
                                                                }
                                                            }
                                                            echo $deptName;
                                                            ?>
                                                        </p>
                                                    </div>
                                                </div>



                                                <hr>

                                                <!-- Parental Information -->
                                                <div class="row mb-4">
                                                    <div class="col-md-4">
                                                        <h6 class="mb-3">Father's Information</h6>
                                                        <p class="mb-1"><strong>Name:</strong> <?php echo isset($userDetails->father_name) ? htmlspecialchars($userDetails->father_name) : 'N/A'; ?></p>
                                                        <p class="mb-1"><strong>Contact:</strong> <?php echo isset($userDetails->father_contact_no) ? htmlspecialchars($userDetails->father_contact_no) : 'N/A'; ?></p>
                                                        <p class="mb-1"><strong>Profession:</strong> <?php echo isset($userDetails->father_profession) ? htmlspecialchars($userDetails->father_profession) : 'N/A'; ?></p>
                                                        <p class="mb-1"><strong>Monthly Income:</strong> <?php echo isset($userDetails->father_monthly_income) ? htmlspecialchars($userDetails->father_monthly_income) : 'N/A'; ?></p>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <h6 class="mb-3">Mother's Information</h6>
                                                        <p class="mb-1"><strong>Name:</strong> <?php echo isset($userDetails->mother_name) ? htmlspecialchars($userDetails->mother_name) : 'N/A'; ?></p>
                                                        <p class="mb-1"><strong>Contact:</strong> <?php echo isset($userDetails->mother_contact_no) ? htmlspecialchars($userDetails->mother_contact_no) : 'N/A'; ?></p>
                                                        <p class="mb-1"><strong>Profession:</strong> <?php echo isset($userDetails->mother_profession) ? htmlspecialchars($userDetails->mother_profession) : 'N/A'; ?></p>
                                                        <p class="mb-1"><strong>Monthly Income:</strong> <?php echo isset($userDetails->mother_monthly_income) ? htmlspecialchars($userDetails->mother_monthly_income) : 'N/A'; ?></p>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <h6 class="mb-3">Guardian's Information</h6>
                                                        <p class="mb-1"><strong>Name:</strong> <?php echo isset($userDetails->guardian_name) ? htmlspecialchars($userDetails->guardian_name) : 'N/A'; ?></p>
                                                        <p class="mb-1"><strong>Contact:</strong> <?php echo isset($userDetails->guardian_contact_no) ? htmlspecialchars($userDetails->guardian_contact_no) : 'N/A'; ?></p>
                                                        <p class="mb-1"><strong>Address:</strong> <?php echo isset($userDetails->guardian_address) ? htmlspecialchars($userDetails->guardian_address) : 'N/A'; ?></p>
                                                    </div>
                                                </div>

                                                <hr>

                                                <!-- Other Information -->
                                                <div class="row mb-3">
                                                    <div class="col-md-4">
                                                        <h6 class="mb-3">Other Information</h6>
                                                        <p class="mb-1">
                                                            <strong>Document File:</strong>
                                                            <a href="../uploads1/<?php echo isset($userDetails->document_id) ? htmlspecialchars($file2->file_new_name) : '0.jpg'; ?>" target="_blank">Click to view</a>
                                                        </p>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <p class="mb-1"><strong>Created:</strong> <?php echo isset($userDetails->created) ? htmlspecialchars($userDetails->created) : 'N/A'; ?></p>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <p class="mb-1"><strong>Modified:</strong> <?php echo isset($userDetails->modified) ? htmlspecialchars($userDetails->modified) : 'N/A'; ?></p>
                                                    </div>
                                                </div>

                                                <!-- Close Button -->
                                                <div class="text-center mt-4">
                                                    <button class="btn btn-outline-secondary btn-sm" data-bs-toggle="collapse" data-bs-target="#<?php echo $collapseId; ?>">Close Details</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                            <?php
                                }
                            } else {
                                echo "<p>No applications found.</p>";
                            }
                            ?>
                        </div>

                        <!-- Pagination Controls (placed after the user list) -->
                        <nav aria-label="User list pagination">
                            <ul class="pagination justify-content-center" id="paginationContainer"></ul>
                        </nav>
                    </div>
                </main>
        </div>
    </div>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>


    <!-- JS Libraries -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" crossorigin="anonymous"></script>
    <script src="script.js"></script>

    <!-- JavaScript for Search Filtering and Pagination -->
    <script>
        let currentPage = 1;
        const itemsPerPage = 5;

        function filterItems() {
            const searchApplicationId = document.getElementById('searchApplicationId').value.trim();
            const searchUserId = document.getElementById('searchUserId').value.trim();
            const searchStudentId = document.getElementById('searchStudentId').value.trim();
            const searchEmail = document.getElementById('searchEmail').value.trim().toLowerCase();

            const items = document.querySelectorAll('.accordion-item.faq-item');

            items.forEach(function(item) {
                const itemAppId = item.getAttribute('data-applicationid');
                const itemUserId = item.getAttribute('data-userid');
                const itemStudentId = item.getAttribute('data-studentid');
                const itemEmail = item.getAttribute('data-email').toLowerCase();

                let match = true;
                if (searchApplicationId !== '' && itemAppId.indexOf(searchApplicationId) === -1) {
                    match = false;
                }
                if (searchUserId !== '' && itemUserId.indexOf(searchUserId) === -1) {
                    match = false;
                }
                if (searchStudentId !== '' && itemStudentId.indexOf(searchStudentId) === -1) {
                    match = false;
                }
                if (searchEmail !== '' && itemEmail.indexOf(searchEmail) === -1) {
                    match = false;
                }
                item.setAttribute('data-match', match ? 'true' : 'false');
            });
            currentPage = 1;
            paginateItems();
        }

        function paginateItems() {
            // Get all accordion items.
            const items = Array.from(document.querySelectorAll('.accordion-item.faq-item'));

            // Hide all items initially.
            items.forEach(item => item.style.display = 'none');

            // Filter items that match the search criteria.
            const visibleItems = items.filter(item => item.getAttribute('data-match') === 'true');
            const totalPages = Math.ceil(visibleItems.length / itemsPerPage);

            // Calculate start and end index for the current page.
            const startIndex = (currentPage - 1) * itemsPerPage;
            const endIndex = startIndex + itemsPerPage;

            // Show only items for the current page.
            visibleItems.slice(startIndex, endIndex).forEach(item => item.style.display = '');

            // Build Bootstrap pagination controls.
            const paginationContainer = document.getElementById('paginationContainer');
            paginationContainer.innerHTML = '';

            if (totalPages > 1) {
                let paginationHTML = '';

                // Previous button.
                paginationHTML += `<li class="page-item ${currentPage === 1 ? 'disabled' : ''}">
                    <a class="page-link" href="#" aria-label="Previous" onclick="changePage(${currentPage - 1}); return false;">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                </li>`;

                // Page number buttons.
                for (let i = 1; i <= totalPages; i++) {
                    paginationHTML += `<li class="page-item ${i === currentPage ? 'active' : ''}">
                        <a class="page-link" href="#" onclick="changePage(${i}); return false;">${i}</a>
                    </li>`;
                }

                // Next button.
                paginationHTML += `<li class="page-item ${currentPage === totalPages ? 'disabled' : ''}">
                    <a class="page-link" href="#" aria-label="Next" onclick="changePage(${currentPage + 1}); return false;">
                        <span aria-hidden="true">&raquo;</span>
                    </a>
                </li>`;

                paginationContainer.innerHTML = paginationHTML;
            }
        }

        function changePage(page) {
            currentPage = page;
            paginateItems();
        }

        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('searchApplicationId').addEventListener('input', filterItems);
            document.getElementById('searchUserId').addEventListener('input', filterItems);
            document.getElementById('searchStudentId').addEventListener('input', filterItems);
            document.getElementById('searchEmail').addEventListener('input', filterItems);
            filterItems(); // Initialize on page load.
        });
    </script>
</body>

</html>