<?php
include_once 'DatabaseConnector.php';

class HallSeatAllocationEvent
{
    public $event_id = 0;
    public $status = 1;
    public $title = "";
    public $details = "";
    public $application_start_date = "";
    public $application_end_date = "";
    public $viva_notice_date = "";
    // Added new variables right after viva_notice_date
    public $viva_date_list = "";
    public $viva_student_count = "";
    public $seat_allotment_result_notice_date = "";
    public $seat_allotment_result_notice_text = "";
    public $seat_confirm_deadline_date = "";
    public $priority_list = "";
    public $semester_priority = "";
    public $seat_distribution_quota = "";
    public $scoring_factor = "";
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
            // Insert new columns right after viva_notice_date using the AFTER clause.
            7  => ['viva_date_list', "ALTER TABLE tbl_hall_seat_allocation_event ADD COLUMN viva_date_list TEXT AFTER viva_notice_date"],
            8  => ['viva_student_count', "ALTER TABLE tbl_hall_seat_allocation_event ADD COLUMN viva_student_count TEXT AFTER viva_date_list"],
            9  => ['seat_allotment_result_notice_date', "ALTER TABLE tbl_hall_seat_allocation_event ADD COLUMN seat_allotment_result_notice_date DATE DEFAULT NULL"],
            10 => ['seat_allotment_result_notice_text', "ALTER TABLE tbl_hall_seat_allocation_event ADD COLUMN seat_allotment_result_notice_text TEXT"],
            11 => ['seat_confirm_deadline_date', "ALTER TABLE tbl_hall_seat_allocation_event ADD COLUMN seat_confirm_deadline_date DATE DEFAULT NULL"],
            12 => ['priority_list', "ALTER TABLE tbl_hall_seat_allocation_event ADD COLUMN priority_list TEXT"],
            13 => ['seat_distribution_quota', "ALTER TABLE tbl_hall_seat_allocation_event ADD COLUMN seat_distribution_quota TEXT"],
            14 => ['created', "ALTER TABLE tbl_hall_seat_allocation_event ADD COLUMN created TIMESTAMP DEFAULT CURRENT_TIMESTAMP"],
            15 => ['modified', "ALTER TABLE tbl_hall_seat_allocation_event ADD COLUMN modified TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP"],
            16 => ['semester_priority', "ALTER TABLE tbl_hall_seat_allocation_event ADD COLUMN semester_priority TEXT AFTER priority_list"],
            17 => ['scoring_factor', "ALTER TABLE tbl_hall_seat_allocation_event ADD COLUMN scoring_factor TEXT AFTER semester_priority"]
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
        // Include the new columns viva_date_list and viva_student_count in the INSERT statement.
        $sql = "INSERT INTO tbl_hall_seat_allocation_event 
                (status, title, details, application_start_date, application_end_date, viva_notice_date, viva_student_count, priority_list, semester_priority, scoring_factor, seat_distribution_quota)
                VALUES ($this->status, '$this->title', '$this->details', '$this->application_start_date', '$this->application_end_date', '$this->viva_notice_date', '$this->viva_student_count', '$this->priority_list', '$this->semester_priority', '$this->scoring_factor', '$this->seat_distribution_quota')";
        if (mysqli_query($this->conn, $sql)) {
            $this->event_id = mysqli_insert_id($this->conn);
            return $this->event_id;
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
            // Load new columns
            $this->viva_date_list = $row['viva_date_list'];
            $this->viva_student_count = $row['viva_student_count'];
            $this->seat_allotment_result_notice_date = $row['seat_allotment_result_notice_date'];
            $this->seat_allotment_result_notice_text = $row['seat_allotment_result_notice_text'];
            $this->seat_confirm_deadline_date = $row['seat_confirm_deadline_date'];
            $this->priority_list = $row['priority_list'];
            $this->semester_priority = $row['semester_priority'];
            $this->scoring_factor = $row['scoring_factor'];
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
                    viva_date_list = '$this->viva_date_list',
                    viva_student_count = '$this->viva_student_count',
                    seat_allotment_result_notice_date = '$this->seat_allotment_result_notice_date',
                    seat_allotment_result_notice_text = '$this->seat_allotment_result_notice_text',
                    seat_confirm_deadline_date = '$this->seat_confirm_deadline_date',
                    priority_list = '$this->priority_list',
                    semester_priority = '$this->semester_priority',
                    scoring_factor = '$this->scoring_factor',
                    seat_distribution_quota = '$this->seat_distribution_quota'
                WHERE event_id = $this->event_id";
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

        // Validate the event_id
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

            if (count($rows) === 1) {
                $this->setProperties($rows[0]);
            }
            return $rows;
        }
        return false;
    }

    /**
     * Get all events filtered by status and sorted by a specific column and direction.
     *
     * @param int|array $status The status value(s) to filter by.
     * @param string|null $sort_col The column name to sort the results by. For example, "event_id", "title", etc.
     * @param string|null $sort_type The sort direction; either "ASC" or "DESC". Defaults to "ASC" if not provided.
     * @return array|false Returns an array of event rows (associative arrays) if found, or false otherwise.
     */
    public function getEventsByStatus($status, $sort_col = null, $sort_type = null)
    {
        $this->ensureConnection();

        // Start building the base query.
        $sql = "SELECT * FROM tbl_hall_seat_allocation_event WHERE 1";

        // Add status filter: supports an integer or an array of integers.
        if ($status !== null) {
            if (is_array($status)) {
                $statusList = implode(',', array_map('intval', $status));
                $sql .= " AND status IN ($statusList)";
            } else {
                $sql .= " AND status = " . intval($status);
            }
        }

        // Append sorting if a sort column is provided.
        if ($sort_col !== null) {
            // Validate sort type, default to ASC if not explicitly DESC.
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

    /**
     * Set Properties of the object from an associative array.
     * 
     * * @param array $data Associative array containing property names and values.
     */
    public function setProperties($data)
    {
        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }
    }

    /**
     * Check if the application is closed based on the application end date.
     * 
     * @param string $applicationEndDate The application end date in 'Y-m-d' format.
     * @return int Returns 1 if the application is closed, 0 otherwise.
     */
    function isApplicationClosed($applicationEndDate)
    {
        // Set timezone for Asia/Dhaka.
        $tz = new DateTimeZone('Asia/Dhaka');

        // Get the current DateTime in Asia/Dhaka.
        $currentTime = new DateTime('now', $tz);

        // Create a DateTime object from the provided application end date.
        $endTime = new DateTime($applicationEndDate, $tz);

        // Calculate the time difference in seconds.
        $diffSeconds = $currentTime->getTimestamp() - $endTime->getTimestamp();

        // If more than 24 hours (86400 seconds) have passed since the application end date, return 1 (closed)
        if ($diffSeconds > 86400) {
            return 1;
        }

        // Otherwise, return 0 (still open for application)
        return 0;
    }

    /**
     * Distribute all currently available hall seats across departments
     * in proportion to each department's total‐student count, then split
     * each department’s quota evenly (with juniors favored for remainders)
     * across the 12 semesters:
     *   0 → BSc 1‑1, 1 → BSc 1‑2, …, 11 → MSc 2‑2
     *
     * @return array map: dept_id => [12‑element integer array]
     */
    public function distributeSeatsByDepartmentRatio(): array
    {
        /* ──────────────────────────────── 1. dependencies ──────────────────────────────── */
        include_once '../class-file/Department.php';
        include_once '../class-file/HallSeatDetails.php';

        /* ──────────────────────────────── 2. input data ────────────────────────────────── */
        // 2‑a  get active departments, largest student body first
        $deptModel  = new Department();
        $deptRows   = $deptModel->getDepartments(null, 1, "department_total_student", "DESC");

        // 2‑b  total seats available (status 0)
        $seatModel  = new HallSeatDetails();
        $totalSeats = $seatModel->countSeatsByStatus(0);

        if (!$deptRows || $totalSeats <= 0) return [];

        /* ──────────────────────────────── 3. proportional share ───────────────────────── */
        $totalStudents = array_sum(array_column($deptRows, 'department_total_student'));
        if ($totalStudents == 0) return [];

        $deptShare  = [];  // dept_id => floorSeats
        $fractions  = [];  // dept_id => fractional part
        $allocated  = 0;

        foreach ($deptRows as $d) {
            $stud   = (int)$d['department_total_student'];
            $raw    = ($totalSeats * $stud) / $totalStudents;  // exact quota
            $floor  = (int)floor($raw);
            $deptId = $d['department_id'];

            $deptShare[$deptId] = $floor;
            $fractions[$deptId] = $raw - $floor;
            $allocated         += $floor;
        }

        /* ──────────────────────────────── 4. distribute leftovers ─────────────────────── */
        $left = $totalSeats - $allocated;                // seats still unassigned
        arsort($fractions);                              // give extras to largest fractions
        foreach (array_keys($fractions) as $deptId) {
            if ($left-- <= 0) break;
            $deptShare[$deptId] += 1;
        }

        /* ──────────────────────────────── 5. split into 12 slots ──────────────────────── */
        $distribution = [];                              // final map
        foreach ($deptShare as $deptId => $quota) {
            $base = intdiv($quota, 12);                  // equal part for every slot
            $rem  = $quota - $base * 12;                // remainder seats
            $slots = array_fill(0, 12, $base);          // start with base share

            // Extra seats go to junior semesters first (index 0 → 11)
            for ($i = 0; $i < $rem; $i++) {
                $slots[$i]++;
            }

            $distribution[$deptId] = $slots;            // 12‑element array saved
        }

        // echo "<br>Distribution: <pre>";
        // print_r($distribution);
        // echo "</pre>";
        /* ──────────────────────────────── 6. return result ────────────────────────────── */
        return $distribution;
    }


    /**
     * Debug version of distributeSeatsByDepartmentRatio(): prints each phase in HTML tables.
     *
     * @return array map: dept_id => [12‑element integer array]
     */
    public function distributeSeatsByDepartmentRatioDebug(): array
    {
        /* ──────────────────────────────── 1. dependencies ──────────────────────────────── */
        include_once '../class-file/Department.php';
        include_once '../class-file/HallSeatDetails.php';

        /* ──────────────────────────────── 2. input data ────────────────────────────────── */
        $deptModel   = new Department();
        $deptRows    = $deptModel->getDepartments(null, 1, "department_total_student", "DESC");
        $seatModel   = new HallSeatDetails();
        $totalSeats  = $seatModel->countSeatsByStatus(0);

        echo "<h4>Phase 2: Input Data</h4>";
        echo "<table class='table table-bordered'><tr><th>Total Available Seats</th><td>{$totalSeats}</td></tr></table>";

        if (!$deptRows || $totalSeats <= 0) {
            echo "<div class='alert alert-warning'>No departments or no seats available.</div>";
            return [];
        }

        /* ──────────────────────────────── 3. proportional share ───────────────────────── */
        $totalStudents = array_sum(array_column($deptRows, 'department_total_student'));
        echo "<h4>Phase 3: Proportional Share (Total Students = {$totalStudents})</h4>";
        echo "<table class='table table-striped table-bordered'>
                <thead><tr>
                  <th>Dept ID</th><th>Dept Name</th><th>Students</th>
                  <th>Exact Quota (raw)</th><th>Floor Quota</th><th>Fractional Part</th>
                </tr></thead><tbody>";

        $deptShare = [];
        $fractions = [];
        $allocated = 0;
        foreach ($deptRows as $d) {
            $deptId = $d['department_id'];
            $name   = htmlspecialchars($d['department_name']);
            $stud   = (int)$d['department_total_student'];
            $raw    = ($totalSeats * $stud) / $totalStudents;
            $floor  = (int)floor($raw);
            $frac   = $raw - $floor;
            $deptShare[$deptId] = $floor;
            $fractions[$deptId] = $frac;
            $allocated += $floor;

            echo "<tr>
                    <td>{$deptId}</td>
                    <td>{$name}</td>
                    <td>{$stud}</td>
                    <td>" . number_format($raw, 2) . "</td>
                    <td>{$floor}</td>
                    <td>" . number_format($frac, 2) . "</td>
                  </tr>";
        }
        echo "</tbody></table>";

        /* ──────────────────────────────── 4. distribute leftovers ─────────────────────── */
        $left = $totalSeats - $allocated;
        echo "<h4>Phase 4: Distribute Leftovers</h4>";
        echo "<p>Seats allocated so far: {$allocated}. Leftover seats to assign: {$left}.</p>";
        echo "<table class='table table-bordered'><thead><tr><th>Dept ID</th><th>Fraction</th><th>Extra +1?</th></tr></thead><tbody>";
        arsort($fractions);
        foreach ($fractions as $deptId => $frac) {
            $give = ($left > 0) ? 'Yes' : 'No';
            if ($left > 0) {
                $deptShare[$deptId] += 1;
                $left--;
            }
            echo "<tr>
                    <td>{$deptId}</td>
                    <td>" . number_format($frac, 2) . "</td>
                    <td>{$give}</td>
                  </tr>";
        }
        echo "</tbody></table>";

        /* ──────────────────────────────── 5. split into 12 slots ──────────────────────── */
        echo "<h4>Phase 5: Split into 12 Semester Slots</h4>";
        $semesters = ['BSc 1‑1', 'BSc 1‑2', 'BSc 2‑1', 'BSc 2‑2', 'BSc 3‑1', 'BSc 3‑2', 'BSc 4‑1', 'BSc 4‑2', 'MSc 1‑1', 'MSc 1‑2', 'MSc 2‑1', 'MSc 2‑2'];
        $distribution = [];
        echo "<table class='table table-sm table-bordered'><thead><tr><th>Dept ID</th>";
        foreach ($semesters as $sem) {
            echo "<th>{$sem}</th>";
        }
        echo "<th>Total</th></tr></thead><tbody>";

        foreach ($deptShare as $deptId => $quota) {
            $base = intdiv($quota, 12);
            $rem  = $quota - $base * 12;
            $slots = array_fill(0, 12, $base);
            for ($i = 0; $i < $rem; $i++) {
                $slots[$i]++;
            }
            $distribution[$deptId] = $slots;

            echo "<tr><td>{$deptId}</td>";
            foreach ($slots as $num) {
                echo "<td>{$num}</td>";
            }
            echo "<td><strong>" . array_sum($slots) . "</strong></td>";
            echo "</tr>";
        }
        echo "</tbody></table>";

        /* ──────────────────────────────── 6. return result ────────────────────────────── */
        return $distribution;
    }

    /**
     * 
     * Distribute all currently available hall seats across departments
     * in proportion to each department's total‐student count, then split
     * each department’s quota evenly (with juniors favored for remainders)
     */
    public function distributeSeatsByDeptTotalMinOne(): array
    {
        // 1. Dependencies
        include_once '../class-file/Department.php';
        include_once '../class-file/HallSeatDetails.php';

        // 2. Fetch data
        $deptModel   = new Department();
        $deptRows    = $deptModel->getDepartments(null, 1, 'department_total_student', 'DESC');
        $seatModel   = new HallSeatDetails();
        $totalSeats  = $seatModel->countSeatsByStatus(0);

        if (empty($deptRows) || $totalSeats <= 0) {
            return [];
        }

        $deptCount = count($deptRows);
        $deptShare = [];

        // 3. Check if we can assign 1 seat to each department
        $assignBaseSeat = $totalSeats >= $deptCount;

        if ($assignBaseSeat) {
            // 4a. Assign 1 seat to each dept
            foreach ($deptRows as $d) {
                $deptShare[$d['department_id']] = 1;
            }

            // 5a. Distribute remaining seats proportionally
            $remainingSeats = $totalSeats - $deptCount;
            if ($remainingSeats > 0) {
                $totalStudents = array_sum(array_column($deptRows, 'department_total_student'));
                if ($totalStudents > 0) {
                    $floors = [];
                    $fractions = [];
                    $assigned = 0;

                    foreach ($deptRows as $d) {
                        $id = $d['department_id'];
                        $count = (int)$d['department_total_student'];
                        $raw = ($remainingSeats * $count) / $totalStudents;
                        $floor = (int)floor($raw);
                        $floors[$id] = $floor;
                        $fractions[$id] = $raw - $floor;
                        $assigned += $floor;
                    }

                    // Distribute remaining leftover seats
                    $leftover = $remainingSeats - $assigned;
                    if ($leftover > 0) {
                        arsort($fractions);
                        foreach (array_keys($fractions) as $id) {
                            if ($leftover-- <= 0) break;
                            $floors[$id]++;
                        }
                    }

                    foreach ($floors as $id => $add) {
                        $deptShare[$id] += $add;
                    }
                }
            }
        } else {
            // 4b. Total seats < number of departments
            // Assign 1 seat to top departments based on student count
            $i = 0;
            foreach ($deptRows as $d) {
                if ($i < $totalSeats) {
                    $deptShare[$d['department_id']] = 1;
                    $i++;
                } else {
                    $deptShare[$d['department_id']] = 0;
                }
            }
        }

        return $deptShare;
    }
}

// $hallSeatAllocationEvent = new HallSeatAllocationEvent();
// $hallSeatAllocationEvent->distributeSeatsByDepartmentRatioDebug(); // Call the debug version to see the output
?>

<!-- end of file -->