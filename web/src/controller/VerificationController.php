<?php
require_once(realpath(dirname(__FILE__)) . "/Parser.php");
require_once(realpath(dirname(__FILE__)) . "/../entity/VerificationRequest.php");
require_once(realpath(dirname(__FILE__)) . "/../entity/VerificationResponse.php");

if (isset($_POST["payload"])) {
    $payload = $_POST["payload"];
    try {
        $request = Parser::getTypedObject($payload, new VerificationRequest());
        $userExists = is_dir(realpath(dirname(__FILE__)) . "/../../../versign-core/db/users/" . $request->customerId);
        
        $response = new VerificationResponse();
        if (!$userExists) {
            $response->belongsTo = null;
        } else {
            // TODO: Fetch user details from database
            $response->belongsTo = new Customer();
            $response->belongsTo->id = $request->customerId;
            $response->belongsTo->firstName = "####";
            $response->belongsTo->lastName = "####";

            $filename = "../../../versign-core/src/app/verify_request.json";
            $file = fopen($filename, "w");
            if($file) {
                fwrite($file, $payload);
                fclose($file);
        
                $python = "/anaconda2/bin/python";
                $action = "../../../versign-core/src/app/verify.py";
                $logfile = "../../../versign-core/src/app/verify.log";
                $cmd = $python.' '.$action.' >& '.$logfile;
                system($cmd);
            }
        }

        // TODO: Read verification status from file (written by Python)
        $status = true;
        $response->genuine = $status;

        Parser::echoJsonData($response);
    }
    catch (ClassCastException $ex) {

    }
}
?>