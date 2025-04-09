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
            9  => ['seat_confirm_date',"ALTER TABLE tbl_hall_seat_allocation_event_details ADD COLUMN seat_confirm_date DATE"],
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
}
?>

<!-- end -->