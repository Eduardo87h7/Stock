<?php
$host = getenv('PGHOST');  // Obteniendo el host desde la variable de entorno
$db = getenv('PGDATABASE');    // Obteniendo el nombre de la base de datos
$user = getenv('PGUSER');  // Obteniendo el usuario desde la variable de entorno
$pass = getenv('PGPASSWORD');  // Obteniendo la contraseña desde la variable de entorno
$port = getenv('PGPORT') ?: '5432'; // Usando 5432 como puerto predeterminado si no se configura
try {
    // Conectar a la base de datos PostgreSQL en Railway
    $pdo = new PDO("pgsql:host=$host;port=$port;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // Crear la tabla usuarios
    $sqlUsuarios = "CREATE TABLE IF NOT EXISTS usuarios (
                        id SERIAL PRIMARY KEY,
                        username VARCHAR(50) UNIQUE NOT NULL,
                        email VARCHAR(100) UNIQUE NOT NULL,
                        password VARCHAR(255) NOT NULL,
                        role VARCHAR(20) DEFAULT 'operador',
                        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
                    )";
    $pdo->exec($sqlUsuarios);
    echo "Tabla 'usuarios' creada exitosamente.<br>";

    // Crear la tabla products
    $sqlProducts = "CREATE TABLE IF NOT EXISTS products (
                        id SERIAL PRIMARY KEY,
                        nombre VARCHAR(100) NOT NULL,
                        marca VARCHAR(100) NOT NULL,
                        modelo VARCHAR(50) NOT NULL,
                        cantidad INTEGER NOT NULL,
                        ubicacion VARCHAR(50) NOT NULL,
                        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
                    )";
    $pdo->exec($sqlProducts);
    echo "Tabla 'products' creada exitosamente.<br>";

    // Crear la tabla auditoria
    $sqlAuditoria = "CREATE TABLE IF NOT EXISTS auditoria (
                         id SERIAL PRIMARY KEY,
                         usuario_id INT,
                         accion VARCHAR(255),
                         descripcion TEXT,
                         fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                         FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
                     )";
    $pdo->exec($sqlAuditoria);
    echo "Tabla 'auditoria' creada exitosamente.<br>";

    // Crear la tabla reportes
    $sqlReportes = "CREATE TABLE IF NOT EXISTS reportes (
                        id SERIAL PRIMARY KEY,
                        usuario_id INTEGER NOT NULL,
                        producto_id INTEGER NOT NULL,
                        accion VARCHAR(255) NOT NULL,
                        fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                        fecha_modificacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                        campo_modificado_anterior TEXT,
                        campo_modificado_nuevo TEXT,
                        FOREIGN KEY (usuario_id) REFERENCES usuarios(id),
                        FOREIGN KEY (producto_id) REFERENCES products(id)
                    )";
    $pdo->exec($sqlReportes);
    echo "Tabla 'reportes' creada exitosamente.<br>";

} catch (PDOException $e) {
    // En caso de error, mostrar mensaje
    echo 'Error de conexión: ' . $e->getMessage();
}
?>
