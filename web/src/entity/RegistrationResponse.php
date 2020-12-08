<?php
class RegistrationResponse {
    public $successful;

    function RegistrationResponse($successful=true){
        $this->successful = $successful;
    }
}
?>