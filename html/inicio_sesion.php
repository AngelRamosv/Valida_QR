<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['user']) && isset($_POST['pasword'])) {
        $usuario = $_POST['user'];
        $clave = $_POST['pasword'];

        if (empty($usuario) || empty($clave)) {
            echo json_encode(array("error" => true, "mensaje" => "Llenar todos los campos."));
        } else {
            $conexion = new mysqli("localhost", "root", "", "bdprueba");

            if ($conexion->connect_error) {
                echo json_encode(array("error" => true, "mensaje" => "Error en la conexión a la base de datos."));
            } else {
                $consulta = "SELECT contraseña FROM usuario WHERE usuario = ?";
                $stmt = $conexion->prepare($consulta);
                $stmt->bind_param("s", $usuario);

                if ($stmt->execute()) {
                    $stmt->bind_result($contrasena);

                    if ($stmt->fetch() && password_verify($clave, $contrasena)) {
                        // Inicio de sesión exitoso
                        session_start();
                        $_SESSION['usuario_autenticado'] = true;
                        // Almacena el nombre de usuario en la variable de sesión
                        $_SESSION['nombre_usuario'] = $usuario;

                        echo json_encode(array("error" => false, "mensaje" => "Inicio de sesión exitoso"));
                    } else {
                        echo json_encode(array("error" => true, "mensaje" => "Datos incorrectos"));
                    }
                } else {
                    echo json_encode(array("error" => true, "mensaje" => "Error en la consulta: " . $stmt->error));
                }

                $stmt->close();
                $conexion->close();
            }
        }
    } else {
        echo json_encode(array("error" => true, "mensaje" => "Llenar todos los campos"));
    }
} else {
    echo json_encode(array("error" => true, "mensaje" => "Solicitud no válida"));
}
?>


