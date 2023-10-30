document.addEventListener('DOMContentLoaded', function () {
    const registroForm = document.getElementById('registroForm');
    const mensajeError = document.getElementById('mensaje-error');

    registroForm.addEventListener('submit', function (event) {
        event.preventDefault(); // Evita el envío del formulario por defecto

        const formData = new FormData(registroForm);

        // Realiza una solicitud AJAX para enviar el formulario
        const xhr = new XMLHttpRequest();
        xhr.open('POST', '../html/firmar_doc.php', true); // Reemplaza con la ruta correcta

        xhr.onload = function () {
            if (xhr.status === 200) {
                if (xhr.responseText === "Documento firmado exitosamente") {
                    // Muestra una ventana emergente con el mensaje
                    alert("Documento firmado exitosamente");
                    
                    // Redirige a pagina.html
                    window.location.href = '../html/pagina.php';
                } else {
                    mensajeError.textContent = xhr.responseText;
                }
            }
        };

        xhr.send(formData);
    });
});
