<!-- Modal Nuevo Usuario-->
<form id="New-Docente">
    <div class="modal fade" id="ModalND" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">
                        Nuevo Usuario
                    </h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="ModalDND"></div>
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
        $("#New-Docente").submit(function(e) {
            e.preventDefault();
            let nombres, apellidos, email, dui, usuario, passw, idusuario;
            nombres = $("input[name='nombres']").val();
            apellidos = $("input[name='apellidos']").val();
            dui = $("input[name='dui']").val();
            usuario = $("input[name='usuario']").val();
            passw = $("input[name='passw']").val();
            email = $("textarea[name='email']").val();
            idusuario = $("select[name='idusuario']").val();

            if (nombres === "") {
                alertify.alert("Registro de Nuevo Docentes", "Favor de  ingresar el nombre de Docente..");
                $("[name='nombres']").focus();
                return false;
            }

            if (apellidos === "") {
                alertify.alert("Registro de Nuevo Docentes", "Favor de  ingresar el nombre de Docente..");
                $("[name='apellidos']").focus();
                return false;
            }

            if (email === "") {
                alertify.alert("Registro de Nuevo Docentes", "Favor de  ingresar el nombre de Docente..");
                $("[name='email']").focus();
                return false;
            }

            if (!email.includes("@")) {
                alertify.alert("Registro de Nuevo Docentes", "El correo debe incluir '@'.", function() {
                    $("[name='email']").focus();
                });
                return false;
            }

            const allowed = /^[^\s@]+@(gmail\.com|hotmail\.com|yahoo\.com|outlook\.com|live\.com|uls\.edu\.sv)$/i;

            if (!allowed.test(email.trim())) {
                alertify.alert(
                    "Registro de Nuevo Docente",
                    "Formato de correo no permitido. Usa: @gmail.com, @hotmail.com, @yahoo.com, @outlook.com o @live.com o @uls.edu.sv",
                    function() {
                        $("[name='email']").focus();
                    }
                );
                return false;
            }

            if (idusuario === "" || idusuario === "0" || idusuario === null) {
                alertify.alert("Registro de Nuevo Docentes", "Seleccione un usuario.", function() {
                    $("[name='idusuario']").focus();
                });
                return false;
            }

            let formData = new FormData(this);
            formData.append("dato", "valor");

            $.ajax({
                url: "./views/docentes/insert.php",
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                success: function(result) {
                    $("#ModalND").modal('hide');
                    $("#PanelDocentes").html(result);
                },
                error: function() {
                    $("#PanelDocentes").html('<p><b style="text-align:center;color:red;">Error en el servidor intente nuevamente...</b></p>');
                }
            });
        });
    });
</script>