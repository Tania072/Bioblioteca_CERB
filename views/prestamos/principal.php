<?php
include_once '../../models/conexion.php';
include_once '../../models/funciones.php';
include_once '../../controllers/funciones.php';
@session_start();

$cont         = 0;
$fechaActual  = date("Y-m-d");

// ================================
// 1) Datos de sesión (rol y usuario)
// ================================
$idrol_sesion     = $_SESSION['idrol']     ?? null;
$idusuario_sesion = $_SESSION['idusuario'] ?? null;

// ================================
// 2) Paginación
// ================================
if (isset($_REQUEST['num'])) {
    $pagina = (int) $_REQUEST['num'];
} else {
    $pagina = 0;
}

if (isset($_REQUEST['num_reg'])) {
    $registros = (int) $_REQUEST['num_reg'];
} else {
    $registros = 10;
}

if (!$pagina) {
    $inicio = 0;
    $pagina = 1;
} else {
    $inicio  = ($pagina - 1) * $registros;
}

// ================================
// 3) Filtro por fecha (+ por usuario según rol)
// ================================
if (isset($_REQUEST['valor']) && $_REQUEST['valor'] !== '') {
    $valor = $_REQUEST['valor'];
} else {
    $valor = $fechaActual;
}

// Base de la condición: siempre filtramos por fecha elegida
$where = "fechaprestamo='$valor'";

// Si es docente (idrol = 2), solo ve sus préstamos
if ($idrol_sesion == 2 && $idusuario_sesion) {
    $where .= " AND idusuario='$idusuario_sesion'";
}

// ================================
// 4) Consulta principal (lista de préstamos)
// ================================
$query = "SELECT * FROM prestamos WHERE $where";
$dataPrestamos = CRUD($query . " LIMIT $inicio,$registros", "s");

// ================================
// 5) Paginación total
// ================================
$num_registros = CountReg($query);
$paginas = ($num_registros > 0) ? ceil($num_registros / $registros) : 1;

// ================================
// 6) Totales de préstamos (según rol)
//   - Admin: todos
//   - Docente: solo los suyos
// ================================
$condUsuario = '';
if ($idrol_sesion == 2 && $idusuario_sesion) {
    $condUsuario = " WHERE idusuario='$idusuario_sesion'";
}

