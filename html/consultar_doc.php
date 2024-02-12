<?php
require('fpdf/fpdf.php');
require('phpqrcode/qrlib.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Procesa el formulario solo si se envió por POST

    // Recibe el valor del ID del usuario desde el formulario
    $idUsuario = isset($_POST["cajaid"]) ? $_POST["cajaid"] : "";

    // Verifica si el campo no está vacío
    if (empty($idUsuario)) {
        echo "*Ingresa un ID de usuario";
    } else {
        // Conexión a la base de datos (ajusta las credenciales)
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "bdprueba";

        $conn = new mysqli($servername, $username, $password, $dbname);

        // Verifica la conexión
        if ($conn->connect_error) {
            die("Conexión fallida: " . $conn->connect_error);
        }

        // Consulta SQL para buscar el usuario en la tabla "firma"
        $sql = "SELECT nombre_usuario FROM firma WHERE id = '$idUsuario'";

        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // Usuario encontrado, procede a mostrar el documento

            // Nombre del archivo PDF
            $pdfFilename = "documento_firmado_" . $idUsuario . ".pdf";
            $pdfFilePath = "../html/pdf/" . $pdfFilename;

            // Configura las cabeceras para forzar la descarga
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . $pdfFilename . '"');
            header('Content-Transfer-Encoding: binary');
            header('Expires: 0');
            header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
            header('Pragma: public');
            header('Content-Length: ' . filesize($pdfFilePath));

            // Lee el archivo PDF y envíalo al navegador
            readfile($pdfFilePath);

            // Cierra la conexión a la base de datos
            $conn->close();

            // Redirige al archivo de éxito en lugar de mostrar el PDF directamente
            exit;
        } else {
            echo "*Usuario no encontrado en la BD";
        }

        $conn->close();
    }
}
?>
