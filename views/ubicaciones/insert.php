<?php
@session_start();
include_once '../../models/conexion.php';
include_once '../../models/funciones.php';
include_once '../../controllers/funciones.php';

$nombre = $_POST['nombre'];
$descripcion = $_POST['descripcion'];
$estado = $_POST['estado'];

// Insertar nueva ubicación usando "i" para INSERT
$insert = CRUD("INSERT INTO ubicaciones (nombre, descripcion, estado) 
                VALUES ('$nombre','$descripcion','$estado')", "i");
?>
<?php if ($insert): ?>
    <script>
        $(document).ready(function() {
            alertify.success("Ubicación Agregada");
            $("#contenido-principal").load("./views/ubicaciones/principal.php");
        });
    </script>
<?php else: ?>
    <script>
        $(document).ready(function() {
            alertify.error("Error: Ubicación No Agregada");
            $("#contenido-principal").load("./views/ubicaciones/principal.php");
        });
    </script>
<?php endif ?>