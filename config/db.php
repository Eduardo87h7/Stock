<?php
// Tu archivo de conexión
$host = getenv('PGHOST');  
$db = getenv('PGDATABASE');    
$user = getenv('PGUSER');  
$pass = getenv('PGPASSWORD');  
$port = getenv('PGPORT') ?: '5432'; 

try {
    $pdo = new PDO("pgsql:host=$host;port=$port;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // Consulta de inserción masiva
    $sql = " delete from products;";
    // Ejecutar la consulta
    $pdo->exec($sql);
    echo "Ca,bios correctamente.";
} catch (PDOException $e) {
    echo "Error al insertar datos: " . $e->getMessage();
      }
      ?>
