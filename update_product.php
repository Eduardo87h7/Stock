<?php
require 'config/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = intval($_POST['id']);
    $nombre = $_POST['nombre'];
    $marca = $_POST['marca'];
    $modelo = $_POST['modelo'];
    $cantidad = intval($_POST['cantidad']);
    $calidad = $_POST['calidad'];

    $stmt = $pdo->prepare('UPDATE products SET nombre = ?, marca = ?, modelo = ?, cantidad = ?, calidad = ? WHERE id = ?');
    $stmt->execute([$nombre, $marca, $modelo, $cantidad, $calidad, $id]);

    header('Location: manage_products.php');
    exit();
}
?>
