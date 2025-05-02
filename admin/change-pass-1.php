<?php
include_once '../class-file/SessionManager.php';
$session = SessionStatic::class;

include_once '../class-file/Auth.php';
auth('admin');

include_once '../class-file/User.php';
include_once '../class-file/UserDetails.php';
include_once '../class-file/FileManager.php';
include_once '../class-file/Department.php';
include_once '../class-file/Division.php';
include_once '../popup-1.php';

if ($session::get('msg1') != null) {
    showPopup($session::get('msg1'));
    $session::delete('msg1');
}

// load user details
$sUser = $session::getObject('adminObj');
$user = new User();
$session::copyProperties($sUser, $user);

$user->load();
// echo $user->user_id . "<br>";

if (isset($_POST['changePass'])) {
    $currentPassword = $_POST['currentPassword'];
    $newPassword = $_POST['newPassword'];
    $confirmPassword = $_POST['confirmPassword'];

    // echo "<br> current " . $currentPassword . "<br>";
    // echo "<br> new " . $newPassword . "<br>";

    if ($newPassword != $confirmPassword) {
        $session::set('msg1', 'New password and confirm password do not match.');
    } else {
        // Check if the current password is correct using password_verify()
        if (!password_verify($currentPassword, $user->password)) {
            $session::set('msg1', 'Current password is incorrect.');
        } else {
            // Use the new function to hash and set the new password
            $user->password = $user->encryptPassword($newPassword);

            $updateResult = $user->update();
            if ($updateResult === true) {
                $session::set('msg1', 'Password changed successfully!');
                echo '<script type="text/javascript">window.location.href = "dashboard.php";</script>';
            } else {
                $session::set('msg1', 'Failed to change password. Please try again.');
            }
        }
        echo "<script>location.href='change-pass-1.php';</script>";
        exit;
    }
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MM Hall</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css"
        rel="stylesheet" />
    <!-- for sidebar -->
    <link href="../css2/sidebar-admin.css" rel="stylesheet"/>

</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar Menu -->
             <?php include 'sidebar-admin.php'; ?>

            <!-- Main Content Area -->
            <main id="mainContent" class="col">
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
                
                <!-- form of current pass, new pass and confirm pass -->
                <div class="p-3" style="height: 100vh; overflow-y: auto;">
                    <h1>Change Password</h1>
                    <form action="" method="post">
                        <div class="mb-3">
                            <label for="currentPassword" class="form-label">Current Password</label>
                            <input type="password" class="form-control" id="currentPassword" name="currentPassword" required>
                        </div>
                        <div class="mb-3">
                            <label for="newPassword" class="form-label">New Password</label>
                            <input type="password" class="form-control" id="newPassword" name="newPassword" required>
                        </div>
                        <div class="mb-3">
                            <label for="confirmPassword" class="form-label">Confirm New Password</label>
                            <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" required>
                        </div>
                        <button type="submit" class="btn btn-primary" name="changePass">Change Password</button>
                    </form>
                </div>

                
            </main>
        </div>
    </div>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>


</body>

</html>