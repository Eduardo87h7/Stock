<?php
session_start();
require 'config/db.php';

// Verificar si el usuario está autenticado
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Agregar nuevo producto a la base de datos
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_name = $_POST['product_name'];
    $model = $_POST['model'];
    $quantity = $_POST['quantity'];

    try {
        // Preparar la consulta SQL sin incluir el campo 'id'
        $sql = "INSERT INTO products (product_name, model, quantity) VALUES (:product_name, :model, :quantity)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':product_name' => $product_name,
            ':model' => $model,
            ':quantity' => $quantity
        ]);

        // Redirigir a la página de gestión de productos después de la inserción
        header('Location: manage_products.php');
    } catch (PDOException $e) {
        echo 'Error: ' . $e->getMessage();
    }
}
?>
