<?php
require_once(realpath(dirname(__FILE__))."/../../src/dao/StaffDAO.php");
use PHPUnit\Framework\TestCase;

/**
 * Created by PhpStorm.
 * User: saifkhichi96
 * Date: 27/05/2017
 * Time: 12:37 PM
 */
class StaffDAOTest extends TestCase
{
    function testCanCreateStaffWithValidProperties()
    {
        $staff = new Staff();
        $staff->setNic("1234597954321");
        $staff->setUsername("_tmpuser-phpunit");
        $staff->setPassword("password");
        $staff->setFirstName("Test");
        $staff->setLastName("User");
        $staff->setAdmin(true);
        $dao = new StaffDAO();
        $this->assertTrue($dao->save($staff));
    }

    function testCannotCreateStaffWithNonUniqueUsername()
    {
        $staff = new Staff();
        $staff->setNic("1234598954321");
        $staff->setUsername("_tmpuser-phpunit");
        $staff->setPassword("password");
        $staff->setFirstName("Test 2");
        $staff->setLastName("User");
        $staff->setAdmin(true);
        $dao = new StaffDAO();
        $this->assertFalse($dao->save($staff));
    }

    function testCannotCreateStaffWithNonUniqueNic()
    {
        $staff = new Staff();
        $staff->setNic("1234597954321");
        $staff->setUsername("_tmpuser-phpunit-2");
        $staff->setPassword("password");
        $staff->setFirstName("Test 3");
        $staff->setLastName("User");
        $staff->setAdmin(true);
        $dao = new StaffDAO();
        $this->assertFalse($dao->save($staff));
    }

    function testCanFetchStaffFromDatabaseWithValidUsername()
    {
        $dao = new StaffDAO();
        $staff = $dao->get("_tmpuser-phpunit");
        $this->assertNotNull($staff);

        $this->assertEquals("1234597954321", $staff->getNic());
        $this->assertEquals("_tmpuser-phpunit", $staff->getUsername());
        $this->assertEquals(md5("password"), $staff->getPassword());
        $this->assertEquals("Test", $staff->getFirstName());
        $this->assertEquals("User", $staff->getLastName());
        $this->assertTrue($staff->isAdmin());
    }

    function testCannotFetchStaffFromDatabaseWithInvalidUsername()
    {
        $dao = new StaffDAO();
        $staff = $dao->get("_tmpuser-phpunit-2");
        $this->assertNull($staff);
    }

    function testCanDeleteStaffFromDatabaase()
    {
        $dao = new StaffDAO();
        $this->assertTrue($dao->delete("_tmpuser-phpunit"));
    }
}
