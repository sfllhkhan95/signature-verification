<?php
/**
 * Created by PhpStorm.
 * User: saifkhichi96
 * Date: 28/05/2017
 * Time: 1:27 AM
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
    <title>Add Employee | VeriSign</title>

    <!-- Bootstrap -->
    <link href="../../vendor/twbs/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet"/>
    
    <!-- Custom Styles -->
    <link rel="stylesheet" href="styles/custom.css"/>
</head>

<body>
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
        <h3>Hire Employees<br>
            <small>Create account of a new employee</small>
        </h3>
        <div class='clearfix'></div>
    </div>
    <div class='form_content container'>
        <form method="post" class="col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2"
                action="../controller/route.php?controller=StaffManagementController&action=hire">
            <div class="form-group">
                <label class="col-sm-12 col-xs-12">Personal Information <sup>*</sup></label>
                <input type="number" min="1111111111111" max="9999999999999" name="nic" placeholder="CNIC"
                       required="required"/>
                <input type="text" name="firtsname" placeholder="First name" required="required"/>
                <input type="text" name="lastname" placeholder="Last name" required="required"/>
                <input type="email" name="email" placeholder="Email address" required="required"/>
            </div>
            <div class="clearfix"></div>

            <div class="form-group">
                <label class="col-sm-12 col-xs-12">Bank Information <sup>*</sup></label>
                <input type="text" name="account" placeholder="Account # (Optional)"/>
                <label for="manager-role" class="col-sm-6 col-xs-12 btn btn-default" style="color: black">
                    Manager
                    <input id="manager-role" style="width: 20px" type="radio" name="role" value="1" required>
                </label>
                <label for="cashier-role" class="col-sm-6 col-xs-12 btn btn-default" style="color: black">
                    Cashier
                    <input id="cashier-role" style="width: 20px" type="radio" name="role" value="0" checked required>
                </label>
            </div>
            <div class="clearfix"></div><br>

            <div class="form-group">
                <label class="col-sm-12 col-xs-12">Login Credentials <sup>*</sup></label>
                <input type="text" name="username" placeholder="Username" required="required"/>
                <input type="password" name="password" placeholder="Password" required="required"/>
            </div>
            <div class="clearfix"></div>

            <p>Sections marked with * are required.</p>
            <button type="submit" class="btn btn-primary btn-block btn-large">Create account</button>
            <br>
        </form>
    </div>

    <script src="../../vendor/components/jquery/jquery.min.js"></script>
    <script src="../../vendor/twbs/bootstrap/dist/js/bootstrap.min.js"></script>

    <!-- Custom Theme Scripts -->
    <script src="scripts/custom.js"></script>
</body>
</html>
