<?php
include_once '../../models/conexion.php';
include_once '../../models/funciones.php';
include_once '../../controllers/funciones.php';

$idusuario = $_POST['idusuario'];
$usuario = $_POST['usuario'];
if (isset($_POST['passw'])) {
    $passw = password_hash($_POST['passw'], PASSWORD_DEFAULT);
}
$email = $_POST['email'];
$idrol = $_POST['idrol'];
$estado = $_POST['estado'];

if (isset($_POST['passw'])) {
    $update = CRUD("UPDATE usuarios SET usuario='$usuario', passw='$passw', email='$email', idrol='$idrol', estado='$estado' WHERE idusuario='$idusuario'", "u");
} else {
    $update = CRUD("UPDATE usuarios SET usuario = '$usuario', email='$email', idrol='$idrol', estado='$estado' WHERE idusuario='$idusuario'", "u");
}
?>
<?php if ($update): ?>
    <script>
        $(document).ready(function() {
            alertify.success("Usuario Actualizado");
            $("#contenido-principal").load("./views/usuarios/principal.php");
        });
    </script>
<?php else: ?>
    <script>
        $(document).ready(function() {
            alertify.error("Error Usuario No Actalizado");
            $("#contenido-principal").load("./views/usuarios/principal.php");
        });
    </script>
<?php endif ?>