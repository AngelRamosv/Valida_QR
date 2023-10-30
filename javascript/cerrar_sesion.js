document.addEventListener('DOMContentLoaded', function () {
    const botonCerrarSesion = document.getElementById('cerrar-sesion');

    botonCerrarSesion.addEventListener('click', function (event) {
        event.preventDefault();

        // Mostrar una ventana emergente de confirmación
        if (confirm('¿Desea cerrar la sesión?')) {
            // Redirige al usuario a la página de cerrar sesión
            window.location.href = '../html/cerrar_sesion.php';
        }
    });
});



