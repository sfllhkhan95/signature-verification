<?php
require_once(realpath(dirname(__FILE__)) . "/../entity/RegistrationResponse.php");

if (isset($_POST["payload"])) {
    $filename = "../../../versign-core/src/app/register_request.json";
    $file = fopen( $filename, "w" );

    if( $file ) {
        fwrite( $file,  $_POST["payload"]);
        fclose( $file );

        system("/anaconda2/bin/python ../../../versign-core/src/app/register.py >& ../../../versign-core/src/app/register.log");
    }
    
    $response = new RegistrationResponse(true);
    header("Content-type", "text/json");
    echo json_encode($response->getJsonData());
}
?>