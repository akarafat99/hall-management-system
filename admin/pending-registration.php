<?php
include_once '../class-file/SessionManager.php';
$session = SessionStatic::class;

include_once '../class-file/Auth.php';
auth('admin');

include_once '../popup-1.php';
if ($session::get('msg1') != null) {
    showPopup($session::get('msg1'));
    $session::delete('msg1');
}


include_once '../class-file/User.php';
include_once '../class-file/UserDetails.php';
include_once '../class-file/FileManager.php';
include_once '../class-file/Department.php';

// Process approve/decline actions.
if (isset($_POST['approve']) || isset($_POST['decline'])) {

    $user_id = $_POST['approve'] ?? $_POST['decline'];
    $status = isset($_POST['approve']) ? 1 : -1; // 1 for approve, -1 for decline

    $user2 = new User();
    $user2->updateStatus($user_id, $status);

    $userDetails2 = new UserDetails();
    $userDetails2->getUsers($user_id, null, 0);
    $userDetails2->status = $status;
    $userDetails2->update();

    if ($status == 1) {
        showPopup("User ID " . $user_id . " (Student ID " . $userDetails2->student_id . ") approved successfully.");
    } else {
        showPopup("User ID " . $user_id . " (Student ID " . $userDetails2->student_id . ") declined successfully.");
    }
}

