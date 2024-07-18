<?php
session_start();

// Redirige si la sesión está activa
if (!empty($_SESSION['active'])) {
    header('location: src/');
    exit;
}

if (!empty($_POST)) {
    $alert = '';
    if (empty($_POST['usuario']) || empty($_POST['clave'])) {
        $alert = '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                    Ingrese usuario y contraseña
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>';
    } else {
        // Incluye la conexión
        require_once "conexion.php";

        // Escapa las cadenas para prevenir inyección SQL
        $user = mysqli_real_escape_string($conexion, $_POST['usuario']);
        $clave = md5(mysqli_real_escape_string($conexion, $_POST['clave'])); // Considera usar `password_hash` en lugar de `md5`

        $user1 =$_POST['usuario'];
        $pass =$_POST['clave'];
        var_dump('puto' . $user , $clave);
        // Realiza la consulta
        $query = mysqli_query($conexion, "SELECT * FROM usuario WHERE usuario = '$user1' AND clave = '$pass'");

        if (!$query) {
            // Maneja el error de la consulta
            $alert = '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                        Error en la consulta: ' . mysqli_error($conexion) . '
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>';
        } else {
            // Cuenta el número de resultados
            $resultado = mysqli_num_rows($query);

            if ($resultado > 0) {
                $dato = mysqli_fetch_array($query);
                $_SESSION['active'] = true;
                $_SESSION['idUser'] = $dato['idusuario'];
                $_SESSION['nombre'] = $dato['nombre'];
                $_SESSION['user'] = $dato['usuario'];
                header('Location: src/');
                exit;
            } else {
                $alert = '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                            Usuario o contraseña incorrectos
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>';
                session_destroy();
            }
        }

        // Cierra la conexión al final
        mysqli_close($conexion);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>
    <link rel="stylesheet" href="assets/css/material-dashboard.css">
    <link rel="stylesheet" href="assets/css/estilos.css">
</head>
<body>
    <div style="display: flex;justify-content: center; align-items: center; height: 100vh;">
        <div class="container">
            <center>
                <img src="assets/img/Screenshot_1.png" alt="" class="img-class">
            </center>
            <form action="" id="login-form" method="POST">
                <div class="user-details">
                    <div class="input-box">
                        <input type="text" class="input-field" name="usuario" id="usuario" placeholder="Usuario" autocomplete="off" required>
                    </div>
                    <div class="input-box">
                        <input type="password" class="input-field" name="clave" id="clave" placeholder="Contraseña" autocomplete="off" required>
                    </div>
                    <?php echo isset($alert) ? $alert : ''; ?>
                    <div class="button">
                        <input type="submit" value="Login">
                    </div>
                </div>
            </form>
        </div>
    </div>
    <script src="assets/js/jquery-3.6.0.min.js" crossorigin="anonymous"></script>
    <script src="assets/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
</body>

</html>