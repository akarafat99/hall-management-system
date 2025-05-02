<?php
class DatabaseConnector {
    public $servername;
    public $username;
    public $password;
    public $dbname;
    public $connection;
    public $error;

    public function __construct($servername = 'localhost', $username = 'root', $password = '', $dbname = 'justhall') {
        $this->servername = $servername;
        $this->username = $username;
        $this->password = $password;
        $this->dbname = $dbname;
        $this->error = '';
        // $this->connect();
    }

    /**
     * Create the database if it does not exist
     */
    public function createDatabase() {
        $conn = new mysqli($this->servername, $this->username, $this->password);
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $sql = "CREATE DATABASE IF NOT EXISTS `" . $this->dbname . "`";
        if ($conn->query($sql) !== TRUE) {
            die("Error creating database: " . $conn->error . "<br>");
        }
        // echo "************* Database created successfully <br>";
        $conn->close();
    }

    /**
     * Establish database connection
     * @return bool Returns true on success, false on failure
     */
    public function connect() {
        $this->connection = mysqli_connect(
            $this->servername,
            $this->username,
            $this->password,
            $this->dbname
        );

        if (!$this->connection) {
            $this->error = mysqli_connect_error();
            return false;
        }

        return true;
    }

    /**
     * Close database connection
     */
    public function disconnect() {
        if ($this->connection) {
            mysqli_close($this->connection);
            $this->connection = null;
        }
    }

    /**
     * Get the database connection object
     * @return mysqli|null Returns the connection resource or null
     */
    public function getConnection() {
        return $this->connection;
    }
}
?>
