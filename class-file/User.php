<?php
include_once 'DatabaseConnector.php';

class User
{
    public $user_id = 0;
    public $status = 0;
    public $user_type = "user";
    public $email = "";
    public $password = "";
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
        if (!$this->conn) { // Check if connection is not set
            $db = new DatabaseConnector(); // Create DB Connection
            $db->connect();
            $this->conn = $db->getConnection();
        } else {
            return 0;
        }
    }

    /**
     * Create table tbl_user with only the user_id column if it does not exist.
     *
     * @return void
     */
    public function createTableMinimal()
    {
        $this->ensureConnection();

        $sql = "CREATE TABLE IF NOT EXISTS tbl_user (
                user_id INT AUTO_INCREMENT PRIMARY KEY
            ) ENGINE=InnoDB";

        $result = mysqli_query($this->conn, $sql);
        if ($result) {
            echo "Minimal table 'tbl_user' created successfully <br>";
        } else {
            echo "Error creating minimal table 'tbl_user': " . mysqli_error($this->conn) . "<br>";
        }
    }

    /**
     * Alter table tbl_user to add additional columns.
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

        // Define queries as a map: key => [column name, SQL query]
        $alterQueries = [
            1 => ['status',    "ALTER TABLE tbl_user ADD COLUMN status INT NOT NULL"],
            2 => ['email',     "ALTER TABLE tbl_user ADD COLUMN email VARCHAR(100)"],
            3 => ['password',  "ALTER TABLE tbl_user ADD COLUMN password TEXT"],
            4 => ['user_type', "ALTER TABLE tbl_user ADD COLUMN user_type VARCHAR(100) DEFAULT 'user'"],
            5 => ['created',   "ALTER TABLE tbl_user ADD COLUMN created TIMESTAMP DEFAULT CURRENT_TIMESTAMP"],
            6 => ['modified',  "ALTER TABLE tbl_user ADD COLUMN modified TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP"]
        ];

        // If a subset of queries is provided, filter the map.
        if ($selectedNums !== null && is_array($selectedNums)) {
            // Build a new map containing only selected keys.
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
            // echo "Adding column (Key: {$num})...'{$colName}'<br>";
            $result = mysqli_query($this->conn, $sql);
            if ($result) {
                echo "Column '{$colName}' added successfully (Key: {$num}).<br>";
            } else {
                echo "Error adding column '{$colName}' (Key: {$num}): " . mysqli_error($this->conn) . "<br>";
            }
        }
    }



    /**
     * Insert a new user record.
     * 
     * @return bool|string Returns true if insertion is successful, otherwise returns an error message.
     */
    public function insert()
    {
        $this->ensureConnection();
        $this->password = password_hash($this->password, PASSWORD_DEFAULT); // Hash the password

        // echo "hello 1 <br>";
        $sql = "INSERT INTO tbl_user (status, email, password, user_type)
                VALUES ($this->status, '$this->email', '$this->password', '$this->user_type')";

        if (mysqli_query($this->conn, $sql)) {
            // echo "hello 2 <br>";
            $this->user_id = mysqli_insert_id($this->conn);
            return 1;
        } else {
            // return "Error inserting record: " . mysqli_error($this->conn);
            return 0;
        }
    }

    /**
     * Load a user record by user_id.
     * 
     * @return bool|string Returns true if user is found, otherwise returns an error message.
     */
    public function load()
    {
        $user_id = $this->user_id;
        $sql = "SELECT * FROM tbl_user WHERE user_id = $user_id LIMIT 1";
        $result = mysqli_query($this->conn, $sql);

        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $this->status = $row['status'];
            $this->email = $row['email'];
            $this->password = $row['password'];
            $this->user_type = $row['user_type'];
            $this->created = $row['created'];
            $this->modified = $row['modified'];
            return true;
        } else {
            return "No record found with user_id: $user_id";
        }
    }

    /**
     * Update user record by user_id.
     * 
     * @return bool|string Returns true if update is successful, otherwise returns an error message.
     */
    public function update()
    {
        if (!$this->user_id) {
            return "User ID not set. Cannot update record.";
        }

        $sql = "UPDATE tbl_user SET 
                    status = $this->status,
                    email = '$this->email',
                    password = '$this->password',
                    user_type = '$this->user_type'
                WHERE user_id = $this->user_id";

        return mysqli_query($this->conn, $sql) ? true : "Error updating record: " . mysqli_error($this->conn);
    }

    /**
     * Get distinct rows based on user_id and status.
     * 
     * @param int|null $status (Optional) Status filter
     * @param string $user_type (Optional) User type filter (Default is "user")
     * @return array|false Returns an array of distinct rows based on user_id, false if no match
     */
    public function getDistinctUsersByStatus($status = null, $user_type = "user")
    {
        $sql = "SELECT * FROM tbl_user where user_type = '$user_type'";

        if (!is_null($status)) {
            $sql .= " AND status = $status";
        }

        $result = mysqli_query($this->conn, $sql);

        if ($result && mysqli_num_rows($result) > 0) {
            $users = [];
            while ($row = mysqli_fetch_assoc($result)) {
                $users[] = $row;
            }
            return $users;
        }
        return false;
    }

    /**
     * Check if user email exists and validate status.
     * 
     * @param string $email User email
     * @param string $password User password
     * @param string $user_type User type (default is "user")
     * @return array Returns an array where index 0 is the status value and index 1 is the status message.
     */
    public function checkUserEmailWithStatus($email, $password, $user_type = null)
    {
        $email = mysqli_real_escape_string($this->conn, $email);

        // Query to fetch all users with the given email and user_type
        $sql = "SELECT * FROM tbl_user WHERE email = '$email'";
        if ($user_type !== null) {
            $sql .= " AND user_type = '$user_type'";
        }
        $result = mysqli_query($this->conn, $sql);

        if ($result && mysqli_num_rows($result) > 0) {
            // Fetch all rows as an associative array
            $rows = mysqli_fetch_all($result, MYSQLI_ASSOC);

            // Initialize variables to track status
            $hasDeleted = false;
            $hasPendingApproval = false;
            $hasBlocked = false;
            $hasDeclined = false;

            // Iterate through each row to check statuses
            foreach ($rows as $row) {
                $status = (int)$row['status'];

                // Check for deleted status (-2)
                if ($status == -2) {
                    $hasDeleted = true;
                    continue;
                }

                // Check for approved status (1)
                if ($status == 1) {
                    if (password_verify($password, $row['password'])) {
                        $this->user_id = $row['user_id'];
                        $this->load();
                        return ["1", "Login successful! User ID: " . $row['user_id']];
                    } else {
                        return ["10", "Wrong password. Please try again."];
                    }
                }

                // Check for pending approval status (0)
                if ($status == 0) {
                    $hasPendingApproval = true;
                    return ["0", "Your account is pending approval."];
                }

                // Check for blocked status (2)
                if ($status == 2) {
                    $hasBlocked = true;
                    return ["2", "Your account is blocked by admin."];
                }

                // Check for declined status (-1)
                if ($status == -1) {
                    $hasDeclined = true;
                    $this->user_id = $row['user_id'];
                    $this->status = -2;
                    $this->update();
                    return ["-1", "Your account registration was declined by the admin. Please register again using <b>this email<b> or other email account."];
                }
            }
        }

        // If no account is found, return a default status
        return ["-99", "No account found with the email '$email'."];
    }



    /**
     * Check if an email is available for registration.
     * It checks if the email exists in the database for statuses 0, 1, 2, or -1.
     *
     * @param string $email The email address to check.
     * @param string|null $user_type (Optional) The user type to filter by (Default = "user").
     * @return bool Returns true if the email is available, false if it is already registered.
     */
    public function isEmailAvailable($email, $user_type = null)
    {
        // Escape the email to prevent SQL injection
        $email = mysqli_real_escape_string($this->conn, $email);

        // Query to check if the email exists with status 0,1,2, or -1
        $sql = "SELECT user_id FROM tbl_user 
            WHERE email = '$email' AND status IN (0, 1, 2, -1)";

        if ($user_type !== null) {
            $sql .= " AND user_type = '$user_type'";
        }

        $result = mysqli_query($this->conn, $sql);

        // If a record exists, email is not available
        if ($result && mysqli_num_rows($result) > 0) {
            return 1;
        }

        return 0;
    }
}

// $user = new User();
// $user->alterTableAddColumns([1, 4]);
?>

<!-- end -->