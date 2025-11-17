<?php 
include_once '../../models/conexion.php';
include_once '../../models/funciones.php';
include_once '../../controllers/funciones.php';
include_once './principal.php';

$idlibro = $_GET['idlibro'];
$valor = $_GET['valor'];
$v_estado = ($valor == 1) ? 'Disponible':'No Disponible';

$update = CRUD("UPDATE libros SET estado='$valor' WHERE idlibro='$idlibro'", "u");
?>
<?php if ($update): ?>
    <script>
        $(document).ready(function() {
            let valor = '<?php echo $valor;?>';
            let vestado = '<?php echo $v_estado;?>';
            if (valor == 1) {
                alertify.warning(`Libro ${vestado}`);
            } else {
                alertify.error(`Libro ${vestado}`);
            }
            $("#contenido-principal").load("./views/libros/principal.php");
        });
    </script>
<?php else: ?>
    <script>
        $(document).ready(function() {
            alertify.error("Error Libro No Actalizado");
            $("#contenido-principal").load("./views/libros/principal.php");
        });
    </script>
<?php endif ?>
