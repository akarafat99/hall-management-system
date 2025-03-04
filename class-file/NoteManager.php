<?php
include_once 'DatabaseConnector.php'; // Ensure database connection is included

class NoteManager
{
    public $conn;

    // Class properties
    public $note_id = 0;
    public $status = 1;
    public $owner_id = 0;
    public $note = "";
    public $created = "";
    public $modified = "";

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
        if (!$this->conn) { // Check if connection is not set
            $db = new DatabaseConnector(); // Create DB Connection
            $db->connect();
            $this->conn = $db->getConnection();
        } else {
            return 0;
        }
    }

    /**
     * Create minimal tbl_notes with only the note_id column if it does not exist.
     *
     * @return void
     */
    public function createTableMinimal()
    {
        $this->ensureConnection();
        $sql = "CREATE TABLE IF NOT EXISTS tbl_notes (
                note_id INT AUTO_INCREMENT PRIMARY KEY
            ) ENGINE=InnoDB";

        $result = mysqli_query($this->conn, $sql);
        if ($result) {
            echo "Minimal table 'tbl_notes' created successfully.<br>";
        } else {
            echo "Error creating minimal table 'tbl_notes': " . mysqli_error($this->conn) . "<br>";
        }
    }

    /**
     * Alter table tbl_notes to add additional columns.
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
        $table = "tbl_notes";

        // Define queries as a map: key => [column name, SQL query]
        $alterQueries = [
            1 => ['status',   "ALTER TABLE $table ADD COLUMN status INT DEFAULT 1"],
            2 => ['owner_id', "ALTER TABLE $table ADD COLUMN owner_id INT"],
            3 => ['note',     "ALTER TABLE $table ADD COLUMN note TEXT"],
            4 => ['created',  "ALTER TABLE $table ADD COLUMN created TIMESTAMP DEFAULT CURRENT_TIMESTAMP"],
            5 => ['modified', "ALTER TABLE $table ADD COLUMN modified TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP"]
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
     * Insert a new note
     * @return int|false Returns inserted note_id or false on failure
     */
    public function insert()
    {
        $sql = "INSERT INTO tbl_notes (status, owner_id, note) 
                VALUES ($this->status, $this->owner_id, '$this->note')";

        $connection = $this->conn;
        if (mysqli_query($connection, $sql)) {
            $this->note_id = mysqli_insert_id($connection);
            return $this->note_id;
        } else {
            return 0;
        }
    }

    /**
     * Update note based on note_id
     * @return bool Returns true if update is successful, false otherwise
     */
    public function update()
    {
        if ($this->note_id == 0) return false; // Ensure note_id is set

        $sql = "UPDATE tbl_notes SET 
                status = $this->status,
                owner_id = $this->owner_id,
                note = '$this->note'
                WHERE note_id = $this->note_id";

        return mysqli_query($this->conn, $sql);
    }

    /**
     * Load notes by note_id and status. If exactly one row is found, set class properties.
     * @param int|null $note_id (Optional) Specific note_id to load
     * @param int|null $status (Optional) Status filter
     * @return array|false Returns an array of results or false if no match
     */
    public function loadByNoteId($note_id = null, $status = null)
    {
        $sql = "SELECT * FROM tbl_notes WHERE 1"; // Ensure valid SQL

        if ($note_id !== null) {
            $sql .= " AND note_id = $note_id";
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
                $this->setProperties($data[0]); // Set class properties if only one row is found
            }

            return $data; // Return the fetched rows
        }

        return false; // No results found
    }


    /**
     * Load notes based on owner_id and status. If exactly one row is found, set class properties.
     * @param int $owner_id Owner ID to search for
     * @param int|null $status (Optional) Status filter
     * @return array|false Returns an array of results or false if no match
     */
    public function loadByOwnerId($owner_id = null, $status = null)
    {
        $sql = "SELECT * FROM tbl_notes WHERE 1";

        if ($owner_id !== null) {
            $sql .= " AND owner_id = $owner_id";
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
                $this->setProperties($data[0]); // Set class properties if only one row is found
            }

            return $data;
        }

        return false;
    }


    /**
     * Check if any note exists for a given owner_id and status.
     * @param int $owner_id Owner ID to check
     * @param int $status Status filter
     * @return int Returns 1 if note exists, 0 otherwise
     */
    public function isNoteAvailable($owner_id, $status)
    {
        $sql = "SELECT * FROM tbl_notes WHERE owner_id = $owner_id AND status = $status";
        $result = mysqli_query($this->conn, $sql);
        return ($result && mysqli_num_rows($result) > 0) ? 1 : 0;
    }

    /**
     * Set class properties from an associative array
     * @param array $row The row data to set
     */
    private function setProperties($row)
    {
        $this->note_id = $row['note_id'];
        $this->status = $row['status'];
        $this->owner_id = $row['owner_id'];
        $this->note = $row['note'];
        $this->created = $row['created'];
        $this->modified = $row['modified'];
    }
}
?>

<!-- end -->