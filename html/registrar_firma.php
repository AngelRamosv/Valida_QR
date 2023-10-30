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
        echo "Llenar todos los campos";
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
                $dbname = "bdprueba";

                $conn = new mysqli($servername, $username, $password, $dbname);

                // Verifica la conexión
                if ($conn->connect_error) {
                    die("Conexión fallida: " . $conn->connect_error);
                }

                // Inserta los datos en la base de datos (ajusta la consulta SQL)
                $sql = "INSERT INTO firma (fecha_registro, nombre_usuario, curp, depto, division, cargo, unidad, pasword) VALUES ('$fecha_registro', '$usuario', '$curp', '$depa', '$division', '$cargo', '$unidad', '$pasword')";

                if ($conn->query($sql) === TRUE) {
                    echo "Firma registrada exitosamente";
                } else {
                    echo "Error: " . $sql . "<br>" . $conn->error;
                }

                $conn->close();
            }
        }
    }
}
?>
