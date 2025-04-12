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

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- for sidebar and phone menu -->
  <link href="../css2/custom1.css" rel="stylesheet">
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
<div class="container-fluid">
    <!-- Use min-vh-100 to force full viewport height -->
    <div class="row min-vh-100">

    <?php include_once 'sidebar-student.php'; ?>

      <!-- Main Content Area -->
      <main class="col-md-10 ms-sm-auto col-lg-10 px-md-4">
        <div class="pt-3 pb-2 mb-3 border-bottom">
          <h1>Main Content Area</h1>
        </div>
        <p>
          Your main body content goes here. Replace this text with your actual content, images, or additional Bootstrap components as needed.
        </p>
      </main>
    </div>
  </div>


  <!-- Bootstrap Bundle with Popper -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <!-- for the sidebar and phone menu -->
  <script src="../js2/custom1.js"></script>

</body>

</html>