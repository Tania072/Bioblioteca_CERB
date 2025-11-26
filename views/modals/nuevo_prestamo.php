<!-- Modal Nuevo Prestamo-->
<form id="New-Prestamo">
    <div class="modal fade" id="ModalNP" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">
                        Nuevo Prestamo
                    </h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="ModalDNP"></div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancelar</button>
                    <button class="btn btn-primary">Guardar</button>
                </div>
            </div>
        </div>
    </div>
</form>
<script>
    $(document).ready(function() {
        $("#New-Prestamo").submit(function(e) {
            e.preventDefault();

            let idusuario, idlibro, fechaprestamo, fechadevolucion;
            idusuario = $("select[name='idusuario']").val();
            idlibro = $("select[name='idlibro']").val();
            fechaprestamo = $("input[name='fechaprestamo']").val();
            fechadevolucion = $("input[name='fecharetorno']").val();

            if (idusuario === null) {
                alertify.alert("Registro de Nuevo Prestamo", "Favor de seleccionar un docente..");
                $("[name='idusuario']").focus();
                return false;
            }

            if (idlibro === null) {
                alertify.alert("Registro de Nuevo Prestamo", "Favor de seleccionar un libro..");
                $("[name='idlibro']").focus();
                return false;
            }

            if (fechaprestamo === null || fechaprestamo === "") {
                alertify.alert("Registro de Nuevo Prestamo", "Favor de seleccionar la fecha..");
                $("[name='fechaprestamo']").focus();
                return false;
            }

            let formData = new FormData(this);
            formData.append("dato", "valor");

            $.ajax({
                url: "./views/prestamos/insert.php",
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                success: function(result) {
                    $("#ModalNP").modal('hide');
                    $("#PanelPrestamos").html(result);
                },
                error: function() {
                    $("#PanelPrestamos").html('<p><b style="text-align:center;color:red;">Error en el servidor intente nuevamente...</b></p>');
                }
            });
        });
    });
</script>