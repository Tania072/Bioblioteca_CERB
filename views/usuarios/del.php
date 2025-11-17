<?php 
include_once '../../models/conexion.php';
include_once '../../models/funciones.php';
include_once '../../controllers/funciones.php';

$idusuario = $_GET['idusuario'];

$delete = CRUD("DELETE FROM usuarios WHERE idusuario='$idusuario'", "u");
?>
<?php if ($delete): ?>
    <script>
        $(document).ready(function() {
            alertify.warning(`Usuario Eliminado`);
            $("#contenido-principal").load("./views/usuarios/principal.php");
        });
    </script>
<?php else: ?>
    <script>
        $(document).ready(function() {
            alertify.error("Error Usuario No Eliminado");
            $("#contenido-principal").load("./views/usuarios/principal.php");
        });
    </script>
<?php endif ?>
