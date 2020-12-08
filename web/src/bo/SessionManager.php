<?php
require_once(realpath(dirname(__FILE__)) . '/../util/Database.php');
require_once(realpath(dirname(__FILE__)) . '/../dao/StaffDAO.php');

class SessionManager
{
    /**
     * @var SessionManager
     */
    private static $ourInstance;
    /**
     * @var StaffDAO
     */
    private $staffDao;
    /**
     * @var Staff
     */
    private $currentUser;

    private function __construct()
    {
        $this->staffDao = new StaffDAO();
        $this->currentUser = NULL;
    }

    public static function getInstance()
    {
        if (self::$ourInstance == null) {
            self::$ourInstance = new SessionManager();
        }

        return self::$ourInstance;
    }

    public function startSession($username, $password, $root_access=False)
    {
        $this->endSession();

        if ($this->currentUser = $this->signIn($username, $password)) {
            if ($root_access && !$this->currentUser->isAdmin()) {
                return null;
            }
            
            session_start();
            $_SESSION["user"] = serialize($this->currentUser);
        }

        return $this->currentUser;
    }

    public function endSession()
    {
        if ($this->isSessionStarted()) {
            $this->currentUser = NULL;
            unset($_SESSION["user"]);
            session_destroy();
        }
    }

    private function signIn($username, $password)
    {
        $account = $this->staffDao->get($username);
        return $account != null && $account->getPassword() == md5($password) ? $account : null;
    }

    public function isSessionStarted()
    {
        return isset($_SESSION["user"]);
    }

}


?>