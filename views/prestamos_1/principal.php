<?php
include_once '../../models/conexion.php';
include_once '../../models/funciones.php';
include_once '../../controllers/funciones.php';
include_once '../modals/nuevo_prestamo.php';
include_once '../modals/editar_prestamo.php';

@session_start();

$cont = 0;

$fechaActual = date("Y-m-d");

//variables de de sesion
$idrol_sesion = $_SESSION['idrol'];
$idusuario_sesion = $_SESSION['idusuario'];

if (isset($_REQUEST['num'])) {
    $pagina = $_REQUEST['num'];
} else {
    $pagina = 0;
}

if (isset($_REQUEST['num_reg'])) {
    $registros = $_REQUEST['num_reg'];
} else {
    $registros = 10;
}

if (!$pagina) {
    $inicio = 0;
    $pagina = 1;
} else {
    $inicio = ($pagina - 1) * $registros;
}

//Filtro por fecha
if (isset($_REQUEST['valor'])) {
    $valor = $_REQUEST['valor'];
} else {
    $valor = $fechaActual;
}

//Base de la condición
$where = "fechaprestamo='$valor'";
//Filtro por usuario y rol
if ($idrol_sesion == 2 && $idusuario_sesion) {
    $where .= " AND idusuario='$idusuario_sesion'";
} else {
    # code...
}


$query = "SELECT * FROM prestamos WHERE  $where";
$dataPrestamos = CRUD($query . " LIMIT $inicio,$registros", "s");

$num_registros = CountReg($query);
$paginas = ceil($num_registros / $registros);

//Totales segun rol
$condUsuario='';
if ($idrol_sesion == 2 && $idusuario_sesion) {
    $condUsuario=" WHERE idusuario='$idusuario_sesion'";
}

$data = CRUD(
    "SELECT COUNT(*)  AS total, 
SUM(CASE WHEN estado = 1 THEN 1 ELSE 0 END) AS p_activos, 
SUM(CASE WHEN estado = 0 THEN 1 ELSE 0 END) AS p_finalizados
FROM prestamos $condUsuario",
    "s"
);

$p_activos = 0;
$p_finalizados = 0;
$total = 0;

foreach ($data as $result) {
    $p_activos = $result['p_activos'];
    $p_finalizados = $result['p_finalizados'];
    $total = $result['total'];
}
?>
<div class="table-responsive" id="PanelPrestamos">
    <div style="margin: 0 auto; width: 100%;" class="barra">
        <a href="" class="btn btn-sm btn-primary" id="new-prestamos">
            <i class="fa-solid fa-circle-plus"></i>
        </a>
        <a href="" class="btn btn-sm" style="background-color: #01522D; color: white;" id="excel-prestamos">
            <i class="fa-solid fa-file-excel"></i>
        </a>
        <div class="acciones">
            <div class="input-group mb-3">
                <input type="date" class="form-control" name="valor">
                <button class="btn btn-outline-secondary" type="button" id="busca-prestamo">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </button>
                <a href="" class="btn btn-outline btn-warning" id="reload">
                    <i class="fa-solid fa-arrows-rotate"></i>
                </a>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-8">
            <table class="table table-bordereless table-bordered table-hover table-sm"
                style="margin: 0 auto;width: 100%;">
                <thead class="vertical-align: middle; " class="cc">
                    <tr>
                        <th>N°</th>
                        <th>Docente</th>
                        <th>Libro</th>
                        <th>Fecha <br>Prestamo</br></th>
                        <th>Fecha <br>Devolución</br></th>
                        <th>Estado</th>
                        <th colspan="2">Acciones</th>
                    </tr>
                </thead>
                <tbody style="vertical-align: middle;" class="cc">
                    <?php if (is_array($dataPrestamos) && $dataPrestamos): ?>
                        <?php foreach ($dataPrestamos as $result): ?>
                            <tr>
                                <td><?php echo $cont += 1; ?></td>
                                <td>
                                    <?php
                                    echo buscavalor("docentes", "CONCAT(nombres, ' ' , apellidos)", "idusuario= '" . $result['idusuario'] . "'");
                                    ?>
                                </td>
                                <td><?php echo buscavalor("libros", "titulo", "idlibro='" . $result['idlibro'] . "'"); ?></td>
                                <td><?php echo $result['fechaprestamo']; ?></td>
                                <td><?php echo $result['fecharetorno']; ?></td>
                                <td><?php echo ($result['estado'] == 1) ? 'En Prestamo' : 'Finalizado'; ?></td>
                                <td>
                                    <a href="" class="btn btn-sm btn-success BtnUpdatePrestamos" idprestamo="<?php echo $result['idprestamo']; ?>">
                                        <i class="fa-solid fa-pen"></i>
                                    </a>
                                </td>
                                <td>
                                    <a href="" class="btn btn-sm btn-danger BtnDeletePrestamos" idprestamo="<?php echo $result['idprestamo']; ?>">
                                        <i class="fa-solid fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8">
                                <b>No se encontran prestamos para la fecha solicitada: <?php echo isset($_REQUEST['valor']) ? $_REQUEST['valor'] : $fechaActual; ?></b>
                            </td>
                        </tr>
                    <?php endif ?>
                </tbody>
            </table>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <b>En Prestamo | Finalizados</b>
                </div>
                <div class="card-body">
                    <p><b>En Prestamo: </b><?= $p_activos; ?></p>
                    <p><b>Finalizados: </b><?= $p_finalizados; ?></p>
                </div>
            </div>
            <hr>
            <div class="card">
                <div class="card-header">
                    <b>Total</b>
                </div>
                <div class="card-body">
                    <p><b>Total: </b><?= $total; ?></p>
                </div>
            </div>
        </div>
    </div>
