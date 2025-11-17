<div class="contenedor">
    <div class="parte ocultar-en-pequeño">
        <div class="cc">
            <h4>Sistema de Gestión Global de Archivos (SGGA)</h4>
            <img src="./public/img/Brasil.jpg" alt="Logo SGGA" class="img-fluid" width="250px">
        </div>
    </div>
    <div class="parte">
        <div class="cc">
            <div class="ocultar-en-grande">
                <img src="./public/img/Brasil.jpg" alt="Logo SGGA" class="img-fluid" width="200px">
            </div>
            <div class="ocultar-en-pequeño">
                
            </div>
            <h4><b>Iniciar Sesión</b></h4>
            <hr>
            <form id="form-login">
                <div class="input-group mb-3">
                    <span class="input-group-text" id="basic-addon1">
                        <i class="fa-regular fa-circle-user"></i>
                    </span>
                    <input type="text" name="usuario" id="usuario" class="form-control" placeholder="Ingrese Usuario ó Correo" required>
                </div>
                <div class="input-group mb-3">
                    <span class="input-group-text" id="basic-addon1">
                        <i class="fa-solid fa-key"></i>
                    </span>
                    <input type="password" name="password" id="password" class="form-control" placeholder="Ingrese Contraseña" required>
                </div>
                <div class="input-group mb-3 justify-content-center">
                    <button class="btn btn-primary btn-sm">
                        <b>Acceder</b> <i class="fa-solid fa-right-to-bracket"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        $("#form-login").submit(function(e) {
            e.preventDefault();
            let formData = new FormData(this);
            formData.append('dato', 'valor');

            $.ajax({
                url: "controllers/LoginController.php",
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    $("#principal").html(response);
                },
                error: function(xhr, status, error) {
                    alertify.error("Error al iniciar sesión: " + error);
                    $("#principal").html('<p><b style="color:red;">Error en el servidor</b></p>');
                }
            });
        });
    });
</script>