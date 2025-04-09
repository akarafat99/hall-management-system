<?php
include_once 'class-file/SessionManager.php';
include_once 'class-file/User.php';
include_once 'class-file/UserDetails.php';
include_once 'class-file/FileManager.php';
include_once 'class-file/NoteManager.php';
include_once 'class-file/Division.php';  // Include the Division class

$session = SessionStatic::class;
// Get divisions array from the Division class.
$divisions = getDivisions();

if (isset($_POST['register'])) {
    $user = new User();
    $temp_user = $session::getObject('signup_user');
    $user->user_type = "user";
    $session::copyProperties($temp_user, $user);
    $user->insert();

    $userDetails = new UserDetails();
    $userDetails->user_id = $user->user_id;

    $userDetails->full_name = $_POST['fullName'];
    $userDetails->student_id = $_POST['studentId'];
    $userDetails->gender = $_POST['gender'];
    $userDetails->contact_no = $_POST['contactNo'];
    $userDetails->session = $_POST['session'];
    $userDetails->year = $_POST['year'];
    $userDetails->semester = $_POST['semester'];
    $userDetails->last_semester_cgpa_or_merit = $_POST['university-merit-or-cgpa'];
    $userDetails->division = $_POST['division'];
    $userDetails->district = $_POST['district'];
    $userDetails->permanent_address = $_POST['permanentAddress'];
    $userDetails->present_address = $_POST['presentAddress'];
    $userDetails->father_name = $_POST['fatherName'];
    $userDetails->father_contact_no = $_POST['fatherContactNo'];
    $userDetails->father_profession = $_POST['fatherProfession'];
    $userDetails->father_monthly_income = $_POST['fatherMonthlyIncome'];
    $userDetails->mother_name = $_POST['motherName'];
    $userDetails->mother_contact_no = $_POST['motherContactNo'];
    $userDetails->mother_profession = $_POST['motherProfession'];
    $userDetails->mother_monthly_income = $_POST['motherMonthlyIncome'];
    $userDetails->guardian_name = $_POST['guardianName'];
    $userDetails->guardian_contact_no = $_POST['guardianContactNo'];
    $userDetails->guardian_address = $_POST['guardianAddress'];

    $userDetails->insert();

    $file1 = new FileManager();
    $file1->file_owner_id = $user->user_id;
    $file1->file_id = $file1->insert();
    $ans = $file1->doOp($_FILES['profilePhoto']);
    if ($ans == 1) {
        // echo 'Profile photo uploaded <br>';
        $file1->update();
    } else {
        // echo 'Profile photo upload failed <br>';
    }

    $file2 = new FileManager();
    $file2->file_owner_id = $user->user_id;
    $file2->file_id = $file2->insert();
    $ans = $file2->doOp($_FILES['formFile']);
    if ($ans == 1) {
        // echo 'Document uploaded <br>';
        $file2->update();
    } else {
        // echo 'Document upload failed <br>';
    }

    $userDetails->profile_picture_id = $file1->file_id;
    $userDetails->document_id = $file2->file_id;
    $userDetails->update();

    // echo 'All done <br>';
    $session::delete('signup_user');
    $session::set('msg1', 'Registration successful. Please login to continue.'); // Set success message
    // $session::destroy();
    echo "<script>window.location = 'index.php';</script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">


    <link href="https://fonts.googleapis.com/css?family=Muli:300,400,700,900" rel="stylesheet">
    <link rel="stylesheet" href="fonts/icomoon/style.css">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
        integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="css/jquery-ui.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/magnific-popup.min.css">
    <link rel="stylesheet" href="css/owl.carousel.min.css">
    <link rel="stylesheet" href="css/owl.theme.default.min.css">
    <link rel="stylesheet" href="css/owl.theme.default.min.css">

    <link rel="stylesheet" href="css/jquery.fancybox.min.css">

    <link rel="stylesheet" href="css/bootstrap-datepicker.css">

    <link rel="stylesheet" href="fonts/flaticon/font/flaticon.css">

    <link rel="stylesheet" href="css/aos.css">

    <link rel="stylesheet" href="css/style.css">

    <title>JUST Hall</title>


    <style>
        .card-header {
            background-color: #f4e90a;
            color: #201e1f;
        }

        .form-block {
            padding: 2rem;
            box-shadow: rgba(149, 157, 165, 0.2) 0px 8px 24px;
            border: none;

        }

        .form-info-title {
            color: #201e1f;
            font-weight: 600;
        }
    </style>

</head>

<body data-spy="scroll" data-target=".site-navbar-target" data-offset="300">

    <!-- navbar section start -->
    <?php include_once 'student/navbar-student.php'; ?>
    <!-- Navbar Section End -->

    <!-- Registration Section Start -->
    <section class="auth-section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-10">
                    <div class="contact-form-wrapper">
                        <div class="card-header text-center">
                            <h4>Provide Each Input Valid Information</h4>
                        </div>
                        <div class="form-block">
                            <form method="post" action="" enctype="multipart/form-data">

                                <div class="text-center mb-5">
                                    <h5 class="form-info-title">Personal Information</h5>
                                </div>
                                <div class="row">
                                    <!-- Profile Photo Upload Section -->
                                    <div class="col-md-6 mb-5">
                                        <label for="profilePhoto" class="form-label">Profile Photo</label>
                                        <div class="custom-file">
                                            <input type="file" class="form-control" id="profilePhoto"
                                                name="profilePhoto" accept="image/*">
                                        </div>
                                        <!-- <small class="form-text text-muted">Upload a professional profile photo.</small> -->
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="fullName" class="form-label">Full Name</label>
                                        <input type="text" class="form-control" id="fullName" name="fullName" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="studentId" class="form-label">Student ID</label>
                                        <input type="number" class="form-control" id="studentId" name="studentId"
                                            required>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="gender" class="form-label">Gender</label>
                                        <select class="form-control" id="gender" name="gender" required>
                                            <option value="male">Male</option>
                                            <option value="female">Female</option>
                                            <option value="other">Other</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="contactNo" class="form-label">Contact No</label>
                                        <input type="number" maxlength="11" class="form-control" id="contactNo" name="contactNo"
                                            required>
                                    </div>
                                </div>

                                <div class="row">
                                    <!-- Session Input -->
                                    <div class="col-md-4 mb-3">
                                        <label for="session" class="form-label">Session</label>
                                        <input type="text" class="form-control" id="session" name="session" required>
                                    </div>

                                    <!-- Year Dropdown -->
                                    <div class="col-md-4 mb-3">
                                        <label for="year" class="form-label">Year</label>
                                        <select class="form-control" id="year" name="year" required>
                                            <option value="1">B.Sc 1 year</option>
                                            <option value="2">B.Sc 2 year</option>
                                            <option value="3">B.Sc 3 year</option>
                                            <option value="4">B.Sc 4 year</option>
                                            <option value="5">M.Sc 1 year</option>
                                            <option value="6">M.Sc 2 year</option>
                                        </select>
                                    </div>

                                    <!-- Semester Dropdown -->
                                    <div class="col-md-4 mb-3">
                                        <label for="semester" class="form-label">Semester</label>
                                        <select class="form-control" id="semester" name="semester" required>
                                            <option value="1">1<sup>st</sup> semester</option>
                                            <option value="2">2<sup>nd</sup> semester</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- University Merit Field -->
                                <!-- Last Semester CGPA Field -->
                                <!-- Common Input Field -->
                                <div class="row">
                                    <div class="col-md-12 mb-3">
                                        <label for="dynamic-input" class="form-label" id="dynamic-label">University Merit</label>
                                        <input type="number" class="form-control" id="dynamic-input" name="university-merit-or-cgpa" required>
                                    </div>
                                </div>

                                <!-- Division Dropdown -->
                                <div class="mb-3">
                                    <label for="division" class="form-label">Division</label>
                                    <select class="form-control" id="division" name="division" required>
                                        <?php foreach ($divisions as $divisionName => $districts): ?>
                                            <option value="<?php echo htmlspecialchars($divisionName); ?>">
                                                <?php echo htmlspecialchars($divisionName); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <!-- District Dropdown -->
                                <div class="mb-3">
                                    <label for="district" class="form-label">District</label>
                                    <select class="form-control" id="district" name="district" required>
                                        <!-- District options will be populated dynamically -->
                                    </select>
                                </div>


                                <div class="mb-3">
                                    <label for="permanentAddress" class="form-label">Permanent Address</label>
                                    <input class="form-control" id="permanentAddress" name="permanentAddress"
                                        rows="2" required>
                                </div>
                                <div class="mb-3">
                                    <label for="presentAddress" class="form-label">Present Address</label>
                                    <input class="form-control" id="presentAddress" name="presentAddress" rows="2"
                                        required>
                                </div>
                                <div class="mb-3">
                                    <label for="formFile" class="form-label">Upload your file (Document scanned copy)</label>
                                    <input class="form-control" type="file" id="formFile" name="formFile" required>
                                </div>
                                <div class="text-center my-5">
                                    <h5 class="form-info-title">Father's Information</h5>
                                </div>
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label for="fatherName" class="form-label">Father's Name</label>
                                        <input type="text" class="form-control" id="fatherName" name="fatherName"
                                            required>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="fatherContactNo" class="form-label">Father's Contact No</label>
                                        <input type="text" class="form-control" id="fatherContactNo"
                                            name="fatherContactNo" required>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="fatherProfession" class="form-label">Father's Profession</label>
                                        <input type="text" class="form-control" id="fatherProfession"
                                            name="fatherProfession" required>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="fatherMonthlyIncome" class="form-label">Father's Monthly
                                        Income</label>
                                    <input type="number" class="form-control" id="fatherMonthlyIncome"
                                        name="fatherMonthlyIncome" required>
                                </div>
                                <div class="text-center my-5">
                                    <h5 class="form-info-title">Mother's Information</h5>
                                </div>
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label for="motherName" class="form-label">Mother's Name</label>
                                        <input type="text" class="form-control" id="motherName" name="motherName"
                                            required>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="motherContactNo" class="form-label">Mother's Contact No</label>
                                        <input type="text" class="form-control" id="motherContactNo"
                                            name="motherContactNo" required>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="motherProfession" class="form-label">Mother's Profession</label>
                                        <input type="text" class="form-control" id="motherProfession"
                                            name="motherProfession" required>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="motherMonthlyIncome" class="form-label">Mother's Monthly
                                        Income</label>
                                    <input type="number" class="form-control" id="motherMonthlyIncome"
                                        name="motherMonthlyIncome" required>
                                </div>
                                <div class="text-center my-5">
                                    <h5 class="form-info-title">Guardian's Information</h5>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="guardianName" class="form-label">Guardian's Name</label>
                                        <input type="text" class="form-control" id="guardianName" name="guardianName"
                                            required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="guardianContactNo" class="form-label">Guardian's Contact
                                            No</label>
                                        <input type="text" class="form-control" id="guardianContactNo"
                                            name="guardianContactNo" required>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="guardianAddress" class="form-label">Guardian's Address</label>
                                    <input class="form-control" id="guardianAddress" name="guardianAddress" rows="2"
                                        required>
                                </div>

                                <div class="text-center">
                                    <button type="submit" class="primary-button"
                                        style="cursor: pointer;" name="register">Create account</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Registration Section End -->


    <!-- Footer Section -->
    <footer class="footer-section ">
        <div class="container">
            <div class="row">
                <div class="col-md-3">
                    <div class="footer-logo-wrapper">
                        <h3>HMS</h3>
                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit.</p>
                    </div>
                </div>

                <div class="col-md-3 ml-auto">
                    <div class="footer-link-col">
                        <h3>Links</h3>
                        <ul class="list-unstyled footer-links">
                            <li><a href="#">Home</a></li>
                            <li><a href="#">Courses</a></li>
                            <li><a href="#">Programs</a></li>
                            <li><a href="#">Teachers</a></li>
                        </ul>
                    </div>
                </div>

                <div class="col-md-3 ml-auto">
                    <div class="footer-link-col">
                        <h3>Links</h3>
                        <ul class="list-unstyled footer-links">
                            <li><a href="#">Home</a></li>
                            <li><a href="#">Courses</a></li>
                            <li><a href="#">Programs</a></li>
                            <li><a href="#">Teachers</a></li>
                        </ul>
                    </div>
                </div>

                <div class="col-md-3 ml-auto">
                    <div class="footer-link-col">
                        <h3>Social Media</h3>
                        <ul class="list-unstyled footer-social-links">
                            <li><a href="#"><i class="fa-brands fa-facebook"></i></a></li>
                            <li><a href="#"><i class="fa-brands fa-linkedin"></i></a></li>
                            <li><a href="#"><i class="fa-brands fa-twitter"></i></a></li>
                        </ul>
                    </div>
                </div>

            </div>

            <div class="row pt-5 mt-5 text-center">
                <div class="col-md-12">
                    <div class="border-top pt-5">
                        <p class="footer-copyright-text">
                            <!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
                            Copyright &copy;
                            <script>
                                document.write(new Date().getFullYear());
                            </script> JUST Credit <i class="icon-heart"
                                aria-hidden="true"></i> by <a href="#" target="_blank">Arafat &
                                Shakil</a>
                        </p>
                    </div>
                </div>

            </div>
        </div>
    </footer>

    <script src="js/jquery-3.3.1.min.js"></script>
    <script src="js/jquery-migrate-3.0.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/jquery.magnific-popup.min.js"></script>
    <script src="js/jquery-ui.js"></script>
    <script src="js/popper.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/owl.carousel.min.js"></script>
    <script src="js/jquery.stellar.min.js"></script>
    <script src="js/jquery.countdown.min.js"></script>
    <script src="js/bootstrap-datepicker.min.js"></script>
    <script src="js/jquery.easing.1.3.js"></script>
    <script src="js/aos.js"></script>
    <script src="js/jquery.fancybox.min.js"></script>
    <script src="js/jquery.sticky.js"></script>


    <script src="js/main.js"></script>

    <!-- JavaScript to Handle Division and District Dropdowns -->
    <script>
        // Pass the PHP $divisions array to JavaScript as a JSON object.
        var divisionData = <?php echo json_encode($divisions); ?>;

        // Function to populate districts based on the selected division.
        function populateDistricts(selectedDivision) {
            var districtSelect = document.getElementById("district");
            districtSelect.innerHTML = ""; // Clear existing options.
            if (divisionData[selectedDivision]) {
                for (var district in divisionData[selectedDivision]) {
                    if (divisionData[selectedDivision].hasOwnProperty(district)) {
                        var option = document.createElement("option");
                        option.value = district;
                        option.text = district;
                        districtSelect.appendChild(option);
                    }
                }
            }
        }

        // Initialize districts on page load based on the first division option.
        document.addEventListener("DOMContentLoaded", function() {
            var divisionSelect = document.getElementById("division");
            populateDistricts(divisionSelect.value);
            // Update districts when the division selection changes.
            divisionSelect.addEventListener("change", function() {
                populateDistricts(this.value);
            });
        });
    </script>

    <!-- JavaScript to Handle Text & Attribute Change -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const yearDropdown = document.getElementById("year");
            const semesterDropdown = document.getElementById("semester");
            const inputField = document.getElementById("dynamic-input");
            const labelField = document.getElementById("dynamic-label");

            function updateField() {
                const year = yearDropdown.value;
                const semester = semesterDropdown.value;

                if ((year === "1" || year === "5") && semester === "1") {
                    // First Year, First Semester: University Merit
                    labelField.textContent = "University Merit";
                    inputField.type = "number";
                    inputField.removeAttribute("min");
                    inputField.removeAttribute("max");
                    inputField.removeAttribute("step");
                } else {
                    // Other Cases: Last Semester CGPA
                    labelField.textContent = "Last Semester CGPA";
                    inputField.type = "number";
                    inputField.setAttribute("min", "0");
                    inputField.setAttribute("max", "4");
                    inputField.setAttribute("step", "0.001");
                }
            }

            yearDropdown.addEventListener("change", updateField);
            semesterDropdown.addEventListener("change", updateField);

            updateField(); // Ensure correct display on page load
        });
    </script>


</body>

</html>