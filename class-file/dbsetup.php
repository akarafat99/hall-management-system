<?php
include_once 'DatabaseConnector.php';
include_once 'User.php';
include_once 'UserDetails.php';
include_once 'NoteManager.php';
include_once 'FileManager.php';
include_once 'Admin.php';
include_once 'HallSeatDetails.php';
include_once 'HallSeatAllocationEvent.php';

// //session 1
$db = new DatabaseConnector();
$db->createDatabase();
echo "Database created successfully";
echo "<br><br><br>";

$user = new User();
$user->createTableMinimal();
$user->alterTableAddColumns();
echo "User table created successfully";
echo "<br><br><br>";

$userDetails = new UserDetails();
$userDetails->createTableMinimal();
$userDetails->alterTableAddColumns();
echo "User Details table created successfully";
echo "<br><br><br>";

$noteManager = new NoteManager();
$noteManager->createTableMinimal();
$noteManager->alterTableAddColumns();
echo "Note Manager table created successfully";
echo "<br><br><br>";

$fileManager = new FileManager();
$fileManager->createTableMinimal();
$fileManager->alterTableAddColumns();
echo "File Manager table created successfully";
echo "<br><br><br>";

$admin = new Admin();
$admin->insertAdmin();
echo "Admin record inserted successfully";
echo "<br><br><br>";

$hallSeatDetails = new HallSeatDetails();
$hallSeatDetails->createTableMinimal();
$hallSeatDetails->alterTableAddColumns();
echo "Hall Seat Details table created successfully";
echo "<br><br><br>";

$hallSeatAllocationEvent = new HallSeatAllocationEvent();
$hallSeatAllocationEvent->createTableMinimal();
$hallSeatAllocationEvent->alterTableAddColumns();
echo "Hall Seat Allocation Event table created successfully";
echo "<br><br><br>";


?>
<!-- end -->