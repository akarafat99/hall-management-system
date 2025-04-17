<?php

// Define the priority list as an associative array (map)
$priorityList = [
    1 => "District",
    2 => "Academic Result",
    3 => "Father's Monthly Income"
];


/**
 * Function to get the priority list.
 *
 * @return array The priority list array.
 */
function getPriorityList() {
    global $priorityList;  // Access the global priority list
    return $priorityList;
}

// Example usage: Get and display the priority list
// $priorityListFromFunction = getPriorityList();

// echo "Priority List:<br>";
// foreach ($priorityListFromFunction as $index => $priority) {
//     echo "Priority $index: $priority<br>";
// }

?>


<!-- end -->