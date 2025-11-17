<form id="New-Genero">
    <div class="modal fade" id="ModalNG" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">
                        Nuevo Libro
                    </h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="ModalDNG"></div>
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
        $("#New-Genero").submit(function(e) {
            e.preventDefault();
            let genero, descripcion;
            genero = $("input[name='genero']").val();
            descripcion = $("textarea[name='descripcion']").val();

            if (genero === "") {
                alertify.alert("Registro de Nuevo Genero", "Favor de  ingresar el genero ...");
                $("[name='genero']").focus();
                return false;
            }

            if (descripcion === "") {
                alertify.alert("Registro de Nuevo Genero", "Favor de  ingresar la descripcion ...");
                $("[name='descripcion']").focus();
                return false;
            }

            let formData = new FormData(this);
            formData.append("dato", "valor");

            $.ajax({
                url: "./views/generos/insert.php",
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                success: function(result) {
                    $("#ModalNG").modal('hide');
                    $("#PanelGeneros").html(result);
                },
                error: function() {
                    $("#PanelGeneros").html('<p><b style="text-align:center;color:red;">Error en el servidor intente nuevamente...</b></p>');
                }
            });
        });
    });
</script>