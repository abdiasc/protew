<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nombre = isset($_POST["nombre"]) ? htmlspecialchars($_POST["nombre"]) : "0";
    $apellidos = isset($_POST["apellidos"]) ? htmlspecialchars($_POST["apellidos"]) : "0";
    $correo = isset($_POST["correo"]) ? htmlspecialchars($_POST["correo"]) : "0";
    $password = isset($_POST["password"]) ? htmlspecialchars($_POST["password"]) : "0";
    $password_confirm = isset($_POST["confirmacion"]) ? htmlspecialchars($_POST["confirmacion"]) : "0";
    $genero = isset($_POST["genero"]) ? htmlspecialchars($_POST["genero"]) : "0";

    if ($password !== $password_confirm) {
        echo "Las contraseñas no coinciden.";
    } else {
        require 'conexion.php';

        // Verificar si es el primer usuario
        $consulta = "SELECT COUNT(*) AS total FROM usuarios";
        $resultado = mysqli_query($conn, $consulta);
        $fila = mysqli_fetch_assoc($resultado);
        $esPrimero = ($fila['total'] == 0);

        // Determinar el rol
        $rol = $esPrimero ? 'admin' : 'residente';

        // Encriptar la contraseña
        $hash = password_hash($password, PASSWORD_DEFAULT);

        // Insertar el nuevo usuario con rol correspondiente
        $sql = "INSERT INTO usuarios(nombre, apellidos, correo, contrasenia, genero, rol) 
                VALUES ('$nombre', '$apellidos', '$correo', '$hash', '$genero', '$rol')";

        if (mysqli_query($conn, $sql)) {
            header("Location: login.php");
            exit();
        } else {
            echo "Error al registrar usuario: " . mysqli_error($conn);
        }
    }
}
?>
