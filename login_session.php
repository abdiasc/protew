<?php
session_start();
require 'conexion.php';



if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $correo = $_POST['correo'];
    $password = $_POST['contrasenia'];

    $sql = "SELECT * FROM usuarios WHERE correo = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $correo);
    mysqli_stmt_execute($stmt);
    $resultado = mysqli_stmt_get_result($stmt);
    

    if ($usuario = mysqli_fetch_assoc($resultado)) {
        if (password_verify($password, $usuario['contrasenia'])) {
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['nombre'] = $usuario['nombre'];
            $_SESSION['rol'] = $usuario['rol'];

            // Redirigir según rol
            switch ($usuario['rol']) {
                case 'admin':
                    header("Location: dashboard_admin.php");
                    break;
                case 'residente':
                    header("Location: dashboard_residente.php");
                    break;
                case 'portero':
                    header("Location: dashboard/portero.php");
                    break;
                default:
                    echo "Rol no reconocido.";
            }
            exit();
        } else {
            echo "Contraseña incorrecta.";
        }
    } else {
        echo "Usuario no encontrado.";
    }
}
?>
