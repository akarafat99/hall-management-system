<?php
include_once 'DatabaseConnector.php';
include_once 'User.php';
include_once 'UserDetails.php';
include_once 'NoteManager.php';
include_once 'FileManager.php';
include_once 'Admin.php';
include_once 'HallSeatDetails.php';
include_once 'HallSeatAllocationEvent.php';
include_once 'HallSeatApplication.php';
include_once 'Department.php';
include_once 'NoticeManager.php';

// //session 1
// $db = new DatabaseConnector();
// $db->createDatabase();
// echo "Database created successfully";
// echo "<br><br><br>";

// $user = new User();
// $user->createTableMinimal();
// $user->alterTableAddColumns();
// echo "User table created successfully";
// echo "<br><br><br>";

// $userDetails = new UserDetails();
// $userDetails->createTableMinimal();
// $userDetails->alterTableAddColumns();
// echo "User Details table created successfully";
// echo "<br><br><br>";

// $noteManager = new NoteManager();
// $noteManager->createTableMinimal();
// $noteManager->alterTableAddColumns();
// echo "Note Manager table created successfully";
// echo "<br><br><br>";

// $fileManager = new FileManager();
// $fileManager->createTableMinimal();
// $fileManager->alterTableAddColumns();
// echo "File Manager table created successfully";
// echo "<br><br><br>";

// $admin = new Admin();
// $admin->createSuperAdmin();
// echo "Super Admin created successfully";
// echo "<br><br><br>";

// $hallSeatDetails = new HallSeatDetails();
// $hallSeatDetails->createTableMinimal();
// $hallSeatDetails->alterTableAddColumns();
// echo "Hall Seat Details table created successfully";
// echo "<br><br><br>";

// $hallSeatAllocationEvent = new HallSeatAllocationEvent();
// $hallSeatAllocationEvent->createTableMinimal();
// $hallSeatAllocationEvent->alterTableAddColumns();
// echo "Hall Seat Allocation Event table created successfully";
// echo "<br><br><br>";

// $hallSeatApplication = new HallSeatApplication();
// $hallSeatApplication->createTableMinimal();
// $hallSeatApplication->alterTableAddColumns();
// echo "Hall Seat Application table created successfully";
// echo "<br><br><br>";

// $department = new Department();
// $department->createTableMinimal();
// $department->alterTableAddColumns();
// echo "Department table created successfully";
// $department->insertDefaultDepartments();
// echo "<br><br><br>";

// $noticeManager = new NoticeManager();
// $noticeManager->createTableMinimal();
// $noticeManager->alterTableAddColumns();
// echo "Notice Manager table created successfully";
// echo "<br><br><br>";


// Session 2
// $userDetails = new UserDetails();
// $userDetails->alterTableAddColumns([33]);
// echo "User Details table updated successfully";

// $hallSeatAllocationEvent = new HallSeatAllocationEvent();
// $hallSeatAllocationEvent->alterTableAddColumns([17]);
// echo "Hall Seat Allocation Event table updated successfully";
// echo "<br><br><br>";


?>
<!-- end -->