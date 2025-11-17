<?php
include_once '../../models/conexion.php';
include_once '../../models/funciones.php';
include_once '../../controllers/funciones.php';

$idgenero = $_POST['idgenero'];
$genero = $_POST['genero'];
$descripcion = $_POST['descripcion'];

$update = CRUD("UPDATE generos SET genero='$genero', descripcion='$descripcion' WHERE idgenero='$idgenero'", "u");
?>
<?php if ($update): ?>
    <script>
        $(document).ready(function() {
            alertify.success("Genero Actualizado");
            $("#contenido-principal").load("./views/generos/principal.php");
        });
    </script>
<?php else: ?>
    <script>
        $(document).ready(function() {
            alertify.error("Error Genero No Actalizado");
            $("#contenido-principal").load("./views/generos/principal.php");
        });
    </script>
<?php endif ?>
