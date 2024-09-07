<?php
session_start();
require 'config/db.php';

// Verificar si el usuario está autenticado
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Procesar el formulario de edición de producto
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $nombre = $_POST['nombre'];
    $marca = $_POST['marca'];
    $modelo = $_POST['modelo'];
    $cantidad = $_POST['cantidad'];
    $calidad = $_POST['calidad'];

    try {
        $stmt = $pdo->prepare('UPDATE products SET nombre = :nombre, marca = :marca, modelo = :modelo, cantidad = :cantidad, calidad = :calidad WHERE id = :id');
        $stmt->execute([
            ':id' => $id,
            ':nombre' => $nombre,
            ':marca' => $marca,
            ':modelo' => $modelo,
            ':cantidad' => $cantidad,
            ':calidad' => $calidad,
        ]);

        header('Location: manage_products.php');
        exit();
    } catch (PDOException $e) {
        echo 'Error: ' . $e->getMessage();
    }
}
?>
