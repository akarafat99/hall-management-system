<?php
include_once 'DatabaseConnector.php';

class FileManager
{
    public $file_id = 0;
    public $status = 1; // Default status: Active
    public $file_owner_id = 0;
    public $file_original_name = '0.jpg';
    public $file_new_name = '0.jpg';
    public $note_ids = "";
    public $created;
    public $modified;
    private $conn;

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
     * Create minimal tbl_file with only the file_id column if it does not exist.
     *
     * @return void
     */
    public function createTableMinimal()
    {
        $this->ensureConnection();
        // Use the connection from the DatabaseConnector wrapper.
        $sql = "CREATE TABLE IF NOT EXISTS tbl_file (
                file_id INT AUTO_INCREMENT PRIMARY KEY
            ) ENGINE=InnoDB";

        $result = mysqli_query($this->conn, $sql);
        if ($result) {
            echo "Minimal table 'tbl_file' created successfully.<br>";
        } else {
            echo "Error creating minimal table 'tbl_file': " . mysqli_error($this->conn) . "<br>";
        }
    }

    /**
     * Alter table tbl_file to add additional columns.
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
        $table = "tbl_file";

        // Define queries as a map: key => [column name, SQL query]
        $alterQueries = [
            1 => ['status',             "ALTER TABLE $table ADD COLUMN status INT DEFAULT 1"],
            2 => ['file_owner_id',      "ALTER TABLE $table ADD COLUMN file_owner_id INT NOT NULL"],
            3 => ['file_original_name', "ALTER TABLE $table ADD COLUMN file_original_name TEXT NOT NULL"],
            4 => ['file_new_name',      "ALTER TABLE $table ADD COLUMN file_new_name TEXT NOT NULL"],
            5 => ['note_ids',           "ALTER TABLE $table ADD COLUMN note_ids TEXT"],
            6 => ['created',            "ALTER TABLE $table ADD COLUMN created TIMESTAMP DEFAULT CURRENT_TIMESTAMP"],
            7 => ['modified',           "ALTER TABLE $table ADD COLUMN modified TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP"]
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
     * Insert a new file record.
     * @return int|false Returns inserted file_id or false on failure
     */
    public function insert()
    {
        $sql = "INSERT INTO tbl_file (status, file_owner_id, file_original_name, file_new_name, note_ids)
                VALUES ($this->status, $this->file_owner_id, '$this->file_original_name', '$this->file_new_name', '$this->note_ids')";

        $result = mysqli_query($this->conn, $sql);

        if ($result) {
            $this->file_id = mysqli_insert_id($this->conn);
            return $this->file_id;
        }

        return false;
    }

    /**
     * Update file details based on file_id.
     * Uses class properties.
     * @return bool Returns true on success, false on failure
     */
    public function update()
    {
        $sql = "UPDATE tbl_file SET 
                    status = $this->status, 
                    file_original_name = '$this->file_original_name', 
                    file_new_name = '$this->file_new_name',
                    note_ids = '$this->note_ids'
                WHERE file_id = $this->file_id";

        return mysqli_query($this->conn, $sql);
    }

    /**
     * Load file details by file_id and status (default: 1).
     * Sets the class properties accordingly.
     * @param int $file_id File ID
     * @param int $status (Optional) Status filter, default is 1
     * @return bool Returns true if file found, false otherwise
     */
    public function loadByFileId($file_id, $status = 1)
    {
        $sql = "SELECT * FROM tbl_file WHERE file_id = $file_id AND status = $status";
        $result = mysqli_query($this->conn, $sql);

        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);

            // Set class properties
            $this->file_id = $row['file_id'];
            $this->status = $row['status'];
            $this->file_owner_id = $row['file_owner_id'];
            $this->file_original_name = $row['file_original_name'];
            $this->file_new_name = $row['file_new_name'];
            $this->note_ids = $row['note_ids'];
            $this->created = $row['created'];
            $this->modified = $row['modified'];

            return 1;
        }

        return 0;
    }


    /**
     * Load files by file_owner_id and status.
     * If exactly one row is found, set the class properties accordingly (like loadById()).
     * If multiple rows are found, return an array of rows.
     *
     * @param int $file_owner_id File owner ID
     * @param int|null $status (Optional) Status filter
     * @return array|bool|int Returns 1 if one row is loaded into class properties, an array of rows if multiple are found, or false if none are found.
     */
    public function loadByOwner($file_owner_id, $status = null)
    {
        $sql = "SELECT * FROM tbl_file WHERE file_owner_id = $file_owner_id";
        if ($status !== null) {
            $sql .= " AND status = $status";
        }

        $result = mysqli_query($this->conn, $sql);

        if ($result && mysqli_num_rows($result) > 0) {
            if (mysqli_num_rows($result) == 1) {
                $row = mysqli_fetch_assoc($result);

                // Set class properties like in loadById()
                $this->file_id = $row['file_id'];
                $this->status = $row['status'];
                $this->file_owner_id = $row['file_owner_id'];
                $this->file_original_name = $row['file_original_name'];
                $this->file_new_name = $row['file_new_name'];
                $this->note_ids = $row['note_ids'];
                $this->created = $row['created'];
                $this->modified = $row['modified'];

                return 1;
            } else {
                $files = [];
                while ($row = mysqli_fetch_assoc($result)) {
                    $files[] = $row;
                }
                return $files;
            }
        }

        return 0;
    }


    /**
     * Check if a file exists by file_id and status.
     * @param int $file_id File ID
     * @param int $status Status filter
     * @return int Returns 1 if exists, 0 otherwise
     */
    public function isFileAvailable($file_id, $status)
    {
        $sql = "SELECT file_id FROM tbl_file WHERE file_id = $file_id AND status = $status LIMIT 1";
        $result = mysqli_query($this->conn, $sql);

        return ($result && mysqli_num_rows($result) > 0) ? 1 : 0;
    }

    /**
     * Generate a secure 12-character random string consisting of alphabets and digits.
     * 
     * This method uses PHP's built-in `random_bytes()` to generate cryptographically secure
     * random bytes and then encodes them using `base64_encode()`. 
     * Unwanted characters (`+`, `/`, `=`) from base64 encoding are removed to ensure the 
     * final string consists of only letters and numbers.
     *
     * @param int $length The length of the random string (default is 12).
     * @return string The generated random string.
     */
    function generateRandomString($length = 12)
    {
        // Generate 9 random bytes (since base64 encoding expands the size)
        $randomBytes = random_bytes(9);

        // Encode bytes to base64 (produces a longer string containing letters, digits, '+', '/', '=')
        $base64String = base64_encode($randomBytes);

        // Remove unwanted characters ('+', '/', '=' from base64 encoding)
        $filteredString = str_replace(['+', '/', '='], '', $base64String);

        // Ensure the final string is exactly the requested length
        return substr($filteredString, 0, $length);
    }

    /**
     * Process the uploaded file:
     * - Saves the original file name.
     * - Extracts the file extension.
     * - Generates a new random file name with the original extension.
     * - Moves the file to the "../uploads1" directory.
     *
     * @param array $file An element from the $_FILES array.
     * @return string Returns the new file name on success.
     * @throws Exception if the file is not valid or the move fails.
     */
    public function doOp($file)
    {
        // Check if the file was uploaded without errors
        if (isset($file) && $file['error'] === 0) {
            // Save the original file name with extension
            $originalFileName = $file['name'];

            // Extract the file extension (e.g., jpg, png)
            $extension = pathinfo($originalFileName, PATHINFO_EXTENSION);

            $this->file_original_name = $originalFileName;
            // Generate a new random string and append the original extension
            $newFileName = $this->generateRandomString() . $this->file_id . '.' . $extension;

            $this->file_new_name = $newFileName;

            // Set the destination path in the "../uploads1" directory
            // $destination = 'uploads1/' . $newFileName;
            $destination = str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT']) . '/uploads1/' . $newFileName;
            // echo $_SERVER['DOCUMENT_ROOT'] . "<br>";
            // echo $destination . "<br>";


            // Move the uploaded file to the destination directory
            if (move_uploaded_file($file['tmp_name'], $destination)) {
                // Return the new file name (or you can return the full path)
                return 1;
            } else {
                // Throw an exception if the file couldn't be moved
                // throw new Exception("Failed to move uploaded file.");
                return -1;
            }
        } else {
            // Throw an exception if the file is invalid or an error occurred during upload
            // throw new Exception("Invalid file or file upload error.");
            return 0;
        }
    }
}
?>

<!-- end -->