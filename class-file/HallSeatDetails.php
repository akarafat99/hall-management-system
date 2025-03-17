<?php
include_once 'DatabaseConnector.php'; // Ensure database connection is included

class HallSeatDetails
{
    public $conn;

    // Class properties
    public $seat_id = 0;
    public $status = 1;
    public $user_id = 0;
    public $floor_no = 0;
    public $room_no = 0;
    public $seat_no = 0;
    public $created = "";
    public $modified = "";

    /**
     * Constructor: Initializes the database connection.
     */
    public function __construct()
    {
        $this->ensureConnection(); // Ensure database connection is established
    }

    /**
     * Ensures that a database connection is established.
     */
    public function ensureConnection()
    {
        if (!$this->conn) { // Check if connection is not set
            $db = new DatabaseConnector(); // Create DB Connection
            $db->connect();
            $this->conn = $db->getConnection();
        } else {
            return 0;
        }
    }

    /**
     * Create tbl_hall_seat_details with only the seat_id column if it does not exist.
     *
     * @return void
     */
    public function createTableMinimal()
    {
        $this->ensureConnection();
        $sql = "CREATE TABLE IF NOT EXISTS tbl_hall_seat_details (
                seat_id INT AUTO_INCREMENT PRIMARY KEY
            ) ENGINE=InnoDB";
        $result = mysqli_query($this->conn, $sql);
        if ($result) {
            echo "Minimal table 'tbl_hall_seat_details' created successfully.<br>";
        } else {
            echo "Error creating minimal table 'tbl_hall_seat_details': " . mysqli_error($this->conn) . "<br>";
        }
    }

    /**
     * Alter table tbl_hall_seat_details to add additional columns.
     *
     * Each query is defined as a map entry where the key is a number and the value is an array:
     * [column name, SQL query].
     *
     * @param array|null $selectedNums Optional array of numbers. If provided, only the queries with these keys will run.
     * @return void
     */
    public function alterTableAddColumns($selectedNums = null)
    {
        $this->ensureConnection();

        $table = 'tbl_hall_seat_details';
        // Define queries as a map: key => [column name, SQL query]
        $alterQueries = [
            1  => ['status',      "ALTER TABLE $table ADD COLUMN status INT DEFAULT 0"],
            2  => ['user_id',     "ALTER TABLE $table ADD COLUMN user_id INT DEFAULT 0"],
            3  => ['floor_no',    "ALTER TABLE $table ADD COLUMN floor_no INT"],
            4  => ['room_no',     "ALTER TABLE $table ADD COLUMN room_no INT"],
            5  => ['seat_no',     "ALTER TABLE $table ADD COLUMN seat_no INT"],
            6  => ['created',     "ALTER TABLE $table ADD COLUMN created TIMESTAMP DEFAULT CURRENT_TIMESTAMP"],
            7  => ['modified',    "ALTER TABLE $table ADD COLUMN modified TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP"]
        ];

        // If a subset of queries is provided, filter the map.
        if ($selectedNums !== null && is_array($selectedNums)) {
            $filteredQueries = [];
            foreach ($selectedNums as $num) {
                if (isset($alterQueries[$num])) {
                    $filteredQueries[$num] = $alterQueries[$num];
                }
            }
            $alterQueries = $filteredQueries;
        }

        // Execute each query in the map.
        foreach ($alterQueries as $num => $queryInfo) {
            list($colName, $sql) = $queryInfo;
            $result = mysqli_query($this->conn, $sql);
            if ($result) {
                echo "Column '{$colName}' added successfully to table '{$table}' (Key: {$num}).<br>";
            } else {
                echo "Error adding column '{$colName}' to table '{$table}' (Key: {$num}): " . mysqli_error($this->conn) . "<br>";
            }
        }
    }

    /**
     * Insert new hall seat details
     * @return int|false Returns inserted seat_id or false on failure
     */
    public function insert()
    {
        $sql = "INSERT INTO tbl_hall_seat_details (
            status, user_id, floor_no, room_no, seat_no
        ) VALUES (
            $this->status, $this->user_id, $this->floor_no, $this->room_no, $this->seat_no
        )";

        $connection = $this->conn;
        if (mysqli_query($connection, $sql)) {
            $this->seat_id = mysqli_insert_id($connection);
            return $this->seat_id;
        } else {
            return 0;
        }
    }

    /**
     * Update hall seat details based on seat_id
     * @return bool Returns true if update is successful, false otherwise
     */
    public function update()
    {
        if ($this->seat_id == 0) return 0; // Ensure seat_id is set

        $sql = "UPDATE tbl_hall_seat_details SET 
            status = $this->status,
            user_id = $this->user_id,
            floor_no = $this->floor_no,
            room_no = $this->room_no,
            seat_no = $this->seat_no
            WHERE seat_id = $this->seat_id";

        return mysqli_query($this->conn, $sql);
    }

    /**
     * Load hall seat details based on user_id and status.
     * @param int|null $user_id (Optional) Specific user_id to load
     * @param int|null $status (Optional) Status filter
     * @return array|false Returns array of results, false if no match
     */
    public function loadByUserId($user_id = null, $status = null)
    {
        $sql = "SELECT * FROM tbl_hall_seat_details WHERE 1";

        if ($user_id !== null) {
            $sql .= " AND user_id = $user_id";
        }
        if ($status !== null) {
            $sql .= " AND status = $status";
        }

        $result = mysqli_query($this->conn, $sql);

        if ($result && mysqli_num_rows($result) > 0) {
            $data = [];
            while ($row = mysqli_fetch_assoc($result)) {
                $data[] = $row;
            }

            // If the size of the data is 1, set the properties
            if (count($data) === 1) {
                $this->setProperties($data[0]);
            }

            return $data; // Return the array regardless of its size
        }

        return false;
    }

    /**
     * Load hall seat details based on seat_id.
     * @param int $seat_id Seat ID to search for
     * @param int|null $status (Optional) Status filter
     * @return array|false Returns an array of results, false if no match
     */
    public function loadBySeatId($seat_id = null, $status = null)
    {
        $sql = "SELECT * FROM tbl_hall_seat_details WHERE 1";

        if ($seat_id !== null) {
            $sql .= " AND seat_id = $seat_id";
        }
        if ($status !== null) {
            $sql .= " AND status = $status";
        }

        $result = mysqli_query($this->conn, $sql);

        if ($result && mysqli_num_rows($result) > 0) {
            $data = [];
            while ($row = mysqli_fetch_assoc($result)) {
                $data[] = $row;
            }

            // If the size of the data is 1, set the properties
            if (count($data) === 1) {
                $this->setProperties($data[0]);
            }

            return $data; // Return the array regardless of its size
        }

        return false;
    }

    /**
     * Check if a user_id with a specified status exists in the tbl_hall_seat_details table.
     * @param int $user_id The user_id to check
     * @param int $status The status to check
     * @return int Returns 1 if found, 0 otherwise
     */
    public function isUserWithStatusAvailable($user_id, $status)
    {
        $sql = "SELECT 1 FROM tbl_hall_seat_details WHERE user_id = $user_id AND status = $status LIMIT 1";
        $result = mysqli_query($this->conn, $sql);

        return ($result && mysqli_num_rows($result) > 0) ? 1 : 0;
    }

    /**
     * Set properties based on the loaded data
     * @param array $row Row of data from the database
     */
    public function setProperties($row)
    {
        $this->seat_id = $row['seat_id'];
        $this->status = $row['status'];
        $this->user_id = $row['user_id'];
        $this->floor_no = $row['floor_no'];
        $this->room_no = $row['room_no'];
        $this->seat_no = $row['seat_no'];
        $this->created = $row['created'];
        $this->modified = $row['modified'];
    }


    /**
     * Get the highest floor number, the highest room number for each floor, and the number of seats in each room.
     * If no floors exist, return -1.
     * @return array|int An associative array containing the highest floor, the highest room for each floor, and the seat count for each room, or -1 if no floors exist.
     */
    public function getFloorRoomSeatSummary()
    {
        // Ensure the database connection is established
        $this->ensureConnection();

        // Step 1: Check if there are any floors
        $sqlCheckFloors = "SELECT COUNT(DISTINCT floor_no) AS floor_count FROM tbl_hall_seat_details";
        $resultCheckFloors = mysqli_query($this->conn, $sqlCheckFloors);

        if ($resultCheckFloors && mysqli_num_rows($resultCheckFloors) > 0) {
            $row = mysqli_fetch_assoc($resultCheckFloors);
            if ($row['floor_count'] == 0) {
                // No floors found, return -1
                return -1;
            }
        } else {
            // No result or an error, return -1
            return -1;
        }

        // Initialize the result array to hold floor, room, and seat information
        $resultMap = [];

        // Step 2: Get the highest floor number
        $sqlMaxFloor = "SELECT MAX(floor_no) AS max_floor_no FROM tbl_hall_seat_details";
        $resultMaxFloor = mysqli_query($this->conn, $sqlMaxFloor);
        if ($resultMaxFloor && mysqli_num_rows($resultMaxFloor) > 0) {
            $row = mysqli_fetch_assoc($resultMaxFloor);
            $max_floor_no = $row['max_floor_no'];
        } else {
            return [];  // Return empty array if no data found
        }

        // Step 3: Iterate over each floor number from 0 to max_floor_no
        for ($floor_no = 0; $floor_no <= $max_floor_no; $floor_no++) {
            // Initialize array to hold room and seat count for the current floor
            $resultMap[$floor_no] = [];

            // Step 4: Get the highest room number for the current floor
            $sqlMaxRoomNo = "SELECT MAX(room_no) AS max_room_no FROM tbl_hall_seat_details WHERE floor_no = $floor_no";
            $resultMaxRoomNo = mysqli_query($this->conn, $sqlMaxRoomNo);
            if ($resultMaxRoomNo && mysqli_num_rows($resultMaxRoomNo) > 0) {
                $row = mysqli_fetch_assoc($resultMaxRoomNo);
                $max_room_no = $row['max_room_no'];

                // Step 5: Get the number of seats for each room on the current floor
                for ($room_no = 1; $room_no <= $max_room_no; $room_no++) {
                    // Get the number of seats in the current room
                    $sqlSeatCount = "SELECT COUNT(seat_id) AS seat_count FROM tbl_hall_seat_details WHERE floor_no = $floor_no AND room_no = $room_no";
                    $resultSeatCount = mysqli_query($this->conn, $sqlSeatCount);
                    if ($resultSeatCount && mysqli_num_rows($resultSeatCount) > 0) {
                        $seatRow = mysqli_fetch_assoc($resultSeatCount);
                        $seat_count = $seatRow['seat_count'];

                        // Store the seat count for the room
                        $resultMap[$floor_no][$room_no] = $seat_count;
                    }
                }
            }
        }

        // Return the final result map
        return $resultMap;
    }

    /**
     * Get the highest floor number. If no floors exist, return -1.
     * @return int The highest floor number or -1 if no floors exist.
     */
    public function getMaxFloorNo()
    {
        // Ensure the database connection is established
        $this->ensureConnection();

        // Query to get the highest floor_no
        $sqlMaxFloor = "SELECT MAX(floor_no) AS max_floor_no FROM tbl_hall_seat_details";
        $resultMaxFloor = mysqli_query($this->conn, $sqlMaxFloor);

        if ($resultMaxFloor && mysqli_num_rows($resultMaxFloor) >= 0) {
            $row = mysqli_fetch_assoc($resultMaxFloor);
            $max_floor_no = $row['max_floor_no'];

            // If a valid floor exists, return the max floor number
            return ($max_floor_no !== null) ? $max_floor_no : -1;
        }

        return -1;  // Return -1 if no floors are found
    }

    /**
     * Get the highest room number for a given floor number.
     * @param int $floor_no The floor number to get the highest room number for.
     * @return int The highest room number for the given floor or -1 if no rooms exist.
     */
    public function getHighestRoomNoByFloor($floor_no)
    {
        // Ensure the database connection is established
        $this->ensureConnection();

        // Query to get the highest room_no for the given floor_no
        $sql = "SELECT MAX(room_no) AS max_room_no FROM tbl_hall_seat_details WHERE floor_no = $floor_no";
        $result = mysqli_query($this->conn, $sql);

        if ($result && mysqli_num_rows($result) >= 0) {
            $row = mysqli_fetch_assoc($result);
            $max_room_no = $row['max_room_no'];

            // If a room exists, return the highest room_no, otherwise return -1
            return ($max_room_no !== null) ? $max_room_no : 0;
        }

        return -1; // Return -1 if no rooms are found for the given floor
    }

    /**
     * Insert multiple rows for seats in a single query based on the given floor number, room number, and per room seat configuration.
     * @param int $floor_no The floor number to insert the seats for.
     * @param int $starting_room_no The starting room number to begin inserting rooms.
     * @param array $per_room_seat An array containing the number of seats for each room, e.g., [3, 2, 4].
     * @return void
     */
    public function createMultipleSeatsOptimized($floor_no, $starting_room_no, $per_room_seat)
    {
        // Ensure the database connection is established
        $this->ensureConnection();

        // Initialize an array to hold the values for batch insert
        $insert_values = [];

        foreach ($per_room_seat as $num_seats) {
            // For each room, prepare the values for batch insert
            for ($i = 1; $i <= $num_seats; $i++) {
                $seat_no = $i;
                // Add the seat's values to the insert array
                $insert_values[] = "($floor_no, $starting_room_no, $seat_no)";
            }

            // Increment room number for the next room
            $starting_room_no++;
        }

        // Step 1: Check if there are any values to insert
        if (!empty($insert_values)) {
            // Step 2: Create a single insert query with all the values
            $sql = "INSERT INTO tbl_hall_seat_details (floor_no, room_no, seat_no) VALUES " . implode(',', $insert_values);

            // Step 3: Execute the batch insert
            $result = mysqli_query($this->conn, $sql);

            if ($result) {
                echo "Seats successfully created.<br>";
            } else {
                echo "Error inserting seats: " . mysqli_error($this->conn) . "<br>";
            }
        } else {
            echo "No seats to insert.<br>";
        }
    }



    /**
     * Get all rows for a given floor number with status filter (default value 1).
     * @param int $floor_no The floor number to get rows for.
     * @param int $status The status to filter rows by (default is null).
     * @return array|false Returns an array of rows for the given floor, or false if no rows are found.
     */
    public function getRowsByFloorNo($floor_no, $status = null)
    {
        // Ensure the database connection is established
        $this->ensureConnection();


        // Query to get all rows for the specified floor_no with the given status (default 1)
        $sql = "SELECT * FROM tbl_hall_seat_details WHERE floor_no = $floor_no";
        if ($status != null) {
            $sql .= " AND status = $status";
        }


        $result = mysqli_query($this->conn, $sql);

        if ($result && mysqli_num_rows($result) > 0) {
            $data = [];
            while ($row = mysqli_fetch_assoc($result)) {
                $data[] = $row;
            }
            return $data;  // Return an array of rows for the specified floor and status
        }

        return false;  // Return false if no rows are found
    }


    /**
     * Get all rows based on floor number, room number, and status.
     * All parameters default to null so you can filter by any combination.
     *
     * @param int|null $floor_no The floor number to filter by.
     * @param int|null $room_no The room number to filter by.
     * @param array|int|null $status The status or array of statuses to filter by.
     * @return array|false Returns an array of matching rows or false if none are found.
     */
    public function getRowsByFloorRoomStatus($floor_no = null, $room_no = null, $status = null)
    {
        // Ensure the database connection is established
        $this->ensureConnection();

        // Start with a base query that always evaluates to true.
        $sql = "SELECT * FROM tbl_hall_seat_details WHERE 1";

        // Add floor number filter if provided
        if ($floor_no !== null) {
            $sql .= " AND floor_no = " . intval($floor_no);
        }

        // Add room number filter if provided
        if ($room_no !== null) {
            $sql .= " AND room_no = " . intval($room_no);
        }

        // Add status filter if provided
        if ($status !== null) {
            if (is_array($status)) {
                // Convert array to a comma-separated list of integers for SQL IN clause
                $statusList = implode(',', array_map('intval', $status));
                $sql .= " AND status IN ($statusList)";
            } else {
                $sql .= " AND status = " . intval($status);
            }
        }

        // Execute the query
        $result = mysqli_query($this->conn, $sql);

        // Return the results if available, otherwise return false
        if ($result && mysqli_num_rows($result) > 0) {
            $data = [];
            while ($row = mysqli_fetch_assoc($result)) {
                $data[] = $row;
            }
            return $data;
        }

        return false;
    }

    /**
     * Add multiple seats to a specific room on a specific floor.
     *
     * This function first retrieves the current maximum seat number for the given floor and room.
     * Then, starting from (max + 1), it inserts the specified number of new seats into the table.
     *
     * @param int $floor_no The floor number.
     * @param int $room_no The room number.
     * @param int $seat_count The number of seats to add.
     * @return int|false Returns the number of rows inserted, or false on failure.
     */
    public function addSeatsToRoom($floor_no, $room_no, $seat_count)
    {
        // Ensure the database connection is established
        $this->ensureConnection();

        // Get the current maximum seat number for the specified floor and room
        $sqlMaxSeat = "SELECT MAX(seat_no) AS max_seat_no FROM tbl_hall_seat_details WHERE floor_no = $floor_no AND room_no = $room_no";
        $resultMaxSeat = mysqli_query($this->conn, $sqlMaxSeat);
        $maxSeatNo = 0;
        if ($resultMaxSeat && mysqli_num_rows($resultMaxSeat) > 0) {
            $row = mysqli_fetch_assoc($resultMaxSeat);
            if ($row['max_seat_no'] !== null) {
                $maxSeatNo = intval($row['max_seat_no']);
            }
        }

        // Generate the insert values for new seats
        $insertValues = [];
        for ($i = 1; $i <= $seat_count; $i++) {
            $newSeatNo = $maxSeatNo + $i;
            // Only floor_no, room_no, seat_no are inserted. Other columns (status, user_id, etc.) use their default values.
            $insertValues[] = "($floor_no, $room_no, $newSeatNo)";
        }

        if (!empty($insertValues)) {
            // Create the single INSERT query for batch insertion.
            $sqlInsert = "INSERT INTO tbl_hall_seat_details (floor_no, room_no, seat_no) VALUES " . implode(',', $insertValues);
            $resultInsert = mysqli_query($this->conn, $sqlInsert);
            if ($resultInsert) {
                // Return the number of inserted rows
                return mysqli_affected_rows($this->conn);
            } else {
                return false;
            }
        }
        return false;
    }

    /**
     * Count the number of seats (records) that have a given status.
     *
     * @param int $status The status value to filter by.
     * @return int The count of seats with the specified status.
     */
    public function countSeatsByStatus($status)
    {
        $this->ensureConnection();
        $sql = "SELECT COUNT(*) AS seat_count FROM tbl_hall_seat_details WHERE status = $status";
        $result = mysqli_query($this->conn, $sql);
        
        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            return (int)$row['seat_count'];
        }
        return 0;
    }
}
?>

<!-- end -->