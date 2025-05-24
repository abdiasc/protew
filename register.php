<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body>
    <div class="content">

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
        <div class="content-form">
            <h2 style="color:#fff">Registro de usuario</h2>
            <form action="procesar.php" method="post">
                <div class="form-element">
                    <label>Nombre</label><br>
                    <input type="text" name="nombre" placeholder="Escribe tu nombre" required>
                </div>
                <div class="form-element">
                    <label>Apellidos</label><br>
                    <input type="text" name="apellidos" placeholder="Escribe tus apellidos" required>
                </div>
                <div class="form-element">
                    <label>Correo</label><br>
                    <input type="email" name="correo" placeholder="Escribe tu correo electrónico" required>
                </div>
                <div class="form-element">
                    <label>Contraseña</label><br>
                    <input type="password" name="password" placeholder="Escribe tu contraseña" required>
                </div>
                <div class="form-element">
                    <label>Repetir contraseña</label><br>
                    <input type="password" name="confirmacion" placeholder="Repite tu contraseña" required>
                </div>

                <div class="form-element">
                    <label>Genero</label><br>
                    <select class="genero" name="genero">
                        <option value="Masculino">Masculino</option>
                        <option value="Femenino">Femenino</option>
                        <option value="Otros">Otros</option>
                    </select>
                </div>
                <button class="btn-submit" type="submit">Registrar Usuario</button> <span><a href="login.php" style="color:#fff "> Ya tengo una cuenta</a></span>
            </form>
        </div>




    </div>
    
</body>
</html>