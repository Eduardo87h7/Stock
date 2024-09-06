<?php
session_start();
require 'config/db.php';

// Verificar si el usuario está autenticado
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Verificar si se enviaron datos del formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validar y limpiar los datos del formulario
    $product_name = isset($_POST['product_name']) ? trim($_POST['product_name']) : '';
    $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 0;
    $price = isset($_POST['price']) ? floatval($_POST['price']) : 0.00;

    // Validar los datos (puedes agregar más validaciones según sea necesario)
    if (!empty($product_name) && $quantity > 0 && $price > 0) {
        // Preparar la consulta SQL para insertar el nuevo producto
        $stmt = $pdo->prepare('INSERT INTO products (product_name, quantity, price) VALUES (?, ?, ?)');
        $stmt->execute([$product_name, $quantity, $price]);

        // Redirigir a la página de gestión de productos con un mensaje de éxito
        header('Location: manage_products.php?success=1');
        exit();
    } else {
        // Redirigir a la página de gestión de productos con un mensaje de error
        header('Location: manage_products.php?error=1');
        exit();
    }
} else {
    // Redirigir a la página de gestión de productos si se accede al script de forma incorrecta
    header('Location: manage_products.php');
    exit();
}
