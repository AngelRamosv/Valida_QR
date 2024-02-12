<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Página Qr</title>
    <link rel="shortcut icon" href="img/uamidesign.png" type="image/x-icon">
    <link rel="stylesheet" href="../css/pagina.css">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,700,800&display=swap" rel="stylesheet"> 
</head>

<body>
    <header>
        <nav>
            <img src="../imagen/logout.png" width="235px" height="110px" alt="logo">
            <a href="registrar_firma.html">Registrar firma</a>
            <a href="firmar_doc.html">Firmar</a>
            <a href="consultar.html">Consultar</a>
            <a href="cerrar_sesion.php" id="cerrar-sesion"><button>Cerrar Sesión</button></a>
            <?php
            session_start(); // Inicia la sesión (asegúrate de que esto esté al principio del archivo)

            if (isset($_SESSION['usuario_autenticado']) && $_SESSION['usuario_autenticado'] === true) {
            
            echo '<div class="bienvenida">Bienvenido ' . $_SESSION['nombre_usuario'] . '</div>';
            }
            ?>
        </nav>
        <section class="textos-header">
            <h1>Validación de la autenticación de firmas digitales a través de códigos QR</h1>            
    </header>
    <main>
        <section class="contenedor sobre-proyecto"><br><br>
            <h2 class="titulov">Nuestro proyecto</h2>
            <div class="contenedor-sobre-proyecto">
                <img src="../imagen/ilustracion2.svg" alt="" class="imagen-about-proyecto">
                <div class="contenido-textos">
                    <h3><span>1</span>¿Qué es un codigo QR?</h3>
                    <p>Los códigos QR almacenan información y la hacen accesible. QR son las iniciales de Quick Response (respuesta rápida) y este nombre les hace justicia, ya que un escáner procesa datos y ejecuta órdenes al momento.</p>
                    <h3><span>2</span>Como funciona nuestro proyecto</h3>
                    <p>Se basa en la aplicación y uso de códigos QR para la encriptación de firmas digitales en documentos oficiales emitidos en la institución UAM IZTAPALAPA, con el fin de garatntizar una mayor seguridad y accesibilidad a dichos documentos tanto para el alumnado asi como tambien para las autoridades pertinentes.</p>
                </div>
            </div>
        </section>
       
        <section class="about-services">
            <div class="contenedor">
                <h2 class="titulo">Otros articulos</h2>
                <div class="servicio-cont">
                    <div class="servicio-ind">
                        <img src="../imagen/ilustracion1.svg" alt="">
                        <h3>Firma digital</h3>
                        <p></p>
                    </div>
                    <div class="servicio-ind">
                        <img src="../imagen/ilustracion4.svg" alt="">
                        <h3>Lenguajes utilizados</h3>
                        <p></p>
                    </div>
                    <div class="servicio-ind">
                        <img src="../imagen/ilustracion3.svg" alt="">
                        <h3>servicios</h3>
                        <p></p>
                    </div>
                </div>
            </div>
        </section>
    </main>
    <footer>
        <div class="contenedor-footer">
            <div class="content-foo">
                <h4>Telefono</h4>
                <p>8296312</p>
            </div>
            <div class="content-foo">
                <h4>Correo</h4>
                <p>estudiantes@gmail.com</p>
            </div>
            <div class="content-foo">
                <h4>Ubicacion</h4>
                <p>Av. San Rafael Atlixco 186, Leyes de Reforma 1ra Secc, Iztapalapa, 09340 Ciudad de México, CDMX</p>
            </div>
        </div>
        <h2 class="titulo-final">&copy; Universidad Autónoma Metropolitana - Unidad Iztapalapa</h2>
    </footer>
    <script src="../javascript/cerrar_sesion.js"></script>
</body>
</html>