<?php
include_once 'DatabaseConnector.php'; // Ensure database connection is included

class UserDetails
{
    public $conn;

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
    public $year = 0;
    public $semester = 0;
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

    // Create a district map using ArrayObject
    public array $district_array;

    /**
     * Constructor: Initializes the database connection.
     */
    public function __construct()
    {
        $this->createDistrictMap(); //Mandatory to create district map
        $this->ensureConnection();
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
     * Create tbl_user_details with only the details_id column if it does not exist.
     *
     * @return void
     */
    public function createTableMinimal()
    {
        $this->ensureConnection();
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
    $this->ensureConnection();

    $table = 'tbl_user_details';
    // Define queries as a map: key => [column name, SQL query]
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
        11 => ['last_semester_cgpa_or_merit',"ALTER TABLE $table ADD COLUMN last_semester_cgpa_or_merit DOUBLE DEFAULT 0.0"],
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
        30 => ['modified',                 "ALTER TABLE $table ADD COLUMN modified TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP"]
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
     * Create district map
     * @return void
     */
    public function createDistrictMap()
    {
        $this->district_array = [
            'Jashore' => 12,
            'Dhaka' => 220,
            'Faidpur' => 100,
            'Coxs Bazar' => 350
        ];
    }


    /**
     * Insert new user details
     * @return int|false Returns inserted details_id or false on failure
     */
    public function insert()
    {
        $sql = "INSERT INTO tbl_user_details (
            status, user_id, profile_picture_id, full_name, student_id, gender, contact_no, session,
            year, semester, last_semester_cgpa_or_merit, district, division, permanent_address, present_address,
            father_name, father_contact_no, father_profession, father_monthly_income,
            mother_name, mother_contact_no, mother_profession, mother_monthly_income,
            guardian_name, guardian_contact_no, guardian_address, document_id, note_ids
        ) VALUES (
            $this->status, $this->user_id, $this->profile_picture_id, '$this->full_name', $this->student_id, 
            '$this->gender', '$this->contact_no', '$this->session', $this->year, $this->semester, 
            $this->last_semester_cgpa_or_merit, '$this->district', '$this->division' ,'$this->permanent_address', '$this->present_address', 
            '$this->father_name', '$this->father_contact_no', '$this->father_profession', 
            $this->father_monthly_income, '$this->mother_name', '$this->mother_contact_no', 
            '$this->mother_profession', $this->mother_monthly_income, '$this->guardian_name', 
            '$this->guardian_contact_no', '$this->guardian_address', $this->document_id, '$this->note_ids'
        )";

        $connection = $this->conn;
        if (mysqli_query($connection, $sql)) {
            $this->details_id = mysqli_insert_id($connection);
            return $this->details_id;
        } else {
            return 0;
        }
    }

    /**
     * Update user details based on details_id
     * @return bool Returns true if update is successful, false otherwise
     */
    public function update()
    {
        if ($this->details_id == 0) return 0; // Ensure details_id is set

        $sql = "UPDATE tbl_user_details SET 
            status = $this->status,
            user_id = $this->user_id,
            profile_picture_id = $this->profile_picture_id,
            full_name = '$this->full_name',
            student_id = $this->student_id,
            gender = '$this->gender',
            contact_no = '$this->contact_no',
            session = '$this->session',
            year = $this->year,
            semester = $this->semester,
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
            note_ids = '$this->note_ids'
            WHERE details_id = $this->details_id";

        return mysqli_query($this->conn, $sql);
    }

    /**
     * Load user details based on user_id and status.
     * @param int|null $user_id (Optional) Specific user_id to load
     * @param int|null $status (Optional) Status filter
     * @return array|false Returns array of results, false if no match
     */
    public function loadByUserId($user_id = null, $status = null)
    {
        $sql = "SELECT * FROM tbl_user_details WHERE 1";

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

            if (count($data) === 1) {
                $this->setProperties($data[0]); // Set properties if only one row found
            }

            return $data;
        }

        return false;
    }



    /**
     * Load individual user details based on student_id and status.
     * @param int $student_id Student ID to search for
     * @param int|null $status (Optional) Status filter
     * @return array|false Returns an array of results, false if no match
     */
    public function loadByStudentId($student_id, $status = null)
    {
        $sql = "SELECT * FROM tbl_user_details WHERE student_id = $student_id";

        if ($status !== null) {
            $sql .= " AND status = $status";
        }

        $result = mysqli_query($this->conn, $sql);

        if ($result && mysqli_num_rows($result) > 0) {
            $data = [];
            while ($row = mysqli_fetch_assoc($result)) {
                $data[] = $row;
            }

            if (count($data) === 1) {
                $this->setProperties($data[0]); // Set properties if only one row found
            }

            return $data;
        }

        return false;
    }


    /**
     * Check if any student_id exists with the given status.
     * @param int $student_id Student ID to check
     * @param int $status Status filter
     * @return int Returns 1 if student exists, 0 otherwise
     */
    public function isUserBasedOnStatusAvailable($user_id, $status)
    {
        $sql = "SELECT * FROM tbl_user_details WHERE user_id = $user_id AND status = $status";
        $result = mysqli_query($this->conn, $sql);
        return ($result && mysqli_num_rows($result) > 0) ? 1 : 0;
    }

    /**
     * Get distinct rows based on user_id and status.
     * @param int|null $status (Optional) Status filter
     * @return array|false Returns an array of distinct rows based on user_id, false if no match
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
        $this->year = $row['year'];
        $this->semester = $row['semester'];
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
    }


    /**
     * Get user IDs from tbl_user with a given status that have a corresponding row in tbl_user_details with a given status.
     *
     * @param int $userStatus The status for tbl_user (e.g., 1 for active).
     * @param int $detailsStatus The status for tbl_user_details (e.g., 0 for pending).
     * @param string $userType The user type to filter by (e.g., 'user', 'moderator', 'admin'). Default is 'user'.
     * @return array Returns an array of user IDs.
     */
    public function cutsomGetUsersByStatus($userStatus, $detailsStatus, $userType = 'user')
    {
        // Ensure that the database connection is active
        $this->ensureConnection();

        // Build the SQL query dynamically using the provided statuses
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

<!-- end -->