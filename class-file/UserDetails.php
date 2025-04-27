<?php
include_once 'DatabaseConnector.php'; // Ensure database connection is included

class UserDetails
{
    public $conn;
    public $db; // Holds the DatabaseConnector instance

    // Class properties
    public $details_id = 0;
    public $status = 0;
    public $user_id = 0;
    public $profile_picture_id = 0;
    public $full_name = "";
    public $student_id = 0;
    public $gender = "";
    public $contact_no = "";
    public $session = "";
    public $department_id = 0;
    public $year_semester_code = 0;
    public $last_semester_cgpa_or_merit = 0.0;
    public $district = "";
    public $division = "";
    public $permanent_address = "";
    public $present_address = "";
    public $father_name = "";
    public $father_contact_no = "";
    public $father_profession = "";
    public $father_monthly_income = 0.0;
    public $mother_name = "";
    public $mother_contact_no = "";
    public $mother_profession = "";
    public $mother_monthly_income = 0.0;
    public $guardian_name = "";
    public $guardian_contact_no = "";
    public $guardian_address = "";
    public $document_id = 0;
    public $note_ids = "";
    public $created = "";
    public $modified = "";
    public $modified_by = 0;

    /**
     * Constructor: Initializes the database connection.
     */
    public function __construct()
    {
        $this->ensureConnection();
    }

    /**
     * Ensures that a database connection is established.
     * Uses the DatabaseConnector class to connect and stores the instance in $db.
     */
    public function ensureConnection()
    {
        if (!$this->conn) {
            $this->db = new DatabaseConnector();
            $this->db->connect();
            $this->conn = $this->db->getConnection();
        }
    }

    /**
     * Disconnects the current database connection using the DatabaseConnector's disconnect method.
     */
    public function disconnect()
    {
        if ($this->db) {
            $this->db->disconnect();
        }
        $this->conn = null;
    }

