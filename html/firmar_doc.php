<?php

require('fpdf/fpdf.php');
require('phpqrcode/qrlib.php');

// Generar un valor de marca de tiempo en el servidor
$timestamp = time();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Procesa el formulario solo si se envió por POST

    // Recibe los valores del formulario
    $nombre_user = isset($_POST["nombre_user"]) ? $_POST["nombre_user"] : "";
    $nombre_doc = isset($_POST["nombre_doc"]) ? $_POST["nombre_doc"] : "";
    $fecha = isset($_POST["fecha"]) ? $_POST["fecha"] : "";
    $tipo = isset($_POST["tipo"]) ? $_POST["tipo"] : "";
    $evento = isset($_POST["evento"]) ? $_POST["evento"] : "";
    $pasword = isset($_POST["pasword"]) ? $_POST["pasword"] : "";

    // Verifica si algún campo está vacío
    if (empty($nombre_user) || empty($nombre_doc) || empty($fecha) || empty($tipo) || empty($evento) || empty($pasword)) {
        echo "Llenar todos los camposo";
    } else {
        // Conexión a la base de datos (ajusta las credenciales)
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "bdpruebak";

        $conn = new mysqli($servername, $username, $password, $dbname);

        // Verifica la conexión
        if ($conn->connect_error) {
            die("Conexión fallida: " . $conn->connect_error);
        }

        // Consulta SQL para buscar el usuario y su password en la tabla "firma"
        $sql = "SELECT id, nombre_usuario, pasword FROM firma WHERE nombre_usuario = '$nombre_user'";

        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // Usuario encontrado, verifica el password
            $row = $result->fetch_assoc();
            if ($row["pasword"] == $pasword) {
                // El password coincide, procede a insertar el documento
                $nombre_usuario = $row["nombre_usuario"]; // Almacena el nombre de usuario

                // Consulta SQL para buscar el documento existente y obtener su id_documento
                $sqlDocumento = "SELECT id_documento FROM documento WHERE nombre_doc = '$nombre_doc'";
                $resultDocumento = $conn->query($sqlDocumento);

                if ($resultDocumento->num_rows > 0) {
                    // El documento ya existe, obtenemos su id_documento
                    $rowDocumento = $resultDocumento->fetch_assoc();
                    $idDocumento = $rowDocumento["id_documento"];
                } else {
                    // El documento no existe, lo insertamos y obtenemos su id_documento
                    $sqlInsertDocumento = "INSERT INTO documento (nombre_user, nombre_doc, fecha_documento, tipo_documento, nombre_evento, pasword) 
                                            VALUES ('$nombre_user', '$nombre_doc', '$fecha', '$tipo', '$evento', '$pasword')";
                    if ($conn->query($sqlInsertDocumento) === TRUE) {
                        $idDocumento = $conn->insert_id;
                    } else {
                        echo "Error al insertar el documento en la base de datos: " . $conn->error;
                        exit;
                    }
                }

                // Datos para el código QR
                $qrData = "ID Usuario: " . $row["id"] . "\nEste documento es válido porque es firmado por el usuario: $nombre_usuario";
                $data = "Nombre de Usuario: $nombre_user\nNombre del Documento: $nombre_doc\nFecha: $fecha\nTipo de Documento: $tipo\nEvento: $evento\nPassword: $pasword";

                // Nombre del archivo PDF
                $timestamp = time(); // Genera un valor de marca de tiempo en segundos
                $pdfFilename = "documento_firmado_" . $timestamp . ".pdf";
                $pdfFilePath = "../html/pdf/" . $pdfFilename;
                $qrImageFilename = "../html/pdf/" . $pdfFilename . ".png";

                // Crear el PDF
                $pdf = new FPDF();
                $pdf->AddPage();
                $pdf->SetFont('Arial', 'B', 16);
                $pdf->Cell(40, 10, 'Documento Firmado');
                $pdf->Ln(10);
                $pdf->Cell(40, 10, 'Datos del documento:');
                $pdf->Ln(10);
                $pdf->MultiCell(0, 10, $data);
                $pdf->Ln(10);

                // Insertar el código QR en el PDF en la parte inferior derecha
                $qrSize = 50; // Tamaño del código QR
                $qrX = $pdf->GetPageWidth() - $qrSize - 10; // Coordenada X
                $qrY = $pdf->GetPageHeight() - $qrSize - 10; // Coordenada Y
                QRcode::png($qrData, $qrImageFilename, 'H'); // Guardar el QR en la ubicación correcta
                $pdf->Image($qrImageFilename, $qrX, $qrY, $qrSize);

                // Error
                $tmpPdfFilename = tempnam(sys_get_temp_dir(), 'pdf');

                // Guardar el PDF en un archivo
                $pdf->Output($pdfFilePath, 'F');

                // Verificar si el PDF se ha guardado exitosamente
                if (file_exists($pdfFilePath)) {
                    // Verificar si ya existe una entrada en la tabla firma para este usuario y documento
                    $sqlCheckExisting = "SELECT id FROM firma WHERE id_usuario = '{$row["id"]}' AND id_documento = '$idDocumento'";
                    $resultCheckExisting = $conn->query($sqlCheckExisting);

                    if ($resultCheckExisting->num_rows > 0) {
                        // Ya existe una entrada, actualizamos la fila existente
                        $sqlUpdateFirma = "UPDATE firma SET fecha_registro = NOW(), pasword = '$pasword', confirm_pasword = '$pasword' WHERE id_usuario = '{$row["id"]}' AND id_documento = '$idDocumento'";
                        if ($conn->query($sqlUpdateFirma) === TRUE) {
                            echo "Documento firmado exitosamente";
                        } else {
                            echo "Error al actualizar la fila en la tabla firma: " . $conn->error;
                        }
                    } else {
                        // No existe una entrada, insertamos una nueva fila
                        $sqlInsertFirma = "INSERT INTO firma (fecha_registro, nombre_usuario, id_usuario, id_documento, pasword, confirm_pasword) 
                                          VALUES (NOW(), '$nombre_usuario', '{$row["id"]}', '$idDocumento', '$pasword', '$pasword')";
                        if ($conn->query($sqlInsertFirma) === TRUE) {
                            echo "Documento firmado exitosamente";
                        } else {
                            echo "Error al guardar el registro en la tabla firma: " . $conn->error;
                        }
                    }
                } else {
                    echo "Error al guardar el documento PDF";
                }

                // Establece el encabezado 'pdf-timestamp' con el valor de marca de tiempo
                header('pdf-timestamp: ' . $timestamp);

                // Configura las cabeceras para forzar la descarga
                header('Content-Description: File Transfer');
                header('Content-Type: application/octet-stream');
                header('Content-Disposition: attachment; filename="' . $pdfFilename . '"');
                header('Content-Transfer-Encoding: binary');
                header('Expires: 0');
                header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
                header('Pragma: public');
                header('Content-Length: ' . filesize($tmpPdfFilename));

                // Lee el archivo temporal y envíalo al navegador
                readfile($tmpPdfFilename);

                // Borra el archivo temporal
                unlink($tmpPdfFilename);

                // Redirige al archivo de éxito en lugar de mostrar el PDF directamente
                exit;
            } else {
                echo "Tu contraseña no coincide con la BD";
            }
        } else {
            echo "Usuario no encontrado en la BD";
        }

        $conn->close();
    }
}
?>


