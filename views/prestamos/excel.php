<?php 
include_once '../../models/conexion.php';
include_once '../../models/funciones.php';
include_once '../../controllers/funciones.php';
@session_start();

//variables de de sesion
$idrol_sesion = $_SESSION['idrol'];
$idusuario_sesion = $_SESSION['idusuario'];
//Capturamos la fecha
$valor = $_GET['valor'];
//Crear WHERE con un arreglo
$condiciones = [];
//Filtro por fecha
if ($valor !== '') {
    $condiciones[] = "fechaprestamo='$valor'";
}

//Filtro por rol y usuario
if ($idrol_sesion == 2 && $idusuario_sesion) {
    $condiciones[] = "idusuario='$idusuario_sesion'";
}
//crear el WHERE final
$where = '';
if (count($condiciones) > 0) {
    $where = ' WHERE ' . implode(' AND ', $condiciones);
}
// Creamos la consulta final
$sql = "SELECT * FROM prestamos $where ORDER BY fechaprestamo DESC";
$dataPrestamos = CRUD($sql, "s") ?? [];

//Construir el Excel
//Indicar al navegador que se trata de un archivo de Excel y con UFT-8
header("Content-Type: application/vnd.ms-excel; charset=UTF-8");
//Nombramos el archivo
$nombreArchivo = "Prestamos Libros Fecha".$valor.".xls";
//Cabeceras para indicar la descarga del archivo
header('Content-Disposition: attachment; filename="'.$nombreArchivo.'"');
//Evitar el cache del archivo
header("Pragma: no-cache");
//Expire inmediatamente para evitar el cache
header("Expires: 0");
//BOM para que Excel detecte UTF-8
echo "\xEF\xBB\xBF";
//Meta etiqueta para navegadores que renderizan HTML
echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
//Contador para numerar filas
$cont = 0;
// Comenzamos la tabla HTML (Excel interpretará esta tabla)
echo "<table border='1' style='margin: 0 auto; width: 100%;border-collapse: collapse;'>";

// Encabezados de columnas
echo "<tr style='vertical-align:middle;text-align:center;'>";
echo "<th>N°</th>";
echo "<th>Docente</th>";
echo "<th>Libro</th>";
echo "<th>Fecha Préstamo</th>";
echo "<th>Fecha Devolución</th>";
echo "<th>Estado</th>";
echo "</tr>";
//Mostrar mensajes si no hay registros en el excel
if (!is_array($dataPrestamos) || count($dataPrestamos) === 0) {
    echo "<tr style='vertical-align:middle;text-align:center;'>";
    echo "<td colspan='6'>No se encontaron prestamos para la fecha solicitada: $valor</td>";
    echo "</tr>";
    echo "</table>";
    exit;
}

// ========== RECORRER Y PINTAR LOS PRÉSTAMOS ==========
foreach ($dataPrestamos as $result) {
    $cont++;
    $idusuario = $result['idusuario'];
    $idlibro = $result['idlibro'];

    // Nombre del docente
    $docenteCompleto = buscavalor(
        "docentes",
        "CONCAT(nombres,' ',apellidos)",
        "idusuario='$idusuario'"
    );

    // Título del libro
    $tituloLibro = buscavalor("libros", "titulo", "idlibro='$idlibro'");

    // Estado legible
    $estado = ($result['estado'] == 1) ? 'En Prestamo' : 'Finalizado';

    echo "<tr>";
    echo "<td style='vertical-align:middle;text-align:center;'>{$cont}</td>";
    echo "<td>{$docenteCompleto}</td>";
    echo "<td>{$tituloLibro}</td>";
    echo "<td style='vertical-align:middle;text-align:center;'>{$result['fechaprestamo']}</td>";
    echo "<td style='vertical-align:middle;text-align:center;'>{$result['fecharetorno']}</td>";
    echo "<td style='vertical-align:middle;text-align:center;'>{$estado}</td>";
    echo "</tr>";
}
echo "</table>";