<?php
$host = 'postgres.railway.internal';
$dbname = 'railway'; // Usa solo el nombre de la base de datos
$user = 'postgres';
$pass = 'ItnteXFdmBscliKLFyezyeWLntEdbDAZ';

try {
    // Conectar a la base de datos PostgreSQL
    $pdo = new PDO("pgsql:host=$host;dbname=$dbname", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo 'Error de conexión: ' . $e->getMessage();
    exit();
}
?>
