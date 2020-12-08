<?php
require_once(realpath(dirname(__FILE__)) . '/../entity/Staff.php');
require_once(realpath(dirname(__FILE__)) . '/../dao/StaffDAO.php');

class StaffManager
{
    /**
     * @var StaffDAO
     */
    private $staffDAO;

    /**
     * EmployeeManager constructor.
     */
    public function __construct()
    {
        $this->staffDAO = new StaffDAO();
    }

}

?>