<?php
session_start();
require("../conexion.php");
$sql = mysqli_query($conexion, "SELECT * FROM cliente c where idcliente = 1");
$existe = mysqli_fetch_all($sql);
if (empty($existe) && $id_user != 1) {
    header('Location: permisos.php');
}
include_once "includes/header.php";
?>
<div class="row">
    <div class="col-lg-12">
        <div class="form-group">
            <h4 class="text-center">Datos del Socio</h4>
        </div>
        <div class="card">
            <div class="card-body">
                <form method="post">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <input type="hidden" id="idcliente" value="1" name="idcliente" required>
                                <label>Nombre</label>
                                <input type="text" name="nom_cliente" id="nom_cliente" class="form-control" placeholder="Ingrese nombre del cliente" required>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-hover" id="tblDetalle">
                <thead class="thead-dark">
                    <tr>
                        <th>Id Cliente</th>
                        <th>Nombre</th>
                        <th>Fecha Registro</th>
                        <th>Fecha Vencimiento</th>
                    </tr>
                </thead>
            </table>

        </div>
    </div>
    <div class="col-md-6">
        <a href="#" class="btn btn-primary" id="btn_generar"><i class="fas fa-save"></i> Buscar Cliente</a>
    </div>

</div>
<?php include_once "includes/footer.php"; ?>