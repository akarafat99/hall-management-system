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


if ($session::get('user') == null) {
  echo "<script>window.location.href = '../login.php';</script>";
  exit;
}

if ($session::get('msg1') != null) {
  showPopup($session::get('msg1'));
  $session::delete('msg1');
}

$user = new User();
$userDetails = new UserDetails();
$department = new Department();
$yearSemesterCode = $department->getYearSemesterCodes();

// load user details
if (isset($_POST['userDetailsId']) || isset($_GET['userDetailsId'])) {
  $userDetails->details_id = $_POST['userDetailsId'] ?? $_GET['userDetailsId'];
  $userDetails->getByDetailsId($userDetails->details_id);
  $user->user_id = $userDetails->user_id;
  $user->load();
} else {
  echo "<script>window.location.href = 'profile.php';</script>";
  exit;
}

$department->getDepartments($userDetails->department_id);

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

  <!-- For navbar -->
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Google Fonts: Roboto for Material Design look -->
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">

  <!-- Font Awesome Icons -->
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

<body>
      <!-- Main Content Area -->
      <main>
        <div class="container-fluid px-4">
          <div class="card__wrapper">
            <div class="card__title-wrap mb-20">
              <h3 class="table__heading-title mb-5">Profile Details</h3>
            </div>
            <div class="card-body">
              <!-- Form Part 1: Profile Picture Display -->
              <div class="card mb-4">
                <div class="card-header">
                  <i class="fa fa-info-circle text-info me-2"></i>
                  Profile Picture
                </div>
                <div class="card-body">
                  <div class="row align-items-center mb-4">
                    <div class="col-md-9">
                      <div class="d-inline-block position-relative me-4 mb-3 account-profile">
                        <div class="avatar-preview rounded">
                          <div id="imagePreview" class="rounded-4 profile-avatar"
                            style="background-image: url('../uploads1/<?php echo  htmlspecialchars($file1->file_new_name) ?>');"></div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Modified section to display read-only profile details -->
              <div class="card mb-4">
                <div class="card-header">
                  <i class="fa fa-info-circle text-warning me-2"></i>
                  Profile Details
                </div>
                <div class="card-body">
                  <!-- Personal Information Section -->
                  <div class="row mb-2">
                    <div class="col-md-6">
                      <label class="form-label mb-md-2">Full Name</label>
                      <p><?php echo  htmlspecialchars($userDetails->full_name) ?></p>
                    </div>
                    <div class="col-md-6">
                      <label class="form-label mb-md-2">Email</label>
                      <p><?php echo  htmlspecialchars($user->email) ?></p>
                    </div>
                  </div>
                  <div class="row mb-2">
                    <div class="col-md-6">
                      <label class="form-label">Gender</label>
                      <p><?php echo  htmlspecialchars($userDetails->gender) ?></p>
                    </div>
                    <div class="col-md-6">
                      <label class="form-label">Contact No</label>
                      <p><?php echo  htmlspecialchars($userDetails->contact_no) ?></p>
                    </div>
                  </div>
                  <div class="row mb-2">
                    <div class="col-md-6">
                      <label class="form-label">Student ID</label>
                      <p><?php echo  htmlspecialchars($userDetails->student_id) ?></p>
                    </div>
                    <div class="col-md-6">
                      <label class="form-label">Session</label>
                      <p><?php echo  htmlspecialchars($userDetails->session) ?></p>
                    </div>
                  </div>
                  <div class="row mb-2">
                    <div class="col-md-6">
                      <label class="form-label">Department</label>
                      <p><?php echo  htmlspecialchars($department->department_name . ' (' . $department->department_short_form . ')') ?></p>
                    </div>
                  </div>
                  <div class="row mb-2">
                    <div class="col-md-6">
                      <label class="form-label">Academic Status</label>
                      <p><?php echo $yearSemesterCode[$userDetails->year_semester_code];  ?></p>
                    </div>
                    <div class="col-md-6">
                      <label class="form-label">Last Semester CGPA/Merit</label>
                      <p><?php echo  htmlspecialchars($userDetails->last_semester_cgpa_or_merit) ?></p>
                    </div>
                  </div>
                  <div class="mb-2">
                    <label class="form-label">Zilla</label>
                    <p><?php echo  htmlspecialchars($userDetails->district) ?></p>
                  </div>
                  <div class="mb-2">
                    <label class="form-label">Permanent Address</label>
                    <p><?php echo  nl2br(htmlspecialchars($userDetails->permanent_address)) ?></p>
                  </div>
                  <div class="mb-2">
                    <label class="form-label">Present Address</label>
                    <p><?php echo  nl2br(htmlspecialchars($userDetails->present_address)) ?></p>
                  </div>
                  <!-- Father's Information -->
                  <div class="text-center my-4">
                    <h5>Father's Information</h5>
                  </div>
                  <div class="row mb-2">
                    <div class="col-md-4">
                      <label class="form-label">Father's Name</label>
                      <p><?php echo  htmlspecialchars($userDetails->father_name) ?></p>
                    </div>
                    <div class="col-md-4">
                      <label class="form-label">Father's Contact No</label>
                      <p><?php echo  htmlspecialchars($userDetails->father_contact_no) ?></p>
                    </div>
                    <div class="col-md-4">
                      <label class="form-label">Father's Profession</label>
                      <p><?php echo  htmlspecialchars($userDetails->father_profession) ?></p>
                    </div>
                  </div>
                  <div class="mb-2">
                    <label class="form-label">Father's Monthly Income</label>
                    <p><?php echo  htmlspecialchars($userDetails->father_monthly_income) ?></p>
                  </div>
                  <!-- Mother's Information -->
                  <div class="text-center my-4">
                    <h5>Mother's Information</h5>
                  </div>
                  <div class="row mb-2">
                    <div class="col-md-4">
                      <label class="form-label">Mother's Name</label>
                      <p><?php echo  htmlspecialchars($userDetails->mother_name) ?></p>
                    </div>
                    <div class="col-md-4">
                      <label class="form-label">Mother's Contact No</label>
                      <p><?php echo  htmlspecialchars($userDetails->mother_contact_no) ?></p>
                    </div>
                    <div class="col-md-4">
                      <label class="form-label">Mother's Profession</label>
                      <p><?php echo  htmlspecialchars($userDetails->mother_profession) ?></p>
                    </div>
                  </div>
                  <div class="mb-2">
                    <label class="form-label">Mother's Monthly Income</label>
                    <p><?php echo  htmlspecialchars($userDetails->mother_monthly_income) ?></p>
                  </div>
                  <!-- Guardian's Information -->
                  <div class="text-center my-4">
                    <h5>Guardian's Information</h5>
                  </div>
                  <div class="row mb-2">
                    <div class="col-md-6">
                      <label class="form-label">Guardian's Name</label>
                      <p><?php echo  htmlspecialchars($userDetails->guardian_name) ?></p>
                    </div>
                    <div class="col-md-6">
                      <label class="form-label">Guardian's Contact No</label>
                      <p><?php echo  htmlspecialchars($userDetails->guardian_contact_no) ?></p>
                    </div>
                  </div>
                  <div class="mb-2">
                    <label class="form-label">Guardian's Address</label>
                    <p><?php echo  nl2br(htmlspecialchars($userDetails->guardian_address)) ?></p>
                  </div>
                  <!-- Document File Section -->
                  <div class="mb-2">
                    <label class="form-label">Document</label>
                    <?php if (!empty($file2->file_new_name)) : ?>
                      <div class="card">
                        <div class="card-body d-flex align-items-center">
                          <i class="fa fa-file-alt fa-2x text-primary me-3"></i>
                          <div>
                            <h5 class="card-title mb-1"><?php echo  htmlspecialchars($file2->file_new_name) ?></h5>
                            <a href="../uploads1/<?php echo  htmlspecialchars($file2->file_new_name) ?>" target="_blank" class="btn btn-sm btn-outline-primary">View Document</a>
                          </div>
                        </div>
                      </div>
                    <?php else : ?>
                      <div class="alert alert-warning" role="alert">
                        No document file available.
                      </div>
                    <?php endif; ?>
                  </div>
                </div>
              </div>
            </div>
          </div>
      </main>


  <!-- Bootstrap Bundle with Popper -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <!-- for the sidebar and phone menu -->
  <script src="../js2/custom1.js"></script>

</body>

</html>