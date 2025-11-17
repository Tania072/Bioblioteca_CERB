<?php
@session_start();
include_once '../../models/conexion.php';
include_once '../../models/funciones.php';
include_once '../../controllers/funciones.php';
$nombres = $_POST['nombre'];
$apellidos = $_POST['apellido'];
$dui = $_POST['dui'];
$idusuario = MaxID("SELECT MAX(idusuario) FROM usuarios") + 1;
$usuario = $_POST['usuario'];
$passw = password_hash($_POST['passw'], PASSWORD_DEFAULT);
$email = $_POST['email'];
$idrol = 2;

$insertB = CRUD("INSERT INTO usuarios(idusuario, usuario,passw,email,idrol) VALUES('$idusuario','$usuario','$passw','$email','$idrol')", "i");

$insertA = CRUD("INSERT INTO docentes(nombres,apellidos,dui, email, estado, idusuario) VALUES('$nombres','$apellidos','$dui', '$email', 1, '$idusuario')", "i");
?>
<?php
if ($insertA): ?>
    <script>
        $(document).ready(function () {
            alertify.success("Docente Registrado");
            $("#contenido-principal").load("./views/docentes/principal.php");
        });
    </script>
<?php else: ?>
    <script>
        $(document).ready(function () {
            alertify.error("Error Docente No Registrado");
            $("#contenido-principal").load("./views/docentes/principal.php");
        });
    </script>
<?php endif ?>