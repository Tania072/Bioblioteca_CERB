<?php
include_once '../../models/conexion.php';
include_once '../../models/funciones.php';
include_once '../../controllers/funciones.php';
include_once '../modals/nuevo_genero.php';
include_once '../modals/editar_genero.php';

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

if (isset($_REQUEST['genero'])) {
    $genero = $_REQUEST['genero'];
    $query = "SELECT * FROM generos WHERE genero LIKE '%$genero%' || descripcion LIKE '%$genero%'";

    $dataGeneros = CRUD("SELECT * FROM generos WHERE genero LIKE '%$genero%' || descripcion LIKE '%$genero%' LIMIT $inicio,$registros", "s");
} else {
    $query = 'SELECT * FROM generos';
    $dataGeneros = CRUD("SELECT * FROM generos LIMIT $inicio,$registros", "s");
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
</style>
<div class="table-responsive" id="PanelGeneros">
    <div style="margin: 0 auto; width: 100%;" class="barra">
        <a href="" class="btn btn-sm btn-primary" id="new-genero">
            <i class="fa-solid fa-circle-plus"></i>
        </a>
        <div class="acciones">
            <div class="input-group mb-3">
                <input type="text" class="form-control" placeholder="Buscar Genero" name="genero">
                <button class="btn btn-outline-secondary" type="button" id="busca-genero">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </button>
                <a href="" class="btn btn-outline btn-warning" id="reload">
                    <i class="fa-solid fa-arrows-rotate"></i>
                </a>
            </div>
        </div>
    </div>
    <table class="table table-bordereless table-bordered table-hover table-sm" style="margin: 0 auto;width: 80%;">
        <thead class="vertical-align: middle; " class="cc">
            <tr>
                <th>N°</th>
                <th>Genero</th>
                <th>Descripcion</th>
                <th colspan="2">Acciones</th>
            </tr>
        </thead>
        <tbody style="vertical-align: middle;" class="cc">
            <?php foreach ($dataGeneros as $result): ?>
                <?php
                $query = "SELECT * FROM libros WHERE idgenero ='" . $result['idgenero'] . "'";

                $contPrestamos = CountReg($query);
                ?>
                <tr>
                    <td><?php echo $cont += 1; ?></td>
                    <td><?php echo $result['genero']; ?></td>
                    <td><?php echo $result['descripcion']; ?></td>
                    <td>
                        <a href="" class="btn btn-sm btn-success BtnUpdateGeneros" idgenero="<?php echo $result['idgenero']; ?>">
                            <i class="fa-solid fa-pen"></i>
                        </a>
                    </td>
                    <td>
                        <?php if ($contPrestamos == 0): ?>
                            <a href="" class="btn btn-sm btn-danger BtnDeleteGenero" idgenero="<?php echo $result['idgenero']; ?>">
                                <i class="fa-solid fa-trash-can"></i>
                            </a>
                        <?php else: ?>
                            <a href="#"
                                class="btn btn-sm btn-danger BtnDeleteLibrosOff is-disabled"
                                idgenero="<?php echo $result['idgenero']; ?>"
                                aria-disabled="true"
                                tabindex="-1"
                                title="Acción deshabilitada">
                                <i class="fa-solid fa-trash-can"></i>
                            </a>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div class="align-items-center text-center">
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
            $("#contenido-principal").load("views/generos/principal.php");
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
            $("#contenido-principal").load("views/generos/principal.php?num=" + num + "&num_reg=" + reg);
            return false;
        });

        // Busca Usuario por genero ó descripcion
        $("#busca-genero").click(function() {
            let genero = $('[name="genero"]').val();
            $.post("views/generos/principal.php", {
                genero: genero,
            }, function(html) {
                $("#contenido-principal").html(html);
            });
            return false;
        });

        //Modal Nuevo Genero
        $("#new-genero").click(function() {
            $("#ModalNG").modal('show');
            $("#ModalDNG").load('views/generos/form_insert.php');
            return false;
        });

        //Modal Editar Genero
        $(".BtnUpdateGeneros").click(function() {
            let idgenero = $(this).attr('idgenero');
            $("#ModalEG").modal('show');
            $("#ModalDEG").load("./views/generos/form_update.php?idgenero=" + idgenero);
            return false;
        });

        //Boton para eliminar genero si no tiee registros de prestamos
        $(".BtnDeleteGenero").click(function() {
            let idgenero = $(this).attr('idgenero');
            alertify.confirm('Eliminar Genero', '¿Seguro de eliminar este genero?',
                function() {
                    $("#PanelGeneros").load('./views/generos/del.php?idgenero=' + idgenero);
                },
                function() {
                    alertify.error('Cancelado');
                });
            return false;
        });
    });
</script>