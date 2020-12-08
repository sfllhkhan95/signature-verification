<?php
require_once(realpath(dirname(__FILE__))."/../../src/util/Database.php");
use PHPUnit\Framework\TestCase;

/**
 * User: saifkhichi96
 * Date: 08/01/2018
 * Time: 10:36 PM
 */
class DatabaseTest extends TestCase {

    function testCanOpenConnection() {
        $link = Database::getConnection();
        $this->assertNotEquals($link, False);
    }

}
?>