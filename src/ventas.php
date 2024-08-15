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
                            <input id="id-socio" class="form-control" type="text" name="id-socio" placeholder="Ingresa el código de socio" onkeypress="return isNumberKey(event)" onkeydown="if(event.keyCode == 13) { buscarSocio(); }">
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
    </div>

</div>
<?php include_once "includes/footer.php"; ?>



<!-- Estilo del Modal Popup -->
<style>
    #container {
        max-width: 1000px;
        margin: 0 auto;
        background: #EEE;
    }

    #fvpp-blackout {
        display: none;
        z-index: 499;
        position: fixed;
        width: 100%;
        height: 100%;
        top: 0;
        left: 0;
        background: #000;
        opacity: 0.5;
    }

    #my-welcome-message {
        display: none;
        z-index: 500;
        position: fixed;
        width: 59%;
        left: 26%;
        top: 20%;
        padding: 114px 2%;
        font-family: Calibri, Arial, sans-serif;
        background: #FFF;
        overflow: auto;
        transition: background-color 0.5s;
        border-radius: 15px;
    }

    #fvpp-close {
        position: relative;
        top: 100px;
        left: 410px;
        cursor: pointer;
        padding: 15px 55px;
        background-color: #5cb85c;
        color: white;
        font-size: 18px;
        border-radius: 20px;
    }

    .error-message {
        font-size: 30px;
        /* Tamaño de fuente más grande para el mensaje de error */
        color: red;
        /* Opcional: color rojo para destacar el mensaje */
        text-align: center;
        /* Centrar el texto */
        padding: 20px;
        /* Espaciado alrededor del texto */
    }

    .data-table {
        font-size: 20px;
        /* Tamaño de fuente para los datos */
    }

    .msg-welcome {
        position: relative;
        font-family: 'Arial', sans-serif;
        /* Fuente legible y moderna */
        font-size: 40px;
        /* Tamaño de fuente cómodo */
        font-weight: bold;
        /* Negrita para destacar */
        color: #333333;
        /* Color de texto oscuro pero suave */
        margin-top: 20px;
        /* Espacio superior */
        margin-bottom: 15px;
        /* Espacio inferior */
        text-align: center;
        /* Centrar el texto */
        line-height: 1.4;
        /* Altura de línea para mejor legibilidad */
        letter-spacing: 1px;
        /* Espaciado entre letras */
        text-transform: uppercase;
        /* Convertir texto a mayúsculas */
        border-bottom: 2px solid #cccccc;
        /* Línea debajo del título */
        padding-bottom: 10px;
        /* Espaciado debajo del texto */

    }

    .row {
        margin-bottom: 15px;
        border-bottom: 1px solid #ccc;
        padding-bottom: 10px;
    }

    .cell-nombre {
        margin: 5px 0;
        position: relative;
        left: 10px;
        font-size: 30px;
    }

    .cell-nombre-deudor {
        margin: 10px 15px 20px 25px;
        position: absolute;
        left: 430px;
        top: 180px;
        font-size: 30px;
    }

    .cell-estado-deudor {
        margin: 10px 15px 20px 25px;
        font-family: 'Arial', sans-serif;
        font-weight: bold;
        position: absolute;
        left: 385px;
        top: 111px;
        font-size: 50px;
    }
    .mensaje{
        font-family: 'Arial', sans-serif;
        font-weight: bold;
        position: absolute;
        left: 235px;
        top: 125px;
        font-size: 40px;
    }

    .cell-mes {
        margin: 5px 0;
        position: relative;
        left: 80px;
        font-size: 30px;
    }

    .cell-estado {
        margin: 5px 0;
        position: relative;
        left: 160px;
        font-size: 30px;
    }

    .notification {
        background-color: #fafafa;
        /* Fondo azul claro */
        color: #000000;
        /* Texto azul oscuro */
        border-left: 4px solid #000000;
        /* Borde azul */
        padding: 25px;
        /* Espaciado interno */
        margin-bottom: 0px;
        /* Espacio debajo del div */
        border-radius: 5px;
        /* Esquinas redondeadas */
        font-family: 'Arial', sans-serif;
        /* Fuente */
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        /* Sombra ligera */
        transition: background-color 0.3s ease;
        /* Transición suave al cambiar de color */
    }

    .notification .title {
        font-weight: bold;
        /* Título en negrita */
        margin-bottom: 5px;
        /* Espacio debajo del título */
        font-size: 1.2em;
        /* Tamaño del título */
    }

    .notification .message {
        margin: 0;
        /* Sin margen adicional */
        font-size: 1em;
        /* Tamaño del texto */
        line-height: 1.5;
        /* Altura de línea */
    }
