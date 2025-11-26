<?php
include_once '../../models/conexion.php';
include_once '../../models/funciones.php';
include_once '../../controllers/funciones.php';

$idprestamo = $_GET['idprestamo'] ?? null;

$delete = false;
if ($idprestamo !== null) {
    $delete = CRUD("DELETE FROM prestamos WHERE idprestamo='$idprestamo'", "u");
}
?>
<?php if ($delete): ?>
    <script>
        $(document).ready(function() {
            alertify.warning('Préstamo eliminado');
            $("#contenido-principal").load("./views/prestamos/principal.php");
        });
    </script>
<?php else: ?>
    <script>
        $(document).ready(function() {
            alertify.error("Error: el préstamo no se eliminó");
            $("#contenido-principal").load("./views/prestamos/principal.php");
        });
    </script>
<?php endif ?>