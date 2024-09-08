<?php
session_start();
require 'config/db.php';

// Verificar si el usuario está autenticado
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Verificar si se recibió el ID
if (isset($_POST['id'])) {
    $id = $_POST['id'];
    $nombre = $_POST['nombre'];
    $marca = $_POST['marca'];
    $modelo = $_POST['modelo'];
    $cantidad = $_POST['cantidad'];
    $ubicacion = $_POST['ubicacion']; // Cambiado de 'calidad' a 'ubicacion'

    // Preparar la consulta SQL
    $sql = 'UPDATE products SET nombre = ?, marca = ?, modelo = ?, cantidad = ?, ubicacion = ? WHERE id = ?';
    $stmt = $pdo->prepare($sql);

    // Ejecutar la consulta
    if ($stmt->execute([$nombre, $marca, $modelo, $cantidad, $ubicacion, $id])) {
        header('Location: manage_products.php');
        exit();
    } else {
        echo 'Error al actualizar el producto.';
    }
} else {
    echo 'ID del producto no especificado.';
}
?>
