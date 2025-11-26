<?php
include_once '../../models/conexion.php';
include_once '../../models/funciones.php';
include_once '../../controllers/funciones.php';

@session_start();

$idprestamo = $_POST['idprestamo'] ?? 0;
$idusuario_sesion = $_SESSION['idusuario'] ?? 0;

// Verificar que el préstamo pertenece al docente y está activo
$prestamoData = CRUD("SELECT p.*, l.titulo, l.idlibro, p.cantidad 
                      FROM prestamos p
                      JOIN libros l ON p.idlibro = l.idlibro
                      WHERE p.idprestamo='$idprestamo' AND p.idusuario='$idusuario_sesion' AND p.estado='1'", "s");

if (!is_array($prestamoData) || count($prestamoData) === 0) {
    echo "<script>
        $(document).ready(function() {
            alertify.error('Error: No tiene permisos para devolver este préstamo');
            $('#contenido-principal').load('./views/prestamos/principal.php');
        });
    </script>";
    exit;
}

$prestamo = $prestamoData[0];
$idlibro = $prestamo['idlibro'];
$cantidad = $prestamo['cantidad'];
$titulo_libro = $prestamo['titulo'];

// Actualizar el estado del préstamo a finalizado (0)
$update_prestamo = CRUD("UPDATE prestamos SET estado='0' WHERE idprestamo='$idprestamo'", "u");

if ($update_prestamo) {
    // Actualizar los ejemplares disponibles del libro
    $libroData = CRUD("SELECT ejemplares_disponibles FROM libros WHERE idlibro='$idlibro'", "s");
    if (is_array($libroData) && count($libroData) > 0) {
        $ejemplares_actuales = $libroData[0]['ejemplares_disponibles'];
        $nuevos_ejemplares = $ejemplares_actuales + $cantidad;
        
        // Actualizar ejemplares disponibles
        CRUD("UPDATE libros SET ejemplares_disponibles = '$nuevos_ejemplares' WHERE idlibro='$idlibro'", "u");
        
        // Si ahora hay ejemplares disponibles, marcar como disponible
        if ($nuevos_ejemplares > 0) {
            CRUD("UPDATE libros SET estado='1' WHERE idlibro='$idlibro'", "u");
        }
    }
    
    // Éxito
    echo "<script>
        $(document).ready(function() {
            alertify.success('Libro devuelto: $cantidad ejemplar(es) de \"$titulo_libro\"');
            $('#contenido-principal').load('./views/prestamos/principal.php');
        });
    </script>";
} else {
    echo "<script>
        $(document).ready(function() {
            alertify.error('Error al procesar la devolución');
            $('#contenido-principal').load('./views/prestamos/principal.php');
        });
    </script>";
}
?>