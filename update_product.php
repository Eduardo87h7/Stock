<?php
session_start();
require 'config/db.php';

// Verificar si el usuario está autenticado
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Actualizar producto en la base de datos
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $product_name = $_POST['product_name'];
    $model = $_POST['model'];
    $quantity = $_POST['quantity'];

    $sql = "UPDATE products SET product_name = :product_name, model = :model, quantity = :quantity WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':product_name' => $product_name,
        ':model' => $model,
        ':quantity' => $quantity,
        ':id' => $id
    ]);

    header('Location: manage_products.php');
}
?>
