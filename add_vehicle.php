<?php
require 'conexion.php';
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["agregar_vehiculo"])) {
    $placa = htmlspecialchars(trim($_POST["placa"]));
    $modelo = htmlspecialchars(trim($_POST["modelo"]));
    $marca = htmlspecialchars(trim($_POST["marca"]));

    // Obtener ID_Residente a partir del ID_Usuario
    $queryRes = "SELECT ID_Residente FROM residente WHERE ID_Usuario = ?";
    $stmtRes = $conn->prepare($queryRes);
    $stmtRes->bind_param("i", $usuario_id);
    $stmtRes->execute();
    $res = $stmtRes->get_result();

    if ($res && $row = $res->fetch_assoc()) {
        $id_residente = $row['ID_Residente'];

        // Insertar vehículo
        $sqlVeh = "INSERT INTO vehiculo (Placa, Modelo, Marca, ID_Residente) VALUES (?, ?, ?, ?)";
        $stmtVeh = $conn->prepare($sqlVeh);
        $stmtVeh->bind_param("sssi", $placa, $modelo, $marca, $id_residente);

        if ($stmtVeh->execute()) {
            echo "<p style='color: green;'>✅ Vehículo agregado correctamente.</p>";
            echo "<script>setTimeout(() => location.reload(), 2000);</script>";
        } else {
            echo "<p style='color: red;'>❌ Error al guardar el vehículo: " . $conn->error . "</p>";
        }
    } else {
        echo "<p style='color: red;'>❌ No se pudo obtener el ID del residente.</p>";
    }
}
?>
