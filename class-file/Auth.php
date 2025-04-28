<?php
// ob_start();
include_once __DIR__ . '/SessionManager.php';

/**
 * This function checks if the user is authenticated as the given role.
 * If not, it forwards the user to the appropriate login page.
 * 
 * @param string $role The role to check for (e.g., 'admin' or 'user').
 */
function auth($role)
{
    $session = SessionStatic::class;
    $roleObj = $session::get($role);
    
    $currentDir = dirname($_SERVER['PHP_SELF']);
    if ($role == 'admin') {
        if ($roleObj == null) {
            // Get the current directory from the URL (e.g. "/php-class-file")
            $currentDir = dirname($_SERVER['PHP_SELF']);

            $session::set('msg1', 'Please login to access.');
            echo "<script>location.href='{$currentDir}/../admin/login.php';</script>";
        }
    } else {
        if ($roleObj == null) {
            $session::set('msg1', 'Please login as a user to access.');
            // This constructs a URL like "/php-class-file/../login.php"
            echo "<script>location.href='{$currentDir}/../login.php';</script>";
        }
    }
}

?>
<!-- end -->