<?php
include_once 'class-file/SessionManager.php';
$session = SessionStatic::class;

if($session::get('user') != null) {
    echo '<script type="text/javascript">
            window.location.href = "index.php";
          </script>';
    exit;
}

include_once 'popup-1.php';
if ($session::get('msg1') != null) {
    showPopup($session::get('msg1'));
    $session::delete('msg1');
}

if ($session::get('step') != 3) {
    $session::set('msg1', 'Please complete the <b>step 1</b> first.');
    echo $session::get('msg1');
    echo '<script> window.location.href = "reset-pass-1.php";</script>';
    exit();
}

if ($session::get('step') == 3) {
}


if (isset($_POST['resetPassword'])) {
    $newPassword = $_POST['newPassword'];
    $retypePassword = $_POST['retypePassword'];

    if ($newPassword != $retypePassword) {
        showPopup('Passwords do not match. Please try again.');
    } else {
        // Assuming you have a method to update the password in your User class
        include_once 'class-file/User.php';
        $sUser = $session::getObject('tempUserObj');
        $user = new User();
        $session::copyProperties($sUser, $user);
        $user->load();

        $user->password = $user->encryptPassword($newPassword);
        $res = $user->update();

        if ($res) {
            $session::delete('tempUserObj');
            $session::delete('step');
            $session::set('msg1', 'Password reset successfully! Please login again.');
            echo '<script type="text/javascript">window.location.href = "login.php";</script>';
        } else {
            $session::set('msg1', 'Failed to reset password. Please try again.');
            echo '<script type="text/javascript">window.location.href = "reset-pass-1.php";</script>';
        }
        exit;
    }
}


?>




<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Only Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet">


    <title>MM Hall</title>


</head>

<body>
    
    <div class="d-flex flex-column min-vh-100">
        <div class="flex-grow-1">

        <!-- Navbar Section Start -->
        <?php
            if ($session::get('user') !== null) {
                include_once 'student/navbar-student-1.php';
            } else {
                include_once 'student/navbar-student-2.php';
            }
            ?>
            <!-- Navbar Section End -->

            <!-- Registration Form -->
            <div class="container my-5">
                <div class="row justify-content-center">
                    <div class="col-md-6">
                        <div class="card shadow-sm">
                            <div class="card-header text-center">
                                <h4 class="mb-0">Reset Password</h4>
                            </div>
                            <div class="card-body">
                                <p class="text-center mb-4">Enter New Password</p>

                                <form method="post" action="">
                                    <!-- new password and retype password -->
                                     <div class="mb-3">
                                        <label for="newPassword" class="form-label">New Password</label>
                                        <input
                                            type="password"
                                            class="form-control"
                                            id="newPassword"
                                            name="newPassword"
                                            required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="retypePassword" class="form-label">Retype Password</label>
                                        <input
                                            type="password"
                                            class="form-control"
                                            id="retypePassword"
                                            name="retypePassword"
                                            required>
                                    </div>
                                    <div class="d-grid mb-3">
                                        <button
                                            type="submit"
                                            name="resetPassword"
                                            class="btn btn-primary">
                                            Submit
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <!-- Registration Form End -->
        </div>

        <!-- Footer -->
        <footer class="bg-dark text-white text-center py-3 mt-auto">
            <div class="container">
                <p class="mb-0">&copy; <?php echo date('Y'); ?> JUST MM Hall</p>
            </div>
        </footer>
    </div>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>
    
</body>

</html>