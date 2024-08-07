<?php
session_start();
include "../conexion.php";
$id_user = $_SESSION['idUser'];
$permiso = "clientes";
$sql = mysqli_query($conexion, "SELECT p.*, d.* FROM permisos p INNER JOIN detalle_permisos d ON p.id = d.id_permiso WHERE d.id_usuario = $id_user AND p.nombre = '$permiso'");
$existe = mysqli_fetch_all($sql);
if (empty($existe) && $id_user != 1) {
    header('Location: permisos.php');
}

if (!empty($_POST)) {
    $alert = "";
    if (empty($_POST['idcliente']) || empty($_POST['nombre'])) {
        $alert = '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                        Todo los campos son obligatorio
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>';
    } else {
        date_default_timezone_set('America/Mexico_City');
        $id = $_POST['id'];
        $idcliente = $_POST['idcliente'];
        $nombre = $_POST['nombre'];
        $mes_registro = date("Y-m-d");
        $mes_vencimiento = new DateTime();
        $result = 0;

        if (empty($id)) {

            if ($idcliente) {
                $query = mysqli_query($conexion, "SELECT * FROM cliente where idcliente = $idcliente");
                $result = mysqli_fetch_array($query);
                if ($result != NULL) {
                    $alert = '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                    El ID del Socio Ya Existe Utiliza Otro
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>';
                } else {
                    $periodo = $_POST['periodo'];
                    //echo "Ha seleccionado: " . htmlspecialchars($periodo);
                    if (htmlspecialchars($periodo) == "semana") {
                        $mes_vencimiento->modify('+7 days');
                        $fechaVencimiento = $mes_vencimiento->format('Y-m-d');
                        $query_insert = mysqli_query($conexion, "INSERT INTO cliente(idcliente,nombre,mes_registro,mes_vencimiento,huella) values ('$idcliente','$nombre', '$mes_registro', '$fechaVencimiento', '')");
                    } else if (htmlspecialchars($periodo) == "visita") {
                        $fechaVencimiento = $mes_vencimiento->format('Y-m-d');
                        $query_insert = mysqli_query($conexion, "INSERT INTO cliente(idcliente,nombre,mes_registro,mes_vencimiento,huella) values ('$idcliente','$nombre', '$mes_registro', '$fechaVencimiento', '')");
                    } else if (htmlspecialchars($periodo) == "quincena") {
                        $mes_vencimiento->modify('+14 days');
                        $fechaVencimiento = $mes_vencimiento->format('Y-m-d');
                        $query_insert = mysqli_query($conexion, "INSERT INTO cliente(idcliente,nombre,mes_registro,mes_vencimiento,huella) values ('$idcliente','$nombre', '$mes_registro', '$fechaVencimiento', '')");
                    } else if (htmlspecialchars($periodo) == "mensualidad") {
                        $mes_vencimiento->modify('+1 month');
                        $fechaVencimiento = $mes_vencimiento->format('Y-m-d');
                        var_dump($fechaVencimiento);
                        $query_insert = mysqli_query($conexion, "INSERT INTO cliente(idcliente,nombre,mes_registro,mes_vencimiento,huella) values ('$idcliente','$nombre', '$mes_registro', '$fechaVencimiento', '')");
                    } else if (htmlspecialchars($periodo) == "anualidad") {
                        $mes_vencimiento->modify('+1 year');
                        $fechaVencimiento = $mes_vencimiento->format('Y-m-d');
                        $query_insert = mysqli_query($conexion, "INSERT INTO cliente(idcliente,nombre,mes_registro,mes_vencimiento,huella) values ('$idcliente','$nombre', '$mes_registro', '$fechaVencimiento', '')");
                    }
                    /*var_dump( $fechaVencimiento);
                    $query_insert = mysqli_query($conexion, "INSERT INTO cliente(idcliente,nombre,mes_registro,mes_vencimiento,huella) values ('$idcliente','$nombre', '$mes_registro', '$fechaVencimiento', '')");
*/
                    if ($query_insert) {
                        $alert = '<div class="alert alert-success alert-dismissible fade show" role="alert">
                            Cliente registrado
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>';
                    } else {
                        $alert = '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                            Error al registrar
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>';
                    }
                }
            }
        } else {

            $sql_update = mysqli_query($conexion, "UPDATE cliente SET  idcliente = '$idcliente' , nombre = '$nombre', mes_registro = '$mes_registro', mes_vencimiento = '$mes_vencimiento' WHERE id = '$id'");

            if ($sql_update) {
                $alert = '<div class="alert alert-success alert-dismissible fade show" role="alert">
                            Cliente Modificado
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>';
            } else {
                $alert = '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                            Error al modificar
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>';
            }
        }
    }
    mysqli_close($conexion);
}
include_once "includes/header.php";
?>

<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-md-12">
                <?php echo (isset($alert)) ? $alert : ''; ?>
                <form action="" method="post" autocomplete="off" id="formulario">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="nombre" class="text-dark font-weight-bold">ID Socio</label>
                                <input type="text" name="idcliente" id="idcliente" class="form-control">
                                <input type="hidden" name="id" id="id">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="telefono" class="text-dark font-weight-bold">Nombre</label>
                                <br>
                                <input type="text" placeholder="Ingrese Nombre" name="nombre" id="nombre" class="form-control">
                            </div>

                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <p for="periodo">Seleccione un período:</p>
                                <select class="custom-select" id="periodo" name="periodo">
                                    <option value="visita">Visita</option>
                                    <option value="semana">Semana</option>
                                    <option value="quincena">Quincena</option>
                                    <option value="mensualidad">Mensualidad</option>
                                    <option value="anualidad">Anualidad</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4 mt-3">
                            <input type="submit" value="Registrar" class="btn btn-primary" id="btnAccion">
                            <input type="button" value="Limpiar" class="btn btn-success" id="btnNuevo" onclick="limpiar()">
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-md-12">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered" id="tbl">
                        <thead class="thead-dark">
                            <tr>

                                <th>ID Socio</th>
                                <th>Nombre</th>
                                <th>Fecha Registro</th>
                                <th>Fecha Vencimiento</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            include "../conexion.php";

                            $query = mysqli_query($conexion, "SELECT * FROM cliente");
                            $result = mysqli_num_rows($query);
                            if ($result > 0) {
                                while ($data = mysqli_fetch_assoc($query)) { ?>
                                    <tr>

                                        <td><?php echo $data['idcliente']; ?></td>
                                        <td><?php echo $data['nombre']; ?></td>
                                        <td><?php echo $data['mes_registro']; ?></td>
                                        <td><?php echo $data['mes_vencimiento']; ?></td>
                                        <td>
                                            <a href="#" onclick="editarCliente(<?php echo $data['id']; ?>)" class="btn btn-primary"><i class='fas fa-edit'></i></a>
                                            <form action="eliminar_cliente.php?id=<?php echo $data['id']; ?>" method="post" class="confirmar d-inline">
                                                <button class="btn btn-danger" type="submit"><i class='fas fa-trash-alt'></i> </button>
                                            </form>
                                        </td>
                                    </tr>
                            <?php }
                            } ?>
                        </tbody>

                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include_once "includes/footer.php"; ?>