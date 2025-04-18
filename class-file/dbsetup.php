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
// $admin->insertAdmin();
// echo "Admin record inserted successfully";
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


// Session 2
// $userDetails = new UserDetails();
// $userDetails->alterTableAddColumns([31,32]);
// echo "User Details table updated successfully";



?>
<!-- end -->