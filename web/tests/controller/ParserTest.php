<?php
require_once(realpath(dirname(__FILE__)) . "/../../src/controller/Parser.php");
require_once(realpath(dirname(__FILE__)) . "/../../src/entity/Customer.php");
use PHPUnit\Framework\TestCase;


class ParserTest extends TestCase {

    function testCanCastValidJsonStringToObject() {
        $jsonString = '{"nic":"123","firstName":"Test","lastName":"User"}';
        $object = Parser::getTypedObject($jsonString, new Customer());

        $this->assertInstanceOf(Customer::class, $object);
        $this->assertEquals("123", $object->nic);
        $this->assertEquals("Test", $object->firstName);
        $this->assertEquals("User", $object->lastName);
    }

    function testCannotCastInvalidJsonStringToObject() {
        $this->expectException(ClassCastException::class);
        
        $jsonString = '{"NIC":"123","FirstName":"Test","LastName":"User"}';
        Parser::getTypedObject($jsonString, new Customer());
    }

    function testCanCastObjectToJsonString() {
        $object = new Customer("123", "Test", "User");
        $jsonString = Parser::getJsonData($object);
        $this->assertEquals(
            '{"nic":"123","firstName":"Test","lastName":"User"}',
            $jsonString
        );
    }

}
?>