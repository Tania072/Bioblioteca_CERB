<?php
include_once 'models/conexion.php';
include_once 'models/funciones.php';

// Crear campo ejemplares_disponibles en libros si no existe
$check1 = CRUD("SHOW COLUMNS FROM libros LIKE 'ejemplares_disponibles'", "s");
if (!is_array($check1) || count($check1) === 0) {
    CRUD("ALTER TABLE libros ADD COLUMN ejemplares_disponibles INT DEFAULT 0", "u");
    CRUD("UPDATE libros SET ejemplares_disponibles = ejemplares", "u");
    echo "Campo 'ejemplares_disponibles' agregado a la tabla libros<br>";
}

// Crear campo cantidad en prestamos si no existe
$check2 = CRUD("SHOW COLUMNS FROM prestamos LIKE 'cantidad'", "s");
if (!is_array($check2) || count($check2) === 0) {
    CRUD("ALTER TABLE prestamos ADD COLUMN cantidad INT DEFAULT 1", "u");
    echo "Campo 'cantidad' agregado a la tabla prestamos<br>";
}

echo "¡Estructura de base de datos actualizada correctamente!";
?>