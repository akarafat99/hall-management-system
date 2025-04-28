<?php
include_once '../class-file/SessionManager.php';
$session = SessionStatic::class;

include_once '../class-file/User.php';
include_once '../class-file/UserDetails.php';
include_once '../class-file/FileManager.php';
include_once '../class-file/Department.php';
include_once '../class-file/Division.php';
include_once '../popup-1.php';
include_once '../class-file/Auth.php';
auth('user');


if ($session::get('msg1') != null) {
  showPopup($session::get('msg1'));
  $session::delete('msg1');
}

// load user details
$sUser = $session::getObject('userObj');
$user = new User();
$session::copyProperties($sUser, $user);

$userDetails = new UserDetails();
$userDetails->user_id = $user->user_id;
$userDetails->getUsers($userDetails->user_id, null, 1);
$session::storeObject('userDetails', $userDetails);

$department = new Department();
$allDepartments = $department->getDepartments(null, 1);
$department->getDepartments($userDetails->department_id);

$divisions = getDivisions();

$yearSemesterCode = $department->getYearSemesterCodes();

$editPending = $userDetails->isRecordAvailable($userDetails->user_id, null, 0);

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
  <div class="d-flex flex-column min-vh-100">
    <div class="flex-grow-1">
      <!-- Navbar Section Start -->
      <?php include_once 'navbar-student-1.php'; ?>
      <!-- Navbar Section End  -->

      <!-- Info Card (shown when an edit request is pending) -->
      <?php if ($editPending != 0): ?>
        <div class="container-fluid px-4 mt-3">
          <div class="alert alert-info d-flex justify-content-between align-items-center" role="alert">
            <form action="view-profile.php" method="post" enctype="multipart/form-data">
              <span>An edit request has already been submitted.</span>
              <button type="submit" name="viewEditRequest" value="<?php echo $editPending; ?>" class="btn btn-info">View Edit Request</button>
            </form>
            <form action="delete-edit-request-profile.php" method="post" enctype="multipart/form-data">
              <button type="submit" name="deleteEditRequest" value="<?php echo $editPending; ?>" class="btn btn-danger">Delete Edit Request</button>
            </form>
          </div>
        </div>
      <?php endif; ?>

      <!-- Main Content Area -->
      <main>
        <div class="container-fluid px-4">
          <div class="card__wrapper">
            <div class="card__title-wrap mb-20">
              <h3 class="table__heading-title mb-5">Profile Details</h3>
            </div>
            <div class="card-body">
              <!-- Form Part 1: Profile Picture Update (No Admin Approval Required) -->
              <!-- Change Profile Picture -->
              <div class="card mb-4">
                <div class="card-header">
                  <i class="fa fa-info-circle text-info me-2"></i>
                  Change Profile Picture <small class="text-muted">(No admin approval required)</small>
                </div>

                <div class="card-body">
                  <form action="update-profile-picture.php" method="post" enctype="multipart/form-data">
                    <div class="row g-4 align-items-center">

                      <!-- 1.5× larger preview -->
                      <div class="col-auto">
                        <div class="position-relative account-profile">
                          <div class="avatar-preview rounded">
                            <div id="imagePreview"
                              class="rounded-4 profile-avatar border"
                              style="
                                      width: 150px;              /* ← 1.5× */
                                      height: 150px;             /* ← 1.5× */
                                      background-image: url('../uploads1/<?= htmlspecialchars($file1->file_new_name) ?>');
                                      background-size: cover;
                                      background-position: center;
                                  ">
                            </div>
                          </div>
                        </div>
                      </div>

                      <!-- nicer file input + button -->
                      <div class="col">
                        <div class="input-group">
                          <label class="input-group-text" for="imageUpload">
                            Select image
                            <input class="form-control" type="file" id="imageUpload" name="profileImage" accept="image/*" required>
                          </label>
                        </div>

                        <button type="submit" name="updateProfilePicture"
                          class="btn btn-primary mt-3">
                          Update Profile Picture
                        </button>
                      </div>

                    </div><!-- /.row -->
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
                  <form action="update-profile-details.php" class="profile-page-form" method="post" enctype="multipart/form-data">
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

                    <!-- Department Dropdown -->
                    <div class="row mb-4">
                      <div class="col-md-6 mb-3">
                        <label for="department" class="form-label">Department</label>

                        <div class="custom-select-wrapper">
                          <select class="form-control" id="department" name="departmentId">
                            <?php
                            $deptFound = false;

                            /* normal list */
                            foreach ($allDepartments as $dept) {
                              $selected = ($userDetails->department_id == $dept['department_id']);
                              if ($selected) {
                                $deptFound = true;
                              }

                              echo '<option value="' . htmlspecialchars($dept['department_id']) . '"' .
                                ($selected ? ' selected' : '') . '>' .
                                htmlspecialchars($dept['department_name']) . '</option>';
                            }

                            /* fallback if the saved ID isn’t in the list */
                            if (!$deptFound) {
                              if (!empty($userDetails->department_id)) {
                                echo '<option value="' . $userDetails->department_id .
                                  '" selected disabled>' .
                                  'Unknown Department (ID ' . $userDetails->department_id . ')</option>';
                              } else {
                                echo '<option value="" selected disabled>' .
                                  '— Select Department —</option>';
                              }
                            }
                            ?>
                          </select>
                          <span class="dropdown-icon"><i class="fa fa-chevron-down"></i></span>
                        </div>
                      </div>
                    </div>


                    <!-- Year & Semester -->
                    <div class="row mb-4">
                      <div class="col-md-6 mb-3">
                        <label for="yearSemester" class="form-label">Year &amp; Semester</label>

                        <div class="custom-select-wrapper">
                          <select
                            id="yearSemester"
                            name="yearSemesterCode"
                            class="form-control"
                            required>
                            <?php foreach ($yearSemesterCode as $code => $label): ?>
                              <option
                                value="<?php echo htmlspecialchars($code, ENT_QUOTES); ?>"
                                <?php if ($userDetails->year_semester_code == $code) {
                                  echo ' selected';
                                } ?>>
                                <?php echo htmlspecialchars($label); ?>
                              </option>
                            <?php endforeach; ?>
                          </select>
                          <span class="dropdown-icon">
                            <i class="fas fa-chevron-down"></i>
                          </span>
                        </div>

                      </div>
                    </div>


                </div>
                <div class="row">
                  <div class="col-md-6 mb-3">
                    <label for="lastSemesterCgpa" class="form-label">Last Semester CGPA/Merit</label>
                    <input type="text" class="form-control" id="lastSemesterCgpa" name="lastSemesterCgpa" value="<?= htmlspecialchars($userDetails->last_semester_cgpa_or_merit) ?>">
                  </div>
                </div>

                <!-- Division and district -->
                <div class="row mb-4">
                  <div class="col-md-6 mb-3">
                    <label for="division" class="form-label fw-semibold">Division</label>
                    <select class="form-select" id="division" name="division" required>
                      <?php foreach ($divisions as $divisionName => $districtList): ?>
                        <option
                          value="<?= htmlspecialchars($divisionName, ENT_QUOTES) ?>"
                          <?= $userDetails->division === $divisionName ? 'selected' : '' ?>>
                          <?= htmlspecialchars($divisionName) ?>
                        </option>
                      <?php endforeach; ?>
                    </select>
                  </div>

                  <div class="col-md-6 mb-3">
                    <label for="district" class="form-label fw-semibold">District</label>
                    <select class="form-select" id="district" name="district" required></select>
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
                  <input class="form-control" type="file" id="formFile" name="changeFile">
                </div>

                <!-- Form Actions -->
                <div class="card-footer text-end">
                  <?php if ($editPending == 0): ?>
                    <button type="submit" name="editDetails" value="<?php echo $userDetails->details_id; ?>" class="btn btn-primary ms-2">Save Changes</button>
                  <?php else: ?>
                    <div class="alert alert-warning mb-0" role="alert">
                      A pending edit request already exists. Please delete that request to submit another edit request.
                    </div>
                  <?php endif; ?>
                </div>

                </form>
                <!-- Unified Form End -->
              </div>
            </div>
          </div>
      </main>

    </div>

    <!-- Footer -->
    <footer class="bg-dark text-white text-center py-3 mt-auto">
      <div class="container">
        <p class="mb-0">&copy; <?php echo date('Y'); ?> JUST MM Hall</p>
      </div>
    </footer>
  </div>


  <!-- Bootstrap Bundle with Popper -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

  <!-- Division → District cascade -->
  <script>
    const divisionData = <?= json_encode($divisions, JSON_UNESCAPED_UNICODE) ?>;
    const selectedDistrict = <?= json_encode($userDetails->district, JSON_UNESCAPED_UNICODE) ?>;

    function populateDistricts(divName) {
      const distSel = document.getElementById('district');
      distSel.innerHTML = '';

      // grab whatever is stored under this division
      let list = divisionData[divName] || [];

      // if it's an object, turn its keys into an array
      if (!Array.isArray(list) && typeof list === 'object') {
        list = Object.keys(list);
      }

      // now list.forEach is safe
      list.forEach(d => {
        const opt = new Option(d, d);
        if (d === selectedDistrict) opt.selected = true;
        distSel.add(opt);
      });
    }

    document.addEventListener('DOMContentLoaded', () => {
      const divSel = document.getElementById('division');
      populateDistricts(divSel.value);
      divSel.addEventListener('change', e => {
        populateDistricts(e.target.value);
      });
    });
  </script>


</body>

</html>