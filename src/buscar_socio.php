<?php
include "../conexion.php";

$codigoSocio = mysqli_real_escape_string($conexion, $_POST['codigo']);

$query = mysqli_query($conexion, "SELECT * FROM cliente WHERE idcliente = '$codigoSocio'");
$result = mysqli_num_rows($query);

if ($result > 0) {
    while ($data = mysqli_fetch_assoc($query)) {
        echo '<tr>';
        echo '<td>' . $data['idcliente'] . '</td>';
        echo '<td>' . $data['nombre'] . '</td>';
        echo '<td>' . $data['mes_vencimiento'] . '</td>';
        echo '</tr>';
    }
} else {
    echo '<tr><td colspan="3">No se encontraron resultados</td></tr>';
}
?>
