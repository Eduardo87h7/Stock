<?php
$host = getenv('PGHOST');  // Obteniendo el host desde la variable de entorno
$db = getenv('PGDATABASE');    // Obteniendo el nombre de la base de datos
$user = getenv('PGUSER');  // Obteniendo el usuario desde la variable de entorno
$pass = getenv('PGPASSWORD');  // Obteniendo la contraseña desde la variable de entorno
$port = getenv('PGPORT') ?: '5432'; // Usando 5432 como puerto predeterminado si no se configura

try {
    $pdo = new PDO("pgsql:host=$host;port=$port;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo 'Error de conexión: ' . $e->getMessage();
    exit();
}
?>