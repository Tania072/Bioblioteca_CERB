<?php
@session_start();
include_once '../../models/conexion.php';
include_once '../../models/funciones.php';
include_once '../../controllers/funciones.php';

$idprestamo    = $_POST['idprestamo']    ?? null;
$idusuario     = $_POST['idusuario']     ?? null;
$idlibro       = $_POST['idlibro']       ?? null;
$fechaprestamo = $_POST['fechaprestamo'] ?? '';
$fecharetorno  = $_POST['fecharetorno']  ?? '';
$estado        = $_POST['estado']        ?? 1;
$cantidad        = $_POST['cantidad']    ?? 1;
// Si el usuario logueado es Docente (idrol = 2), forzamos su idusuario
$idrol_sesion     = $_SESSION['idrol']     ?? null;
$idusuario_sesion = $_SESSION['idusuario'] ?? null;
if ($idrol_sesion == 2 && $idusuario_sesion) {
    $idusuario = $idusuario_sesion;
}

// Normalizar fechas
if (!empty($fechaprestamo)) {
    $fechaprestamo = date('Y-m-d', strtotime($fechaprestamo));
}
if (!empty($fecharetorno)) {
    $fecharetorno = date('Y-m-d', strtotime($fecharetorno));
}

$update = false;
if ($idprestamo !== null) {
    $update = CRUD(
        "UPDATE prestamos SET 
            idusuario='$idusuario',
            idlibro='$idlibro',
            fechaprestamo='$fechaprestamo',
            fecharetorno='$fecharetorno',
            estado='$estado',
            cantidad ='$cantidad'
         WHERE idprestamo='$idprestamo'",
        "u"
    );
}
?>
<?php if ($update): ?>
    <script>
        $(document).ready(function() {
            alertify.success("Préstamo actualizado correctamente");
            $("#contenido-principal").load("./views/prestamos/principal.php");
        });
    </script>
<?php else: ?>
    <script>
        $(document).ready(function() {
            alertify.error("Error: el préstamo no se actualizó");
            $("#contenido-principal").load("./views/prestamos/principal.php");
        });
    </script>
<?php endif ?>