    /**
     * Create tbl_user_details with only the details_id column if it does not exist.
     *
     * @return void
     */
    public function createTableMinimal()
    {
        $sql = "CREATE TABLE IF NOT EXISTS tbl_user_details (
                details_id INT AUTO_INCREMENT PRIMARY KEY
            ) ENGINE=InnoDB";
        $result = mysqli_query($this->conn, $sql);
        if ($result) {
            echo "Minimal table 'tbl_user_details' created successfully.<br>";
        } else {
            echo "Error creating minimal table 'tbl_user_details': " . mysqli_error($this->conn) . "<br>";
        }
    }

    /**
     * Alter table tbl_user_details to add additional columns.
     *
     * Each query is defined as a map entry where the key is a number and the value is an array:
     * [column name, SQL query].
     *
     * @param array|null $selectedNums Optional array of numbers. If provided, only the queries with these keys will run.
     * @return void
     */
    public function alterTableAddColumns($selectedNums = null)
    {
        $table = 'tbl_user_details';
        $alterQueries = [
            1  => ['status',                   "ALTER TABLE $table ADD COLUMN status INT DEFAULT 0"],
            2  => ['user_id',                  "ALTER TABLE $table ADD COLUMN user_id INT"],
            3  => ['profile_picture_id',       "ALTER TABLE $table ADD COLUMN profile_picture_id INT DEFAULT 0"],
            4  => ['full_name',                "ALTER TABLE $table ADD COLUMN full_name TEXT"],
            5  => ['student_id',               "ALTER TABLE $table ADD COLUMN student_id INT"],
            6  => ['gender',                   "ALTER TABLE $table ADD COLUMN gender TEXT"],
            7  => ['contact_no',               "ALTER TABLE $table ADD COLUMN contact_no TEXT"],
            8  => ['session',                  "ALTER TABLE $table ADD COLUMN session TEXT"],
            9  => ['year',                     "ALTER TABLE $table ADD COLUMN year INT DEFAULT 0"],
            10 => ['semester',                 "ALTER TABLE $table ADD COLUMN semester INT DEFAULT 0"],
            11 => ['last_semester_cgpa_or_merit', "ALTER TABLE $table ADD COLUMN last_semester_cgpa_or_merit DOUBLE DEFAULT 0.0"],
            12 => ['district',                 "ALTER TABLE $table ADD COLUMN district TEXT"],
            13 => ['division',                 "ALTER TABLE $table ADD COLUMN division TEXT"],
            14 => ['permanent_address',        "ALTER TABLE $table ADD COLUMN permanent_address TEXT"],
            15 => ['present_address',          "ALTER TABLE $table ADD COLUMN present_address TEXT"],
            16 => ['father_name',              "ALTER TABLE $table ADD COLUMN father_name TEXT"],
            17 => ['father_contact_no',        "ALTER TABLE $table ADD COLUMN father_contact_no TEXT"],
            18 => ['father_profession',        "ALTER TABLE $table ADD COLUMN father_profession TEXT"],
            19 => ['father_monthly_income',    "ALTER TABLE $table ADD COLUMN father_monthly_income DOUBLE DEFAULT 0.0"],
            20 => ['mother_name',              "ALTER TABLE $table ADD COLUMN mother_name TEXT"],
            21 => ['mother_contact_no',        "ALTER TABLE $table ADD COLUMN mother_contact_no TEXT"],
            22 => ['mother_profession',        "ALTER TABLE $table ADD COLUMN mother_profession TEXT"],
            23 => ['mother_monthly_income',    "ALTER TABLE $table ADD COLUMN mother_monthly_income DOUBLE DEFAULT 0.0"],
            24 => ['guardian_name',            "ALTER TABLE $table ADD COLUMN guardian_name TEXT"],
            25 => ['guardian_contact_no',      "ALTER TABLE $table ADD COLUMN guardian_contact_no TEXT"],
            26 => ['guardian_address',         "ALTER TABLE $table ADD COLUMN guardian_address TEXT"],
            27 => ['document_id',              "ALTER TABLE $table ADD COLUMN document_id INT DEFAULT 0"],
            28 => ['note_ids',                 "ALTER TABLE $table ADD COLUMN note_ids TEXT"],
            29 => ['created',                  "ALTER TABLE $table ADD COLUMN created TIMESTAMP DEFAULT CURRENT_TIMESTAMP"],
            30 => ['modified',                 "ALTER TABLE $table ADD COLUMN modified TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP"],
            31 => ['modified_by',              "ALTER TABLE $table ADD COLUMN modified_by INT DEFAULT 0"],
            32 => ['department_id',            "ALTER TABLE $table ADD COLUMN department_id INT DEFAULT 0 AFTER session"],
            33 => ['year_semester_code',        "ALTER TABLE $table ADD COLUMN year_semester_code INT DEFAULT 0 AFTER department_id"]
        ];

        if ($selectedNums !== null && is_array($selectedNums)) {
            $filteredQueries = [];
            foreach ($selectedNums as $num) {
                if (isset($alterQueries[$num])) {
                    $filteredQueries[$num] = $alterQueries[$num];
                }
            }
            $alterQueries = $filteredQueries;
        }

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
     * Insert new user details.
     * @return int|false Returns inserted details_id or 0 on failure.
     */
    public function insert()
    {
        $sql = "INSERT INTO tbl_user_details (
            status, user_id, profile_picture_id, full_name, student_id, gender, contact_no, session, department_id,
            year_semester_code, last_semester_cgpa_or_merit, district, division, permanent_address, present_address,
            father_name, father_contact_no, father_profession, father_monthly_income,
            mother_name, mother_contact_no, mother_profession, mother_monthly_income,
            guardian_name, guardian_contact_no, guardian_address, document_id, note_ids,
            modified_by
        ) VALUES (
            $this->status, $this->user_id, $this->profile_picture_id, '$this->full_name', $this->student_id, 
            '$this->gender', '$this->contact_no', '$this->session', $this->department_id, $this->year_semester_code,
            $this->last_semester_cgpa_or_merit, '$this->district', '$this->division', '$this->permanent_address', '$this->present_address', 
            '$this->father_name', '$this->father_contact_no', '$this->father_profession', 
            $this->father_monthly_income, '$this->mother_name', '$this->mother_contact_no', 
            '$this->mother_profession', $this->mother_monthly_income, '$this->guardian_name', 
            '$this->guardian_contact_no', '$this->guardian_address', $this->document_id, '$this->note_ids'
            , $this->modified_by
        )";

        $result = mysqli_query($this->conn, $sql);
        if ($result) {
            $this->details_id = mysqli_insert_id($this->conn);
            return $this->details_id;
        } else {
            return 0;
        }
    }

    /**
     * Update user details based on details_id.
     * @return bool Returns true if update is successful, false otherwise.
     */
    public function update()
    {
        if ($this->details_id == 0) {
            $this->disconnect();
            return 0;
        }
        $sql = "UPDATE tbl_user_details SET 
            status = $this->status,
            user_id = $this->user_id,
            profile_picture_id = $this->profile_picture_id,
            full_name = '$this->full_name',
            student_id = $this->student_id,
            gender = '$this->gender',
            contact_no = '$this->contact_no',
            session = '$this->session',
            department_id = $this->department_id,
            year_semester_code = $this->year_semester_code,
            last_semester_cgpa_or_merit = $this->last_semester_cgpa_or_merit,
            district = '$this->district',
            division = '$this->division',
            permanent_address = '$this->permanent_address',
            present_address = '$this->present_address',
            father_name = '$this->father_name',
            father_contact_no = '$this->father_contact_no',
            father_profession = '$this->father_profession',
            father_monthly_income = $this->father_monthly_income,
            mother_name = '$this->mother_name',
            mother_contact_no = '$this->mother_contact_no',
            mother_profession = '$this->mother_profession',
            mother_monthly_income = $this->mother_monthly_income,
            guardian_name = '$this->guardian_name',
            guardian_contact_no = '$this->guardian_contact_no',
            guardian_address = '$this->guardian_address',
            document_id = $this->document_id,
            note_ids = '$this->note_ids',
            modified_by = $this->modified_by
            WHERE details_id = $this->details_id";

        $result = mysqli_query($this->conn, $sql);
        return $result;
    }

    /**
     * Update the status for a given user in tbl_user_details only if the current status matches.
     *
     * @param int $user_id The ID of the user to update.
     * @param int $currentStatus The expected current status.
     * @param int $newStatus The new status value to set.
     * @return bool|string Returns true if the update is successful, otherwise returns an error message.
     */
    public function updateStatus($user_id, $currentStatus, $newStatus)
    {
        // Ensure a valid database connection is available.
        $this->ensureConnection();

        // Sanitize the inputs.
        $user_id = intval($user_id);
        $currentStatus = intval($currentStatus);
        $newStatus = intval($newStatus);

        // Prepare the SQL update query to only update if the current status matches.
        $sql = "UPDATE tbl_user_details 
            SET status = $newStatus 
            WHERE user_id = $user_id AND status = $currentStatus";

        // Execute the query.
        if (mysqli_query($this->conn, $sql)) {
            if (mysqli_affected_rows($this->conn) > 0) {
                return true;
            } else {
                return false;
                // return "No record updated. Check if the current status matches.";
            }
        } else {
            return false;
            return "Error updating status: " . mysqli_error($this->conn);
        }
    }

    /**
     * Update the status of a user details based on details_id.
     * 
     * @param int $details_id The ID of the user details to update.
     * @param int $status The new status to set.
     * @return bool Returns true if the update is successful, false otherwise.
     */
    public function updateStatusByDetailsId($details_id, $status)
    {
        $sql = "UPDATE tbl_user_details SET status = $status WHERE details_id = $details_id";
        $result = mysqli_query($this->conn, $sql);
        return $result;
    }

    /**
     * Load user details based on details_id.
     *
     * @param int $details_id The ID of the user details to load.
     * @return bool Returns true if the record is found, false otherwise.
     */
    public function getByDetailsId($details_id)
    {
        $sql = "SELECT * FROM tbl_user_details WHERE details_id = $details_id LIMIT 1";
        $result = mysqli_query($this->conn, $sql);

        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $this->setProperties($row);
            return true;
        } else {
            return false;
        }
    }


    /**
     * Load user details based on user_id, student_id, status
     *
     * @param int|array|null $user_id (Optional) Specific user_id(s) to load.
     * @param int|array|null $student_id (Optional) Specific student_id(s) to load.
     * @param int|array|null $status (Optional) Status filter (can be a number or an array of numbers).
     * @param string $sort_col (Optional) Column to sort by (allowed: "created", "modified"). Default is "created".
     * @param string $sort_type (Optional) Sort order ("ASC" or "DESC"). Default is "ASC".
     * @throws Exception If an invalid sort column is provided.
     * @return array|false Returns an array of results, or false if no match.
     */
    public function getUsers($user_id = null, $student_id = null, $status = null, $sort_col = "created", $sort_type = "ASC")
    {
        $sql = "SELECT * FROM tbl_user_details WHERE 1";

        // Condition for user_id (number or array)
        if ($user_id !== null) {
            if (is_array($user_id)) {
                $user_ids = array_map('intval', $user_id);
                $sql .= " AND user_id IN (" . implode(',', $user_ids) . ")";
            } else {
                $user_id = intval($user_id);
                $sql .= " AND user_id = $user_id";
            }
        }

        // Condition for student_id (number or array)
        if ($student_id !== null) {
            if (is_array($student_id)) {
                $student_ids = array_map('intval', $student_id);
                $sql .= " AND student_id IN (" . implode(',', $student_ids) . ")";
            } else {
                $student_id = intval($student_id);
                $sql .= " AND student_id = $student_id";
            }
        }

        // Condition for status (number or array)
        if ($status !== null) {
            if (is_array($status)) {
                $statuses = array_map('intval', $status);
                $sql .= " AND status IN (" . implode(',', $statuses) . ")";
            } else {
                $status = intval($status);
                $sql .= " AND status = $status";
            }
        }

        // Append ORDER BY clause if sort_col is provided
        if ($sort_col !== null) {
            // Allowed sort columns
            $allowed_cols = ["created", "modified"];
            if (in_array($sort_col, $allowed_cols)) {
                $sort_type = strtoupper($sort_type);
                if ($sort_type !== "ASC" && $sort_type !== "DESC") {
                    $sort_type = "ASC";
                }
                $sql .= " ORDER BY " . $sort_col . " " . $sort_type;
            } else {
                throw new Exception("Invalid sort column: $sort_col");
            }
        }

        $result = mysqli_query($this->conn, $sql);

        if ($result && mysqli_num_rows($result) > 0) {
            $data = [];
            while ($row = mysqli_fetch_assoc($result)) {
                $data[] = $row;
            }

            // If only one row is returned, update properties of the current instance.
            if (count($data) === 1) {
                $this->setProperties($data[0]);
            }
            return $data;
        }

        return false;
    }

    /**
     * Get a summary of user details by a list of details IDs.
     *
     * This function queries the database for records whose details_id is in the provided list.
     * It returns an array of associative arrays. Each associative array contains:
     * - details_id
     * - father_monthly_income
     * - division
     * - district
     * - academic_result (derived from last_semester_cgpa_or_merit)
     *
     * @param int|array $details_ids A single details_id or an array of details_ids.
     * @return array Returns an array of summary details.
     */
    public function getUserSummaryByIds($details_ids)
    {
        // Ensure the input is an array.
        if (!is_array($details_ids)) {
            $details_ids = array($details_ids);
        }

        // Sanitize the input by converting each id to an integer.
        $details_ids = array_map('intval', $details_ids);
        $ids_string = implode(',', $details_ids);

        // Build the query; alias academic_result for clarity.
        $sql = "SELECT details_id, father_monthly_income, division, district, 
                   last_semester_cgpa_or_merit
            FROM tbl_user_details 
            WHERE details_id IN ($ids_string)";

        $result = mysqli_query($this->conn, $sql);
        $data = [];

        if ($result && mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $data[] = $row;
            }
        }

        return $data;
    }


    /**
     * Check if any record exists based on user_id, student_id, and status.
     *
     * @param int|null $user_id (Optional) User ID to check. Defaults to null.
     * @param int|null $student_id (Optional) Student ID to check. Defaults to null.
     * @param int|null $status (Optional) Status filter. Defaults to null.
     * @return int Returns the details_id if a matching record exists, 0 otherwise.
     */
    public function isRecordAvailable($user_id = null, $student_id = null, $status = null)
    {
        $sql = "SELECT details_id FROM tbl_user_details WHERE 1";

        if ($user_id !== null) {
            $user_id = intval($user_id);
            $sql .= " AND user_id = $user_id";
        }

        if ($student_id !== null) {
            $student_id = intval($student_id);
            $sql .= " AND student_id = $student_id";
        }

        if ($status !== null) {
            $status = intval($status);
            $sql .= " AND status = $status";
        }

        $sql .= " LIMIT 1";

        $result = mysqli_query($this->conn, $sql);

        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            return (int)$row['details_id'];
        } else {
            return 0;
        }
    }


    /**
     * Get distinct rows based on user_id and status.
     * @param int|null $status (Optional) Status filter.
     * @return array|false Returns an array of distinct rows based on user_id, false if no match.
     */
    public function getDistinctUsersByStatus($status = null)
    {
        $sql = "SELECT * FROM tbl_user_details WHERE 1";

        if ($status !== null) {
            $sql .= " AND status = $status";
        }

        $sql .= " GROUP BY user_id"; // Ensure distinct user_id

        $result = mysqli_query($this->conn, $sql);

        if ($result && mysqli_num_rows($result) > 0) {
            $users = [];
            while ($row = mysqli_fetch_assoc($result)) {
                $users[] = $row;
            }
            if (count($users) === 1) {
                $this->setProperties($users[0]); // Set properties if only one row found
            }
            return $users;
        }

        return false;
    }

    /**
     * Set class properties based on an associative array.
     *
     * @param array $row The row data to set.
     */
    public function setProperties($row)
    {
        $this->details_id = $row['details_id'];
        $this->status = $row['status'];
        $this->user_id = $row['user_id'];
        $this->profile_picture_id = $row['profile_picture_id'];
        $this->full_name = $row['full_name'];
        $this->student_id = $row['student_id'];
        $this->gender = $row['gender'];
        $this->contact_no = $row['contact_no'];
        $this->session = $row['session'];
        $this->department_id = $row['department_id'];
        $this->year_semester_code = $row['year_semester_code'];
        $this->last_semester_cgpa_or_merit = $row['last_semester_cgpa_or_merit'];
        $this->district = $row['district'];
        $this->division = $row['division'];
        $this->permanent_address = $row['permanent_address'];
        $this->present_address = $row['present_address'];
        $this->father_name = $row['father_name'];
        $this->father_contact_no = $row['father_contact_no'];
        $this->father_profession = $row['father_profession'];
        $this->father_monthly_income = $row['father_monthly_income'];
        $this->mother_name = $row['mother_name'];
        $this->mother_contact_no = $row['mother_contact_no'];
        $this->mother_profession = $row['mother_profession'];
        $this->mother_monthly_income = $row['mother_monthly_income'];
        $this->guardian_name = $row['guardian_name'];
        $this->guardian_contact_no = $row['guardian_contact_no'];
        $this->guardian_address = $row['guardian_address'];
        $this->document_id = $row['document_id'];
        $this->note_ids = $row['note_ids'];
        $this->created = $row['created'];
        $this->modified = $row['modified'];
        $this->modified_by = $row['modified_by'];
    }

    /**
     * Get user details by joining tbl_user and tbl_user_details for a given user status, details status, and user type.
     *
     * @param int $userStatus The status for tbl_user (e.g., 1 for active).
     * @param int $detailsStatus The status for tbl_user_details (e.g., 0 for pending).
     * @param string $userType The user type to filter by (e.g., 'user', 'moderator', 'admin'). Default is 'user'.
     * @return array Returns an array of user details.
     */
    public function cutsomGetUsersDetailByStatus($userStatus, $detailsStatus, $userType = 'user')
    {
        $sql = "SELECT d.* 
            FROM tbl_user u
            JOIN tbl_user_details d ON u.user_id = d.user_id
            WHERE u.status = $userStatus
              AND d.status = $detailsStatus
              AND u.user_type = '$userType'";

        $result = mysqli_query($this->conn, $sql);
        $rows = [];

        if ($result && mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $rows[] = $row;
            }
        }

        return $rows;
    }
}


?>

<!-- end of file -->