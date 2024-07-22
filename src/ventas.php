<?php
session_start();
require("../conexion.php");
$id_user = $_SESSION['idUser'];
$permiso = "nueva_venta";
$sql = mysqli_query($conexion, "SELECT p.*, d.* FROM permisos p INNER JOIN detalle_permisos d ON p.id = d.id_permiso WHERE d.id_usuario = $id_user AND p.nombre = '$permiso'");
$existe = mysqli_fetch_all($sql);
if (empty($existe) && $id_user != 1) {
    header('Location: permisos.php');
}
include_once "includes/header.php";

$id = $_POST['id'];
?>
<div class="row">
    <div class="col-lg-12">
        <div class="form-group">
            <h4 class="text-center">Datos del Cliente</h4>
        </div>
        <div class="card">
            <div class="card-header bg-primary text-white text-center">
                Buscar socio
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-5">
                        <div class="form-group">
                            <label for="id">Código del socio</label>
                            <input id="id" class="form-control" type="text" name="id" placeholder="Ingresa el código de socio" onkeydown="if(event.keyCode == 13) { buscarSocio(); }">

                        </div>
                    </div>
                    <div class="col-lg-2">
                        <div class="form-group">
                            <label for="huella">Huella</label>
                            <input id="huella" class="form-control" type="text" name="huella" placeholder="Huella">
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-hover" id="tblDetalle">
                <thead class="thead-dark">
                    <tr>
                        <th>IdSocio</th>
                        <th>Nombre</th>
                        <th>Fecha de Vencimiento</th>
                    </tr>
                </thead>
                <tbody>
                    
                </tbody>
            </table>

        </div>
    </div>

</div>
<?php include_once "includes/footer.php"; ?>

<script>
function buscarSocio() {
    var codigoSocio = document.getElementById('id').value.trim();

    if (codigoSocio !== '') {
        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'buscar_socio.php', true);
        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        xhr.onreadystatechange = function() {
            if (xhr.readyState == 4 && xhr.status == 200) {
                document.getElementById('tblDetalle').getElementsByTagName('tbody')[0].innerHTML = xhr.responseText;
            }
        };
        xhr.send('codigo=' + codigoSocio);
    }
}
</script>
