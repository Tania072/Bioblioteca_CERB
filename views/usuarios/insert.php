<?php
include_once '../../models/conexion.php';
include_once '../../models/funciones.php';
include_once '../../controllers/funciones.php';

$usuario = $_POST['usuario'];
$passw = password_hash($_POST['passw'], PASSWORD_DEFAULT);
$email = $_POST['email'];
$idrol = $_POST['idrol'];


$insert = CRUD("INSERT INTO usuarios(usuario, passw, email, idrol) VALUES ('$usuario', '$passw', '$email', '$idrol')", "i");

?>
<?php if ($insert): ?>
    <script>
        $(document).ready(function() {
            alertify.success("Usuario Registrado");
            $("#contenido-principal").load("./views/usuarios/principal.php");
        });
    </script>
<?php else: ?>
    <script>
        $(document).ready(function() {
            alertify.error("Error Usuario No Registrado");
            $("#contenido-principal").load("./views/usuarios/principal.php");
        });
    </script>
<?php endif ?>