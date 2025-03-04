<?php
include_once '../class-file/User.php';
include_once '../class-file/UserDetails.php';
include_once '../class-file/FileManager.php';
include_once '../popup-1.php';

if (isset($_POST['approve']) || isset($_POST['decline'])) {
    $userDetails1 = new UserDetails();
    $userDetails2 = new UserDetails();

    if (isset($_POST['approve'])) {
        $userDetails1->user_id = $_POST['approve'];
        $userDetails2->user_id = $_POST['approve'];
        $userDetails1->loadByUserId($userDetails1->user_id, 1);
        $userDetails2->loadByUserId($userDetails2->user_id, 0);

        $userDetails1->status = -3;
        $userDetails2->status = 1;

        $userDetails1->update();
        $userDetails2->update();

        showPopup("The request has been approved successfully. User ID = " . $_POST['approve']);
    } else {
        $userDetails2->user_id = $_POST['decline'];
        $userDetails2->loadByUserId($userDetails2->user_id, 0);

        $userDetails2->status = -1;

        $userDetails2->update();

        showPopup("The request has been declined successfully. User ID = " . $_POST['decline']);
    }
}


$userDetails = new UserDetails();
$allNewList = array();
$allCurrentList = array();

// Requested
$detailsList = $userDetails->cutsomGetUsersByStatus(1, 0, 'user');  // Now returns an array of associative arrays (full rows)
if (is_array($detailsList)) {
    for ($i = 0; $i < count($detailsList); $i++) {
        $ud = new UserDetails();
        $ud->setProperties($detailsList[$i]);
        // echo $ud->student_id . "<br>";
        $allNewList[] = $ud;
    }
}

