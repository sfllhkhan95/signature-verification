<?php

/**
 * Database is a Singleton class which provides interface to the application
 * with a MySQL database.
 *
 * @author saifkhichi96
 */
class Database
{
    private static $ourInstance = NULL;
    private $db_name;
    private $db_host;
    private $db_user;
    private $db_pwd;

    /**
     * Database constructor. Private so that class cannot be instantiated externally.
     */
    private function __construct()
    {
        require_once(realpath(dirname(__FILE__)) . "/auth.php");
     
        $this->db_name = $DB_NAME;
        $this->db_host = $DB_HOST;
        $this->db_user = $DB_USER;
        $this->db_pwd = $DB_AUTH;
    }

    /**
     * @return bool|mysqli
     */
    public static function getConnection()
    {
        if (self::$ourInstance == NULL) {
            self::$ourInstance = new Database();
        }

        return self::$ourInstance->connect();
    }

    /**
     * Opens a connection with MySQL database.
     *
     * @return bool|mysqli connection object or False on connection failure
     */
    private function connect()
    {
        $db_conn = mysqli_connect(
            $this->db_host,
            $this->db_user,
            $this->db_pwd,
            $this->db_name);

        return mysqli_connect_errno() != 0 ? False : $db_conn;
    }
}

?>