$user = new User();
$userList = $user->getDistinctUsersByStatus(0, "user"); // Get all pending users
$department = new Department();
$departmentList = $department->getDepartments(); // Get all departments
$yearSemesterCode = $department->getYearSemesterCodes(); // Get all year-semester codes
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>MM HALL</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css"
        rel="stylesheet" />
    <!-- for sidebar -->
    <link href="../css2/sidebar-admin.css" rel="stylesheet" />

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <link href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <style>
        /* Enhanced Search Field Styling */
        #searchContainer {
            margin: 20px 0;
        }

        #searchContainer .form-control {
            max-width: 250px;
        }

        /* Accordion and detail styling */
        .accordion-item {
            border: none;
        }

        .faq-heading {
            background: #f8f9fa;
            padding: 10px;
            border-radius: 10px;
            margin-bottom: 10px;
        }

        .faq-heading p {
            font-weight: 600;
            margin: 0;
            color: black;
        }

        .faq-item {
            border: 1px solid #e5e5e5 !important;
            border-radius: 5px;
            margin-bottom: 5px;
            padding: 10px;
        }

        .profile-wrap {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            overflow: hidden;
        }

        .accordion-body {
            padding-top: 24px !important;
            padding-bottom: 24px !important;
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

                <main>
                    <div class="container-fluid px-4">
                        <div class="card__wrapper">
                            <div class="card__title-wrap mt-4 mb-20">
                                <h3 class="table__heading-title">Review Student Account Registration</h3>
                            </div>

                            <!-- Enhanced Search Options -->
                            <div id="searchContainer" class="container">
                                <div class="row g-3 align-items-center">
                                    <div class="col-auto">
                                        <label for="searchUserId" class="col-form-label">User ID</label>
                                    </div>
                                    <div class="col-auto">
                                        <input type="text" id="searchUserId" class="form-control" placeholder="Enter User ID" />
                                    </div>
                                    <div class="col-auto">
                                        <label for="searchStudentId" class="col-form-label">Student ID</label>
                                    </div>
                                    <div class="col-auto">
                                        <input type="text" id="searchStudentId" class="form-control" placeholder="Enter Student ID" />
                                    </div>
                                    <div class="col-auto">
                                        <label for="searchEmail" class="col-form-label">Email</label>
                                    </div>
                                    <div class="col-auto">
                                        <input type="text" id="searchEmail" class="form-control" placeholder="Enter Email" />
                                    </div>
                                </div>
                            </div>

                            <!-- Accordion with User List -->
                            <div class="accordion" id="faqAccordion">
                                <div class="faq-heading">
                                    <div class="row">
                                        <div class="col-lg-2">
                                            <p>User ID</p>
                                        </div>
                                        <div class="col-lg-2">
                                            <p>Student ID</p>
                                        </div>
                                        <div class="col-lg-3">
                                            <p>Email</p>
                                        </div>
                                        <div class="col-lg-2">
                                            <p>Status</p>
                                        </div>
                                        <div class="col-lg-3">
                                            <p>Action</p>
                                        </div>
                                    </div>
                                </div>

                                <?php
                                if ($userList && count($userList) > 0) {
                                    foreach ($userList as $userItem) {
                                        $userId = $userItem['user_id'];
                                        $userEmail = $userItem['email'];
                                        // Create a new instance of UserDetails and load details for this user.
                                        $userDetails = new UserDetails();
                                        $userDetails->getUsers($userId, null, 0);

                                        $department->getDepartments($userDetails->department_id);

                                        $file = new FileManager();
                                        $file->loadByFileId($userDetails->profile_picture_id);

                                        $file2 = new FileManager();
                                        $file2->loadByFileId($userDetails->document_id);

                                        $collapseId = "collapse{$userId}";
                                ?>
                                        <div class="accordion-item faq-item"
                                            data-userid="<?php echo $userDetails->user_id; ?>"
                                            data-studentid="<?php echo $userDetails->student_id; ?>"
                                            data-email="<?php echo htmlspecialchars($userEmail); ?>">
                                            <div class="row">
                                                <div class="col-lg-2 d-flex align-items-center">
                                                    <p><?php echo $userDetails->user_id; ?></p>
                                                </div>
                                                <div class="col-lg-2 d-flex align-items-center">
                                                    <p><?php echo $userDetails->student_id; ?></p>
                                                </div>
                                                <div class="col-lg-3 d-flex align-items-center">
                                                    <p><?php echo htmlspecialchars($userEmail); ?></p>
                                                </div>
                                                <div class="col-lg-2 d-flex align-items-center">
                                                    <button class="btn btn-primary" data-bs-toggle="collapse"
                                                        data-bs-target="#<?php echo $collapseId; ?>">Details</button>
                                                </div>
                                                <div class="col-lg-3 d-flex align-items-center">
                                                    <form action="" method="post">
                                                        <button type="submit" name="approve"
                                                            value="<?php echo htmlspecialchars($userId); ?>"
                                                            class="btn btn-success">Approve</button>
                                                        <button type="submit" name="decline"
                                                            value="<?php echo htmlspecialchars($userId); ?>"
                                                            class="btn btn-danger">Decline</button>
                                                    </form>
                                                </div>
                                            </div>
                                            <div id="<?php echo $collapseId; ?>" class="accordion-collapse collapse"
                                                data-bs-parent="#faqAccordion">
                                                <div class="accordion-body">
                                                    <div class="profile-info-flex">
                                                        <div class="profile-wrap">
                                                            <img src="../uploads1/<?php echo $file->file_new_name; ?>" alt="User Image" class="img-fluid">
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-lg-4">
                                                            <p><strong>Name:</strong> <?php echo htmlspecialchars($userDetails->full_name); ?></p>
                                                        </div>
                                                        <div class="col-lg-4">
                                                            <p><strong>Gender:</strong> <?php echo isset($userDetails->gender) ? htmlspecialchars($userDetails->gender) : 'N/A'; ?></p>
                                                        </div>
                                                        <div class="col-lg-4">
                                                            <p><strong>Contact No:</strong> <?php echo isset($userDetails->contact_no) ? htmlspecialchars($userDetails->contact_no) : 'N/A'; ?></p>
                                                        </div>
                                                    </div>
                                                    <div class="row pt-4">
                                                        <div class="col-lg-4">
                                                            <p><strong>Student ID:</strong> <?php echo htmlspecialchars($userDetails->student_id); ?></p>
                                                        </div>
                                                        <div class="col-lg-4">
                                                            <p><strong>Session:</strong> <?php echo isset($userDetails->session) ? htmlspecialchars($userDetails->session) : 'N/A'; ?></p>
                                                        </div>
                                                        <div class="col-lg-4">
                                                            <p><strong>Department:</strong> <?php echo isset($userDetails->department_id) ? htmlspecialchars($department->department_name) : 'N/A'; ?></p>
                                                        </div>
                                                    </div>

                                                    <div class="row pt-4">
                                                        <div class="col-lg-4">
                                                            <p><strong>Academic Status:</strong> <?php echo $yearSemesterCode[$userDetails->year_semester_code]; ?></p>
                                                        </div>
                                                    </div>

                                                    <div class="row pt-4">
                                                        <div class="col-lg-4">
                                                            <p><strong>Last Semester CGPA/Merit:</strong> <?php echo isset($userDetails->last_semester_cgpa_or_merit) ? htmlspecialchars($userDetails->last_semester_cgpa_or_merit) : 'N/A'; ?></p>
                                                        </div>
                                                        <div class="col-lg-4">
                                                            <p><strong>District:</strong> <?php echo isset($userDetails->district) ? htmlspecialchars($userDetails->district) : 'N/A'; ?></p>
                                                        </div>
                                                        <div class="col-lg-4">
                                                            <p><strong>Division:</strong> <?php echo isset($userDetails->division) ? htmlspecialchars($userDetails->division) : 'N/A'; ?></p>
                                                        </div>
                                                    </div>
                                                    <div class="row pt-4">
                                                        <div class="col-lg-6">
                                                            <p><strong>Permanent Address:</strong> <?php echo isset($userDetails->permanent_address) ? htmlspecialchars($userDetails->permanent_address) : 'N/A'; ?></p>
                                                        </div>
                                                        <div class="col-lg-6">
                                                            <p><strong>Present Address:</strong> <?php echo isset($userDetails->present_address) ? htmlspecialchars($userDetails->present_address) : 'N/A'; ?></p>
                                                        </div>
                                                    </div>

                                                    <div class="text-center mt-5 mb-4">
                                                        <h5 class="form-info-title">Father's Information</h5>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-lg-4">
                                                            <p><strong>Father's Name:</strong> <?php echo isset($userDetails->father_name) ? htmlspecialchars($userDetails->father_name) : 'N/A'; ?></p>
                                                        </div>
                                                        <div class="col-lg-4">
                                                            <p><strong>Father's Contact No:</strong> <?php echo isset($userDetails->father_contact_no) ? htmlspecialchars($userDetails->father_contact_no) : 'N/A'; ?></p>
                                                        </div>
                                                        <div class="col-lg-4">
                                                            <p><strong>Father's Profession:</strong> <?php echo isset($userDetails->father_profession) ? htmlspecialchars($userDetails->father_profession) : 'N/A'; ?></p>
                                                        </div>
                                                    </div>
                                                    <div class="row pt-4">
                                                        <div class="col-lg-4">
                                                            <p><strong>Father's Monthly Income:</strong> <?php echo isset($userDetails->father_monthly_income) ? htmlspecialchars($userDetails->father_monthly_income) : 'N/A'; ?></p>
                                                        </div>
                                                    </div>

                                                    <div class="text-center mt-5 mb-4">
                                                        <h5 class="form-info-title">Mother's Information</h5>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-lg-4">
                                                            <p><strong>Mother's Name:</strong> <?php echo isset($userDetails->mother_name) ? htmlspecialchars($userDetails->mother_name) : 'N/A'; ?></p>
                                                        </div>
                                                        <div class="col-lg-4">
                                                            <p><strong>Mother's Contact No:</strong> <?php echo isset($userDetails->mother_contact_no) ? htmlspecialchars($userDetails->mother_contact_no) : 'N/A'; ?></p>
                                                        </div>
                                                        <div class="col-lg-4">
                                                            <p><strong>Mother's Profession:</strong> <?php echo isset($userDetails->mother_profession) ? htmlspecialchars($userDetails->mother_profession) : 'N/A'; ?></p>
                                                        </div>
                                                    </div>
                                                    <div class="row pt-4">
                                                        <div class="col-lg-4">
                                                            <p><strong>Mother's Monthly Income:</strong> <?php echo isset($userDetails->mother_monthly_income) ? htmlspecialchars($userDetails->mother_monthly_income) : 'N/A'; ?></p>
                                                        </div>
                                                    </div>

                                                    <div class="text-center mt-5 mb-4">
                                                        <h5 class="form-info-title">Guardian's Information</h5>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-lg-4">
                                                            <p><strong>Guardian's Name:</strong> <?php echo isset($userDetails->guardian_name) ? htmlspecialchars($userDetails->guardian_name) : 'N/A'; ?></p>
                                                        </div>
                                                        <div class="col-lg-4">
                                                            <p><strong>Guardian's Contact No:</strong> <?php echo isset($userDetails->guardian_contact_no) ? htmlspecialchars($userDetails->guardian_contact_no) : 'N/A'; ?></p>
                                                        </div>
                                                        <div class="col-lg-4">
                                                            <p><strong>Guardian's Address:</strong> <?php echo isset($userDetails->guardian_address) ? htmlspecialchars($userDetails->guardian_address) : 'N/A'; ?></p>
                                                        </div>
                                                    </div>

                                                    <div class="text-center mt-5 mb-4">
                                                        <h5 class="form-info-title">Other Information</h5>
                                                    </div>
                                                    <div class="row pt-4">
                                                        <div class="col-lg-4">
                                                            <p><strong>Document file:
                                                                    <a href="../uploads1/<?php echo isset($userDetails->document_id) ? htmlspecialchars($file2->file_new_name) : '0.jpg'; ?>" target="_blank">Click to view</strong> </a>
                                                            </p>
                                                        </div>
                                                        <!-- <div class="col-lg-4">
                                                        <p><strong>Note IDs:</strong> <?php echo isset($userDetails->note_ids) ? htmlspecialchars($userDetails->note_ids) : 'N/A'; ?></p>
                                                    </div> -->
                                                        <div class="col-lg-4">
                                                            <p><strong>Created:</strong> <?php echo isset($userDetails->created) ? htmlspecialchars($userDetails->created) : 'N/A'; ?></p>
                                                            <p><strong>Modified:</strong> <?php echo isset($userDetails->modified) ? htmlspecialchars($userDetails->modified) : 'N/A'; ?></p>
                                                        </div>
                                                    </div>


                                                    <!-- Additional details can be added here -->
                                                    <div class="col-lg-12 d-flex align-items-center justify-content-center mt-4">
                                                        <button class="btn btn-danger" data-bs-toggle="collapse"
                                                            data-bs-target="#<?php echo $collapseId; ?>">Close</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                <?php
                                    }
                                } else {
                                    echo "<p>No users found.</p>";
                                }
                                ?>
                            </div>

                            <!-- Pagination Controls (placed after the user list) -->
                            <nav aria-label="User list pagination">
                                <ul class="pagination justify-content-center" id="paginationContainer"></ul>
                            </nav>
                        </div>
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
            const searchUserId = document.getElementById('searchUserId').value.trim();
            const searchStudentId = document.getElementById('searchStudentId').value.trim();
            const searchEmail = document.getElementById('searchEmail').value.trim().toLowerCase();

            const items = document.querySelectorAll('.accordion-item.faq-item');

            items.forEach(function(item) {
                const itemUserId = item.getAttribute('data-userid');
                const itemStudentId = item.getAttribute('data-studentid');
                const itemEmail = item.getAttribute('data-email').toLowerCase();

                let match = true;
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
            document.getElementById('searchUserId').addEventListener('input', filterItems);
            document.getElementById('searchStudentId').addEventListener('input', filterItems);
            document.getElementById('searchEmail').addEventListener('input', filterItems);
            filterItems(); // Initialize on page load.
        });
    </script>
</body>

</html>