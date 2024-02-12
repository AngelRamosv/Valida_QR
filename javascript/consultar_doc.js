document.addEventListener('DOMContentLoaded', function () {
    document.getElementById('registroForm').addEventListener('submit', function (event) {
        event.preventDefault(); // Prevenir el envío del formulario

        // Realizar una solicitud AJAX para enviar los datos del formulario a PHP
        var formData = new FormData(this);
        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'consultar_doc.php', true);
        xhr.onload = function () {
            if (xhr.status === 200) {
                // La solicitud AJAX se completó
                var mensajeDiv = document.getElementById('mensaje-error');
                mensajeDiv.textContent = xhr.responseText;
            }
        };
        xhr.send(formData);
    });
});
