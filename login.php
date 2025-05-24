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
    <div class="content c-login">
        <div class="content-form">
            <h2 style="color:#fff">Iniciar sesión</h2>
            <form action="login_session.php" method="POST">
                <div class="form-element">
                    <label>Correo</label><br>
                    <input type="email" name="correo" placeholder="Escribe tu correo electronico" required>
                </div>
                <div class="form-element">
                    <label>Contraseña</label><br>
                    <input type="password" name="contrasenia" placeholder="Escribe tu contraseña" required>
                </div>
                <button class="btn-submit" type="submit">Iniciar sesión</button>
                <span><a href="register.php" style="color:#fff ">Crear cuenta</a></span>
            </form>
        </div>
        
    </div>
    
</body>
</html>