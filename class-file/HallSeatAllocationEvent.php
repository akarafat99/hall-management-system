<?php
include_once 'DatabaseConnector.php';

class HallSeatAllocationEvent
{
    public $event_id = 0;
    public $status = 0;
    public $title = "";
    public $details = "";
    public $application_start_date = "";
    public $application_end_date = "";
    public $viva_notice_date = "";
    public $seat_allotment_result_notice_date = "";
    public $seat_allotment_result_notice_text = "";
    public $seat_confirm_deadline_date = "";
    public $priority_list = "";
    public $seat_distribution_quota = "";
    public $created = "";
    public $modified = "";

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
        } else {
            return 0;
        }
    }

    /**
     * Create the minimal table tbl_hall_seat_allocation_event with only the event_id column if it does not exist.
     */
    public function createTableMinimal()
    {
        $this->ensureConnection();
        $sql = "CREATE TABLE IF NOT EXISTS tbl_hall_seat_allocation_event (
                    event_id INT AUTO_INCREMENT PRIMARY KEY
                ) ENGINE=InnoDB";
        $result = mysqli_query($this->conn, $sql);
        if ($result) {
            echo "Minimal table 'tbl_hall_seat_allocation_event' created successfully.<br>";
        } else {
            echo "Error creating minimal table 'tbl_hall_seat_allocation_event': " . mysqli_error($this->conn) . "<br>";
        }
    }

    /**
     * Alter the table to add additional columns.
     * Optionally, you can pass an array of keys to selectively run specific alter queries.
     */
    public function alterTableAddColumns($selectedNums = null)
    {
        $this->ensureConnection();

        // Map of alter queries: key => [column name, SQL query]
        $alterQueries = [
            1  => ['status', "ALTER TABLE tbl_hall_seat_allocation_event ADD COLUMN status INT NOT NULL"],
            2  => ['title', "ALTER TABLE tbl_hall_seat_allocation_event ADD COLUMN title VARCHAR(255)"],
            3  => ['details', "ALTER TABLE tbl_hall_seat_allocation_event ADD COLUMN details TEXT"],
            4  => ['application_start_date', "ALTER TABLE tbl_hall_seat_allocation_event ADD COLUMN application_start_date DATE"],
            5  => ['application_end_date', "ALTER TABLE tbl_hall_seat_allocation_event ADD COLUMN application_end_date DATE"],
            6  => ['viva_notice_date', "ALTER TABLE tbl_hall_seat_allocation_event ADD COLUMN viva_notice_date DATE"],
            7  => ['seat_allotment_result_notice_date', "ALTER TABLE tbl_hall_seat_allocation_event ADD COLUMN seat_allotment_result_notice_date DATE"],
            8  => ['seat_allotment_result_notice_text', "ALTER TABLE tbl_hall_seat_allocation_event ADD COLUMN seat_allotment_result_notice_text TEXT"],
            9  => ['seat_confirm_deadline_date', "ALTER TABLE tbl_hall_seat_allocation_event ADD COLUMN seat_confirm_deadline_date DATE"],
            10  => ['priority_list', "ALTER TABLE tbl_hall_seat_allocation_event ADD COLUMN priority_list TEXT"],
            11 => ['seat_distribution_quota', "ALTER TABLE tbl_hall_seat_allocation_event ADD COLUMN seat_distribution_quota TEXT"],
            12 => ['created', "ALTER TABLE tbl_hall_seat_allocation_event ADD COLUMN created TIMESTAMP DEFAULT CURRENT_TIMESTAMP"],
            13 => ['modified', "ALTER TABLE tbl_hall_seat_allocation_event ADD COLUMN modified TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP"]
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

        // Execute each alter query
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
     * Insert a new hall seat allocation event record.
     * Excludes the auto-increment event_id column.
     */
    public function insert()
    {
        $this->ensureConnection();
        $sql = "INSERT INTO tbl_hall_seat_allocation_event 
                (status, title, details, application_start_date, application_end_date, viva_notice_date, priority_list, seat_distribution_quota)
                VALUES ($this->status, '$this->title', '$this->details', '$this->application_start_date', '$this->application_end_date', '$this->viva_notice_date', '$this->priority_list', '$this->seat_distribution_quota')";
        if (mysqli_query($this->conn, $sql)) {
            $this->event_id = mysqli_insert_id($this->conn);
            return 1;
        } else {
            return 0;
        }
    }

    /**
     * Load an event record by event_id and update object properties.
     */
    public function load()
    {
        $sql = "SELECT * FROM tbl_hall_seat_allocation_event WHERE event_id = $this->event_id LIMIT 1";
        $result = mysqli_query($this->conn, $sql);
        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $this->status = $row['status'];
            $this->title = $row['title'];
            $this->details = $row['details'];
            $this->application_start_date = $row['application_start_date'];
            $this->application_end_date = $row['application_end_date'];
            $this->viva_notice_date = $row['viva_notice_date'];
            $this->seat_allotment_result_notice_date = $row['seat_allotment_result_notice_date'];
            $this->seat_allotment_result_notice_text = $row['seat_allotment_result_notice_text'];
            $this->seat_confirm_deadline_date = $row['seat_confirm_deadline_date'];
            $this->priority_list = $row['priority_list'];
            $this->seat_distribution_quota = $row['seat_distribution_quota'];
            $this->created = $row['created'];
            $this->modified = $row['modified'];
            return true;
        } else {
            return "No record found with event_id: $this->event_id";
        }
    }

    /**
     * Update an existing event record identified by event_id.
     */
    public function update()
    {
        if (!$this->event_id) {
            return "Event ID not set. Cannot update record.";
        }
        $sql = "UPDATE tbl_hall_seat_allocation_event SET 
                    status = $this->status,
                    title = '$this->title',
                    details = '$this->details',
                    application_start_date = '$this->application_start_date',
                    application_end_date = '$this->application_end_date',
                    viva_notice_date = '$this->viva_notice_date',
                    seat_allotment_result_notice_date = '$this->seat_allotment_result_notice_date',
                    seat_allotment_result_notice_text = '$this->seat_allotment_result_notice_text',
                    seat_confirm_deadline_date = '$this->seat_confirm_deadline_date',
                    priority_list = '$this->priority_list',
                    seat_distribution_quota = '$this->seat_distribution_quota'
                WHERE event_id = $this->event_id";
        // return mysqli_query($this->conn, $sql) ? true : "Error updating record: " . mysqli_error($this->conn);
        if (mysqli_query($this->conn, $sql)) {
            return true;
        } else {
            return false;
            return "Error updating record: " . mysqli_error($this->conn);
        }
    }

    /**
     * Update the status of an event.
     *
     * @param int $event_id The ID of the event to update.
     * @param int $new_status The new status value.
     * @return bool Returns true if the update is successful, false otherwise.
     */
    public function updateStatus($event_id, $new_status)
    {
        // Ensure the database connection is established
        $this->ensureConnection();

        // Validate the seat_id
        if ($event_id <= 0) {
            return false;
        }

        // Prepare and execute the update query
        $sql = "UPDATE tbl_hall_seat_allocation_event SET status = $new_status WHERE event_id = $event_id";
        if (mysqli_query($this->conn, $sql)) {
            return true;
        } else {
            // Optionally, log the error: mysqli_error($this->conn)
            return false;
        }
    }

    /**
     * Load rows from tbl_hall_seat_allocation_event filtered by event_id and status.
     *
     * @param int|array|null $event_id The event ID(s) to filter by.
     * @param int|array|null $status The status value(s) to filter by.
     * @param string|null $sort_col The column name to sort the results by.
     * @param string|null $sort_type The sort direction (ASC or DESC). Defaults to ASC.
     * @return array|false Returns an array of rows (as associative arrays) if found, or false otherwise.
     */
    public function getByEventAndStatus($event_id = null, $status = null, $sort_col = null, $sort_type = null)
    {
        $this->ensureConnection();

        // Build the base query.
        $sql = "SELECT * FROM tbl_hall_seat_allocation_event WHERE 1";

        // Process event_id filter: supports int or array of ints.
        if ($event_id !== null) {
            if (is_array($event_id)) {
                $eventIdList = implode(',', array_map('intval', $event_id));
                $sql .= " AND event_id IN ($eventIdList)";
            } else {
                $sql .= " AND event_id = " . intval($event_id);
            }
        }

        // Process status filter: supports int or array of ints.
        if ($status !== null) {
            if (is_array($status)) {
                $statusList = implode(',', array_map('intval', $status));
                $sql .= " AND status IN ($statusList)";
            } else {
                $sql .= " AND status = " . intval($status);
            }
        }

        // Process sorting options if provided.
        if ($sort_col !== null) {
            // Validate sort_type; default to ASC if not explicitly set to DESC.
            $sort_type = (strtoupper($sort_type) === 'DESC') ? 'DESC' : 'ASC';
            $sql .= " ORDER BY " . $sort_col . " " . $sort_type;
        }

        $result = mysqli_query($this->conn, $sql);
        if ($result && mysqli_num_rows($result) > 0) {
            $rows = [];
            while ($row = mysqli_fetch_assoc($result)) {
                $rows[] = $row;
            }
            return $rows;
        }
        return false;
    }
}


?>

<!-- end of file -->