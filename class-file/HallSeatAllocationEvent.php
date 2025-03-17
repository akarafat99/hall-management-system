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
    public $seat_allotted_notice_date = "";
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
            7  => ['seat_allotted_notice_date', "ALTER TABLE tbl_hall_seat_allocation_event ADD COLUMN seat_allotted_notice_date DATE"],
            8  => ['seat_confirm_deadline_date', "ALTER TABLE tbl_hall_seat_allocation_event ADD COLUMN seat_confirm_deadline_date DATE"],
            9  => ['priority_list', "ALTER TABLE tbl_hall_seat_allocation_event ADD COLUMN priority_list TEXT"],
            10 => ['seat_distribution_quota', "ALTER TABLE tbl_hall_seat_allocation_event ADD COLUMN seat_distribution_quota TEXT"],
            11 => ['created', "ALTER TABLE tbl_hall_seat_allocation_event ADD COLUMN created TIMESTAMP DEFAULT CURRENT_TIMESTAMP"],
            12 => ['modified', "ALTER TABLE tbl_hall_seat_allocation_event ADD COLUMN modified TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP"]
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
                (status, title, details, application_start_date, application_end_date, viva_notice_date, seat_allotted_notice_date, seat_confirm_deadline_date, priority_list, seat_distribution_quota)
                VALUES ($this->status, '$this->title', '$this->details', '$this->application_start_date', '$this->application_end_date', '$this->viva_notice_date', '$this->seat_allotted_notice_date', '$this->seat_confirm_deadline_date', '$this->priority_list', '$this->seat_distribution_quota')";
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
            $this->seat_allotted_notice_date = $row['seat_allotted_notice_date'];
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
                    seat_allotted_notice_date = '$this->seat_allotted_notice_date',
                    seat_confirm_deadline_date = '$this->seat_confirm_deadline_date',
                    priority_list = '$this->priority_list',
                    seat_distribution_quota = '$this->seat_distribution_quota'
                WHERE event_id = $this->event_id";
        return mysqli_query($this->conn, $sql) ? true : "Error updating record: " . mysqli_error($this->conn);
    }

    /**
     * Load rows from tbl_hall_seat_allocation_event filtered by event_id and status.
     *
     * @param int|null $event_id The event ID to filter by.
     * @param int|array|null $status (Optional) The status value(s) to filter by. Defaults to null.
     * @return array|false Returns an array of rows (as associative arrays) if found, or false otherwise.
     */
    public function getByEventAndStatus($event_id = null, $status = null)
    {
        $this->ensureConnection();

        // Build the base query.
        $sql = "SELECT * FROM tbl_hall_seat_allocation_event WHERE 1";

        if ($event_id !== null) {
            $sql .= " AND event_id = " . intval($event_id);
        }

        if ($status !== null) {
            if (is_array($status)) {
                // Convert array values to integers and implode them with commas.
                $statusList = implode(',', array_map('intval', $status));
                $sql .= " AND status IN ($statusList)";
            } else {
                $sql .= " AND status = " . intval($status);
            }
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
