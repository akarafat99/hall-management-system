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

if ($session::get('admin') == null) {
  echo "<script>window.location.href = '../login.php';</script>";
  exit;
}


$sUser = $session::getObject('adminObj');
$user = new User();
$session::copyProperties($sUser, $user);

$userDetails = new UserDetails();

if (isset($_POST['editDetails'])) {
  $userDetails->details_id = $_POST['editDetails'];
  $userDetails->getByDetailsId($userDetails->details_id);

  $user->load();
  $user->email = isset($_POST['email']) ? $_POST['email'] : $user->email;
  $user->update();

  $userDetails->full_name = isset($_POST['fullName']) ? $_POST['fullName'] : $userDetails->full_name;
  $userDetails->gender = isset($_POST['gender']) ? $_POST['gender'] : $userDetails->gender;
  $userDetails->contact_no = isset($_POST['contactNo']) ? $_POST['contactNo'] : $userDetails->contact_no;
  $userDetails->division = isset($_POST['division']) ? $_POST['division'] : $userDetails->division;
  $userDetails->district = isset($_POST['district']) ? $_POST['district'] : $userDetails->district;
  $userDetails->permanent_address = isset($_POST['permanentAddress']) ? $_POST['permanentAddress'] : $userDetails->permanent_address;
  $userDetails->present_address = isset($_POST['presentAddress']) ? $_POST['presentAddress'] : $userDetails->present_address;
  
  $userDetails->status = 1;
  $userDetails->update();

  $session::set('msg1', 'Profile details updated successfully!');
  echo '<script type="text/javascript">window.location.href = "profile.php";</script>';
}

?>

<!-- end -->