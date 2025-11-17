<?php 
include_once '../../models/conexion.php';
include_once '../../models/funciones.php';
include_once '../../controllers/funciones.php';
include_once './principal.php';

$idlibro = $_GET['idlibro'];

$delete = CRUD("DELETE FROM libros WHERE idlibro='$idlibro'", "u");
?>
<?php if ($delete): ?>
    <script>
        $(document).ready(function() {
            alertify.warning(`Libro Eliminado`);
            $("#contenido-principal").load("./views/libros/principal.php");
        });
    </script>
<?php else: ?>
    <script>
        $(document).ready(function() {
            alertify.error("Error Libro No Eliminado");
            $("#contenido-principal").load("./views/libros/principal.php");
        });
    </script>
<?php endif ?>
