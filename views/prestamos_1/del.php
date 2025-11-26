<?php
include_once '../../models/conexion.php';
include_once '../../models/funciones.php';
include_once '../../controllers/funciones.php';

$idprestamo = $_GET['idprestamo'] ?? 0;

// Obtener datos del préstamo antes de eliminar
$prestamoData = CRUD("SELECT p.*, l.titulo, l.idlibro, p.cantidad 
                      FROM prestamos p
                      JOIN libros l ON p.idlibro = l.idlibro
                      WHERE p.idprestamo='$idprestamo'", "s");

if (is_array($prestamoData) && count($prestamoData) > 0) {
    $prestamo = $prestamoData[0];
    $idlibro = $prestamo['idlibro'];
    $cantidad = $prestamo['cantidad'];
    $titulo_libro = $prestamo['titulo'];
    
    // Devolver los ejemplares al stock
    $libroData = CRUD("SELECT ejemplares_disponibles FROM libros WHERE idlibro='$idlibro'", "s");
    if (is_array($libroData) && count($libroData) > 0) {
        $ejemplares_actuales = $libroData[0]['ejemplares_disponibles'];
        $nuevos_ejemplares = $ejemplares_actuales + $cantidad;
        
        CRUD("UPDATE libros SET ejemplares_disponibles = '$nuevos_ejemplares', estado='1' WHERE idlibro='$idlibro'", "u");
    }
}

// Eliminar el préstamo
$delete = CRUD("DELETE FROM prestamos WHERE idprestamo='$idprestamo'", "d");

if ($delete) {
    echo "<script>
        $(document).ready(function() {
            alertify.success('Préstamo eliminado correctamente');
            $('#contenido-principal').load('./views/prestamos/principal.php');
        });
    </script>";
} else {
    echo "<script>
        $(document).ready(function() {
            alertify.error('Error al eliminar el préstamo');
            $('#contenido-principal').load('./views/prestamos/principal.php');
        });
    </script>";
}
?>