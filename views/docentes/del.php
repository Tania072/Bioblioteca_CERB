<?php
include_once '../../models/conexion.php';
include_once '../../models/funciones.php';
include_once '../../controllers/funciones.php';

$iddocente = $_GET['iddocente'];
$dataDocente = CRUD("SELECT * FROM docentes WHERE iddocente='$iddocente'", "s");
foreach ($dataDocente as $result) {
    $idusuario = $result['idusuario'];
}
$delete = CRUD("DELETE FROM docentes WHERE iddocente='$iddocente'", "u");
$delete = CRUD("DELETE FROM usuarios WHERE idusuario='$idusuario'", "u");


?>
<?php if ($delete): ?>
    <script>
        $(document).ready(function() {
                alertify.warning("Docente Eliminado");                       
                        $("#contenido-principal").load("./views/docentes/principal.php");
                    });
    </script>
<?php else: ?>
    <script>
        $(document).ready(function() {
            alertify.error("Error Docente No Actualizado");
            $("#contenido-principal").load("./views/docentes/principal.php");
        });
    </script>
<?php endif ?>