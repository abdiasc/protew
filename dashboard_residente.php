<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="css/residente.css">
</head>
<body>
    <?php
        session_start();
        require 'conexion.php';

        if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'residente') {
            header("Location: login.php");
            exit();
        }

        $usuario_id = $_SESSION['usuario_id'];
        $nombre = $_SESSION['nombre'];

        // Verificar si ya existe un registro en la tabla residente
        $sql = "SELECT * FROM residente WHERE ID_Usuario = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $usuario_id);
        $stmt->execute();
        $resultado = $stmt->get_result();

        if ($resultado->num_rows === 0) {
            // Mostrar formulario para completar registro
            ?>
            <div class="datos residente">
                <h2 >Bienvenido <?php echo htmlspecialchars($nombre); ?> 👋</h2>
                <a style="text-decoration:none; color:#fff;" href="logout.php">
                    <p class="btn-enlace">Cerrar sesión</p>
                </a>

            </div>
            


            <div class="registro-completar">
                <small>Por favor completa tu información como residente:</small>
                <form method="POST" action="">
                    <div class="form-element">
                        <label for="direccion">Dirección</label><br>
                        <input type="text" name="direccion" required><br><br>
                    </div>
                    <div class="form-element">
                        <label for="telefono">Teléfono</label><br>
                        <input type="text" name="telefono" required><br><br>
                    </div>
                    <button class="btn-submit" type="submit" name="completar">Guardar</button>
                </form>
            </div>

    <?php
    if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["completar"])) {
        $direccion = htmlspecialchars(trim($_POST["direccion"]));
        $telefono = htmlspecialchars(trim($_POST["telefono"]));

        $insert_sql = "INSERT INTO residente (ID_Usuario, Direccion, Telefono) VALUES (?, ?, ?)";
        $insert_stmt = $conn->prepare($insert_sql);
        $insert_stmt->bind_param("iss", $usuario_id, $direccion, $telefono);

        if ($insert_stmt->execute()) {
            echo "<p class='ms-exito'>✅ Registro completado correctamente.</p>";
            echo "<script>setTimeout(() => location.reload(), 5000);</script>";
        } else {
            echo "<p style='color: red;'>❌ Error al guardar los datos: " . $conn->error . "</p>";
        }
    }

} else {
    // Ya tiene registro como residente
    $residente = $resultado->fetch_assoc();

    // Procesar agregar vehículo
    if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["agregar_vehiculo"])) {
        $placa = htmlspecialchars(trim($_POST["placa"]));
        $modelo = htmlspecialchars(trim($_POST["modelo"]));
        $marca = htmlspecialchars(trim($_POST["marca"]));
        $id_residente = $residente['ID_Residente'];

        $sqlVeh = "INSERT INTO vehiculo (Placa, Modelo, Marca, ID_Residente) VALUES (?, ?, ?, ?)";
        $stmtVeh = $conn->prepare($sqlVeh);
        $stmtVeh->bind_param("sssi", $placa, $modelo, $marca, $id_residente);

        if ($stmtVeh->execute()) {
            echo "<p class='ms-exito'>✅ Vehículo agregado correctamente.</p>";
            echo "<script>setTimeout(() => location.href=window.location.href, 1000);</script>";
            exit();
        } else {
            echo "<p style='color: red;'>❌ Error al guardar el vehículo: " . $conn->error . "</p>";
        }
    }

    // Procesar registrar ingreso/salida
    if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["registrar_acceso"])) {
        $vehiculo_id = intval($_POST["vehiculo"]);
        $tipo = $_POST["tipo"] === "Ingreso" ? "Ingreso" : "Salida";
        $observaciones = htmlspecialchars(trim($_POST["observaciones"]));
        date_default_timezone_set('America/La_Paz');
        $fecha_hora = date("Y-m-d H:i:s");

        $sqlRegistro = "INSERT INTO registro (FechaHora, Tipo, Observaciones, ID_Vehiculo) VALUES (?, ?, ?, ?)";
        $stmtRegistro = $conn->prepare($sqlRegistro);
        $stmtRegistro->bind_param("sssi", $fecha_hora, $tipo, $observaciones, $vehiculo_id);

        if ($stmtRegistro->execute()) {
            echo "<p style='color: green;'>✅ Registro de $tipo guardado correctamente.</p>";
            echo "<script>setTimeout(() => location.href=window.location.href, 1000);</script>";
            exit();
        } else {
            echo "<p style='color: red;'>❌ Error al registrar el acceso: " . $conn->error . "</p>";
        }
    }

    ?>

    <div class="datos residente-datos">
        <h2>Bienvenido <?php echo htmlspecialchars($nombre); ?> 👋</h2>
        <p><strong>Rol:</strong> <?php echo htmlspecialchars($_SESSION['rol']); ?></p>
        <p><strong>Dirección:</strong> <?php echo htmlspecialchars($residente["Direccion"]); ?></p>
        <p><strong>Teléfono:</strong> <?php echo htmlspecialchars($residente["Telefono"]); ?></p>

        <a style="text-decoration:none; color:#fff;" href="logout.php">
            <p class="btn-enlace">Cerrar sesión</p>
        </a>
    </div>

    

    <div class="vehiculos">
        <div class="v-element">
                <h3>Agregar Vehículo</h3>
                <form method="POST" action="">
                    <div class="frm-v">
                        <label for="placa">Placa:</label>
                        <input type="text" name="placa" required><br><br>
                    </div>
                    <div class="frm-v">
                        <label for="modelo">Modelo:</label>
                        <input type="text" name="modelo" required><br><br>
                    </div>
                    <div class="frm-v">
                        <label for="marca">Marca:</label>
                        <input type="text" name="marca" required><br><br>
                    </div>
                    <button class="btn-submit" type="submit" name="agregar_vehiculo">Guardar Vehículo</button>
                </form>
        </div>

        <div class="v-element">
            <h3>Mis Vehículos Registrados</h3>
            <table class="v-tabla" border="1" cellpadding="8" cellspacing="0">
                <thead>
                    <tr>
                        <th>Placa</th>
                        <th>Marca</th>
                        <th>Modelo</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $vehiculos = $conn->prepare("SELECT ID_Vehiculo, Placa, Modelo, Marca FROM vehiculo WHERE ID_Residente = ?");
                    $vehiculos->bind_param("i", $residente["ID_Residente"]);
                    $vehiculos->execute();
                    $resultVeh = $vehiculos->get_result();

                    if ($resultVeh->num_rows > 0) {
                        while ($vehiculo = $resultVeh->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($vehiculo["Placa"]) . "</td>";
                            echo "<td>" . htmlspecialchars($vehiculo["Marca"]) . "</td>";
                            echo "<td>" . htmlspecialchars($vehiculo["Modelo"]) . "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='3'>No tienes vehículos registrados.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="v-historial v-historial-b">
        <div class="v-historial-element">
            <h3>Registrar Ingreso / Salida</h3>
            <form method="POST" action="">
                <label for="vehiculo">Seleccionar vehículo:</label>
                <select name="vehiculo" required>
                    <?php
                    $stmtVehiculos = $conn->prepare("SELECT ID_Vehiculo, Placa FROM vehiculo WHERE ID_Residente = ?");
                    $stmtVehiculos->bind_param("i", $residente["ID_Residente"]);
                    $stmtVehiculos->execute();
                    $resultVehiculos = $stmtVehiculos->get_result();

                    while ($veh = $resultVehiculos->fetch_assoc()) {
                        echo "<option value='" . $veh["ID_Vehiculo"] . "'>" . htmlspecialchars($veh["Placa"]) . "</option>";
                    }
                    ?>
                </select><br><br>

                <label for="tipo">Tipo de registro:</label>
                <select name="tipo" required>
                    <option value="Ingreso">Ingreso</option>
                    <option value="Salida">Salida</option>
                </select><br><br>

                <label for="observaciones">Observaciones (opcional):</label><br>
                <textarea class="t-area" name="observaciones" rows="3" cols="40"></textarea><br><br>

                <button class="btn-submit" type="submit" name="registrar_acceso">Registrar</button>
            </form>
        </div>
        <div class="v-historial-element">
            <h3>Historial de Ingresos y Salidas</h3>
                <table class="v-thistorial" border="1" cellpadding="5">
                    <tr>
                        <th>Fecha y Hora</th>
                        <th>Tipo</th>
                        <th>Vehículo (Placa)</th>
                        <th>Observaciones</th>
                    </tr>
                    <?php
                    $sqlHist = "
                        SELECT r.FechaHora, r.Tipo, v.Placa, r.Observaciones 
                        FROM registro r
                        INNER JOIN vehiculo v ON r.ID_Vehiculo = v.ID_Vehiculo
                        WHERE v.ID_Residente = ?
                        ORDER BY r.FechaHora DESC
                    ";
                    $stmtHist = $conn->prepare($sqlHist);
                    $stmtHist->bind_param("i", $residente["ID_Residente"]);
                    $stmtHist->execute();
                    $resultHist = $stmtHist->get_result();

                    if ($resultHist->num_rows > 0) {
                        while ($row = $resultHist->fetch_assoc()) {
                            echo "<tr>
                                    <td>" . htmlspecialchars($row["FechaHora"]) . "</td>
                                    <td>" . htmlspecialchars($row["Tipo"]) . "</td>
                                    <td>" . htmlspecialchars($row["Placa"]) . "</td>
                                    <td>" . htmlspecialchars($row["Observaciones"]) . "</td>
                                </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='4'>Sin registros.</td></tr>";
                    }
                    ?>
                </table>
        </div>
    </div>

    
    <?php
}
?>

</body>
</html>