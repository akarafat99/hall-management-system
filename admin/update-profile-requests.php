<?php
include_once '../class-file/SessionManager.php';
$session = SessionStatic::class;

include_once '../class-file/Auth.php';
auth('admin');

include_once '../class-file/User.php';
include_once '../class-file/UserDetails.php';
include_once '../class-file/FileManager.php';
include_once '../class-file/Department.php';
include_once '../popup-1.php';

if (isset($_POST['approve']) || isset($_POST['decline'])) {
    $userDetails1 = new UserDetails();
    $userDetails2 = new UserDetails();

    if (isset($_POST['approve'])) {
        $userDetails1->user_id = $_POST['approve'];
        $userDetails2->user_id = $_POST['approve'];
        $userDetails1->getUsers($userDetails1->user_id, 1);
        $userDetails2->getUsers($userDetails2->user_id, 0);

        // -3 means history, 1 means current or active
        $userDetails1->status = -3;
        $userDetails2->status = 1;

        $userDetails1->update();
        $userDetails2->update();

        showPopup("The request has been approved successfully. User ID = " . $_POST['approve']);
    } else {
        $userDetails2->user_id = $_POST['decline'];
        $userDetails2->getUsers($userDetails2->user_id, 0);

        $userDetails2->status = -1;

        $userDetails2->update();

        showPopup("The request has been declined successfully. User ID = " . $_POST['decline']);
    }
}


$userDetails = new UserDetails();
$allNewList = array();
$allCurrentList = array();

$departmentNew = new Department();
$departmentCurrent = new Department();
$departmentNew->department_id = $userDetails->department_id;
$departmentCurrent->department_id = $userDetails->department_id;

$yearSemesterCode = $departmentCurrent->getYearSemesterCodes();

// Requested
$detailsList = $userDetails->cutsomGetUsersDetailByStatus(1, 0, 'user');  // Now returns an array of associative arrays (full rows)
if (is_array($detailsList)) {
    for ($i = 0; $i < count($detailsList); $i++) {
        $ud = new UserDetails();
        $ud->setProperties($detailsList[$i]);
        // echo $ud->student_id . "<br>";
        $allNewList[] = $ud;
    }
}

// Current
$detailsList = $userDetails->cutsomGetUsersDetailByStatus(1, 1, 'user');  // Now returns an array of associative arrays (full rows)
if (is_array($detailsList)) {
    for ($i = 0; $i < count($detailsList); $i++) {
        $ud = new UserDetails();
        $ud->setProperties($detailsList[$i]);
        // echo $ud->student_id . "<br>";
        $allCurrentList[] = $ud;
    }
}



