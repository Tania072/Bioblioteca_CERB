<?php
include_once '../../models/conexion.php';
include_once '../../models/funciones.php';
include_once '../../controllers/funciones.php';

@session_start();

$idprestamo = $_POST['idprestamo'] ?? 0;
$fecharetorno = $_POST['fecharetorno'] ?? '';
$idusuario_sesion = $_SESSION['idusuario'] ?? 0;

// Verificar que el préstamo pertenece al docente
$prestamoData = CRUD("SELECT * FROM prestamos WHERE idprestamo='$idprestamo' AND idusuario='$idusuario_sesion' AND estado='1'", "s");

if (!is_array($prestamoData) || count($prestamoData) === 0) {
    echo "<script>
        $(document).ready(function() {
            alertify.error('Error: No tiene permisos para editar este préstamo');
            $('#contenido-principal').load('./views/prestamos/principal.php');
        });
    </script>";
    exit;
}

// Actualizar solo la fecha de devolución
$update = CRUD("UPDATE prestamos SET fecharetorno='$fecharetorno' WHERE idprestamo='$idprestamo'", "u");

if ($update) {
    echo "<script>
        $(document).ready(function() {
            alertify.success('Fecha de devolución actualizada correctamente');
            $('#contenido-principal').load('./views/prestamos/principal.php');
        });
    </script>";
} else {
    echo "<script>
        $(document).ready(function() {
            alertify.error('Error al actualizar la fecha de devolución');
            $('#contenido-principal').load('./views/prestamos/principal.php');
        });
    </script>";
}
?>