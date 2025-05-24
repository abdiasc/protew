<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body>
    <?php
        session_start();
        // Verificar si ya hay una sesión activa
        if (isset($_SESSION['usuario_id'])) {
            // Redirigir según el rol
            switch ($_SESSION['rol']) {
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
        }
    ?>
    <div class="container-index">
        <h2 class="titulo" type="button">SISTEMA DE CONTROL VEHICULAR</h2>
        <div class="botonera">
            <a href="login.php"><button class="iniciar">Iniciar sesion</button></a>
            <a href="register.php"><button class="registrar" >Crear cuenta nueva</button></a>
            
        </div>
    </div>
</body>
</html>