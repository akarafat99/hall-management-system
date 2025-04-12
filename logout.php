<?php
include_once 'class-file/SessionManager.php';
$session =  SessionStatic::class;
$session::destroy();
echo "<script>location.href = 'login.php';</script>";

?>

<!-- end of the file -->