<?php
include_once '../class-file/SessionManager.php';
include_once '../class-file/User.php';
include_once '../class-file/UserDetails.php';
include_once '../class-file/FileManager.php';
include_once '../popup-1.php';

$session = SessionStatic::class;

if ($session::get('admin') == null) {
  echo "<script>window.location.href = '../login.php';</script>";
  exit;
}

// load user details
$sUser = $session::getObject('adminObj');
$user = new User();
$session::copyProperties($sUser, $user);

$userDetails = new UserDetails();
if(isset($_POST['updateProfilePicture'])) {
  $userDetails->user_id = $user->user_id;
  $userDetails->getUsers($userDetails->user_id, null, 1);

  $file1 = new FileManager();
  $file1->file_owner_id = $user->user_id;
  $file1->insert();
  $result = $file1->doOp($_FILES['profileImage']);
  $file1->update();

  $userDetails->profile_picture_id = $file1->file_id;
  $userDetails->update();

  $session::set('msg1', 'Profile picture updated successfully!');
  echo '<script type="text/javascript">window.location.href = "profile.php";</script>';
}

?>