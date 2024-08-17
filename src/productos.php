<?php
session_start();
include "../conexion.php";
$id_user = $_SESSION['idUser'];
$permiso = "productos";
$sql = mysqli_query($conexion, "SELECT p.*, d.* FROM permisos p INNER JOIN detalle_permisos d ON p.id = d.id_permiso WHERE d.id_usuario = $id_user AND p.nombre = '$permiso'");
$existe = mysqli_fetch_all($sql);
if (empty($existe) && $id_user != 1) {
    header('Location: permisos.php');
}
if (!empty($_POST)) {
    $alert = "";
    if (empty($_POST['producto'])) {
        $alert = '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                        Todo los campos son obligatorios
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>';
    } else {
        $id = $_POST['codproducto'];
        $producto = $_POST['producto'];
        $precio = $_POST['precio'];
        $cantidad = $_POST['cantidad'];
        
        if (empty($id)) {
                $query_insert = mysqli_query($conexion, "INSERT INTO producto(descripcion,precio,existencia) values ('$producto', '$precio', '$cantidad')");
                if ($query_insert) {
                    $alert = '<div class="alert alert-success alert-dismissible fade show" role="alert">
                        Producto registrado
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>';
                } else {
                    $alert = '<div class="alert alert-danger" role="alert">
                    Error al registrar el producto
                  </div>';
                }
            }else{
                $query_update = mysqli_query($conexion, "UPDATE producto set descripcion = '$producto', precio = $precio, existencia = $cantidad where codproducto = $id");
                if ($query_update) {
                    $alert = '<div class="alert alert-success alert-dismissible fade show" role="alert">
                        Producto Actualizado con exito
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>';
                } else {
                    $alert = '<div class="alert alert-danger" role="alert">
                    Error al editar
                  </div>';
                }
            }
        }
    }
include_once "includes/header.php";
?>
<div class="card shadow-lg">
    <div class="card-body">
        <div class="row">
            <div class="col-md-12">
                <form action="" method="post" autocomplete="off" id="formulario">
                    <?php echo isset($alert) ? $alert : ''; ?>
                    <div class="row">
                        <div class="col-md-5">
                            <div class="form-group">
                                
                                <label for="producto" class=" text-dark font-weight-bold">Producto</label>
                                <input type="text" placeholder="Ingrese nombre del producto" name="producto" id="producto" class="form-control">
                                <input type="hidden" name="codproducto" id="codproducto">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="precio" class=" text-dark font-weight-bold">Precio</label>
                                <input type="text" onkeypress="return isNumberKey(event)" placeholder="Ingrese precio" class="form-control" name="precio" id="precio">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="cantidad" class=" text-dark font-weight-bold">Cantidad</label>
                                <input type="text" onkeypress="return isNumberKey(event)" placeholder="Ingrese cantidad" class="form-control" name="cantidad" id="cantidad">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <input type="submit" value="Registrar" class="btn btn-primary" id="btnAccion">
                            <input type="button" value="Limpiar" class="btn btn-success" id="btnNuevo" onclick="limpiar()">
                        </div>
                    </div>

                </form>
            </div>
        </div>
        <div class="col-md-12">
            <div class="table-responsive">
                <table class="table table-striped table-bordered" id="tbl">
                    <thead class="thead-dark">
                        <tr>
                            <th>Producto</th>
                            <th>Precio</th>
                            <th>Stock</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        include "../conexion.php";

                        $query = mysqli_query($conexion, "SELECT * FROM producto");
                        $result = mysqli_num_rows($query);
                        if ($result > 0) {
                            while ($data = mysqli_fetch_assoc($query)) { ?>
                                <tr>
                                    <td><?php echo $data['descripcion']; ?></td>
                                    <td><?php echo $data['precio']; ?></td>
                                    <td id="stock_<?php echo $data['codproducto']; ?>"><?php echo $data['existencia']; ?></td>
                                    <td>
                                        <button class="btn btn-success btn-sm" onclick="actualizarStock(<?php echo $data['codproducto']; ?>, 'mas')">+</button>
                                        <button class="btn btn-danger btn-sm" onclick="actualizarStock(<?php echo $data['codproducto']; ?>, 'menos')">-</button>

                                        <a href="#" onclick="editarProducto(<?php echo $data['codproducto']; ?>)" class="btn btn-primary btn-sm"><i class='fas fa-edit'></i></a>

                                        <form action="eliminar_producto.php?id=<?php echo $data['codproducto']; ?>" method="post" class="confirmar d-inline">
                                                <button class="btn btn-danger btn-sm" type="submit"><i class='fas fa-trash-alt'></i> </button>
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
<?php include_once "includes/footer.php"; ?>

<script>
function isNumberKey(evt) {
    var charCode = evt.which ? evt.which : evt.keyCode;
    return !(charCode < 48 || charCode > 57); // Permitir solo números
    }
    function actualizarStock(idProducto, accion) {
        $.ajax({
            url: 'actualizar_stock.php',
            type: 'POST',
            dataType: 'json',
            data: {
                id: idProducto,
                accion: accion
            },       success: function(response) {
                // Actualizar el stock en la tabla
                $('#stock_' + idProducto).text(response.nuevoStock);
            },
            error: function(error) {
                console.error('Error en la solicitud AJAX: ', error);
            }
        });
    }
     


</script>