<?php
require_once "../conexion.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $idProducto = $_POST['id'];
    $accion = $_POST['accion'];

    $query = mysqli_query($conexion, "SELECT existencia FROM producto WHERE codproducto = $idProducto");
    $resultado = mysqli_fetch_assoc($query);
    $stockActual = $resultado['existencia'];

    if ($accion == 'mas') {
        $nuevoStock = $stockActual + 1;
    } elseif ($accion == 'menos') {
        if ($stockActual > 0) {
            $nuevoStock = $stockActual - 1;
        } else {
            $nuevoStock = 0; // No deja stock negativo
        }
    }

    // Actualizar el stock en la base de datos
    $queryActualizar = mysqli_query($conexion, "UPDATE producto SET existencia = $nuevoStock WHERE codproducto = $idProducto");

    if ($queryActualizar) {
        $response['nuevoStock'] = $nuevoStock;
        echo json_encode($response);
    } else {
        echo json_encode(array('error' => 'No se pudo actualizar el stock'));
    }
} else {
    echo json_encode(array('error' => 'Método no permitido'));
}
?>
