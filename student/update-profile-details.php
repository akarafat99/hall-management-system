<?php
include_once '../class-file/SessionManager.php';
include_once '../class-file/User.php';
include_once '../class-file/UserDetails.php';
include_once '../class-file/FileManager.php';
include_once '../class-file/Division.php';
include_once '../class-file/Department.php';

$session = SessionStatic::class;
$divisions = getDivisions();
$department = new Department();
$departmentList = $department->getDepartments(null, 1);

if ($session::get('user') == null) {
  echo "<script>window.location.href = '../login.php';</script>";
  exit;
}


$sUser = $session::getObject('userObj');
$user = new User();
$session::copyProperties($sUser, $user);

$userDetails = new UserDetails();

if (isset($_POST['editDetails'])) {
  $userDetails->details_id = $_POST['editDetails'];
  $userDetails->getByDetailsId($userDetails->details_id);

  $userDetails->full_name = isset($_POST['fullName']) ? $_POST['fullName'] : $userDetails->full_name;
  $userDetails->student_id = isset($_POST['studentId']) ? $_POST['studentId'] : $userDetails->student_id;
  $userDetails->gender = isset($_POST['gender']) ? $_POST['gender'] : $userDetails->gender;
  $userDetails->contact_no = isset($_POST['contactNo']) ? $_POST['contactNo'] : $userDetails->contact_no;
  $userDetails->session = isset($_POST['session']) ? $_POST['session'] : $userDetails->session;
  $userDetails->department_id = isset($_POST['departmentId']) ? $_POST['departmentId'] : $userDetails->department_id;
  $userDetails->year_semester_code = isset($_POST['yearSemesterCode']) ? $_POST['yearSemesterCode'] : $userDetails->year_semester_code;
  $userDetails->last_semester_cgpa_or_merit = isset($_POST['lastSemesterCgpaOrMerit']) ? $_POST['lastSemesterCgpaOrMerit'] : $userDetails->last_semester_cgpa_or_merit;
  $userDetails->division = isset($_POST['division']) ? $_POST['division'] : $userDetails->division;
  $userDetails->district = isset($_POST['district']) ? $_POST['district'] : $userDetails->district;
  $userDetails->permanent_address = isset($_POST['permanentAddress']) ? $_POST['permanentAddress'] : $userDetails->permanent_address;
  $userDetails->present_address = isset($_POST['presentAddress']) ? $_POST['presentAddress'] : $userDetails->present_address;
  $userDetails->father_name = isset($_POST['fatherName']) ? $_POST['fatherName'] : $userDetails->father_name;
  $userDetails->father_contact_no = isset($_POST['fatherContactNo']) ? $_POST['fatherContactNo'] : $userDetails->father_contact_no;
  $userDetails->father_profession = isset($_POST['fatherProfession']) ? $_POST['fatherProfession'] : $userDetails->father_profession;
  $userDetails->father_monthly_income = isset($_POST['fatherMonthlyIncome']) ? $_POST['fatherMonthlyIncome'] : $userDetails->father_monthly_income;
  $userDetails->mother_name = isset($_POST['motherName']) ? $_POST['motherName'] : $userDetails->mother_name;
  $userDetails->mother_contact_no = isset($_POST['motherContactNo']) ? $_POST['motherContactNo'] : $userDetails->mother_contact_no;
  $userDetails->mother_profession = isset($_POST['motherProfession']) ? $_POST['motherProfession'] : $userDetails->mother_profession;
  $userDetails->mother_monthly_income = isset($_POST['motherMonthlyIncome']) ? $_POST['motherMonthlyIncome'] : $userDetails->mother_monthly_income;
  $userDetails->guardian_name = isset($_POST['guardianName']) ? $_POST['guardianName'] : $userDetails->guardian_name;
  $userDetails->guardian_contact_no = isset($_POST['guardianContactNo']) ? $_POST['guardianContactNo'] : $userDetails->guardian_contact_no;
  $userDetails->guardian_address = isset($_POST['guardianAddress']) ? $_POST['guardianAddress'] : $userDetails->guardian_address;

  $userDetails->status = 0;
  $userDetails->insert();

  // Manage the document file change
  if ($_FILES['changeFile']['name'] != '') {
    $file1 = new FileManager();
    $file1->file_owner_id = $user->user_id;
    $file1->file_id = $file1->insert();
    $ans = $file1->doOp($_FILES['changeFile']);
    if ($ans == 1) {
      $userDetails->document_id = $file1->file_id;
      $userDetails->update();
      // echo 'Document uploaded <br>';
      $file1->update();
    } else {
      // echo 'Document upload failed <br>';
    }
  }

  $session::set('msg1', 'Profile details updated successfully!. Please wait for admin approval.');
  echo '<script type="text/javascript">window.location.href = "profile.php";</script>';
}

?>

<!-- end -->