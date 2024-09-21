<?php
// Variables de entorno para Railway
$host = getenv('postgres.railway.internal');
$db = getenv('railway');
$user = getenv('postgres');
$pass = getenv('loGjAslKMQQGGzYpOPoUadWjLrLVfBPk');

try {
    $pdo = new PDO("pgsql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo 'Error de conexión: ' . $e->getMessage();
    exit();
}
?>