</div>
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
<script>
    $(document).ready(function() {
        //reload
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
        $("#busca-prestamo").click(function() {
            let valor = $('[name="valor"]').val();
            if (valor == "" || valor == null) {
                alertify.alert("Buscar Prestamo", "Por favor ingrese una fecha para buscar.");
            } else {
                $.post("views/prestamos/principal.php", {
                    valor: valor
                }, function(html) {
                    $("#contenido-principal").html(html);
                });
            }
            return false;
        });

        //Modal Nuevo Prestamo
        $("#new-prestamos").click(function(e) {
            e.preventDefault();
            $("#ModalNP").modal('show');
            $("#ModalDNP").load("./views/prestamos/form_insert.php");
            return false;
        });

        //Modal Editar Prestamo
        $(".BtnUpdatePrestamos").click(function() {
            let idprestamo = $(this).attr('idprestamo');
            $("#ModalEP").modal('show');
            $("#ModalDEP").load("./views/prestamos/form_update.php?idprestamo=" + idprestamo);
            return false;
        });

        //Boton de actualizar estado de Prestamo
        $(".BtnBAPrestamos").click(function() {
            let idprestamo, valor;
            idprestamo = $(this).attr('idprestamo');
            valor = $(this).attr('valor');
            $("#PanelPrestamos").load('./views/prestamos/estado.php?idprestamo=' + idprestamo + '&valor=' + valor);
            return false;
        });

        //Boton para eliminar Prestamo
        $(".BtnDeletePrestamos").click(function() {
            let idprestamo = $(this).attr('idprestamo');
            alertify.confirm('Eliminar Usuario', '¿Seguro de eliminar este usuario?',
                function() {
                    $("#PanelPrestamos").load('./views/prestamos/del.php?idprestamo=' + idprestamo);
                },
                function() {
                    alertify.error('Cancelado');
                });
            return false;
        });

        // // Generar excel
        $("#excel-prestamos").click(function() {
            let fecha = "<?= (isset($_REQUEST['valor'])) ? $_REQUEST['valor'] : $fechaActual; ?>";
            let url = "./views/prestamos/excel.php";
            if (fecha !=="") {
                url += "?valor=" + encodeURIComponent(fecha);
            }
            window.location.href = url;
            return false;
        });
    });
</script>