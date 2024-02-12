document.addEventListener('DOMContentLoaded', function () {
    const registroForm = document.getElementById('registroForm');
    const mensajeError = document.getElementById('mensaje-error');

    registroForm.addEventListener('submit', async function (event) {
        event.preventDefault(); // Evita el envío del formulario por defecto

        try {
            const formData = new FormData(registroForm);

            // Realiza una solicitud AJAX utilizando fetch
            const response = await fetch('../html/registrar_firma.php', {
                method: 'POST',
                body: formData,
            });

            if (response.ok) {
                const responseData = await response.text();
                if (responseData.trim() === "Firma registrada exitosamente") {
                    // Muestra una ventana emergente con el mensaje
                    alert("Firma registrada exitosamente");
                    // Redirige a pagina.html
                    window.location.href = '../html/pagina.php';
                } else {
                    mensajeError.style.color = 'yellow';
                    mensajeError.style.fontSize = '19px';
                    mensajeError.textContent = responseData;
                }
            } else {
                throw new Error('Error en la solicitud AJAX');
            }
        } catch (error) {
            console.error('Error en la solicitud AJAX:', error);
            mensajeError.style.color = 'red';
            mensajeError.style.fontSize = '19px';
            mensajeError.textContent = 'Error en la solicitud AJAX';
        }
    });
});


