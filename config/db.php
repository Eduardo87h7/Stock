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

    // Modificar la clave foránea en la tabla reportes
    $sqlDropConstraint = "ALTER TABLE reportes DROP CONSTRAINT reportes_producto_id_fkey";
    $pdo->exec($sqlDropConstraint);

    $sqlAddConstraint = "ALTER TABLE reportes ADD CONSTRAINT reportes_producto_id_fkey FOREIGN KEY (producto_id) REFERENCES products(id) ON DELETE CASCADE";
    $pdo->exec($sqlAddConstraint);

    echo "Clave foránea modificada para usar ON DELETE CASCADE exitosamente.<br>";

} catch (PDOException $e) {
    // En caso de error, mostrar mensaje
    echo 'Error de conexión: ' . $e->getMessage();
}
?>