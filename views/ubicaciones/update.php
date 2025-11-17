<?php
include_once '../../models/conexion.php';
include_once '../../models/funciones.php';
include_once '../../controllers/funciones.php';

$idubicacion = $_POST['idubicacion'];
$nombre = $_POST['nombre'];
$descripcion = $_POST['descripcion'];
$estado = $_POST['estado'];

$update = CRUD("UPDATE ubicaciones SET nombre='$nombre', descripcion='$descripcion', estado='$estado' WHERE idubicacion='$idubicacion'", "u");
?>
<?php if ($update): ?>
    <script>
        $(document).ready(function() {
            alertify.success("Ubicación Actualizada");
            $("#contenido-principal").load("./views/ubicaciones/principal.php");
        });
    </script>
<?php else: ?>
    <script>
        $(document).ready(function() {
            alertify.error("Error: Ubicación No Actualizada");
            $("#contenido-principal").load("./views/ubicaciones/principal.php");
        });
    </script>
<?php endif ?>