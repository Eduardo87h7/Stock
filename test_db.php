<?php
// test_db.php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require 'config/db.php'; // Incluye el archivo de configuración de la base de datos

try {
    // Intenta realizar una consulta simple para verificar la conexión
    $stmt = $pdo->query('SELECT 1');

    // Si la consulta tiene éxito, muestra un mensaje de éxito
    echo 'Conexión a la base de datos establecida correctamente.';
} catch (PDOException $e) {
    // Si hay un error, muestra el mensaje de error
    echo 'Error de conexión: ' . $e->getMessage();
}
?>
