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

    // ID del producto que deseas eliminar
    $producto_id = 7; // Cambia esto al ID del producto que quieres eliminar

    // 1. Eliminar los reportes que están relacionados con el producto
    $sqlDeleteReportes = "DELETE FROM reportes WHERE producto_id = :producto_id";
    $stmt = $pdo->prepare($sqlDeleteReportes);
    $stmt->execute([':producto_id' => $producto_id]);
    echo "Reportes relacionados eliminados exitosamente.<br>";

    // 2. Ahora eliminar el producto
    $sqlDeleteProduct = "DELETE FROM products WHERE id = :producto_id";
    $stmt = $pdo->prepare($sqlDeleteProduct);
    $stmt->execute([':producto_id' => $producto_id]);
    echo "Producto eliminado exitosamente.<br>";

} catch (PDOException $e) {
    // En caso de error, mostrar mensaje
    echo 'Error de conexión: ' . $e->getMessage();
}
?>