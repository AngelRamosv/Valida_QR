<?php
// Verifica si se ha enviado el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recibe los valores del formulario
    $fecha_registro = isset($_POST["calendario"]) ? $_POST["calendario"] : "";
    $usuario = isset($_POST["usuario"]) ? $_POST["usuario"] : "";
    $curp = isset($_POST["curp"]) ? $_POST["curp"] : "";
    $depa = isset($_POST["depa"]) ? $_POST["depa"] : "";
    $division = isset($_POST["division"]) ? $_POST["division"] : "";
    $cargo = isset($_POST["cargo"]) ? $_POST["cargo"] : "";
    $unidad = isset($_POST["unidad"]) ? $_POST["unidad"] : "";
    $pasword = isset($_POST["pasword"]) ? $_POST["pasword"] : "";
    $confirm_pasword = isset($_POST["confirm_pasword"]) ? $_POST["confirm_pasword"] : "";

    // Verifica si algún campo está vacío
    if (empty($fecha_registro) || empty($usuario) || empty($curp) || empty($depa) || empty($division) || empty($cargo) || empty($unidad) || empty($pasword) || empty($confirm_pasword)) {
        echo "Llenar todos los campos :)";
    } else {
        // Verifica que las contraseñas coincidan
        if ($pasword !== $confirm_pasword) {
            echo "El password no coincide";
        } else {
            // Verifica que la contraseña cumpla con los criterios (8 caracteres, 1 mayúscula, 1 minúscula y 1 carácter alfanumérico)
            if (strlen($pasword) < 8 || !preg_match('/[A-Z]/', $pasword) || !preg_match('/[a-z]/', $pasword) || !preg_match('/\d/', $pasword)) {
                echo "La contraseña no cumple con los criterios";
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

                // Verifica si el usuario existe en la tabla usuario
                $checkUserQuery = "SELECT id FROM usuario WHERE usuario = '$usuario'";
                $checkUserResult = $conn->query($checkUserQuery);

                if ($checkUserResult === false) {
                    // Manejar el error de consulta
                    echo "Error en la consulta: " . $conn->error;
                } else {
                    // Verifica si el usuario existe
                    if ($checkUserResult->num_rows > 0) {
                        // Inserta los datos en la base de datos (ajusta la consulta SQL)
                        $sql = "INSERT INTO firma (fecha_registro, nombre_usuario, curp, depto, division, cargo, unidad, pasword, confirm_pasword) VALUES ('$fecha_registro', '$usuario', '$curp', '$depa', '$division', '$cargo', '$unidad', '$pasword', '$confirm_pasword')";

                        if ($conn->query($sql) === TRUE) {
                            echo "Firma registrada exitosamente";
                        } else {
                            echo "Error: " . $sql . "<br>" . $conn->error;
                        }
                    } else {
                        echo "Usuario no válido";
                    }
                }

                $conn->close();
            }
        }
    }
}
?>
