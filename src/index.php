<?php
require "../conexion.php";
$usuarios = mysqli_query($conexion, "SELECT * FROM usuario");
$total['usuarios'] = mysqli_num_rows($usuarios);
$clientes = mysqli_query($conexion, "SELECT * FROM cliente");
$total['clientes'] = mysqli_num_rows($clientes);
$productos = mysqli_query($conexion, "SELECT * FROM producto");
$total['productos'] = mysqli_num_rows($productos);
$ventas = mysqli_query($conexion, "SELECT * FROM ventas WHERE fecha > CURDATE()");
$total['ventas'] = mysqli_num_rows($ventas);
session_start();
include_once "includes/header.php";
?>
<!-- Content Row -->
<div class="row">
    <img src="../assets/img/logo-punto-fitness-blanco-login.png" alt="" class="fondo">
</div>

<style>
    .fondo {
       
        height: 890px;
        /* Hace que la imagen abarque el 100% de la altura de la pantalla */
        width: 1600px;
        /* Hace que la imagen abarque el 100% del ancho de la pantalla */
    }
</style>

<?php include_once "includes/footer.php"; ?>