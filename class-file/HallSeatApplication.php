<?php
include_once 'DatabaseConnector.php';

class HallSeatApplication
{
    public $application_id = 0;
    public $status = 1;
    public $event_id = 0;
    public $user_id = 0;
    public $user_details_id = 0;
    public $serial_no = 0;
    public $viva_date = null;
    public $allotted_seat_id = 0;
    public $seat_confirm_date = null;
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
     * Create table tbl_hall_seat_application with only the application_id column if it does not exist.
     *
     * @return void
     */
    public function createTableMinimal()
    {
        $sql = "CREATE TABLE IF NOT EXISTS tbl_hall_seat_application (
                    application_id INT AUTO_INCREMENT PRIMARY KEY
                ) ENGINE=InnoDB";
        $result = mysqli_query($this->conn, $sql);
        if ($result) {
            echo "Minimal table 'tbl_hall_seat_application' created successfully <br>";
        } else {
            echo "Error creating minimal table: " . mysqli_error($this->conn) . "<br>";
        }
    }

    /**
     * Alter table tbl_hall_seat_application to add additional columns.
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
            1  => ['status',           "ALTER TABLE tbl_hall_seat_application ADD COLUMN status INT NOT NULL"],
            2  => ['event_id',         "ALTER TABLE tbl_hall_seat_application ADD COLUMN event_id INT NOT NULL"],
            3  => ['user_id',          "ALTER TABLE tbl_hall_seat_application ADD COLUMN user_id INT NOT NULL"],
            4  => ['user_details_id',  "ALTER TABLE tbl_hall_seat_application ADD COLUMN user_details_id INT NOT NULL"],
            5  => ['serial_no',        "ALTER TABLE tbl_hall_seat_application ADD COLUMN serial_no INT NOT NULL"],
            6  => ['viva_date',        "ALTER TABLE tbl_hall_seat_application ADD COLUMN viva_date DATE DEFAULT NULL"],
            7  => ['allotted_seat_id', "ALTER TABLE tbl_hall_seat_application ADD COLUMN allotted_seat_id INT NOT NULL"],
            8  => ['seat_confirm_date', "ALTER TABLE tbl_hall_seat_application ADD COLUMN seat_confirm_date DATE DEFAULT NULL"],
            9 => ['created',          "ALTER TABLE tbl_hall_seat_application ADD COLUMN created TIMESTAMP DEFAULT CURRENT_TIMESTAMP"],
            10 => ['modified',         "ALTER TABLE tbl_hall_seat_application ADD COLUMN modified TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP"]
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
     * Insert a new record into tbl_hall_seat_application.
     *
     * @return bool|string Returns true if successful, otherwise an error message.
     */
    public function insert()
    {
        $sql = "INSERT INTO tbl_hall_seat_application 
                (status, event_id, user_id, user_details_id)
                VALUES ($this->status, $this->event_id, $this->user_id, $this->user_details_id)";

        if (mysqli_query($this->conn, $sql)) {
            $this->application_id = mysqli_insert_id($this->conn);
            return $this->application_id;
        } else {
            // return "Error inserting record: " . mysqli_error($this->conn);
            return false;
        }
    }

    /**
     * Load a record by application_id.
     *
     * @return bool|string Returns true if the record is found, otherwise an error message.
     */
    public function load()
    {
        $sql = "SELECT * FROM tbl_hall_seat_application WHERE application_id = $this->application_id LIMIT 1";
        $result = mysqli_query($this->conn, $sql);
        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $this->setProperties($row);
            return true;
        } else {
            return false; // No record found or error occurred.
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

        $sql = "UPDATE tbl_hall_seat_application SET
                    status = $this->status,
                    event_id = $this->event_id,
                    user_id = $this->user_id,
                    user_details_id = $this->user_details_id,
                    serial_no = $this->serial_no,
                    viva_date = '$this->viva_date',
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
        $sql = "UPDATE tbl_hall_seat_application SET status = $status WHERE application_id = $application_id";

        if (mysqli_query($this->conn, $sql)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Update viva_date for application records based on a custom distribution.
     * 
     * For each date in the $date_list, the corresponding element in $student_counts
     * indicates how many application IDs (from the $application_ids array, in order)
     * should be assigned that viva_date.
     * 
     * For example, if:
     *   $date_list = ['2025-06-01', '2025-06-02', '2025-06-03']
     *   $student_counts = [10, 15, 5]
     * then:
     *   - The first 10 application IDs are assigned viva_date = '2025-06-01',
     *   - The next 15 application IDs are assigned viva_date = '2025-06-02',
     *   - The next 5 application IDs are assigned viva_date = '2025-06-03'.
     *
     * @param array $application_ids An array of application IDs (in order).
     * @param array $date_list       An array of viva dates.
     * @param array $student_counts  An array of integers, each representing the number
     *                               of application IDs to assign for the corresponding date.
     *
     * @return bool|string Returns true if the update is successful, or an error message.
     */
    public function updateVivaDetailsByStudentCount($application_ids, $date_list, $student_counts)
    {
        // Ensure inputs are arrays.
        if (!is_array($application_ids) || !is_array($date_list) || !is_array($student_counts)) {
            return "application_ids, date_list, and student_counts must be arrays.";
        }

        // Initialize the CASE expression.
        $caseVivaDate = "CASE application_id ";
        $idList = [];

        $appIndex = 0;
        $totalApps = count($application_ids);

        // Process each date in date_list with its corresponding student count.
        for ($d = 0; $d < count($date_list); $d++) {
            // Escape the date value.
            $escapedDate = mysqli_real_escape_string($this->conn, $date_list[$d]);
            // Get the student count for this date.
            $numForThisDate = intval($student_counts[$d]);

            // Assign the next $numForThisDate application IDs to this date.
            for ($i = 0; $i < $numForThisDate; $i++) {
                if ($appIndex >= $totalApps) {
                    break 2; // Stop if no more application IDs are available.
                }
                $app_id = intval($application_ids[$appIndex]);
                $idList[] = $app_id;
                $caseVivaDate .= "WHEN $app_id THEN '$escapedDate' ";
                $appIndex++;
            }
        }

        // Complete the CASE expression so that other records remain unchanged.
        $caseVivaDate .= "ELSE viva_date END";

        // Build the WHERE clause with the processed application IDs.
        $idListStr = implode(',', $idList);

        // Construct the combined SQL update query.
        $sql = "UPDATE tbl_hall_seat_application 
            SET viva_date = $caseVivaDate 
            WHERE application_id IN ($idListStr)";

        // Execute the update query.
        if (mysqli_query($this->conn, $sql)) {
            return true;
        } else {
            return "Error updating records: " . mysqli_error($this->conn);
        }
    }


    /**
     * Update the status for all records that match the given event_id and current status.
     *
     * @param int $event_id              The event ID to match.
     * @param int $expected_current_status  The current status value that must be matched.
     * @param int $new_status            The new status value to set.
     *
     * @return bool|string Returns true if the update is successful, or an error message otherwise.
     */
    public function updateStatusForEvent($event_id, $expected_current_status, $new_status)
    {
        // Ensure the database connection is available.
        $this->ensureConnection();

        // Convert input values to integers.
        $event_id = intval($event_id);
        $expected_current_status = intval($expected_current_status);
        $new_status = intval($new_status);

        // Build the SQL query.
        $sql = "UPDATE tbl_hall_seat_application 
            SET status = $new_status 
            WHERE event_id = $event_id AND status = $expected_current_status";

        // Execute the query and return the result.
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
        $this->allotted_seat_id  = $row['allotted_seat_id'];
        $this->seat_confirm_date = $row['seat_confirm_date'];
        $this->created           = $row['created'];
        $this->modified          = $row['modified'];
    }

    /**
     * Retrieve application IDs based on event_id and status filter,
     * with optional sorting.
     *
     * @param int         $event_id  The event ID to filter records.
     * @param int|array   $status    The status filter (either a single integer or an array of integers).
     * @param string      $sortCol   The column name to sort by (default is "created").
     * @param string      $sortType  The sorting order ("ASC" or "DESC", default is "ASC").
     *
     * @return array|false Returns an array of application IDs or false if none found.
     */
    public function getApplicationIdsByEventAndStatus($event_id, $status = null, $sortCol = 'created', $sortType = 'ASC')
    {
        // Ensure a database connection is established.
        $this->ensureConnection();

        // Convert event_id to integer.
        $event_id = intval($event_id);

        // Start building the SQL query.
        $sql = "SELECT application_id FROM tbl_hall_seat_application WHERE event_id = $event_id";

        // Process status filter if provided.
        if (!is_null($status)) {
            if (is_array($status)) {
                // Convert each status to int and build an IN clause.
                $statusArr = array_map('intval', $status);
                $statusList = implode(',', $statusArr);
                $sql .= " AND status IN ($statusList)";
            } else {
                // Single status value.
                $status = intval($status);
                $sql .= " AND status = $status";
            }
        }

        // Validate and set sorting.
        if (is_null($sortCol)) {
            $sortCol = 'created';
        }

        // Normalize sort type.
        $sortType = strtoupper($sortType);
        if ($sortType !== 'ASC' && $sortType !== 'DESC') {
            $sortType = 'ASC';
        }
        $sql .= " ORDER BY $sortCol $sortType";

        // Execute the query.
        $result = mysqli_query($this->conn, $sql);

        // Return the application IDs if any records are found.
        if ($result && mysqli_num_rows($result) > 0) {
            $applicationIds = [];
            while ($row = mysqli_fetch_assoc($result)) {
                $applicationIds[] = $row['application_id'];
            }
            return $applicationIds;
        }
        return [];
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
        $sql = "SELECT * FROM tbl_hall_seat_application WHERE 1=1";

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

    /**
     * Retrieve all application records filtered by user_id, event_id, and status.
     *
     * @param int|array|null $user_id  Filter by user_id.
     * @param int|null       $event_id Filter by event_id.
     * @param int|array|null $status   Filter by status.
     * @param string         $sortCol  Column to sort by (default: 'application_id').
     * @param string         $sortType Sort order, either 'ASC' or 'DESC' (default: 'ASC').
     *
     * @return array|false Returns an array of matching records or false if none found.
     */
    public function getApplicationsByUserIdEventStatus($user_id = null, $event_id = null, $status = null, $sortCol = 'application_id', $sortType = 'ASC')
    {
        $this->ensureConnection();
        $sql = "SELECT * FROM tbl_hall_seat_application WHERE 1=1";

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

        // Filter by event_id.
        if (!is_null($event_id)) {
            $event_id = intval($event_id);
            $sql .= " AND event_id = $event_id";
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

        if (is_null($sortCol)) {
            $sortCol = 'created';
        }

        // Normalize sort order.
        $sortType = strtoupper($sortType);
        if ($sortType !== 'ASC' && $sortType !== 'DESC') {
            $sortType = 'ASC';
        }

        $sql .= " ORDER BY $sortCol $sortType";

        $result = mysqli_query($this->conn, $sql);

        if ($result && mysqli_num_rows($result) > 0) {
            $applications = [];
            while ($row = mysqli_fetch_assoc($result)) {
                $applications[] = $row;
            }
            if (count($applications) == 1) {
                $this->setProperties($applications[0]);
            }
            return $applications;
        }
        return [];
    }


    /**
     * Check if a user has already applied for a given event with an optional status filter.
     *
     * @param int       $user_id  The user ID to check.
     * @param int       $event_id The event ID to check.
     * @param int|array|null $status Optional status filter (either a single integer or an array of integers).
     *
     * @return bool Returns true if an application exists, otherwise false.
     */
    public function isAppliedByUserIdEventStatus($user_id, $event_id, $status = null)
    {
        // Ensure the database connection is established.
        $this->ensureConnection();

        // Convert user_id and event_id to integers.
        $user_id = intval($user_id);
        $event_id = intval($event_id);

        // Start building the SQL query.
        $sql = "SELECT application_id FROM tbl_hall_seat_application 
                WHERE user_id = $user_id AND event_id = $event_id";

        // If a status filter is provided, add it to the query.
        if (!is_null($status)) {
            if (is_array($status)) {
                // Convert each status value to int and build an IN clause.
                $statusArr = array_map('intval', $status);
                $statusList = implode(',', $statusArr);
                $sql .= " AND status IN ($statusList)";
            } else {
                // Single status value.
                $status = intval($status);
                $sql .= " AND status = $status";
            }
        }

        // Limit the result to a single record for efficiency.
        $sql .= " LIMIT 1";

        // Execute the query.
        $result = mysqli_query($this->conn, $sql);

        // Return true if a record is found, otherwise false.
        return ($result && mysqli_num_rows($result) > 0) ? true : false;
    }


    /**
     * Get all approved (status=2) applications for an event,
     * fetch their user_details rows, and group by department.
     *
     * @param int $event_id
     * @return array  [ department_id => [ user_details_row1, user_details_row2, … ], … ]
     */
    public function getDeptWiseUserDetailsByEvent($event_id)
    {
        $this->ensureConnection();
        $event_id = intval($event_id);

        $sql = "
      SELECT d.*, a.application_id
      FROM tbl_hall_seat_application AS a
      JOIN tbl_user_details      AS d
        ON a.user_details_id = d.details_id
      WHERE a.event_id = {$event_id}
        AND a.status   = 2
      ORDER BY d.department_id
    ";

        $res = mysqli_query($this->conn, $sql);
        $deptWise = [];

        if ($res && mysqli_num_rows($res) > 0) {
            while ($row = mysqli_fetch_assoc($res)) {
                $deptWise[$row['department_id']][] = $row;
            }
        }

        return $deptWise;
    }

    /**
     * Flatten the dept‐grouped applications into a simple map of metrics:
     *   application_id => [
     *     district,
     *     distance,
     *     academic result,
     *     father monthly income,
     *     department_id,
     *     year_semester_code,
     *     details_id
     *   ]
     *
     * @param array $deptWise  [ dept_id => [ row1, row2, … ], … ]
     * @return array           [ application_id => [...], … ]
     */
    public function mapApplicationMetrics(array $deptWise, $event_id): array
    {
        // load your division→district→distance table
        include_once 'Division.php';
        $divisions = getDivisions();

        include_once 'HallSeatAllocationEvent.php';
        $event = new HallSeatAllocationEvent();
        $event->event_id = $event_id;
        $event->load();
        $scoring_factor = array_map(
            'doubleval',
            explode(',', $event->scoring_factor)
        );


        $allApplications = [];

        foreach ($deptWise as $deptId => $applications) {
            foreach ($applications as $row) {
                $appId    = $row['application_id'];
                $userId = $row['user_id'];
                $detailsId = $row['details_id'];
                $division = $row['division'];
                $district = $row['district'];
                $distance = $divisions[$division][$district] ?? 0;
                $result   = $row['last_semester_cgpa_or_merit'];
                $income   = $row['father_monthly_income'];
                $semCode  = $row['year_semester_code'];
                $score = $scoring_factor[0] * $distance +
                    $scoring_factor[1] * $result -
                    $scoring_factor[2] * $income;
                
                if($semCode == 1 || $semCode == 9) {
                    $score = $scoring_factor[0] * $distance -
                        $scoring_factor[1] * $result -
                        $scoring_factor[2] * $income;
                }

                $allApplications[$appId] = [
                    (int)$deptId,
                    $semCode,
                    $userId,
                    (int)$detailsId,
                    $division,
                    $district,
                    $distance,
                    $result,
                    $income,
                    $score
                ];
            }
        }

        return $allApplications;
    }

    /**
     * Shortlist applications by dept, semester priority, and score
     *
     * @param array $applications  [ application_id => [deptId, semCode, detailsId, division, district, distance, result, income, score], … ]
     * @param int   $event_id
     * @return array               [ deptId => [appId1, appId2, …], … ]
     */
    public function shortlisting(array $applications, $event_id): array
    {
        include_once 'HallSeatAllocationEvent.php';
        $event = new HallSeatAllocationEvent();
        $event->event_id = intval($event_id);
        $event->load();

        // 1) Parse seat_distribution_quota
        $seatQuota = [];
        if (!empty($event->seat_distribution_quota)) {
            foreach (explode(',', $event->seat_distribution_quota) as $pair) {
                list($d, $cnt) = explode('=>', $pair);
                $seatQuota[(int)$d] = (int)$cnt;
                // echo $d . " - " . $cnt . "<br>";
            }
        }

        // 1b) Parse semester_priority
        $semesterPriority = !empty($event->semester_priority)
            ? array_map('intval', explode(',', $event->semester_priority))
            : [];

        $selectedApplications = [];
        $selectedApplicationIds = [];

        // For each department…
        foreach ($seatQuota as $deptId => $deptSeats) {
            // filter apps belonging to this dept
            $byDept = array_filter($applications, function ($m) use ($deptId) {
                return $m[0] == $deptId;
            });

            $remaining = $deptSeats;

            // foreach ($byDept as $appId => $data) {
            //     echo "Dept: {$deptId} - {$appId} - semester {$data[1]}  . <br>";
            // }
            // echo "<br>";

            // echo "------------- Department: {$deptId} <br>";

            // allocate seats in semester-priority order
            foreach ($semesterPriority as $semCode) {
                // echo "Semester: {$semCode} <br>";
                if ($remaining <= 0) break;

                // 2) select only this semester’s apps
                $bySem = array_filter($byDept, function ($m) use ($semCode) {
                    return $m[1] == $semCode;
                });

                // foreach ($bySem as $appId => $data) {
                //     echo "Pre Dept: {$deptId} - {$appId} - semester {$data[1]}  . <br>";
                // }
                // echo "<br> Sorted by score: <br>";

                if (empty($bySem)) continue;

                // 3) sort by score descending (score is at index 8)
                uasort($bySem, function ($a, $b) {
                    return $b[9] <=> $a[9];
                });

                // foreach ($bySem as $appId => $data) {
                //     echo "Post Dept: {$deptId} - {$appId} - semester {$data[1]}  score {$data[8]} . <br>";
                // }

                // 4) take up to $remaining applications
                foreach ($bySem as $appId => $_metrics) {
                    if ($remaining <= 0) break;
                    $selectedApplications[$deptId][] = $appId;
                    $selectedApplicationIds[$appId][] = 1;
                    // echo "Selected: {$deptId} - {$appId} - semester {$_metrics[1]}  . <br>";
                    $remaining--;
                }
            }
        }

        include_once 'HallSeatDetails.php';
        $hallSeatDetails = new HallSeatDetails();
        $allSeatId = $hallSeatDetails->getAllSeatIdsByEventIdAndStatus($event_id, 2);

        // Assign a randomized seat to each app ID
        // Randomize the available seat IDs
        shuffle($allSeatId);

        // Build a new map: application_id => allotted_seat_id
        $appSeatMap = [];
        $idx = 0;
        foreach (array_keys($selectedApplicationIds) as $appId) {
            if (isset($allSeatId[$idx])) {
                $appSeatMap[$appId] = $allSeatId[$idx];
                $idx++;
            } else {
                // No more seats to assign
                break;
            }
        }

        // Replace the simple presence‐map with a seat‐map
        $selectedApplicationIds = $appSeatMap;


        // foreach ($selectedApplications as $deptId => $appIds) {
        //     echo "Selected for dept {$deptId}: " . implode(', ', $appIds) . "<br>";
        // }
        // echo "<br>";
        // foreach ($selectedApplicationIds as $appId) {
        //     echo "Selected application ID: {$appId} <br>";
        // }

        return [
            'deptWise' => $selectedApplications,
            'appIds'   => $selectedApplicationIds
        ];
    }

    /**
     * Build a publish‐ready map of application → [user_id, seat, status].
     *
     * @param array $allApplication  [ application_id => [deptId, userId, detailsId, semCode, division, district, distance, result, income, score], … ]
     * @param array $submittedMap    [ application_id => allotted_seat_id, … ]
     * @return array                 [ application_id => ['user_id'=>int,'allotted_seat_id'=>int,'status'=>int], … ]
     *                                 status = 5 if allotted, 4 if not
     */
    public function buildPublishMap(array $allApplication, array $submittedMap): array
    {
        $publishMap = [];

        foreach ($allApplication as $appId => $metrics) {
            // <-- now pulling user_id from index 1 -->
            $userId = (int)$metrics[2];

            if (isset($submittedMap[$appId])) {
                $publishMap[$appId] = [
                    'user_id'          => $userId,
                    'allotted_seat_id' => (int)$submittedMap[$appId],
                    'status'           => 5,
                ];
            } else {
                $publishMap[$appId] = [
                    'user_id'          => $userId,
                    'allotted_seat_id' => 0,
                    'status'           => 4,
                ];
            }
        }

        return $publishMap;
    }

    /**
     * Bulk‐update a set of applications in one SQL statement.
     *
     * @param array $publishMap  [ application_id => ['user_id'=>…, 'allotted_seat_id'=>…, 'status'=>…], … ]
     * @return bool|string       true on success, or error message
     */
    public function bulkUpdateApplications(array $publishMap)
    {
        $this->ensureConnection();

        if (empty($publishMap)) {
            return true;
        }

        $ids = array_keys($publishMap);
        $idList = implode(',', array_map('intval', $ids));

        // Build CASE clauses for each column
        $userCase = "CASE application_id\n";
        $seatCase = "CASE application_id\n";
        $statusCase = "CASE application_id\n";

        foreach ($publishMap as $appId => $data) {
            $app  = intval($appId);
            $uid  = intval($data['user_id']);
            $sid  = intval($data['allotted_seat_id']);
            $st   = intval($data['status']);

            $userCase   .= "  WHEN {$app} THEN {$uid}\n";
            $seatCase   .= "  WHEN {$app} THEN {$sid}\n";
            $statusCase .= "  WHEN {$app} THEN {$st}\n";
        }
        $userCase   .= "  ELSE user_id END";
        $seatCase   .= "  ELSE allotted_seat_id END";
        $statusCase .= "  ELSE status END";

        $sql = "
            UPDATE tbl_hall_seat_application
            SET
                user_id = {$userCase},
                allotted_seat_id = {$seatCase},
                status = {$statusCase}
            WHERE application_id IN ({$idList})
        ";

        if (!mysqli_query($this->conn, $sql)) {
            return "Error bulk‐updating applications: " . mysqli_error($this->conn);
        }
        return true;
    }
}



?>

<!-- end -->