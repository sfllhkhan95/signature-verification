<?php
require_once(realpath(dirname(__FILE__)) . "/Customer.php");

class VerificationResponse {
    public $genuine;
    public $belongsTo;

    function VerificationResponse($genuine=true, $NIC="", $firstName="", $lastName=""){
        $this->genuine = $genuine;
        $this->belongsTo = new Customer($NIC, $firstName, $lastName);
    }
}
?>