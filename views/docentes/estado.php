<?php
include_once '../../models/conexion.php';
include_once '../../models/funciones.php';
include_once '../../controllers/funciones.php';

$idusuario = $_GET['idusuario'];
$valor = $_GET['valor'];
$v_estado = ($valor == 1) ? 'Activo' : 'Inactivo';

$update = CRUD("UPDATE usuarios SET estado='$valor' WHERE idusuario='$idusuario'", "u");
?>
<?php if ($update): ?>
    <script>
        $(document).ready(function() {

            let vestado = '<?php echo $v_estado; ?>';
            let valor = '<?php echo $valor; ?>';
            if (valor == 1) {
                alertify.warning(`Usuario ${vestado}`);
            } else {
                alertify.error(`Usuario ${vestado}`);
            }
            $("#contenido-principal").load("./views/usuarios/principal.php");
        });
    </script>
<?php else: ?>
    <script>
        $(document).ready(function() {
            alertify.error("Error Usuario No Actualizado");
            $("#contenido-principal").load("./views/usuarios/principal.php");
        });
    </script>
<?php endif ?>