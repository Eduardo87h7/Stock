<?php
session_start();
require 'config/db.php';

// Verificar si el usuario está autenticado
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $marca = $_POST['marca'];
    $modelo = $_POST['modelo'];
    $cantidad = $_POST['cantidad'];
    $ubicacion = $_POST['ubicacion'];
    $user_id = $_SESSION['user_id']; // ID del usuario que realiza la acción

    // Insertar el nuevo producto
    $stmt = $pdo->prepare('INSERT INTO products (nombre, marca, modelo, cantidad, ubicacion) VALUES (?, ?, ?, ?, ?)');
    $stmt->execute([$nombre, $marca, $modelo, $cantidad, $ubicacion]);

    // Obtener el ID del nuevo producto
    $product_id = $pdo->lastInsertId();

    // Registrar la acción en la tabla de reportes
    $stmt = $pdo->prepare('INSERT INTO reportes (usuario_id, producto_id, accion, fecha) VALUES (?, ?, ?, NOW())');
    $stmt->execute([$user_id, $product_id, 'agregar']);

    header('Location: manage_products.php');
    exit();
}
?>
