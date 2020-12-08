<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <title>Login | VeriSign</title>
    
    <!-- Bootstrap -->
    <link href="../../vendor/twbs/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet"/>

    <!-- Custom Styles -->
    <link rel="stylesheet" href="styles/custom.css">
</head>

<body>

<div class="login">
    <h1>Staff Login</h1>
    <form method="post" action="../controller/route.php?controller=LoginController&action=adminLogin">
        <input type="text" name="username" placeholder="Username" required="required"/>
        <input type="password" name="password" placeholder="Password" required="required"/>
        <button class="btn btn-primary btn-block btn-large" type="submit">Login</button>
    </form>
</div>

<script src="../../vendor/components/jquery/jquery.min.js"></script>
<script src="../../vendor/twbs/bootstrap/dist/js/bootstrap.min.js"></script>

<!-- Custom Theme Scripts -->
<script src="scripts/custom.js"></script>

</body>
</html>
