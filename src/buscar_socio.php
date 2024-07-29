<?php
include "../conexion.php";

$codigoSocio = mysqli_real_escape_string($conexion, $_POST['codigo']);

$stmt = $conexion->prepare("SELECT * FROM cliente WHERE idcliente = ?");
$stmt->bind_param("s", $codigoSocio);
$stmt->execute();
$result = $stmt->get_result();

$hoy = new DateTime();
$socioEstado = 'En Orden'; // Estado por defecto

$response = ['estado' => $socioEstado, 'rows' => []];

if ($result->num_rows > 0) {
    $rows = [];
    while ($data = $result->fetch_assoc()) {
        $mesVencimiento = new DateTime($data['mes_vencimiento']);

        if ($mesVencimiento < $hoy) {
            $socioEstado = 'Socio Deudor';
        }

        $rows[] = [
            'nombre' => htmlspecialchars($data['nombre']),
            'mes_vencimiento' => htmlspecialchars($data['mes_vencimiento']),
            'estado' => $socioEstado
        ];
    }

    $response['estado'] = $socioEstado;
    $response['rows'] = $rows;
} else {
    $response['estado'] = 'No Encontrado'; // Cambio en el estado
    $response['message'] = 'El número de socio no se encontró'; // Mensaje de error
}

echo json_encode($response);

$stmt->close();
$conexion->close();
?>
