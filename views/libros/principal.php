<?php
include_once '../../models/conexion.php';
include_once '../../models/funciones.php';
include_once '../../controllers/funciones.php';
include_once '../modals/editar_libro.php';
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

if (isset($_REQUEST['libro'])) {
    $libro = $_REQUEST['libro'];
    $query = "SELECT * FROM libros WHERE titulo LIKE '%$libro%' || autor LIKE '%$libro%'";

    $dataLibros = CRUD("SELECT * FROM libros WHERE titulo LIKE '%$libro%' || autor LIKE '%$libro%' LIMIT $inicio,$registros", "s");
} else {
    $query = 'SELECT * FROM libros';
    $dataLibros = CRUD("SELECT * FROM libros LIMIT $inicio,$registros", "s");
}

$num_registros = CountReg($query);
$paginas = ceil($num_registros / $registros);
?>
<style>
    /* Estado deshabilitado visual */
    .BtnDeleteLibrosOff.is-disabled {
        position: relative;
        /* Necesario para posicionar el overlay */
        opacity: 0.6;
        /* Look de deshabilitado */
    }

    /* El overlay cubre todo el botón: evita el click y muestra cursor "prohibido" */
    .BtnDeleteLibrosOff.is-disabled::after {
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
    .BtnDeleteLibrosOff.is-disabled i {
        filter: grayscale(1);
        opacity: 0.8;
    }

    /* Mejoras para la tabla */
    .table-custom {
        width: 95% !important;
        margin: 0 auto;
    }
    .table-custom th {
        white-space: nowrap;
        padding: 12px 8px;
    }
    .table-custom td {
        padding: 10px 6px;
        vertical-align: middle;
    }
    .acciones-column {
        min-width: 140px;
        white-space: nowrap;
    }
    .titulo-column {
        min-width: 180px;
        max-width: 200px;
    }
    .autor-column {
        min-width: 150px;
        max-width: 180px;
    }
    .ubicacion-column {
        min-width: 120px;
    }
</style>
<div class="table-responsive" id="PanelLibros">
    <div style="margin: 0 auto; width: 100%;" class="barra">
        <a href="" class="btn btn-sm btn-primary" id="new-libro">
            <i class="fa-solid fa-circle-plus"></i>
        </a>
        <div class="acciones">
            <div class="input-group mb-3">
                <input type="text" class="form-control" placeholder="Buscar Libro | autor" name="libro">
                <button class="btn btn-outline-secondary" type="button" id="busca-libro">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </button>
                <a href="" class="btn btn-outline btn-warning" id="reload">
                    <i class="fa-solid fa-arrows-rotate"></i>
                </a>
            </div>
        </div>
    </div>
    <table class="table table-borderless table-bordered table-hover table-sm table-custom">
        <thead style="vertical-align: middle;" class="cc">
            <tr>
                <th width="4%">N°</th>
                <th width="10%">ISBN</th>
                <th class="titulo-column">Nombre</th>
                <th class="autor-column">Autor</th>
                <th width="12%">Género</th>
                <th width="8%">Ejemplares</th>
                <th class="ubicacion-column">Ubicación</th>
                <th width="10%">Estado</th>
                <th class="acciones-column" colspan="2">Acciones</th>
            </tr>
        </thead>
        <tbody style="vertical-align: middle;" class="cc">
            <?php foreach ($dataLibros as $result): ?>
                <?php
                $query = "SELECT * FROM prestamos WHERE idlibro ='" . $result['idlibro'] . "'";
                $genero = CRUD("SELECT * FROM generos WHERE idgenero='" . $result['idgenero'] . "'", "s");
                $nombreGenero = (count($genero) > 0) ? $genero[0]['genero'] : "Sin género";
                $ubicacion = CRUD("SELECT * FROM ubicaciones WHERE idubicacion='" . $result['idubicacion'] . "'", "s");
                $nombreUbicacion = (count($ubicacion) > 0) ? $ubicacion[0]['nombre'] : "Sin ubicación";
                $contPrestamos = CountReg($query);
                ?>
                <tr>
                    <td><?php echo $cont += 1; ?></td>
                    <td><small><?php echo $result['isbn']; ?></small></td>
                    <td class="titulo-column" title="<?php echo $result['titulo']; ?>">
                        <div style="max-width: 180px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                            <?php echo $result['titulo']; ?>
                        </div>
                    </td>
                    <td class="autor-column" title="<?php echo $result['autor']; ?>">
                        <div style="max-width: 160px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                            <?php echo $result['autor']; ?>
                        </div>
                    </td>
                    <td><small><?php echo $nombreGenero; ?></small></td>
                    <td class="text-center"><?php echo $result['ejemplares']; ?></td>
                    <td class="ubicacion-column"><small><?php echo $nombreUbicacion; ?></small></td>
                    <td>
                        <span class="badge <?php echo ($result['estado'] == 1) ? 'bg-success' : 'bg-danger'; ?>">
                            <?php echo ($result['estado'] == 1) ? 'Disponible' : 'No disponible'; ?>
                        </span>
                    </td>
                    <td class="acciones-column">
                        <a href="" class="btn btn-sm btn-success BtnUpdateLibros" idlibro="<?php echo $result['idlibro']; ?>" title="Editar">
                            <i class="fa-solid fa-pen"></i>
                        </a>
                    </td>
                    <td class="acciones-column">
                        <?php if ($contPrestamos == 0): ?>
                            <a href="" class="btn btn-sm btn-danger BtnDeleteLibro" idlibro="<?php echo $result['idlibro']; ?>" title="Eliminar">
                                <i class="fa-solid fa-trash-can"></i>
                            </a>
                        <?php else: ?>
                            <a href="#"
                                class="btn btn-sm btn-danger BtnDeleteLibrosOff is-disabled"
                                idlibro="<?php echo $result['idlibro']; ?>"
                                aria-disabled="true"
                                tabindex="-1"
                                title="No se puede eliminar - Tiene préstamos asociados">
                                <i class="fa-solid fa-trash-can"></i>
                            </a>
                        <?php endif ?>
                        <?php if ($result['estado'] == 1): ?>
                            <a href="" class="btn btn-sm btn-warning BtnBALibro" valor="2" idlibro="<?php echo $result['idlibro']; ?>" title="Desactivar">
                                <i class="fa-solid fa-ban"></i>
                            </a>
                        <?php else: ?>
                            <a href="" class="btn btn-sm btn-info BtnBALibro" valor="1" idlibro="<?php echo $result['idlibro']; ?>" title="Activar">
                                <i class="fa-solid fa-book"></i>
                            </a>
                        <?php endif ?>
                    </td>
                </tr>
            <?php endforeach ?>
        </tbody>
    </table>
    <div class="align-items-center text-center mt-3">
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
        //reload
        $("#reload").click(function() {
            $("#contenido-principal").load("views/libros/principal.php");
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
            $("#contenido-principal").load("views/libros/principal.php?num=" + num + "&num_reg=" + reg);
            return false;
        });

        // Busca Usuario por Libro ó autor
        $("#busca-libro").click(function() {
            let libro = $('[name="libro"]').val();
            $.post("views/libros/principal.php", {
                libro: libro,
            }, function(html) {
                $("#contenido-principal").html(html);
            });
            return false;
        });

        //Modal Nuevo Libro
        $("#new-libro").click(function() {
            $("#ModalNL").modal('show');
            $("#ModalDNL").load('views/libros/form_insert.php');
            return false;
        });

        //Modal Editar Usuario
        $(".BtnUpdateLibros").click(function() {
            let idlibro = $(this).attr('idlibro');
            $("#ModalEL").modal('show');
            $("#ModalDEL").load("./views/libros/form_update.php?idlibro=" + idlibro);
            return false;
        });

        //Boton de actualizar estado de usuario
        $(".BtnBALibro").click(function() {
            let idlibro = $(this).attr('idlibro');
            let valor = $(this).attr('valor');
            $("#PanelLibros").load('./views/libros/estado.php?idlibro=' + idlibro + '&valor=' + valor);
            return false;
        });

        //Boton para eliminar usuario si no tiee registros de prestamos
        $(".BtnDeleteLibro").click(function() {
            let idlibro = $(this).attr('idlibro');
            alertify.confirm('Eliminar Usuario', '¿Seguro de eliminar este usuario?',
                function() {
                    $("#PanelLibros").load('./views/libros/del.php?idlibro=' + idlibro);
                },
                function() {
                    alertify.error('Cancelado');
                });
            return false;
        });
    });
</script>