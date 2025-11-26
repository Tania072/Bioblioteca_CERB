<!-- Modal Solicitar Prestamo-->
<form id="FormSolicitarPrestamo">
    <div class="modal fade" id="ModalSP" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">
                        Solicitar Préstamo
                    </h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="ModalDSP">
                    <!-- Aquí se cargará el formulario -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Solicitar Préstamo</button>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
$(document).ready(function() {
    // Modal Solicitar Préstamo
    $(".BtnPrestarLibro").click(function() {
        let idlibro = $(this).attr('idlibro');
        $("#ModalSP").modal('show');
        $("#ModalDSP").load("./views/prestamos/form_solicitar.php?idlibro=" + idlibro);
        return false;
    });

    // Enviar formulario de solicitud
    $("#FormSolicitarPrestamo").submit(function(e) {
        e.preventDefault();

        let cantidad = $("input[name='cantidad']").val();
        let fecharetorno = $("input[name='fecharetorno']").val();

        if (cantidad === "" || cantidad === null || cantidad <= 0) {
            alertify.alert("Solicitar Préstamo", "Favor de ingresar una cantidad válida.");
            $("[name='cantidad']").focus();
            return false;
        }

        if (fecharetorno === "" || fecharetorno === null) {
            alertify.alert("Solicitar Préstamo", "Favor de seleccionar la fecha de devolución.");
            $("[name='fecharetorno']").focus();
            return false;
        }

        let formData = new FormData(this);

        $.ajax({
            url: "./views/prestamos/solicitar_insert.php",
            type: "POST",
            data: formData,
            contentType: false,
            processData: false,
            success: function(result) {
                $("#ModalSP").modal('hide');
                $("#contenido-principal").html(result);
            },
            error: function() {
                alertify.error("Error en el servidor intente nuevamente...");
            }
        });
    });
});
</script>