</style>
<!-- /modal popup -->

<!-- El modal -->
<div id="fvpp-blackout"></div>
<div id="my-welcome-message">



    <div id="modal-content" class="notification">
        <!-- Los datos del socio se cargarán aquí -->
    </div>
    <button id="fvpp-close" onclick="limpiarInput()">ACEPTAR</button>
</div>
<!-- /el modal -->

<?php include_once "includes/footer.php"; ?>
<script>
    var blinkInterval;

    function buscarSocio() {
        var codigoSocio = document.getElementById('id-socio').value.trim();

        if (codigoSocio !== '') {
            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'buscar_socio.php', true);
            xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
            xhr.onreadystatechange = function() {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    var response = JSON.parse(xhr.responseText);
                    var estado = response.estado;
                    var rows = response.rows;
                    var message = response.message || ''; // Mensaje de error

                    var tableHtml = '';
                    if (estado === 'No Encontrado') {
                        tableHtml = '<p class="mensaje">' + message + '</p>';
                        stopBlinking(); // Detener parpadeo si hay mensaje de error
                    } else {

                        rows.forEach(function(row) {
                            if (estado === 'Socio Deudor') {
                                tableHtml += '<div class="cell-estado-deudor" style="color = "red"">' + row.estado + '</div>';
                                tableHtml += '<div class="cell-nombre-deudor">' + row.nombre + '</div>';
                            } else {
                                tableHtml = '<table class="table table-hover"><tbody>';
                                tableHtml += '<h2 class="msg-welcome">Bienvenido!</h2>';
                                tableHtml += '<div class="row">';
                                tableHtml += '<div class="cell-nombre">' + row.nombre + '</div>';
                                tableHtml += '<div class="cell-mes">' + row.mes_vencimiento + '</div>';
                                tableHtml += '<div class="cell-estado">' + row.estado + '</div>';
                                tableHtml += '</div>';
                            }
                        });
                        tableHtml += '</tbody></table>';


                        /*if (estado === 'Socio Deudor') {
                            modal.style.backgroundColor = 'rgb(217, 39, 39)';
                            //startBlinking();
                        } else {
                            modal.style.backgroundColor = 'rgba(255, 255, 255, 1)';
                            stopBlinking();
                        }*/
                    }

                    document.getElementById('modal-content').innerHTML = tableHtml;

                    var modal = document.getElementById('my-welcome-message');
                    modal.style.display = 'block';
                    document.getElementById('fvpp-blackout').style.display = 'block';
                }
            };
            xhr.send('codigo=' + encodeURIComponent(codigoSocio));
        }
    }

    function startBlinking() {
        var modal = document.getElementById('my-welcome-message');
        var duration = 6000; // Duración total del parpadeo en milisegundos
        var interval = 600; // Intervalo de cambio de color en milisegundos
        var count = 0; // Contador de intervalos

        function toggleColor() {
            if (count * interval < duration) {
                modal.style.backgroundColor = modal.style.backgroundColor === 'rgba(255, 0, 0, 0.5)' ?
                    'rgba(0, 0, 255, 0.5)' :
                    'rgba(255, 0, 0, 0.5)';
                count++;
            } else {
                clearInterval(blinkInterval);
                modal.style.backgroundColor = '';
            }
        }

        blinkInterval = setInterval(toggleColor, interval);
    }

    function stopBlinking() {
        clearInterval(blinkInterval);
        document.getElementById('my-welcome-message').style.backgroundColor = ''; // Restablecer el color
    }

    // Cerrar el modal
    document.getElementById('fvpp-close').onclick = function() {
        document.getElementById('my-welcome-message').style.display = 'none';
        document.getElementById('fvpp-blackout').style.display = 'none';
        document.getElementById('id-socio').innerHTML = '';
        var input = document.getElementById('id-socio');
        input.value = '';
    };

    document.getElementById('fvpp-blackout').onclick = function() {
        document.getElementById('my-welcome-message').style.display = 'none';
        document.getElementById('fvpp-blackout').style.display = 'none';
    };

    function isNumberKey(evt) {
        var charCode = evt.which ? evt.which : evt.keyCode;
        return !(charCode < 48 || charCode > 57); // Permitir solo números
    }
</script>