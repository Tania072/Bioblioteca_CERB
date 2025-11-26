<?php
include_once '../../models/conexion.php';
include_once '../../models/funciones.php';
include_once '../../controllers/funciones.php';

@session_start();

$idlibro = $_POST['idlibro'] ?? 0;
$iddocente = $_POST['iddocente'] ?? 0;
$cantidad = $_POST['cantidad'] ?? 1;
$fechaprestamo = $_POST['fechaprestamo'] ?? date("Y-m-d");
$fecharetorno = $_POST['fecharetorno'] ?? date("Y-m-d", strtotime("+7 day"));

// Verificar que el docente existe y obtener su idusuario
$docenteData = CRUD("SELECT idusuario FROM docentes WHERE iddocente='$iddocente'", "s");
if (!is_array($docenteData) || count($docenteData) === 0) {
    echo "<script>alertify.error('Error: Docente no encontrado');</script>";
    echo "<script>$('#contenido-principal').load('./views/libros/principal_docentes.php');</script>";
    exit;
}

$idusuario = $docenteData[0]['idusuario'];

// Verificar disponibilidad del libro
$libroData = CRUD("SELECT ejemplares, ejemplares_disponibles, titulo FROM libros WHERE idlibro='$idlibro'", "s");
if (!is_array($libroData) || count($libroData) === 0) {
    echo "<script>alertify.error('Error: Libro no encontrado');</script>";
    echo "<script>$('#contenido-principal').load('./views/libros/principal_docentes.php');</script>";
    exit;
}

$ejemplares_totales = $libroData[0]['ejemplares'];
$ejemplares_disponibles = $libroData[0]['ejemplares_disponibles'];
$titulo_libro = $libroData[0]['titulo'];

// Si no existe el campo ejemplares_disponibles, usar ejemplares_totales
if ($ejemplares_disponibles === null) {
    $ejemplares_disponibles = $ejemplares_totales;
}

if ($cantidad > $ejemplares_disponibles) {
    echo "<script>alertify.error('Error: No hay suficientes ejemplares disponibles. Solo hay $ejemplares_disponibles disponibles');</script>";
    echo "<script>$('#contenido-principal').load('./views/libros/principal_docentes.php');</script>";
    exit;
}

// Verificar si existe el campo cantidad en préstamos
$checkField = CRUD("SHOW COLUMNS FROM prestamos LIKE 'cantidad'", "s");
if (!is_array($checkField) || count($checkField) === 0) {
    // Agregar el campo si no existe
    CRUD("ALTER TABLE prestamos ADD COLUMN cantidad INT DEFAULT 1", "u");
}

// Insertar el préstamo
$insert = CRUD("INSERT INTO prestamos(idusuario, idlibro, fechaprestamo, fecharetorno, estado, cantidad) 
               VALUES ('$idusuario', '$idlibro', '$fechaprestamo', '$fecharetorno', '1', '$cantidad')", "i");

if ($insert) {
    // Actualizar ejemplares disponibles
    $nuevos_ejemplares = $ejemplares_disponibles - $cantidad;
    
    // Verificar si existe el campo ejemplares_disponibles
    $checkField = CRUD("SHOW COLUMNS FROM libros LIKE 'ejemplares_disponibles'", "s");
    if (is_array($checkField) && count($checkField) > 0) {
        $update_libro = CRUD("UPDATE libros SET ejemplares_disponibles = '$nuevos_ejemplares' WHERE idlibro='$idlibro'", "u");
    } else {
        // Si no existe el campo, crearlo
        CRUD("ALTER TABLE libros ADD COLUMN ejemplares_disponibles INT DEFAULT 0", "u");
        CRUD("UPDATE libros SET ejemplares_disponibles = ejemplares WHERE ejemplares_disponibles IS NULL", "u");
        $update_libro = CRUD("UPDATE libros SET ejemplares_disponibles = '$nuevos_ejemplares' WHERE idlibro='$idlibro'", "u");
    }
    
    // Si no quedan ejemplares, marcar como no disponible
    if ($nuevos_ejemplares == 0) {
        CRUD("UPDATE libros SET estado = '0' WHERE idlibro='$idlibro'", "u");
    }
    
    // Éxito
    echo "<script>
        $(document).ready(function() {
            alertify.success('Préstamo registrado: $cantidad ejemplar(es) de \"$titulo_libro\"');
            $('#contenido-principal').load('./views/libros/principal_docentes.php');
        });
    </script>";
} else {
    echo "<script>
        $(document).ready(function() {
            alertify.error('Error: No se pudo registrar el préstamo');
            $('#contenido-principal').load('./views/libros/principal_docentes.php');
        });
    </script>";
}
?>