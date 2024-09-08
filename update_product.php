<?php
require 'config/db.php';
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Obtener los datos del formulario
$id = $_POST['id'];
$nombre = $_POST['nombre'];
$marca = $_POST['marca'];
$modelo = $_POST['modelo'];
$cantidad = $_POST['cantidad'];
$ubicacion = $_POST['ubicacion'];
$usuario_id = $_SESSION['user_id'];

// Obtener el estado anterior del producto
$stmt = $pdo->prepare('SELECT * FROM products WHERE id = ?');
$stmt->execute([$id]);
$producto = $stmt->fetch();

if ($producto) {
    // Registrar el cambio en la tabla reportes
    $stmt = $pdo->prepare('
        INSERT INTO reportes (usuario_id, producto_id, accion, campo_modificado_anterior, campo_modificado_nuevo, fecha_modificacion)
        VALUES (?, ?, ?, ?, ?, NOW())
    ');
    $stmt->execute([
        $usuario_id,
        $id,
        'Actualización',
        json_encode($producto),
        json_encode([
            'nombre' => $nombre,
            'marca' => $marca,
            'modelo' => $modelo,
            'cantidad' => $cantidad,
            'ubicacion' => $ubicacion
        ])
    ]);

    // Actualizar el producto en la base de datos
    $stmt = $pdo->prepare('
        UPDATE products
        SET nombre = ?, marca = ?, modelo = ?, cantidad = ?, ubicacion = ?
        WHERE id = ?
    ');
    $stmt->execute([$nombre, $marca, $modelo, $cantidad, $ubicacion, $id]);

    header('Location: manage_products.php');
} else {
    echo 'Producto no encontrado.';
}
?>
