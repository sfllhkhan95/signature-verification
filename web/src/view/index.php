<?php
/**
 * Created by PhpStorm.
 * User: saifkhichi96
 * Date: 28/05/2017
 * Time: 12:32 AM
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
    <title>Employees | VeriSign</title>
    
    <!-- Bootstrap -->
    <link href="../../vendor/twbs/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet"/>

    <!-- Font Awesome -->
    <link href="../../vendor/fortawesome/font-awesome/css/font-awesome.min.css" rel="stylesheet">

    <!-- Datatables -->
    <link href="../../vendor/datatables/datatables/media/css/dataTables.bootstrap.min.css" rel="stylesheet">

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
            <li class="active"><a href="index.php">Employees</a></li>
            <li><a href="customers.php">Customers</a></li>
        </ul>
        <ul class="nav navbar-nav navbar-right">
            <li><a href="profile.php"><? echo "Welcome, " . $user->getFirstName(); ?>
            <li><a href="profile.php"><span class="glyphicon glyphicon-user"></span> Profile</a></li>
            <li><a href="../controller/route.php?controller=LoginController&action=logout">
                    <span class="glyphicon glyphicon-log-out"></span> Logout</a></li>
        </ul>
    </div>
</nav>
<br>
<!-- /Navbar -->

<!-- page content -->
<div class="scroll">
    <div class="container">
        <div class='x_panel col-sm-12 col-md-9 col-xs-12'>
            <div class='x_title'>
                <h3>Employees<br>
                    <small>Lists of employees of the bank</small>
                </h3>
                <div class='clearfix'></div>
            </div>
            <div class='x_content'>
                <table id='datatable' class='table table-striped table-bordered'>
                    <thead>
                    <tr>
                        <th>NIC</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Position</th>
                        <th>Username</th>
                        <th>Email</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $dao = new StaffDAO();
                    $employees = $dao->getAll();
                    foreach ($employees as $emp) {
                        echo "
                        <tr>
                            <td>" . $emp->getNic() . "</td>
                            <td>" . $emp->getFirstName() . "</td>
                            <td>" . $emp->getLastName() . "</td>
                            <td>" . $emp->getPosition() . "</td>
                            <td>" . $emp->getUsername() . "</td>
                            <td>" . $emp->getEmail() . "</td>
                        </tr>";
                    }
                    ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="x_panel col-sm-12 col-md-3 col-xs-12">
            <br><br><br>
            <div class='x_title'>
                <h3>Employee Management</h3>
                <div class='clearfix'></div>
            </div>
            <div class="btn-group">
                <a href="employee_add.php">
                    <button class="btn-primary">Hire Employee</button>
                </a>
                <br><br>
                <form method="post" action="../controller/route.php?controller=StaffManagementController&action=fire">
                    <input type="number" maxlength="13" minlength="13" name="nic" placeholder="Employee NIC"/>
                    <button class="btn-primary" type="submit">Fire Employee</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="../../vendor/components/jquery/jquery.min.js"></script>
<script src="../../vendor/twbs/bootstrap/dist/js/bootstrap.min.js"></script>

<!-- Datatables -->
<script src="../../vendor/datatables/datatables/media/js/jquery.dataTables.min.js"></script>
<script src="../../vendor/datatables/datatables/media/js/dataTables.bootstrap.min.js"></script>

<!-- Custom Theme Scripts -->
<script src="scripts/custom.js"></script>
</body>
</html>
