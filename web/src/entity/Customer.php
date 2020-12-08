<?php
/**
 * Customer is an entity class which represents a person whose signatures
 * are registered with the system for verification.
 * 
 * @author saifkhichi96
 * @version 1.0
 */
class Customer {

    /**
     * @var string customer's unique identifier 
     */
    public $id;

    /**
     * @var string customer's first name
     */
    public $firstName;

    /**
     * @var string customer's last name
     */
    public $lastName;

    /**
     * Default public constructor
     * 
     * @param string $NIC customer's unique identifier
     * @param string $firstName customer's first name
     * @param string $lastName customer's last name
     */
    public function Customer($id="", $firstName="", $lastName=""){
        $this->id = $id;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
    }
}
?>