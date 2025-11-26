<?php

include_once '../../models/conexion.php';
include_once '../../models/funciones.php';
include_once '../../controllers/funciones.php';

$idusuario = $_POST['idusuario'];
$iddocente = $_POST['iddocente'];
$nombres = $_POST['nombre'];
$apellidos = $_POST['apellido'];
$dui = $_POST['dui'];

$dataUsuario = CRUD("SELECT usuario FROM usuarios WHERE idusuario='$idusuario'", "s");

foreach ($dataUsuario as $du) {
    $usuario = $du['usuario'];
}

if (str_replace('-', '', $dui) != $usuario) {
    $valUser = 1;
    $nUser = str_replace('-', '', $dui);
    $passw = password_hash($nUser, PASSWORD_DEFAULT);
}

$email = $_POST['email'];
$estado = $_POST['estado'];

if (isset($valUser) == 1) {
    $update = CRUD("UPDATE docentes SET nombres = '$nombres', apellidos = '$apellidos', dui = '$dui', email='$email', estado='$estado' WHERE iddocente = '$iddocente'", "u");

    CRUD("UPDATE usuarios SET usuario = '$nUser', passw = '$passw', email='$email', estado='$estado' WHERE idusuario = '$idusuario'", "u");
} else {
    $update = CRUD("UPDATE usuarios SET email='$email', estado='$estado' WHERE idusuario = '$idusuario'", "u");

    $update = CRUD("UPDATE docentes SET nombres = '$nombres', apellidos = '$apellidos', dui = '$dui', email='$email', estado='$estado' WHERE iddocente = '$iddocente'", "u");
}
?>

<?php if ($update): ?>
    <script>
        $(document).ready(function () {
            alertify.success("Docente Actualizado");
            $("#contenido-principal").load("./views/docentes/principal.php");
        });
    </script>
<?php else: ?>
    <script>
        $(document).ready(function () {
            alertify.error("Error Docente No Actualizado");
            $("#contenido-principal").load("./views/docentes/principal.php");
        });
    </script>
<?php endif ?>