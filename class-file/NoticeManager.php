<?php
include_once 'DatabaseConnector.php';

class NoticeManager
{
    public $notice_id = 0;
    public $status = 1; // Default: active
    public $title = '';
    public $description = '';
    public $created;
    public $modified;
    private $conn;

    /**
     * Constructor: Initialize DB connection.
     */
    public function __construct()
    {
        $this->ensureConnection();
    }

    /**
     * Ensure a database connection is established.
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
     * Create minimal tbl_notice with only the primary key.
     */
    public function createTableMinimal()
    {
        $this->ensureConnection();
        $sql = "CREATE TABLE IF NOT EXISTS tbl_notice (
                    notice_id INT AUTO_INCREMENT PRIMARY KEY
                ) ENGINE=InnoDB";

        if (mysqli_query($this->conn, $sql)) {
            echo "Minimal table 'tbl_notice' created successfully.<br>";
        } else {
            echo "Error creating minimal table 'tbl_notice': " . mysqli_error($this->conn) . "<br>";
        }
    }

    /**
     * Alter tbl_notice to add columns. Optionally run only selected keys.
     * @param array|null $selectedNums Keys of columns to add
     */
    public function alterTableAddColumns($selectedNums = null)
    {
        $this->ensureConnection();
        $table = 'tbl_notice';

        $alterQueries = [
            1 => ['status',      "ALTER TABLE $table ADD COLUMN status INT DEFAULT 1"],
            2 => ['title',       "ALTER TABLE $table ADD COLUMN title TEXT NOT NULL"],
            3 => ['description', "ALTER TABLE $table ADD COLUMN description TEXT"],
            4 => ['created',     "ALTER TABLE $table ADD COLUMN created TIMESTAMP DEFAULT CURRENT_TIMESTAMP"],
            5 => ['modified',    "ALTER TABLE $table ADD COLUMN modified TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP"]
        ];

        if (is_array($selectedNums)) {
            $filtered = [];
            foreach ($selectedNums as $key) {
                if (isset($alterQueries[$key])) {
                    $filtered[$key] = $alterQueries[$key];
                }
            }
            $alterQueries = $filtered;
        }

        foreach ($alterQueries as $key => $info) {
            list($col, $sql) = $info;
            if (mysqli_query($this->conn, $sql)) {
                echo "Column '$col' added to '$table' (Key: $key).<br>";
            } else {
                echo "Error adding '$col' to '$table' (Key: $key): " . mysqli_error($this->conn) . "<br>";
            }
        }
    }

    /**
     * Insert a new notice record.
     * @return int|false Inserted notice_id or false
     */
    public function insert()
    {
        $this->ensureConnection();
        $title       = mysqli_real_escape_string($this->conn, $this->title);
        $description = mysqli_real_escape_string($this->conn, $this->description);

        $sql = "INSERT INTO tbl_notice (status, title, description)
                VALUES ($this->status, '$title', '$description')";

        if (mysqli_query($this->conn, $sql)) {
            $this->notice_id = mysqli_insert_id($this->conn);
            return $this->notice_id;
        }
        return false;
    }

    /**
     * Update an existing notice.
     * @return bool True on success
     */
    public function update()
    {
        $this->ensureConnection();
        $title       = mysqli_real_escape_string($this->conn, $this->title);
        $description = mysqli_real_escape_string($this->conn, $this->description);

        $sql = "UPDATE tbl_notice SET
                    status = $this->status,
                    title = '$title',
                    description = '$description'
                WHERE notice_id = $this->notice_id";

        return mysqli_query($this->conn, $sql);
    }

    /**
     * Load a notice by its ID and status.
     * 
     * @return bool True if found
     */
    public function loadByNoticeId()
    {
        $this->ensureConnection();
        $sql = "SELECT * FROM tbl_notice WHERE notice_id = $this->notice_id";
        $res = mysqli_query($this->conn, $sql);
        if ($res && mysqli_num_rows($res) == 1) {
            $row = mysqli_fetch_assoc($res);
            $this->setProperties($row);
            return true;
        }
        return false;
    }

    /**
     * Load notices filtered by status. Returns array or false.
     * @param int|null $status
     * @return array|false
     */
    public function getByStatus($status = null, $col_sort = 'created', $order = 'DESC')
    {
        $this->ensureConnection();
        $sql = 'SELECT * FROM tbl_notice';
        if ($status !== null) {
            $sql .= " WHERE status = $status";
        }
        $sql .= " ORDER BY $col_sort $order";

        $res = mysqli_query($this->conn, $sql);
        if ($res && mysqli_num_rows($res) > 0) {
            $list = [];
            while ($row = mysqli_fetch_assoc($res)) {
                $list[] = $row;
            }
            return $list;
        }
        return [];
    }
    

    /**
     * Check if a notice exists by ID and status.
     * @param int $notice_id
     * @param int $status
     * @return bool
     */
    public function isNoticeAvailable($notice_id, $status)
    {
        $this->ensureConnection();
        $sql = "SELECT notice_id FROM tbl_notice WHERE notice_id = $notice_id AND status = $status LIMIT 1";
        $res = mysqli_query($this->conn, $sql);
        return ($res && mysqli_num_rows($res) > 0);
    }

    /**
     * Set multiple properties at once.
     * @param array $data Associative array with keys: notice_id, status, title, description
     */
    public function setProperties(array $data)
    {
        if (isset($data['notice_id'])) {
            $this->notice_id = (int) $data['notice_id'];
        }
        if (isset($data['status'])) {
            $this->status = (int) $data['status'];
        }
        if (isset($data['title'])) {
            $this->title = $data['title'];
        }
        if (isset($data['description'])) {
            $this->description = $data['description'];
        }
    }
}
?>

<!-- end -->