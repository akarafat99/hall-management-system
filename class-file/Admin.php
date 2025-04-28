<?php
include_once 'DatabaseConnector.php';

class Admin
{
    public $user_id = 0;
    public $status = 1; // You can adjust this if needed (e.g., 1 for active)
    public $email = "admin@admin";
    public $password = 'pa$$w0rd';
    public $user_type = "admin";
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
        if (!$this->conn) {
            $db = new DatabaseConnector();
            $db->connect();
            $this->conn = $db->getConnection();
        }
    }

    /**
     * Insert the admin record into tbl_user.
     * 
     * @return bool|string Returns true on success, or an error message on failure.
     */
    public function insertAdmin()
    {
        // Hash the password
        $hashedPassword = password_hash($this->password, PASSWORD_DEFAULT);

        // Prepare the SQL query
        $sql = "INSERT INTO tbl_user (status, email, password, user_type)
                VALUES ($this->status, '$this->email', '$hashedPassword', '$this->user_type')";

        $result = mysqli_query($this->conn, $sql);

        if ($result) {
            $this->user_id = mysqli_insert_id($this->conn);
            
            include_once 'UserDetails.php';
            $userDetails = new UserDetails();
            $userDetails->user_id = $this->user_id;
            $userDetails->status = 1;
            $userDetails->full_name = "Admin";
            $userDetails->insert();
            return true;
        } else {
            return false;
        }
    }

    /**
     * Insert super-admin and admin
     * 
     */
    public function createSuperAdmin()
    {
        $this->email = "super@admin";
        $this->user_type = "super-admin";
        $res = $this->insertAdmin();
        return $res;
    }
}
?>

<!-- end of the file Admin.php -->