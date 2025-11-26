<?php
include_once '../../models/conexion.php';
include_once '../../models/funciones.php';
include_once '../../controllers/funciones.php';
@session_start();

// Detectar rol y usuario en sesión
$idrol_sesion     = $_SESSION['idrol'] ?? null;
$idusuario_sesion = $_SESSION['idusuario'] ?? null;

// Obtener lista de docentes (para administradores)
$docentes = CRUD("SELECT * FROM docentes WHERE estado = 1 ORDER BY nombres, apellidos", "S") ?? [];

// Obtener lista de libros disponibles
$libros = CRUD("SELECT * FROM libros WHERE estado = 1 ORDER BY titulo", "S") ?? [];

// Fecha actual para valor por defecto
$hoy = date('Y-m-d');
?>

<div class="input-group mb-3">
    <span class="input-group-text"><b>Docente:</b></span>
    <?php if ($idrol_sesion == 2 && $idusuario_sesion): ?>
        <?php
        $nombreDocente = buscavalor(
            'docentes',
            "CONCAT(nombres,' ',apellidos)",
            "idusuario='" . $idusuario_sesion . "'"
        );
        ?>
        <!-- Muestra el nombre, pero NO permite cambiarlo -->
        <input type="text"
            class="form-control"
            value="<?php echo htmlspecialchars($nombreDocente, ENT_QUOTES, 'UTF-8'); ?>"
            disabled>
        <!-- Guarda el idusuario del docente logueado -->
        <input type="hidden" name="idusuario" value="<?php echo $idusuario_sesion; ?>">
    <?php else: ?>
        <!-- Admin u otro rol: selecciona cualquier docente -->
        <select class="form-select" name="idusuario" required>
            <option value="">Seleccione un docente</option>
            <?php foreach ($docentes as $doc): ?>
                <option value="<?php echo $doc['idusuario']; ?>">
                    <?php echo htmlspecialchars($doc['nombres'] . ' ' . $doc['apellidos'], ENT_QUOTES, 'UTF-8'); ?>
                </option>
            <?php endforeach; ?>
        </select>
    <?php endif; ?>
</div>

<div class="input-group mb-3">
    <span class="input-group-text"><b>Libro:</b></span>
    <select class="form-select" name="idlibro" id="idlibro" required>
        <option value="">Seleccione un libro</option>
        <?php foreach ($libros as $lib): ?>
            <option value="<?php echo $lib['idlibro']; ?>">
                <?php
                $texto = $lib['titulo'];
                if (!empty($lib['isbn'])) {
                    $texto .= ' (ISBN: ' . $lib['isbn'] . ')';
                }
                echo htmlspecialchars($texto, ENT_QUOTES, 'UTF-8');
                ?>
            </option>
        <?php endforeach; ?>
    </select>
</div>

<div id="DataNombreUbicacion">

</div>

<div class="input-group mb-3">
    <span class="input-group-text"><b>Fecha préstamo:</b></span>
    <input type="date"
        class="form-control"
        name="fechaprestamo"
        value="<?php echo $hoy; ?>"
        required>
</div>

<div class="input-group mb-3">
    <span class="input-group-text"><b>Fecha retorno:</b></span>
    <input type="date" class="form-control" name="fecharetorno">
</div>

<!-- Por defecto se crea "En Préstamo" -->
<input type="hidden" name="estado" value="1">

<script>
    $(document).ready(function() {
        $("#idlibro").click('keyup', function() {
            let idlibro = $("#idlibro").val();
            $("#DataNombreUbicacion").load("./views/prestamos/ubicacion.php?idlibro=" + idlibro);
            return false;
        });
    });
</script>