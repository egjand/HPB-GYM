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

$id = $_POST['id'];
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
                            <input id="id" class="form-control" type="text" name="id" placeholder="Ingresa el código de socio" onkeydown="if(event.keyCode == 13) { buscarSocio(); }">

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
        width: 60%;
        left: 20%;
        top: 20%;
        padding: 20px 2%;
        font-family: Calibri, Arial, sans-serif;
        background: #FFF;
        overflow: auto;
        transition: background-color 0.5s;
    }

    #fvpp-close {
        position: absolute;
        top: 10px;
        right: 20px;
        cursor: pointer;
        padding: 2px 16px;
        background-color: #5cb85c;
        color: white;
    }

    .error-message {
        font-size: 30px; /* Tamaño de fuente más grande para el mensaje de error */
        color: red; /* Opcional: color rojo para destacar el mensaje */
        text-align: center; /* Centrar el texto */
        padding: 20px; /* Espaciado alrededor del texto */
    }

    .data-table {
        font-size: 20px; /* Tamaño de fuente para los datos */
    }
</style>
<!-- /modal popup -->

<!-- El modal -->
<div id="fvpp-blackout"></div>
<div id="my-welcome-message">
    <a id="fvpp-close">✖</a>
    <h2>Bienvenido!</h2>
    <div id="modal-content">
        <!-- Los datos del socio se cargarán aquí -->
    </div>
</div>
<!-- /el modal -->

<?php include_once "includes/footer.php"; ?>
<script>
    var blinkInterval; 

    function buscarSocio() {
        var codigoSocio = document.getElementById('id').value.trim();

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
                        tableHtml = '<p>' + message + '</p>';
                        stopBlinking(); // Detener parpadeo si hay mensaje de error
                    } else {
                        tableHtml = '<table class="table table-hover"><tbody>';
                        rows.forEach(function(row) {
                            tableHtml += '<tr>';
                            tableHtml += '<td>' + row.nombre + '</td>';
                            tableHtml += '<td>' + row.mes_vencimiento + '</td>';
                            tableHtml += '<td>' + row.estado + '</td>';
                            tableHtml += '</tr>';
                        });
                        tableHtml += '</tbody></table>';
                        
                        var modal = document.getElementById('my-welcome-message');
                        if (estado === 'Socio Deudor') {
                            modal.style.backgroundColor = 'rgba(255, 111, 0, 0.5)';
                            startBlinking();
                        } else {
                            modal.style.backgroundColor = 'rgba(255, 255, 255, 1)';
                            stopBlinking();
                        }
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
                modal.style.backgroundColor = modal.style.backgroundColor === 'rgba(255, 0, 0, 0.5)' 
                    ? 'rgba(0, 0, 255, 0.5)' 
                    : 'rgba(255, 0, 0, 0.5)';
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
    };

    document.getElementById('fvpp-blackout').onclick = function() {
        document.getElementById('my-welcome-message').style.display = 'none';
        document.getElementById('fvpp-blackout').style.display = 'none';
    };
</script>
