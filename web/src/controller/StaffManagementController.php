<?php
require_once(realpath(dirname(__FILE__))."/../dao/StaffDAO.php");

/**
 * Created by PhpStorm.
 * User: saifkhichi96
 * Date: 28/05/2017
 * Time: 3:44 AM
 */
class StaffManagementController
{
    /**
     * @var StaffDAO
     */
    private $staffDao;
    private $staffMember;

    public function __construct()
    {
        $this->staffDao = new StaffDAO();
        $this->staffMember = new Staff();
    }

    public function hire()
    {
        echo "\n\tStaffManagementController::hire() ... ";

        $this->staffMember->setNic($_POST["nic"]);
        $this->staffMember->setFirstName($_POST["firtsname"]);
        $this->staffMember->setLastName($_POST["lastname"]);
        $this->staffMember->setEmail($_POST["email"]);
        $this->staffMember->setAdmin($_POST["role"]);
        $this->staffMember->setUsername($_POST["username"]);
        $this->staffMember->setPassword($_POST["password"]);

        if ($this->staffDao->save($this->staffMember)) {
            echo "SUCCESS.\n\tRedirecting now ...\n";
            header("Location: ../view/index.php");
        } else {
            echo "FAILURE.\n\tRedirecting now ...\n";
            header("Location: ../view/employee_add.php");
        }
    }

    public function fire()
    {
        echo "\n\tStaffManagementController::fire() ... ";
        if ($this->staffDao->delete($_POST["nic"])) {
            echo "SUCCESS.\n\tRedirecting now ...\n";
            header("Location: ../view/index.php?success");
        } else {
            echo "FAILURE.\n\tRedirecting now ...\n";
            header("Location: ../view/index.php?failure");
        }
    }
}