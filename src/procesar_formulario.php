<?php
session_start();
require("../conexion.php");
$nomsocio=$_POST['nom_cliente'];
$sql = mysqli_query($conexion, "SELECT * FROM cliente c where idcliente = $nomsocio" );
$existe = mysqli_fetch_all($sql);
if (empty($existe) && $id_user != 1) {
    header('Location: permisos.php');
}
?>