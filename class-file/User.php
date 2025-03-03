<?php
include_once 'DatabaseConnector.php';

class User
{
    public $user_id = 0;
    public $status = 0;
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
     * This function adds the following columns:
     * - status (INT NOT NULL)
     * - email (VARCHAR(100))
     * - password (TEXT)
     * - created (TIMESTAMP DEFAULT CURRENT_TIMESTAMP)
     * - modified (TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP)
     *
     * It prints the column name and table name for each alter operation.
     *
     * @return void
     */
    public function alterTableAddColumns()
    {
        $this->ensureConnection();

        // Define an associative array mapping column names to ALTER TABLE queries.
        $alterQueries = [
            'status'   => "ALTER TABLE tbl_user ADD COLUMN status INT NOT NULL",
            'email'    => "ALTER TABLE tbl_user ADD COLUMN email VARCHAR(100)",
            'password' => "ALTER TABLE tbl_user ADD COLUMN password TEXT",
            'created'  => "ALTER TABLE tbl_user ADD COLUMN created TIMESTAMP DEFAULT CURRENT_TIMESTAMP",
            'modified' => "ALTER TABLE tbl_user ADD COLUMN modified TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP"
        ];

        // Execute each ALTER query.
        foreach ($alterQueries as $colName => $sql) {
            $result = mysqli_query($this->conn, $sql);
            if ($result) {
                echo "Column '{$colName}' added successfully to table 'tbl_user'.<br>";
            } else {
                echo "Error altering table 'tbl_user' to add column '{$colName}': " . mysqli_error($this->conn) . "<br>";
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
        $sql = "INSERT INTO tbl_user (status, email, password)
                VALUES ($this->status, '$this->email', '$this->password')";

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
                    password = '$this->password'
                WHERE user_id = $this->user_id";

        return mysqli_query($this->conn, $sql) ? true : "Error updating record: " . mysqli_error($this->conn);
    }

    /**
     * Get distinct rows based on user_id and status.
     * 
     * @param int|null $status (Optional) Status filter
     * @return array|false Returns an array of distinct rows based on user_id, false if no match
     */
    public function getDistinctUsersByStatus($status = null)
    {
        $sql = "SELECT * FROM tbl_user where 1";

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
     * @return array Returns an array where index 0 is the status value and index 1 is the status message.
     */
    public function checkUserEmailWithStatus($email, $password)
    {
        $email = mysqli_real_escape_string($this->conn, $email);

        // Check if the email exists (excluding status -2 is handled in the logic)
        $sql = "SELECT user_id, status, password FROM tbl_user WHERE email = '$email' LIMIT 1";
        $result = mysqli_query($this->conn, $sql);

        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $status = (int)$row['status'];

            // If user has status -2 (deleted), return that status and message
            if ($status == -2) {
                return ["-2", "This account has been deleted."];
            }

            // If the user is approved (status = 1), verify the password
            if ($status == 1) {
                if (password_verify($password, $row['password'])) {
                    $this->user_id = $row['user_id'];
                    $this->load();
                    return ["1", "Login successful! User ID: " . $row['user_id']];
                } else {
                    return ["10", "Wrong password. Please try again."];
                }
            }

            // If the user is unapproved (0)
            if ($status == 0) {
                return ["0", "Your account is pending approval."];
            }
            // If the user is blocked (2)
            if ($status == 2) {
                return ["2", "Your account is blocked by admin."];
            }
            // If the user registration was declined (-1)
            if ($status == -1) {
                return ["-1", "Your account registration was declined by the admin."];
            }
        }

        // If no account is found, return a default status (using -99 as a custom "not found" code)
        return ["-99", "No account found with the email '$email'."];
    }


    /**
     * Check if an email is available for registration.
     * It checks if the email exists in the database for statuses 0, 1, 2, or -1.
     *
     * @param string $email The email address to check.
     * @return bool Returns true if the email is available, false if it is already registered.
     */
    public function isEmailAvailable($email)
    {
        // Escape the email to prevent SQL injection
        $email = mysqli_real_escape_string($this->conn, $email);

        // Query to check if the email exists with status 0,1,2, or -1
        $sql = "SELECT user_id FROM tbl_user 
            WHERE email = '$email' AND status IN (0, 1, 2, -1)
            LIMIT 1";

        $result = mysqli_query($this->conn, $sql);

        // If a record exists, email is not available
        if ($result && mysqli_num_rows($result) > 0) {
            return 1;
        }

        return 0;
    }
}
?>

<!-- end -->