$data = CRUD("
    SELECT 
        COUNT(*) AS total, 
        SUM(CASE WHEN estado = 1 THEN 1 ELSE 0 END) AS p_activos,
        SUM(CASE WHEN estado = 2 THEN 1 ELSE 0 END) AS p_finalizados
    FROM prestamos
    $condUsuario
", "s");

$p_activos      = 0;
$p_finalizados  = 0;
$total          = 0;

foreach ($data as $result) {
    $p_activos     = $result['p_activos'];
    $p_finalizados = $result['p_finalizados'];
    $total         = $result['total'];
}
?>

<style>
    /* Estado deshabilitado visual */
    .BtnPrestamosOff.is-disabled {
        position: relative;
        /* Necesario para posicionar el overlay */
        opacity: 0.6;
        /* Look de deshabilitado */
    }

    /* El overlay cubre todo el botón: evita el click y muestra cursor "prohibido" */
    .BtnPrestamosOff.is-disabled::after {
        content: "";
        position: absolute;
        inset: 0;
        /* top/right/bottom/left: 0 */
        pointer-events: auto;
        /* Este pseudo-elemento recibe el puntero */
        cursor: not-allowed;
        /* Icono de prohibido */
    }

    /* Opcional: desaturar el ícono */
    .BtnPrestamosOff.is-disabled i {
        filter: grayscale(1);
        opacity: 0.8;
    }
</style>
<div class="table-responsive" id="PanelPrestamos">
    <div style="margin: 0 auto; width: 100%;" class="barra">
        <a href="" class="btn btn-sm btn-primary" id="new-prestamo">
            <i class="fa-solid fa-user-plus"></i>
        </a>
        <a href="" class="btn btn-sm" style="background-color: #01522D;color:white" id="excel-prestamos">
            <i class="fa-solid fa-file-excel"></i>
        </a>
        <div class="acciones">
            <div class="input-group mb-3">
                <input type="date" class="form-control" name="valor">
                <button class="btn btn-outline-secondary" type="button" id="busca-prestamos">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </button>
                <a href="" class="btn btn-outline btn-warning" id="reload">
                    <i class="fa-solid fa-arrows-rotate"></i>
                </a>
            </div>
        </div>
        <hr>
    </div>
    <div class="row">
        <div class="col-md-9 mb-2">
            <table class="table table-borderless table-bordered table-hover table-sm" style="margin: 0 auto; width: 100%;border-collapse: collapse;">
                <thead style="vertical-align:middle" class="cc">
                    <tr>
                        <th>N°</th>
                        <th>Docente</th>
                        <th>Libro</th>
                        <th>Fecha <br>Prestamo</th>
                        <th>Fecha <br>Devolución</th>
                        <th>Estado</th>
                        <th colspan="2">Acciones</th>
                    </tr>
                </thead>
                <tbody style="vertical-align:middle" class="cc">
                    <?php if (is_array($dataPrestamos) && $dataPrestamos): ?>
                        <?php foreach ($dataPrestamos as $result): ?>
                            <tr>
                                <td><?php echo $cont += 1; ?></td>
                                <td>
                                    <?php
                                    echo buscavalor("docentes", "CONCAT(nombres,' ',apellidos)", "idusuario='" . $result['idusuario'] . "'") ?? [];
                                    ?>
                                </td>
                                <td>
                                    <?php
                                    echo buscavalor("libros", "titulo", "idlibro='" . $result['idlibro'] . "'");
                                    ?>
                                </td>
                                <td><?php echo $result['fechaprestamo']; ?></td>
                                <td><?php echo $result['fecharetorno']; ?></td>
                                <td style="background-color: <?= ($result['estado'] == 1) ? 'red' : 'green' ?>;color:white;">
                                    <?php
                                    echo ($result['estado'] == 1) ? 'En Prestamo' : 'Finalizado';
                                    ?>
                                </td>
                                <td>
                                    <?php if ($result['estado'] == 2): ?>
                                        <a href="" class="btn btn-sm btn-success  BtnPrestamosOff is-disabled null-envio" idprestamo="<?php echo $result['idprestamo']; ?>" aria-disabled="true">
                                            <i class="fa-solid fa-pen"></i>
                                        </a>
                                    <?php else: ?>
                                        <a href="" class="btn btn-sm btn-success BtnUpdatePrestamo" idprestamo="<?php echo $result['idprestamo']; ?>">
                                            <i class="fa-solid fa-pen"></i>
                                        </a>
                                    <?php endif ?>
                                </td>
                                <td>
                                    <?php if ($result['estado'] == 2): ?>
                                        <a href="" class="btn btn-sm btn-danger  BtnPrestamosOff is-disabled null-envio" idprestamo="<?php echo $result['idprestamo']; ?>">
                                            <i class="fa-solid fa-trash"></i>
                                        </a>
                                    <?php else: ?>
                                        <a href="" class="btn btn-sm btn-danger BtnDeletePrestamo" idprestamo="<?php echo $result['idprestamo']; ?>">
                                            <i class="fa-solid fa-trash"></i>
                                        </a>
                                    <?php endif ?>
                                </td>
                            </tr>
                        <?php endforeach ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8"><b>No se encuentras prestamos para la fecha solicitada: <?= (isset($_REQUEST['valor'])) ? $_REQUEST['valor'] : $fechaActual; ?></b></td>
                        </tr>
                    <?php endif ?>
                </tbody>
            </table>
            <div style="margin-top: 15px;vertical-align: middle;text-align: center;">
                <?php if ($num_registros > $registros): ?>
                    <?php if ($pagina == 1): ?>
                        <div class="vh">
                            <a href="" class="pagina btn btn-sm btn-d null-envio" v-num="<?php echo ($pagina - 1); ?>" num-reg="<?php echo $registros; ?>" disabled="disabled">
                                <i class="fa-regular fa-circle-left"></i>
                            </a>
                            &nbsp;&nbsp;
                            <a href="" class="pagina btn btn-sm bg-dark text-white" v-num="<?php echo ($pagina + 1); ?>" num-reg="<?php echo $registros; ?>">
                                <i class="fa-regular fa-circle-right"></i>
                            </a>
                        </div>
                    <?php elseif ($pagina == $paginas): ?>
                        <div class="vh">
                            <a href="" class="pagina btn btn-sm bg-dark text-white" v-num="<?php echo ($pagina - 1); ?>" num-reg="<?php echo $registros; ?>">
                                <i class="fa-regular fa-circle-left"></i>
                            </a>
                            &nbsp;&nbsp;
                            <a href="" class="btn btn-sm btn-d null-envio" v-num="<?php echo ($pagina + 1); ?>" num-reg="<?php echo $registros; ?>" disabled="disabled">
                                <i class="fa-regular fa-circle-right"></i>
                            </a>
                        </div>
                    <?php else: ?>
                        <div class="vh">
                            <a href="" class="pagina btn btn-sm bg-dark text-white" v-num="<?php echo ($pagina - 1); ?>" num-reg="<?php echo $registros; ?>">
                                <i class="fa-regular fa-circle-left"></i>
                            </a>
                            &nbsp;&nbsp;
                            <a href="" class="pagina btn btn-sm bg-dark text-white" v-num="<?php echo ($pagina + 1); ?>" num-reg="<?php echo $registros; ?>">
                                <i class="fa-regular fa-circle-right"></i>
                            </a>
                        </div>
                    <?php endif ?>
                <?php endif ?>
            </div>
        </div>
        <div class="col-md-3 mb-2">
            <div class="card">
                <div class="card-header">
                    <b>Estado Prestamos</b>
                </div>
                <div class="card-body">
                    <p><b>En Prestamo: </b><?= $p_activos; ?></p>
                    <p><b>Finalizados: </b><?= $p_finalizados; ?></p>
                </div>
            </div>
            <hr>
            <div class="card">
                <div class="card-header">
                    <b>Total Prestamos</b>
                </div>
                <div class="card-body">
                    <p><b>Total : </b><?= $total; ?></p>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        // reload
        $("#reload").click(function() {
            $("#contenido-principal").load("views/prestamos/principal.php");
            return false;
        });
        // Evitar redirigir
        $(".null-envio").click(function() {
            return false;
        });
        // Paginado
        $(".pagina").click(function() {
            let num, reg;
            num = $(this).attr("v-num");
            reg = $(this).attr("num-reg");
            $("#contenido-principal").load("views/prestamos/principal.php?num=" + num + "&num_reg=" + reg);
            return false;
        });
        // Busca prestamo por fecha
        $("#busca-prestamos").click(function() {
            let valor = $('[name="valor"]').val();
            if (valor == "" || valor == null) {
                alertify.alert("Busca Prestamo", "Favor de seleccionar la fecha de los prestamos");
            } else {
                $.post("views/prestamos/principal.php", {
                    valor: valor
                }, function(html) {
                    $("#contenido-principal").html(html);
                });
            }
            return false;
        });
        // Modal Nuevo Prestamo
        $("#new-prestamo").click(function() {
            $("#ModalNP").modal('show');
            $("#ModalDNP").load("./views/prestamos/form_insert.php");
            return false;
        });

        // Modal Editar Docente
        $(".BtnUpdatePrestamo").click(function() {
            let idprestamo = $(this).attr('idprestamo');
            $("#ModalEP").modal('show');
            $("#ModalDEP").load("./views/prestamos/form_update.php?idprestamo=" + idprestamo);
            return false;
        });
        // Boton para eliminar docente sino tiene registros de prestamos
        $(".BtnDeletePrestamo").click(function() {
            let idprestamo = $(this).attr('idprestamo');
            alertify.confirm('Eliminar Prestamo', 'Seguro/a de eliminar prestamo?', function() {
                $("#PanelPrestamos").load('./views/prestamos/del.php?idprestamo=' + idprestamo);
            }, function() {
                alertify.error('Acción Cancelada');
            });
            return false;
        });
        // Genera Excel
        $("#excel-prestamos").click(function() {
            let fecha = "<?= (isset($_REQUEST['valor'])) ? $_REQUEST['valor'] : $fechaActual; ?>";
            let url = "./views/prestamos/excel.php";

            if (fecha !== "") {
                url += "?valor=" + encodeURIComponent(fecha);
            }

            window.location.href = url;
            return false;
        });

    });
</script>