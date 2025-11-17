<?php
if (isset($_POST['off']) or isset($_GET['off'])) {
    @session_start();
    $_SESSION = array();
    session_destroy();
    echo '<script>window.location.replace("index.php")</script>';
} else {
    @session_start();
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="icon" href="./public/img/Brasil.jpg" type="image/x-icon">
    <?php if (isset($_SESSION['login_ok'])): ?>
        <title>SGGA</title>
    <?php else: ?>
        <title>Login</title>
    <?php endif ?>
    <!-- CSS -->
    <link rel="stylesheet" href="./public/css/estilos.css">
    <link rel="stylesheet" href="./public/css/bootstrap.min.css">
    <link rel="stylesheet" href="./public/css/alertify.css">
    <link rel="stylesheet" href="./public/css/default.css">
    <link rel="stylesheet" href="./public/css/inputmask.min.css">

    <!-- JS -->
    <script src="./public/js/jquery-3.7.1.min.js"></script>
    <script src="./public/js/jquery-1.9.1.js"></script>
    <script src="./public/js/bootstrap.bundle.min.js"></script>
    <script src="./public/js/alertify.js"></script>
    <script src="./public/js/fontawesome.js"></script>
    <script src="./public/js/inputmask.min.js"></script>
    <script src="./public/js/jquery.inputmask.bundle.js"></script>
</head>

<body id="toor">
    <?php
    if (isset($_SESSION['login_ok'])) {
        include 'navbar/navbar.php';
    }
    ?>
    <div id="principal" class="container-fluid" style="margin-top: 10px;">
        <?php
        if (isset($_SESSION['login_ok'])) {
            include 'principal.php';
        } else {
            include 'login.php';
        }
        include './views/modals/modals.php';
        ?>
    </div>

</body>

</html>