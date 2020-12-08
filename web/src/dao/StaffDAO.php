<?php
require_once(realpath(dirname(__FILE__)) . '/../util/Database.php');
require_once(realpath(dirname(__FILE__)) . '/../entity/Staff.php');

/**
 * Class StaffDAO is a data-access class for Staff, which is used to exchange
 * Staff objects with the database.
 *
 * @author saifkhichi96
 * @version 1.0
 */
class StaffDAO
{
    /**
     * Retrieves a specified Staff object from the database.
     *
     * @param $username string unique username of the Staff member to be fetched
     * @return Staff|null Staff corresponding to the specified username of null if none matched
     */
    public function get($username)
    {
        $staff = NULL;

        if ($connection = Database::getConnection()) {
            $query = "SELECT * FROM `staff` WHERE `username` LIKE '$username';";

            if ($result = mysqli_query($connection, $query)) {
                if ($row = mysqli_fetch_row($result)) {
                    $staff = new Staff();
                    $staff->setNic($row[0]);
                    $staff->setUsername($row[1]);
                    $staff->setPassword($row[2]);
                    $staff->setFirstName($row[3]);
                    $staff->setLastName($row[4]);
                    $staff->setEmail($row[5]);
                    $staff->setAdmin($row[6]);
                }
                mysqli_free_result($result);
            }

            mysqli_close($connection);
        }

        return $staff;
    }

    /**
     * Saves an Staff object to the database.
     *
     * @param $staff Staff object to be persisted
     * @return bool returns true on success, or false if object could not be inserted
     */
    public function save($staff)
    {
        if ($connection = Database::getConnection()) {
            $query = "INSERT INTO `staff` (`nic`, `username`, `password`, `firstName`, `lastName`, `email`, `isAdmin`) VALUE
                      ('" . $staff->getNic() . "', '" . $staff->getUsername() . "',  md5('" . $staff->getPassword() . "'),
                       '" . $staff->getFirstName() . "', '" . $staff->getLastName() . "', '".$staff->getEmail().
                        "', '".$staff->getAdmin()."');";
            if (mysqli_query($connection, $query)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Deletes specified Staff member from the database.
     *
     * @param $username string username of the Staff member to delete
     * @return bool true if user is deleted, false if not
     */
    public function delete($username)
    {
        if ($connection = Database::getConnection()) {
            $query = "DELETE FROM `staff` WHERE `username` LIKE '$username' OR `nic` LIKE '$username'";

            if (mysqli_query($connection, $query)) {
                return true;
            }
        }

        return false;
    }

    public function getAll()
    {
        $staffList = NULL;

        if ($connection = Database::getConnection()) {
            $query = "SELECT * FROM `staff`;";

            if ($result = mysqli_query($connection, $query)) {
                while ($row = mysqli_fetch_row($result)) {
                    $staff = new Staff();
                    $staff->setNic($row[0]);
                    $staff->setUsername($row[1]);
                    $staff->setPassword($row[2]);
                    $staff->setFirstName($row[3]);
                    $staff->setLastName($row[4]);
                    $staff->setEmail($row[5]);
                    $staff->setAdmin($row[6]);

                    $staffList[] = $staff;
                }
                mysqli_free_result($result);
            }

            mysqli_close($connection);
        }

        return $staffList;
    }
}

?>