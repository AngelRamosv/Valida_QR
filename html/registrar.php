<?php
$mensajeError = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST["nombre"];
    $apaterno = $_POST["apaterno"];
    $amaterno = $_POST["amaterno"];
    $correo = $_POST["correo"];
    $usuario = $_POST["usuario"];
    $contrasena = $_POST["contraseña"];

    if (empty($nombre) || empty($apaterno) || empty($amaterno) || empty($correo) || empty($usuario) || empty($contrasena)) {
        $mensajeError = "Faltan datos por llenar";
    } elseif (strlen($contrasena) !== 8) {
        $mensajeError = "La contraseña debe tener exactamente 8 caracteres.";
    } else {
        // Conexión a la base de datos
        $conexion = new mysqli("localhost", "root", "", "bdprueba");

        if ($conexion->connect_error) {
            die("Error de conexión: " . $conexion->connect_error);
        }

        // Verifica si el usuario ya existe en la base de datos
        $consulta = "SELECT * FROM usuario WHERE usuario = ?";
        $stmt = $conexion->prepare($consulta);
        $stmt->bind_param("s", $usuario);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {

            $mensajeError = "El usuario ya existe.Intenta con otro.";
        } else {
            // Hash de la contraseña
            $contrasena = password_hash($contrasena, PASSWORD_DEFAULT);

            // Inserta el nuevo usuario
            $sql = "INSERT INTO usuario (nombre, apaterno, amaterno, correo, usuario, contraseña) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $conexion->prepare($sql);
            $stmt->bind_param("ssssss", $nombre, $apaterno, $amaterno, $correo, $usuario, $contrasena);

            if ($stmt->execute()) {
                $mensajeError = "Registro exitoso.";
            } else {
                $mensajeError = "Error en el registro: " . $stmt->error;
            }
        }

        $stmt->close();
        $conexion->close();
    }

    // Salida en formato JSON
    $respuesta = ["mensaje" => $mensajeError];
    echo json_encode($respuesta);
}
?>

