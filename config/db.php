<?php
$host = 'postgres.railway.internal';
$db = 'postgresql://postgres:ItnteXFdmBscliKLFyezyeWLntEdbDAZ@postgres.railway.internal:5432/railway';
$user = 'postgres';
$pass = 'ItnteXFdmBscliKLFyezyeWLntEdbDAZ';

try {
    $pdo = new PDO("pgsql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo 'Error de conexión: ' . $e->getMessage();
    exit();
}
?>
