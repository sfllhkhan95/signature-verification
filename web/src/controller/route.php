<?php
/**
 * Created by PhpStorm.
 * User: saifkhichi96
 * Date: 27/05/2017
 * Time: 9:56 PM
 */
ob_start();
require_once(realpath(dirname(__FILE__))."/LoginController.php");
require_once(realpath(dirname(__FILE__))."/StaffManagementController.php");


#echo "<pre>";
#echo "Routing request ...\n";
if (isset($_GET["controller"]) && isset($_GET["action"])) {

    #echo "Locating controller ... ";
    if (class_exists($_GET["controller"])) {
        $controller = new $_GET["controller"]();

        #echo "Found\nLocating action ... ";
        if (method_exists($controller, $_GET["action"])) {
            $action = $_GET["action"];

            #echo "Found\nExecuting action ... ";
            $controller->$action();
            #echo "Done\n";
        } else {
            #echo "Not found!\n";
        }
    } else {
        #echo "Not found!\n";
    }
} else {
    #echo "No controller and/or action specified";
}
#echo "</pre>";