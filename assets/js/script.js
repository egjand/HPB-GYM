// Esperar a que el DOM esté completamente cargado
document.addEventListener("DOMContentLoaded", function() {
    // Obtener referencia al campo de código de socio
    var codigoSocioInput = document.getElementById("id");

    // Escuchar el evento de cambio en el campo de código de socio
    codigoSocioInput.addEventListener("keyup", function() {
        // Obtener el valor del campo de código de socio
        var codigoSocio = codigoSocioInput.value.trim();

        // Realizar la solicitud AJAX solo si el código de socio no está vacío
        if (codigoSocio !== "") {
            // Configurar la solicitud AJAX
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "buscar_socio.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

            // Manejar la respuesta de la solicitud AJAX
            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
                    // Convertir la respuesta JSON a objeto JavaScript
                    var socios = JSON.parse(xhr.responseText);
                    
                    // Llamar a la función para actualizar la tabla con los resultados
                    actualizarTabla(socios);
                }
            };

            // Enviar la solicitud AJAX con el código de socio como parámetro
            xhr.send("id=" + encodeURIComponent(codigoSocio));
        }
    });

    // Función para actualizar la tabla con los resultados de la búsqueda
    function actualizarTabla(socios) {
        // Obtener referencia a la tabla
        var tabla = document.getElementById("tblDetalle");

        // Limpiar el contenido actual de la tabla (excepto la cabecera)
        var tbody = tabla.querySelector("tbody");
        tbody.innerHTML = "";

        // Iterar sobre los socios y agregar cada uno como una fila en la tabla
        socios.forEach(function(socio) {
            var fila = "<tr>" +
                        "<td>" + socio.idcliente + "</td>" +
                        "<td>" + socio.nombre + "</td>" +
                        "<td>" + socio.mes_vencimiento + "</td>" +
                       "</tr>";
            tbody.innerHTML += fila;
        });
    }
});
