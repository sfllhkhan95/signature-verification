<?php
/**
 * Created by PhpStorm.
 * User: saifkhichi96
 * Date: 28/05/2017
 * Time: 12:27 AM
 */
ob_start();
session_start();

require_once(realpath(dirname(__FILE__)) . "/../bo/SessionManager.php");
if (!SessionManager::getInstance()->isSessionStarted()) {
    header("Location: login.php");
    die();
}

$user = unserialize($_SESSION["user"]);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <title>Add Customer | VeriSign</title>

    <!-- Bootstrap -->
    <link href="../../vendor/twbs/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet"/>
    
    <!-- Custom Styles -->
    <link rel="stylesheet" href="styles/custom.css"/>
</head>

<body id="main_body">
<!-- Navbar -->
<nav class="navbar navbar-inverse">
    <div class="container-fluid">
        <div class="navbar-header">
            <a class="navbar-brand" href="index.php">VeriSign</a>
        </div>
        <ul class="nav navbar-nav">
            <li><a href="index.php">Employees</a></li>
            <li class="active"><a href="customers.php">Customers</a></li>
        </ul>
        <ul class="nav navbar-nav navbar-right">
            <li><a href="profile.php"><? echo "Welcome, ".$user->getFirstName(); ?>
            <li><a href="profile.php"><span class="glyphicon glyphicon-user"></span> Profile</a></li>
            <li><a href="../controller/route.php?controller=LoginController&action=logout">
                    <span class="glyphicon glyphicon-log-out"></span> Logout</a></li>
        </ul>
    </div>
</nav>
<br>
<!-- /Navbar -->

<div class="scroll container">
    <div class='x_title'>
        <h3>Create New Account<br>
            <small>Train new customer account</small>
        </h3>
        <div class='clearfix'></div>
    </div>
    <div class='form_content container'>
        <form id="form_27997" class="appnitro col-md-8 col-md-offset-2 col-sm-12" enctype="multipart/form-data"
              method="post" action="#">
            <div>
                <label class="col-sm-12 col-xs-12">Personal <sup>*</sup> </label>
                <input type="number" min="1111111111111" max="9999999999999" name="nic" placeholder="CNIC" required="required"/>
                <input type="text" name="firstname" placeholder="First name" required="required"/>
                <input type="text" name="lastname" placeholder="Last name" required="required"/>
                <input type="date" name="birthdate" placeholder="Birth date" required="required"/>
            </div>
            <div class="clearfix"></div>

            <div>
                <label class="col-sm-12 col-xs-12">Work </label>
                <input type="text" name="organization" placeholder="Organization Name"/>
                <input type="text" name="designation" placeholder="Designation"/>
            </div>
            <div class="clearfix"></div>

            <div>
                <label class="col-sm-12 col-xs-12">Signature Samples <sup>*</sup> </label>
                <input name="sign_1" class="col-sm-6 col-xs-12" type="file" required/>
                <input name="sign_2" class="col-sm-6 col-xs-12" type="file" required/>
                <input name="sign_3" class="col-sm-6 col-xs-12" type="file" required/>
                <input name="sign_4" class="col-sm-6 col-xs-12" type="file" required/>
            </div>
            <div class="clearfix"></div>

            <p>Sections marked with * are required.</p>
            <button type="submit" class="btn btn-primary btn-block btn-large">Train system</button>
        </form>
    </div>

    <script src="../../vendor/components/jquery/jquery.min.js"></script>
    <script src="../../vendor/twbs/bootstrap/dist/js/bootstrap.min.js"></script>

    <!-- Custom Theme Scripts -->
    <script src="scripts/custom.js"></script>
</body>
</html>