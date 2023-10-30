document.addEventListener('DOMContentLoaded', function () {
    document.getElementById('registroForm').addEventListener('submit', function (event) {
        event.preventDefault(); // Prevenir el envío del formulario
        
        // Realizar una solicitud AJAX para enviar los datos del formulario a PHP
        var formData = new FormData(this);
        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'registrar.php', true);
        xhr.onload = function () {
            if (xhr.status === 200) {
                // La solicitud AJAX se completó
                var mensajeDiv = document.getElementById('mensaje-error');
                var respuesta = JSON.parse(xhr.responseText);
                
                if (respuesta.mensaje === 'Registro exitoso.') {
                    // Si la respuesta es "Registro exitoso", muestra el mensaje de éxito en una ventana emergente
                    alert("Registro exitoso.");
                    
                    // Redirige a pagina.html
                    window.location.href = '../html/index.html';
                } else {
                    // Si la respuesta es un error, muestra el mensaje de error en el div
                    mensajeDiv.style.color = 'white';
                    mensajeDiv.textContent = respuesta.mensaje;
                }
            }
        };
        xhr.send(formData);
    });
});
