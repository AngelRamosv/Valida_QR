document.addEventListener('DOMContentLoaded', function () {
    const registroForm = document.getElementById('registroForm');
    const mensajeError = document.getElementById('mensaje-error');
    const verPdfButton = document.getElementById('verPdf');
    let formularioEnviado = false;
    let pdfTimestamp = null;

    registroForm.addEventListener('submit', function (event) {
        if (formularioEnviado) {
            event.preventDefault();
            return;
        }

        event.preventDefault();

        const formData = new FormData(registroForm);
        const xhr = new XMLHttpRequest();
        xhr.open('POST', 'firmar_doc.php', true);

        xhr.onload = function () {
            if (xhr.status === 200) {
                const pdfTimestampHeader = xhr.getResponseHeader('pdf-timestamp');
                if (pdfTimestampHeader) {
                    pdfTimestamp = parseInt(pdfTimestampHeader);
                }

                if (xhr.responseText === "Documento firmado exitosamente") {
                    alert("Documento firmado exitosamente");
                    formularioEnviado = true;
                    // Ocultar el mensaje de error
                    
                    mensajeError.style.display = 'none';

                    // Cambiar el estilo del botón verPdf
                    verPdfButton.style.backgroundColor = 'red';
                } else {
                    mensajeError.textContent = xhr.responseText;
                    // Mostrar el mensaje de error
                    mensajeError.style.display = 'block';
                    mensajeError.style.fontSize = '18px';
                }
            }
        };
        xhr.send(formData);
    });

    verPdfButton.addEventListener('click', function () {
        if (formularioEnviado) {
            if (pdfTimestamp !== null) {
                const pdfFilename = 'documento_firmado_' + pdfTimestamp + '.pdf';
                const pdfUrl = 'http://localhost/Valida_QR/Valida_QR/html/pdf/' + pdfFilename;
                window.open(pdfUrl, '_blank');
            } 
        } else {
            alert('Primero debes firmar');
        }
    });
});

