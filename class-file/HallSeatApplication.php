<?php
include_once 'DatabaseConnector.php';

class HallSeatAllocationEventDetails
{
    public $application_id = 0;
    public $status = 0;
    public $event_id = 0;
    public $user_id = 0;
    public $user_details_id = 0;
    public $serial_no = 0;
    public $viva_date = "";
    public $viva_time_slot = "";
    public $allotted_seat_id = 0;
    public $seat_confirm_date = "";
    public $created;
    public $modified;

    public $conn;

    /**
     * Constructor: Initializes the database connection.
     */
    public function __construct()
    {
        $this->ensureConnection();
    }

    /**
     * Ensures that a database connection is established.
     */
    public function ensureConnection()
    {
        if (!$this->conn) {
            $db = new DatabaseConnector();
            $db->connect();
            $this->conn = $db->getConnection();
        }
    }

    /**
     * Create table tbl_hall_seat_allocation_event_details with only the application_id column if it does not exist.
     *
     * @return void
     */
    public function createTableMinimal()
    {
        $sql = "CREATE TABLE IF NOT EXISTS tbl_hall_seat_allocation_event_details (
                    application_id INT AUTO_INCREMENT PRIMARY KEY
                ) ENGINE=InnoDB";
        $result = mysqli_query($this->conn, $sql);
        if ($result) {
            echo "Minimal table 'tbl_hall_seat_allocation_event_details' created successfully <br>";
        } else {
            echo "Error creating minimal table: " . mysqli_error($this->conn) . "<br>";
        }
    }

    /**
     * Alter table tbl_hall_seat_allocation_event_details to add additional columns.
     *
     * Each query is defined as a map entry where the key is a number and the value is an array:
     * [column name, SQL query].
     *
     * @param array|null $selectedNums Optional array of keys. If provided, only the queries with these keys will run.
     * @return void
     */
    public function alterTableAddColumns($selectedNums = null)
    {
        // Define queries as a map: key => [column name, SQL query]
        $alterQueries = [
            1  => ['status',           "ALTER TABLE tbl_hall_seat_allocation_event_details ADD COLUMN status INT NOT NULL"],
            2  => ['event_id',         "ALTER TABLE tbl_hall_seat_allocation_event_details ADD COLUMN event_id INT NOT NULL"],
            3  => ['user_id',          "ALTER TABLE tbl_hall_seat_allocation_event_details ADD COLUMN user_id INT NOT NULL"],
            4  => ['user_details_id',  "ALTER TABLE tbl_hall_seat_allocation_event_details ADD COLUMN user_details_id INT NOT NULL"],
            5  => ['serial_no',        "ALTER TABLE tbl_hall_seat_allocation_event_details ADD COLUMN serial_no INT NOT NULL"],
            6  => ['viva_date',        "ALTER TABLE tbl_hall_seat_allocation_event_details ADD COLUMN viva_date DATE"],
            7  => ['viva_time_slot',   "ALTER TABLE tbl_hall_seat_allocation_event_details ADD COLUMN viva_time_slot VARCHAR(50)"],
            8  => ['allotted_seat_id', "ALTER TABLE tbl_hall_seat_allocation_event_details ADD COLUMN allotted_seat_id INT NOT NULL"],
            9  => ['seat_confirm_date', "ALTER TABLE tbl_hall_seat_allocation_event_details ADD COLUMN seat_confirm_date DATE"],
            10 => ['created',          "ALTER TABLE tbl_hall_seat_allocation_event_details ADD COLUMN created TIMESTAMP DEFAULT CURRENT_TIMESTAMP"],
            11 => ['modified',         "ALTER TABLE tbl_hall_seat_allocation_event_details ADD COLUMN modified TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP"]
        ];

        // Filter the queries if a subset is provided.
        if ($selectedNums !== null && is_array($selectedNums)) {
            $filteredQueries = [];
            foreach ($selectedNums as $num) {
                if (isset($alterQueries[$num])) {
                    $filteredQueries[$num] = $alterQueries[$num];
                }
            }
            $alterQueries = $filteredQueries;
        }

        // Execute each query.
        foreach ($alterQueries as $num => $queryInfo) {
            list($colName, $sql) = $queryInfo;
            $result = mysqli_query($this->conn, $sql);
            if ($result) {
                echo "Column '{$colName}' added successfully (Key: {$num}).<br>";
            } else {
                echo "Error adding column '{$colName}' (Key: {$num}): " . mysqli_error($this->conn) . "<br>";
            }
        }
    }

    /**
     * Insert a new record into tbl_hall_seat_allocation_event_details.
     *
     * @return bool|string Returns true if successful, otherwise an error message.
     */
    public function insert()
    {
        $sql = "INSERT INTO tbl_hall_seat_allocation_event_details 
                (status, event_id, user_id, user_details_id, serial_no, viva_date, viva_time_slot, allotted_seat_id, seat_confirm_date)
                VALUES ($this->status, $this->event_id, $this->user_id, $this->user_details_id, $this->serial_no, '$this->viva_date', '$this->viva_time_slot', $this->allotted_seat_id, '$this->seat_confirm_date')";

        if (mysqli_query($this->conn, $sql)) {
            $this->application_id = mysqli_insert_id($this->conn);
            return true;
        } else {
            return "Error inserting record: " . mysqli_error($this->conn);
        }
    }

    /**
     * Load a record by application_id.
     *
     * @return bool|string Returns true if the record is found, otherwise an error message.
     */
    public function load()
    {
        $sql = "SELECT * FROM tbl_hall_seat_allocation_event_details WHERE application_id = $this->application_id LIMIT 1";
        $result = mysqli_query($this->conn, $sql);
        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $this->setProperties($row);
            return true;
        } else {
            return "No record found with application_id: $this->application_id";
        }
    }

    /**
     * Update record by application_id.
     *
     * @return bool|string Returns true if update is successful, otherwise an error message.
     */
    public function update()
    {
        if (!$this->application_id) {
            return "Application ID not set. Cannot update record.";
        }

        $sql = "UPDATE tbl_hall_seat_allocation_event_details SET
                    status = $this->status,
                    event_id = $this->event_id,
                    user_id = $this->user_id,
                    user_details_id = $this->user_details_id,
                    serial_no = $this->serial_no,
                    viva_date = '$this->viva_date',
                    viva_time_slot = '$this->viva_time_slot',
                    allotted_seat_id = $this->allotted_seat_id,
                    seat_confirm_date = '$this->seat_confirm_date'
                WHERE application_id = $this->application_id";

        return mysqli_query($this->conn, $sql) ? true : "Error updating record: " . mysqli_error($this->conn);
    }

    /**
     * Update the status for a given application_id.
     *
     * @param int $application_id The application ID to update.
     * @param int $status The new status.
     * @return bool|string Returns true if successful, otherwise an error message.
     */
    public function updateStatus($application_id, $status)
    {
        $this->ensureConnection();
        $application_id = intval($application_id);
        $status = intval($status);
        $sql = "UPDATE tbl_hall_seat_allocation_event_details SET status = $status WHERE application_id = $application_id";

        if (mysqli_query($this->conn, $sql)) {
            return true;
        } else {
            return "Error updating status: " . mysqli_error($this->conn);
        }
    }

    /**
     * Update viva_date and viva_time_slot (as integer values) for a set of application records in one SQL query.
     * 
     * The function processes the input list of application IDs and a list of dates.
     * For each date in the date_list, it assigns up to $per_time_slot application IDs
     * for time slot 1 (viva_time_slot = 1) and then up to $per_time_slot IDs for time slot 2 (viva_time_slot = 2).
     * 
     * For example, if there are 32 application IDs, 4 dates, and 5 applications per time slot,
     * then:
     *   - Date 1, Time Slot 1 (value 1): IDs 1 to 5,
     *   - Date 1, Time Slot 2 (value 2): IDs 6 to 10,
     *   - Date 2, Time Slot 1 (value 1): IDs 11 to 15,
     *   - Date 2, Time Slot 2 (value 2): IDs 16 to 20,
     *   - Date 3, Time Slot 1 (value 1): IDs 21 to 25,
     *   - Date 3, Time Slot 2 (value 2): IDs 26 to 30,
     *   - Date 4, Time Slot 1 (value 1): IDs 31 to 32 (if fewer than 5 are available),
     *   - Date 4, Time Slot 2 (value 2): (no IDs if none remain).
     * 
     * @param array $application_ids An array of application IDs in the order to be updated.
     * @param array $date_list       An array of dates.
     * @param int   $per_time_slot   The number of application IDs per time slot.
     *
     * @return bool|string Returns true if the update is successful, or an error message.
     */
    public function updateVivaDetailsByTimeSlot($application_ids, $date_list, $per_time_slot)
    {
        // Ensure the database connection is available.
        $this->ensureConnection();

        // Validate that $application_ids and $date_list are arrays.
        if (!is_array($application_ids) || !is_array($date_list)) {
            return "Both application_ids and date_list must be arrays.";
        }

        // Initialize the CASE expression strings and an array to collect all processed IDs.
        $caseVivaDate = "CASE application_id ";
        $caseVivaTimeSlot = "CASE application_id ";
        $idList = [];

        // This index will traverse the application_ids array.
        $appIndex = 0;
        $totalApps = count($application_ids);

        // Iterate over each date in the date list.
        foreach ($date_list as $date) {
            // Escape the date value for SQL safety.
            $escapedDate = mysqli_real_escape_string($this->conn, $date);

            // Process the current date for Time Slot 1 (value 1).
            for ($i = 0; $i < $per_time_slot; $i++) {
                if ($appIndex >= $totalApps) {
                    break 2; // Exit both loops if no more application IDs are left.
                }
                $app_id = intval($application_ids[$appIndex]);
                $idList[] = $app_id;
                // For Time Slot 1, assign the date and set viva_time_slot to 1.
                $caseVivaDate .= "WHEN $app_id THEN '$escapedDate' ";
                $caseVivaTimeSlot .= "WHEN $app_id THEN 1 ";
                $appIndex++;
            }

            // Process the current date for Time Slot 2 (value 2).
            for ($i = 0; $i < $per_time_slot; $i++) {
                if ($appIndex >= $totalApps) {
                    break 2; // Exit both loops if no more IDs remain.
                }
                $app_id = intval($application_ids[$appIndex]);
                $idList[] = $app_id;
                // For Time Slot 2, assign the same date and set viva_time_slot to 2.
                $caseVivaDate .= "WHEN $app_id THEN '$escapedDate' ";
                $caseVivaTimeSlot .= "WHEN $app_id THEN 2 ";
                $appIndex++;
            }
        }

        // Complete the CASE expressions with a fallback.
        $caseVivaDate .= "ELSE viva_date END";
        $caseVivaTimeSlot .= "ELSE viva_time_slot END";

        // Build the WHERE clause using the processed application IDs.
        $idListStr = implode(',', $idList);

        // Combine everything into a single UPDATE SQL statement.
        $sql = "UPDATE tbl_hall_seat_allocation_event_details 
            SET viva_date = $caseVivaDate, 
                viva_time_slot = $caseVivaTimeSlot 
            WHERE application_id IN ($idListStr)";

        // Execute the query.
        if (mysqli_query($this->conn, $sql)) {
            return true;
        } else {
            return "Error updating records: " . mysqli_error($this->conn);
        }
    }


    /**
     * Set class properties based on an associative array.
     *
     * @param array $row The row data.
     */
    public function setProperties($row)
    {
        $this->application_id    = $row['application_id'];
        $this->status            = $row['status'];
        $this->event_id          = $row['event_id'];
        $this->user_id           = $row['user_id'];
        $this->user_details_id   = $row['user_details_id'];
        $this->serial_no         = $row['serial_no'];
        $this->viva_date         = $row['viva_date'];
        $this->viva_time_slot    = $row['viva_time_slot'];
        $this->allotted_seat_id  = $row['allotted_seat_id'];
        $this->seat_confirm_date = $row['seat_confirm_date'];
        $this->created           = $row['created'];
        $this->modified          = $row['modified'];
    }

    /**
     * Retrieve application records based on application_id, status, and user_id filters,
     * with optional sorting.
     *
     * @param int|array|null  $application_id Filter by application_id (int or array of ints)
     * @param int|array|null  $status         Filter by status (int or array of ints)
     * @param int|array|null  $user_id        Filter by user_id (int or array of ints)
     * @param string          $sortCol        Column to sort by (default is 'application_id')
     * @param string          $sortType       Sort order, either 'ASC' or 'DESC' (default is 'ASC')
     *
     * @return array|false Returns an array of matching records or false if none found.
     */
    public function getApplication($application_id = null, $status = null, $user_id = null, $sortCol = 'application_id', $sortType = 'ASC')
    {
        // Ensure the database connection is available.
        $this->ensureConnection();

        // Start building the SQL query.
        $sql = "SELECT * FROM tbl_hall_seat_allocation_event_details WHERE 1=1";

        // Filter by application_id.
        if (!is_null($application_id)) {
            if (is_array($application_id)) {
                $application_ids = array_map('intval', $application_id);
                $appList = implode(',', $application_ids);
                $sql .= " AND application_id IN ($appList)";
            } else {
                $appId = intval($application_id);
                $sql .= " AND application_id = $appId";
            }
        }

        // Filter by status.
        if (!is_null($status)) {
            if (is_array($status)) {
                $statusArray = array_map('intval', $status);
                $statusList = implode(',', $statusArray);
                $sql .= " AND status IN ($statusList)";
            } else {
                $statusInt = intval($status);
                $sql .= " AND status = $statusInt";
            }
        }

        // Filter by user_id.
        if (!is_null($user_id)) {
            if (is_array($user_id)) {
                $userIds = array_map('intval', $user_id);
                $userList = implode(',', $userIds);
                $sql .= " AND user_id IN ($userList)";
            } else {
                $uid = intval($user_id);
                $sql .= " AND user_id = $uid";
            }
        }

        // Define allowed columns to prevent SQL injection in ORDER BY clause.
        $allowedSortCols = ['application_id', 'status', 'event_id', 'user_id', 'user_details_id', 'serial_no', 'viva_date', 'viva_time_slot', 'allotted_seat_id', 'seat_confirm_date', 'created', 'modified'];
        if (!in_array($sortCol, $allowedSortCols)) {
            $sortCol = 'application_id';
        }

        // Ensure sort type is valid.
        $sortType = strtoupper($sortType);
        if ($sortType !== 'ASC' && $sortType !== 'DESC') {
            $sortType = 'ASC';
        }

        // Append sorting to the query.
        $sql .= " ORDER BY $sortCol $sortType";

        // Execute the query.
        $result = mysqli_query($this->conn, $sql);

        // Fetch and return results if found.
        if ($result && mysqli_num_rows($result) > 0) {
            $applications = [];
            while ($row = mysqli_fetch_assoc($result)) {
                $applications[] = $row;
            }
            return $applications;
        }
        return false;
    }
}
?>

<!-- end -->