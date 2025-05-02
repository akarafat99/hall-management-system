<?php
include_once 'class-file/SessionManager.php';
include_once 'class-file/User.php';
include_once 'class-file/UserDetails.php';
include_once 'class-file/FileManager.php';
include_once 'class-file/NoteManager.php';
include_once 'class-file/Division.php';
include_once 'class-file/Department.php';

$session = SessionStatic::class;
$divisions = getDivisions();
$department = new Department();
$departmentList = $department->getDepartments(null, 1);
$yearSemesterCodes = $department->getYearSemesterCodes();

if (isset($_POST['register'])) {
    // Insert User
    $user  = new User();
    $temp  = $session::getObject('signup_user');
    $session::copyProperties($temp, $user);
    $user->user_type = 'user';
    $user->insert();

    // Insert User Details
    $detail = new UserDetails();
    $detail->user_id                    = $user->user_id;
    $detail->full_name                  = $_POST['fullName'];
    $detail->student_id                 = $_POST['studentId'];
    $detail->gender                     = $_POST['gender'];
    $detail->contact_no                 = $_POST['contactNo'];
    $detail->session                    = $_POST['session'];
    $detail->department_id              = $_POST['department'];
    $detail->year_semester_code         = $_POST['year_semester_code'];
    $detail->last_semester_cgpa_or_merit = $_POST['university-merit-or-cgpa'];
    $detail->division                   = $_POST['division'];
    $detail->district                   = $_POST['district'];
    $detail->permanent_address          = $_POST['permanentAddress'];
    $detail->present_address            = $_POST['presentAddress'];
    $detail->father_name                = $_POST['fatherName'];
    $detail->father_contact_no          = $_POST['fatherContactNo'];
    $detail->father_profession          = $_POST['fatherProfession'];
    $detail->father_monthly_income      = $_POST['fatherMonthlyIncome'];
    $detail->mother_name                = $_POST['motherName'];
    $detail->mother_contact_no          = $_POST['motherContactNo'];
    $detail->mother_profession          = $_POST['motherProfession'];
    $detail->mother_monthly_income      = $_POST['motherMonthlyIncome'];
    $detail->guardian_name              = $_POST['guardianName'];
    $detail->guardian_contact_no        = $_POST['guardianContactNo'];
    $detail->guardian_address           = $_POST['guardianAddress'];
    $detail->insert();

    /* ---- profile photo ---- */
    $file1              = new FileManager();
    $file1->file_owner_id = $user->user_id;
    $file1->file_id       = $file1->insert();
    if ($file1->doOp($_FILES['profilePhoto']) === 1) {
        $file1->update();
        $detail->profile_picture_id = $file1->file_id;
    }

    /* ---- document ---- */
    $file2              = new FileManager();
    $file2->file_owner_id = $user->user_id;
    $file2->file_id       = $file2->insert();
    if ($file2->doOp($_FILES['formFile']) === 1) {
        $file2->update();
        $detail->document_id = $file2->file_id;
    }

    $detail->update();

    $session::delete('signup_user');
    $session::set('msg1', 'Registration successful. Please login.');
    echo "<script>window.location='index.php';</script>";
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>MM Hall ‑ Registration</title>

    <!-- Google Fonts & Icons -->
    <link href="https://fonts.googleapis.com/css?family=Muli:300,400,700,900" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" />

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">

    <style>
        /* Enhanced selects */
        .form-select {
            border-radius: .75rem;
            background: #f8f9fa;
            border: 1px solid #ced4da;
            padding: .75rem 1rem;
            transition: border-color .2s, box-shadow .2s
        }

        .form-select:focus {
            border-color: #80bdff;
            box-shadow: 0 0 0 .2rem rgba(0, 123, 255, .25)
        }

        .form-control,
        textarea.form-control {
            border-radius: .5rem;
            padding: .75rem 1rem
        }

        .card-header {
            background: #007bff;
            color: #fff
        }

        /* never let a .form-select grow wider than its parent */
        .form-select.w-100 {
            max-width: 100%;
        }

        /* wrap text *inside* the dropdown list so long options are readable there */
        .form-select.w-100 option {
            white-space: normal;
            overflow-wrap: anywhere;
        }

        /* but truncate the single selected line to keep the control itself narrow */
        .form-select.w-100.text-truncate {
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
    </style>
</head>

<body>
    <div class="d-flex flex-column min-vh-100">

        <?php include_once 'student/navbar-student-2.php'; ?>

        <div class="container my-5">
            <div class="card border-0 shadow-sm">
                <!-- Card header -->
                <div class="card-header bg-light border-0 text-center py-3">
                    <h4 class="mb-0 fw-bold text-primary text-uppercase d-inline-flex align-items-center gap-2">
                        <!-- icon adds a visual cue (optional) -->
                        <i class="fas fa-user-plus"></i>
                        Registration
                    </h4>
                </div>


                <div class="card-body p-4 p-lg-5">
                    <form method="post" enctype="multipart/form-data" class="mx-auto" style="max-width: 900px;">

                        <!-- Personal Information -->
                        <h5 class="fw-semibold mb-4">Personal Information</h5>

                        <div class="row">
                            <div class="col-12 col-md-12 mb-3">
                                <label for="profilePhoto" class="form-label fw-semibold">Profile Photo</label>
                                <input class="form-control" type="file" id="profilePhoto" name="profilePhoto" accept="image/*" required>
                            </div>

                            <div class="col-12 mb-3">
                                <div class="p-3 border rounded bg-light">
                                    <i class="fas fa-info-circle text-info me-2"></i>
                                    <span class="small text-muted">
                                        Please combine the following into a <strong>single PDF</strong> before uploading:
                                        <ul class="mb-0 ps-3">
                                            <li>Nationality certificate</li>
                                            <li>Student ID card</li>
                                            <li>Father’s monthly income certificate</li>
                                            <li>Last semester result or admission rank certificate</li>
                                        </ul>
                                    </span>
                                </div>
                            </div>
                            <div class="col-12 col-md-12 mb-3">
                                <label for="formFile" class="form-label fw-semibold">Document (scanned copy)</label>
                                <input class="form-control" type="file" id="formFile" name="formFile" required>
                            </div>

                            <div class="col-12 col-md-12 mb-3">
                                <label for="fullName" class="form-label fw-semibold">Full Name</label>
                                <input class="form-control" type="text" id="fullName" name="fullName" placeholder="Enter full name" required>
                            </div>

                            <div class="col-12 col-md-12 mb-3">
                                <label for="gender" class="form-label fw-semibold">Gender</label>
                                <select class="form-select" id="gender" name="gender" required>
                                    <option value="male" selected>Male</option>
                                    <option value="female">Female</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>

                            <div class="col-12 col-md-6 mb-3">
                                <label for="studentId" class="form-label fw-semibold">Student ID</label>
                                <input class="form-control" type="text" id="studentId" name="studentId" placeholder="Enter student ID" required>
                            </div>
                            <div class="col-12 col-md-6 mb-3">
                                <label for="contactNo" class="form-label fw-semibold">Contact No</label>
                                <input class="form-control" type="tel" id="contactNo" name="contactNo" placeholder="+8801XXXXXXXXX" required>
                            </div>

                            <!-- Session (full‑width) -->
                            <div class="col-12 mb-3">
                                <label for="session" class="form-label fw-semibold">Session</label>
                                <input class="form-control" type="text" id="session" name="session"
                                    placeholder="2022–2023" required>
                            </div>

                            <!-- Department -->
                            <div class="col-12 mb-3">
                                <label for="department" class="form-label fw-semibold">Department</label>

                                <!-- w-100 fixes the width; text-truncate prevents overflow; -->
                                <select class="form-select w-100 text-truncate" id="department"
                                    name="department" required>
                                    <?php foreach ($departmentList as $dept): ?>
                                        <option value="<?php echo htmlspecialchars($dept['department_id'], ENT_QUOTES); ?>">
                                            <?php echo htmlspecialchars($dept['department_name'] . ' (' . $dept['department_short_form'] . ')'); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>


                            <!-- Year & Semester -->
                            <div class="col-12 mb-3">
                                <label for="yearSemester" class="form-label fw-semibold">Year &amp; Semester</label>
                                <select
                                    id="yearSemester"
                                    name="year_semester_code"
                                    class="form-select w-100 text-truncate"
                                    required>
                                    <?php foreach ($yearSemesterCodes as $code => $label): ?>
                                        <option value="<?php echo htmlspecialchars($code, ENT_QUOTES); ?>">
                                            <?php echo htmlspecialchars($label); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>


                            <!-- Merit / CGPA -->
                            <div class="col-12 mb-3">
                                <label for="dynamic-input" class="form-label fw-semibold" id="dynamic-label">
                                    University Merit / CGPA
                                </label>

                                <input class="form-control"
                                    type="number"
                                    step="any"
                                    min="0"
                                    id="dynamic-input"
                                    name="university-merit-or-cgpa"
                                    placeholder="Enter value"
                                    required>

                                <!-- new help‑text -->
                                <small id="dynamic-help" class="form-text text-muted">
                                    For B.Sc. 1st year 1st semester and M.Sc. 1st year 1st semester, enter your merit list position.
                                    All other students should enter their last‑semester CGPA.
                                </small>
                            </div>


                            <div class="col-12 col-md-6 mb-3">
                                <label for="division" class="form-label fw-semibold">Division</label>
                                <select class="form-select" id="division" name="division" required>
                                    <?php foreach ($divisions as $divisionName => $districtList) : ?>
                                        <option value="<?php echo htmlspecialchars($divisionName, ENT_QUOTES); ?>">
                                            <?php echo htmlspecialchars($divisionName); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="col-12 col-md-6 mb-3">
                                <label for="district" class="form-label fw-semibold">District</label>
                                <select class="form-select" id="district" name="district" required></select>
                            </div>

                            <div class="col-12 mb-3">
                                <label for="permanentAddress" class="form-label fw-semibold">Permanent Address</label>
                                <textarea class="form-control" id="permanentAddress" name="permanentAddress" rows="3" placeholder="Enter permanent address" required></textarea>
                            </div>
                            <div class="col-12 mb-3">
                                <label for="presentAddress" class="form-label fw-semibold">Present Address</label>
                                <textarea class="form-control" id="presentAddress" name="presentAddress" rows="3" placeholder="Enter present address" required></textarea>
                            </div>
                        </div><!-- /.row -->

                        <!-- Father’s Information -->
                        <h5 class="fw-semibold mt-5 mb-4">Father’s Information</h5>
                        <div class="row">
                            <div class="col-12 col-md-4 mb-3">
                                <label for="fatherName" class="form-label fw-semibold">Father’s Name</label>
                                <input class="form-control" type="text" id="fatherName" name="fatherName" required>
                            </div>
                            <div class="col-12 col-md-4 mb-3">
                                <label for="fatherContactNo" class="form-label fw-semibold">Contact No</label>
                                <input class="form-control" type="tel" id="fatherContactNo" name="fatherContactNo" required>
                            </div>
                            <div class="col-12 col-md-4 mb-3">
                                <label for="fatherProfession" class="form-label fw-semibold">Profession</label>
                                <input class="form-control" type="text" id="fatherProfession" name="fatherProfession" required>
                            </div>
                            <div class="col-12 mb-3">
                                <label for="fatherMonthlyIncome" class="form-label fw-semibold">Monthly Income</label>
                                <input class="form-control" type="number" id="fatherMonthlyIncome" name="fatherMonthlyIncome" required>
                            </div>
                        </div>

                        <!-- Mother’s Information -->
                        <h5 class="fw-semibold mt-5 mb-4">Mother’s Information</h5>
                        <div class="row">
                            <div class="col-12 col-md-4 mb-3">
                                <label for="motherName" class="form-label fw-semibold">Mother’s Name</label>
                                <input class="form-control" type="text" id="motherName" name="motherName" required>
                            </div>
                            <div class="col-12 col-md-4 mb-3">
                                <label for="motherContactNo" class="form-label fw-semibold">Contact No</label>
                                <input class="form-control" type="tel" id="motherContactNo" name="motherContactNo" required>
                            </div>
                            <div class="col-12 col-md-4 mb-3">
                                <label for="motherProfession" class="form-label fw-semibold">Profession</label>
                                <input class="form-control" type="text" id="motherProfession" name="motherProfession" required>
                            </div>
                            <div class="col-12 mb-3">
                                <label for="motherMonthlyIncome" class="form-label fw-semibold">Monthly Income</label>
                                <input class="form-control" type="number" id="motherMonthlyIncome" name="motherMonthlyIncome" required>
                            </div>
                        </div>

                        <!-- Guardian’s Information -->
                        <h5 class="fw-semibold mt-5 mb-4">Guardian’s Information</h5>
                        <div class="row">
                            <div class="col-12 col-md-6 mb-3">
                                <label for="guardianName" class="form-label fw-semibold">Guardian’s Name</label>
                                <input class="form-control" type="text" id="guardianName" name="guardianName" required>
                            </div>
                            <div class="col-12 col-md-6 mb-3">
                                <label for="guardianContactNo" class="form-label fw-semibold">Contact No</label>
                                <input class="form-control" type="tel" id="guardianContactNo" name="guardianContactNo" required>
                            </div>
                            <div class="col-12 mb-3">
                                <label for="guardianAddress" class="form-label fw-semibold">Guardian’s Address</label>
                                <textarea class="form-control" id="guardianAddress" name="guardianAddress" rows="3" required></textarea>
                            </div>
                        </div>

                        <div class="d-grid mt-5">
                            <button class="btn btn-primary btn-lg" type="submit" name="register">Create Account</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>



        <footer class="bg-dark text-white text-center py-3 mt-auto">
            <div class="container">
                <p class="mb-0">&copy; <?= date('Y') ?> JUST MM Hall</p>
            </div>
        </footer>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Division → District cascade -->
    <script>
        const divisionData = <?= json_encode($divisions, JSON_UNESCAPED_UNICODE) ?>;

        function populateDistricts(division) {
            const districtSel = document.getElementById('district');
            districtSel.innerHTML = ''; // ← wipe everything, no placeholder

            if (!division || !divisionData[division]) return;

            Object.keys(divisionData[division]).forEach(name => {
                districtSel.add(new Option(name, name));
            });
        }

        document.addEventListener('DOMContentLoaded', () => {
            const divSel = document.getElementById('division');

            populateDistricts(divSel.value); // useful on form re‑render
            divSel.addEventListener('change', () => populateDistricts(divSel.value));
        });
    </script>




</body>

</html>