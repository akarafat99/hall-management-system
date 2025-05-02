<?php
include_once '../class-file/SessionManager.php';
$session = SessionStatic::class;

include_once '../class-file/User.php';
include_once '../class-file/UserDetails.php';
include_once '../class-file/FileManager.php';
include_once '../class-file/NoteManager.php';
include_once '../class-file/Division.php';
include_once '../class-file/Department.php';
include_once '../popup-1.php';

if ($session::get('msg1')) {
    showPopup($session::get('msg1'));
    $session::delete('msg1');
}

$divisions = getDivisions();
$department = new Department();
$departmentList = $department->getDepartments(null, 1);
$yearSemesterCodes = $department->getYearSemesterCodes();

if (isset($_POST['register'])) {
    // Insert User
    $user  = new User();
    $user->email        = $_POST['email'];
    $user->password     = $_POST['password'];
    $user->user_type = 'admin';
    $user->status       = 0;

    $result = $user->isEmailAvailable($user->email, [0, 1]);

    if ($result) {
        $session::set('msg1', 'Email already exists. Please try another one.');
        echo "<script>window.location='signup.php';</script>";
        exit;
    }

    $user->insert();

    // Insert User Details
    $detail = new UserDetails();
    $detail->status         = 1;
    $detail->user_id         = $user->user_id;
    $detail->full_name       = $_POST['fullName'];
    $detail->contact_no      = $_POST['contactNo'];
    $detail->gender        = $_POST['gender'];
    $detail->division        = $_POST['division'];
    $detail->district        = $_POST['district'];
    $detail->permanent_address = $_POST['permanentAddress'];
    $detail->present_address   = $_POST['presentAddress'];

    $detail->insert();

    /* ---- profile photo ---- */
    $file1              = new FileManager();
    $file1->file_owner_id = $user->user_id;
    $file1->file_id       = $file1->insert();
    if ($file1->doOp($_FILES['profilePhoto']) == 1) {
        $file1->update();
        $detail->profile_picture_id = $file1->file_id;
    }

    $detail->update();

    $session::set('msg1', 'Registration successful. Please wait for super admin approval.');
    echo "<script>window.location='login.php';</script>";
    exit;
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css"
        rel="stylesheet" />

    <!-- Google Fonts & Icons -->
    <link href="https://fonts.googleapis.com/css?family=Muli:300,400,700,900" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" />

    <!-- for sidebar -->
    <link href="../css2/sidebar-admin.css" rel="stylesheet" />

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
    <!-- Main Content Area -->
    <main class="col">
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

                            <div class="col-12 col-md-6 mb-3">
                                <label for="email" class="form-label fw-semibold">Email</label>
                                <input class="form-control" type="email" id="email" name="email" placeholder="email" required>
                            </div>

                            <div class="col-12 col-md-6 mb-3">
                                <label for="password" class="form-label fw-semibold">Password</label>
                                <input class="form-control" type="password" id="password" name="password" placeholder="password" required>
                            </div>

                            <div class="col-12 col-md-12 mb-3">
                                <label for="fullName" class="form-label fw-semibold">Full Name</label>
                                <input class="form-control" type="text" id="fullName" name="fullName" placeholder="Full Name" required>
                            </div>

                            <div class="col-12 col-md-12 mb-3">
                                <label for="gender" class="form-label fw-semibold">Gender</label>
                                <select class="form-select" id="gender" name="gender" required>
                                    <option value="male" selected>Male</option>
                                    <option value="female">Female</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>

                            <div class="col-12 col-md-12 mb-3">
                                <label for="contactNo" class="form-label fw-semibold">Contact No</label>
                                <input class="form-control" type="tel" id="contactNo" name="contactNo" placeholder="+8801XXXXXXXXX" required>
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
                        </div>

                        <div class="d-grid mt-5">
                            <button class="btn btn-primary btn-lg" type="submit" name="register">Create Account</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </main>

    <!-- Bootstrap Bundle with Popper -->
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