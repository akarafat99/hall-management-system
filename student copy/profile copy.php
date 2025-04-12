<?php
include_once '../class-file/User.php';
include_once '../class-file/UserDetails.php';
include_once '../class-file/FileManager.php';
include_once '../class-file/SessionManager.php';

$session = SessionStatic::class;

if ($session::get('user') == null) {
  echo "<script>window.location.href = '../login.php';</script>";
  exit;
}

// load user details
$sUser = $session::getObject('userObj');
$user = new User();
$session::copyProperties($sUser, $user);

$userDetails = new UserDetails();
$userDetails->user_id = $user->user_id;
$userDetails->getUsers($userDetails->user_id, null, 1);
$session::storeObject('userDetails', $userDetails);

$file1 = new FileManager();
$file1->file_id = $userDetails->profile_picture_id;
$file1->loadByFileId($file1->file_id);

$file2 = new FileManager();
$file2->file_id = $userDetails->document_id;
$file2->loadByFileId($file2->file_id);
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
  <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
  <link href="../css/Dashboard/dashboard.css" rel="stylesheet" />
  <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>

  <title>Dashboard - Profile Page</title>
  <style>
    .card__wrapper {
      background: #fff;
      padding: 25px;
      margin-bottom: 20px;
      border-radius: 10px;
      box-shadow: 0 6px 30px rgba(182, 186, 203, 0.3);
      position: relative;
      margin-top: 40px;
    }

    .table__heading-title {
      color: #362a2a;
      font-weight: 700;
      margin-top: 0px;
      line-height: 1;
      margin-bottom: 40px;
      text-align: center;
    }

    .profile-img {
      width: 120px;
      height: 120px;
      object-fit: cover;
      border-radius: 50%;
      border: 3px solid #ddd;
      margin-bottom: 20px;
    }

    .form-group label {
      font-weight: 600;
    }

    .form-control[readonly] {
      background-color: #e9ecef;
    }

    .form-label,
    .col-form-label {
      color: #362a2a;
    }

    .account-profile .avatar-preview {
      width: 160px;
      height: 160px;
      position: relative;
    }

    .account-profile .avatar-preview>div {
      width: 100%;
      height: 100%;
      background-size: cover;
      background-repeat: no-repeat;
      background-position: center;
    }

    .account-profile .upload-link {
      position: absolute;
      width: 35px;
      height: 35px;
      line-height: 35px;
      text-align: center;
      background: var(--primary);
      bottom: 0;
      right: 0;
      border-radius: 6px;
      color: #fff;
      overflow: hidden;
    }

    .account-profile .upload-link .update-flie {
      position: absolute;
      opacity: 0;
      z-index: 0;
      width: 100%;
      cursor: pointer;
      left: 0;
      height: 100%;
    }

    .fs-update {
      font-size: 1rem !important;
      line-height: 1.6;
    }

    .form-control {
      color: #7e7e7e;
      line-height: 1.7;
      font-size: 0.9rem;
      border-color: #dad8d4;
      height: 2.813rem;
      border-radius: 0.5rem;
      display: block;
      width: 100%;
      padding: 0.375rem 0.75rem;
      font-size: 0.875rem;
      font-weight: 400;
      line-height: 1.5;
      background-clip: padding-box;
      transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
    }

    .form-control:hover,
    .form-control:focus,
    .form-control.active {
      box-shadow: none;
      outline: none;
      border-color: #dad8d4;

    }

    /* Custom wrapper to position the dropdown icon */
    .custom-select-wrapper {
      position: relative;
      display: inline-block;
      width: 100%;
    }

    .custom-select-wrapper select {
      width: 100%;
      appearance: none;
      -webkit-appearance: none;
      -moz-appearance: none;
    }

    .custom-select-wrapper .dropdown-icon {
      position: absolute;
      right: 10px;
      top: 50%;
      transform: translateY(-50%);
      pointer-events: none;
      color: #555;
    }
  </style>
</head>