?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>MM HALL - Dashboard</title>

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
                            <div class="card__title-wrap mb-20">
                                <h3 class="table__heading-title">Profile Update Requests</h3>
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
                                        <div class="col-lg-4">
                                            <p>Current and Requested</p>
                                        </div>
                                        <div class="col-lg-4">
                                            <p>Action</p>
                                        </div>
                                    </div>
                                </div>

                                <?php
                                function getYearDescription($year)
                                {
                                    switch ($year) {
                                        case 1:
                                            return 'B.Sc. 1st Year';
                                        case 2:
                                            return 'B.Sc. 2nd Year';
                                        case 3:
                                            return 'B.Sc. 3rd Year';
                                        case 4:
                                            return 'B.Sc. 4th Year';
                                        case 5:
                                            return 'M.Sc. 1st Year';
                                        case 6:
                                            return 'M.Sc. 2nd Year';
                                        default:
                                            return 'Unknown Year';
                                    }
                                }

                                if ($allNewList && $allCurrentList && count($allNewList) > 0 && count($allCurrentList) > 0) {
                                    $length = min(count($allNewList), count($allCurrentList));
                                    for ($i = 0; $i < $length; $i++) {
                                        $userDetailsNew = $allNewList[$i];
                                        $userDetailsCurrent = $allCurrentList[$i];

                                        $collapseId = "collapse{$userDetailsCurrent->user_id}";
                                        $userObj1 = new User();
                                        $userObj1->user_id = $userDetailsCurrent->user_id;
                                        $userObj1->load();

                                        $departmentCurrent->getDepartments($userDetailsCurrent->department_id);
                                        $departmentNew->getDepartments($userDetailsNew->department_id);

                                        $file1 = new FileManager();
                                        $file1->loadByFileId($userDetailsCurrent->profile_picture_id);
                                        // echo $userDetailsCurrent->profile_picture_id . "<br>";

                                        $file2 = new FileManager();
                                        $file2->loadByFileId($userDetailsCurrent->document_id);

                                        $file3 = new FileManager();
                                        $file3->loadByFileId($userDetailsNew->profile_picture_id);

                                        $file4 = new FileManager();
                                        $file4->loadByFileId($userDetailsNew->document_id);
                                ?>
                                        <div class="accordion-item faq-item"
                                            data-userid="<?php echo $userDetailsCurrent->user_id; ?>"
                                            data-studentid="<?php echo $userDetailsCurrent->student_id; ?>"
                                            data-email="<?php echo $userObj1->email; ?>">
                                            <div class="row">
                                                <div class="col-lg-2 d-flex align-items-center">
                                                    <p><?php echo $userDetailsCurrent->user_id; ?></p>
                                                </div>
                                                <div class="col-lg-2 d-flex align-items-center">
                                                    <p><?php echo $userDetailsCurrent->student_id; ?></p>
                                                </div>
                                                <div class="col-lg-4 d-flex align-items-center">
                                                    <button class="btn btn-primary" data-bs-toggle="collapse"
                                                        data-bs-target="#<?php echo $collapseId; ?>">Details</button>
                                                </div>
                                                <div class="col-lg-4 d-flex align-items-center">
                                                    <div>
                                                        <form action="" method="post">
                                                            <button type="submit" name="approve" value="<?php echo htmlspecialchars($userDetailsCurrent->user_id); ?>" class="btn btn-success">Approved</button>
                                                            <button type="submit" name="decline" value="<?php echo htmlspecialchars($userDetailsCurrent->user_id); ?>" class="btn btn-danger">Declined</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                            <div id="<?php echo $collapseId; ?>" class="accordion-collapse collapse"
                                                data-bs-parent="#faqAccordion">
                                                <div class="accordion-body">
                                                    <div class="profile-info-flex">
                                                        <div class="profile-wrap">
                                                            <img src="../uploads1/<?php echo $file1->file_new_name; ?>" alt="User Image" class="img-fluid">
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-lg-12">
                                                            <p><strong>Email:</strong> <?php echo htmlspecialchars($userObj1->email); ?></p>
                                                        </div>
                                                    </div>

                                                    <!-- Student ID -->
                                                    <div class="row pt-4">
                                                        <div class="col-lg-6">
                                                            <p><strong>Student ID (Current):</strong> <?php echo htmlspecialchars($userDetailsCurrent->student_id); ?></p>
                                                        </div>
                                                        <div class="col-lg-6">
                                                            <p><strong>Student ID (Requested):</strong> <?php echo htmlspecialchars($userDetailsNew->student_id); ?></p>
                                                        </div>
                                                    </div>

                                                    <!-- Row: Full Name -->
                                                    <div class="row pt-4">
                                                        <div class="col-lg-6">
                                                            <p><strong>Name (Current):</strong> <?php echo htmlspecialchars($userDetailsCurrent->full_name); ?></p>
                                                        </div>
                                                        <div class="col-lg-6">
                                                            <p><strong>Name (Requested):</strong> <?php echo htmlspecialchars($userDetailsNew->full_name); ?></p>
                                                        </div>
                                                    </div>

                                                    <!-- Row: Gender -->
                                                    <div class="row pt-4">
                                                        <div class="col-lg-6">
                                                            <p><strong>Gender (Current):</strong> <?php echo htmlspecialchars($userDetailsCurrent->gender); ?></p>
                                                        </div>
                                                        <div class="col-lg-6">
                                                            <p><strong>Gender (Requested):</strong> <?php echo htmlspecialchars($userDetailsNew->gender); ?></p>
                                                        </div>
                                                    </div>

                                                    <!-- Row: Contact No -->
                                                    <div class="row pt-4">
                                                        <div class="col-lg-6">
                                                            <p><strong>Contact No (Current):</strong> <?php echo htmlspecialchars($userDetailsCurrent->contact_no); ?></p>
                                                        </div>
                                                        <div class="col-lg-6">
                                                            <p><strong>Contact No (Requested):</strong> <?php echo htmlspecialchars($userDetailsNew->contact_no); ?></p>
                                                        </div>
                                                    </div>

                                                    <!-- Row: Session -->
                                                    <div class="row pt-4">
                                                        <div class="col-lg-6">
                                                            <p><strong>Session (Current):</strong> <?php echo   htmlspecialchars($userDetailsCurrent->session); ?></p>
                                                        </div>
                                                        <div class="col-lg-6">
                                                            <p><strong>Session (Requested):</strong> <?php echo   htmlspecialchars($userDetailsNew->session); ?></p>
                                                        </div>
                                                    </div>
                                                    <div class="row pt-4">
                                                        <div class="col-lg-6">
                                                            <p><strong>Department (Current):</strong> <?php echo   htmlspecialchars($departmentCurrent->department_name); ?></p>
                                                        </div>
                                                        <div class="col-lg-6">
                                                            <p><strong>Department (Requested):</strong> <?php echo   htmlspecialchars($departmentNew->department_name); ?></p>
                                                        </div>
                                                    </div>

                                                    <div class="row pt-4">
                                                        <div class="col-lg-6">
                                                            <p><strong>Academic Status (Current):</strong> <?php echo $yearSemesterCode[$userDetailsCurrent->year_semester_code]; ?></p>
                                                        </div>
                                                        <div class="col-lg-6">
                                                            <p><strong>Academic Status (Requested):</strong> <?php echo $yearSemesterCode[$userDetailsNew->year_semester_code]; ?></p>
                                                        </div>
                                                    </div>

                                                    <!-- Row: Last Semester CGPA/Merit -->
                                                    <div class="row pt-4">
                                                        <div class="col-lg-6">
                                                            <p><strong>Last Semester CGPA/Merit (Current):</strong> <?php echo   htmlspecialchars($userDetailsCurrent->last_semester_cgpa_or_merit); ?></p>
                                                        </div>
                                                        <div class="col-lg-6">
                                                            <p><strong>Last Semester CGPA/Merit (Requested):</strong> <?php echo htmlspecialchars($userDetailsNew->last_semester_cgpa_or_merit); ?></p>
                                                        </div>
                                                    </div>

                                                    <!-- Row: District -->
                                                    <div class="row pt-4">
                                                        <div class="col-lg-6">
                                                            <p><strong>District (Current):</strong> <?php echo  htmlspecialchars($userDetailsCurrent->district); ?></p>
                                                        </div>
                                                        <div class="col-lg-6">
                                                            <p><strong>District (Requested):</strong> <?php echo  htmlspecialchars($userDetailsNew->district); ?></p>
                                                        </div>
                                                    </div>

                                                    <!-- Row: Division -->
                                                    <div class="row pt-4">
                                                        <div class="col-lg-6">
                                                            <p><strong>Division (Current):</strong> <?php echo  htmlspecialchars($userDetailsCurrent->division); ?></p>
                                                        </div>
                                                        <div class="col-lg-6">
                                                            <p><strong>Division (Requested):</strong> <?php echo  htmlspecialchars($userDetailsNew->division); ?></p>
                                                        </div>
                                                    </div>

                                                    <!-- Row: Permanent Address -->
                                                    <div class="row pt-4">
                                                        <div class="col-lg-6">
                                                            <p><strong>Permanent Address (Current):</strong> <?php echo  htmlspecialchars($userDetailsCurrent->permanent_address); ?></p>
                                                        </div>
                                                        <div class="col-lg-6">
                                                            <p><strong>Permanent Address (Requested):</strong> <?php echo  htmlspecialchars($userDetailsNew->permanent_address); ?></p>
                                                        </div>
                                                    </div>

                                                    <!-- Row: Present Address -->
                                                    <div class="row pt-4">
                                                        <div class="col-lg-6">
                                                            <p><strong>Present Address (Current):</strong> <?php echo  htmlspecialchars($userDetailsCurrent->present_address); ?></p>
                                                        </div>
                                                        <div class="col-lg-6">
                                                            <p><strong>Present Address (Requested):</strong> <?php echo  htmlspecialchars($userDetailsNew->present_address); ?></p>
                                                        </div>
                                                    </div>

                                                    <!-- Row: Father's Information -->
                                                    <div class="text-center mt-5 mb-4">
                                                        <h5 class="form-info-title">Father's Information</h5>
                                                    </div>
                                                    <div class="row pt-4">
                                                        <div class="col-lg-6">
                                                            <p><strong>Father's Name (Current):</strong> <?php echo  htmlspecialchars($userDetailsCurrent->father_name); ?></p>
                                                        </div>
                                                        <div class="col-lg-6">
                                                            <p><strong>Father's Name (Requested):</strong> <?php echo  htmlspecialchars($userDetailsNew->father_name); ?></p>
                                                        </div>
                                                    </div>
                                                    <div class="row pt-4">
                                                        <div class="col-lg-6">
                                                            <p><strong>Father's Contact No (Current):</strong> <?php echo  htmlspecialchars($userDetailsCurrent->father_contact_no); ?></p>
                                                        </div>
                                                        <div class="col-lg-6">
                                                            <p><strong>Father's Contact No (Requested):</strong> <?php echo  htmlspecialchars($userDetailsNew->father_contact_no); ?></p>
                                                        </div>
                                                    </div>
                                                    <div class="row pt-4">
                                                        <div class="col-lg-6">
                                                            <p><strong>Father's Profession (Current):</strong> <?php echo  htmlspecialchars($userDetailsCurrent->father_profession); ?></p>
                                                        </div>
                                                        <div class="col-lg-6">
                                                            <p><strong>Father's Profession (Requested):</strong> <?php echo  htmlspecialchars($userDetailsNew->father_profession); ?></p>
                                                        </div>
                                                    </div>
                                                    <div class="row pt-4">
                                                        <div class="col-lg-6">
                                                            <p><strong>Father's Monthly Income (Current):</strong> <?php echo  htmlspecialchars($userDetailsCurrent->father_monthly_income); ?></p>
                                                        </div>
                                                        <div class="col-lg-6">
                                                            <p><strong>Father's Monthly Income (Requested):</strong> <?php echo  htmlspecialchars($userDetailsNew->father_monthly_income); ?></p>
                                                        </div>
                                                    </div>

                                                    <!-- Row: Mother's Information -->
                                                    <div class="text-center mt-5 mb-4">
                                                        <h5 class="form-info-title">Mother's Information</h5>
                                                    </div>
                                                    <div class="row pt-4">
                                                        <div class="col-lg-6">
                                                            <p><strong>Mother's Name (Current):</strong> <?php echo  htmlspecialchars($userDetailsCurrent->mother_name); ?></p>
                                                        </div>
                                                        <div class="col-lg-6">
                                                            <p><strong>Mother's Name (Requested):</strong> <?php echo  htmlspecialchars($userDetailsNew->mother_name); ?></p>
                                                        </div>
                                                    </div>
                                                    <div class="row pt-4">
                                                        <div class="col-lg-6">
                                                            <p><strong>Mother's Contact No (Current):</strong> <?php echo  htmlspecialchars($userDetailsCurrent->mother_contact_no); ?></p>
                                                        </div>
                                                        <div class="col-lg-6">
                                                            <p><strong>Mother's Contact No (Requested):</strong> <?php echo  htmlspecialchars($userDetailsNew->mother_contact_no); ?></p>
                                                        </div>
                                                    </div>
                                                    <div class="row pt-4">
                                                        <div class="col-lg-6">
                                                            <p><strong>Mother's Profession (Current):</strong> <?php echo  htmlspecialchars($userDetailsCurrent->mother_profession); ?></p>
                                                        </div>
                                                        <div class="col-lg-6">
                                                            <p><strong>Mother's Profession (Requested):</strong> <?php echo  htmlspecialchars($userDetailsNew->mother_profession); ?></p>
                                                        </div>
                                                    </div>
                                                    <div class="row pt-4">
                                                        <div class="col-lg-6">
                                                            <p><strong>Mother's Monthly Income (Current):</strong> <?php echo  htmlspecialchars($userDetailsCurrent->mother_monthly_income); ?></p>
                                                        </div>
                                                        <div class="col-lg-6">
                                                            <p><strong>Mother's Monthly Income (Requested):</strong> <?php echo  htmlspecialchars($userDetailsNew->mother_monthly_income); ?></p>
                                                        </div>
                                                    </div>

                                                    <!-- Row: Guardian's Information -->
                                                    <div class="text-center mt-5 mb-4">
                                                        <h5 class="form-info-title">Guardian's Information</h5>
                                                    </div>
                                                    <div class="row pt-4">
                                                        <div class="col-lg-6">
                                                            <p><strong>Guardian's Name (Current):</strong> <?php echo  htmlspecialchars($userDetailsCurrent->guardian_name); ?></p>
                                                        </div>
                                                        <div class="col-lg-6">
                                                            <p><strong>Guardian's Name (Requested):</strong> <?php echo  htmlspecialchars($userDetailsNew->guardian_name); ?></p>
                                                        </div>
                                                    </div>
                                                    <div class="row pt-4">
                                                        <div class="col-lg-6">
                                                            <p><strong>Guardian's Contact No (Current):</strong> <?php echo  htmlspecialchars($userDetailsCurrent->guardian_contact_no); ?></p>
                                                        </div>
                                                        <div class="col-lg-6">
                                                            <p><strong>Guardian's Contact No (Requested):</strong> <?php echo  htmlspecialchars($userDetailsNew->guardian_contact_no); ?></p>
                                                        </div>
                                                    </div>
                                                    <div class="row pt-4">
                                                        <div class="col-lg-6">
                                                            <p><strong>Guardian's Address (Current):</strong> <?php echo  htmlspecialchars($userDetailsCurrent->guardian_address); ?></p>
                                                        </div>
                                                        <div class="col-lg-6">
                                                            <p><strong>Guardian's Address (Requested):</strong> <?php echo  htmlspecialchars($userDetailsNew->guardian_address); ?></p>
                                                        </div>
                                                    </div>

                                                    <!-- Row: Other Information -->
                                                    <div class="text-center mt-5 mb-4">
                                                        <h5 class="form-info-title">Other Information</h5>
                                                    </div>
                                                    <div class="row pt-4">
                                                        <div class="col-lg-6">
                                                            <p><strong>Document File (Current):</strong>
                                                                <a href="../uploads1/<?php echo  htmlspecialchars($file2->file_new_name); ?>" target="_blank">Click to view</a>
                                                            </p>
                                                        </div>
                                                        <div class="col-lg-6">
                                                            <p><strong>Document File (Requested):</strong>
                                                                <a href="../uploads1/<?php echo  htmlspecialchars($file4->file_new_name); ?>" target="_blank">Click to view</a>
                                                            </p>
                                                        </div>
                                                    </div>

                                                    <div class="row pt-4">
                                                        <div class="col-lg-6">
                                                            <p><strong>Created (Current):</strong> <?php echo  htmlspecialchars($userDetailsCurrent->created); ?></p>
                                                        </div>
                                                        <div class="col-lg-6">
                                                            <p><strong>Created (Requested):</strong> <?php echo  htmlspecialchars($userDetailsNew->created); ?></p>
                                                        </div>
                                                    </div>
                                                    <div class="row pt-4">
                                                        <div class="col-lg-6">
                                                            <p><strong>Modified (Current):</strong> <?php echo  htmlspecialchars($userDetailsCurrent->modified); ?></p>
                                                        </div>
                                                        <div class="col-lg-6">
                                                            <p><strong>Modified (Requested):</strong> <?php echo  htmlspecialchars($userDetailsNew->modified); ?></p>
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
        const itemsPerPage = 1;

        function filterItems() {
            const searchUserId = document.getElementById('searchUserId').value.trim();
            const searchStudentId = document.getElementById('searchStudentId').value.trim();
            const searchEmail = document.getElementById('searchEmail').value.trim();

            const items = document.querySelectorAll('.accordion-item.faq-item');

            items.forEach(function(item) {
                const itemUserId = item.getAttribute('data-userid');
                const itemStudentId = item.getAttribute('data-studentid');
                const itemEmail = item.getAttribute('data-email');

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