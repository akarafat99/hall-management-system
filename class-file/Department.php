<?php
include_once 'DatabaseConnector.php'; // Database connection handler

class Department
{
    public $conn;
    public $db; // DatabaseConnector instance

    // Table columns (in desired order)
    public $department_id = 0;
    public $status = 0;
    public $department_name = '';
    public $department_short_form = '';
    public $department_total_student = 0;
    public $created = '';
    public $modified = '';
    public $modified_by = 0;

    /**
     * Constructor: ensure DB connection
     */
    public function __construct()
    {
        $this->ensureConnection();
    }

    /**
     * Establishes a database connection if not already connected
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
     * Disconnects from the database
     */
    public function disconnect()
    {
        if ($this->db) {
            $this->db->disconnect();
        }
        $this->conn = null;
    }

    /**
     * Create minimal table with only department_id
     */
    public function createTableMinimal()
    {
        $sql = "CREATE TABLE IF NOT EXISTS tbl_department (
            department_id INT AUTO_INCREMENT PRIMARY KEY
        ) ENGINE=InnoDB";
        mysqli_query($this->conn, $sql);
        echo "Department minimal table created successfully<br>";
    }

    /**
     * Alter table to add additional columns
     * 
     * Note: This also inserts default departments if executed each time.
     * @param array|null $selectedNums if provided, only runs those keys
     */
    public function alterTableAddColumns($selectedNums = null)
    {
        $table = 'tbl_department';
        $alterQueries = [
            1 => ['status',                     "ALTER TABLE $table ADD COLUMN status INT DEFAULT 0"],
            2 => ['department_name',            "ALTER TABLE $table ADD COLUMN department_name TEXT"],
            3 => ['department_short_form',      "ALTER TABLE $table ADD COLUMN department_short_form VARCHAR(100)"],
            4 => ['department_total_student',   "ALTER TABLE $table ADD COLUMN department_total_student INT DEFAULT 0"],
            5 => ['created',                    "ALTER TABLE $table ADD COLUMN created TIMESTAMP DEFAULT CURRENT_TIMESTAMP"],
            6 => ['modified',                   "ALTER TABLE $table ADD COLUMN modified TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP"],
            7 => ['modified_by',                "ALTER TABLE $table ADD COLUMN modified_by INT DEFAULT 0"]
        ];

        if (is_array($selectedNums)) {
            $alterQueries = array_intersect_key($alterQueries, array_flip($selectedNums));
        }

        foreach ($alterQueries as $query) {
            $columnName = $query[0];
            $sql = $query[1];
            if (mysqli_query($this->conn, $sql)) {
                echo "Column '$columnName' added successfully<br>";
            } else {
                echo "Error adding column '$columnName': " . mysqli_error($this->conn) . "<br>";
            }
        }
        echo "Department table altered successfully<br>";
    }

    /**
     * Insert new department record
     * @return int inserted department_id or 0 on failure
     */
    public function insert()
    {
        $name   = mysqli_real_escape_string($this->conn, $this->department_name);
        $short  = mysqli_real_escape_string($this->conn, $this->department_short_form);
        $status = intval($this->status);
        $total  = intval($this->department_total_student);
        $by     = intval($this->modified_by);

        $sql = "INSERT INTO tbl_department (
            status,
            department_name,
            department_short_form,
            department_total_student,
            modified_by
        ) VALUES (
            $status, '$name', '$short', $total, $by
        )";

        if (mysqli_query($this->conn, $sql)) {
            $this->department_id = mysqli_insert_id($this->conn);
            return $this->department_id;
        }
        return 0;
    }

    /**
     * Batch insert default departments from getDeptMap()
     * @param int $defaultStatus = 1 means active
     * @return bool
     */
    public function insertDefaultDepartments($defaultStatus = 1)
    {
        $map = $this->getDeptMap();
        if (empty($map)) {
            return false;
        }
        $values = [];
        foreach ($map as $item) {
            $name  = mysqli_real_escape_string($this->conn, $item[0]);
            $short = mysqli_real_escape_string($this->conn, $item[1]);
            $values[] = "($defaultStatus, '$name', '$short', 0, 0)";
        }
        $sql = "INSERT INTO tbl_department 
            (status, department_name, department_short_form, department_total_student, modified_by)
            VALUES " . implode(",", $values);

        return mysqli_query($this->conn, $sql) !== false;
    }

    /**
     * Update existing department by id
     * @return bool
     */
    public function update()
    {
        if (!$this->department_id) {
            return false;
        }

        $name   = mysqli_real_escape_string($this->conn, $this->department_name);
        $short  = mysqli_real_escape_string($this->conn, $this->department_short_form);
        $status = intval($this->status);
        $total  = intval($this->department_total_student);
        $by     = intval($this->modified_by);

        $sql = "UPDATE tbl_department SET
            status = $status,
            department_name = '$name',
            department_short_form = '$short',
            department_total_student = $total,
            modified_by = $by
            WHERE department_id = $this->department_id";

        return mysqli_query($this->conn, $sql);
    }

    /**
     * Update department status by department_id
     * @param int $department_id
     * @param int $status
     * @return bool
     */
    public function updateStatusByDepartmentId($department_id, $status)
    {
        $department_id = intval($department_id);
        $status = intval($status);
        $sql = "UPDATE tbl_department SET status = $status WHERE department_id = $department_id";
        return mysqli_query($this->conn, $sql);
    }

    /**
     * Load department by id
     * @param int $id
     * @return bool
     */
    public function getById($id)
    {
        $id = intval($id);
        $sql = "SELECT * FROM tbl_department WHERE department_id = $id LIMIT 1";
        $res = mysqli_query($this->conn, $sql);
        if ($res && mysqli_num_rows($res) > 0) {
            $this->setProperties(mysqli_fetch_assoc($res));
            return true;
        }
        return false;
    }

    /**
     * Get list of departments with optional filtering and sorting
     * @param int|array|null $department_id single ID or array of IDs
     * @param int|array|null $status single status or array of statuses
     * @param string|null    $sort_col (column lists example: department_id, status, department_name, department_short_form, department_total_student, created, modified, modified_by)
     * @param string         $sort_type
     * @return array|false
     */
    public function getDepartments($department_id = null, $status = null, $sort_col = null, $sort_type = 'ASC')
    {
        $sql = "SELECT * FROM tbl_department WHERE 1";

        if ($department_id !== null) {
            if (is_array($department_id)) {
                $ids = implode(',', array_map('intval', $department_id));
                $sql .= " AND department_id IN ($ids)";
            } else {
                $sql .= " AND department_id = " . intval($department_id);
            }
        }

        if ($status !== null) {
            if (is_array($status)) {
                $stats = implode(',', array_map('intval', $status));
                $sql .= " AND status IN ($stats)";
            } else {
                $sql .= " AND status = " . intval($status);
            }
        }

        if ($sort_col == null) {
            $sort_col = 'department_id';
        }
        $sort_type = strtoupper($sort_type) === 'DESC' ? 'DESC' : 'ASC';

        $sql .= " ORDER BY $sort_col $sort_type";

        $res = mysqli_query($this->conn, $sql);
        if ($res && mysqli_num_rows($res) > 0) {
            $data = [];
            while ($row = mysqli_fetch_assoc($res)) {
                $data[] = $row;
            }
            if (count($data) == 1) {
                $this->setProperties($data[0]);
            }
            return $data;
        }
        return [];
    }

    /**
     * Set object properties from DB row
     */
    public function setProperties($row)
    {
        $this->department_id             = $row['department_id'];
        $this->status                    = $row['status'];
        $this->department_name           = $row['department_name'];
        $this->department_short_form     = $row['department_short_form'];
        $this->department_total_student  = $row['department_total_student'];
        $this->created                   = $row['created'];
        $this->modified                  = $row['modified'];
        $this->modified_by               = $row['modified_by'];
    }

    /**
     * Check if department exists by name, short form, or status
     * @param string|null $name
     * @param string|null $short
     * @param int|null    $status
     * @return int|false department_id or false
     */
    public function isRecordAvailable($name = null, $short = null, $status = null)
    {
        $conditions = [];
        if ($name !== null) {
            $conditions[] = "department_name = '" . mysqli_real_escape_string($this->conn, $name) . "'";
        }
        if ($short !== null) {
            $conditions[] = "department_short_form = '" . mysqli_real_escape_string($this->conn, $short) . "'";
        }
        if ($status !== null) {
            $conditions[] = "status = " . intval($status);
        }
        if (empty($conditions)) {
            return false;
        }

        $sql = "SELECT department_id FROM tbl_department WHERE " . implode(' AND ', $conditions) . " LIMIT 1";
        $res = mysqli_query($this->conn, $sql);
        if ($res && mysqli_num_rows($res) > 0) {
            return (int)mysqli_fetch_assoc($res)['department_id'];
        }
        return false;
    }

    /**
     * Returns a map of predefined departments
     * @return array
     */
    public function getDeptMap()
    {
        return [
            1  => ['Computer Science and Engineering', 'CSE'],
            2  => ['Industrial and Production Engineering', 'IPE'],
            3  => ['Petroleum and Mining Engineering', 'PME'],
            4  => ['Chemical Engineering', 'CHE'],
            5  => ['Electrical and Electronic Engineering', 'EEE'],
            6  => ['Biomedical Engineering', 'BME'],
            7  => ['Textile Engineering', 'TE'],
            8  => ['Microbiology', 'MB'],
            9  => ['Fisheries and Marine Bioscience', 'FMB'],
            10 => ['Genetic Engineering and Biotechnology', 'GEBT'],
            11 => ['Pharmacy', 'PHARM'],
            12 => ['Biochemistry and Molecular Biology', 'BMB'],
            13 => ['Environmental Science and Technology', 'EST'],
            14 => ['Nutrition and Food Technology', 'NFT'],
            15 => ['Food Engineering', 'FE'],
            16 => ['Climate and Disaster Management', 'CDM'],
            17 => ['Physical Education and Sports Science', 'PESS'],
            18 => ['Physiotherapy and Rehabilitation', 'PTR'],
            19 => ['Nursing and Health Science', 'NHS'],
            20 => ['English', 'ENG'],
            21 => ['Physics', 'PHY'],
            22 => ['Chemistry', 'CHEM'],
            23 => ['Mathematics', 'MATH'],
            24 => ['Applied Statistics and Data Science', 'ASDS']
        ];
    }

    /**
     * Get Semester code mapping
     * @return array
     */
    public function getYearSemesterCodes()
    {
        $semesterCode = [
            1 => 'B. Sc. 1st Year 1st Semester',
            2 => 'B. Sc. 1st Year 2nd Semester',
            3 => 'B. Sc. 2nd Year 1st Semester',
            4 => 'B. Sc. 2nd Year 2nd Semester',
            5 => 'B. Sc. 3rd Year 1st Semester',
            6 => 'B. Sc. 3rd Year 2nd Semester',
            7 => 'B. Sc. 4th Year 1st Semester',
            8 => 'B. Sc. 4th Year 2nd Semester',
            9 => 'M. Sc. 1st Year 1st Semester',
            10 => 'M. Sc. 1st Year 2nd Semester',
            11 => 'M. Sc. 2nd Year 1st Semester',
            12 => 'M. Sc. 2nd Year 2nd Semester'
        ];

        return $semesterCode;
    }
}

?>
<!-- end -->