<body class="sb-nav-fixed">

  <div id="layoutSidenav">
    <div id="layoutSidenav_nav">
      <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
        <div class="sb-sidenav-menu">
          <div class="nav">
            <div class="sb-sidenav-menu-heading">Pages</div>
            <a class="nav-link" href="../index.php">
              <div class="sb-nav-link-icon"><i class="fa-solid fa-house"></i></div>
              Goto Homepage
            </a>
            <a class="nav-link" href="#">
              <div class="sb-nav-link-icon"><i class="fa-solid fa-house"></i></div>
              Dashboard
            </a>
            <a class="nav-link" href="#">
              <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
              My Profile
            </a>
            <a class="nav-link" href="pending-profile.html">
              <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
              Update Profile
            </a>
            <a class="nav-link" href="h-residential-info.html">
              <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
              My Hall Information
            </a>
          </div>
        </div>
        <div class="sb-sidenav-footer">
          <!-- Logout button -->
          <a href="../logout.php" class="btn btn-danger btn-lg">Logout</a>
          <div class="small">Logged in as: User (Student)</div>
        </div>

      </nav>
    </div>
    <div id="layoutSidenav_content">
      <main>
        <div class="container-fluid px-4">
          <div class="card__wrapper">
            <div class="card__title-wrap mb-20">
              <h3 class="table__heading-title mb-5">Account Details</h3>
            </div>
            <div class="card-body">
              <!-- Form Part 1: Profile Picture Update (No Admin Approval Required) -->
              <div class="card mb-4">
                <div class="card-header">
                  <i class="fa fa-info-circle text-info me-2"></i>
                  Change Profile Picture (No Admin Approval Required)
                </div>
                <div class="card-body">
                  <form action="updateProfilePic.php" method="post" enctype="multipart/form-data">
                    <div class="row align-items-center mb-4">
                      <div class="col-md-9">
                        <div class="d-inline-block position-relative me-4 mb-3 account-profile">
                          <div class="avatar-preview rounded">
                            <div id="imagePreview" class="rounded-4 profile-avatar"
                              style="background-image: url('../uploads1/<?= htmlspecialchars($file1->file_new_name) ?>');"></div>
                          </div>
                          <div class="upload-link" title="Update">
                            <input type="file" class="update-flie" id="imageUpload" name="avatar">
                            <i class="fa-solid fa-pen-to-square fs-update"></i>
                          </div>
                        </div>
                        <button type="submit" class="btn btn-primary ms-2">Update Profile Picture</button>
                      </div>
                    </div>
                  </form>
                </div>
              </div>

              <!-- Form Part 2: Profile Details Update (Admin Approval Required) -->
              <div class="card mb-4">
                <div class="card-header">
                  <i class="fa fa-info-circle text-warning me-2"></i>
                  Update Profile Details (Admin Approval Required)
                </div>
                <div class="card-body">
                  <!-- Unified Form Start -->
                  <form action="" class="profile-page-form" enctype="multipart/form-data">
                    <!-- Personal Information Section -->
                    <div class="row align-items-center mb-4">
                      <div class="col-md-6">
                        <label class="form-label mb-md-2">Full Name</label>
                        <input type="text" class="form-control" name="fullName" value="<?= htmlspecialchars($userDetails->full_name) ?>">
                      </div>
                      <div class="col-md-6">
                        <label class="form-label mb-md-2">Email</label>
                        <!-- Assuming $user holds the email -->
                        <input type="email" class="form-control" name="email" value="<?= htmlspecialchars($user->email) ?>" readonly>
                      </div>
                    </div>
                    <div class="row align-items-center mb-4">
                      <div class="col-md-6">
                        <label for="gender" class="form-label">Gender</label>
                        <select class="form-control" id="gender" name="gender" required>
                          <option value="">Select</option>
                          <option value="male" <?= ($userDetails->gender === 'male') ? 'selected' : '' ?>>Male</option>
                          <option value="female" <?= ($userDetails->gender === 'female') ? 'selected' : '' ?>>Female</option>
                          <option value="other" <?= ($userDetails->gender === 'other') ? 'selected' : '' ?>>Other</option>
                        </select>
                      </div>
                      <div class="col-md-6">
                        <label for="contactNo" class="form-label">Contact No</label>
                        <input type="text" class="form-control" id="contactNo" name="contactNo" value="<?= htmlspecialchars($userDetails->contact_no) ?>">
                      </div>
                    </div>
                    <div class="row align-items-center mb-4">
                      <div class="col-md-6">
                        <label for="studentId" class="form-label">Student ID</label>
                        <input type="text" class="form-control" id="studentId" name="studentId" value="<?= htmlspecialchars($userDetails->student_id) ?>">
                      </div>
                      <div class="col-md-6">
                        <label for="session" class="form-label">Session</label>
                        <input type="text" class="form-control" id="session" name="session" value="<?= htmlspecialchars($userDetails->session) ?>">
                      </div>
                    </div>
                    <div class="row">
                      <!-- Year Dropdown -->
                      <div class="col-md-4 mb-3">
                        <label for="year" class="form-label">Year</label>
                        <div class="custom-select-wrapper">
                          <select class="form-control" id="year" name="year">
                            <option value="1" <?= ($userDetails->year == 1) ? 'selected' : '' ?>>1st Year B.Sc.</option>
                            <option value="2" <?= ($userDetails->year == 2) ? 'selected' : '' ?>>2nd Year B.Sc.</option>
                            <option value="3" <?= ($userDetails->year == 3) ? 'selected' : '' ?>>3rd Year B.Sc.</option>
                            <option value="4" <?= ($userDetails->year == 4) ? 'selected' : '' ?>>4th Year B.Sc.</option>
                            <option value="5" <?= ($userDetails->year == 5) ? 'selected' : '' ?>>1st Year MSc</option>
                            <option value="6" <?= ($userDetails->year == 6) ? 'selected' : '' ?>>2nd Year MSc</option>
                          </select>
                          <span class="dropdown-icon"><i class="fa fa-chevron-down"></i></span>
                        </div>
                      </div>

                      <!-- Semester Dropdown -->
                      <div class="col-md-4 mb-3">
                        <label for="semester" class="form-label">Semester</label>
                        <div class="custom-select-wrapper">
                          <select class="form-control" id="semester" name="semester">
                            <option value="1" <?= ($userDetails->semester == 1) ? 'selected' : '' ?>>Semester 1</option>
                            <option value="2" <?= ($userDetails->semester == 2) ? 'selected' : '' ?>>Semester 2</option>
                          </select>
                          <span class="dropdown-icon"><i class="fa fa-chevron-down"></i></span>
                        </div>
                      </div>
                    </div>

                </div>
                <div class="row">
                  <div class="col-md-6 mb-3">
                    <label for="lastSemesterCgpa" class="form-label">Last Semester CGPA/Merit</label>
                    <input type="text" class="form-control" id="lastSemesterCgpa" name="lastSemesterCgpa" value="<?= htmlspecialchars($userDetails->last_semester_cgpa_or_merit) ?>">
                  </div>
                  <div class="col-md-6 mb-3">
                    <label for="zilla" class="form-label">Zilla</label>
                    <!-- Using district value here -->
                    <input type="text" class="form-control" id="zilla" name="zilla" value="<?= htmlspecialchars($userDetails->district) ?>">
                  </div>
                </div>
                <div class="mb-3">
                  <label for="permanentAddress" class="form-label">Permanent Address</label>
                  <textarea class="form-control" id="permanentAddress" name="permanentAddress" rows="2"><?= htmlspecialchars($userDetails->permanent_address) ?></textarea>
                </div>
                <div class="mb-3">
                  <label for="presentAddress" class="form-label">Present Address</label>
                  <textarea class="form-control" id="presentAddress" name="presentAddress" rows="2"><?= htmlspecialchars($userDetails->present_address) ?></textarea>
                </div>
                <!-- Father's Information -->
                <div class="text-center my-5">
                  <h5 class="form-info-title">Father's Information</h5>
                </div>
                <div class="row">
                  <div class="col-md-4 mb-3">
                    <label for="fatherName" class="form-label">Father's Name</label>
                    <input type="text" class="form-control" id="fatherName" name="fatherName" value="<?= htmlspecialchars($userDetails->father_name) ?>">
                  </div>
                  <div class="col-md-4 mb-3">
                    <label for="fatherContactNo" class="form-label">Father's Contact No</label>
                    <input type="text" class="form-control" id="fatherContactNo" name="fatherContactNo" value="<?= htmlspecialchars($userDetails->father_contact_no) ?>">
                  </div>
                  <div class="col-md-4 mb-3">
                    <label for="fatherProfession" class="form-label">Father's Profession</label>
                    <input type="text" class="form-control" id="fatherProfession" name="fatherProfession" value="<?= htmlspecialchars($userDetails->father_profession) ?>">
                  </div>
                </div>
                <div class="mb-3">
                  <label for="fatherMonthlyIncome" class="form-label">Father's Monthly Income</label>
                  <input type="text" class="form-control" id="fatherMonthlyIncome" name="fatherMonthlyIncome" value="<?= htmlspecialchars($userDetails->father_monthly_income) ?>">
                </div>
                <!-- Mother's Information -->
                <div class="text-center my-5">
                  <h5 class="form-info-title">Mother's Information</h5>
                </div>
                <div class="row">
                  <div class="col-md-4 mb-3">
                    <label for="motherName" class="form-label">Mother's Name</label>
                    <input type="text" class="form-control" id="motherName" name="motherName" value="<?= htmlspecialchars($userDetails->mother_name) ?>">
                  </div>
                  <div class="col-md-4 mb-3">
                    <label for="motherContactNo" class="form-label">Mother's Contact No</label>
                    <input type="text" class="form-control" id="motherContactNo" name="motherContactNo" value="<?= htmlspecialchars($userDetails->mother_contact_no) ?>">
                  </div>
                  <div class="col-md-4 mb-3">
                    <label for="motherProfession" class="form-label">Mother's Profession</label>
                    <input type="text" class="form-control" id="motherProfession" name="motherProfession" value="<?= htmlspecialchars($userDetails->mother_profession) ?>">
                  </div>
                </div>
                <div class="mb-3">
                  <label for="motherMonthlyIncome" class="form-label">Mother's Monthly Income</label>
                  <input type="text" class="form-control" id="motherMonthlyIncome" name="motherMonthlyIncome" value="<?= htmlspecialchars($userDetails->mother_monthly_income) ?>">
                </div>
                <!-- Guardian's Information -->
                <div class="text-center my-5">
                  <h5 class="form-info-title">Guardian's Information</h5>
                </div>
                <div class="row">
                  <div class="col-md-6 mb-3">
                    <label for="guardianName" class="form-label">Guardian's Name</label>
                    <input type="text" class="form-control" id="guardianName" name="guardianName" value="<?= htmlspecialchars($userDetails->guardian_name) ?>">
                  </div>
                  <div class="col-md-6 mb-3">
                    <label for="guardianContactNo" class="form-label">Guardian's Contact No</label>
                    <input type="text" class="form-control" id="guardianContactNo" name="guardianContactNo" value="<?= htmlspecialchars($userDetails->guardian_contact_no) ?>">
                  </div>
                </div>
                <div class="mb-3">
                  <label for="guardianAddress" class="form-label">Guardian's Address</label>
                  <textarea class="form-control" id="guardianAddress" name="guardianAddress" rows="2"><?= htmlspecialchars($userDetails->guardian_address) ?></textarea>
                </div>

                <!-- Document File Section -->
                <div class="mb-3">
                  <label class="form-label">Existing Document</label>
                  <?php if (!empty($file2->file_new_name)) : ?>
                    <div class="card">
                      <div class="card-body d-flex align-items-center">
                        <i class="fa fa-file-alt fa-2x text-primary me-3"></i>
                        <div>
                          <h5 class="card-title mb-1"><?= htmlspecialchars($file2->file_new_name) ?></h5>
                          <a href="../uploads1/<?= htmlspecialchars($file2->file_new_name) ?>" target="_blank" class="btn btn-sm btn-outline-primary">View Document</a>
                        </div>
                      </div>
                    </div>
                  <?php else : ?>
                    <div class="alert alert-warning" role="alert">
                      No document file available.
                    </div>
                  <?php endif; ?>
                </div>


                <!-- File Change Section -->
                <div class="mb-3">
                  <label for="formFile" class="form-label">Change Document</label>
                  <input class="form-control" type="file" id="formFile" name="changeFile" required>
                </div>

                <!-- Form Actions -->
                <div class="card-footer text-end">
                  <button type="submit" class="btn btn-primary ms-2">Save Changes</button>
                </div>
                </form>
                <!-- Unified Form End -->
              </div>
            </div>
          </div>
      </main>
    </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
    crossorigin="anonymous"></script>
  <script src="script.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js"
    crossorigin="anonymous"></script>
  <script src="assets/demo/chart-area-demo.js"></script>
  <script src="assets/demo/chart-bar-demo.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js"
    crossorigin="anonymous"></script>
  <script src="../js/datatables-simple-demo.js"></script>
  <script>
    window.addEventListener('DOMContentLoaded', event => {
      const sidebarToggle = document.body.querySelector('#sidebarToggle');
      if (sidebarToggle) {
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