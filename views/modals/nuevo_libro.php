<!-- Modal Nuevo Libro-->
<form id="New-Libro">
    <div class="modal fade" id="ModalNL" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">
                        Nuevo Libro
                    </h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="ModalDNL"></div>
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
        //Guardar Nuevo Libro
        $("#New-Libro").submit(function(e) {
            e.preventDefault();
            let titulo, isbn, autor, ejemplares, idgenero;
            isbn = $("input[name='isbn']").val();
            titulo = $("input[name='titulo']").val();
            autor = $("textarea[name='autor']").val();
            ejemplares = $("input[name='ejemplares']").val();
            idgenero = $("select[name='idgenero']").val();
            idubicacion = $("select[name='idubicacion']").val();

            if (isbn === "") {
                alertify.alert("Registro de Nuevo Libro", "Favor de  ingresar el isbn..");
                $("[name='isbn']").focus();
                return false;
            }

            if (titulo === "") {
                alertify.alert("Registro de Nuevo Libro", "Favor de  ingresar el nombre del libro..");
                $("[name='titulo']").focus();
                return false;
            }

            if (autor === "") {
                alertify.alert("Registro de Nuevo Libro", "Favor de  ingresar el nombre del autor..");
                $("[name='autor']").focus();
                return false;
            }

            if (ejemplares === "") {
                alertify.alert("Registro de Nuevo Libro", "Favor de  ingresar el numero de ejemplares..");
                $("[name='ejemplares']").focus();
                return false;
            }

            if (idgenero === "" || idgenero === "0" || idgenero === null) {
                alertify.alert("Registro de Nuevo Libro", "Seleccione un genero.", function() {
                    $("[name='idgenero']").focus();
                });
                return false;
            }
            if (idubicacion === "" || idubicacion === "0" || idubicacion === null) {
                alertify.alert("Registro de Nuevo Libro", "Seleccione una ubicación.", function() {
                    $("[name='idubicacion']").focus();
                });
                return false;
            }

            let formData = new FormData(this);
            formData.append("dato", "valor");

            $.ajax({
                url: "./views/libros/insert.php",
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                success: function(result) {
                    $("#ModalNL").modal('hide');
                    $("#PanelLibros").html(result);
                },
                error: function() {
                    $("#PanelLibros").html('<p><b style="text-align:center;color:red;">Error en el servidor intente nuevamente...</b></p>');
                }
            });
        });
    });
</script>