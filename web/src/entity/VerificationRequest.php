<?php
/**
 * VerificationRequest is an entity class which reprsents a signature
 * verification request sent by a client program.
 * 
 * @author saifkhichi96
 * @version 1.0
 */
class VerificationRequest {

    /**
     * @var string customer's unique identifier
     */
    public $customerId;

    /**
     * @var SignatureImage the signature to be verified
     */
    public $questionedSignature;

}
?>