<?php
/**
 * Created by PhpStorm.
 * User: saifkhichi96
 * Date: 27/05/2017
 * Time: 10:00 PM
 */
ob_start();
session_start();

require_once(realpath(dirname(__FILE__))."/../bo/SessionManager.php");
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
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Profile | VeriSign</title>
    
    <!-- Bootstrap -->
    <link href="../../vendor/twbs/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet"/>

    <!-- Font Awesome -->
    <link href="../../vendor/fortawesome/font-awesome/css/font-awesome.min.css" rel="stylesheet">

    <!-- Custom Styles -->
    <link rel="stylesheet" href="styles/profile.css"/>
    <link rel="stylesheet" href="styles/custom.css">
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
            <li><a href="customers.php">Customers</a></li>
        </ul>
        <ul class="nav navbar-nav navbar-right">
            <li><a href="profile.php"><? echo "Welcome, ".$user->getFirstName(); ?>
            <li class="active"><a href="profile.php"><span class="glyphicon glyphicon-user"></span> Profile</a></li>
            <li><a href="../controller/route.php?controller=LoginController&action=logout">
                    <span class="glyphicon glyphicon-log-out"></span> Logout</a></li>
        </ul>
    </div>
</nav>
<br>
<!-- /Navbar -->

<!-- page content -->
<div class="scroll container">
    <div class="row">

        <!-- Sidebar -->
        <div class="col-sm-3">
            <ul class="nav nav-pills nav-stacked nav-email shadow mb-20">
                <li class="active">
                    <a href="#">
                        <figure>
                            <img class="img-circle img-responsive" alt="" src="http://placehold.it/300x300">
                        </figure>
                    </a>
                </li>
            </ul><!-- /.nav -->

            <ul class="nav nav-pills nav-stacked nav-email mb-20 rounded shadow">
                <li><a href="#"><i class="fa fa-id-badge"></i><? echo strtoupper($user->getFirstName()); ?></a></li>
                <li><a href="#"><i class="fa fa-user-secret"></i><? echo strtoupper($user->getLastName()); ?></a></li>
                <li><a href="#"><i class="fa fa-key"></i><? echo "@".$user->getUsername(); ?></a></li>
                <li><a href="#"><i class="fa fa-id-card"></i><? echo $user->getNIC(); ?></a></li>
                <li><a href="#"><i class="fa fa-envelope"></i> <? echo $user->getEmail(); ?></a></li>
            </ul>
        </div>
        <!-- /Sidebar -->

        <!-- Profile Content -->
        <div class="col-sm-9">
            <div class="panel panel-default">
                <div class="bs-callout">
                    <h3>Introduction</h3>
                    <p>
                        Lorem ipsum ...
                    </p>
                </div>
                <div class="bs-callout bs-callout-danger">
                    <h4>Bank Information</h4>
                    <ul class="list-group">
                        <li class="list-group-item row">
                            <em class="col-sm-3 col-xs-3">ACCOUNT #</em>
                            <strong class="col-sm-9 col-xs-3">
                                123456789
                            </strong>
                        </li>
                        <li class="list-group-item row">
                            <em class="col-sm-3 col-xs-3">ACCOUNT TITLE</em>
                            <strong class="col-sm-9 col-xs-3">
                                ABRACADABRA
                            </strong>
                        </li>
                        <li class="list-group-item row">
                            <em class="col-sm-3 col-xs-3">BRANCH NAME</em>
                            <strong class="col-sm-9 col-xs-3">
                                DISNEYLAND
                            </strong>
                        </li>
                        <li class="list-group-item row">
                            <em class="col-sm-3 col-xs-3">DATE OPENED ON</em>
                            <strong class="col-sm-9 col-xs-3">
                                9/11
                            </strong>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <!-- /Profile Content -->
    </div>
</div>

<script src="../../vendor/components/jquery/jquery.min.js"></script>
<script src="../../vendor/twbs/bootstrap/dist/js/bootstrap.min.js"></script>

<!-- Custom Theme Scripts -->
<script src="scripts/custom.js"></script>
</body>
</html>