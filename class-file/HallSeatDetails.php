<?php
include_once 'DatabaseConnector.php'; // Ensure database connection is included

class HallSeatDetails
{
    public $conn;

    // Class properties
    public $seat_id = 0;
    public $status = 0;
    public $reserved_by_event_id = 0;
    public $user_id = 0;
    public $floor_no = 0;
    public $room_no = 0;
    public $created = "";
    public $modified = "";
    public $modified_by = -1; // New property: modified_by, integer default -1

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
            1  => ['status',                "ALTER TABLE $table ADD COLUMN status INT DEFAULT 0"],
            2  => ['reserved_by_event_id',  "ALTER TABLE $table ADD COLUMN reserved_by_event_id INT DEFAULT 0"],
            3  => ['user_id',               "ALTER TABLE $table ADD COLUMN user_id INT DEFAULT 0"],
            4  => ['floor_no',              "ALTER TABLE $table ADD COLUMN floor_no INT"],
            5  => ['room_no',               "ALTER TABLE $table ADD COLUMN room_no INT"],
            6  => ['created',               "ALTER TABLE $table ADD COLUMN created TIMESTAMP DEFAULT CURRENT_TIMESTAMP"],
            7  => ['modified',              "ALTER TABLE $table ADD COLUMN modified TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP"],
            8  => ['modified_by',           "ALTER TABLE $table ADD COLUMN modified_by INT DEFAULT -1"]
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
     * Insert new hall seat details.
     * @return int|false Returns inserted seat_id or false on failure.
     */
    public function insert()
    {
        // Include the new column in the INSERT query.
        $sql = "INSERT INTO tbl_hall_seat_details (
            status, user_id, floor_no, room_no, modified_by
        ) VALUES (
            $this->status, $this->user_id, $this->floor_no, $this->room_no, $this->modified_by
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
     * Update hall seat details based on seat_id.
     * @return bool Returns true if update is successful, false otherwise.
     */
    public function update()
    {
        if ($this->seat_id == 0) return 0; // Ensure seat_id is set

        // Include modified_by in the update query.
        $sql = "UPDATE tbl_hall_seat_details SET 
            status = $this->status,
            reserved_by_event_id = $this->reserved_by_event_id,
            user_id = $this->user_id,
            floor_no = $this->floor_no,
            room_no = $this->room_no,
            modified_by = $this->modified_by
            WHERE seat_id = $this->seat_id";

        return mysqli_query($this->conn, $sql);
    }

    /**
     * Update the status of a seat based on its seat_id.
     *
     * @param int $seat_id The ID of the seat to update.
     * @param int $new_status The new status value.
     * @return bool Returns true if the update is successful, false otherwise.
     */
    public function updateStatus($seat_id, $new_status)
    {
        $this->ensureConnection();

        if ($seat_id <= 0) {
            return false;
        }

        // Update query now includes modified_by.
        $sql = "UPDATE tbl_hall_seat_details 
                SET status = $new_status, modified_by = $this->modified_by 
                WHERE seat_id = $seat_id";
        if (mysqli_query($this->conn, $sql)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Update rows matching a specified current status.
     *
     * This function sets the rows' status to a new status and updates reserved_by_event_id as follows:
     * - If $resetEventId is not null, reserved_by_event_id is set to $resetEventId.
     * - If $resetEventId is null, reserved_by_event_id is set to $eventId.
     *
     * Additionally, if $resetEventId is not null the update applies only to rows where reserved_by_event_id equals $eventId.
     * The update is limited to a specified number of rows and ordered by seat_id according to the specified order.
     *
     * @param int $currentStatus The current status value to match.
     * @param int $newStatus The new status value to set.
     * @param int $eventId The event id used for determining update conditions.
     * @param int|null $resetEventId If provided, reserved_by_event_id will be set to this value and used in the WHERE clause.
     * @param int $limit The maximum number of rows to update.
     * @param string $order The update order; either "ASC" or "DESC". Default is "ASC".
     * @return int|false Returns the number of affected rows on success, or false on failure.
     */
    public function updateRowsByStatusAndEventIdAndLimit($currentStatus, $newStatus, $eventId, $resetEventId = null, $limit, $order = 'ASC')
    {
        $this->ensureConnection();

        // Ensure the order is uppercase and valid, defaulting to ASC if not.
        $order = strtoupper($order);
        if ($order !== 'ASC' && $order !== 'DESC') {
            $order = 'ASC';
        }

        // Determine the new reserved_by_event_id value to set.
        $newReservedValue = ($resetEventId !== null) ? 0     : $eventId;

        // Build the WHERE clause.
        $where = "WHERE status = $currentStatus";
        if ($resetEventId !== null) {
            $where .= " AND reserved_by_event_id = $eventId";
        }

        // Build the update query, including an ORDER BY clause.
        $sql = "UPDATE tbl_hall_seat_details 
            SET status = $newStatus, reserved_by_event_id = $newReservedValue, modified_by = $this->modified_by 
            $where
            ORDER BY seat_id $order
            LIMIT " . intval($limit);

        $result = mysqli_query($this->conn, $sql);
        if ($result) {
            return mysqli_affected_rows($this->conn);
        } else {
            return false;
        }
    }


    /**
     * Update the reserved seats based on the difference between the new and previous reserved counts.
     *
     * If additional seats are being reserved (new - previous > 0),
     * this function changes free seats (status 0) into reserved seats (status 2) for the given event id,
     * using an ascending order (i.e. earlier rows first).
     *
     * If seats are being released (new - previous < 0),
     * it changes reserved seats (status 2) into free seats (status 0) for the given event id,
     * using a descending order (i.e. the latest rows are updated first).
     *
     * @param int $previousReserved The previous total reserved seats.
     * @param int $newReserved The new total reserved seats.
     * @param int $eventId The event id associated with the reservation.
     * @return int|false Returns the number of affected rows on success, 0 if no update is needed, or false on failure.
     */
    public function updateReservedSeatsBasedOnDelta($previousReserved, $newReserved, $eventId)
    {
        // Calculate the delta between new and previous reserved seats.
        $delta = $newReserved - $previousReserved;

        // If more seats need to be reserved:
        if ($delta > 0) {
            // Update free seats (status 0) to reserved (status 2) using ASC order.
            return $this->updateRowsByStatusAndEventIdAndLimit(0, 2, $eventId, null, $delta, "ASC");
        }
        // If seats are to be released:
        elseif ($delta < 0) {
            // Update reserved seats (status 2) back to free (status 0) using DESC order.
            return $this->updateRowsByStatusAndEventIdAndLimit(2, 0, $eventId, 1, abs($delta), "DESC");
        }
        // If no change is needed, return 0.
        else {
            return 0;
        }
    }


    /**
     * Count the number of rows that are reserved by a specific event.
     * Optionally, filter by status.
     *
     * @param int $eventId The event id to count rows for.
     * @param int|array|null $status Optional status filter; can be an int or an array of ints. Default is null.
     * @return int The count of rows where reserved_by_event_id equals the input event id, and matches the optional status filter.
     */
    public function countRowsByEventId($eventId, $status = null)
    {
        $this->ensureConnection();
        $sql = "SELECT COUNT(*) AS reserved_count FROM tbl_hall_seat_details WHERE reserved_by_event_id = $eventId";

        if ($status !== null) {
            if (is_array($status)) {
                $statusList = implode(',', array_map('intval', $status));
                $sql .= " AND status IN ($statusList)";
            } else {
                $status = intval($status);
                $sql .= " AND status = $status";
            }
        }

        $result = mysqli_query($this->conn, $sql);
        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            return (int)$row['reserved_count'];
        }
        return 0;
    }

    /**
     * Load hall seat details based on user_id and status.
     * @param int|null $user_id (Optional) Specific user_id to load.
     * @param int|null $status (Optional) Status filter.
     * @return array|false Returns array of results, false if no match.
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
            if (count($data) == 1) {
                $this->setProperties($data[0]);
            }
            return $data;
        }
        return false;
    }

    /**
     * Load hall seat details based on seat_id.
     * @param int $seat_id Seat ID to search for.
     * @param int|null $status (Optional) Status filter.
     * @return array|false Returns an array of results, false if no match.
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
            if (count($data) == 1) {
                $this->setProperties($data[0]);
            }
            return $data;
        }
        return false;
    }

    /**
     * get all seat IDs by event ID and status
     * @param int $event_id Event ID to search for.
     * @param int|null $status (Optional) Status filter.
     */
    public function getAllSeatIdsByEventIdAndStatus($event_id, $status = null)
    {
        $this->ensureConnection();
        $sql = "SELECT seat_id FROM tbl_hall_seat_details WHERE reserved_by_event_id = $event_id";
        if ($status !== null) {
            $sql .= " AND status = $status";
        }
        $result = mysqli_query($this->conn, $sql);
        if ($result && mysqli_num_rows($result) > 0) {
            $data = [];
            while ($row = mysqli_fetch_assoc($result)) {
                $data[] = $row['seat_id'];
            }
            return $data;
        }
        return [];
    }

    /**
     * Check if a user with a specific status is available.
     * @param int $user_id User ID to check.
     * @param int|null $status (Optional) Status to filter by.
     * @return int Returns 1 if available, 0 otherwise.
     */
    public function isResident($user_id, $status = null)
    {
        $this->ensureConnection();
        $sql = "SELECT 1 FROM tbl_hall_seat_details WHERE user_id = $user_id";
        if ($status !== null) {
            $sql .= " AND status = $status";
        }
        $result = mysqli_query($this->conn, $sql);
        return (mysqli_num_rows($result) ? 1 : 0);
    }


    /**
     * Set properties based on the loaded data.
     * @param array $row Row of data from the database.
     */
    public function setProperties($row)
    {
        $this->seat_id = $row['seat_id'];
        $this->reserved_by_event_id = $row['reserved_by_event_id'];
        $this->status = $row['status'];
        $this->user_id = $row['user_id'];
        $this->floor_no = $row['floor_no'];
        $this->room_no = $row['room_no'];
        $this->created = $row['created'];
        $this->modified = $row['modified'];
        // Assign the new modified_by property.
        $this->modified_by = isset($row['modified_by']) ? $row['modified_by'] : -1;
    }

    /**
     * Get the highest floor number, the highest room number for each floor, and the number of seats in each room.
     * If no floors exist, return -1.
     * @return array|int An associative array containing the highest floor, the highest room for each floor, and the seat count for each room, or -1 if no floors exist.
     */
    public function getFloorRoomSeatSummary()
    {
        $this->ensureConnection();

        $sqlCheckFloors = "SELECT COUNT(DISTINCT floor_no) AS floor_count FROM tbl_hall_seat_details";
        $resultCheckFloors = mysqli_query($this->conn, $sqlCheckFloors);
        if ($resultCheckFloors && mysqli_num_rows($resultCheckFloors) > 0) {
            $row = mysqli_fetch_assoc($resultCheckFloors);
            if ($row['floor_count'] == 0) {
                return -1;
            }
        } else {
            return -1;
        }

        $resultMap = [];
        $sqlMaxFloor = "SELECT MAX(floor_no) AS max_floor_no FROM tbl_hall_seat_details";
        $resultMaxFloor = mysqli_query($this->conn, $sqlMaxFloor);
        if ($resultMaxFloor && mysqli_num_rows($resultMaxFloor) > 0) {
            $row = mysqli_fetch_assoc($resultMaxFloor);
            $max_floor_no = $row['max_floor_no'];
        } else {
            return [];
        }

        for ($floor_no = 0; $floor_no <= $max_floor_no; $floor_no++) {
            $resultMap[$floor_no] = [];
            $sqlMaxRoomNo = "SELECT MAX(room_no) AS max_room_no FROM tbl_hall_seat_details WHERE floor_no = $floor_no";
            $resultMaxRoomNo = mysqli_query($this->conn, $sqlMaxRoomNo);
            if ($resultMaxRoomNo && mysqli_num_rows($resultMaxRoomNo) > 0) {
                $row = mysqli_fetch_assoc($resultMaxRoomNo);
                $max_room_no = $row['max_room_no'];

                for ($room_no = 1; $room_no <= $max_room_no; $room_no++) {
                    $sqlSeatCount = "SELECT COUNT(seat_id) AS seat_count FROM tbl_hall_seat_details WHERE floor_no = $floor_no AND room_no = $room_no";
                    $resultSeatCount = mysqli_query($this->conn, $sqlSeatCount);
                    if ($resultSeatCount && mysqli_num_rows($resultSeatCount) > 0) {
                        $seatRow = mysqli_fetch_assoc($resultSeatCount);
                        $seat_count = $seatRow['seat_count'];
                        $resultMap[$floor_no][$room_no] = $seat_count;
                    }
                }
            }
        }
        return $resultMap;
    }

    /**
     * Get the highest floor number. If no floors exist, return -1.
     * @return int The highest floor number or -1 if no floors exist.
     */
    public function getMaxFloorNo()
    {
        $this->ensureConnection();
        $sqlMaxFloor = "SELECT MAX(floor_no) AS max_floor_no FROM tbl_hall_seat_details";
        $resultMaxFloor = mysqli_query($this->conn, $sqlMaxFloor);
        if ($resultMaxFloor && mysqli_num_rows($resultMaxFloor) >= 0) {
            $row = mysqli_fetch_assoc($resultMaxFloor);
            $max_floor_no = $row['max_floor_no'];
            return ($max_floor_no !== null) ? $max_floor_no : -1;
        }
        return -1;
    }

    /**
     * Get the highest room number for a given floor number.
     * @param int $floor_no The floor number to get the highest room number for.
     * @return int The highest room number for the given floor or -1 if no rooms exist.
     */
    public function getHighestRoomNoByFloor($floor_no)
    {
        $this->ensureConnection();
        $sql = "SELECT MAX(room_no) AS max_room_no FROM tbl_hall_seat_details WHERE floor_no = $floor_no";
        $result = mysqli_query($this->conn, $sql);
        if ($result && mysqli_num_rows($result) >= 0) {
            $row = mysqli_fetch_assoc($result);
            $max_room_no = $row['max_room_no'];
            return ($max_room_no !== null) ? $max_room_no : 0;
        }
        return -1;
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
        $this->ensureConnection();
        $insert_values = [];
        foreach ($per_room_seat as $num_seats) {
            for ($i = 1; $i <= $num_seats; $i++) {
                $insert_values[] = "($floor_no, $starting_room_no)";
            }
            $starting_room_no++;
        }
        if (!empty($insert_values)) {
            $sql = "INSERT INTO tbl_hall_seat_details (floor_no, room_no) VALUES " . implode(',', $insert_values);
            $result = mysqli_query($this->conn, $sql);
            if ($result) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * Get all rows with optional floor number and status filters.
     *
     * @param int|array|null $floor_no The floor number (or array of floor numbers) to filter by. Default is null.
     * @param int|array|null $status The status (or array of statuses) to filter by. Default is null.
     * @return array|false Returns an array of rows matching the filters, or false if no rows are found.
     */
    public function getRowsByFloorNo($floor_no = null, $status = null)
    {
        $this->ensureConnection();
        $sql = "SELECT * FROM tbl_hall_seat_details WHERE 1";
        if ($floor_no !== null) {
            if (is_array($floor_no)) {
                $floorNoList = implode(',', array_map('intval', $floor_no));
                $sql .= " AND floor_no IN ($floorNoList)";
            } else {
                $sql .= " AND floor_no = " . intval($floor_no);
            }
        }
        if ($status !== null) {
            if (is_array($status)) {
                $statusList = implode(',', array_map('intval', $status));
                $sql .= " AND status IN ($statusList)";
            } else {
                $sql .= " AND status = " . intval($status);
            }
        }
        $result = mysqli_query($this->conn, $sql);
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
        $this->ensureConnection();
        $sql = "SELECT * FROM tbl_hall_seat_details WHERE 1";
        if ($floor_no !== null) {
            $sql .= " AND floor_no = " . intval($floor_no);
        }
        if ($room_no !== null) {
            $sql .= " AND room_no = " . intval($room_no);
        }
        if ($status !== null) {
            if (is_array($status)) {
                $statusList = implode(',', array_map('intval', $status));
                $sql .= " AND status IN ($statusList)";
            } else {
                $sql .= " AND status = " . intval($status);
            }
        }
        $result = mysqli_query($this->conn, $sql);
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
        $this->ensureConnection();
        $insertValues = [];
        for ($i = 1; $i <= $seat_count; $i++) {
            $insertValues[] = "($floor_no, $room_no)";
        }
        if (!empty($insertValues)) {
            $sqlInsert = "INSERT INTO tbl_hall_seat_details (floor_no, room_no) VALUES " . implode(',', $insertValues);
            $resultInsert = mysqli_query($this->conn, $sqlInsert);
            if ($resultInsert) {
                return mysqli_affected_rows($this->conn);
            } else {
                return false;
            }
        }
        return false;
    }

    /**
     * Check if a given floor number exists in the tbl_hall_seat_details table.
     *
     * @param int $floor_no The floor number to check.
     * @return bool Returns true if the floor exists, false otherwise.
     */
    public function isFloorExist($floor_no)
    {
        $this->ensureConnection();
        $sql = "SELECT 1 FROM tbl_hall_seat_details WHERE floor_no = $floor_no LIMIT 1";
        $result = mysqli_query($this->conn, $sql);
        return (mysqli_num_rows($result) ? true : false);
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