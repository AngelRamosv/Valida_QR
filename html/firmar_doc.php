<?php
// Verifica si se ha enviado el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recibe los valores del formulario
    $nombre_user = isset($_POST["nombre_user"]) ? $_POST["nombre_user"] : "";
    $nombre_doc = isset($_POST["nombre_doc"]) ? $_POST["nombre_doc"] : "";
    $fecha = isset($_POST["fecha"]) ? $_POST["fecha"] : "";
    $tipo = isset($_POST["tipo"]) ? $_POST["tipo"] : "";
    $evento = isset($_POST["evento"]) ? $_POST["evento"] : "";
    $pasword = isset($_POST["pasword"]) ? $_POST["pasword"] : "";

    // Verifica si algún campo está vacío
    if (empty($nombre_user) ||empty($nombre_doc) || empty($fecha) || empty($tipo) || empty($evento) || empty($pasword)) {
        echo "Llenar todos los campos";
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

        // Consulta SQL para buscar el usuario y su password en la tabla "firma"
        $sql = "SELECT nombre_usuario, pasword FROM firma WHERE nombre_usuario = '$nombre_user'";

        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // Usuario encontrado, verifica el password
            $row = $result->fetch_assoc();
            if ($row["pasword"] == $pasword) {
                // El password coincide, procede a insertar el documento
                $nombre_usuario = $row["nombre_usuario"]; // Almacena el nombre de usuario
                // Inserta los datos en la base de datos (ajusta la consulta SQL)
                $sql = "INSERT INTO documento (nombre_user, nombre_doc, fecha_documento, tipo_documento, nombre_evento, pasword) VALUES ('$nombre_user', '$nombre_doc', '$fecha', '$tipo', '$evento', '$pasword')";

                if ($conn->query($sql) === TRUE) {
                    echo "Documento firmado exitosamente";
                } else {
                    echo "Error al insertar el documento: " . $conn->error;
                }
            } else {
                echo "Tu password no coincide con la BD";
            }
        } else {
            echo "Usuario no encontrado en la BD";
        }

        $conn->close();
    }
}
?>
