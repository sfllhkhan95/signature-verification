<?php
/**
 * Created by PhpStorm.
 * User: saifkhichi96
 * Date: 27/05/2017
 * Time: 9:53 PM
 */
require_once(realpath(dirname(__FILE__)) . "/../bo/SessionManager.php");

require_once(realpath(dirname(__FILE__)) . "/Parser.php");
require_once(realpath(dirname(__FILE__)) . "/../entity/Credentials.php");
require_once(realpath(dirname(__FILE__)) . "/../dao/StaffDAO.php");

class LoginController
{
    function adminLogin() {
        $username = $_POST["username"];
        $password = $_POST["password"];

        $auth = SessionManager::getInstance();
        $auth->startSession($username, $password, True);
        if ($auth->isSessionStarted()) {
            header("Location: ../view/index.php");
        } else {
            header("Location: ../view/login.php");
        }
    }

    function login()
    {
        if (isset($_GET["payload"])) {
            $credentials = Parser::getTypedObject($_GET["payload"], new Credentials());   // Read credentials passed in payload

            if (($staff = (new StaffDAO())->get($credentials->getUsername())) != null   // If employee exists
                && strcmp($staff->getPassword(), $credentials->getPassword()) == 0      // and passwords match
            ) {
                Parser::echoJsonData($staff);
            }
        }
    }

    function logout()
    {
        SessionManager::getInstance()->endSession();
        header("Location: ../view/login.php");
    }
}