// Current
$detailsList = $userDetails->cutsomGetUsersByStatus(1, 1, 'user');  // Now returns an array of associative arrays (full rows)
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
    <meta name="description" content="" />
    <meta name="author" content="" />

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
        integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" /> -->
    <link href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css" rel="stylesheet" />
    <link href="../css/Dashboard/dashboard.css" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>

    <title>MM HALL - Dashboard</title>

    <style>
        .profile-info-flex .label {
            font-weight: 500;
            color: black;
        }

        .profile-info-flex .data {
            color: black;
        }


        .accordion-item {
            border: none;
        }

        .accordion-item P {
            margin: 0;
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

        .profile-info-flex {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1rem;
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
    </style>
</head>

<body class="sb-nav-fixed">
    <nav class="sb-topnav navbar navbar-expand dashboard-nav py-4">
        <!-- Navbar Brand-->
        <a class="navbar-brand ps-3" href="../index.html">HMS</a>
        <!-- Sidebar Toggle-->
        <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!"><i
                class="fas fa-bars"></i></button>
        <!-- Navbar Search-->
        <form class="d-none d-md-inline-block form-inline ms-auto me-0 me-md-3 my-2 my-md-0">
            <!-- <div class="input-group">
                <input class="form-control" type="text" placeholder="Search for..." aria-label="Search for..."
                    aria-describedby="btnNavbarSearch" />
                <button class="btn btn-primary" id="btnNavbarSearch" type="button"><i
                        class="fas fa-search"></i></button>
            </div> -->
        </form>
        <!-- Navbar-->
        <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown"
                    aria-expanded="false"><i class="fas fa-user fa-fw"></i></a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                    <li><a class="dropdown-item" href="#!">Profile</a></li>
                    <li><a class="dropdown-item" href="#!">Activity Log</a></li>
                    <li>
                        <hr class="dropdown-divider" />
                    </li>
                    <li><a class="dropdown-item" href="#!">Logout</a></li>
                </ul>
            </li>
        </ul>
    </nav>
    <div id="layoutSidenav">
        <div id="layoutSidenav_nav">
            <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                <div class="sb-sidenav-menu">
                    <div class="nav">
                        <div class="sb-sidenav-menu-heading">Pages</div>
                        <a class="nav-link" href="./Dashbaord.html">
                            <div class="sb-nav-link-icon"><i class="fa-solid fa-house"></i></div>
                            Dashboard
                        </a>
                        <a class="nav-link" href="./user-review.html">
                            <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                            Users Review
                        </a>
                        <a class="nav-link" href="manage-user.html">
                            <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                            Manage User
                        </a>
                        <a class="nav-link" href="pending-noc.html">
                            <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                            Pending NOC
                        </a>
                        <a class="nav-link" href="pending-hall-seat.html">
                            <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                            Pending Hall Seat
                        </a>
                        <a class="nav-link" href="create-event.html">
                            <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                            Create Event
                        </a>
                        <a class="nav-link" href="event-management.html">
                            <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                            Event Management
                        </a>
                        <a class="nav-link" href="seat-confirmation.html">
                            <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                            Seat Confirmation
                        </a>
                        <a class="nav-link" href="seat-management.html">
                            <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                            Seat Management
                        </a>


                    </div>
                </div>
                <div class="sb-sidenav-footer">
                    <div class="small">Logged in as: Admin</div>

                </div>
            </nav>
        </div>
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">
                    <div class="card__wrapper">
                        <div class="card__title-wrap mb-20">
                            <h3 class="table__heading-title">Update profile requests</h3>
                        </div>

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


                            <!-- First Item -->
                            <?php
                            if ($allNewList && $allCurrentList && count($allNewList) > 0 && count($allCurrentList) > 0) {
                                $length = min(count($allNewList), count($allCurrentList));
                                for ($i = 0; $i < $length; $i++) {
                                    $userDetailsNew = $allNewList[$i];
                                    $userDetailsCurrent = $allCurrentList[$i];

                                    $collapseId = "collapse{$userDetailsCurrent->user_id}";
                                    $userObj1 = new User();
                                    $userObj1->user_id = $userDetailsCurrent->user_id;
                                    $userObj1->load();

                                    $file1 = new FileManager();
                                    $file1->loadById($userDetailsCurrent->profile_picture_id);

                                    $file2 = new FileManager();
                                    $file2->loadById($userDetailsCurrent->document_id);

                                    $file3 = new FileManager();
                                    $file3->loadById($userDetailsNew->profile_picture_id);

                                    $file4 = new FileManager();
                                    $file4->loadById($userDetailsNew->document_id);

                            ?>
                                    <div class="accordion-item faq-item">
                                        <div class="row">
                                            <div class="col-lg-2 d-flex align-items-center">
                                                <p><?php echo htmlspecialchars($userDetailsCurrent->user_id); ?></p>
                                            </div>
                                            <div class="col-lg-2 d-flex align-items-center">
                                                <p><?php echo htmlspecialchars($userDetailsCurrent->student_id); ?></p>
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
                                        <div id="<?php echo $collapseId; ?>" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                            <div class="accordion-body">
                                                <!-- Row: Basic details ID -->
                                                <div class="row pt-4">
                                                    <div class="col-lg-12">
                                                        <p><strong>Email:</strong> <?php echo htmlspecialchars($userObj1->email); ?></p>
                                                    </div>
                                                </div>

                                                <!-- Row: Profile Picture -->
                                                <div class="row pt-4">
                                                    <div class="col-lg-6">
                                                        <p><strong>Profile Picture (Current):</strong>
                                                            <img src="../uploads1/<?php echo htmlspecialchars($file1->file_new_name); ?>" alt="Profile Picture" style="width: 150px; height: 150px;">
                                                        </p>
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <p><strong>Profile Picture (Requested):</strong>
                                                            <img src="../uploads1/<?php echo htmlspecialchars($file3->file_new_name); ?>" alt="Profile Picture" style="width: 150px; height: 150px;">
                                                        </p>
                                                    </div>

                                                <!-- Row: Student ID -->
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

                                                <!-- Row: Year -->
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
                                                ?>
                                                <div class="row pt-4">
                                                    <div class="col-lg-6">
                                                        <p><strong>Year (Current):</strong> <?php echo htmlspecialchars(getYearDescription($userDetailsCurrent->year)); ?></p>
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <p><strong>Year (Requested):</strong> <?php echo htmlspecialchars(getYearDescription($userDetailsNew->year)); ?></p>
                                                    </div>
                                                </div>


                                                <!-- Row: Semester -->
                                                <div class="row pt-4">
                                                    <div class="col-lg-6">
                                                        <p><strong>Semester (Current):</strong> <?php echo   htmlspecialchars($userDetailsCurrent->semester); ?></p>
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <p><strong>Semester (Requested):</strong> <?php echo   htmlspecialchars($userDetailsNew->semester); ?></p>
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
                                                        <p><strong>Note IDs (Current):</strong> <?php echo  htmlspecialchars($userDetailsCurrent->note_ids); ?></p>
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <p><strong>Note IDs (Requested):</strong> <?php echo  htmlspecialchars($userDetailsNew->note_ids); ?></p>
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

                                                <!-- Close Button -->
                                                <div class="col-lg-12 d-flex align-items-center justify-content-center mt-4">
                                                    <button type="button" class="btn btn-danger" data-bs-toggle="collapse" data-bs-target="#<?php echo $collapseId; ?>">Close</button>
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
                    </div>
                </div>
            </main>
            <footer class="py-4 dashboard-copyright-footer mt-auto">
                <div class="container-fluid px-4">
                    <div class="d-flex align-items-center justify-content-between small">
                        <div class="text-muted">Copyright &copy; Just 2024</div>
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
        crossorigin="anonymous"></script>
    <script src="script.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
    <script src="assets/demo/chart-area-demo.js"></script>
    <script src="assets/demo/chart-bar-demo.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js"
        crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>



    <script>
        $(document).ready(function() {
            $('#userTable').DataTable(); // Initialize DataTables on #userTable
        });

        window.addEventListener('DOMContentLoaded', event => {

            // Toggle the side navigation
            const sidebarToggle = document.body.querySelector('#sidebarToggle');
            if (sidebarToggle) {
                // Uncomment Below to persist sidebar toggle between refreshes
                // if (localStorage.getItem('sb|sidebar-toggle') === 'true') {
                //     document.body.classList.toggle('sb-sidenav-toggled');
                // }
                sidebarToggle.addEventListener('click', event => {
                    event.preventDefault();
                    document.body.classList.toggle('sb-sidenav-toggled');
                    localStorage.setItem('sb|sidebar-toggle', document.body.classList.contains('sb-sidenav-toggled'));
                });
            }

        });
    </script>
</body>

</html>