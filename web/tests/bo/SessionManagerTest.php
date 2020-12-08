<?php
require_once(realpath(dirname(__FILE__)) . "/../../src/bo/SessionManager.php");
require_once(realpath(dirname(__FILE__)) . '/../../src/dao/StaffDAO.php');
use PHPUnit\Framework\TestCase;

/**
 * Created by PhpStorm.
 * User: saifkhichi96
 * Date: 09/01/2018
 * Time: 1:52 AM
 */
class SessionManagerTest extends TestCase
{
    function testCanBeCreatedFromStaticMethod() {
        $this->assertInstanceOf(
            SessionManager::class,
            SessionManager::getInstance()
        );
    }

    function testCanStartSessionWithValidCredentials() {
        $staff = new Staff();
        $staff->setNic("1234597954321");
        $staff->setUsername("_tmpuser-phpunit");
        $staff->setPassword("password");
        $staff->setFirstName("Test");
        $staff->setLastName("User");
        $staff->setAdmin(true);

        $dao = new StaffDAO();
        $dao->save($staff);

        try {
            $manager = SessionManager::getInstance();
            $manager->startSession("_tmpuser-phpunit", "password");
            $this->assertTrue($manager->isSessionStarted());
        } finally  {
            $dao->delete("_tmpuser-phpunit");
        }
    }

    function testCannotStartSessionWithInvalidCredentials() {
        $manager = SessionManager::getInstance();
        $manager->startSession("_tmpuser-phpunit-2", "password-eh");
        $this->assertFalse($manager->isSessionStarted());
    }

}