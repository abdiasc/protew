-- Tabla de usuarios unificada
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL,
    apellidos VARCHAR(100) NOT NULL,
    correo VARCHAR(100) NOT NULL UNIQUE,
    contrasenia VARCHAR(255) NOT NULL,
    genero ENUM('Masculino', 'Femenino', 'Otro') NOT NULL,
    rol ENUM('admin', 'residente', 'portero') NOT NULL DEFAULT 'residente',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabla de residentes (relacionada a usuarios con rol 'residente')
CREATE TABLE residente (
    ID_Residente INT AUTO_INCREMENT PRIMARY KEY,
    ID_Usuario INT NOT NULL,
    Direccion VARCHAR(200),
    Telefono VARCHAR(20),
    FOREIGN KEY (ID_Usuario) REFERENCES usuarios(id)
);

-- Tabla de vehículos (relacionados a residentes)
CREATE TABLE vehiculo (
    ID_Vehiculo INT AUTO_INCREMENT PRIMARY KEY,
    Placa VARCHAR(10) UNIQUE,
    Modelo VARCHAR(50),
    Marca VARCHAR(50),
    ID_Residente INT,
    FOREIGN KEY (ID_Residente) REFERENCES residente(ID_Residente)
);

-- Tabla de registros de ingreso/salida (relacionados a vehículos)
CREATE TABLE registro (
    ID_Registro INT AUTO_INCREMENT PRIMARY KEY,
    FechaHora TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    Tipo ENUM('Ingreso', 'Salida'),
    Observaciones TEXT,
    ID_Vehiculo INT,
    FOREIGN KEY (ID_Vehiculo) REFERENCES vehiculo(ID_Vehiculo)
);
