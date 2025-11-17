<?php 
include_once '../../models/conexion.php';
include_once '../../models/funciones.php';
include_once '../../controllers/funciones.php';
include_once './principal.php';

$idgenero = $_GET['idgenero'];

$delete = CRUD("DELETE FROM generos WHERE idgenero='$idgenero'", "u");
?>
<?php if ($delete): ?>
    <script>
        $(document).ready(function() {
            alertify.warning(`Genero Eliminado`);
            $("#contenido-principal").load("./views/generos/principal.php");
        });
    </script>
<?php else: ?>
    <script>
        $(document).ready(function() {
            alertify.error("Error Genero No Eliminado");
            $("#contenido-principal").load("./views/generos/principal.php");
        });
    </script>
<?php endif ?>