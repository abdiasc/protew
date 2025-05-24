<?php
    $host = "localhost";
    $usuario = "root";
    $contrasena = "";
    $bd = "protew";

    $conn = new mysqli($host, $usuario, $contrasena, $bd);

    // Verificar conexión
    if ($conn->connect_error) {
        die("Conexión fallida: " . $conn->connect_error);
    }
?>