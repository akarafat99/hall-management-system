<?php
include_once '../class-file/User.php';
include_once '../class-file/UserDetails.php';
include_once '../class-file/FileManager.php';
include_once '../popup-1.php';

if (isset($_POST['active']) || isset($_POST['deactive'])) {
    $user2 = new User();

    $status = 0;
    if (isset($_POST['active'])) {
        $user2->user_id = $_POST['active'];
        $status = 1;
    } else {
        $user2->user_id = $_POST['deactive'];
        $status = 2;
    }

    $user2->load();
    $user2->status = $status;
    $user2->update();

    showPopup("User ID: " . $user2->user_id . " with email: {$user2->email} has been successfully " . ($status == 1 ? "activated" : "deactivated"));
}

$user = new User();
$userListActive = $user->getDistinctUsersByStatus(1, "user"); // Get all users with status 1 (active)
$userListDeactive = $user->getDistinctUsersByStatus(2, "user"); // Get all users with status 2 (deactive)

// Merge the arrays (using an empty array fallback if one of the lists is false)
$userList = array_merge($userListActive ?: [], $userListDeactive ?: []);



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
                            <h3 class="table__heading-title">Manage Users</h3>
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
                                    <div class="col-lg-3">
                                        <p>Email</p>
                                    </div>
                                    <div class="col-lg-1">
                                        <p>Status</p>
                                    </div>
                                    <div class="col-lg-1">
                                        <p>Profile</p>
                                    </div>
                                    <div class="col-lg-3">
                                        <p>Action</p>
                                    </div>
                                </div>
                            </div>


                            <!-- First Item -->
                            <?php
                            if ($userList && count($userList) > 0) {
                                foreach ($userList as $userItem) {
                                    $userId = $userItem['user_id'];
                                    $userObj1 = new User();
                                    $userObj1->user_id = $userId;
                                    $userObj1->load();

                                    // Create a new instance of UserDetails and load details for this user
                                    $userDetails = new UserDetails();
                                    $userDetails->loadByUserId($userId, 1);

                                    $file = new FileManager();
                                    $file->loadById($userDetails->profile_picture_id);

                                    $file2 = new FileManager();
                                    $file2->loadById($userDetails->document_id);

                                    $collapseId = "collapse{$userId}";

                            ?>
                                    <div class="accordion-item faq-item">
                                        <div class="row">
                                            <div class="col-lg-2 d-flex align-items-center">
                                                <p><?php echo htmlspecialchars($userDetails->user_id); ?></p>
                                            </div>
                                            <div class="col-lg-2 d-flex align-items-center">
                                                <p><?php echo htmlspecialchars($userDetails->student_id); ?></p>
                                            </div>
                                            <div class="col-lg-3 d-flex align-items-center">
                                                <p><?php echo htmlspecialchars($userObj1->email); ?></p>
                                            </div>
                                            <div class="col-lg-1 d-flex align-items-center">
                                                <?php if ($userObj1->status == 1) { ?>
                                                    <span class="bd-badge bg-success">Active</span>
                                                <?php } else { ?>
                                                    <span class="bd-badge bg-warning">Deactive</span>
                                                <?php } ?>
                                            </div>
                                            <div class="col-lg-1 d-flex align-items-center">
                                                <button class="btn btn-primary" data-bs-toggle="collapse"
                                                    data-bs-target="#<?php echo $collapseId; ?>">Details</button>
                                            </div>
                                            <div class="col-lg-3 d-flex align-items-center">
                                                <div>
                                                    <form action="" method="post">
                                                        <?php if ($userObj1->status == 1) { ?>
                                                            <button type="submit" name="deactive" value="<?php echo htmlspecialchars($userId); ?>" class="btn btn-success">Deactive</button>
                                                        <?php } else { ?>
                                                            <button type="submit" name="active" value="<?php echo htmlspecialchars($userId); ?>" class="btn btn-success">Active</button>
                                                        <?php } ?>
                                                    </form>
                                                </div>
                                            </div>

                                        </div>
                                        <div id="<?php echo $collapseId; ?>" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
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
                                                        <p><strong>Year:</strong> <?php echo isset($userDetails->year) ? htmlspecialchars($userDetails->year) : 'N/A'; ?></p>
                                                        <p><strong>Semester:</strong> <?php echo isset($userDetails->semester) ? htmlspecialchars($userDetails->semester) : 'N/A'; ?></p>
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
                                                        <p><strong>Note IDs:</strong> <?php //echo isset($userDetails->note_ids) ? htmlspecialchars($userDetails->note_ids) : 'N/A'; 
                                                                                        ?></p>
                                                    </div> -->
                                                    <div class="col-lg-4">
                                                        <p><strong>Created:</strong> <?php echo isset($userDetails->created) ? htmlspecialchars($userDetails->created) : 'N/A'; ?></p>
                                                        <p><strong>Modified:</strong> <?php echo isset($userDetails->modified) ? htmlspecialchars($userDetails->modified) : 'N/A'; ?></p>
                                                    </div>
                                                </div>

                                                <!-- You can add more rows here similar to the above structure -->

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