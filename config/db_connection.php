<?php
class Db_connection
{
    private $servername = "localhost";
    private $username   = "root";
    private $password   = "root";
    private $dbname     = "sample";
    private $connection;
    private static $instance = null;

    private function __construct()
    {
        $this->connection = new mysqli(
            $this->servername,
            $this->username,
            $this->password,
            $this->dbname
        );

        if ($this->connection->connect_error) {
            throw new Exception("Database connection failed: " . $this->connection->connect_error);
        }
    }

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new Db_connection();
        }

        return self::$instance;
    }

    /**
     * Get connecton
     * @return mysqli
     */
    public function getConnection()
    {
        return $this->connection;
    }

    /**
     * Close connection
     * @return void
     */
    public function close()
    {
        if ($this->connection) {
            $this->connection->close();
        }
    }
}
