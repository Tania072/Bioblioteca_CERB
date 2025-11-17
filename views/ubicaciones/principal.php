<?php
include_once '../../models/conexion.php';
include_once '../../models/funciones.php';
include_once '../../controllers/funciones.php';
@session_start();
$cont = 0;
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
if (isset($_REQUEST['valor'])) {
    $valor = $_REQUEST['valor'];
    $query = "SELECT * FROM ubicaciones WHERE nombre LIKE '%$valor%' OR descripcion LIKE '%$valor%'";

    $dataUbicaciones = CRUD("SELECT * FROM ubicaciones WHERE nombre LIKE '%$valor%' OR descripcion LIKE '%$valor%' LIMIT $inicio,$registros", "s");
} else {
    $query = 'SELECT * FROM ubicaciones';
    $dataUbicaciones = CRUD("SELECT * FROM ubicaciones LIMIT $inicio,$registros", "s");
}

$num_registros = CountReg($query);
$paginas = ceil($num_registros / $registros);
?>
<style>
    /* Estado deshabilitado visual */
    .BtnDeleteUbicacionOff.is-disabled {
        position: relative;
        /* Necesario para posicionar el overlay */
        opacity: 0.6;
        /* Look de deshabilitado */
    }

    /* El overlay cubre todo el botón: evita el click y muestra cursor "prohibido" */
    .BtnDeleteUbicacionOff.is-disabled::after {
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
    .BtnDeleteUbicacionOff.is-disabled i {
        filter: grayscale(1);
        opacity: 0.8;
    }
</style>
<div class="table-responsive" id="PanelUbicaciones">
    <div style="margin: 0 auto; width: 80%;" class="barra">

        <a href="" class="btn btn-sm btn-primary" id="new-ubicacion">
            <i class="fa-solid fa-location-dot"></i>
        </a>
        <div class="acciones">
            <div class="input-group mb-3">
                <input type="text" class="form-control" placeholder="Buscar Ubicación | Nombre" name="valor">
                <button class="btn btn-outline-secondary" type="button" id="busca-ubicacion">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </button>
                <a href="" class="btn btn-outline btn-warning" id="reload">
                    <i class="fa-solid fa-arrows-rotate"></i>
                </a>
            </div>
        </div>
        <hr>
    </div>
    <table class="table table-borderless table-bordered table-hover table-sm" style="margin: 0 auto; width: 80%;">
        <thead style="vertical-align:middle" class="cc">
            <tr>
                <th>N</th>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Estado</th>
                <th colspan="2">Acciones</th>
            </tr>
        </thead>
        <tbody style="vertical-align:middle" class="cc">
            <?php foreach ($dataUbicaciones as $result): ?>
                <?php
                $query = "SELECT * FROM libros WHERE idubicacion='" . $result['idubicacion'] . "'";
                $contLibros = CountReg($query);
                ?>
                <tr>
                    <td><?php echo $cont += 1; ?></td>
                    <td><?php echo $result['nombre']; ?></td>
                    <td><?php echo $result['descripcion']; ?></td>
                    <td>
                        <?php
                        echo ($result['estado'] == 1) ? 'Activo' : 'Inactivo';
                        ?>
                    </td>

                    <td>
                        <a href="" class="btn btn-sm btn-success BtnUpdateUbicacion" idubicacion="<?php echo $result['idubicacion']; ?>">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </a>
                    </td>
                    <td>
                        <?php if ($contLibros == 0): ?>
                            <a href="" class="btn btn-sm btn-danger BtnDeleteUbicacion" idubicacion="<?php echo $result['idubicacion']; ?>">
                                <i class="fa-solid fa-trash-can"></i>
                            </a>
                        <?php else: ?>
                            <a href="#"
                                class="btn btn-sm btn-danger BtnDeleteUbicacionOff is-disabled"
                                idubicacion="<?php echo $result['idubicacion']; ?>"
                                aria-disabled="true"
                                tabindex="-1"
                                title="No se puede eliminar - Tiene libros asociados">
                                <i class="fa-solid fa-trash-can"></i>
                            </a>
                        <?php endif ?>
                        <?php if ($result["estado"] == 1): ?>
                            <a href="" class="btn btn-sm btn-warning BtnBAUbicacion" valor="0" idubicacion="<?php echo $result['idubicacion']; ?>">
                                <i class="fa-solid fa-toggle-on"></i>
                            </a>
                        <?php else: ?>
                            <a href="" class="btn btn-sm btn-info BtnBAUbicacion" valor="1" idubicacion="<?php echo $result['idubicacion']; ?>">
                                <i class="fa-solid fa-toggle-off"></i>
                            </a>
                        <?php endif ?>
                    </td>
                </tr>
            <?php endforeach ?>
        </tbody>
    </table>

    <!-- PAGINADO -->
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

<script>
    $(document).ready(function() {
        // reload
        $("#reload").click(function() {
            $("#contenido-principal").load("views/ubicaciones/principal.php");
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
            $("#contenido-principal").load("views/ubicaciones/principal.php?num=" + num + "&num_reg=" + reg);
            return false;
        });

        // Busca Ubicación por nombre o descripción
        $("#busca-ubicacion").click(function() {
            let valor = $('[name="valor"]').val();
            if (valor == "" || valor == null) {
                alertify.alert("Buscar Ubicación", "Ingrese un término de búsqueda");
            } else {
                $.post("views/ubicaciones/principal.php", {
                    valor: valor
                }, function(html) {
                    $("#contenido-principal").html(html);
                });
            }
            return false;
        });

        // Modal Nueva Ubicación
        $("#new-ubicacion").click(function() {
            $("#ModalNUB").modal('show');
            $("#ModalDNUB").load("./views/ubicaciones/form_insert.php");
            return false;
        });

        // Modal Editar Ubicación
        $(".BtnUpdateUbicacion").click(function() {
            let idubicacion = $(this).attr('idubicacion');
            $("#ModalEUB").modal('show');
            $("#ModalDEUB").load("./views/ubicaciones/form_update.php?idubicacion=" + idubicacion);
            return false;
        });

        // Boton de actualizar estado de Ubicación
        $(".BtnBAUbicacion").click(function() {
            let idubicacion, valor;
            idubicacion = $(this).attr('idubicacion');
            valor = $(this).attr('valor');
            $("#PanelUbicaciones").load('./views/ubicaciones/estado.php?idubicacion=' + idubicacion + '&valor=' + valor);
            return false;
        });

        // Boton para eliminar Ubicación 
        $(".BtnDeleteUbicacion").click(function() {
            let idubicacion = $(this).attr('idubicacion');
            alertify.confirm('Eliminar Ubicación', '¿Seguro/a que quiere eliminar esta ubicación?', function() {
                $("#PanelUbicaciones").load('./views/ubicaciones/del.php?idubicacion=' + idubicacion);
            }, function() {
                alertify.error('Acción Cancelada');
            });
            return false;
        });

    });
</script>