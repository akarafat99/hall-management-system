<?php
include_once '../class-file/SessionManager.php';
include_once '../class-file/User.php';
include_once '../class-file/UserDetails.php';
include_once '../class-file/FileManager.php';

$session = SessionStatic::class;

if ($session::get('user') == null) {
    echo "<script>window.location.href = '../login.php';</script>";
    exit;
}

$userDetails = new UserDetails();

if (isset($_POST['deleteEditRequest'])) {
    $userDetails->details_id = $_POST['deleteEditRequest'];
    $userDetails->updateStatusByDetailsId($userDetails->details_id, -2);
    // -2 means the edit request is deleted by the user.

    $session::set('msg1', 'Delete the edit request successfully!');
    echo '<script type="text/javascript">window.location.href = "profile.php";</script>';
    exit;
} else {
    echo '<script type="text/javascript">window.location.href = "profile.php";</script>';
    exit;
}

?>

<!-- end -->