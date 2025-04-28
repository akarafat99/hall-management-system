<?php
include_once '../class-file/SessionManager.php';
$session = SessionStatic::class;

include_once '../class-file/Auth.php';
auth('user');

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
$sUser = $session::getObject('userObj');
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
        echo "<script>location.href='change-pass-1.php';</script>";
        exit;
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
                echo '<script type="text/javascript">window.location.href = "change-pass-1.php";</script>';
                exit;
            } else {
                $session::set('msg1', 'Failed to change password. Please try again.');
                echo "<script>location.href='change-pass-1.php';</script>";
                exit;
            }
        }
    }
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
    <!-- for sidebar -->

</head>

<body>
    <div class="d-flex flex-column min-vh-100">
        <div class="flex-grow-1">
            <!-- Navbar Section Start -->
            <?php include_once 'navbar-student-1.php'; ?>
            <!-- Navbar Section End  -->

            <!-- form of current pass, new pass and confirm pass -->
            <div class="p-3 mt-4 mb-4 bg-light rounded-3">
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

        </div>
        <!-- Footer -->
        <footer class="bg-dark text-white text-center py-3 mt-auto">
            <div class="container">
                <p class="mb-0">&copy; <?php echo date('Y'); ?> JUST MM Hall</p>
            </div>
        </footer>
    </div>
    

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>


</body>

</html>